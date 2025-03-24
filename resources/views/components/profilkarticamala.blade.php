<?php

$trenutni=\App\Models\User::find($id);

?>
<div class="w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
    <div class="flex flex-col items-center pb-10 pt-6">
        <div class="flex items-center justify-center sm:justify-start relative z-10 mb-5">
            <img src="{{ $trenutni->profilna_slika ? asset('storage/' . $trenutni->profilna_slika) : asset('storage/profilne_slike/default_profile_pic.PNG') }}" alt="user-avatar-image" class="h-32 w-32 border-4 border-solid border-white rounded-full object-cover">
        </div>
        <h5 class="mb-1 text-xl font-medium text-gray-900 dark:text-white">{{$username}}</h5>
        <span class="text-sm text-gray-500 dark:text-gray-400">{{$titula}}</span>
        <div class="flex mt-4 md:mt-6">
            <a href="/instruktori/{{$id}}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">Posjeti profil</a>

        </div>


            <svg class="mt-2 w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
                <path fill-rule="evenodd" d="M11.906 1.994a8.002 8.002 0 0 1 8.09 8.421 7.996 7.996 0 0 1-1.297 3.957.996.996 0 0 1-.133.204l-.108.129c-.178.243-.37.477-.573.699l-5.112 6.224a1 1 0 0 1-1.545 0L5.982 15.26l-.002-.002a18.146 18.146 0 0 1-.309-.38l-.133-.163a.999.999 0 0 1-.13-.202 7.995 7.995 0 0 1 6.498-12.518ZM15 9.997a3 3 0 1 1-5.999 0 3 3 0 0 1 5.999 0Z" clip-rule="evenodd"/>

            </svg>

            <div class="">{{$lokacija}}</div>


        <div class="flex space-x-2">

            @foreach($kategorije as $kat)
                <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">
                    {{ $kat }}
                </span>
            @endforeach
        </div>
    </div>
</div>
