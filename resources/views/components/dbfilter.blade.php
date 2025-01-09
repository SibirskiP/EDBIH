<div class="relative inline-block">
    <button id="dropdownCategoryButton" data-dropdown-toggle="dropdownCategory" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center inline-flex items-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800" type="button">
        {{ $buttonText }}
        <svg class="w-2.5 h-2.5 ms-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 10 6">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 4 4 4-4"/>
        </svg>
    </button>
    <!-- Dropdown menu -->
    <div id="dropdownCategory" class="z-50 hidden absolute bg-white rounded-lg shadow w-60 dark:bg-gray-700">
        <div class="p-3">
            <label for="input-group-search" class="sr-only">Search</label>
            <div class="relative">
                <div class="absolute inset-y-0 rtl:inset-r-0 start-0 flex items-center ps-3 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z"/>
                    </svg>
                </div>
                <input type="text" id="input-group-search" class="block w-full p-2 ps-10 text-sm text-gray-900 border border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500 dark:bg-gray-600 dark:border-gray-500 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500" placeholder="Pretraži kategorije">
            </div>
        </div>

        <!-- Sortiranje da označeni budu na vrhu -->
        @php
            $selected = collect($categories)->filter(function($category) {
                return is_array(request('kategorije')) && in_array($category, request('kategorije'));
            });
            $unselected = collect($categories)->filter(function($category) {
                return !is_array(request('kategorije')) || !in_array($category, request('kategorije'));
            });
            $sortedCategories = $selected->merge($unselected);
        @endphp

        <ul class="h-48 px-3 pb-3 overflow-y-auto text-sm text-gray-700 dark:text-gray-200" aria-labelledby="dropdownSearchButton">
            @foreach($sortedCategories as $category)
                <li>
                    <div class="flex items-center ps-2 rounded hover:bg-gray-100 dark:hover:bg-gray-600">
                        <input type="checkbox" value="{{ $category }}" id="{{ $category }}" name="kategorije[]"
                               class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500"
                            {{ is_array(request('kategorije')) && in_array($category, request('kategorije')) ? 'checked' : '' }}>
                        <label for="{{ $category }}" class="w-full py-2 ms-2 text-sm font-medium text-gray-900 rounded dark:text-gray-300">{{ $category }}</label>
                    </div>
                </li>
            @endforeach
        </ul>

        <a href="#" id="resetFilters1" class="flex items-center p-3 text-sm font-medium text-red-600 border-t border-gray-200 rounded-b-lg bg-gray-50 dark:border-gray-600 hover:bg-gray-100 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-red-500 hover:underline">
            <svg class="w-4 h-4 me-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 18">
                <path d="M6.5 9a4.5 4.5 0 1 0 0-9 4.5 4.5 0 0 0 0 9ZM8 10H5a5.006 5.006 0 0 0-5 5v2a1 1 0 0 0 1 1h11a1 1 0 0 0 1-1v-2a5.006 5.006 0 0 0-5-5Zm11-3h-6a1 1 0 1 0 0 2h6a1 1 0 1 0 0-2Z"/>
            </svg>
            Resetiraj filter
        </a>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const dropdownButton = document.getElementById('dropdownCategoryButton');
        const dropdownMenu = document.getElementById('dropdownCategory');
        const resetButton = document.getElementById('resetFilters1');
        const checkboxes = document.querySelectorAll('input[name="kategorije[]"]');

        dropdownButton.addEventListener('click', function () {
            dropdownMenu.classList.toggle('hidden');
        });

        // Zatvaranje dropdown-a kada se klikne van njega
        window.addEventListener('click', function (event) {
            if (!dropdownButton.contains(event.target) && !dropdownMenu.contains(event.target)) {
                dropdownMenu.classList.add('hidden');
            }
        });

        // Resetiranje filtera - odznači sve checkboxove
        resetButton.addEventListener('click', function (event) {
            event.preventDefault(); // sprječava reload stranice
            checkboxes.forEach(function (checkbox) {
                checkbox.checked = false; // Oznaka checkboxova postaje false
            });
        });
    });
</script>
