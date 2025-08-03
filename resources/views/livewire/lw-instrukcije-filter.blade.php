<div>
    {{-- Centrirani naslov i podnaslov --}}
    <div class="mx-auto max-w-screen-lg text-center  mt-12">
        <h2 class="mb-4 text-4xl lg:text-5xl tracking-tight font-extrabold text-slate-800"> {{-- Ažurirane klase za font i veličinu --}}
            Pronađi <span class="text-blue-600">Instrukcije</span> i Kurseve {{-- Istaknuta riječ "Instrukcije" --}}
        </h2>
        <p class="mt-3 text-lg text-slate-600">
            Istražite našu bazu instrukcija i kurseva prema kategoriji, lokaciji i cijeni.
        </p>
    </div>

    {{-- Filteri u modernom kontejneru --}}
    <div class="bg-white p-6 rounded-2xl shadow-xl shadow-blue-50/10 mt-10 mb-12 border border-slate-200">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 items-center">

            {{-- Polje za pretragu po nazivu --}}
            <div class="relative lg:col-span-2">
                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                    <svg class="w-5 h-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </div>
                <input type="text" wire:model.live.debounce.300ms="naziv" placeholder="Pretraži po nazivu..."
                       class="w-full bg-white border border-slate-300 rounded-lg shadow-sm pl-10 pr-4 py-2.5 text-slate-800 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200">
            </div>

            {{-- Dropdown za kategorije --}}
            <div class="relative">
                <button type="button" class="w-full bg-white border border-slate-300 rounded-lg shadow-sm px-4 py-2.5 text-left flex items-center justify-between text-slate-800 hover:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200"
                        onclick="toggleDropdown('dropdownCategory')">
                    <span>Kategorija</span>
                    <svg class="w-5 h-5 ml-2 text-slate-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                    </svg>
                </button>
                <div id="dropdownCategory" class="absolute z-50 hidden mt-2 w-full max-w-sm rounded-lg shadow-xl border border-slate-200 bg-white">
                    <div class="p-3 border-b border-slate-200">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-slate-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                </svg>
                            </div>
                            <input type="text" id="input-group-search-category" class="block w-full p-2 pl-10 text-sm text-slate-900 border border-slate-300 rounded-lg bg-slate-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Pretraži kategorije">
                        </div>
                    </div>
                    <ul id="category-options" class="h-48 px-3 pb-3 overflow-y-auto text-sm text-slate-700">
                        @foreach(config('mojconfig.kategorije') as $kategorija)
                            <li>
                                <label class="flex items-center p-2 rounded-md hover:bg-slate-100 cursor-pointer">
                                    <input wire:model.live="kategorije" type="checkbox" value="{{ $kategorija }}" class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-3 text-sm text-slate-700">{{ $kategorija }}</span>
                                </label>
                            </li>
                        @endforeach
                    </ul>
                    <div class="p-3 border-t border-slate-200">
                        <a wire:click="resetFiltersKategorije" href="#" id="resetFilters1" class="w-full text-red-600 bg-red-50 hover:bg-red-100 font-medium rounded-lg px-4 py-2 text-sm inline-flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                                <path d="M6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Zm11-3h-6a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2Z"/>
                            </svg>
                            Poništi filter
                        </a>
                    </div>
                </div>
            </div>

            {{-- Dropdown za lokacije --}}
            <div class="relative">
                <button type="button" class="w-full bg-white border border-slate-300 rounded-lg shadow-sm px-4 py-2.5 text-left flex items-center justify-between text-slate-800 hover:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200"
                        onclick="toggleDropdown('dropdownLocation')">
                    <span>Lokacija</span>
                    <svg class="w-5 h-5 ml-2 text-slate-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                    </svg>
                </button>
                <div id="dropdownLocation" class="absolute z-50 hidden mt-2 w-full max-w-sm rounded-lg shadow-xl border border-slate-200 bg-white">
                    <div class="p-3 border-b border-slate-200">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-slate-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                </svg>
                            </div>
                            <input type="text" id="input-group-search-location" class="block w-full p-2 pl-10 text-sm text-slate-900 border border-slate-300 rounded-lg bg-slate-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Pretraži lokacije">
                        </div>
                    </div>
                    <ul id="location-options" class="h-48 px-3 pb-3 overflow-y-auto text-sm text-slate-700">
                        @foreach(config('mojconfig.lokacije') as $lokacija)
                            <li>
                                <label class="flex items-center p-2 rounded-md hover:bg-slate-100 cursor-pointer">
                                    <input wire:model.live="lokacije" type="checkbox" value="{{ $lokacija }}" class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-3 text-sm text-slate-700">{{ $lokacija }}</span>
                                </label>
                            </li>
                        @endforeach
                    </ul>
                    <div class="p-3 border-t border-slate-200">
                        <a wire:click="resetFiltersLokacije" href="#" id="resetFilters2" class="w-full text-red-600 bg-red-50 hover:bg-red-100 font-medium rounded-lg px-4 py-2 text-sm inline-flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                                <path d="M6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Zm11-3h-6a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2Z"/>
                            </svg>
                            Poništi filter
                        </a>
                    </div>
                </div>
            </div>

            {{-- Slider za cijenu --}}
            <div class="lg:col-span-2">
                <div class="flex justify-between items-center mb-2">
                    <label class="text-sm font-medium text-slate-700">Maksimalna cijena</label>
                    <span class="text-lg font-bold text-blue-600">{{ $cijena }} KM</span>
                </div>
                <input type="range" wire:model.live="cijena" min="0" max="100" step="1"
                       class="w-full h-2 bg-blue-100 rounded-lg appearance-none cursor-pointer focus:outline-none">
            </div>

            {{-- Reset svih filtera --}}
            <div class="lg:col-span-2 flex justify-center lg:justify-end">
                <button wire:click="resetFiltersAll" class="w-full md:w-auto bg-slate-100 text-slate-700 hover:bg-slate-200 font-medium rounded-lg px-5 py-2.5 text-sm inline-flex items-center justify-center transition-colors shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4 me-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10 14L21 3m-2 2L3 17m3 6l4-4m-4 0l-4 4" />
                    </svg>
                    Poništi sve filtere
                </button>
            </div>
        </div>
    </div>

    {{-- Prikaz instrukcija --}}
    @if ($instrukcije->isEmpty())
        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-12 text-center my-12">
            <h3 class="text-2xl font-bold text-slate-800 mb-2">Nema rezultata</h3>
            <p class="text-slate-600">Pokušajte prilagoditi filtere za širi raspon.</p>
        </div>
    @else
        <div class="grid gap-8 grid-cols-1 sm:grid-cols-2 lg:grid-cols-2">
            @foreach($instrukcije as $instrukcija)
                <x-instrukcijaoglas
                    :id="$instrukcija->id"
                    :kategorija="$instrukcija->kategorija"
                    :starostInstrukcije="$instrukcija->updated_at->diffForHumans()"
                    :nazivInstrukcije="$instrukcija->naziv"
                    :opisInstrukcije="$instrukcija->opis"
                    :instruktor="$instrukcija->user->username"
                    :userId="$instrukcija->user->id"
                />
            @endforeach
        </div>
    @endif

    {{-- Paginacija --}}
    <div class="mt-8 flex flex-col items-center">
        {{ $instrukcije->links() }}
    </div>
