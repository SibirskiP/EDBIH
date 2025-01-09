@guest
    <?php
    redirect('/');
    ?>
@endguest
<?php

$kategorije=config('mojconfig.kategorije');
$vrste=config('mojconfig.vrste');
$lokacije=config('mojconfig.lokacije');

?>

<x-layout>

    <section class="py-24 relative xl:mr-0 lg:mr-5 mr-0 ">
        <div class="w-full max-w-7xl px-4 md:px-5 lg:px-5 mx-auto">
            <div class="w-full justify-start items-center xl:gap-12 gap-10 grid lg:grid-cols-2 grid-cols-1">
                <div class="w-full flex-col justify-center lg:items-start  gap-10 inline-flex">
                    <div class="w-full flex-col justify-center items-start gap-8 flex">
                        <div class="flex-col justify-start lg:items-start  gap-4 flex">
                            <h6 class="text-gray-400 text-base font-normal leading-relaxed"> <span class="inline-flex  rounded-md bg-blue-50 px-2 py-1 text-xs font-medium text-blue-700 ring-1 ring-inset ring-blue-700/10">{{$objava->kategorija}}</span>
                            </h6>
                            <div class="w-full flex-col justify-start lg:items-start  gap-3 flex">
                                <h2
                                    class="text-blue-700 text-4xl font-bold font-manrope leading-normal lg:text-start text-center">
                                    {{$objava['naziv']}}</h2>
                                <p
                                    class="text-gray-500 text-base font-normal leading-relaxed lg:text-start ">
                                    {{$objava['sadrzaj']}}
                                </p>
                            </div>
                        </div>

                        @if($objava->putanja)
                            @php

                                $putanja=$objava->putanja;
                                $ekstenzija = pathinfo($putanja, PATHINFO_EXTENSION);
                            @endphp

                            <div class="mt-4 w-full">

                                @if(in_array($ekstenzija, ['jpg', 'jpeg', 'PNG', 'gif','png','GIF','JPG','JPEG']))
                                    {{-- Ako je slika --}}
                                    <div class="w-full h-60 bg-blue-50 rounded-lg overflow-hidden">
                                        <img src="{{ asset('storage/' . $putanja) }}"
                                             alt="Priložena slika"
                                             class="w-full h-full object-contain">
                                    </div>
                                @elseif(in_array($ekstenzija, ['pdf','PDF']))
                                    {{-- Ako je PDF --}}
                                    <p>Preuzmi PDF: <a href="{{ asset('storage/' . $putanja) }}" class="text-blue-600 hover:underline" target="_blank">{{ basename($putanja) }}</a></p>
                                @else
                                    {{-- Ostale datoteke --}}
                                    <p>Preuzmi datoteku: <a href="{{ asset('storage/' . $putanja) }}" class="text-blue-600 hover:underline" target="_blank">{{ basename($putanja) }}</a></p>
                                @endif
                            </div>
                        @endif


                    </div>


                    @if(auth()->user() && auth()->user()->id === $objava->user_id)
                        <div class="flex gap-2">


                            <form action="/objave/{{$objava['id']}}" method="POST" onsubmit="return confirm('Da li ste sigurni da želite obrisati ovu instrukciju?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-3 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg">
                                    Izbrisi
                                </button>
                            </form>
                        </div>
                      <x-objavaUmodal id="{{$objava['id']}}" trenutniNaziv="{{$objava['naziv']}}"  trenutnaKategorija="{{$objava['kategorija']}}"  trenutniSadrzaj="{{$objava['sadrzaj']}}"></x-objavaUmodal>
                    @endif


                </div>
                <div class="w-full flex justify-center lg:justify-end lg:pr-8">

                   <x-profilkarticamala :kategorije="$kategorije" id="{{$objava->user->id}}" lokacija="{{$objava->user->lokacija}}" username="{{$objava->user->username}}" titula="{{$objava->user->titula}}"></x-profilkarticamala>

                </div>
            </div>
        </div>

    </section>


