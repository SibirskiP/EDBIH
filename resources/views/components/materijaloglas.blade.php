<?php
$trenutni=\App\Models\User::find($userId);

    ?>
<article class="  p-6 bg-white rounded-lg border border-gray-200 shadow-md dark:bg-gray-800 dark:border-gray-700">

    <div class="flex justify-between items-center mb-5 text-gray-500">
                <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                    {{ $kategorija }}
                </span>
        <span class="text-sm">{{$starostInstrukcije}}</span>
    </div>
    <h2 class="mb-2 text-2xl font-bold tracking-tight text-gray-900 dark:text-white"><a href="#">{{$nazivInstrukcije}}</a></h2>
    <p class="mb-5 font-light text-gray-500 dark:text-gray-400">
        {{$opisInstrukcije}}</p>
    <div class="flex justify-between items-center">
        <div class="flex items-center space-x-4">
            <img class="w-7 h-7 rounded-full"
                 src="{{ $trenutni->profilna_slika ? asset('storage/' . $trenutni->profilna_slika) : asset('storage/profilne_slike/default_profile_pic.PNG') }}"
                 alt="Jese Leos avatar" />
            <span class="font-medium dark:text-white">
                          {{$instruktor}}
                      </span>
        </div>
        <a href="{{ route('materijali.download', $id) }}"     class="inline-flex items-center font-medium text-primary-600 dark:text-primary-500 hover:underline">Preuzmi

            <svg class="ml-2 w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>

        </a>


        @if(auth()->user() && auth()->user()->id === $trenutni->id)
            <div class="flex gap-2">
                <form action="/materijali/{{$id}}" method="POST" onsubmit="return confirm('Da li ste sigurni da želite obrisati ovaj materijal?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="inline-flex items-center font-medium text-red-600 dark:text-primary-500 hover:underline">
                        Izbriši
                        <svg class="ml-2 w-4 h-4" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                            <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                        </svg>
                    </button>
                </form>
            </div>
        @endif

    </div>

</article>
