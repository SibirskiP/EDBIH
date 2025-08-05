<x-layout>
    {{-- Hero Sekcija --}}
    <section class="relative overflow-hidden py-32 sm:py-40 lg:py-48 bg-gradient-to-br from-blue-50 to-indigo-50"> {{-- Prilagođen padding za jednake gornje i donje razmake --}}
        {{-- Apstraktni pozadinski oblici za moderni vizualni efekt --}}
        <div class="absolute inset-0 z-0 opacity-40">
            <svg class="absolute top-0 left-0 w-full h-full" viewBox="0 0 1440 800" fill="none" xmlns="http://www.w3.org/2000/svg">
                {{-- Krugovi s radijalnim gradijentima --}}
                <circle cx="10%" cy="20%" r="150" fill="url(#heroGradient1)" opacity="0.7"/>
                <circle cx="90%" cy="80%" r="200" fill="url(#heroGradient2)" opacity="0.7"/>
                {{-- Pravokutni oblik s linearnim gradijentom, BEZ clip-path-a --}}
                <rect x="0" y="0" width="100%" height="100%" fill="url(#heroGradient3)" opacity="0.5"/> {{-- Korišten rect umjesto path za jednostavnost --}}
                <defs>
                    <radialGradient id="heroGradient1" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(144 144) rotate(90) scale(144)">
                        <stop stop-color="#60A5FA"/>
                        <stop offset="1" stop-color="#3B82F6" stop-opacity="0"/>
                    </radialGradient>
                    <radialGradient id="heroGradient2" cx="0" cy="0" r="1" gradientUnits="userSpaceOnUse" gradientTransform="translate(1296 656) rotate(90) scale(192)">
                        <stop stop-color="#818CF8"/>
                        <stop offset="1" stop-color="#6366F1" stop-opacity="0"/>
                    </radialGradient>
                    <linearGradient id="heroGradient3" x1="720" y1="0" x2="720" y2="300" gradientUnits="userSpaceOnUse">
                        <stop stop-color="#BFDBFE"/>
                        <stop offset="1" stop-color="#EFF6FF" stop-opacity="0"/>
                    </linearGradient>
                </defs>
            </svg>
        </div>

        <div class="mx-auto max-w-3xl text-center relative z-10 px-4 sm:px-6 lg:px-8">
            <h1 class="text-5xl sm:text-6xl lg:text-7xl font-extrabold tracking-tight text-slate-900 leading-tight">
                Sve za <span class="text-blue-600">edukaciju</span> na jednom mjestu
            </h1>
            <p class="mt-6 text-lg sm:text-xl leading-8 text-slate-700 max-w-2xl mx-auto">
                Pronađite ili objavite instrukcije, kurseve i materijale za učenje, pridružite se drugim članovima u sobama za učenje i još mnogo toga.
            </p>
            <div class="mt-10 flex flex-col sm:flex-row items-center justify-center gap-4">
                @guest
                    <a href="/register" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-semibold rounded-full shadow-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-300 transform hover:-translate-y-1 hover:scale-105">
                        Registracija
                    </a>
                    <a href="/login" class="w-full sm:w-auto inline-flex items-center justify-center px-8 py-3 text-base font-semibold text-blue-600 bg-white border border-blue-600 rounded-full shadow-md hover:bg-blue-50 hover:text-blue-700 transition-all duration-300 transform hover:-translate-y-1 hover:scale-105">
                        Prijava <span class="ml-2" aria-hidden="true">&rarr;</span>
                    </a>
                @endguest
            </div>
        </div>
    </section>

    {{-- "Šta Nudimo" Sekcija (Kategorije) --}}
    <section class="py-16 sm:py-24 bg-slate-50">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-3xl sm:text-4xl font-bold tracking-tight text-slate-800">Istražite naše kategorije</h2>
                <p class="mt-3 text-lg text-slate-600 max-w-xl mx-auto">
                    Pronađite instrukcije i materijale prilagođene vašem nivou obrazovanja.
                </p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                {{-- Kartica: Osnovna škola --}}
                <a href="#" class="block bg-white rounded-2xl shadow-lg border border-slate-100 p-8 text-center transform transition-all duration-300 hover:scale-[1.03] hover:shadow-xl hover:shadow-blue-50/20 group">
                    <div class="inline-flex items-center justify-center p-4 rounded-full bg-blue-100 text-blue-600 mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-200">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18s-3.332.477-4.5 1.253" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-800 mb-2 group-hover:text-blue-700 transition-colors duration-200">Osnovna škola</h3>
                    <p class="text-slate-600 text-sm">Materijali i instrukcije za učenike osnovnih škola.</p>
                </a>

                {{-- Kartica: Srednja škola --}}
                <a href="#" class="block bg-white rounded-2xl shadow-lg border border-slate-100 p-8 text-center transform transition-all duration-300 hover:scale-[1.03] hover:shadow-xl hover:shadow-blue-50/20 group">
                    <div class="inline-flex items-center justify-center p-4 rounded-full bg-blue-100 text-blue-600 mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-200">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10 10l4 4m0-4l-4 4" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-800 mb-2 group-hover:text-blue-700 transition-colors duration-200">Srednja škola</h3>
                    <p class="text-slate-600 text-sm">Podrška za srednjoškolce u svim predmetima.</p>
                </a>

                {{-- Kartica: Fakultet --}}
                <a href="#" class="block bg-white rounded-2xl shadow-lg border border-slate-100 p-8 text-center transform transition-all duration-300 hover:scale-[1.03] hover:shadow-xl hover:shadow-blue-50/20 group">
                    <div class="inline-flex items-center justify-center p-4 rounded-full bg-blue-100 text-blue-600 mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-200">

                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 14l9-5-9-5-9 5 9 5zm0 0l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-800 mb-2 group-hover:text-blue-700 transition-colors duration-200">Fakultet</h3>
                    <p class="text-slate-600 text-sm">Stručna pomoć i resursi za studente fakulteta.</p>
                </a>

                {{-- Kartica: Ostalo --}}
                <a href="#" class="block bg-white rounded-2xl shadow-lg border border-slate-100 p-8 text-center transform transition-all duration-300 hover:scale-[1.03] hover:shadow-xl hover:shadow-blue-50/20 group">
                    <div class="inline-flex items-center justify-center p-4 rounded-full bg-blue-100 text-blue-600 mb-6 group-hover:bg-blue-600 group-hover:text-white transition-colors duration-200">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-800 mb-2 group-hover:text-blue-700 transition-colors duration-200">Ostalo</h3>
                    <p class="text-slate-600 text-sm">Različiti kursevi i materijali izvan formalnog obrazovanja.</p>
                </a>
            </div>
        </div>
    </section>

    {{-- "Kako Funkcioniše" Sekcija --}}
    <section class="py-16 sm:py-24 bg-white">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-3xl sm:text-4xl font-bold tracking-tight text-slate-800">Kako EDBIH funkcioniše?</h2>
                <p class="mt-3 text-lg text-slate-600 max-w-xl mx-auto">
                    Jednostavni koraci do vašeg uspjeha u učenju.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-12">
                {{-- Korak 1 --}}
                <div class="flex flex-col items-center text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-600 text-white text-2xl font-bold mb-6 shadow-lg">
                        1
                    </div>
                    <h3 class="text-xl font-semibold text-slate-800 mb-3">Pronađi instruktora</h3>
                    <p class="text-slate-600 text-base">Pretražite našu bazu stručnih instruktora i pronađite idealnog mentora za vaše potrebe.</p>
                </div>

                {{-- Korak 2 --}}
                <div class="flex flex-col items-center text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-600 text-white text-2xl font-bold mb-6 shadow-lg">
                        2
                    </div>
                    <h3 class="text-xl font-semibold text-slate-800 mb-3">Zakaži instrukcije</h3>
                    <p class="text-slate-600 text-base">Jednostavno dogovorite termin i lokaciju (uživo ili online) za vaše instrukcije direktno s instruktorom.</p>
                </div>

                {{-- Korak 3 --}}
                <div class="flex flex-col items-center text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-600 text-white text-2xl font-bold mb-6 shadow-lg">
                        3
                    </div>
                    <h3 class="text-xl font-semibold text-slate-800 mb-3">Iskoristi resurse</h3>
                    <p class="text-slate-600 text-base">Iskoristite resurse i podršku da unaprijedite svoje znanje i vještine.</p>
                </div>

                {{-- Korak 4 --}}
                <div class="flex flex-col items-center text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-600 text-white text-2xl font-bold mb-6 shadow-lg">
                        4
                    </div>
                    <h3 class="text-xl font-semibold text-slate-800 mb-3">Uči zajedno s drugima</h3>
                    <p class="text-slate-600 text-base">Komunicirajte i učite zajedno s drugim članovima u sobama za učenje</p>
                </div>

                {{-- Korak 5 --}}
                <div class="flex flex-col items-center text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-600 text-white text-2xl font-bold mb-6 shadow-lg">
                        5
                    </div>
                    <h3 class="text-xl font-semibold text-slate-800 mb-3">Postavi pitanja</h3>
                    <p class="text-slate-600 text-base">Postavi pitanja ili traži pomoć od drugih u sekcijama za objave</p>
                </div>


                {{-- Korak 6 --}}
                <div class="flex flex-col items-center text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-blue-600 text-white text-2xl font-bold mb-6 shadow-lg">
                        6
                    </div>
                    <h3 class="text-xl font-semibold text-slate-800 mb-3">Istražite</h3>
                    <p class="text-slate-600 text-base">Istražite, objavite ili komentarišite novosti iz svijeta edukacije</p>
                </div>

            </div>
        </div>
    </section>

    {{-- Završni CTA --}}
    <section class="bg-gradient-to-r from-blue-600 to-blue-800 py-16 sm:py-20 text-white text-center">
        <div class="mx-auto max-w-3xl px-4 sm:px-6 lg:px-8">
            <h2 class="text-3xl sm:text-4xl font-bold mb-6 leading-tight">
                Spremni za novi nivo učenja?
            </h2>
            <p class="text-lg mb-8 opacity-90">
                Pridružite se našoj zajednici i započnite svoje putovanje ka uspjehu već danas!
            </p>
            <a href="/register" class="inline-flex items-center justify-center px-10 py-4 border border-white text-base font-semibold rounded-full shadow-lg text-white bg-transparent hover:bg-white hover:text-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-300 transition-all duration-300 transform hover:-translate-y-1 hover:scale-105">
                Registrujte se besplatno
            </a>
        </div>
    </section>

    {{-- Podnožje (Footer) - Preuzeto iz main-layout-blade --}}


</x-layout>
