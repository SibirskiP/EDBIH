@guest
    <?php
    redirect('/'); // Ostavljeno kako je traženo, bez promjene funkcionalnosti
    ?>
@endguest

<x-layout>

    <section class="py-16 sm:py-24 bg-gradient-to-br from-blue-50 to-indigo-50">
        <div class="w-full max-w-screen-xl mx-auto px-6 md:px-8 lg:px-12">
            <div class="grid lg:grid-cols-3 grid-cols-1 gap-12 items-start"> {{-- Promijenjen grid na 3 kolone --}}

                {{-- Lijeva kolona (glavni sadržaj objave) --}}
                <div class="lg:col-span-2 flex flex-col items-start"> {{-- Zauzima 2/3 širine na desktopu --}}
                    {{-- Kategorija tag --}}
                    <span class="inline-flex items-center rounded-full bg-blue-100 px-3 py-1 text-sm font-semibold text-blue-700 mb-4">
                        {{$objava->kategorija}}
                    </span>

                    {{-- Naslov objave --}}
                    <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight text-slate-900 leading-tight mb-6">
                        {{$objava['naziv']}}
                    </h1>

                    {{-- Sadržaj/Opis objave --}}
                    <p class="text-lg text-slate-700 leading-relaxed mb-8">
                        {{$objava['sadrzaj']}}
                    </p>

                    {{-- Priložena datoteka (slika/PDF) --}}
                    @if($objava->putanja)
                        @php
                            $putanja=$objava->putanja;
                            $ekstenzija = pathinfo($putanja, PATHINFO_EXTENSION);
                            $fullPath = asset('storage/' . $putanja);
                        @endphp

                        <div class="mt-4 mb-8 w-full rounded-xl overflow-hidden border border-slate-200 shadow-lg"> {{-- Dodani shadow-lg --}}
                            @if(in_array(strtolower($ekstenzija), ['jpg', 'jpeg', 'png', 'gif']))
                                {{-- Ako je slika --}}
                                <a href="{{ $fullPath }}" target="_blank" class="block w-full h-96 bg-slate-50 flex items-center justify-center group cursor-pointer">
                                    <img src="{{ $fullPath }}"
                                         alt="Priložena slika"
                                         class="w-full h-full object-contain group-hover:scale-105 transition-transform duration-300"> {{-- object-contain za cijelu sliku --}}
                                </a>
                            @elseif(strtolower($ekstenzija) === 'pdf')
                                {{-- Ako je PDF --}}
                                <div class="flex items-center p-5 bg-blue-50 text-blue-800">
                                    <svg class="w-8 h-8 mr-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <p class="text-lg font-medium truncate">Preuzmi PDF: <a href="{{ $fullPath }}" class="text-blue-600 hover:underline" target="_blank">{{ basename($putanja) }}</a></p>
                                </div>
                            @else
                                {{-- Ostale datoteke --}}
                                <div class="flex items-center p-5 bg-slate-50 text-slate-700">
                                    <svg class="w-8 h-8 mr-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.485L20.5 13.5" />
                                    </svg>
                                    <p class="text-lg font-medium truncate">Preuzmi datoteku: <a href="{{ $fullPath }}" class="text-blue-600 hover:underline" target="_blank">{{ basename($putanja) }}</a></p>
                                </div>
                            @endif
                        </div>
                    @endif

                    {{-- Dugmad za akcije (Izbrisi, Azuriraj) --}}
                    @if(auth()->user() && auth()->user()->id === $objava->user_id)
                        <div class="flex gap-4 mt-4">
                            <form action="/objave/{{$objava['id']}}" method="POST" onsubmit="return confirm('Da li ste sigurni da želite obrisati ovu objavu?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center px-6 py-3 bg-red-600 text-white text-base font-semibold rounded-full shadow-md hover:bg-red-700 transition-colors duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                    Izbriši
                                </button>
                            </form>
                            <x-objavaUmodal id="{{$objava['id']}}" trenutniNaziv="{{$objava['naziv']}}"  trenutnaKategorija="{{$objava['kategorija']}}"  trenutniSadrzaj="{{$objava['sadrzaj']}}">
                                <button type="button" class="inline-flex items-center px-6 py-3 bg-blue-600 text-white text-base font-semibold rounded-full shadow-md hover:bg-blue-700 transition-colors duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Ažuriraj
                                </button>
                            </x-objavaUmodal>
                        </div>
                    @endif
                </div>

                {{-- Desna kolona (Kartica instruktora i ostali detalji) --}}
                <div class="lg:col-span-1 flex flex-col items-center lg:items-start space-y-8 mt-8 lg:mt-0"> {{-- Zauzima 1/3 širine, dodan space-y --}}
                    <x-profilkarticamala :kategorije="$kategorije" id="{{$objava->user->id}}" lokacija="{{$objava->user->lokacija}}" username="{{$objava->user->username}}" titula="{{$objava->user->titula}}"></x-profilkarticamala>

                    {{-- Dodatni detalji o objavi (datum objave, autor) --}}
                    <div class="w-full p-6 bg-white rounded-xl border border-slate-200 shadow-md text-slate-700">
                        <h3 class="text-lg font-bold text-slate-800 mb-4">Detalji objave</h3>
                        <div class="space-y-3">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <span class="font-medium">Objavljeno:</span> <span class="ml-2">{{ $objava->created_at->format('d. M Y.') }}</span>
                            </div>
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                <span class="font-medium">Autor:</span> <span class="ml-2">{{ $objava->user->username }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Sekcija za Komentare --}}
    <section class="bg-white py-12 lg:py-16">
        <div class="max-w-screen-xl mx-auto px-6 md:px-8 lg:px-12">
            <div class="flex justify-between items-center mb-8">
                <h2 class="text-2xl lg:text-3xl font-bold text-slate-800">Komentari ({{ count($komentari) }})</h2>
            </div>

            {{-- Forma za dodavanje komentara --}}
            <form action="/komentari" class="mb-10 p-6 bg-slate-50 rounded-xl border border-slate-200 shadow-sm" method="POST">
                @csrf
                <div class="mb-4">
                    <textarea id="sadrzaj" name="sadrzaj" rows="4"
                              class="block w-full p-3 text-base text-slate-900 bg-white rounded-lg border border-slate-300 focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400"
                              placeholder="Napišite komentar..." required></textarea>
                </div>
                <input name="objava_id" id="objava_id" value="{{ $objava->id }}" type="hidden">
                <button type="submit"
                        class="inline-flex items-center py-2.5 px-5 text-sm font-medium text-center text-white bg-blue-600 rounded-lg focus:ring-4 focus:ring-blue-300 hover:bg-blue-700 transition-colors duration-200 shadow-md">
                    Postavi komentar
                </button>
            </form>

            {{-- Prikaz komentara --}}
            <div class="space-y-6">
                @foreach($komentari as $komentar)
                    <article class="p-6 bg-white rounded-xl border border-slate-200 shadow-sm">
                        <footer class="flex justify-between items-center mb-4">
                            <div class="flex items-center">
                                @php
                                    $komentarUser = \App\Models\User::find($komentar->user_id);
                                    $komentarUserImageUrl = $komentarUser->profilna_slika ? asset('storage/' . $komentarUser->profilna_slika) : asset('storage/profilne_slike/default_profile_pic.PNG');
                                    $komentarUserInitials = '';
                                    if (!$komentarUser->profilna_slika && $komentarUser) {
                                        $nameParts = explode(' ', $komentarUser->username);
                                        foreach ($nameParts as $part) {
                                            $komentarUserInitials .= strtoupper(substr($part, 0, 1));
                                        }
                                        if (strlen($komentarUserInitials) > 2) {
                                            $komentarUserInitials = substr($komentarUserInitials, 0, 2);
                                        }
                                    }
                                @endphp
                                @if ($komentarUserImageUrl)
                                    <img class="mr-3 w-8 h-8 rounded-full object-cover border border-slate-200" src="{{ $komentarUserImageUrl }}" alt="{{ $komentarUser->username }}">
                                @else
                                    <div class="mr-3 w-8 h-8 rounded-full bg-blue-500 flex items-center justify-center text-white text-sm font-bold border border-slate-200">
                                        {{ $komentarUserInitials ?: '?' }}
                                    </div>
                                @endif
                                <p class="font-semibold text-slate-800 mr-2">{{ $komentar->user->username }}</p>
                                <p class="text-sm text-slate-500">{{ $komentar->created_at->format('d M, Y') }}</p>
                            </div>
                            <div class="relative inline-block text-left">
                                <button
                                    id="menuButton{{ $komentar->id }}"
                                    onclick="toggleDropdown('dropdown{{ $komentar->id }}')"
                                    class="inline-flex items-center p-2 text-sm font-medium text-slate-500 rounded-lg hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-200 transition-colors duration-200"
                                >
                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 10a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm6 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm6 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z"/>
                                    </svg>
                                </button>
                                <div
                                    id="dropdown{{ $komentar->id }}"
                                    class="hidden absolute right-0 z-10 mt-2 w-36 bg-white rounded-lg shadow-lg border border-slate-200"
                                >
                                    <ul class="py-1 text-sm text-slate-700">
                                        @if(auth()->user() && auth()->user()->id === $komentar->user_id)
                                            <li><a href="#" class="block px-4 py-2 hover:bg-slate-100 transition-colors duration-200">Uredi</a></li>
                                        @endif
                                        @if(auth()->user() && auth()->user()->id === $komentar->user_id)
                                            <li>
                                                <form action="/komentari/{{$komentar->id}}" method="POST" onsubmit="return confirm('Da li ste sigurni da želite obrisati ovaj komentar?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="w-full text-left block px-4 py-2 text-red-600 hover:bg-red-50 transition-colors duration-200">
                                                        Izbriši
                                                    </button>
                                                </form>
                                            </li>
                                        @endif
                                        <li><a href="#" class="block px-4 py-2 hover:bg-slate-100 transition-colors duration-200">Prijavi</a></li>
                                    </ul>
                                </div>
                            </div>
                        </footer>
                        <p class="text-slate-600 leading-relaxed">{{ $komentar->sadrzaj }}</p>
                        <div class="comment-actions mt-4">
                            <button class="reply-btn text-sm font-medium text-blue-600 hover:text-blue-800 transition-colors duration-200" onclick="toggleReplyInput(this)">
                                <svg class="w-4 h-4 inline-block align-middle mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                                </svg>
                                Odgovori
                            </button>

                            <div class="reply-input mt-3" style="display: none;">
                                <form action="/odgovori" method="post" class="flex items-center gap-2">
                                    @csrf
                                    <input class="flex-grow p-2 text-sm text-slate-900 border border-slate-300 rounded-lg focus:ring-blue-500 focus:border-blue-500 placeholder-slate-400" type="text" name="sadrzaj" id="sadrzaj" placeholder="Napiši odgovor..." />
                                    <input name="komentar_id" id="komentar_id" value="{{$komentar->id}}" type="hidden" />

                                    <button type="submit" class="inline-flex items-center py-2 px-4 text-xs font-medium text-center text-white bg-blue-600 rounded-lg focus:ring-4 focus:ring-blue-300 hover:bg-blue-700 transition-colors duration-200 shadow-sm">
                                        Odgovori
                                    </button>
                                </form>
                            </div>
                        </div>

                        @if($komentar->odgovori->count())
                            <div class="ml-8 mt-4 space-y-4">
                                @foreach($komentar->odgovori as $odgovor)
                                    <article class="p-4 bg-slate-50 rounded-lg border border-slate-200 shadow-sm">
                                        <footer class="flex justify-between items-center mb-3">
                                            <div class="flex items-center">
                                                @php
                                                    $odgovorUser = \App\Models\User::find($odgovor->user_id);
                                                    $odgovorUserImageUrl = $odgovorUser->profilna_slika ? asset('storage/' . $odgovorUser->profilna_slika) : asset('storage/profilne_slike/default_profile_pic.PNG');
                                                    $odgovorUserInitials = '';
                                                    if (!$odgovorUser->profilna_slika && $odgovorUser) {
                                                        $nameParts = explode(' ', $odgovorUser->username);
                                                        foreach ($nameParts as $part) {
                                                            $odgovorUserInitials .= strtoupper(substr($part, 0, 1));
                                                        }
                                                        if (strlen($odgovorUserInitials) > 2) {
                                                            $odgovorUserInitials = substr($odgovorUserInitials, 0, 2);
                                                        }
                                                    }
                                                @endphp
                                                @if ($odgovorUserImageUrl)
                                                    <img class="mr-2 w-7 h-7 rounded-full object-cover border border-slate-200" src="{{ $odgovorUserImageUrl }}" alt="{{ $odgovorUser->username }}">
                                                @else
                                                    <div class="mr-2 w-7 h-7 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs font-bold border border-slate-200">
                                                        {{ $odgovorUserInitials ?: '?' }}
                                                    </div>
                                                @endif
                                                <p class="font-semibold text-slate-800 text-sm mr-2">{{ $odgovor->user->username }}</p>
                                                <p class="text-xs text-slate-500">{{ $odgovor->created_at->format('d M, Y') }}</p>
                                            </div>

                                            <div class="relative inline-block text-left">
                                                <button
                                                    onclick="toggleDropdown('reply-dropdown{{$odgovor->id}}')"
                                                    class="inline-flex items-center p-1.5 text-sm font-medium text-slate-500 rounded-lg hover:bg-slate-100 focus:outline-none focus:ring-2 focus:ring-blue-200 transition-colors duration-200"
                                                >
                                                    <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 20 20">
                                                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 10a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm6 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0Zm6 0a1 1 0 1 1-2 0 1 1 0 0 1 2 0Z"/>
                                                    </svg>
                                                </button>

                                                <div
                                                    id="reply-dropdown{{$odgovor->id}}"
                                                    class="hidden absolute right-0 z-10 mt-2 w-36 bg-white rounded-lg shadow-lg border border-slate-200"
                                                >
                                                    <ul class="py-1 text-sm text-slate-700">
                                                        @if(auth()->user() && auth()->user()->id === $odgovor->user_id)
                                                            <li><a href="#" class="block px-4 py-2 hover:bg-slate-100 transition-colors duration-200">Uredi</a></li>
                                                        @endif
                                                        @if(auth()->user() && auth()->user()->id === $odgovor->user_id)
                                                            <li>
                                                                <form action="/odgovori/{{$odgovor->id}}" method="POST" onsubmit="return confirm('Da li ste sigurni da želite obrisati ovaj odgovor?');">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <button type="submit" class="w-full text-left block px-4 py-2 text-red-600 hover:bg-red-50 transition-colors duration-200">
                                                                        Izbriši
                                                                    </button>
                                                                </form>
                                                            </li>
                                                        @endif
                                                        <li><a href="#" class="block px-4 py-2 hover:bg-slate-100 transition-colors duration-200">Prijavi</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </footer>
                                        <p class="text-slate-600 text-sm leading-relaxed">{{ $odgovor->sadrzaj }}</p>
                                    </article>
                                @endforeach
                            </div>
                        @endif
                    </article>
                @endforeach
            </div>
        </div>
    </section>

</x-layout>

<script>
    function toggleReplyInput(button) {
        const replyInput = button.nextElementSibling;
        if (replyInput.style.display === "none" || replyInput.style.display === "") {
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

        // Zatvaranje svih ostalih otvorenih dropdowna
        document.querySelectorAll('[id^="dropdown"], [id^="reply-dropdown"]').forEach(otherDropdown => {
            if (otherDropdown.id !== id && otherDropdown.classList.contains('block')) {
                otherDropdown.classList.remove('block');
                otherDropdown.classList.add('hidden');
            }
        });
    }

    // Zatvaranje dropdowna kada se klikne izvan njega
    window.addEventListener('click', function (event) {
        document.querySelectorAll('[id^="dropdown"], [id^="reply-dropdown"]').forEach(dropdown => {
            let button = document.querySelector(`[onclick*="toggleDropdown('${dropdown.id}')"]`);
            if (button && dropdown && !button.contains(event.target) && !dropdown.contains(event.target)) {
                dropdown.classList.remove('block');
                dropdown.classList.add('hidden');
            }
        });
    });
</script>
