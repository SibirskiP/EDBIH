<x-layout>

    <section class="bg-white dark:bg-gray-900 mt-10 ">


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

            <form class="max-w-md mx-auto my-5" method="GET" action="/objave">
                <x-glavnifilter4>


                </x-glavnifilter4>
            </form>



            <div class="grid gap-8 lg:grid-cols-1  sm:mx-10 md:mx-20 lg:mx-35  ">

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
            <div class="mt-6 flex justify-center">
                {{ $objave->links() }}
            </div>

        </div>

    </section>

    <x-objavaCmodal></x-objavaCmodal>

</x-layout>

