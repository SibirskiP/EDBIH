<?php
$trenutni = \App\Models\User::find($id);

// Generiranje inicijala ako nema profilne slike
$initials = '';
if (!$trenutni->profilna_slika && $trenutni->username) {
    $nameParts = explode(' ', $trenutni->username);
    foreach ($nameParts as $part) {
        $initials .= strtoupper(substr($part, 0, 1));
    }
    if (strlen($initials) > 2) {
        $initials = substr($initials, 0, 2);
    }
}
?>

<section class="relative py-16 sm:py-20 bg-gradient-to-br from-blue-50 to-indigo-50 overflow-hidden">
    {{-- Nema kompleksnih SVG oblika, samo suptilni gradijent pozadine --}}

    <div class="w-full max-w-screen-xl mx-auto px-6 md:px-8 lg:px-12 relative z-10">
        <div class="flex flex-col sm:flex-row items-center sm:items-end justify-between gap-6 mb-8">
            {{-- Profilna slika i osnovne informacije --}}
            <div class="flex flex-col sm:flex-row items-center sm:items-end gap-6 text-center sm:text-left">
                {{-- Avatar --}}
                @if ($trenutni->profilna_slika)
                    <img src="{{ asset('storage/' . $trenutni->profilna_slika) }}" alt="user-avatar"
                         class="h-32 w-32 rounded-full object-cover border-4 border-white shadow-xl transition-transform duration-300 hover:scale-105">
                @else
                    <div class="h-32 w-32 rounded-full bg-blue-600 flex items-center justify-center text-white text-4xl font-bold border-4 border-white shadow-xl transition-transform duration-300 hover:scale-105">
                        {{ $initials }}
                    </div>
                @endif

                <div class="flex flex-col items-center sm:items-start">
                    <h3 class="font-extrabold text-4xl sm:text-5xl text-slate-900 leading-tight">{{$username}}</h3>
                    <p class="font-medium text-lg text-slate-600 mt-1">{{$lokacija}}</p>
                </div>
            </div>

            {{-- Titula i dugmad za akcije --}}
            <div class="flex flex-col sm:flex-row items-center gap-4 mt-4 sm:mt-0">
                {{-- Titula --}}
                <span class="inline-flex items-center rounded-full py-2 px-4 bg-white text-slate-700 font-semibold text-base shadow-sm transition-all duration-300 hover:shadow-md">
                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18s-3.332.477-4.5 1.253" />
                    </svg>
                    {{$titula}}
                </span>

                @if(auth()->user() && auth()->user()->id == $id)
                    <div class="flex items-center gap-3">
                        <button type="button" id="defaultModalButton" data-modal-target="defaultModal" data-modal-toggle="defaultModal"
                                class="py-3 px-6 rounded-full bg-blue-600 text-white font-semibold text-base shadow-md hover:bg-blue-700 transition-all duration-300 transform hover:-translate-y-0.5">
                            Uredi profil
                        </button>
                        <form action="/logout" method="POST">
                            @csrf
                            <button type="submit"
                                    class="py-3 px-6 rounded-full bg-slate-100 text-blue-600 font-semibold text-base shadow-md hover:bg-blue-100 hover:text-blue-700 transition-all duration-300 transform hover:-translate-y-0.5">
                                Odjava
                            </button>
                        </form>
                    </div>
                @endif
            </div>
        </div>

        {{-- Kategorije --}}
        <div class="flex flex-wrap items-center justify-center sm:justify-start gap-3 mt-6 pt-4 border-t border-slate-200"> {{-- Dodana gornja linija i padding --}}
            <p class="font-semibold text-lg text-slate-700">Kategorije:</p>
            @if($kategorije->isEmpty())
                <span class="text-slate-500 text-base">Nema definisanih kategorija.</span>
            @else
                @foreach($kategorije as $kat)
                    <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-sm font-semibold text-blue-700">
            {{ $kat }}
        </span>
                @endforeach
            @endif
        </div>

        <x-instruktorUmodal :id="$id" :username="$username" :titula="$titula" :kontakt="$kontakt" :opis="$opis"></x-instruktorUmodal>
    </div>
</section>
