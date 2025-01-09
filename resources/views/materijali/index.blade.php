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

            <form class="max-w-md mx-auto my-5" method="GET" action="/materijali">
                <x-glavnifilter3>


                </x-glavnifilter3>
            </form>



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
            <div class="mt-6 flex justify-center">
                {{ $materijali->links() }}
            </div>

        </div>

    </section>

    <x-materijalCmodal></x-materijalCmodal>

</x-layout>

