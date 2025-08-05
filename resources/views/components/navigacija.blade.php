<style>
    /* Custom CSS for animated underline on nav links */
    .nav-link-underline {
        position: relative;
        display: inline-block;
    }
    .nav-link-underline::after {
        content: '';
        position: absolute;
        width: 0;
        height: 2px;
        bottom: -4px; /* Adjust this value to move the underline */
        left: 50%;
        transform: translateX(-50%);
        background-color: currentColor; /* Inherits color from the link */
        transition: width 0.3s ease-out;
    }
    .nav-link-underline:hover::after,
    .nav-link-underline.active::after {
        width: 100%;
    }

    /* Mobile Menu specific styles */
    .mobile-menu-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent overlay */
        z-index: 40; /* Below navbar, above content */
        transition: opacity 0.3s ease-in-out;
        opacity: 0;
        visibility: hidden;
    }
    .mobile-menu-overlay.open {
        opacity: 1;
        visibility: visible;
    }

    .mobile-menu-panel {
        position: fixed;
        top: 0;
        right: 0;
        width: 80%; /* Adjust width as needed */
        max-width: 320px; /* Max width for larger mobiles */
        height: 100%;
        background-color: #ffffff; /* Solid white background for the menu */
        z-index: 50; /* Above overlay */
        box-shadow: -5px 0 15px rgba(0, 0, 0, 0.1); /* Shadow for depth */
        transform: translateX(100%); /* Start off-screen */
        transition: transform 0.3s ease-in-out;
        display: flex; /* Use flexbox for internal layout */
        flex-direction: column; /* Stack items vertically */
    }
    .mobile-menu-panel.open {
        transform: translateX(0); /* Slide in */
    }

    .mobile-menu-panel ul {
        flex-grow: 1; /* Allow the list to take available space */
    }
</style>

<nav class="bg-white/80 backdrop-blur-sm shadow-md fixed w-full z-50 top-0 start-0 border-b border-slate-100">
    <div class="max-w-screen-xl flex flex-wrap items-center justify-between mx-auto p-4 md:py-4">
        {{-- Logo --}}
        <a href="/" class="flex items-center space-x-2 rtl:space-x-reverse transform transition-transform hover:scale-105 duration-200">
            <img src="{{asset('slike/logo.png')}}" class="h-6" alt="EDBIH Logo">
            <span class="self-center text-xl font-bold text-slate-800 whitespace-nowrap tracking-tight">EDBIH</span>
        </a>

        {{-- Dugmad za prijavu/profil i Hamburger --}}
        <div class="flex md:order-2 items-center space-x-3 md:space-x-0 rtl:space-x-reverse">
            @guest
                <form action="/register">
                    <button type="submit" class="p-2 text-slate-600 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-200 rounded-full transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        <span class="sr-only">Prijava</span>
                    </button>
                </form>
            @endguest
            @auth
                <form action="/instruktori/{{auth()->id()}}" method="get">
                    <button type="submit" class="p-2 text-slate-600 hover:text-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-200 rounded-full transition-colors duration-200">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                        </svg>
                        <span class="sr-only">Moj profil</span>
                    </button>
                </form>
            @endauth

            {{-- Hamburger dugme za mobilne --}}
            <button id="mobile-menu-toggle" type="button" class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-slate-500 rounded-full md:hidden hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-200 transition-all duration-200" aria-controls="mobile-menu-panel" aria-expanded="false">
                <span class="sr-only">Otvori glavni meni</span>
                <svg class="w-5 h-5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>
        </div>

        {{-- Navigacijski linkovi (Desktop) --}}
        <div class="items-center justify-between hidden w-full md:flex md:w-auto md:order-1" id="navbar-sticky">
            <ul class="flex flex-col p-4 md:p-0 mt-4 font-medium  rounded-lg  md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 ">
                <li>
                    <a href="/" class="nav-link-underline block py-2 px-3 {{ request()->is('/') ? 'text-blue-600 font-semibold active' : 'text-slate-700' }} rounded-lg md:bg-transparent hover:text-blue-600 md:p-0 transition-colors duration-200">Početna</a>
                </li>
                <li>
                    <a href="/instrukcije" class="nav-link-underline block py-2 px-3 {{ request()->is('instrukcije') ? 'text-blue-600 font-semibold active' : 'text-slate-700' }} rounded-lg md:bg-transparent hover:text-blue-600 md:hover:text-blue-600 md:p-0 transition-colors duration-200">Instrukcije</a>
                </li>
                <li>
                    <a href="/instruktori" class="nav-link-underline block py-2 px-3 {{ request()->is('instruktori') ? 'text-blue-600 font-semibold active' : 'text-slate-700' }} rounded-lg md:bg-transparent hover:text-blue-600 md:hover:text-blue-600 md:p-0 transition-colors duration-200">Korisnici</a>
                </li>
                <li>
                    <a href="/materijali" class="nav-link-underline block py-2 px-3 {{ request()->is('materijali') ? 'text-blue-600 font-semibold active' : 'text-slate-700' }} rounded-lg md:bg-transparent hover:text-blue-600 md:hover:text-blue-600 md:p-0 transition-colors duration-200">Materijali</a>
                </li>
                <li>
                    <a href="/objave" class="nav-link-underline block py-2 px-3 {{ request()->is('objave') ? 'text-blue-600 font-semibold active' : 'text-slate-700' }} rounded-lg md:bg-transparent hover:text-blue-600 md:hover:text-blue-600 md:p-0 transition-colors duration-200">Objave</a>
                </li>
                <li>
                    <a href="/chat" class="nav-link-underline block py-2 px-3 {{ request()->is('chat') ? 'text-blue-600 font-semibold active' : 'text-slate-700' }} rounded-lg md:bg-transparent hover:text-blue-600 md:hover:text-blue-600 md:p-0 transition-colors duration-200">Chat</a>
                </li>

                <li>
                    <a href="/sobe" class="nav-link-underline block py-2 px-3 {{ request()->is('sobe') ? 'text-blue-600 font-semibold active' : 'text-slate-700' }} rounded-lg md:bg-transparent hover:text-blue-600 md:hover:text-blue-600 md:p-0 transition-colors duration-200">Sobe</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

