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

<article class="p-6 bg-white rounded-2xl border border-slate-100 shadow-lg shadow-blue-50/10 transform transition-all duration-300 hover:scale-[1.02] hover:shadow-xl hover:shadow-blue-100/20">
    <div class="flex justify-between items-center mb-4 text-slate-500"> {{-- Smanjen mb --}}
        <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-sm font-semibold text-blue-700">
            {{ $kategorija }}
        </span>
        <span class="text-sm font-medium text-slate-600">{{$starostInstrukcije}}</span> {{-- Tamniji tekst za starost --}}
    </div>
    <h2 class="mb-2 text-2xl font-bold tracking-tight text-slate-900"><a href="/instrukcije/{{ $id }}" class="hover:text-blue-600 transition-colors duration-200">{{$nazivInstrukcije}}</a></h2>
    <p class="mb-5 font-light text-slate-600 leading-relaxed"> {{-- Tamniji tekst i bolji line-height --}}
        {{$opisInstrukcije}}
    </p>

    <div class="flex justify-between items-center mt-6"> {{-- Povećan mt --}}
        <div class="flex items-center space-x-3"> {{-- Smanjen space-x --}}
            @if ($imageUrl)
                <img class="w-8 h-8 rounded-full object-cover border-2 border-white shadow-sm"
                     src="{{ $imageUrl }}"
                     alt="Profilna slika {{$instruktor}}" />
            @else
                <div class="w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-sm font-bold border-2 border-white shadow-sm">
                    {{ $initials ?: '?' }}
                </div>
            @endif
            <span class="font-medium text-slate-800">
                {{$instruktor}}
            </span>
        </div>
        <a href="/instrukcije/{{ $id }}"
           class="inline-flex items-center font-semibold text-blue-600 hover:text-blue-800 transition-colors duration-200"> {{-- Podebljan font i bolji hover --}}
            Otvori
            <svg class="ml-2 w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>
        </a>
    </div>
</article>
