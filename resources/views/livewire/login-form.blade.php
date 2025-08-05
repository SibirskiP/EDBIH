<div class="max-w-md w-full space-y-8 p-10 bg-white rounded-xl shadow-2xl">
    <div class="text-center">
        <img class="mx-auto h-12 w-auto" src="{{asset('slike/logo.png')}}" alt="Logo">
        <h2 class="mt-6 text-3xl font-extrabold text-gray-900">
            Prijavi se na svoj račun
        </h2>
        <p class="mt-2 text-sm text-gray-600">
            Nemaš korisnički račun?
            <a href="/register" class="font-medium text-blue-600 hover:text-blue-500">
                Registruj se
            </a>
        </p>
    </div>
    <form class="mt-8 space-y-6" wire:submit.prevent="login">
        @csrf
        <div class="rounded-md shadow-sm -space-y-px">
            {{-- Email --}}
            <div>
                <label for="email" class="sr-only">Email adresa</label>
                <input wire:model.live="email" id="email" name="email" type="email" autocomplete="email" required
                       class="appearance-none rounded-t-md relative block w-full px-3 py-2 border @error('email') border-red-500 @else border-gray-300 @enderror placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                       placeholder="Email adresa">
                @error('email')
                <div class="text-red-500 text-xs mt-1">{{$message}}</div>
                @enderror
            </div>
            {{-- Lozinka --}}
            <div>
                <label for="password" class="sr-only">Lozinka</label>
                <input wire:model.live="password" id="password" name="password" type="password" autocomplete="current-password" required
                       class="appearance-none rounded-b-md relative block w-full px-3 py-2 border @error('password') border-red-500 @else border-gray-300 @enderror placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-blue-500 focus:border-blue-500 focus:z-10 sm:text-sm"
                       placeholder="Lozinka">
                @error('password')
                <div class="text-red-500 text-xs mt-1">{{$message}}</div>
                @enderror
            </div>
        </div>

        <div class="flex items-center justify-end">
            <div class="text-sm">
                <a href="/forgot-password" class="font-medium text-blue-600 hover:text-blue-500">
                    Zaboravio si lozinku?
                </a>
            </div>
        </div>

        <div>
            <button type="submit" class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                Prijava
            </button>
        </div>
    </form>
</div>
