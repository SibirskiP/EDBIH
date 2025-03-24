@guest
    <?php
        redirect('/');
        ?>
@endguest
<?php


$vrste=config('mojconfig.vrste');
$lokacije=config('mojconfig.lokacije');


 


?>

<x-layout>

        <section class="py-24 relative xl:mr-0 lg:mr-5 mr-0 ">
            <div class="w-full max-w-7xl px-4 md:px-5 lg:px-5 mx-auto">
                <div class="w-full justify-start items-center xl:gap-12 gap-10 grid lg:grid-cols-2 grid-cols-1">
                    <div class="w-full flex-col justify-center lg:items-start items-center gap-10 inline-flex">
                        <div class="w-full flex-col justify-center items-start gap-8 flex">
                            <div class="flex-col justify-start lg:items-start items-center gap-4 flex">
                                <h6 class="text-gray-400 text-base font-normal leading-relaxed"> <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">{{$instrukcija->kategorija}}</span>
                                </h6>
                                <div class="w-full flex-col justify-start lg:items-start items-center gap-3 flex">
                                    <h2
                                        class="text-blue-700 text-4xl font-bold font-manrope leading-normal lg:text-start text-center">
                                        {{$instrukcija['naziv']}}</h2>
                                    <p
                                        class="text-gray-500 text-base font-normal leading-relaxed lg:text-start text-center">
                                      {{$instrukcija['opis']}}
                                    </p>
                                </div>
                            </div>
                            <div class="w-full flex-col justify-center items-start gap-6 flex">
                                <div class="w-full justify-start items-center gap-8 grid md:grid-cols-2 grid-cols-1">
                                    <div
                                        class="w-full h-full p-3.5 rounded-xl border border-gray-200 hover:border-gray-400 transition-all duration-700 ease-in-out flex-col justify-start items-start gap-2.5 inline-flex">
                                        <h4 class="text-gray-900 text-2xl font-bold font-manrope leading-9">Cijena</h4>
                                        <p class="text-gray-500 text-base font-normal leading-relaxed">{{$instrukcija['cijena']}}</p>
                                    </div>
                                    <div
                                        class="w-full h-full p-3.5 rounded-xl border border-gray-200 hover:border-gray-400 transition-all duration-700 ease-in-out flex-col justify-start items-start gap-2.5 inline-flex">
                                        <h4 class="text-gray-900 text-2xl font-bold font-manrope leading-9">Lokacija
                                        </h4>
                                        <p class="text-gray-500 text-base font-normal leading-relaxed">{{$instrukcija['lokacija']}}</p>
                                    </div>
                                </div>
                                <div class="w-full h-full justify-start items-center gap-8 grid md:grid-cols-2 grid-cols-1">
                                    <div
                                        class="w-full p-3.5 rounded-xl border border-gray-200 hover:border-gray-400 transition-all duration-700 ease-in-out flex-col justify-start items-start gap-2.5 inline-flex">
                                        <h4 class="text-gray-900 text-2xl font-bold font-manrope leading-9">Vrsta</h4>
                                        <p class="text-gray-500 text-base font-normal leading-relaxed">{{$instrukcija['vrsta']}}</p>
                                    </div>
                                    <div
                                        class="w-full h-full p-3.5 rounded-xl border border-gray-200 hover:border-gray-400 transition-all duration-700 ease-in-out flex-col justify-start items-start gap-2.5 inline-flex">
                                        <h4 class="text-gray-900 text-2xl font-bold font-manrope leading-9">Kontakt</h4>
                                        <p class="text-gray-500 text-base font-normal leading-relaxed">{{$instrukcija->user->kontakt}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>


                        @if(auth()->user() && auth()->user()->id === $instrukcija->user_id)
                            <div class="flex gap-2">


                                <form action="/instrukcije/{{$instrukcija['id']}}" method="POST" onsubmit="return confirm('Da li ste sigurni da želite obrisati ovu instrukciju?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg">
                                        Izbrisi
                                    </button>
                                </form>
                            </div>
                            <x-instrukcijaUmodal id="{{$instrukcija['id']}}" trenutniNaziv="{{$instrukcija['naziv']}}" trenutnaVrsta="{{$instrukcija['vrsta']}}" trenutnaKategorija="{{$instrukcija['kategorija']}}" trenutnaLokacija="{{$instrukcija['lokacija']}}" trenutniOpis="{{$instrukcija['opis']}}" trenutnaCijena="{{$instrukcija['cijena']}}"></x-instrukcijaUmodal>
                        @endif


                    </div>
                    <div class="w-full flex justify-center lg:justify-end lg:pr-8">

                        <x-profilkarticamala :kategorije="$kategorije" id="{{$instrukcija->user->id}}" lokacija="{{$instrukcija->user->lokacija}}" username="{{$instrukcija->user->username}}" titula="{{$instrukcija->user->titula}}"></x-profilkarticamala>

                    </div>
                </div>
            </div>
        </section>



</x-layout>

