<?php

namespace App\Http\Controllers;

use Log;
use Carbon\Carbon;
use IntlDateFormatter;
use App\Models\Odcuser;
use App\Models\Activite;
use App\Models\Candidat;
use App\Models\Presence;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Border;
use App\Http\Requests\StoreCandidatRequest;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use App\Http\Requests\UpdateCandidatRequest;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use Illuminate\Support\Facades\Log as FacadesLog;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Fill as cellFill;
use PhpOffice\PhpSpreadsheet\Reader\Xml\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Alignment as cellAlignment;

class CandidatController extends Controller
{
    public function index() {}

    public function getCandidats($id_event)
    {
        $candidats = Candidat::where('event_id', $id_event)->where('status', '!=', 'accept')->latest()->get();
        return response()->json($candidats);
    }

    public function getParticipants(Request $request, $id)
    {
        try {
            $participants = Candidat::where('activite_id', $id)->where('status', 'accept')->select('id', 'odcuser_id', 'activite_id', 'status')->with(['odcuser', 'candidat_attribute'])->get();

            $etiquettes = [];
            $participantsData = [];

            if (count($participants) > 0) {
                foreach ($participants as $participant) {
                    $participantArray = $participant->toArray();

                    if ($participant->candidat_attribute) {
                        foreach ($participant->candidat_attribute as $attribute) {
                            $participantArray[$attribute->label] = $attribute->value;
                            if (!in_array($attribute->label, $etiquettes)) {
                                $etiquettes[] = $attribute->label;
                            }
                        }
                    }
                    $participantsData[] = $participantArray;
                }
            } else {
                $participantsData = null;
                $etiquettes = null;
            }

            $dataTable = DataTables::of($participantsData)
                ->editColumn('first_name', function ($participant) {
                    return $participant['odcuser']['first_name'];
                })
                ->editColumn('last_name', function ($participant) {
                    return $participant['odcuser']['last_name'];
                });

            foreach (array_unique($etiquettes) as $etiquette) {
                $dataTable->addColumn($etiquette, function ($participant) use ($etiquette) {
                    $value = isset($participant[$etiquette]) && $participant[$etiquette] !== '' ? $participant[$etiquette] : 'N/A';
                    return Str::of($value)->limit(45, '...') . '<span hidden>' . $value . '</span>' . (strlen($value) > 45 ? " <a href='#' onclick='readMore(event)'>Read more</a>" : '');
                })->escapeColumns([])
                    ->rawColumns([]);
            }

            $dataTable->addColumn('action', function ($participant) {
                return view('partials.action-btn-participants', ['participant' => $participant])->render();
            });

            $dataTable->addColumn('certificat', function ($participant) {
                return view('partials.generer-certificat', ['participant' => $participant])->render();
            });

            return $dataTable->toJson();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Une erreur est survenue : ' . $e->getMessage()], 500);
        }
    }

