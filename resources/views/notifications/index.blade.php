<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-content center">
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                {{ __('Notifications') }}
            </h2>
        </div>
    </x-slot>

    @if (session('success'))
        <div id="alert-3" class="flex items-center p-4 mt-4 mb-4 text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
            <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
            </svg>
            <span class="sr-only">Info</span>
            <div class="text-sm font-medium ms-3">
                {{ session('success') }}
            </div>
            <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-green-50 text-green-500 rounded-lg focus:ring-2 focus:ring-green-400 p-1.5 hover:bg-green-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-green-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-3" aria-label="Close">
            <span class="sr-only">Close</span>
            <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
            </svg>
            </button>
        </div>
    @endif

    @if (session('error'))
    <div id="alert-2" class="flex items-center p-4 mt-4 mb-4 text-red-800 rounded-lg bg-red-50 dark:bg-gray-800 dark:text-red-400" role="alert">
        <svg class="flex-shrink-0 w-4 h-4" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
          <path d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z"/>
        </svg>
        <span class="sr-only">Info</span>
        <div class="text-sm font-medium ms-3">
            {{ session('error') }}
        </div>
        <button type="button" class="ms-auto -mx-1.5 -my-1.5 bg-red-50 text-red-500 rounded-lg focus:ring-2 focus:ring-red-400 p-1.5 hover:bg-red-200 inline-flex items-center justify-center h-8 w-8 dark:bg-gray-800 dark:text-red-400 dark:hover:bg-gray-700" data-dismiss-target="#alert-2" aria-label="Close">
          <span class="sr-only">Close</span>
          <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
          </svg>
        </button>
      </div>
    @endif

    <!-- Modal toggle -->
    <div class="flex justify-end mt-6 mr-4 center">
        <button id="dropdownDefaultButton" data-dropdown-toggle="dropdown" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">Envoyer <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
            </svg>
        </button>
    </div>

    <!-- Dropdown menu -->
    <div id="dropdown" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-28 dark:bg-gray-700">
        <ul class="py-2 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownDefaultButton">
          <li>
            <a href="#" data-modal-target="mail-modal" data-modal-toggle="mail-modal" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Mail</a>
          </li>
          <li>
            <a href="#" data-modal-target="sms-modal" data-modal-toggle="sms-modal" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">SMS</a>
          </li>
        </ul>
    </div>
