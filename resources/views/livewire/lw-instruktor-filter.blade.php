<div>
    <div class="mx-auto max-w-screen-lg text-center  mt-12">
        <h2 class="mb-4 text-4xl lg:text-5xl tracking-tight font-extrabold text-slate-800 dark:text-white">
            Pronađi svog <span class="text-blue-600">Instruktora</span>
        </h2>
        <p class="font-light text-slate-500 sm:text-xl dark:text-slate-400 max-w-2xl mx-auto">
            Istražite našu bazu stručnih instruktora i pronađite idealnog mentora za vaše potrebe.
        </p>
    </div>

    {{-- Filteri i pretraga --}}
    <div class="bg-white p-6 rounded-2xl shadow-xl shadow-blue-50/10 mb-1 mt-10 border border-slate-200">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 items-end">
            {{-- Dropdown za lokacije --}}
            <div class="relative">
                <label for="location-filter-btn" class="block text-sm font-medium text-slate-700 mb-1">Lokacija</label>
                <button id="location-filter-btn" type="button" class="w-full bg-white border border-slate-300 rounded-lg shadow-sm px-4 py-2.5 text-left flex items-center justify-between text-slate-800 hover:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200"
                        onclick="toggleDropdown('dropdownLocation')">
                    <span class="truncate">{{ count($lokacije) > 0 ? (count($lokacije) === 1 ? $lokacije[0] : count($lokacije) . ' lokacija odabrano') : 'Odaberite lokaciju' }}</span>
                    <svg class="w-4 h-4 ml-2 text-slate-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
                    </svg>
                </button>
                <div id="dropdownLocation" class="absolute z-50 hidden mt-2 w-full bg-white rounded-lg shadow-xl border border-slate-200 max-h-60 overflow-y-auto">
                    <div class="p-3 border-b border-slate-200">
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                <svg class="w-4 h-4 text-slate-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                                </svg>
                            </div>
                            <input type="text" id="location-search-input" class="block w-full p-2 pl-10 text-sm text-slate-900 border border-slate-300 rounded-lg bg-slate-50 focus:ring-blue-500 focus:border-blue-500" placeholder="Pretraži lokacije">
                        </div>
                    </div>
                    <ul id="location-options" class="p-2">
                        @foreach($allLokacije as $lokacijaOption)
                            <li>
                                <label class="flex items-center p-2 rounded-md hover:bg-slate-100 cursor-pointer">
                                    <input wire:model.live="lokacije" type="checkbox" value="{{ $lokacijaOption }}" class="h-4 w-4 rounded border-slate-300 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-3 text-sm text-slate-700">{{ $lokacijaOption }}</span>
                                </label>
                            </li>
                        @endforeach
                    </ul>
                    <div class="p-3 border-t border-slate-200">
                        <button wire:click="resetFiltersLokacije" type="button" class="w-full text-red-600 bg-red-50 hover:bg-red-100 font-medium rounded-lg px-4 py-2 text-sm inline-flex items-center justify-center transition-colors">
                            <svg class="w-4 h-4 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                                <path d="M6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Zm11-3h-6a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2Z"/>
                            </svg>
                            Poništi lokacije
                        </button>
                    </div>
                </div>
            </div>

            {{-- Pretraga po nazivu (sada kao obično input polje s istim stilom) --}}
            <div class="relative">
                <label for="naziv-input" class="block text-sm font-medium text-slate-700 mb-1">Pretraži po imenu</label>
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-slate-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                        </svg>
                    </div>
                    <input wire:model.live.debounce.300ms="naziv" type="text" id="naziv-input"
                           class="w-full bg-white border border-slate-300 rounded-lg shadow-sm pl-10 pr-4 py-2.5 text-slate-800 hover:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200"
                           placeholder="Upišite ime instruktora...">
                </div>
            </div>

            {{-- Dugme za reset svih filtera --}}
            <div class="flex justify-end md:col-span-1">
                <button wire:click="resetFiltersAll" class="w-full md:w-auto bg-slate-100 text-slate-700 hover:bg-slate-200 font-medium rounded-lg px-5 py-2.5 text-sm inline-flex items-center justify-center transition-colors shadow-sm hover:shadow-md">
                    <svg class="w-4 h-4 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0011.664 0l3.181-3.183m-4.991-2.695v-4.992m0 0h-4.992m4.992 0l-3.181-3.183a8.25 8.25 0 00-11.664 0l-3.181 3.183"/>
                    </svg>
                    Poništi sve filtere
                </button>
            </div>
        </div>
    </div>

    {{-- Prikaz instrukcija --}}
    @if($instruktori->isEmpty())
        <div class="text-center py-16 bg-white rounded-2xl shadow-lg border border-slate-200">
            <svg class="mx-auto h-12 w-12 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
            </svg>
            <h3 class="mt-2 text-xl font-semibold text-slate-800">Nema pronađenih instruktora</h3>
            <p class="mt-1 text-slate-500">Pokušajte promijeniti kriterije pretrage.</p>
        </div>
    @else
        <div class="grid gap-8 grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 mt-12">
            @foreach($instruktori as $instruktor)
                    <?php
                    $kategorijeInstruktora = $instruktor->instrukcije()->distinct()->pluck('kategorija');
                    ?>
                <div class="flex justify-center">
                    <x-profilkarticamala
                        :kategorije="$kategorijeInstruktora"
                        id="{{$instruktor->id}}"
                        lokacija="{{$instruktor->lokacija}}"
                        username="{{$instruktor->username}}"
                        titula="{{$instruktor->titula}}"
                        profilna_slika="{{$instruktor->profilna_slika}}"
                    ></x-profilkarticamala>
                </div>
            @endforeach
        </div>

        {{-- Paginacija --}}
        <div class="mt-6 flex flex-col items-center">
            {{ $instruktori->links() }}
        </div>
    @endif

    <script>
        document.addEventListener('livewire:navigated', () => {
            const locationFilterBtn = document.getElementById('location-filter-btn');
            const dropdownLocation = document.getElementById('dropdownLocation');
            const locationSearchInput = document.getElementById('location-search-input');
            const locationOptions = document.getElementById('location-options');

            // Function to toggle dropdown visibility
            function toggleDropdown(id) {
                const dropdown = document.getElementById(id);
                dropdown.classList.toggle('hidden');
            }

            // Attach toggle function to global scope for onclick attributes
            window.toggleDropdown = toggleDropdown;

            // Event listener for location filter button
            locationFilterBtn.addEventListener('click', (event) => {
                event.stopPropagation();
            });

            // Close dropdowns when clicking outside
            window.addEventListener('click', (event) => {
                if (!locationFilterBtn.contains(event.target) && !dropdownLocation.contains(event.target)) {
                    dropdownLocation.classList.add('hidden');
                }
            });

            // Search locations inside dropdown
            locationSearchInput.addEventListener('input', () => {
                const searchTerm = locationSearchInput.value.toLowerCase();
                Array.from(locationOptions.children).forEach(li => {
                    const labelText = li.querySelector('span').textContent.toLowerCase();
                    if (labelText.includes(searchTerm)) {
                        li.style.display = '';
                    } else {
                        li.style.display = 'none';
                    }
                });
            });
        });
    </script>
</div>
