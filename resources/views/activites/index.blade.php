<x-app-layout>

    @if ($errors->any())
        @foreach ($errors->all() as $error)
            <div class="p-4 mb-4 text-sm text-red-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-red-400"
                role="alert">

                <span class="font-medium">{{ $error }}</span>

            </div>
        @endforeach
    @endif

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Gestion Activites') }}
                </h2>
            </div>



        </div>

    </x-slot>

    <div class=" mb-4 mt-4 text-white">

        <a class=" cursor-pointer mt-5 bg-slate-600 p-2 rounded-sm font-bold"
            href="{{ route('activites.create') }}">Create Activites</a>


    </div>

    @if (Session('success'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400"
            role="alert">
            <span class="font-medium">{{ session('success') }}</span>
        </div>
    @endif

    {{-- <div class="relative overflow-x-auto mt-4" id="selecteurTab">
        <x-tableactivites :activites="$activites" />
    </div> --}}

    <div class="container bg-gray-500 darj p-4 rounded-lg bg-opacity-5 relative">
        <table id="table"
            class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 cell-border display">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3">Id</th>
                    <th scope="col" class="px-6 py-3">Title</th>
                    <th scope="col" class="px-6 py-3">Categories</th>
                    <th scope="col" class="px-6 py-3">Type Event</th>
                    <th scope="col" class="px-6 py-3">Lieu</th>
                    <th scope="col" class="px-6 py-3">Teming</th>
                    <th scope="col" class="px-6 py-3">Status</th>
                    <th scope="col" class="px-6 py-3">Book In seat</th>
                    <th scope="col" class="px-6 py-3">Number Hours</th>
                    <th scope="col" class="px-6 py-3">Date Début</th>
                    <th scope="col" class="px-6 py-3">Date Fin</th>
                    <th scope="col" class="px-6 py-3">Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>



    <x-statusactive :name="__('Would you like show in calendar this activity? ')" />

    <x-statusdesactive :name="__('Would you like disable in calendar this activity? ')" />

    <x-delete :name="__('Would you like disable in calendar this activity? ')" />

    @section('script')
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
            function destroy(event) {
                event.preventDefault();
                let link = event.target.getAttribute('href');
                document.querySelector('.delete').setAttribute('action', link);
            }

            $(document).ready(function() {
                $('.dt-container').addClass('text-xl text-gray-800 dark:text-gray-200 leading-tight')

                $('.dt-buttons').addClass('mt-4')

                $('.dt-buttons buttons').addClass('cursor-pointer mt-5 bg-slate-600 p-2 rounded-sm font-bold')
            })
        </script>

        <script>
            $(document).ready(function() {
                $('#table').on('click', '.btn-menu', function() {
                    const lien = $(this).attr('data-dropdown-toggle')
                    $('#' + lien).fadeToggle('fast')
                })
            })
        </script>

        <script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
        <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.dataTables.js"></script>
        <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.colVis.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
        <script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
        <script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.dataTables.js"></script>
        <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.print.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

        <script type="text/javascript">
            $(document).ready(function() {
                $('#table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('get') }}",
                        type: "GET",
                        dataSrc: function(json) {
                            if (json.error) {
                                alert(json.error);
                                return [];
                            }
                            return json.data;
                        }
                    },
                    layout: {
                        topStart: {
                            pageLength: {
                                menu: [10, 25, 50, 100, 200]
                            },
                            buttons: [
                                'copy',
                                'print',

                                {
                                    extend: 'spacer',
                                    style: 'bar',
                                    text: 'Export files:'
                                },
                                'csv',
                                'excel',
                                'spacer',
                                'pdf',
                                {
                                    extend: 'spacer',
                                    style: 'bar',
                                    text: ':'
                                },

                                'colvis'
                            ]
                        },
                        topEnd: {
                            search: {
                                placeholder: 'Type search here'
                            }
                        },
                        bottomEnd: {
                            paging: {
                                numbers: 3
                            }
                        },

                    },
                    columns: [{
                            data: 'id',
                            name: 'id'
                        },
                        {
                            data: 'title',
                            name: 'title'
                        },
                        {
                            data: 'categories',
                            name: 'categories'
                        },
                        {
                            data: 'typEvent',
                            name: 'typEvent'
                        },
                        {
                            data: 'location',
                            name: 'location'
                        },
                        {
                            data: 'message',
                            name: 'message'
                        },
                        {
                            data: 'status',
                            name: 'status'
                        },
                        {
                            data: 'book_a_seat',
                            name: 'book_a_seat'
                        },
                        {
                            data: 'number_day',
                            name: 'number_day'
                        },
                        {
                            data: 'start_date',
                            name: 'start_date'
                        },
                        {
                            data: 'end_date',
                            name: 'end_date'
                        },
                        {
                            data: 'action',
                            name: 'action',
                            orderable: false,
                            searchable: false
                        }
                    ],
                    pageLength: 10,
                    lengthMenu: [
                        [10, 25, 50, -1],
                        [10, 25, 50, "All"]
                    ]
                });

            });
        </script>



        {{-- <script>
            new DataTable('#table', {
                responsive: true,
                columnDefs: [{
                        responsivePriority: 1,
                        targets: 0
                    },
                    {
                        responsivePriority: 2,
                        targets: -1
                    }
                ],
                layout: {
                    topStart: {
                        pageLength: {
                            menu: [10, 25, 50, 100, 200]
                        },
                        buttons: [
                            'copy',
                            'print',

                            {
                                extend: 'spacer',
                                style: 'bar',
                                text: 'Export files:'
                            },
                            'csv',
                            'excel',
                            'spacer',
                            'pdf',
                            {
                                extend: 'spacer',
                                style: 'bar',
                                text: ':'
                            },

                            'colvis'
                        ]
                    },
                    topEnd: {
                        search: {
                            placeholder: 'Type search here'
                        }
                    },
                    bottomEnd: {
                        paging: {
                            numbers: 3
                        }
                    },

                },

                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, 'All']
                ],
            });
        </script> --}}
    @endsection


</x-app-layout>
