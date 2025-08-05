@vite('resources/css/app.css')

<x-layout>
    {{-- Glavni kontejner za centriranje i pozadinu --}}
    <section class="bg-gradient-to-br from-blue-50 to-indigo-50 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        {{-- Moderni "card" dizajn --}}
        <div class="max-w-md w-full space-y-8 p-10 bg-white rounded-xl shadow-2xl">
            {{-- Vizualni element - Ikona za mail --}}
            <div class="flex justify-center mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                </svg>
            </div>

            {{-- Naslov i opis --}}
            <div class="text-center">
                <h1 class="text-3xl font-extrabold text-gray-900">
                    Potvrdi svoj mail
                </h1>
                <p class="mt-2 text-md text-gray-600">
                    Da bi mogao koristiti sve funkcije profila, potrebno je potvrditi email adresu.
                    Provjeri svoj inbox za verifikacijski link.
                </p>
            </div>

            {{-- Prikaz poruke o uspješnom slanju --}}
            @if (session('message'))
                <div class="mt-4 p-4 text-sm text-green-700 bg-green-100 rounded-lg dark:bg-green-200 dark:text-green-800" role="alert">
                    <span class="font-medium">Uspješno!</span> Verifikacijski link je ponovno poslan.
                </div>
            @endif

            {{-- Forma za ponovno slanje linka --}}
            <form method="POST" action="/email/verification-notification" class="flex justify-center">
                @csrf
                <button
                    class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out"
                    type="submit">
                    Pošalji verifikacijski email
                </button>
            </form>

        </div>
    </section>
</x-layout>