{{--    Komentari--}}
    <section class="bg-white dark:bg-gray-900 py-8 lg:py-16 antialiased">
        <div class="max-w-2xl mx-auto px-4">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-lg lg:text-2xl font-bold text-gray-900 dark:text-white">Komentari ({{ count($komentari) }})</h2>
            </div>
            <form action="/komentari" class="mb-6" method="POST">
                @csrf
                <div class="py-2 px-4 mb-4 bg-white rounded-lg rounded-t-lg border border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                <textarea id="sadrzaj" name="sadrzaj" rows="6"
                          class="px-0 w-full text-sm text-gray-900 border-0 focus:ring-0 focus:outline-none dark:text-white dark:placeholder-gray-400 dark:bg-gray-800"
                          placeholder="Write a comment..." required></textarea>
                </div>
                <input name="objava_id" id="objava_id" value="{{ $objava->id }}" type="hidden">
                <button type="submit"
                        class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
                    Postavi komentar
                </button>
            </form>
            @foreach($komentari as $komentar)
                <article class="p-6 text-base bg-white rounded-lg dark:bg-gray-900 mb-4">
                    <footer class="flex justify-between items-center mb-2">
                        <div class="flex items-center">
                            <p class="inline-flex items-center mr-3 text-sm text-gray-900 dark:text-white font-semibold">
                                <img class="mr-2 w-6 h-6 rounded-full" src="{{ $komentar->user->profilna_slika }}" alt="{{ $komentar->user->username }}">
                                {{ $komentar->user->username }}
                            </p>
                            <p class="text-sm text-gray-600 dark:text-gray-400"><tusername pubdate datetime="{{ $komentar->created_at }}">{{ $komentar->created_at->format('d M, Y') }}</tusername></p>
                        </div>
                        <div class="relative inline-block text-left">
                            <button
                                id="menuButton{{ $komentar->id }}"
                                onclick="toggleDropdown('dropdown{{ $komentar->id }}')"
                                class="inline-flex items-center p-2 text-sm font-medium text-gray-500 rounded-lg hover:bg-gray-100 dark:text-gray-400 dark:hover:bg-gray-700"
                            >
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 10a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm6 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm6 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z"/>
                                </svg>
                            </button>
                            <div
                                id="dropdown{{ $komentar->id }}"
                                class="hidden absolute right-0 z-10 mt-2 w-36 bg-white rounded-lg divide-y divide-gray-100 shadow dark:bg-gray-700"
                            >
                                <ul class="py-1 text-sm text-gray-700 dark:text-gray-200">
                                    @if(auth()->user() && auth()->user()->id === $komentar->user_id)
                                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Uredi</a></li>
                                    @endif
                                        @if(auth()->user() && auth()->user()->id === $komentar->user_id)
                                    <li>
                                        <form action="/komentari/{{$komentar->id}}" method="POST">
                                            @csrf
                                            @method('DELETE')


                                            <button type="submit" class="w-full text-left block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">

                                                Izbrisi
                                            </button>
                                        </form>

                                    </li>
                                        @endif

                                    <li><a href="#" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Prijavi</a></li>
                                </ul>
                            </div>
                        </div>


                    </footer>
                    <p class="text-gray-500 dark:text-gray-400">{{ $komentar->sadrzaj }}</p>
                    <div class="comment  justify-center items-left mt-2 ">
                        <button class="reply-btn text-sm " onclick="toggleReplyInput(this)">
                            <svg class=" w-3.5 h-3.5 inline" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 18">
                                <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 5h5M5 8h2m6-3h2m-5 3h6m2-7H2a1 1 0 0 0-1 1v9a1 1 0 0 0 1 1h3v5l5-5h8a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1Z"/>

                            </svg>
                            Odgovori
                        </button>

                        <div class="reply-input" style="display: none;">
                            <form action="/odgovori" method="post">
                                @csrf
                                <input class="text-sm text-gray-900" type="text" name="sadrzaj" id="sadrzaj" placeholder="Napiši odgovor..." />
                                <input name="komentar_id" id="komentar_id" value="{{$komentar->id}}" class="hidden" />

                                <button                         class="inline-flex items-center py-2.5 px-4 text-xs font-medium text-center text-white bg-primary-700 rounded-lg focus:ring-4 focus:ring-primary-200 dark:focus:ring-primary-900 hover:bg-primary-800">
                                    Odgovori</button>
                            </form>

                        </div>
                    </div>
                    @if($komentar->odgovori->count())
                        <div class="ml-6 mt-4">
                            @foreach($komentar->odgovori as $odgovor)
                                <article class="p-4 text-sm bg-gray-100 rounded-lg dark:bg-gray-800 mb-2">
                                    <footer class="flex justify-between items-center mb-2">
                                        <div class="flex items-center">
                                            <p class="inline-flex items-center mr-3 text-sm text-gray-900 dark:text-white font-semibold">
                                                <img class="mr-2 w-5 h-5 rounded-full" src="{{ $odgovor->user->profilna_slika }}" alt="{{ $odgovor->user->username }}">
                                                {{ $odgovor->user->username }}
                                            </p>
                                            <p class="text-xs text-gray-600 dark:text-gray-400"><time pubdate datetime="{{ $odgovor->created_at }}">{{ $odgovor->created_at->format('d M, Y') }}</time></p>
                                        </div>

                                        <div class="relative inline-block text-left">
                                            <!-- Dugme za odgovore -->
                                            <button
                                                onclick="toggleDropdown('reply-dropdown{{$odgovor->id}}')"
                                                class="inline-flex justify-center w-full px-4 py-2 text-sm font-medium text-gray-700   focus:outline-none "
                                            >

                                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 10a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm6 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm6 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z"/>
                                                </svg>
                                            </button>

                                            <!-- Dropdown meni za odgovore -->
                                            <div
                                                id="reply-dropdown{{$odgovor->id}}"
                                                class="hidden absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                                role="menu"
                                                aria-orientation="vertical"
                                                aria-labelledby="menu-button"
                                                tabindex="-1"
                                            >
                                                <div class="py-1" role="none">
                                                    @if(auth()->user() && auth()->user()->id === $komentar->user_id)
                                                    <a
                                                        href="#"
                                                        class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100"
                                                        role="menuitem"
                                                        tabindex="-1"
                                                        id="menu-item-0"
                                                    >
                                                        Uredi
                                                    </a>
                                                    @endif

                                                        @if(auth()->user() && auth()->user()->id === $odgovor->user_id)
                                                            <form action="/odgovori/{{$odgovor->id}}" method="POST">
                                                                @csrf
                                                                @method('DELETE')


                                                                <button type="submit" class="block w-full text-left px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">

                                                                    Izbrisi
                                                                </button>
                                                            </form>

                                                        @endif

                                                    <a
                                                        href="#"
                                                        class="text-gray-700 block px-4 py-2 text-sm hover:bg-gray-100"
                                                        role="menuitem"
                                                        tabindex="-1"
                                                        id="menu-item-1"
                                                    >
                                                        Prijavi
                                                    </a>
                                                </div>
                                            </div>
                                        </div>

                                    </footer>
                                    <p class="text-gray-500 dark:text-gray-400">{{ $odgovor->sadrzaj }}</p>
                                </article>
                            @endforeach
                        </div>
                    @endif
                </article>


            @endforeach
        </div>
    </section>




</x-layout>


<script>
    function toggleReplyInput(button) {
        const replyInput = button.nextElementSibling;
        if (replyInput.style.display === "none") {
            replyInput.style.display = "block";
        } else {
            replyInput.style.display = "none";
        }
    }


    function toggleDropdown(id) {
        const dropdown = document.getElementById(id);
        if (dropdown.classList.contains('hidden')) {
            dropdown.classList.remove('hidden');
            dropdown.classList.add('block');
        } else {
            dropdown.classList.remove('block');
            dropdown.classList.add('hidden');
        }
    }

</script>

<style>
    .reply-input {
        margin-top: 10px;
    }

    .reply-input input {
        width: 80%;
        padding: 5px;
        margin-right: 5px;
        border: 1px solid #ccc;
        border-radius: 4px;
    }

    .reply-input .submit-reply-btn {
        padding: 5px 10px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .reply-input .submit-reply-btn:hover {
        background-color: #0056b3;
    }



</style>
