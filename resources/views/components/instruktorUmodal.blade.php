<?php

$kategorije=config('mojconfig.kategorije');
$vrste=config('mojconfig.vrste');
$lokacije=config('mojconfig.lokacije');
?>


<div id="defaultModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 flex justify-center items-center bg-black bg-opacity-50">
    <div class="relative p-4 w-full max-w-2xl h-auto">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <!-- Modal header -->
            <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Uredi svoje informacije
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="defaultModal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form action="/instruktori/{{$id}}" method="POST">
                @csrf
                @method('PATCH')
                <div>
                    <label for="username" class="block text-sm font-medium leading-6 text-gray-900">Korisničko ime</label>
                    <div class="mt-2">
                        <input value="{{$username}}" id="username" name="username" type="text"  required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                    @error('username')
                    {{$message}}
                    @enderror
                </div>


                <div>
                    <label for="titula" class="block text-sm font-medium leading-6 text-gray-900">Titula</label>
                    <div class="mt-2">
                        <input id="titula" name="titula" type="titula" value="{{$titula}}" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                    @error('titula')
                    {{$message}}
                    @enderror
                </div>
                <div>
                    <label for="kontakt" class="block text-sm font-medium leading-6 text-gray-900">Kontakt</label>
                    <div class="mt-2">
                        <input id="kontakt" name="kontakt" value="{{$kontakt}}" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                    </div>
                    @error('kontakt')
                    {{$message}}
                    @enderror
                </div>

                <div>
                    <label for="opis" class="block text-sm font-medium leading-6 text-gray-900">Opis</label>
                    <div class="mt-2">
                        <textarea id="opis" name="opis" required class="block w-full rounded-md border-0 py-1.5 text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-indigo-600 sm:text-sm sm:leading-6">
                            {{$opis}}
                        </textarea>
                    </div>
                    @error('opis')
                    {{$message}}
                    @enderror
                </div>


                <button type="submit" class=" mt-2 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800  ">
                    <svg class="mr-1 -ml-1 w-6 h-6" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd"></path></svg>
                    Uredi
                </button>

            </form>
        </div>
    </div>
</div>



<script>
    document.addEventListener("DOMContentLoaded", function(event) {
        const modalButton = document.getElementById('defaultModalButton');
        const modal = document.getElementById('defaultModal');

        modalButton.addEventListener('click', function() {
            modal.classList.toggle('hidden');
        });

        // Dodajte još jedan event listener za zatvaranje modala
        const closeModalButton = modal.querySelector('[data-modal-toggle="defaultModal"]');
        closeModalButton.addEventListener('click', function() {
            modal.classList.toggle('hidden');
        });
    });


</script>

