<div class="max-w-md w-full space-y-8 p-10 bg-white rounded-xl shadow-2xl">
    <div class="text-center">
        <img class="mx-auto h-12 w-auto" src="{{asset('slike/logo.png')}}" alt="Logo">
        <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
            Registriraj se
        </h2>
        <p class="mt-2 text-sm text-gray-600">
            Već si član?
            <a href="/login" class="font-medium text-blue-600 hover:text-blue-500">
                Prijavi se
            </a>
        </p>
    </div>
    <form class="mt-8 space-y-6" wire:submit.prevent="store" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="rounded-md shadow-sm -space-y-px">
            {{-- Korisničko ime --}}
            <div>
                <label for="username" class="sr-only">Korisničko ime</label>
                <input wire:model.live="username" id="username" name="username" type="text" autocomplete="username" required
                       class="appearance-none rounded-t-md relative block w-full px-3 py-2 border @error('username') border-red-500 @else border-gray-300 @enderror placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                       placeholder="Korisničko ime">
                @error('username')
                <div class="text-red-500 text-xs mt-1">{{$message}}</div>
                @enderror
            </div>
            {{-- Email --}}
            <div>
                <label for="email" class="sr-only">Email</label>
                <input wire:model.live="email" id="email" name="email" type="email" autocomplete="email" required
                       class="appearance-none relative block w-full px-3 py-2 border @error('email') border-red-500 @else border-gray-300 @enderror placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                       placeholder="Email adresa">
                @error('email')
                <div class="text-red-500 text-xs mt-1">{{$message}}</div>
                @enderror
            </div>
            {{-- ... ostala polja (titula, kontakt, opis, lokacija) bi trebalo ažurirati na isti način --}}
            <div>
                <label for="titula" class="sr-only">Titula</label>
                <input wire:model.live="titula" id="titula" name="titula" type="text" required
                       class="appearance-none relative block w-full px-3 py-2 border @error('titula') border-red-500 @else border-gray-300 @enderror placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                       placeholder="Titula (npr. vlasnik sobe)">
                @error('titula')
                <div class="text-red-500 text-xs mt-1">{{$message}}</div>
                @enderror
            </div>
            <div>
                <label for="kontakt" class="sr-only">Kontakt</label>
                <input wire:model.live="kontakt" id="kontakt" name="kontakt" type="text" required
                       class="appearance-none relative block w-full px-3 py-2 border @error('kontakt') border-red-500 @else border-gray-300 @enderror placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                       placeholder="Kontakt telefon">
                @error('kontakt')
                <div class="text-red-500 text-xs mt-1">{{$message}}</div>
                @enderror
            </div>
            <div>
                <label for="opis" class="sr-only">Opis</label>
                <textarea wire:model.live="opis" id="opis" name="opis" required
                          class="appearance-none relative block w-full px-3 py-2 border @error('opis') border-red-500 @else border-gray-300 @enderror placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                          placeholder="Kratak opis"></textarea>
                @error('opis')
                <div class="text-red-500 text-xs mt-1">{{$message}}</div>
                @enderror
            </div>
            <div>
                <label for="lokacija" class="sr-only">Lokacija</label>
                <select wire:model.live="lokacija" id="lokacija" name="lokacija" required
                        class="appearance-none relative block w-full px-3 py-2 border @error('lokacija') border-red-500 @else border-gray-300 @enderror placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm">
                    <option value="" disabled>Izaberi lokaciju</option>
                    @foreach($lokacije as $lokacija)
                        <option value="{{ $lokacija }}">{{ $lokacija }}</option>
                    @endforeach
                </select>
                @error('lokacija')
                <div class="text-red-500 text-xs mt-1">{{$message}}</div>
                @enderror
            </div>
            {{-- Lozinka --}}
            <div>
                <label for="password" class="sr-only">Lozinka</label>
                <input wire:model.live="password" id="password" name="password" type="password" autocomplete="new-password" required
                       class="appearance-none relative block w-full px-3 py-2 border @error('password') border-red-500 @else border-gray-300 @enderror placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                       placeholder="Lozinka">
                @error('password')
                <div class="text-red-500 text-xs mt-1">{{$message}}</div>
                @enderror
            </div>
            {{-- Potvrdi lozinku --}}
            <div>
                <label for="password_confirmation" class="sr-only">Potvrdi lozinku</label>
                <input wire:model.live="password_confirmation" id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required
                       class="appearance-none rounded-b-md relative block w-full px-3 py-2 border @error('password_confirmation') border-red-500 @else border-gray-300 @enderror placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                       placeholder="Potvrdi lozinku">
                @error('password_confirmation')
                <div class="text-red-500 text-xs mt-1">{{$message}}</div>
                @enderror
            </div>
        </div>

        {{-- Upload slike --}}
        <div class="rounded-xl border-2 border-dashed border-gray-300 p-6 text-center mt-6">
            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.232-3.232a1 1 0 00-1.272 0l-1.396 1.395A1 1 0 0028.98 22.842l-6.52 6.521m-1.398-1.398l-5.6-5.6A1 1 0 0013 22.45v-4m15-4a4 4 0 11-8 0 4 4 0 018 0z" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
            <div class="flex text-sm text-gray-600 mt-2">
                <label for="profilna_slika" class="relative cursor-pointer bg-white rounded-md font-medium text-blue-600 hover:text-blue-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-blue-500">
                    <span>Izaberi sliku</span>
                    <input wire:model="profilna_slika" id="profilna_slika" name="profilna_slika" type="file" class="sr-only" />
                </label>
                <p class="pl-1">ili je prevuci</p>
            </div>
            <p class="text-xs text-gray-500">
                PNG, JPG, do 1MB (opcionalno)
            </p>
            @if ($profilna_slika)
                <div class="text-center text-sm text-gray-500 mt-2">Slika je odabrana.</div>
            @endif
            @error('profilna_slika')
            <div class="text-red-500 text-xs mt-1">{{$message}}</div>
            @enderror
        </div>
        <div>
            <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Registracija
            </button>
        </div>
    </form>
</div>
