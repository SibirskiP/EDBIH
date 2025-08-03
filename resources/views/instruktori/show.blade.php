<?php
// Ostavljeno kako je traženo, bez promjene funkcionalnosti
?>

<x-layout>
    <?php
    // Dohvaćanje kategorija za instruktora
    $kategorije = $instruktor->instrukcije()->distinct()->pluck('kategorija');
    ?>

    {{-- Header profila (pretpostavlja se da je već redizajniran) --}}
    <x-profileheader opis="{{$instruktor->opis}}" kontakt="{{$instruktor->kontakt}}" id="{{$instruktor->id}}" username="{{$instruktor['username']}}" lokacija="{{$instruktor['lokacija']}}" titula="{{$instruktor['titula']}}" :kategorije="$kategorije">
    </x-profileheader>

    <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
        @if (session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                <strong class="font-bold">Uspjeh!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        @if (session('failure'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-6" role="alert">
                <strong class="font-bold">Greška!</strong>
                <span class="block sm:inline">{{ session('failure') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-xl shadow-blue-50/10 p-8 md:p-12 mb-12 border border-slate-200">
            {{-- Sekcija "O meni" --}}
            <div class="mb-12 text-center">
                <h2 class="text-3xl sm:text-4xl font-bold tracking-tight text-slate-800 mb-4">
                    O <span class="text-blue-600">meni</span>
                </h2>
                <p class="mt-4 max-w-3xl text-lg text-slate-700 mx-auto leading-relaxed">
                    {{$instruktor->opis }}
                </p>
            </div>

            {{-- Tabs za organizaciju sadržaja --}}
            <div x-data="{ activeTab: 'instrukcije' }">
                {{-- Tab navigacija --}}
                <div class="flex border-b border-slate-200 mb-8 justify-center flex-wrap">
                    <button @click="activeTab = 'instrukcije'"
                            :class="{ 'border-blue-600 text-blue-600': activeTab === 'instrukcije', 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300': activeTab !== 'instrukcije' }"
                            class="py-3 px-6 text-lg font-semibold transition-colors duration-200 border-b-2 focus:outline-none">
                        Instrukcije i kursevi
                    </button>
                    <button @click="activeTab = 'materijali'"
                            :class="{ 'border-blue-600 text-blue-600': activeTab === 'materijali', 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300': activeTab !== 'materijali' }"
                            class="py-3 px-6 text-lg font-semibold transition-colors duration-200 border-b-2 focus:outline-none">
                        Materijali
                    </button>
                    {{-- Pretpostavka da postoji i sekcija za objave --}}
                    <button @click="activeTab = 'objave'"
                            :class="{ 'border-blue-600 text-blue-600': activeTab === 'objave', 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300': activeTab !== 'objave' }"
                            class="py-3 px-6 text-lg font-semibold transition-colors duration-200 border-b-2 focus:outline-none">
                        Objave
                    </button>
                </div>

                {{-- Tab sadržaj: Instrukcije i kursevi --}}
                <div x-show="activeTab === 'instrukcije'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0">
                    <h3 class="text-2xl font-bold text-slate-800 mb-6 text-center">Moje instrukcije i kursevi</h3>
                    @if($instrukcije->isEmpty())
                        <div class="bg-slate-50 rounded-xl p-8 text-center text-slate-600 border border-slate-200">
                            Trenutno nema objavljenih instrukcija i kurseva.
                        </div>
                    @else
                        <div class="grid gap-8 lg:grid-cols-2">
                            @foreach($instrukcije as $instrukcija)
                                <x-instrukcijaoglas
                                    :id="$instrukcija->id"
                                    :kategorija="$instrukcija->kategorija"
                                    :starostInstrukcije="$instrukcija->updated_at->diffForHumans()"
                                    :nazivInstrukcije="$instrukcija->naziv"
                                    :opisInstrukcije="$instrukcija->opis"
                                    :instruktor="$instrukcija->user->username"
                                    :userId="$instrukcija->user->id"
                                />
                            @endforeach
                        </div>
                        <div class="mt-8 flex justify-center">
                            {{ $instrukcije->links() }}
                        </div>
                    @endif
                </div>

                {{-- Tab sadržaj: Moji materijali --}}
                <div x-show="activeTab === 'materijali'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0">
                    <h3 class="text-2xl font-bold text-slate-800 mb-6 text-center">Moji materijali</h3>
                    @if($materijali->isEmpty())
                        <div class="bg-slate-50 rounded-xl p-8 text-center text-slate-600 border border-slate-200">
                            Trenutno nema objavljenih materijala.
                        </div>
                    @else
                        <div class="grid gap-8 lg:grid-cols-2">
                            @foreach($materijali as $materijal)
                                <x-materijaloglas
                                    :id="$materijal->id"
                                    :kategorija="$materijal->kategorija"
                                    :starostInstrukcije="$materijal->updated_at->diffForHumans()"
                                    :nazivInstrukcije="explode('_', $materijal->naziv, 2)[1]"
                                    :opisInstrukcije="$materijal->opis"
                                    :instruktor="$materijal->user->username"
                                    :userId="$materijal->user->id"
                                />
                            @endforeach
                        </div>
                        <div class="mt-8 flex justify-center">
                            {{ $materijali->links() }}
                        </div>
                    @endif
                </div>

                {{-- Tab sadržaj: Moje objave (pretpostavljam da postoji) --}}
                <div x-show="activeTab === 'objave'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0">
                    <h3 class="text-2xl font-bold text-slate-800 mb-6 text-center">Moje objave</h3>
                    @if($objave->isEmpty()) {{-- Pretpostavka da postoji $objave varijabla --}}
                    <div class="bg-slate-50 rounded-xl p-8 text-center text-slate-600 border border-slate-200">
                        Trenutno nema objavljenih objava.
                    </div>
                    @else
                        <div class="grid gap-8 grid-cols-1"> {{-- Objava je obično cijelom širinom --}}
                            @foreach($objave as $objava)
                                <x-objavaoglas
                                    :id="$objava->id"
                                    :kategorija="$objava->kategorija"
                                    :starostInstrukcije="$objava->updated_at->diffForHumans()"
                                    :nazivInstrukcije="$objava->naziv"
                                    :opisInstrukcije="$objava->sadrzaj"
                                    :instruktor="$objava->user->username"
                                    :userId="$objava->user->id"
                                    :putanja="$objava->putanja"
                                />
                            @endforeach
                        </div>
                        <div class="mt-8 flex justify-center">
                            {{ $objave->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-layout>
