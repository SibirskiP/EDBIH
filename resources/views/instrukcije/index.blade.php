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

            <form class="max-w-md mx-auto my-5" method="GET" action="/instrukcije">
                <x-glavnifilter2>


                </x-glavnifilter2>
            </form>



        <div class="grid gap-8 lg:grid-cols-2 ">

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
        <div class="mt-6 flex justify-center">
            {{ $instrukcije->links() }}
        </div>

    </div>

</section>

    <x-instrukcijaCmodal></x-instrukcijaCmodal>

</x-layout>