</div>

<script>
    // Originalna toggleDropdown funkcija
    function toggleDropdown(id) {
        document.getElementById(id).classList.toggle('hidden');
    }

    // Funkcija za pretraživanje unutar dropdowna
    function setupDropdownSearch(inputId, listId) {
        const searchInput = document.getElementById(inputId);
        const listItems = document.getElementById(listId);

        if (searchInput && listItems) {
            searchInput.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                Array.from(listItems.children).forEach(li => {
                    const label = li.querySelector('label span');
                    if (label) {
                        const text = label.textContent.toLowerCase();
                        if (text.includes(searchTerm)) {
                            li.style.display = '';
                        } else {
                            li.style.display = 'none';
                        }
                    }
                });
            });
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        // Postavljanje pretraživanja za kategorije
        setupDropdownSearch('input-group-search-category', 'category-options');
        // Postavljanje pretraživanja za lokacije
        setupDropdownSearch('input-group-search-location', 'location-options');

        // Originalna JavaScript logika za zatvaranje dropdowna kada se klikne van njega
        window.addEventListener('click', function (event) {
            let dropdowns = ['dropdownCategory', 'dropdownLocation'];
            dropdowns.forEach(id => {
                let button = document.querySelector(`[onclick="toggleDropdown('${id}')"]`);
                let menu = document.getElementById(id);

                if (button && menu && !button.contains(event.target) && !menu.contains(event.target)) {
                    menu.classList.add('hidden');
                }
            });
        });

        // Originalna JavaScript logika za resetiranje checkboxova
        const resetButton1 = document.getElementById('resetFilters1');
        if (resetButton1) {
            resetButton1.addEventListener('click', function (event) {
                // Livewire wire:click će se pobrinuti za resetiranje stanja,
                // ali ovaj dio osigurava vizualno odznačavanje checkboxova odmah.
                const checkboxes1 = document.querySelectorAll('#dropdownCategory input[type="checkbox"]');
                checkboxes1.forEach(function (checkbox) {
                    checkbox.checked = false;
                });
                // Opcionalno, resetirajte i polje za pretragu unutar dropdowna
                const searchInputCategory = document.getElementById('input-group-search-category');
                if (searchInputCategory) searchInputCategory.value = '';
                // Prikaz svih opcija nakon resetiranja pretrage
                const categoryOptions = document.getElementById('category-options');
                if (categoryOptions) {
                    Array.from(categoryOptions.children).forEach(li => {
                        li.style.display = '';
                    });
                }
            });
        }

        const resetButton2 = document.getElementById('resetFilters2');
        if (resetButton2) {
            resetButton2.addEventListener('click', function (event) {
                const checkboxes2 = document.querySelectorAll('#dropdownLocation input[type="checkbox"]');
                checkboxes2.forEach(function (checkbox) {
                    checkbox.checked = false;
                });
                const searchInputLocation = document.getElementById('input-group-search-location');
                if (searchInputLocation) searchInputLocation.value = '';
                const locationOptions = document.getElementById('location-options');
                if (locationOptions) {
                    Array.from(locationOptions.children).forEach(li => {
                        li.style.display = '';
                    });
                }
            });
        }
    });

    document.addEventListener('livewire:navigated', () => {
        // Re-inicijalizacija pretraživanja unutar dropdowna nakon Livewire navigacije
        setupDropdownSearch('input-group-search-category', 'category-options');
        setupDropdownSearch('input-group-search-location', 'location-options');
    });
</script>
