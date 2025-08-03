<x-layout>
    <section class="bg-gradient-to-br from-blue-50 to-indigo-50 py-12 sm:py-16 min-h-screen">
        <div class="px-4 mx-auto max-w-screen-xl lg:px-6">
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

            @livewire('lw-instruktor-filter')
        </div>
    </section>
</x-layout>