    public function generateExcel($id_event)
    {
        // Get the activite and its dates
        $activite = Activite::findOrFail($id_event);
        $start_date = Carbon::parse($activite->start_date);
        $end_date = Carbon::parse($activite->end_date);
        $dates = [];
        $fullDates = [];

        // Generate an array of dates between start and end dates
        for ($date = $start_date; $date->lte($end_date); $date->addDay()) {
            if (!$date->isWeekend()) {
                $dates[] = $date->format('d');
                $fullDates[] = $date->format('Y-m-d');
            }
        }
        $fmt = new IntlDateFormatter(
            'FR',
            IntlDateFormatter::SHORT,
            IntlDateFormatter::SHORT
        );
        $fmt->setPattern('EEEE');
        $days = [];
        foreach ($fullDates as $key => $value) {
            $timestamp = strtotime($value);
            $day = ucfirst($fmt->format($timestamp));
            $days[] = $day;
        }

        $totalDaysOfSheets = [];
        $daysOfSheet = [];

        foreach ($days as $day) {
            $daysOfSheet[] = $day;
            if ($day == 'Vendredi' || count($daysOfSheet) == 5) {
                $totalDaysOfSheets[] = $daysOfSheet;
                $daysOfSheet = [];
            }
        }
        if (!empty($daysOfSheet)) {
            $totalDaysOfSheets[] = $daysOfSheet;
        }

        // Get all candidats for the given event
        $candidats = Candidat::where('activite_id', $id_event)
            ->where('status', 'accept')
            ->with(['odcuser', 'candidat_attribute', 'presence'])
            ->get();

        // Initialize arrays for candidats data and labels
        $candidatsData = [];
        $labels = [];

        // Loop through each candidat and generate its data
        foreach ($candidats as $candidat) {
            $candidatArray = $candidat->toArray();

            // Add candidat attributes to the array
            if ($candidat->candidat_attribute) {
                foreach ($candidat->candidat_attribute as $attribute) {
                    $candidatArray[$attribute->label] = $attribute->value;
                    if (!in_array($attribute->label, $labels)) {
                        $labels[] = $attribute->label;
                    }
                }
            }

            // Get presences for the candidat
            $presences = Presence::where('candidat_id', $candidat->id)->get();
            $presence_dates = [];

            // Generate an array of presence dates
            foreach ($presences as $presence) {
                $presence_dates[] = date('Y-m-d', strtotime($presence->date));
            }

            // Add presence dates and state to the candidat array
            $candidatArray['date'] = $presence_dates;
            $presence_state = [];

            // Generate an array of presence states (1 or 0) for each date
            foreach ($fullDates as $date) {
                $presence_state[] = in_array($date, $presence_dates) ? 1 : "";
            }

            $candidatArray['presence_state'] = $presence_state;

            // Add the candidat array to the data array
            $candidatsData[] = $candidatArray;
        }

        // Create a new Spreadsheet object
        $spreadsheet = new Spreadsheet();

        $style_for_titles = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => cellAlignment::HORIZONTAL_CENTER,
            ],
        ];

        $columnIndex = 7; // Starting column index (H)
        $index = 7;
        foreach ($totalDaysOfSheets as $totalDaysOfSheet) {
            foreach ($totalDaysOfSheet as $daySheet) {

                $spreadsheet->getActiveSheet()
                    ->getPageSetup()
                    ->setHorizontalCentered(true)
                    ->setOrientation(PageSetup::ORIENTATION_LANDSCAPE)
                    ->setPaperSize(PageSetup::PAPERSIZE_A4);

                $spreadsheet->getActiveSheet()
                    ->getStyle('A1:' . $spreadsheet->getActiveSheet()->getHighestColumn() . $spreadsheet->getActiveSheet()->getHighestRow())
                    ->getFont()
                    ->setName('Arial')
                    ->setSize(8);

                // Set the title and merge cells
                $spreadsheet->getActiveSheet()
                    ->setCellValue('A1', "FICHE DES CANDIDATS POUR " . strtoupper($activite->title))
                    ->mergeCells('A1:U2')
                    ->getStyle('A1:U2')
                    ->getAlignment()->setVertical(cellAlignment::VERTICAL_CENTER)
                    ->setHorizontal(cellAlignment::HORIZONTAL_CENTER);

                $spreadsheet->getActiveSheet()
                    ->getStyle('A1:' . $spreadsheet->getActiveSheet()->getHighestColumn() . $spreadsheet->getActiveSheet()->getHighestRow())
                    ->getBorders()
                    ->getAllBorders()
                    ->setBorderStyle(Border::BORDER_THIN);

                $spreadsheet->getActiveSheet()->getPageMargins()->setTop(0.8);
                $spreadsheet->getActiveSheet()->getPageMargins()->setRight(0.1);
                $spreadsheet->getActiveSheet()->getPageMargins()->setLeft(0.1);

                // Set the column headers
                $spreadsheet->getActiveSheet()
                    ->setCellValue('A3', 'N°')
                    ->setCellValue('B3', 'NOM')
                    ->setCellValue('C3', 'PRENOM')
                    ->setCellValue('D3', 'GENRE')
                    ->setCellValue('E3', 'NUMERO')
                    ->setCellValue('F3', 'EMAIL');

                $spreadsheet->getActiveSheet()->getStyle('A1:S3')->applyFromArray($style_for_titles);

                // Set the column widths
                $spreadsheet->getActiveSheet()
                    ->getColumnDimension('A')
                    ->setAutoSize(false)
                    ->setWidth(25, 'px');
                $spreadsheet->getActiveSheet()
                    ->getColumnDimension('B')
                    ->setAutoSize(false)
                    ->setWidth(120, 'px');
                $spreadsheet->getActiveSheet()
                    ->getColumnDimension('C')
                    ->setAutoSize(false)
                    ->setWidth(75, 'px');
                $spreadsheet->getActiveSheet()
                    ->getColumnDimension('D')
                    ->setAutoSize(false)
                    ->setWidth(40, 'px');
                $spreadsheet->getActiveSheet()
                    ->getColumnDimension('E')
                    ->setAutoSize(false)
                    ->setWidth(80, 'px');
                $spreadsheet->getActiveSheet()
                    ->getColumnDimension('F')
                    ->setAutoSize(false)
                    ->setWidth(160, 'px');

                // Set the fill color for the header row
                $spreadsheet->getActiveSheet()->getStyle('A4:F4')
                    ->getFill()->setFillType(cellFill::FILL_SOLID);
                $spreadsheet->getActiveSheet()->getStyle('A4:F4')
                    ->getFill()->getStartColor()->setARGB('000000');

                // Loop through each candidat and set its data
                $cell_key = 5;
                $i = 1;
                foreach ($candidatsData as $candidat) {
                    $spreadsheet->getActiveSheet()
                        ->setCellValue("A$cell_key", "$i");

                    $name = isset($candidat['Nom']) ? $candidat['Nom'] : $candidat['odcuser']['last_name'];
                    $spreadsheet->getActiveSheet()
                        ->setCellValue("B$cell_key", $name);

                    $first_name = isset($candidat['Prénom']) ? $candidat['Prénom'] : $candidat['odcuser']['first_name'];
                    $spreadsheet->getActiveSheet()
                        ->setCellValue("C$cell_key", $first_name);

                    $spreadsheet->getActiveSheet()
                        ->setCellValue("D$cell_key", $candidat['odcuser']['gender']);

                    $phone = isset($candidat['Téléphone']) ? $candidat['Téléphone'] : '';
                    $spreadsheet->getActiveSheet()
                        ->setCellValue("E$cell_key", $phone)
                        ->getCell("E$cell_key")
                        ->setValueExplicit($phone, DataType::TYPE_STRING)
                        ->getStyle()
                        ->getAlignment()
                        ->setHorizontal(cellAlignment::HORIZONTAL_RIGHT);

                    $email = isset($candidat['E-mail']) ? $candidat['E-mail'] : $candidat['odcuser']['email'];
                    $spreadsheet->getActiveSheet()
                        ->setCellValue("F$cell_key", $email);

                    $i++;
                    $cell_key++;
                }

                $cellCoordinate = Coordinate::stringFromColumnIndex($columnIndex) . '3';
                $spreadsheet->getActiveSheet()
                    ->setCellValue($cellCoordinate, $daySheet)
                    ->mergeCells($cellCoordinate . ":" . Coordinate::stringFromColumnIndex($columnIndex + 2) . "3")
                    ->getStyle($cellCoordinate . ":" . Coordinate::stringFromColumnIndex($columnIndex + 2) . "3")
                    ->getAlignment()
                    ->setVertical(cellAlignment::VERTICAL_CENTER)
                    ->setHorizontal(cellAlignment::HORIZONTAL_CENTER);
                $columnIndex += 3; // Increment column index by 3 (since we're merging 3 cells)

                $cellC = Coordinate::stringFromColumnIndex($index);

                $spreadsheet->getActiveSheet()
                    ->getColumnDimension(Coordinate::stringFromColumnIndex($index))
                    ->setAutoSize(false)
                    ->setWidth(30, 'px');

                $spreadsheet->getActiveSheet()
                    ->getColumnDimension(Coordinate::stringFromColumnIndex($index + 1))
                    ->setAutoSize(false)
                    ->setWidth(30, 'px');

                $spreadsheet->getActiveSheet()
                    ->getColumnDimension(Coordinate::stringFromColumnIndex($index + 2))
                    ->setAutoSize(false)
                    ->setWidth(30, 'px');

                $spreadsheet->getActiveSheet()
                    ->setCellValue($cellC . "4", "H/E");
                $spreadsheet->getActiveSheet()
                    ->setCellValue(Coordinate::stringFromColumnIndex($index + 1) . "4", "H/S");
                $spreadsheet->getActiveSheet()
                    ->setCellValue(Coordinate::stringFromColumnIndex($index + 2) . "4", "Sign");

                $spreadsheet->getActiveSheet()
                    ->getStyle($cellC . "4:" . Coordinate::stringFromColumnIndex($index + 2) . "4")
                    ->getAlignment()
                    ->setVertical(cellAlignment::VERTICAL_CENTER)
                    ->setHorizontal(cellAlignment::HORIZONTAL_CENTER);
                $index += 3;
            }
            // Create a new sheet for each chunk
            $spreadsheet->createSheet();
            $spreadsheet->setActiveSheetIndex($spreadsheet->getActiveSheetIndex() + 1);
            $columnIndex = 7;
            $index = 7;
        }

        // Create a writer for the Spreadsheet
        $writer = new Xlsx($spreadsheet);

        // Create a temporary file for the Excel file
        $tmp = tempnam(sys_get_temp_dir(), "Participants.Xlsx");

        // Save the Excel file to the temporary file
        $writer->save($tmp);

        // Return the Excel file as a download response
        return response()->download($tmp, "Participants.Xlsx")->deleteFileAfterSend(true);
    }

    public function updateStatus(Request $request, $status)
    {
        // Validate the status
        if (!in_array($status, ['accept', 'decline', 'wait'])) {
            return response()->json(['error' => 'Invalid status'], 400);
        }

        // Get the IDs from the request
        $ids = $request->input('ids');

        if (!is_array($ids) || empty($ids)) {
            return response()->json(['error' => 'Désolé, une erreur s\'est produite lors de la mise à jour des candidats sélectionnés'], 400);
        }

        // Find the candidats
        $candidats = Candidat::whereIn('id', $ids)->get();

        if ($candidats->isEmpty()) {
            return response()->json(['error' => 'Aucun candidat n\' a été trouvé'], 404);
        }

        // Update the status for each candidat
        foreach ($candidats as $candidat) {
            $candidat->status = $status;
            $candidat->save();
        }

        return response()->json(['message' => count($candidats) . ' candidat(s) mis à jour avec succès !'], 200);
    }

    public function store(Request $request)
    {
        FacadesLog::info('storeCandidats called');
        // Validate the incoming request data
        $validatedData = $request->validate([
            'odcuser_id' => 'required',
            'activite_id' => 'required'
        ]);

        // Find the Odcuser and Activite by their respective _id fields
        $odcuser = Odcuser::where('_id', $validatedData['odcuser_id'])->first();
        $activite = Activite::where('_id', $validatedData['activite_id'])->first();

        if ($odcuser && $activite) {
            // Create a new Candidat record
            $candidat = Candidat::firstOrCreate([
                'odcuser_id' => $odcuser->id,
                'activite_id' => $activite->id,
                'status' => 'new'

            ]);
            FacadesLog::warning('User or Event not found', ['odcuser_id' => $candidat['odcuser_id'], 'activite_id' => $candidat['activite_id']]);
            return response()->json(['success' => true, 'candidat' => $candidat], 200);
        } else {
            return response()->json(['success' => false, 'message' => 'User or Event not found'], 303);
        }
    }
}
