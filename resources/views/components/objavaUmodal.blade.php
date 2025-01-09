<?php

$kategorije=config('mojconfig.kategorije');
$vrste=config('mojconfig.vrste');
$lokacije=config('mojconfig.lokacije');
?>

<div class="fixed bottom-5 right-5">

    <button type="button" id="defaultModalButton" data-modal-target="defaultModal" data-modal-toggle="defaultModal" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-full text-sm p-2.5 text-center inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
        <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
            <path stroke="white" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m14.304 4.844 2.852 2.852M7 7H4a1 1 0 0 0-1 1v10a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-4.5m2.409-9.91a2.017 2.017 0 0 1 0 2.853l-6.844 6.844L8 14l.713-3.565 6.844-6.844a2.015 2.015 0 0 1 2.852 0Z"/>
        </svg>

        <span class="sr-only">Icon description</span>
    </button>
</div>

<div id="defaultModal" tabindex="-1" aria-hidden="true" class="hidden overflow-y-auto overflow-x-hidden fixed inset-0 z-50 flex justify-center items-center bg-black bg-opacity-50">
    <div class="relative p-4 w-full max-w-2xl h-auto">
        <!-- Modal content -->
        <div class="relative p-4 bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
            <!-- Modal header -->
            <div class="flex justify-between items-center pb-4 mb-4 rounded-t border-b sm:mb-5 dark:border-gray-600">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                    Kreiraj novu objavu
                </h3>
                <button type="button" class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-toggle="defaultModal">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <!-- Modal body -->
            <form action="/objave/{{$id}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                <div class="grid gap-4 mb-4 sm:grid-cols-2">
                    <div>
                        <label for="kategorija" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Kategorija</label>
                        <select name="kategorija" id="kategorija" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" required>
                            <option value="" disabled {{ !$trenutnaKategorija ? 'selected' : '' }}>Izaberi kategoriju</option>
                            @foreach($kategorije as $kategorija)
                                <option value="{{ $kategorija }}" {{ $kategorija == $trenutnaKategorija ? 'selected' : '' }}>
                                    {{ $kategorija }}
                                </option>
                            @endforeach
                        </select>
                        @error('kategorija')
                        {{$message}}
                        @enderror
                    </div>


                    <div>
                        <label for="naziv" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Naziv</label>

                        <input value="{{$trenutniNaziv}}" type="text" name="naziv" id="naziv" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="npr matematika 3" required="">
                        @error('naziv')
                        {{$message}}
                        @enderror
                    </div>
                    <div class="sm:col-span-2">
                        <label for="sadrzaj" class="block mb-2 text-sm font-medium text-gray-900 dark:text-white">Sadrzaj</label>

                        <textarea name="sadrzaj" id="sadrzaj" rows="4" class="block p-2.5 w-full text-sm text-gray-900 bg-gray-50 rounded-lg border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500" placeholder="Sadrzaj tvoje objave">


                             {{$trenutniSadrzaj}}
                        </textarea>
                        @error('sadrzaj')
                        {{$message}}
                        @enderror
                    </div>



                </div>

                <button type="submit" class=" text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center me-2 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800  ">
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
