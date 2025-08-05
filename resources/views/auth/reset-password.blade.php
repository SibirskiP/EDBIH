<x-layout>
    {{-- Glavni kontejner za centriranje i pozadinu --}}
    <section class="bg-gradient-to-br from-blue-50 to-indigo-50 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
        {{-- Moderni "card" dizajn --}}
        <div class="max-w-md w-full space-y-8 p-10 bg-white rounded-xl shadow-2xl">
            {{-- Vizualni element - Ikona za katanac --}}
            <div class="flex justify-center mb-6">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-blue-600" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                </svg>
            </div>

            {{-- Naslov i opis --}}
            <div class="text-center">
                <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
                    Promijeni lozinku
                </h2>
                <p class="mt-2 text-md text-gray-600">
                    Unesite novu lozinku za vaš račun.
                </p>
            </div>

            {{-- Forma za promjenu lozinke --}}
            <form class="space-y-6" action="{{ route('password.update') }}" method="POST">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email adresa</label>
                    <div class="mt-1">
                        <input id="email" name="email" type="email" autocomplete="email" required
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    @error('email')
                    <span class="mt-2 text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Nova lozinka</label>
                    <div class="mt-1">
                        <input id="password" name="password" type="password" required
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    @error('password')
                    <span class="mt-2 text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Potvrdi novu lozinku</label>
                    <div class="mt-1">
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                               class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                    </div>
                    @error('password_confirmation')
                    <span class="mt-2 text-sm text-red-600">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <button type="submit"
                            class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150 ease-in-out">
                        Promijeni lozinku
                    </button>
                </div>
            </form>
        </div>
    </section>
</x-layout>
