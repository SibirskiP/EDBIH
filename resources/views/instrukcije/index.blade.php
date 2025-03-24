<x-layout>

<section class=" dark:bg-gray-900 mt-20 sm:mt-10 bg-[#fdfaf5]">


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


            @livewire('lw-instrukcije-filter')

    </div>

</section>

    <x-instrukcijaCmodal></x-instrukcijaCmodal>

</x-layout>

