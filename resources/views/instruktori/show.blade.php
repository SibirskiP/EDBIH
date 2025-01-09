<?php



?>


<x-layout>
    <?php
    $kategorije = $instruktor->instrukcije()->distinct()->pluck('kategorija');

    ?>
<x-profileheader opis="{{$instruktor->opis}}" kontakt="{{$instruktor->kontakt}}" id="{{$instruktor->id}}" username="{{$instruktor['username']}}" lokacija="{{$instruktor['lokacija']}}" titula="{{$instruktor['titula']}}" :kategorije="$kategorije">


</x-profileheader>
    <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
        @if (session('success'))
            <div class="bg-green-500 text-white p-4 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session('failure'))
            <div class="bg-green-500 text-white p-4 rounded mb-4">
                {{ session('failure') }}
            </div>
        @endif


        <div class="py-12 bg-white">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                <div class="lg:text-center">
                    <h2
                        class="text-center font-heading mb-4 bg-orange-100 text-orange-800 px-4 py-2 rounded-lg md:w-64 md:mx-auto text-xs font-semibold tracking-widest text-black uppercase title-font">
                        O meni
                    </h2>

                    <p class="mt-4 max-w-2xl text-lg text-gray-500 lg:mx-auto ">
                        {{$instruktor->opis }}
                    </p>
                </div>


            </div>
            <h2
                class="text-center  mt-5 font-heading mb-4 bg-orange-100 text-orange-800 px-4 py-2 rounded-lg md:w-64 md:mx-auto text-xs font-semibold tracking-widest text-black uppercase title-font">
                Moje instrukcije i kursevi
            </h2>
            <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">
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

                <h2
                    class="text-center  mt-5 font-heading mb-4 bg-orange-100 text-orange-800 px-4 py-2 rounded-lg md:w-64 md:mx-auto text-xs font-semibold tracking-widest text-black uppercase title-font">
                    Moji materijali
                </h2>
                <div class="grid gap-8 lg:grid-cols-2 ">

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


            </div>
            <div class="mt-6 flex justify-center">
                {{ $instrukcije->links() }}
            </div>
        </div>

</x-layout>

