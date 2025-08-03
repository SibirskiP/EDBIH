<?php
$trenutni = \App\Models\User::find($id); // Dohvaćanje korisnika unutar komponente
$defaultProfilePic = asset('storage/profilne_slike/default_profile_pic.PNG'); // Vaša defaultna slika
$imageUrl = $trenutni->profilna_slika ? asset('storage/' . $trenutni->profilna_slika) : null; // Postavi na null ako nema slike

$initials = '';
if (!$imageUrl && $trenutni) { // Generiraj inicijale samo ako nema slike i korisnik postoji
    $nameParts = explode(' ', $trenutni->username); // Koristite $trenutni->username
    foreach ($nameParts as $part) {
        $initials .= strtoupper(substr($part, 0, 1));
    }
    if (strlen($initials) > 2) { // Ograniči na 2 inicijala ako je ime duže
        $initials = substr($initials, 0, 2);
    }
}
?>

<div class="w-full max-w-sm bg-white border border-slate-100 rounded-3xl shadow-xl shadow-blue-50/20 overflow-hidden transform transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl hover:shadow-blue-100/30">
    <div class="flex flex-col items-center p-5 text-center h-full"> {{-- Smanjen padding na p-5 --}}
        <div class="relative mb-3"> {{-- Smanjen mb-3 --}}
            @if ($imageUrl)
                {{-- Prikazuje profilnu sliku ako postoji --}}
                <img src="{{ $imageUrl }}" alt="Profilna slika {{ $trenutni->username }}"
                     class="h-20 w-20 rounded-full object-cover border-4 border-white shadow-md" {{-- Smanjena veličina slike na h-20 w-20 --}}
                     onerror="this.onerror=null;this.src='{{ $defaultProfilePic }}';">
            @else
                {{-- Prikazuje inicijale ako nema profilne slike --}}
                <div class="h-20 w-20 rounded-full bg-blue-500 flex items-center justify-center text-white text-xl font-bold border-4 border-white shadow-md"> {{-- Smanjena veličina i font inicijala --}}
                    {{ $initials ?: '?' }}
                </div>
            @endif
        </div>
        <h5 class="mb-0 text-lg font-bold text-slate-800">{{ $trenutni->username }}</h5> {{-- Smanjen font na text-lg --}}
        <span class="text-sm text-slate-500 mb-2 font-medium">{{ $trenutni->titula }}</span> {{-- Smanjen mb na mb-2 --}}

        <div class="flex items-center text-xs text-slate-600 mb-3"> {{-- Smanjen font na text-xs i mb na mb-3 --}}
            <svg class="w-3 h-3 mr-1 text-slate-500" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M11.906 1.994a8.002 8.002 0 0 1 8.09 8.421 7.996 7.996 0 0 1-1.297 3.957.996.996 0 0 1-.133.204l-.108.129c-.178.243-.37.477-.573.699l-5.112 6.224a1 1 0 0 1-1.545 0L5.982 15.26l-.002-.002a18.146 18.146 0 0 1-.309-.38l-.133-.163a.999.999 0 0 1-.13-.202 7.995 7.995 0 0 1 6.498-12.518ZM15 9.997a3 3 0 1 1-5.999 0 3 3 0 0 1 5.999 0Z" clip-rule="evenodd"/>
            </svg>
            <span class="text-slate-700">{{ $trenutni->lokacija }}</span>
        </div>

        <div class="flex flex-wrap justify-center gap-1 mb-4 min-h-[30px]"> {{-- Smanjen gap na gap-1 i min-h na 30px, mb na mb-4 --}}
            @foreach($kategorije as $kat)
                <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-sm font-semibold text-blue-700">
            {{ $kat }}
        </span>
            @endforeach
        </div>

        <a href="/instruktori/{{ $trenutni->id }}" class="w-full inline-flex items-center justify-center px-4 py-2 text-sm font-semibold text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:outline-none focus:ring-blue-300 transition-colors duration-200 shadow-md mt-auto"> {{-- Smanjen px i py --}}
            Posjeti profil
            <svg class="w-4 h-4 ml-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
            </svg>
        </a>
    </div>
</div>

