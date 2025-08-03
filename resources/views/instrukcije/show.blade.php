@guest
    <?php
    redirect('/'); // Ostavljeno kako je traženo, bez promjene funkcionalnosti
    ?>



@endguest

<?php

$instruktorId=$instrukcija['user_id'];

$instruktor=\App\Models\User::findOrFail($instruktorId);


?>

<x-layout>

    <section class="py-16 sm:py-24 bg-gradient-to-br mt-10 from-blue-50 to-indigo-50"> {{-- Konzistentna pozadina i padding --}}
        {{-- Prilagođen horizontalni padding za simetriju --}}
        <div class="w-full max-w-screen-xl mx-auto px-6 md:px-8 lg:px-12">
            {{-- Promijenjen items-start u items-center za vertikalno centriranje kolona --}}
            <div class="grid lg:grid-cols-2 grid-cols-1 gap-12 items-center">

                {{-- Lijeva kolona: Detalji instrukcije --}}
                <div class="flex flex-col justify-center items-start">
                    {{-- Kategorija tag --}}
                    <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-sm font-semibold text-blue-700 mb-4">
                        {{$instrukcija->kategorija}}
                    </span>

                    {{-- Naslov instrukcije --}}
                    <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight text-slate-900 leading-tight mb-4">
                        {{$instrukcija['naziv']}}
                    </h1>

                    {{-- Opis instrukcije --}}
                    <p class="text-lg text-slate-700 leading-relaxed mb-8">
                        {{$instrukcija['opis']}}
                    </p>

                    {{-- Grid sa detaljima (Cijena, Lokacija, Vrsta, Kontakt) --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 w-full mb-8">
                        {{-- Kartica: Cijena --}}
                        <div class="p-5 rounded-xl border border-slate-200 bg-white shadow-md flex flex-col items-start transition-all duration-300 hover:shadow-lg hover:border-blue-300">
                            <h4 class="text-xl font-bold text-slate-800 mb-2 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 9V7a2 2 0 002-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                Cijena
                            </h4>
                            <p class="text-lg font-semibold text-blue-600">{{$instrukcija['cijena']}} KM</p>
                        </div>

                        {{-- Kartica: Lokacija --}}
                        <div class="p-5 rounded-xl border border-slate-200 bg-white shadow-md flex flex-col items-start transition-all duration-300 hover:shadow-lg hover:border-blue-300">
                            <h4 class="text-xl font-bold text-slate-800 mb-2 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.828 0L6.343 16.657a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Lokacija
                            </h4>
                            <p class="text-lg text-slate-700">{{$instrukcija['lokacija']}}</p>
                        </div>

                        {{-- Kartica: Vrsta --}}
                        <div class="p-5 rounded-xl border border-slate-200 bg-white shadow-md flex flex-col items-start transition-all duration-300 hover:shadow-lg hover:border-blue-300">
                            <h4 class="text-xl font-bold text-slate-800 mb-2 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5s3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18s-3.332.477-4.5 1.253" />
                                </svg>
                                Vrsta
                            </h4>
                            <p class="text-lg text-slate-700">{{$instrukcija['vrsta']}}</p>
                        </div>

                        {{-- Kartica: Kontakt --}}
                        <div class="p-5 rounded-xl border border-slate-200 bg-white shadow-md flex flex-col items-start transition-all duration-300 hover:shadow-lg hover:border-blue-300">
                            <h4 class="text-xl font-bold text-slate-800 mb-2 flex items-center">
                                <svg class="w-6 h-6 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.135a11.249 11.249 0 005.422 5.422l1.135-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                Kontakt
                            </h4>
                            <p class="text-lg text-slate-700">{{$instrukcija->user->kontakt}}</p>
                        </div>
                    </div>

                    {{-- Dugmad za akcije (Izbrisi, Azuriraj) --}}
                    @if(auth()->user() && auth()->user()->id === $instrukcija->user_id)
                        <div class="flex gap-4 mt-4"> {{-- Povećan gap i mt --}}
                            <form action="/instrukcije/{{$instrukcija['id']}}" method="POST" onsubmit="return confirm('Da li ste sigurni da želite obrisati ovu instrukciju?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-6 py-3 bg-red-600 text-white text-base font-semibold rounded-full shadow-md hover:bg-red-700 transition-colors duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Izbriši
                                </button>
                            </form>
                            {{-- Dugme za otvaranje Update Modala --}}
                            <x-instrukcijaUmodal id="{{$instrukcija['id']}}" trenutniNaziv="{{$instrukcija['naziv']}}" trenutnaVrsta="{{$instrukcija['vrsta']}}" trenutnaKategorija="{{$instrukcija['kategorija']}}" trenutnaLokacija="{{$instrukcija['lokacija']}}" trenutniOpis="{{$instrukcija['opis']}}" trenutnaCijena="{{$instrukcija['cijena']}}">
                                {{-- Ovdje možete dodati stil za dugme unutar slota ako komponenta to podržava --}}
                                <button type="button" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white text-base font-semibold rounded-full shadow-md hover:bg-blue-700 transition-colors duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Ažuriraj
                                </button>
                            </x-instrukcijaUmodal>
                        </div>
                    @endif
                </div>

                {{-- Desna kolona: Kartica instruktora --}}
                {{-- Korištenje flexboxa za centriranje unutar kolone --}}
                <div class="w-full flex justify-center items-center mt-8 lg:mt-0">
                    <x-profilkarticamala :kategorije="$kategorije" id="{{$instrukcija->user->id}}" lokacija="{{$instrukcija->user->lokacija}}" username="{{$instrukcija->user->username}}" titula="{{$instrukcija->user->titula}}"></x-profilkarticamala>
                </div>
            </div>
        </div>
    </section>

</x-layout>
