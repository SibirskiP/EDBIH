@php
    use Illuminate\Support\Str;
@endphp


<div x-data="{
        sidebarOpen: window.innerWidth >= 768,
        activeSidebarView: 'conversations',
        // Dodano za desni sidebar
        rightSidebarOpen: @entangle('rightSidebarOpen').live, // Povezano sa Livewire propertijem
        init() {
            // Gleda promjenu veličine prozora da prilagodi sidebar
            window.addEventListener('resize', () => {
                this.sidebarOpen = window.innerWidth >= 768;
                // Zatvori desni sidebar na manjim ekranima ako se otvori
                if (window.innerWidth < 768) {
                    this.rightSidebarOpen = false; // Zatvori desni sidebar na manjim ekranima
                }
            });
            // Inicijalno prilagođavanje veličine polja za unos poruke
            this.$nextTick(() => {
                const messageInput = this.$refs.messageInput;
                if (messageInput) {
                    resizeTextarea(messageInput);
                }
            });
        }
    }"
     class="flex h-screen antialiased text-slate-800">

    <div class="flex flex-row h-full w-full bg-white rounded-2xl shadow-xl border border-slate-200 overflow-hidden">

        {{-- Lijevi Sidebar (Lista razgovora) --}}
        <div x-show="sidebarOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 -translate-x-full"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 -translate-x-full"
             class="fixed inset-y-0 left-0 z-40 flex flex-col py-6 pl-4 pr-2 w-64 sm:w-72 flex-shrink-0 border-r border-slate-200 bg-slate-50 h-full md:relative md:flex md:translate-x-0 md:opacity-100">

            {{-- Naslov sidebara --}}
            <div class="flex flex-row items-center px-4 h-14 w-full mb-4">
                <div class="flex items-center justify-center rounded-full text-blue-700 bg-blue-100 h-9 w-9 flex-shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                    </svg>
                </div>
                <div class="ml-3 font-bold text-xl text-slate-800">Chat</div>
                {{-- Dugme za zatvaranje sidebara na mobilnom --}}
                <button @click="sidebarOpen = false" class="md:hidden ml-auto p-2 rounded-full hover:bg-slate-200 text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            {{-- Dugme za prebacivanje pogleda (Razgovori/Sobe) --}}
            <div class="flex items-center justify-between px-4 mb-4">
                <span class="font-bold text-slate-700" x-text="activeSidebarView === 'conversations' ? 'Aktivni razgovori' : 'Aktivne sobe'">Aktivni razgovori</span>
                <button @click="activeSidebarView = (activeSidebarView === 'conversations' ? 'rooms' : 'conversations')"
                        class="p-2 rounded-full hover:bg-slate-200 text-slate-600 transition-colors duration-200">
                    <svg x-show="activeSidebarView === 'conversations'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    <svg x-show="activeSidebarView === 'rooms'" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </button>
            </div>

            {{-- Kontejner za sekcije s jednim scrollom --}}
            <div class="flex-grow overflow-y-auto -mx-2 pr-2 relative">
                {{-- Aktivni razgovori sekcija --}}
                <div x-show="activeSidebarView === 'conversations'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform -translate-x-4"
                     x-transition:enter-end="opacity-100 transform translate-x-0"
                     x-transition:leave="transition ease-in duration-300 absolute w-full"
                     x-transition:leave-start="opacity-100 transform translate-x-0"
                     x-transition:leave-end="opacity-0 transform -translate-x-4"
                     class="flex flex-col space-y-1">
                    @forelse($users as $user)
                        @php
                            $userProfilePic = $user->profilna_slika
                                ? asset('storage/' . $user->profilna_slika)
                                : null;
                            $userInitials = '';
                            if (!$userProfilePic) {
                                $nameParts = explode(' ', $user->username);
                                foreach ($nameParts as $part) {
                                    $userInitials .= strtoupper(substr($part, 0, 1));
                                }
                                if (strlen($userInitials) > 2) {
                                    $userInitials = substr($userInitials, 0, 2);
                                }
                            }
                        @endphp
                        <button wire:key="user-{{ $user->id }}" wire:click="selectUser({{ $user->id }})"
                                class="flex flex-row items-center p-3 rounded-xl transition-colors duration-200
                                     {{ $selectedUser && $selectedUser->id === $user->id ? 'bg-blue-100 shadow-sm' : 'hover:bg-slate-100' }}">
                            <div class="flex items-center justify-center h-10 w-10 rounded-full overflow-hidden flex-shrink-0
                                            {{ $selectedUser && $selectedUser->id === $user->id ? 'bg-blue-500' : 'bg-slate-300' }}">
                                @if($userProfilePic)
                                    <img src="{{ $userProfilePic }}" alt="avatar" class="h-full w-full object-cover">
                                @else
                                    <span class="text-white font-bold text-sm">{{ $userInitials }}</span>
                                @endif
                            </div>
                            <div class="ml-3 text-sm font-semibold text-slate-800 truncate">{{ $user->username }}</div>
                            {{-- Indikator nepročitanih poruka --}}
                            @if($user->unread_messages_count > 0)
                                <div class="flex-shrink-0 ml-auto mr-2">
                                    <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">
                                        {{ $user->unread_messages_count }}
                                    </span>
                                </div>
                            @endif
                        </button>
                    @empty
                        <p class="text-center text-slate-500 text-sm py-4">Nema aktivnih razgovora.</p>
                    @endforelse
                </div>

                {{-- Aktivne sobe sekcija (placeholder) --}}
                <div x-show="activeSidebarView === 'rooms'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-x-4"
                     x-transition:enter-end="opacity-100 transform translate-x-0"
                     x-transition:leave="transition ease-in duration-300 absolute w-full"
                     x-transition:leave-start="opacity-100 transform translate-x-0"
                     x-transition:leave-end="opacity-0 transform translate-x-4"
                     class="flex flex-col space-y-1">
                    <button class="flex flex-row items-center p-3 rounded-xl hover:bg-slate-100 transition-colors duration-200 cursor-not-allowed">
                        <div class="flex items-center justify-center h-10 w-10 rounded-full overflow-hidden flex-shrink-0 bg-gray-300">
                            <svg class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h2a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h2m3-2v2m0-2a3 3 0 01-3-3V8a3 3 0 013-3h4a3 3 0 013 3v7a3 3 0 01-3 3h-4z" />
                            </svg>
                        </div>
                        <div class="ml-3 text-sm font-semibold text-slate-800 truncate">Opća soba</div>
                    </button>
                    <p class="text-center text-slate-500 text-sm py-4">Sobe još nisu dostupne.</p>
                </div>
            </div>
        </div>

        {{-- Glavni Chat Prozor --}}
        <div class="flex flex-col flex-auto h-full p-2 sm:p-4">
            {{-- Dugme za otvaranje sidebara na mobilnom --}}
            <button @click="sidebarOpen = true" class="md:hidden p-2 rounded-full hover:bg-slate-100 text-slate-600 mb-4">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                </svg>
            </button>

            {{-- Dinamički naslov chata --}}
            <div class="flex items-center justify-between px-4 py-3 bg-white border-b border-slate-200 rounded-t-xl shadow-sm mb-4">
                <div class="flex items-center">
                    @if ($selectedUser)
                        @php
                            $selectedUserProfilePic = $selectedUser->profilna_slika
                                ? asset('storage/' . $selectedUser->profilna_slika)
                                : null;
                            $selectedUserInitials = '';
                            if (!$selectedUserProfilePic) {
                                $nameParts = explode(' ', $selectedUser->username);
                                foreach ($nameParts as $part) {
                                    $selectedUserInitials .= strtoupper(substr($part, 0, 1));
                                }
                                if (strlen($selectedUserInitials) > 2) {
                                    $selectedUserInitials = substr($selectedUserInitials, 0, 2);
                                }
                            }
                        @endphp
                        <div class="flex items-center justify-center h-10 w-10 rounded-full overflow-hidden flex-shrink-0 bg-blue-500">
                            @if($selectedUserProfilePic)
                                <img src="{{ $selectedUserProfilePic }}" alt="avatar" class="h-full w-full object-cover">
                            @else
                                <span class="text-white font-bold text-sm">{{ $selectedUserInitials }}</span>
                            @endif
                        </div>
                        <div class="ml-3 text-lg font-bold text-slate-800">{{ $selectedUser->username }}</div>
                    @else
                        <div class="flex items-center justify-center h-10 w-10 rounded-full overflow-hidden flex-shrink-0 bg-gray-300">
                            <svg class="w-6 h-6 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                            </svg>
                        </div>
                        <div class="ml-3 text-lg font-bold text-slate-800">Odaberite razgovor</div>
                    @endif
                </div>
                {{-- Dugme za otvaranje desnog sidebara --}}
                @if($selectedUser)
                    <button @click="$wire.toggleRightSidebar()" class="p-2 rounded-full hover:bg-slate-100 text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </button>
                @endif
            </div>

            @if ($selectedUser)
                <div
                    id="chat-container"
                    data-selected-user="{{ $selectedUser->id }}"
                    class="flex flex-col flex-grow overflow-y-auto  bg-slate-50 rounded-b-xl border border-slate-200 shadow-inner"

                    x-data="{
                    // Funkcija za automatsko skrolovanje na dno
                    scrollToBottom(behavior = 'auto') {
                        const container = this.$refs.chatContainer;
                        if (container) {
                            container.scrollTo({ top: container.scrollHeight, behavior: behavior });
                        }
                    },

                    // Funkcija koja se poziva pri skrolovanju
                    handleScroll() {
                        // Ako je korisnik na vrhu i ako ima još poruka i ako se ne učitavaju već
                        if (this.$refs.chatContainer.scrollTop === 0 && $wire.get('hasMore') && !$wire.get('loadingMore')) {
                            // Zapamti trenutnu visinu da znamo gdje vratiti skrol
                            let oldHeight = this.$refs.chatContainer.scrollHeight;

                            // Pozovi Livewire metodu i sačekaj da završi
                            $wire.loadMore().then(() => {
                                // Nakon što Livewire doda nove poruke, izračunaj novu visinu
                                this.$nextTick(() => {
                                    let newHeight = this.$refs.chatContainer.scrollHeight;
                                    // Postavi skrol tako da korisnik ostane na istom mjestu
                                    this.$refs.chatContainer.scrollTop = newHeight - oldHeight;
                                });
                            });
                        }
                    }
                }"
                    x-init="
                    // Inicijalno skroluj na dno
                    $nextTick(() => scrollToBottom());

                    // Slušaj Livewire evente
                    Livewire.on('messages-loaded', () => $nextTick(() => scrollToBottom()));
                    Livewire.on('messages-updated', () => $nextTick(() => scrollToBottom('smooth')));
                "
                    wire:key="chat-{{ $selectedUser->id }}"

                >
                    <div
                        x-ref="chatContainer"
                        @scroll.debounce.150ms="handleScroll()"

                        class="flex flex-col flex-grow h-full overflow-y-auto p-4 bg-slate-50 rounded-b-xl border border-slate-200 shadow-inner space-y-3"

                    >

                        @if($hasMore)
                            <div class="text-center my-4">
                                <button wire:click="loadMore" wire:loading.attr="disabled" class="text-blue-600 hover:text-blue-800 text-sm font-semibold disabled:opacity-50 disabled:cursor-wait">
                                    <span wire:loading.remove wire:target="loadMore">Učitaj starije poruke</span>
                                    <span wire:loading wire:target="loadMore">Učitavanje...</span>
                                </button>
                            </div>
                        @else
                            <div class="text-center my-4 text-slate-500 text-xs">
                                <p>Početak razgovora</p>
                            </div>
                        @endif

                        @foreach($messages as $message)
                            {{-- Provjera da li pošiljalac postoji, da se spriječi greška --}}
                            @if ($message->sender)
                                @php
                                    // Koristimo učitanu relaciju, NEMA VIŠE User::find()
                                    $senderUser = $message->sender;
                                    $senderProfilePic = $senderUser->profilna_slika
                                        ? asset('storage/' . $senderUser->profilna_slika)
                                        : null;
                                    $senderInitials = '';
                                    if (!$senderProfilePic) {
                                        $nameParts = explode(' ', $senderUser->username ?? 'Korisnik');
                                        foreach ($nameParts as $part) {
                                            $senderInitials .= strtoupper(substr($part, 0, 1));
                                        }
                                        if (strlen($senderInitials) > 2) {
                                            $senderInitials = substr($senderInitials, 0, 2);
                                        }
                                    }
                                @endphp

                                @if($message->sender_id === auth()->id())
                                    {{-- Poruke od trenutnog korisnika (desno) --}}
                                    {{-- wire:key je OBAVEZAN za ispravno renderiranje liste! --}}
                                    <div wire:key="msg-{{ $message->id }}" class="flex justify-end items-end">
                                        <div class="relative max-w-[75%] sm:max-w-xs lg:max-w-md bg-blue-600 text-white py-2 px-4 shadow rounded-xl rounded-br-none">
                                            <div>
                                                {{-- Ako postoji tekst poruke --}}
                                                @if($message->message)
                                                    <p class="mb-1">{{ $message->message }}</p>
                                                @endif

                                                {{-- Prikaz fajlova za pošiljaoca (trenutnog korisnika) --}}
                                                @if($message->file_path)
                                                    @if(Str::startsWith($message->file_type, 'image'))
                                                        <a href="{{ asset('storage/' . $message->file_path) }}" target="_blank">
                                                            <img src="{{ asset('storage/' . $message->file_path) }}" alt="Poslata slika" class="rounded-lg max-w-full h-auto cursor-pointer">
                                                        </a>
                                                    @else
                                                        <div class="flex items-center space-x-2 bg-blue-500 text-white rounded-lg p-2 max-w-full">
                                                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                            <a href="{{ asset('storage/' . $message->file_path) }}" target="_blank" class="underline text-sm truncate">
                                                                {{ $message->file_name_original ?? 'Preuzmi fajl' }}
                                                            </a>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>

                                            <div class="flex items-center justify-end text-xs text-blue-200 mt-1">
                                                <span>{{ $message->created_at->format('H:i') }}</span>
                                                @if($message->read_at)
                                                    {{-- Pročitano --}}
                                                    <svg class="w-4 h-4 ml-1 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" class="translate-x-1"></path>
                                                    </svg>
                                                @else
                                                    {{-- Poslano --}}
                                                    <svg class="w-4 h-4 ml-1 text-blue-200" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex-shrink-0 ml-2">
                                            <div class="flex items-center justify-center h-8 w-8 rounded-full overflow-hidden bg-blue-500 shadow-sm">
                                                @if($senderProfilePic)
                                                    <img src="{{ $senderProfilePic }}" alt="avatar" class="h-full w-full object-cover">
                                                @else
                                                    <span class="text-white text-xs font-bold">{{ $senderInitials }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    {{-- Poruke od drugog korisnika (lijevo) --}}
                                    <div wire:key="msg-{{ $message->id }}" class="flex justify-start items-end">
                                        <div class="flex-shrink-0 mr-2">
                                            <div class="flex items-center justify-center h-8 w-8 rounded-full overflow-hidden bg-slate-300 shadow-sm">
                                                @if($senderProfilePic)
                                                    <img src="{{ $senderProfilePic }}" alt="avatar" class="h-full w-full object-cover">
                                                @else
                                                    <span class="text-slate-700 text-xs font-bold">{{ $senderInitials }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="relative max-w-[75%] sm:max-w-xs lg:max-w-md bg-white py-2 px-4 shadow rounded-xl rounded-bl-none">
                                            <div>
                                                {{-- Ako postoji tekst poruke --}}
                                                @if($message->message)
                                                    <p class="mb-1">{{ $message->message }}</p>
                                                @endif

                                                {{-- Prikaz fajlova za primaoca (drugog korisnika) --}}
                                                @if($message->file_path)
                                                    @if(Str::startsWith($message->file_type, 'image'))
                                                        <a href="{{ asset('storage/' . $message->file_path) }}" target="_blank">
                                                            <img src="{{ asset('storage/' . $message->file_path) }}" alt="Primljena slika" class="rounded-lg max-w-full h-auto cursor-pointer">
                                                        </a>
                                                    @else
                                                        <div class="flex items-center space-x-2 bg-slate-200 text-slate-800 rounded-lg p-2 max-w-full">
                                                            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                            <a href="{{ asset('storage/' . $message->file_path) }}" target="_blank" class="underline text-sm truncate">
                                                                {{ $message->file_name_original ?? 'Preuzmi fajl' }}
                                                            </a>
                                                        </div>
                                                    @endif
                                                @endif
                                            </div>
                                            <div class="flex items-center justify-start text-xs text-slate-400 mt-1">
                                                <span>{{ $message->created_at->format('H:i') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            @endif {{-- Kraj provjere @if ($message->sender) --}}
                        @endforeach
                        <div id="typing-indicator" class="px-4 pb-1 text-sm italic text-slate-500"></div>
                    </div>
                </div>

                <form wire:submit="submit" enctype="multipart/form-data" class="p-4 bg-white rounded-b-xl border-t border-slate-200 shadow-md">
                    <div class="flex items-end rounded-xl bg-white w-full px-4 py-2">
                        <div>
                            <button type="button" onclick="document.getElementById('fileInput').click()" class="flex items-center justify-center text-slate-500 hover:text-blue-600 mr-2 sm:mr-4 transition-colors duration-200 p-2 rounded-full">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                </svg>
                            </button>
                            <input type="file" id="fileInput" wire:model="file" class="hidden">

                            @if($file)
                                <div class="flex items-center space-x-3 mt-3 p-2 bg-slate-100 rounded-lg shadow-sm w-full border border-slate-200">
                                    @if(str_contains($file->getMimeType(), 'image'))
                                        <img src="{{ $file->temporaryUrl() }}" class="h-24 w-24 object-contain rounded-md border border-slate-300">
                                        <span class="text-sm text-slate-700 font-medium truncate flex-grow">{{ $file->getClientOriginalName() }}</span>
                                    @else
                                        <div class="flex items-center space-x-2 flex-grow">
                                            <svg class="w-8 h-8 text-blue-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            <span class="text-sm text-slate-700 font-medium truncate">{{ $file->getClientOriginalName() }}</span>
                                        </div>
                                    @endif
                                    <button type="button" wire:click="$set('file', null)" class="text-red-600 hover:text-red-800 p-1 rounded-full hover:bg-red-100 transition-colors duration-200 flex-shrink-0">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow">
                            <div class="relative w-full">
                                <textarea
                                    x-ref="messageInput"
                                    wire:keydown="userTyping"
                                    wire:model.live.debounce.500ms="newMessage"
                                    x-on:input="resizeTextarea($event.target)"
                                    @keydown.enter.prevent="if (!event.shiftKey) $wire.submit()"
                                    rows="1"
                                    class="block w-full border border-slate-300 rounded-xl focus:outline-none focus:border-blue-500 focus:ring-1 focus:ring-blue-500 pl-4 pr-12 py-2 text-slate-800 placeholder-slate-400 transition-all duration-200 resize-none overflow-hidden"
                                    placeholder="Napišite poruku..."
                                    style="min-height: 3rem; max-height: 10rem;"
                                ></textarea>
                                <button type="button" class="absolute flex items-center justify-center h-full w-10 right-0 top-0 text-slate-500 hover:text-blue-600 transition-colors duration-200">
                                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="ml-2 sm:ml-4">
                            <button type="submit" class="flex items-center justify-center bg-blue-600 hover:bg-blue-700 rounded-xl text-white px-3 py-2 sm:px-5 sm:py-2.5 flex-shrink-0 shadow-md transition-colors duration-200 text-sm sm:text-base">
                                <span class="font-semibold hidden sm:inline">Pošalji</span>
                                <span class="sm:ml-2">
                                        <svg class="w-4 h-4 sm:w-5 sm:h-5 transform rotate-45 -mt-px" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                                        </svg>
                                    </span>
                            </button>
                        </div>
                    </div>
                </form>
            @else
                <div class="flex flex-col items-center justify-center h-full bg-slate-50 rounded-xl border border-slate-200 shadow-inner text-slate-600">
                    <svg class="w-20 h-20 text-blue-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path>
                    </svg>
                    <p class="text-xl font-semibold mb-2">Odaberite razgovor</p>
                    <p class="text-center max-w-sm">Odaberite korisnika iz liste lijevo da započnete ili nastavite razgovor.</p>
                </div>
            @endif
        </div>

        {{-- Desni Sidebar (Informacije o korisniku i dijeljeni fajlovi) --}}
        <div x-show="rightSidebarOpen"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-x-full"
             x-transition:enter-end="opacity-100 translate-x-0"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-full"
             class="fixed inset-y-0 right-0 z-40 flex flex-col py-6 pl-2 pr-4 w-64 sm:w-72 flex-shrink-0 border-l border-slate-200 bg-slate-50 h-full md:relative md:flex md:translate-x-0 md:opacity-100"
             x-cloak>

            <div class="flex-shrink-0 px-4 flex items-center justify-between border-b border-slate-200 pb-4">
                <h2 class="text-xl font-bold text-slate-800">Detalji Chata</h2>
                <button @click="$wire.toggleRightSidebar()" class="p-2 rounded-full hover:bg-slate-100 text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>

            @if($selectedUser)
                <div class="overflow-y-auto custom-scrollbar flex-grow p-4">
                    {{-- Informacije o korisniku --}}
                    <div class="flex flex-col items-center mb-6">
                        @if($selectedUser->profilna_slika)
                            <img src="{{ asset('storage/' . $selectedUser->profilna_slika) }}" alt="avatar" class="h-24 w-24 rounded-full object-cover mb-3 shadow-md">
                        @else
                            @php
                                $initials = '';
                                $nameParts = explode(' ', $selectedUser->username);
                                foreach ($nameParts as $part) {
                                    $initials .= strtoupper(substr($part, 0, 1));
                                }
                                if (strlen($initials) > 2) {
                                    $initials = substr($initials, 0, 2);
                                }
                            @endphp
                            <div class="flex items-center justify-center h-24 w-24 rounded-full bg-blue-500 text-white font-bold text-3xl mb-3 shadow-md">
                                {{ $initials }}
                            </div>
                        @endif
                        <h3 class="text-xl font-bold text-slate-800">{{ $selectedUser->username }}</h3>
                        <p class="text-sm text-slate-500">{{ $selectedUser->email }}</p> {{-- Primjer dodatne informacije --}}
                        {{-- Ovdje možete dodati više informacija o korisniku: telefon, status, itd. --}}
                    </div>

                    {{-- Dijeljene Slike --}}
                    @if(count($sharedMedia['images']) > 0)
                        <h4 class="text-lg font-semibold text-slate-800 mb-3 border-b pb-2">Dijeljene Slike ({{ count($sharedMedia['images']) }})</h4>
                        <div class="grid grid-cols-2 gap-3 mb-6">
                            @foreach($sharedMedia['images'] as $image)
                                <a href="{{ asset('storage/' . $image->file_path) }}" target="_blank" class="block w-full h-24 overflow-hidden rounded-lg shadow-sm hover:shadow-md transition-shadow">
                                    <img src="{{ asset('storage/' . $image->file_path) }}" alt="Shared Image" class="w-full h-full object-cover">
                                </a>
                            @endforeach
                        </div>
                    @endif

                    {{-- Dijeljeni Fajlovi --}}
                    @if(count($sharedMedia['files']) > 0)
                        <h4 class="text-lg font-semibold text-slate-800 mb-3 border-b pb-2">Dijeljeni Fajlovi ({{ count($sharedMedia['files']) }})</h4>
                        <div class="flex flex-col gap-2">
                            @foreach($sharedMedia['files'] as $file)
                                {{-- Dodano: max-w-full i overflow-hidden za ispravno trunciranje --}}
                                <a href="{{ asset('storage/' . $file->file_path) }}" target="_blank" class="flex items-center p-3 rounded-lg bg-white hover:bg-slate-100 shadow-sm transition-colors max-w-full overflow-hidden">
                                    <div class="flex-shrink-0 mr-3 text-slate-500">
                                        {{-- Ikona za fajl tip --}}
                                        @if(Str::contains($file->file_type, 'pdf'))
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        @elseif(Str::contains($file->file_type, 'msword') || Str::contains($file->file_type, 'document'))
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                                        @elseif(Str::contains($file->file_type, 'text'))
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        @elseif(Str::contains($file->file_type, 'spreadsheet') || Str::contains($file->file_type, 'excel'))
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2zM12 7V3m0 0a2 2 0 012 2v2m0 0a2 2 0 002 2h2m0 0a2 2 0 012 2v2m0 0a2 2 0 002 2H12a2 2 0 00-2 2v-2m0 0a2 2 0 01-2-2V9m0 0a2 2 0 00-2-2H5a2 2 0 01-2-2V5a2 2 0 012-2h2m0 0a2 2 0 002-2V3m0 0a2 2 0 012-2z"></path></svg>
                                        @else
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        @endif
                                    </div>
                                    <div class="flex-grow">
                                        <p class="font-medium text-slate-800 truncate">{{ $file->file_name_original ?? 'Nepoznat fajl' }}</p>
                                        <p class="text-xs text-slate-500">{{ round($file->file_size / 1024, 2) }} KB - {{ $file->created_at->format('d.m.Y.') }}</p>
                                    </div>
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            @else
                <div class="flex flex-col items-center justify-center h-full text-slate-600">
                    <svg class="w-20 h-20 text-gray-300 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-lg font-semibold mb-2">Detalji razgovora</p>
                    <p class="text-center max-w-sm">Odaberite razgovor da biste vidjeli detalje korisnika i dijeljene fajlove.</p>
                </div>
            @endif
        </div>

    </div>
</div>

<script type="module">
    // Funkcija za automatsko povećavanje visine textarea
    function resizeTextarea(element) {
        element.style.height = 'auto'; // Resetiraj visinu
        element.style.height = element.scrollHeight + 'px'; // Postavi visinu na scrollHeight
    }
</script>

<script>
    document.addEventListener('livewire:initialized', () => {
        const authUserId = {{ auth()->id() }};

        if (window.Echo && window.Echo.private) {
            window.Echo
                .private(`chat3.${authUserId}`)
                .listen('UserTyping', (event) => {
                    const chatContainerElement = document.getElementById('chat-container');
                    if (!chatContainerElement) return;

                    const selectedUserId = Number(chatContainerElement.dataset.selectedUser);

                    if (event.senderId === selectedUserId) {
                        const t = document.getElementById("typing-indicator");
                        if (t) {
                            t.innerText = `${event.senderName ?? 'Korisnikkkk'} tipka...`;

                            clearTimeout(window.typingTimeout);
                            window.typingTimeout = setTimeout(() => {
                                t.innerText = '';
                            }, 2000);
                        }
                    }
                })
                .listen('MessageSent3', (event) => { // Corrected event name
                    // Livewire.on("messages-updated") već radi skrolanje
                });

            // Zatvaranje dropdowna kada se klikne van njega (ako se koriste unutar chata)
            window.addEventListener('click', function (event) {
                document.querySelectorAll('[id^="dropdown"], [id^="reply-dropdown"]').forEach(dropdown => {
                    let button = document.querySelector(`[onclick*="toggleDropdown('${dropdown.id}')"]`);
                    if (button && dropdown && !button.contains(event.target) && !dropdown.contains(event.target)) {
                        dropdown.classList.remove('block');
                    }
                });
            });
        }
    });
</script>
