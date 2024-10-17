<?php

namespace App\Http\Controllers\Api;

use App\Models\Odcuser;
use App\Models\Activite;
use App\Models\Candidat;
use Illuminate\Http\Request;
use App\Models\CandidatAttribute;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CandidatController extends Controller
{
    public function index()
    {
        $candidat = Candidat::all();
        return response()->json($candidat);
    }

    public function showDetail(Request $request)
    {
        $candidat = Candidat::findOrFail($request->candidat_id);
        $odcuser = Odcuser::find($candidat->odcuser_id);

        if (!$odcuser) {
            return response()->json(['message' => 'Odcuser not found'], 404);
        }

        $nbrEvents = Candidat::where('odcuser_id', $odcuser->id)->count();
        $nbrParticipation = $odcuser->candidat()->where('status', 'accept')->count();

        $userId = $odcuser->id;
        $activitesP = DB::select(
            '
            SELECT act.*, cat.name
            FROM activites act
            JOIN candidats c ON act.id = c.activite_id
            JOIN categories cat ON act.categorie_id = cat.id
            WHERE c.odcuser_id = ? AND (c.status = ?)
            ORDER BY c.createdAt
            LIMIT 3',
            [$userId, 'accept']
        );

        $activitespAll = DB::select(
            '
           SELECT act.*, cat.name
            FROM activites act
            JOIN candidats c ON act.id = c.activite_id
            JOIN categories cat ON act.categorie_id = cat.id
            WHERE c.odcuser_id = ? AND (c.status = ?)
            ORDER BY c.createdAt
            LIMIT 3',
            [$userId, 'accept']
        );

        $activitesC = DB::select(
            '
            SELECT act.*, cat.name
            FROM activites act
            JOIN categories cat ON act.categorie_id = cat.id
            JOIN candidats c ON act.id = c.activite_id
            WHERE c.odcuser_id = ?
            ORDER BY c.createdAt
            ',
            [$userId]
        );

        $candidats = Candidat::where('odcuser_id', $userId)->with('candidat_attribute')->get();
        if (isset($candidats)) {
            $odcuserDatas = [];
            $labels = [];
            foreach ($candidats as $candidat) {
                $array = $candidat->toArray();
                if (isset($candidat->candidat_attribute)) {
                    foreach ($candidat->candidat_attribute as $attribute) {
                        $array[$attribute->label] = $attribute->value;
                        if (!in_array($attribute->label, $labels)) {
                            $labels[] = $attribute->label;
                        }
                    }
                    $odcuserDatas[] = $array;
                }
            }
        }

        // Rendre le composant et retourner en tant que HTML
        $view = view('components.detail-user', compact( 'activitespAll', 'nbrParticipation', 'activitesC', 'activitesP', 'odcuser', 'candidats', 'nbrEvents', 'odcuserDatas', 'labels'))->render();
        return response()->json(['html' => $view]);
    }

    public function store(Request $request)
    {
        // Validation des données de la requête
        $validatedData = $this->validateRequest($request);

        try {
            // Créer un nouvel enregistrement Candidat ou récupérer celui existant
            $candidat = Candidat::firstOrCreate([
                'odcuser_id' => $validatedData['odcuser_id'],
                'activite_id' => $validatedData['activite_id'],
                'status' => 'new'
            ]);

            return response()->json(['success' => true, 'candidat' => $candidat], 200);
        } catch (\Exception $e) {
            // Gestion des exceptions, retour d'un message d'erreur
            return response()->json(['success' => false, 'message' => 'Erreur lors de la création du candidat: ' . $e->getMessage()], 500);
        }
    }

    // Méthode pour valider les données de la requête
    protected function validateRequest(Request $request)
    {
        return $request->validate([
            'odcuser_id' => 'required',
            'activite_id' => 'required'
        ]);
    }

    public function storeAttributes(Request $request)
    {
        try {
            $candidatAttributes = CandidatAttribute::firstOrCreate([
                '_id' => $request->_id,
                'label' => $request->label,
                'value' => $request->value,
                'candidat_id' => $request->candidat_id
            ]);
            return response()->json(['success' => true, 'candidat_attributes' => $candidatAttributes], 200);
        } catch (\Throwable $th) {
            return response()->json(['error' => 'Une erreur s\'est produite lors du stockage des attributs.']);
        }
    }

    public function show(Candidat $candidat)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Candidat $candidat)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Candidat $candidat)
    {
        //
    }
}
