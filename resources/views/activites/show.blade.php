<x-app-layout>
    <!-- Display errors if any -->
    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-red-400"
                role="alert">
                <span class="font-medium">{{ $error }}</span>
            </div>
        @endforeach
    @endif

    <!-- Header section -->
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <!-- Title of the page -->
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __($activite->title) }}
                </h2>
            </div>
        </div>
    </x-slot>

    @if (Session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400"
            role="alert">
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    <!-- Tab navigation -->
    <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
        <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" id="default-styled-tab"
            data-tabs-toggle="#default-styled-tab-content"
            data-tabs-active-classes="text-purple-600 hover:text-purple-600 dark:text-purple-500 dark:hover:text-purple-500 border-purple-600 dark:border-purple-500"
            data-tabs-inactive-classes="dark:border-transparent text-gray-500 hover:text-gray-600 dark:text-gray-400 border-gray-100 hover:border-gray-300 dark:border-gray-700 dark:hover:text-gray-300"
            role="tablist">
            <li class="me-2" role="presentation">
                <button class="inline-block p-4 border-b-2 rounded-t-lg" id="profile-styled-tab"
                    data-tabs-target="#styled-profile" type="button" role="tab" aria-controls="profile"
                    aria-selected="false">Detail</button>
            </li>
            <li class="me-2" role="presentation">
                <button
                    class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                    id="dashboard-styled-tab" data-tabs-target="#styled-dashboard" type="button" role="tab"
                    aria-controls="dashboard" aria-selected="false">Candidats</button>
            </li>
            <li class="me-2" role="presentation">
                <button
                    class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                    id="dashboard-styled-tab" data-tabs-target="#participants-tab" type="button" role="tab"
                    aria-controls="dashboard" aria-selected="false">Participants</button>
            </li>
            <li class="me-2" role="presentation">
                <button
                    class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                    id="presence-styled-tab" data-tabs-target="#content-presence" type="button" role="tab"
                    aria-controls="presence" aria-selected="false">Presence</button>
            </li>
            <li class="me-2" role="presentation">
                <button
                    class="inline-block p-4 border-b-2 rounded-t-lg hover:text-gray-600 hover:border-gray-300 dark:hover:text-gray-300"
                    id="import-styled-tab" data-tabs-target="#import" type="button" role="tab"
                    aria-controls="Importation" aria-selected="false">Import</button>
            </li>
        </ul>
    </div>

    <!-- Tab content -->
    <div id="default-styled-tab-content">
        <!-- Show activity details -->
        <x-activitesShow :datachart="$datachart" :show="$activite" />

        <!-- Show candidates for the activity -->
        <x-show-candidates-event :activite="$activite" :labels="$labels" :candidatsData="$candidatsData" :odcusers="$odcusers"
            :activite_Id="$activite_Id" :id="$id" />

        <!-- Participants tab content -->
        <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="participants-tab" role="tabpanel"
            aria-labelledby="participants-tab">
            <x-show-participants-event :participantsData="$participantsData" :activite="$activite" :labels="$labels" :candidatsData="$candidatsData"
                :odcusers="$odcusers" :activite_Id="$activite_Id" :id="$id" :modelMail="$modelMail" />
        </div>

        <!-- Presence tab content -->
        <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="content-presence" role="tabpanel"
            aria-labelledby="settings-tab">
            <x-activite-presence-component :fullDates="$fullDates" :dates="$dates" :data="$data" :presences="$presences"
                :countdate="$countdate" :activite="$activite" :candidats="$candidats" />
        </div>

        <!-- import tab content -->
        <div class="hidden p-4 rounded-lg bg-gray-50 dark:bg-gray-800" id="import" role="tabpanel"
            aria-labelledby="contacts-tab">
            <p class="text-sm text-gray-500 dark:text-gray-400"><x-activite-import :activite="$activite" /></p>
        </div>
    </div>



    @php
        $url = env('API_URL');
    @endphp

    @section('script')
        <script>
            let selectAllCheckbox = document.getElementById('select-all');
            let selectedCandidats = new Set(); // Pour stocker les IDs sélectionnés
            let rowCheckboxes = document.querySelectorAll('.row-select');
            let selectedCountDisplay = document.createElement('span');
            selectedCountDisplay.className = "text-gray-200 ms-5";
            selectedCountDisplay.id = "selected-count";

            $(document).ready(function() {
                // Fonction pour mettre à jour l'affichage des boutons et le compte des sélections
                function updateSelectionDisplay() {
                    const selectedCount = selectedCandidats.size; // Utiliser la taille du Set
                    selectedCountDisplay.textContent = selectedCount ? `${selectedCount} ligne(s) sélectionnée(s)` : '';

                    // Afficher ou cacher les boutons
                    if (selectedCount > 0) {
                        $('#acceptAllBtn').removeClass('hidden');
                        $('#rejectAllBtn').removeClass('hidden');
                        $('#awaitAllBtn').removeClass('hidden');
                    } else {
                        $('#acceptAllBtn').addClass('hidden');
                        $('#rejectAllBtn').addClass('hidden');
                        $('#awaitAllBtn').addClass('hidden');
                    }

                    // Mettre à jour l'affichage du compteur
                    $('#candidatTable_info').append(selectedCountDisplay);
                }

                // Événement pour le checkbox "Sélectionner tout"
                selectAllCheckbox.addEventListener('change', function() {
                    rowCheckboxes.forEach(checkbox => {
                        checkbox.checked = selectAllCheckbox.checked;
                        const id = checkbox.dataset.id; // Supposant que chaque checkbox a un data-id
                        if (selectAllCheckbox.checked) {
                            selectedCandidats.add(id); // Ajouter à l'ensemble si sélectionné
                        } else {
                            selectedCandidats.delete(id); // Retirer de l'ensemble si désélectionné
                        }
                    });
                    updateSelectionDisplay(); // Mettre à jour l'affichage après changement
                });

                // Événement pour les checkboxes de chaque ligne
                rowCheckboxes.forEach(checkbox => {
                    checkbox.addEventListener('change', function() {
                        const id = checkbox.dataset.id; // Supposant que chaque checkbox a un data-id
                        if (checkbox.checked) {
                            selectedCandidats.add(id); // Ajouter à l'ensemble si coché
                        } else {
                            selectedCandidats.delete(id); // Retirer de l'ensemble si décoché
                        }
                        // Vérifier si "Sélectionner tout" doit être cochée ou décochée
                        selectAllCheckbox.checked = Array.from(rowCheckboxes).every(cb => cb.checked);
                        updateSelectionDisplay(); // Mettre à jour l'affichage après changement
                    });
                });

                // Gérer la mise à jour du statut des candidats sélectionnés
                $('#acceptAllBtn, #rejectAllBtn, #awaitAllBtn').on('click', function() {
                    const action = $(this).data('status');; // Récupérer l'action (accept, reject, wait)
                    const candidats = Array.from(selectedCandidats); // Convertir le Set en tableau
                    //tr = $(event.target.closest('tr'));
                    //let statusCell = tr.find('#statusCell');

                    if (candidats.length) {
                        $.ajax({
                            url: `/candidat/${action}`,
                            type: 'POST',
                            contentType: 'application/json',
                            data: JSON.stringify({
                                ids: candidats
                            }),
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            },
                            success: function(data) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Succès',
                                    text: data.message,
                                    timer: 2000,
                                    timerProgressBar: true,
                                });
                                $(statusCell[0]).text(action);
                                // Optionnel : Réinitialiser la sélection après succès
                                selectedCandidats.clear(); // Réinitialiser le Set
                                updateSelectionDisplay(); // Mettre à jour l'affichage
                            },
                            error: function(xhr) {
                                const errorMessage = xhr.responseJSON?.error ||
                                    'Une erreur est survenue';
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erreur',
                                    text: errorMessage,
                                });
                            }
                        });
                    }
                });
            });
        </script>

        {{-- Script for presence data table --}}

        <script>
            $(document).ready(function() {
                $('#candidatpresence').DataTable({
                    "scrollX": true,
                    "fixedColumns": {
                        "start": 3
                    }
                });

                $('#candidatpresence').css('width', '100%');
            });
        </script>

        {{-- Script for participants data table --}}
        <script>
            function generer(event) {
                event.preventDefault()
                $('#loading').removeClass('hidden');
                $('#loading').addClass('inline');

                let id_event = @json($id);
                try {
                    setTimeout(function() {
                        window.location.href = "{{ route('generate_excel', '') }}/" + id_event;
                    }, 1000);

                    setTimeout(function() {
                        $('#loading').addClass('hidden');
                        $('#loading').removeClass('inline');
                    }, 2000);

                } catch (error) {
                    console.log(error)
                }
            }


            $(document).ready(function() {
                let event = @json($activite->title);
                var id_event = @json($activite->id);

                $('#participantTable').DataTable({
                    lengthMenu: [
                        [10, 25, 50, -1],
                        [10, 25, 50, "Tout"]
                    ],
                    columnDefs: [{
                        targets: '.label',
                        visible: false
                    }],
                    language: {
                        lengthMenu: 'Afficher _MENU_',
                        info: 'Affichage de la page _PAGE_ sur _PAGES_',
                        search: 'Recherche : ',
                        infoEmpty: "Affichage de 0 participants sur 0",
                        emptyTable: 'Aucun participant n\'a été trouvé sur cette activité !',
                        loadingRecords: 'Chargment des participants...',
                        zeroRecords: 'Aucun participant correspondant à votre recherche n\'a été trouvé',
                        aria: {
                            "orderable": "Trier sur cette colonne",
                            "orderableReverse": "Inverser l'ordre de tri de cette colonne"
                        }
                    }
                });

                $('body').on('click', function(event) {
                    if (!$(event.target).closest('.modalp, .btnModal').length) {
                        $('.modalp').hide();
                    }
                });


                $(document).on('click', '.btnModal', function(event) {
                    event.stopPropagation();
                });

                $('#participantTable').css('width', '100%');
                $("#dt-length-1").addClass('text-gray-700 dark:text-gray-200 w-24 bg-white')

            });
        </script>

        {{-- Script for candidates data table --}}
        <script>
            var tr = null;
            var statusCell = null;

            function readMore(event) {
                event.preventDefault();
                let value = event.target.previousElementSibling.innerHTML;

                var td = $(event.target).closest('td');
                $(td).text(value);
            }

            $(document).ready(function() {
                let event = @json($activite->title);
                var id_event = @json($activite->id);

                $('#candidatTable').DataTable({
                    pageLength: 10,
                    lengthMenu: [
                        [10, 25, 50, -1],
                        [10, 25, 50, "Tous"]
                    ],
                    columnDefs: [{
                        targets: '.label',
                        visible: false
                    }],
                    language: {
                        lengthMenu: 'Afficher _MENU_',
                        info: 'Affichage de la page _PAGE_ sur _PAGES_',
                        search: 'Recherche : ',
                        infoEmpty: "Affichage de 0 candidats sur 0",
                        emptyTable: 'Aucun candidat n\'a été trouvé sur cette activité !',
                        loadingRecords: 'Chargment des candidats...',
                        zeroRecords: 'Aucun candidat correspondant à votre recherche n\'a été trouvé',
                        aria: {
                            "orderable": "Trier sur cette colonne",
                            "orderableReverse": "Inverser l'ordre de tri de cette colonne"
                        }
                    },
                });


                $(document).on('click', '.btnAction', function(event) {
                    let id = $(this).data('dropdown-toggle');
                    $('.modal').not('#' + id).hide();
                    $('#' + id).toggle();
                    event.stopPropagation();
                });

                $('body').on('click', function(event) {
                    if (!$(event.target).closest('.modal, .btnAction').length) {
                        $('.modal').hide();
                    }
                });


                $(document).on('click', '.modal', function(event) {
                    event.stopPropagation();
                });


                $('#candidatTable').css('width', '100%');

                $('.dt-container').addClass('text-lg text-gray-800 dark:text-gray-400 leading-tight')

                $('.dt-buttons').addClass('mt-4')

                $('.dt-buttons buttons').addClass(
                    'cursor-pointer mt-5 bg-slate-600 p-2 rounded-sm font-bold')

                $("#dt-length-2").addClass('text-gray-700 dark:text-gray-200 w-24 bg-white')

            })

            function tooltip(event) {
                event.preventDefault();
                const tooltip = document.getElementById('tooltip');
                $(tooltip).show();
            }

            function remove(event, id) {
                alert(4)
                event.preventDefault();
                let status = 'decline';
                $('#popup-title-decline').text(
                    "Confirmez-vous le retrait de " + firstname + " ?");
                $.ajax({
                    type: 'POST',
                    url: '/candidat/' + status,
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        // Update the UI or display a success message
                        // Update the table cell with the new status
                        $(statusCell[0]).text(status);
                        console.log('Status updated successfully!');
                    },
                    error: function(xhr, status, error) {
                        console.log('Error updating status: ' + error);
                    }
                });
            }

            function actionStatus(event, type, id, firstname, lastname) {
                tr = $(event.target.closest('tr'));
                statusCell = tr.find('#statusCell');

                switch (type) {
                    case 'accept':
                        $('#accept-link').attr('data', id)
                        $('#popup-title-accept').text(
                            "Confirmez-vous la validation de la candidature de " + firstname + " ?")
                        document.getElementById('first-modal').click()
                        break;
                    case 'decline':
                        $('#decline-link').attr('data', id)
                        $('#popup-title-decline').text(
                            "Confirmez-vous l'annulation de la candidature de " + firstname + " ?");
                        document.getElementById('second-modal').click()
                        break;
                    case 'wait':
                        $('#wait-link').attr('data', id)
                        $('#popup-title-wait').text(
                            "Confirmez-vous la mise en attente de " + firstname + " ?")
                        document.getElementById('third-modal').click()
                    default:
                        break;
                }


            }

            function changeStatus(event, status) {
                event.preventDefault();

                let id = $(event.target).attr('data')
                $.ajax({
                    type: 'POST',
                    url: '/candidat/' + status,
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        // Update the UI or display a success message
                        // Update the table cell with the new status
                        $(statusCell[0]).text(status);
                        $('#alert-success').addClass('flex')
                        $('#alert-success').removeClass('hidden')
                        $('#div-success').text(data.message)

                        console.log('Status updated successfully!');
                    },
                    error: function(xhr, status, error) {
                        console.log('Error updating status: ' + error);
                    }
                });
            }
        </script>

        {{-- Script for storing candidates --}}
        <script>
            let url = "{!! $url !!}"
            let id = @json($id);
            let idEvent = "{!! $activite_Id !!}"
            let idUsers = @json($odcusers);
            let user = {};
            // Initialize the user object with pairs of `_id` and `id`
            idUsers.forEach(element => {
                user[element._id] = element.id;
            });

            function Reload() {
                fetch(`${url}/events/show/${idEvent}`)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        let events = data.data;
                        events.forEach(event => {
                            let userId = event.user._id;
                            if (user.hasOwnProperty(userId)) {
                                // Add an object to the candidates array with `odcuser_id` containing `id`
                                let candidat = {
                                    'odcuser_id': user[userId],
                                    'activite_id': id, // or use event.activite_id if available
                                    'status': 1
                                };
                                console.log(candidat)
                                storeCandidats(candidat);
                            }
                        });
                    })
                    .catch(error => {
                        console.error('There was a problem with the fetch operation:', error);
                    });
            }

            function storeCandidats(candidat) {
                fetch('/api/candidat', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(candidat)
                    })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Network response was not ok');
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Candidats stored successfully:', data);
                    })
                    .catch(error => {
                        console.error('There was a problem with the fetch operation:', error);
                    });
            }

            function redirectToPresence() {
                window.location.href = '{{ route('presences.index') }}';
            }
        </script>
        <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>


        <script>
            function activer(event) {
                event.preventDefault();
                let link = event.target.getAttribute('href');
                document.querySelector('#desactiveStatus form').setAttribute('action', link);
            }

            function desactiver(event) {
                event.preventDefault();
                let link = event.target.getAttribute('href');
                document.querySelector('#activeStatus form').setAttribute('action', link);
            }
        </script>
        <script>
            const getChartOptions = () => {
                return {
                    series: [

                        @json([$datachart->sum('total_filles')]), // Total des filles
                        @json([$datachart->sum('total_garcons')]),
                        // Total des garçons
                    ],
                    colors: ["#1C64F2", "#16BDCA", "#FDBA8C"],
                    chart: {
                        height: "380px",
                        width: "100%",
                        type: "radialBar",
                        sparkline: {
                            enabled: true,
                        },
                    },
                    stroke: {
                        colors: ["transparent"],
                        lineCap: "round", // Use 'round' for the end caps of the radial bar
                    },
                    plotOptions: {
                        radialBar: {
                            track: {
                                background: '#E5E7EB',
                            },
                            dataLabels: {
                                show: false,
                            },
                            hollow: {
                                margin: 0,
                                size: "32%",
                            },
                            donut: {
                                labels: {
                                    show: true,
                                    name: {
                                        show: true,
                                        fontFamily: "Inter, sans-serif",
                                        offsetY: 20,
                                    },

                                    value: {
                                        show: true,
                                        fontFamily: "Inter, sans-serif",
                                        offsetY: -20,
                                        formatter: function(value) {
                                            return value; // Afficher la valeur brute
                                        },
                                    },
                                },
                                size: "70%",
                            },
                        },
                    },
                    grid: {
                        show: false,
                        strokeDashArray: 4,
                        padding: {
                            left: 2,
                            right: 2,
                            top: -23,
                            bottom: -20,
                        },
                    },
                    labels: ["Femme", "Homme"], // Étiquettes pour les séries
                    dataLabels: {
                        enabled: false,
                    },
                    legend: {

                        show: true,
                        position: "bottom",
                        fontFamily: "Inter, sans-serif",
                    },
                    tooltip: {
                        enabled: true,
                        x: {
                            show: false,
                        },
                    },
                    yaxis: {
                        show: false,

                    }
                }
            }

            document.addEventListener('DOMContentLoaded', function() {
                const options = getChartOptions();
                const chart = new ApexCharts(document.querySelector("#chart"), options);
                chart.render();
            });
        </script>

        </script>
        {{-- pour choisir le certificat a generer --}}
        <script>
            function choix_certificat(event) {
                event.preventDefault();
                const lien = event.target.getAttribute("href");
                document.querySelector("#choixCertificat-modal form").setAttribute("action", lien);
            }
        </script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('model-mail').addEventListener('change', function() {
                    var selectedMessage = this.value;
                    document.getElementById('message').value = selectedMessage;
                });
            });
        </script>
    @endsection
</x-app-layout>