{{-- Mobile Menu Overlay and Panel --}}
<div id="mobile-menu-overlay" class="mobile-menu-overlay"></div>
<div id="mobile-menu-panel" class="mobile-menu-panel">
    <div class="flex justify-end p-4 border-b border-slate-100">
        <button id="mobile-menu-close" class="text-slate-500 hover:text-slate-700 focus:outline-none">
            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
    <ul class="flex flex-col p-4 space-y-2">
        <li>
            <a href="/" class="block py-3 px-4 text-lg {{ request()->is('/') ? 'text-blue-600 font-semibold' : 'text-slate-700' }} rounded-lg hover:bg-slate-100 transition-colors duration-200">Početna</a>
        </li>
        <li>
            <a href="/instrukcije" class="block py-3 px-4 text-lg {{ request()->is('instrukcije') ? 'text-blue-600 font-semibold' : 'text-slate-700' }} rounded-lg hover:bg-slate-100 transition-colors duration-200">Instrukcije</a>
        </li>
        <li>
            <a href="/instruktori" class="block py-3 px-4 text-lg {{ request()->is('instruktori') ? 'text-blue-600 font-semibold' : 'text-slate-700' }} rounded-lg hover:bg-slate-100 transition-colors duration-200">Korisnici</a>
        </li>
        <li>
            <a href="/materijali" class="block py-3 px-4 text-lg {{ request()->is('materijali') ? 'text-blue-600 font-semibold' : 'text-slate-700' }} rounded-lg hover:bg-slate-100 transition-colors duration-200">Materijali</a>
        </li>
        <li>
            <a href="/objave" class="block py-3 px-4 text-lg {{ request()->is('objave') ? 'text-blue-600 font-semibold' : 'text-slate-700' }} rounded-lg hover:bg-slate-100 transition-colors duration-200">Objave</a>
        </li>
        <li>
            <a href="/chat" class="block py-3 px-4 text-lg {{ request()->is('chat') ? 'text-blue-600 font-semibold' : 'text-slate-700' }} rounded-lg hover:bg-slate-100 transition-colors duration-200">Chat</a>
        </li>

        <li>
            <a href="/sobe" class="block py-3 px-4 text-lg {{ request()->is('sobe') ? 'text-blue-600 font-semibold' : 'text-slate-700' }} rounded-lg hover:bg-slate-100 transition-colors duration-200">Sobe</a>
        </li>

    </ul>
    <div class="p-4 border-t border-slate-100 mt-auto">
        @guest
            <form action="/register">
                <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-3 rounded-lg shadow-md hover:bg-blue-700 transition-colors duration-200">
                    Prijava
                </button>
            </form>
        @endguest
        @auth
            <form action="/instruktori/{{auth()->id()}}" method="get">
                <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-3 rounded-lg shadow-md hover:bg-blue-700 transition-colors duration-200">
                    Moj profil
                </button>
            </form>
        @endauth
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function () {
        const mobileMenuToggle = document.getElementById('mobile-menu-toggle');
        const mobileMenuPanel = document.getElementById('mobile-menu-panel');
        const mobileMenuClose = document.getElementById('mobile-menu-close');
        const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
        const body = document.body;

        if (mobileMenuToggle && mobileMenuPanel && mobileMenuClose && mobileMenuOverlay) {
            mobileMenuToggle.addEventListener('click', function () {
                mobileMenuPanel.classList.toggle('open');
                mobileMenuOverlay.classList.toggle('open');
                this.setAttribute('aria-expanded', mobileMenuPanel.classList.contains('open'));
                body.classList.toggle('overflow-hidden'); // Prevent scrolling on body
            });

            mobileMenuClose.addEventListener('click', function () {
                mobileMenuPanel.classList.remove('open');
                mobileMenuOverlay.classList.remove('open');
                mobileMenuToggle.setAttribute('aria-expanded', 'false');
                body.classList.remove('overflow-hidden');
            });

            mobileMenuOverlay.addEventListener('click', function () {
                mobileMenuPanel.classList.remove('open');
                mobileMenuOverlay.classList.remove('open');
                mobileMenuToggle.setAttribute('aria-expanded', 'false');
                body.classList.remove('overflow-hidden');
            });
        }

        // Livewire specific re-initialization for dropdowns in filter section
        document.addEventListener('livewire:navigated', () => {
            const locationFilterBtn = document.getElementById('location-filter-btn');
            const dropdownLocation = document.getElementById('dropdownLocation');
            const locationSearchInput = document.getElementById('location-search-input');
            const locationOptions = document.getElementById('location-options');

            function toggleDropdown(id) {
                const dropdown = document.getElementById(id);
                dropdown.classList.toggle('hidden');
            }
            window.toggleDropdown = toggleDropdown;

            locationFilterBtn.addEventListener('click', (event) => {
                event.stopPropagation();
            });

            window.addEventListener('click', (event) => {
                if (!locationFilterBtn.contains(event.target) && !dropdownLocation.contains(event.target)) {
                    dropdownLocation.classList.add('hidden');
                }
            });

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
    });
</script>
