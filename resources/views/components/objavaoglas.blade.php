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

<article class="p-6 bg-white rounded-2xl border border-slate-100 shadow-xl shadow-blue-50/15 transform transition-all duration-300 hover:scale-[1.01] hover:shadow-2xl hover:shadow-blue-100/20 flex flex-col">
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

    {{-- Naslov objave --}}
    <h2 class="mb-3 text-2xl font-extrabold tracking-tight text-slate-900 leading-tight">
        <a href="/objave/{{ $id }}" class="hover:text-blue-600 transition-colors duration-200">
            {{$nazivInstrukcije}}
        </a>
    </h2>

    {{-- Sadržaj/Opis objave --}}
    <p class="mb-6 text-base text-slate-600 leading-relaxed flex-grow">
        {{$opisInstrukcije}}
    </p>

    {{-- Priložena datoteka (slika/PDF) --}}
    @if($putanja)
        @php
            $ekstenzija = pathinfo($putanja, PATHINFO_EXTENSION);
            $fullPath = asset('storage/' . $putanja);
        @endphp

        <div class="mt-4 mb-6">
            @if(in_array(strtolower($ekstenzija), ['jpg', 'jpeg', 'png', 'gif']))
                {{-- Ako je slika --}}
                <a href="{{ $fullPath }}" target="_blank" class="block w-full h-72 rounded-lg overflow-hidden border border-slate-200 shadow-md group cursor-pointer"> {{-- Dodan a tag za otvaranje u novom tabu --}}
                    <img src="{{ $fullPath }}"
                         alt="Priložena slika"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"> {{-- object-cover i hover efekat --}}
                </a>
            @elseif(strtolower($ekstenzija) === 'pdf')
                {{-- Ako je PDF --}}
                <div class="flex items-center p-4 bg-blue-50 rounded-lg border border-blue-200 text-blue-800">
                    <svg class="w-6 h-6 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-sm font-medium truncate">Preuzmi PDF: <a href="{{ $fullPath }}" class="text-blue-600 hover:underline" target="_blank">{{ basename($putanja) }}</a></p>
                </div>
            @else
                {{-- Ostale datoteke --}}
                <div class="flex items-center p-4 bg-slate-50 rounded-lg border border-slate-200 text-slate-700">
                    <svg class="w-6 h-6 mr-3 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.485L20.5 13.5" />
                    </svg>
                    <p class="text-sm font-medium truncate">Preuzmi datoteku: <a href="{{ $fullPath }}" class="text-blue-600 hover:underline" target="_blank">{{ basename($putanja) }}</a></p>
                </div>
            @endif
        </div>
    @endif

    {{-- Donji dio kartice: Instruktor i Dugme "Otvori" --}}
    <div class="flex justify-between items-center mt-auto">
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
        <div class="flex items-center gap-3">
            <a href="/objave/{{ $id }}"
               class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg shadow-md hover:bg-blue-700 transition-colors duration-200">
                Otvori
                <svg class="w-4 h-4 ml-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
                </svg>
            </a>

            @if(auth()->user() && auth()->user()->id === $trenutni->id)
                <form action="/objave/{{$id}}" method="POST" onsubmit="return confirm('Da li ste sigurni da želite obrisati ovu objavu?');">
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