@section('modal')

    <!-- Mail modal -->
    <div id="mail-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-5xl max-h-full p-4">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 border-b rounded-t md:p-5 dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Envoie des mails
                    </h3>
                    <button type="button" class="inline-flex items-center justify-center w-8 h-8 text-sm text-gray-400 bg-transparent rounded-lg hover:bg-gray-200 hover:text-gray-900 ms-auto dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="mail-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>

                <!-- Modal body -->
                <form action="{{ route('sendMail' )}}" class="p-4 md:p-5" method="post">
                    @csrf
                    @method('GET')
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="col-span-2">
                            <label for="activity" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Activité</label>
                            <select id="activity" name="activity" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="" selected="">Sélectionner une activité</option>
                                {{ $activites }}
                                @foreach ($activites as $item)
                                    <option value="{{$item->id }}">{{ $item->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="model-mail-div" class="col-span-2">
                            <label id="label-model-mail" for="model-mail" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Modèle de mail</label>
                            <select id="model-mail" name="model-mail" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="" selected="">Sélectionner un modèle de mail</option>
                                {{ $modelMail }}
                                @foreach ($modelMail as $item)
                                    <option value="{{ $item->message }}">{{ $item->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label for="subject" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sujet</label>
                            <input type="text" name="subject" id="subject" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Entrer un sujet de mail" required="">
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <label for="cible" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cible</label>
                            <select id="cible" name="cible" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="tout-le-monde" selected="">Tous le monde</option>
                                <option value="activité">Par rapport à une activité</option>
                                <option value="age-cible">Age</option>
                                <option value="sexe-cible">Genre</option>
                                <option value="personnalise">Personnalisé</option>
                            </select>
                        </div>
                        <div id="per-activity-div" class="col-span-2">
                            <label for="per-activity" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Par rapport à une activité</label>
                            <input type="text" list="activity_name_list" name="per-activity" id="per-activity" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Entrer une activité">
                            <div id="activity_name_list" class="text-black bg-gray-300 rounded-lg "></div>
                        </div>

                        {{ csrf_field() }}

                        <div id="age-div" class="col-span-2">
                            <label for="age" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Age</label>
                            <select id="age" name="age" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="" selected="">Sélectionner un âge</option>
                                <option value=">18">> à 18</option>
                                <option value="<18">< à 18</option>
                                <option value=">25">> à 25</option>
                                <option value=">30">> à 30</option>
                            </select>
                        </div>
                        <div id="sexe-div" class="col-span-2">
                            <label for="sexe" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Genre</label>
                            <select id="sexe" name="sexe" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="" selected="">Sélectionner un genre</option>
                                <option value="M">Masculin</option>
                                <option value="F">Feminin</option>
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label for="message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Message</label>
                            <textarea id="message" name="message" rows="6" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Write your message here"></textarea>
                        </div>
                    </div>
                    <button type="submit" class="text-white inline-flex w-full justify-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Envoyer
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Sms modal -->
    <div id="sms-modal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative w-full max-w-5xl max-h-full p-4">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between p-4 border-b rounded-t md:p-5 dark:border-gray-600">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Envoie des sms
                    </h3>
                    <button type="button" class="inline-flex items-center justify-center w-8 h-8 text-sm text-gray-400 bg-transparent rounded-lg hover:bg-gray-200 hover:text-gray-900 ms-auto dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="sms-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>

                <!-- Modal body -->
                <form action="{{ route('sendSms')}}" class="p-4 md:p-5" method="post">
                    @csrf
                    @method('GET')
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div class="col-span-2">
                            <label for="sms-activity" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Activité</label>
                            <select id="sms-activity" name="sms-activity" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="" selected="">Sélectionner une activité</option>
                                {{ $activites }}
                                @foreach ($activites as $item)
                                    <option value="{{$item->id }}">{{ $item->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div id="model-sms-div" class="col-span-2">
                            <label id="label-model-sms" for="model-sms" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Modèle de sms</label>
                            <select id="model-sms" name="model-sms" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="" selected="">Sélectionner un modèle de sms</option>
                                {{ $modelSms }}
                                @foreach ($modelSms as $item)
                                    <option value="{{ $item->message }}">{{ $item->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-span-2">
                            <label for="sms-cible" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Cible</label>
                            <select id="sms-cible" name="sms-cible" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="tout-le-monde" selected="">Tous le monde</option>
                                <option value="activité">Par rapport à une activité</option>
                                <option value="age-cible">Age</option>
                                <option value="sexe-cible">Genre</option>
                                <option value="personnalise">Personnalisé</option>
                            </select>
                        </div>
                        <div id="sms-per-activity-div" class="col-span-2">
                            <label for="sms-per-activity" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Par rapport à une activité</label>
                            <input type="text" list="sms-activity_name_list" name="sms-per-activity" id="sms-per-activity" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Entrer une activité">
                            <div id="sms-activity_name_list" class="text-black bg-gray-300 rounded-lg "></div>
                        </div>

                        {{ csrf_field() }}

                        <div id="sms-age-div" class="col-span-2">
                            <label for="sms-age" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Age</label>
                            <select id="sms-age" name="sms-age" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="" selected="">Sélectionner un âge</option>
                                <option value=">18">> à 18</option>
                                <option value="<18">< à 18</option>
                                <option value=">25">> à 25</option>
                                <option value=">30">> à 30</option>
                            </select>
                        </div>
                        <div id="sms-sexe-div" class="col-span-2">
                            <label for="sms-sexe" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Genre</label>
                            <select id="sms-sexe" name="sms-sexe" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-500 focus:border-primary-500 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500">
                                <option value="" selected="">Sélectionner un genre</option>
                                <option value="M">Masculin</option>
                                <option value="F">Feminin</option>
                            </select>
                        </div>
                        <div id="sms-personnalise-div" class="col-span-2">
                            <label for="sms-person" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Personnalisé</label>
                            <input type="text" name="sms-person" id="sms-person" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="821002525, 821060509, ...">
                        </div>
                        <div class="col-span-2">
                            <label for="sms-message" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Message</label>
                            <textarea id="sms-message" name="sms-message" rows="6" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Write your message here"></textarea>
                        </div>
                    </div>
                    <button type="submit" class="text-white inline-flex w-full justify-center bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                        Envoyer
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

    <!--data table-->

    <div class="relative mt-2 overflow-x-auto">
        <table id="nofifTable" class="w-full text-sm text-left text-gray-500 rtl:text-right dark:text-gray-400 cell-border">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-6 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        Sender
                    </th>
                    <th scope="col" class="px-6 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        Activité
                    </th>
                    <th scope="col" class="px-6 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        Message
                    </th>
                    <th scope="col" class="px-6 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        Date
                    </th>
                    <th scope="col" class="px-6 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        Nombre des personnes
                    </th>
                    {{-- <th scope="col" class="px-6 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        Nombre des echecs
                    </th> --}}
                    <th scope="col" class="px-6 py-3 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                        Type
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($notifications as $item)
                @foreach ($notifications as $item)
                    <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                        <td class="px-6 py-4">{{ $item->name }}</td>
                        <td class="px-6 py-4">{{ $item->title }}</td>
                        <td class="px-6 py-4">{{ $item->message }}</td>
                        <td class="px-6 py-4">{{ $item->send_date }}</td>
                        <td class="px-6 py-4">{{ $item->person_number }}</td>
                        @if ($item->type === 'Mail')
                            <td class="px-6 py-4">Mail</td>
                        @elseif ($item->type === 'SMS')
                            <td class="px-6 py-4">SMS</td>
                        @else
                            <td class="px-6 py-4">Mail/SMS</td>
                        @endif
                    </tr>
                @endforeach
                @endforeach
            </tbody>
        </table>
    </div>

    @section('script')
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
        <script src="https://code.jquery.com/ui/1.11.4/jquery-ui.min.js"></script>

        <x-head.tinymce-config/>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const activitySelect = document.getElementById('activity');
                const cibleSelect = document.getElementById('cible');
                const modelMailSelect = document.getElementById('model-mail');

                const activitySelectSms = document.getElementById('sms-activity');
                const cibleSelectSms = document.getElementById('sms-cible');
                const modelSmsSelect = document.getElementById('model-sms');

                const modelMailDiv = document.getElementById('model-mail-div');
                const perActivity = document.getElementById('per-activity-div');
                const perActivityInput = document.getElementById('per-activity');
                const person = document.getElementById('personnalise-div');
                const age = document.getElementById('age-div');
                const sexe = document.getElementById('sexe-div');
                // const message = document.getElementById('message');

                const modelSmsDiv = document.getElementById('model-sms-div');
                const perActivitySms = document.getElementById('sms-per-activity-div');
                const perActivityInputSms = document.getElementById('sms-per-activity');
                const personSms = document.getElementById('sms-personnalise-div');
                const ageSms = document.getElementById('sms-age-div');
                const sexeSms = document.getElementById('sms-sexe-div');
                // const messageSms = document.getElementById('message');

                modelMailDiv.style.display = 'none';
                perActivity.style.display = 'none';
                age.style.display = 'none';
                sexe.style.display = 'none';

                modelSmsDiv.style.display = 'none';
                perActivitySms.style.display = 'none';
                personSms.style.display = 'none';
                ageSms.style.display = 'none';
                sexeSms.style.display = 'none';

                activitySelect.addEventListener('change', function() {
                    const optionToRemove = cibleSelect.querySelector('option[value="accepté"]');
                    const optionToRemoveDecline = cibleSelect.querySelector('option[value="decline"]');
                    if (optionToRemove && optionToRemoveDecline) {
                        cibleSelect.removeChild(optionToRemove);
                        cibleSelect.removeChild(optionToRemoveDecline);
                    }
                    modelMailDiv.style.display = 'none';
                    // message.value = '';
                    tinymce.get('message').setContent("");

                    if (this.value !== '') {
                        const option = document.createElement('option');
                        const option2 = document.createElement('option');
                        modelMailDiv.style.display = 'block';
                        option.text = 'Ceux acceptés';
                        option.value = 'accepté';
                        option2.text = 'Ceux non acceptés';
                        option2.value = 'decline';
                        cibleSelect.appendChild(option);
                        cibleSelect.appendChild(option2);
                    } else {
                        const optionToRemove = cibleSelect.querySelector('option[value="accepté"]');
                        const optionToRemoveDecline = cibleSelect.querySelector('option[value="decline"]');
                        if (optionToRemove && optionToRemoveDecline) {
                            cibleSelect.removeChild(optionToRemove);
                            cibleSelect.removeChild(optionToRemoveDecline);
                        }
                        modelMailDiv.style.display = 'none';
                        // message.value = '';
                        tinymce.get('message').setContent("");
                    }
                });

                activitySelectSms.addEventListener('change', function() {
                    const optionToRemove = cibleSelectSms.querySelector('option[value="accepté"]');
                    const optionToRemoveDecline = cibleSelectSms.querySelector('option[value="decline"]');
                    if (optionToRemove && optionToRemoveDecline) {
                        cibleSelectSms.removeChild(optionToRemove);
                        cibleSelectSms.removeChild(optionToRemoveDecline);
                    }
                    modelSmsDiv.style.display = 'none';
                    // message.value = '';
                    tinymce.get('sms-message').setContent("");

                    if (this.value !== '') {
                        const option = document.createElement('option');
                        const option2 = document.createElement('option');
                        modelSmsDiv.style.display = 'block';
                        option.text = 'Ceux acceptés';
                        option.value = 'accepté';
                        option2.text = 'Ceux non acceptés';
                        option2.value = 'decline';
                        cibleSelectSms.appendChild(option);
                        cibleSelectSms.appendChild(option2);
                    } else {
                        const optionToRemove = cibleSelectSms.querySelector('option[value="accepté"]');
                        const optionToRemoveDecline = cibleSelectSms.querySelector('option[value="decline"]');
                        if (optionToRemove && optionToRemoveDecline) {
                            cibleSelectSms.removeChild(optionToRemove);
                            cibleSelectSms.removeChild(optionToRemoveDecline);
                        }
                        modelSmsDiv.style.display = 'none';
                        // message.value = '';
                        tinymce.get('sms-message').setContent("");
                    }
                });

                modelMailSelect.addEventListener('change', function() {
                    // message.value = this.value;
                    tinymce.get('message').setContent(this.value);
                });

                modelSmsSelect.addEventListener('change', function() {
                    // message.value = this.value;
                    tinymce.get('sms-message').setContent(this.value);
                });

                cibleSelect.addEventListener('change', function() {
                    if (this.value === 'activité') {
                        perActivityInput.value = '';
                        perActivity.style.display = 'block';
                    } else {
                        perActivityInput.value = '';
                        perActivity.style.display = 'none';
                    }

                    if (this.value === 'age-cible') {
                        age.style.display = 'block';
                    } else {
                        age.style.display = 'none';
                    }

                    if (this.value === 'sexe-cible') {
                        sexe.style.display = 'block';
                    } else {
                        sexe.style.display = 'none';
                    }

                    if (this.value === 'personnalise') {
                        person.style.display = 'block';
                    } else {
                        person.style.display = 'none';
                    }

                });

                cibleSelectSms.addEventListener('change', function() {
                    if (this.value === 'activité') {
                        perActivityInputSms.value = '';
                        perActivitySms.style.display = 'block';
                    } else {
                        perActivityInputSms.value = '';
                        perActivitySms.style.display = 'none';
                    }

                    if (this.value === 'age-cible') {
                        ageSms.style.display = 'block';
                    } else {
                        ageSms.style.display = 'none';
                    }

                    if (this.value === 'sexe-cible') {
                        sexeSms.style.display = 'block';
                    } else {
                        sexeSms.style.display = 'none';
                    }

                    if (this.value === 'personnalise') {
                        personSms.style.display = 'block';
                    } else {
                        personSms.style.display = 'none';
                    }

                });
            });
        </script>

        <script>
            //scrpit pour l'autocomplete
            $(function() {
                $('#per-activity').on('input', function() {
                    var query = $(this).val();
                    if (query != '') {
                        var _token = $('input[name="_token"]').val();
                        $.ajax({
                            url: "{{ route('autocomplete') }}",
                            method: "GET",
                            data: {
                                query: query,
                                _token: _token
                            },
                            success: function(data) {
                                $('#activity_name_list').fadeIn();
                                $('#activity_name_list').empty(); // Vider la liste avant d'ajouter de nouveaux éléments
                                let data_user = $.each(data, function(index, item) {
                                    $('#activity_name_list').append('<ul class= "font-bold"><li class="pl-4 bg-gray-300 hover:bg-gray-400">' + item.title + '</li></ul>');
                                });
                            }
                        });
                    } else {
                        $('#activity_name_list').fadeOut();
                    }
                });

                delay:500,

                $(document).on('click', 'li', function() {
                    $('#per-activity').val($(this).text());
                    $('#activity_name_list').fadeOut();
                });

                // $('#resetButton').click(function() {
                //     $('#per-activity').val('');
                //     $('#activity_name_list').fadeOut();
                // });
            });
        </script>

        <script>
            //scrpit pour l'autocomplete
            $(function() {
                $('#sms-per-activity').on('input', function() {
                    var query = $(this).val();
                    if (query != '') {
                        var _token = $('input[name="_token"]').val();
                        $.ajax({
                            url: "{{ route('autocompleteSms') }}",
                            method: "GET",
                            data: {
                                query: query,
                                _token: _token
                            },
                            success: function(data) {
                                $('#sms-activity_name_list').fadeIn();
                                $('#sms-activity_name_list').empty(); // Vider la liste avant d'ajouter de nouveaux éléments
                                let data_user = $.each(data, function(index, item) {
                                    $('#sms-activity_name_list').append('<ul class= "font-bold"><li class="pl-4 bg-gray-300 hover:bg-gray-400">' + item.title + '</li></ul>');
                                });
                            }
                        });
                    } else {
                        $('#sms-activity_name_list').fadeOut();
                    }
                });

                delay:500,

                $(document).on('click', 'li', function() {
                    $('#sms-per-activity').val($(this).text());
                    $('#sms-activity_name_list').fadeOut();
                });

                // $('#resetButton').click(function() {
                //     $('#sms-per-activity').val('');
                //     $('#sms-activity_name_list').fadeOut();
                // });
            });
        </script>

        {{-- <script type="text/javascript">
            $(document).ready(function() {
                $('#nofifTable').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: "{{ route('getNotif') }}",
                        type: 'GET',
                        dataType: 'json'
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
                    columns: [
                        { data: 'name', name: 'name' },
                        { data: 'title', name: 'title' },
                        { data: 'message', name: 'message' },
                        { data: 'send_date', name: 'send_date' },
                        { data: 'person_number', name: 'person_number' },
                        { data: 'type', name: 'type' }
                    ],
                    pageLength: 10,
                    lengthMenu: [
                        [10, 25, 50, -1],
                        [10, 25, 50, "All"]
                    ]
                });

                // Custom CSS classes
                $('#nofifTable').css('width', '100%');
                $('.dt-container').addClass('text-base text-gray-800 dark:text-gray-400 leading-tight');
                $('.dt-buttons').addClass('mt-4');
                $('.dt-buttons button').addClass('cursor-pointer mt-5 bg-slate-600 p-2 rounded-sm font-bold');
                $("#dt-length-0").addClass('text-gray-700 dark:text-gray-400 w-24 bg-white');
                $("label[for='dt-length-0']").addClass('text-gray-700 dark:text-gray-400').text('Enregistrements par page');
                $('.dt-input').addClass('w-24');
            });
        </script> --}}

        <script>
            new DataTable('#nofifTable', {
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
        </script>
    @endsection
</x-app-layout>
