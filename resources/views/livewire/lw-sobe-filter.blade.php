<div>
    {{-- Stop trying to control. --}}
    {{-- Prikaz poruka o uspjehu ili neuspjehu --}}
    @if ($successMessage)
        <div x-data="{ show: true }" x-init="setTimeout(() => { show = false; $wire.set('successMessage', '') }, 5000)"
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-90"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-90"
             class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-6"
             role="alert">
            <strong class="font-bold">Uspjeh!</strong>
            <span class="block sm:inline">{{ $successMessage }}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <svg @click="show = false; $wire.set('successMessage', '')"
                     class="fill-current h-6 w-6 text-green-500 cursor-pointer"
                     role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <title>Zatvori</title>
                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.03a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                </svg>
            </span>
        </div>
    @endif

    @if ($failureMessage)
        <div x-data="{ show: true }" x-init="setTimeout(() => { show = false; $wire.set('failureMessage', '') }, 5000)"
             x-show="show"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform scale-90"
             x-transition:enter-end="opacity-100 transform scale-100"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 transform scale-100"
             x-transition:leave-end="opacity-0 transform scale-90"
             class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-6"
             role="alert">
            <strong class="font-bold">Greška!</strong>
            <span class="block sm:inline">{{ $failureMessage }}</span>
            <span class="absolute top-0 bottom-0 right-0 px-4 py-3">
                <svg @click="show = false; $wire.set('failureMessage', '')"
                     class="fill-current h-6 w-6 text-red-500 cursor-pointer"
                     role="button" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <title>Zatvori</title>
                    <path d="M14.348 14.849a1.2 1.2 0 0 1-1.697 0L10 11.819l-2.651 3.03a1.2 1.2 0 1 1-1.697-1.697l2.758-3.15-2.759-3.152a1.2 1.2 0 1 1 1.697-1.697L10 8.183l2.651-3.031a1.2 1.2 0 1 1 1.697 1.697l-2.758 3.152 2.758 3.15a1.2 1.2 0 0 1 0 1.698z"/>
                </svg>
            </span>
        </div>
    @endif
    <div>
        {{-- Centrirani naslov i podnaslov --}}
        <div class="mx-auto max-w-screen-lg text-center mt-12">
            <h2 class="mb-4 text-4xl lg:text-5xl tracking-tight font-extrabold text-slate-800">
                Istražite <span class="text-blue-600">Sobe</span>
            </h2>
            <p class="mt-3 text-lg text-slate-600">
                Pregledajte najnovije sobe iz naše zajednice.
            </p>
        </div>

        {{-- Filteri u modernom kontejneru --}}
        <div class="bg-white p-6 rounded-2xl shadow-xl shadow-blue-50/10 mb-12 mt-10 border border-slate-200">
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

                {{-- Placeholder za Dropdown za lokacije (ako je potreban za simetriju) --}}


                {{-- Dugme za reset svih filtera --}}
                <div class="flex justify-center lg:justify-end">
                    <button wire:click="resetFiltersAll" class="w-full md:w-auto bg-slate-100 text-slate-700 hover:bg-slate-200 font-medium rounded-lg px-5 py-2.5 text-sm inline-flex items-center justify-center transition-colors shadow-sm hover:shadow-md">
                        <svg class="w-4 h-4 me-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 14L21 3m-2 2L3 17m3 6l4-4m-4 0l-4 4" />
                        </svg>
                        Poništi sve filtere
                    </button>
                </div>
            </div>
        </div>

        {{-- Prikaz soba --}}
        <div>
            {{-- Prikaz soba --}}
            @if ($sobe->isEmpty())
                <div class="bg-white rounded-2xl shadow-lg border border-slate-200 p-12 text-center my-12">
                    <h3 class="text-2xl font-bold text-slate-800 mb-2">Nema rezultata</h3>
                    <p class="text-slate-600">Pokušajte prilagoditi filtere za širi raspon.</p>
                </div>
            @else
                <div class="grid gap-8 grid-cols-1 md:grid-cols-2 lg:grid-cols-3">
                    @foreach($sobe as $soba)
                        <div class="bg-white rounded-2xl shadow-lg border border-slate-200 overflow-hidden transition-transform duration-300 hover:scale-[1.02] hover:shadow-xl">
                            <div class="p-6">
                                {{-- Zaglavlje kartice --}}
                                <div class="flex items-start mb-4">
                                    {{-- Ikonica ili inicijali sobe --}}
                                    <div class="flex items-center justify-center h-12 w-12 rounded-full overflow-hidden bg-blue-100 text-blue-600 flex-shrink-0">
                                        @if ($soba->profilna_slika)
                                            <img src="{{ Storage::url($soba->profilna_slika) }}" alt="{{ $soba->name }} profilna slika" class="w-10 h-10 rounded-full object-cover">
                                        @else

                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h12a2 2 0 012 2v8a2 2 0 01-2 2h-4l-4 4v-4H6a2 2 0 01-2-2V6z"></path>
                                        </svg>
                                        @endif

                                    </div>
                                    {{-- Ime sobe i kategorija --}}
                                    <div class="ml-4 flex-grow">
                                        <h3 class="text-xl font-bold text-slate-800 truncate">{{ $soba->name }}</h3>
                                        <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700 mt-2">

                                {{ $soba->kategorija  }}
                            </span>
                                    </div>
                                    {{-- Status --}}
                                    <div class="flex-shrink-0 text-right">
                            <span class="text-xs text-slate-500">
                                {{-- Sada koristimo accessor members_count --}}
                                {{ $soba->members_count ?? 0 }} člana
                            </span>
                                    </div>
                                </div>

                                {{-- Opis sobe --}}
                                <p class="text-slate-600 text-sm mb-6 line-clamp-3">
                                    {{ $soba->opis ?? 'Ovo je soba za razgovor o ' . $soba->kategorija . ' i sličnim temama. Pridružite se diskusiji!' }}
                                </p>

                                @if($soba->privatnost==="otvorena")
                                    <div class="flex items-center justify-center">
                                        {{-- Sada koristimo wire:click da pozovemo metodu joinRoom --}}
                                        <button wire:click="joinRoom({{ $soba->id }})"
                                                class="inline-flex items-center justify-center w-full px-4 py-2.5 text-base font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-md">
                                            <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3" />
                                            </svg>
                                            Pridruži se
                                        </button>
                                    </div>
                                @else
                                    {{-- Ažurirani blok za privatne sobe --}}
                                    <div class="flex items-center justify-center">
                                        <div
                                           {{-- Promijenjene su Tailwind klase za sivu pozadinu i hover efekt --}}
                                           class="cursor-not-allowed inline-flex items-center justify-center w-full px-4 py-2.5 text-base font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 transition-colors duration-200 shadow-md">
                                            {{-- Dodana je SVG ikona lokota umjesto slova 'x' --}}
                                            <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                                            </svg>
                                            Privatna soba
                                       </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    @endforeach
                </div>
            @endif

            {{-- Paginacija --}}
            <div class="mt-8 flex flex-col items-center">
                {{ $sobe->links() }}
            </div>
            ...
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

            // Originalna JavaScript logika za zatvaranje dropdowna kada se klikne van njega
            window.addEventListener('click', function (event) {
                let dropdowns = ['dropdownCategory'];
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
                    const checkboxes1 = document.querySelectorAll('#dropdownCategory input[type="checkbox"]');
                    checkboxes1.forEach(function (checkbox) {
                        checkbox.checked = false;
                    });
                    const searchInputCategory = document.getElementById('input-group-search-category');
                    if (searchInputCategory) searchInputCategory.value = '';
                    const categoryOptions = document.getElementById('category-options');
                    if (categoryOptions) {
                        Array.from(categoryOptions.children).forEach(li => {
                            li.style.display = '';
                        });
                    }
                });
            }
        });

        document.addEventListener('livewire:navigated', () => {
            // Re-inicijalizacija pretraživanja unutar dropdowna nakon Livewire navigacije
            setupDropdownSearch('input-group-search-category', 'category-options');
        });
    </script>
</div>
