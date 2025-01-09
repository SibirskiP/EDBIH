
<x-layout>
    <section class="bg-white dark:bg-gray-900 mt-24 pt-2">
        <form class="max-w-md mx-auto my-5" method="GET" action="/instruktori">
        <x-glavnifilter></x-glavnifilter>
        </form>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mx-auto max-w-screen-lg justify-center mt-20">

        @foreach($instruktori as $instruktor)
            <?php
            $kategorije = $instruktor->instrukcije()->distinct()->pluck('kategorija');
        ?>

            <div class="flex justify-center">
                <x-profilkarticamala :kategorije="$kategorije" id="{{$instruktor->id}}" lokacija="{{$instruktor->lokacija}}" username="{{$instruktor->username}}" titula="{{$instruktor->titula}}"></x-profilkarticamala>
            </div>
        @endforeach


    </div>

</section>
    <div class="flex justify-center mt-6">
        {{ $instruktori->links() }}
    </div>
</x-layout>
