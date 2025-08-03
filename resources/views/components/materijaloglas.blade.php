<?php
$trenutni = \App\Models\User::find($userId);
$defaultProfilePic = asset('storage/profilne_slike/default_profile_pic.PNG');
$imageUrl = $trenutni->profilna_slika ? asset('storage/' . $trenutni->profilna_slika) : null;

$initials = '';
if (!$imageUrl && $trenutni) {
    $nameParts = explode(' ', $trenutni->username);
    foreach ($nameParts as $part) {
        $initials .= strtoupper(substr($part, 0, 1));
    }
    if (strlen($initials) > 2) {
        $initials = substr($initials, 0, 2);
    }
}
?>

<article class="p-6 bg-white rounded-2xl border border-slate-100 shadow-xl shadow-blue-50/15 transform transition-all duration-300 hover:scale-[1.02] hover:shadow-2xl hover:shadow-blue-100/20 flex flex-col h-full">
    {{-- Gornji dio kartice: Kategorija i Vrijeme --}}
    <div class="flex justify-between items-center mb-4">
        <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-sm font-semibold text-blue-700">
            {{ $kategorija }}
        </span>
        <span class="text-sm text-slate-500 flex items-center">
            <svg class="w-4 h-4 mr-1 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{$starostInstrukcije}}
        </span>
    </div>

    {{-- Naslov materijala --}}
    <h2 class="mb-3 text-2xl font-extrabold tracking-tight text-slate-900 leading-tight">
        <a href="{{ route('materijali.download', $id) }}" class="hover:text-blue-600 transition-colors duration-200">
            {{$nazivInstrukcije}}
        </a>
    </h2>

    {{-- Opis materijala --}}
    <p class="mb-6 text-base text-slate-600 leading-relaxed flex-grow"> {{-- flex-grow da zauzme sav dostupan prostor --}}
        {{$opisInstrukcije}}
    </p>

    {{-- Donji dio kartice: Instruktor i Akcije --}}
    <div class="flex justify-between items-center mt-auto"> {{-- mt-auto gura ovaj div na dno --}}
        {{-- Podaci o instruktoru --}}
        <div class="flex items-center space-x-3">
            @if ($imageUrl)
                <img class="w-9 h-9 rounded-full object-cover border-2 border-white shadow-sm"
                     src="{{ $imageUrl }}"
                     alt="Profilna slika {{$instruktor}}" />
            @else
                <div class="w-9 h-9 rounded-full bg-blue-500 flex items-center justify-center text-white text-base font-bold border-2 border-white shadow-sm">
                    {{ $initials ?: '?' }}
                </div>
            @endif
            <span class="font-semibold text-slate-800 text-base">
                {{$instruktor}}
            </span>
        </div>

        {{-- Dugmad za akcije --}}
        <div class="flex items-center gap-2">
            <a href="{{ route('materijali.download', $id) }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg shadow-md hover:bg-blue-700 transition-colors duration-200">
                <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                </svg>
                Preuzmi
            </a>

            @if(auth()->user() && auth()->user()->id === $trenutni->id)
                <form action="/materijali/{{$id}}" method="POST" onsubmit="return confirm('Da li ste sigurni da želite obrisati ovaj materijal?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-red-500 text-white text-sm font-medium rounded-lg shadow-md hover:bg-red-600 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                        </svg>
                        Izbriši
                    </button>
                </form>
            @endif
        </div>
    </div>
</article>
