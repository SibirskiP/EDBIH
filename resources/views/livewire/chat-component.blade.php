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
                {{-- Aktivne sobe sekcija --}}
                <div x-show="activeSidebarView === 'rooms'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-x-4"
                     x-transition:enter-end="opacity-100 transform translate-x-0"
                     x-transition:leave="transition ease-in duration-300 absolute w-full"
                     x-transition:leave-start="opacity-100 transform translate-x-0"
                     x-transition:leave-end="opacity-0 transform translate-x-4"
                     class="flex flex-col space-y-1">

                    {{-- Dugme za kreiranje nove sobe --}}
                    <button wire:click="openCreateRoomModal" class="flex flex-row items-center p-3 rounded-xl hover:bg-slate-100 transition-colors duration-200 text-slate-700 font-semibold">
                        <div class="flex items-center justify-center h-10 w-10 rounded-full overflow-hidden flex-shrink-0 bg-slate-200">
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                            </svg>
                        </div>
                        <div class="ml-3 text-sm">Kreiraj novu sobu</div>
                    </button>

                    @forelse($rooms as $room)
                        <button wire:key="room-{{ $room->id }}" wire:click="selectRoom({{ $room->id }})"
                                class="flex flex-row items-center p-3 rounded-xl transition-colors duration-200
                       {{ $selectedRoom && $selectedRoom->id === $room->id ? 'bg-blue-100 shadow-sm' : 'hover:bg-slate-100' }}">
                            <div class="flex items-center justify-center h-10 w-10 rounded-full overflow-hidden flex-shrink-0
                            {{ $selectedRoom && $selectedRoom->id === $room->id ? 'bg-blue-500 text-white' : 'bg-slate-300 text-slate-700' }}">
                                @if ($room->profilna_slika)
                                    <img src="{{ Storage::url($room->profilna_slika) }}" alt="{{ $room->name }} profilna slika" class="w-10 h-10 rounded-full object-cover">
                                @else
                                    {{-- Prikazi default ikonu ako nema profilne slike --}}
                                    <div class="w-10 h-10 rounded-full  flex items-center justify-center bg-blue-500 text-white">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h2a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h2m3-2v2m0-2a3 3 0 01-3-3V8a3 3 0 013-3h4a3 3 0 013 3v7a3 3 0 01-3 3h-4z" /></svg>

                                    </div>
                                @endif                            </div>
                            <div class="ml-3 text-sm font-semibold text-slate-800 truncate">{{ $room->name }}</div>
                            {{-- Ovdje se može dodati brojač nepročitanih poruka za sobe --}}

                            @if($room->unread_messages_count > 0)
                                <div class="flex-shrink-0 ml-auto mr-2">
                                    <span class="inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">
                                        {{ $room->unread_messages_count }}
                                    </span>
                                </div>
                            @endif

                        </button>
                    @empty
                        <p class="text-center text-slate-500 text-sm py-4 px-2">Niste član nijedne sobe. Kreirajte novu!</p>
                    @endforelse
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

                    @elseif ($selectedRoom)
                        {{-- Novi kod za prikaz sobe --}}
                        <div class="flex items-center justify-center h-10 w-10 rounded-full overflow-hidden flex-shrink-0 bg-blue-500 text-white">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h2a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h2m3-2v2m0-2a3 3 0 01-3-3V8a3 3 0 013-3h4a3 3 0 013 3v7a3 3 0 01-3 3h-4z" /></svg>
                        </div>
                        <div class="ml-3 text-lg font-bold text-slate-800">{{ $selectedRoom->name }}</div>
                        {{-- Dugme za dodavanje korisnika u sobu --}}

                        @if(\Illuminate\Support\Facades\Auth::id() === $selectedRoom->created_by || $isCurrentUserAdmin)
                            <button wire:click="openAddUserModal" class="ml-4 p-2 rounded-full hover:bg-slate-200 text-slate-600">
                                <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 7.5v3m0 0v3m0-3h3m-3 0h-3m-2.25-4.125a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zM4 19.235v-.11a6.375 6.375 0 0112.75 0v.109A12.318 12.318 0 0110.5 21h-1.061a12.317 12.317 0 01-4.136-1.666z" />
                                </svg>
                            </button>
                        @endif
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
                @if($selectedUser || $selectedRoom)
                    <button @click="$wire.toggleRightSidebar()" class="p-2 rounded-full hover:bg-slate-100 text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </button>
                @endif

                @if ($selectedRoom)

                        <button class="bg-blue-500 text-white px-3 py-1 rounded hover:bg-blue-600" wire:click="startVideoCall"> Video Poziv </button>

                    @if($callUrl)
                        <iframe
                            src="{{ $callUrl }}"
                            allow="camera; microphone; fullscreen; display-capture"
                            class="w-full h-[400px] mt-3 border rounded">
                        </iframe>
                    @endif
                @endif


            </div>

            @if ($selectedUser || $selectedRoom)
                <div
                    id="chat-container"
                    data-selected-user="{{ $selectedUser->id ?? '' }}"
                    data-selected-room="{{ $selectedRoom->id ?? '' }}"
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
                    wire:key="chat-{{ $selectedUser->id ?? $selectedRoom->id  }}
                    "


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

                                            @if ($selectedRoom && $message->sender)
                                                <p class="text-xs  font-semibold mb-0.5 text-blue-600 ">
                                                    {{ $message->sender->username }}
                                                </p>
                                            @endif
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

            @elseif($selectedRoom)
                <div class="overflow-y-auto custom-scrollbar flex-grow p-4">
                    {{-- Informacije o sobi --}}
                    <div class="flex flex-col items-center mb-6">
                        <div class="flex items-center justify-center h-24 w-24 rounded-full bg-blue-500 text-white font-bold text-3xl mb-3 shadow-md">
                            <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h2a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h2m3-2v2m0-2a3 3 0 01-3-3V8a3 3 0 013-3h4a3 3 0 013 3v7a3 3 0 01-3 3h-4z" /></svg>
                        </div>
                        <h3 class="text-xl font-bold text-slate-800">{{ $selectedRoom->name }}</h3>
                        <p class="text-sm text-slate-500">Kreirao: {{ $selectedRoom->creator->username }}</p>
                        {{-- Članovi: X dugme --}}
                        <button wire:click="openMembersModal" class="text-sm text-slate-500 mt-1 hover:underline cursor-pointer">
                            Članovi: {{ $selectedRoom->users->count() }}
                        </button>

                        {{-- Action buttons for rooms --}}
                        <div class="mt-4 flex flex-col space-y-2 w-full px-4">
                            <button wire:click="leaveRoom" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-semibold py-2 px-4 rounded-md shadow-sm transition-colors duration-200">
                                Napusti grupu
                            </button>
                            @if(\Illuminate\Support\Facades\Auth::id() === $selectedRoom->created_by)
                                <button wire:click="deleteRoom" onclick="return confirm('Da li ste sigurni da želite izbrisati ovu grupu? Sve poruke će biti obrisane.');" class="w-full bg-red-600 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-md shadow-sm transition-colors duration-200">
                                    Izbriši grupu
                                </button>
                            @endif
                        </div>
                    </div>

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


    {{-- Modal za kreiranje nove sobe --}}
    @if($showCreateRoomModal)
        {{-- Modal za kreiranje sobe --}}
        <div x-cloak x-show="$wire.showCreateRoomModal"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="$wire.showCreateRoomModal" class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
                    <form wire:submit.prevent="createRoom">
                        <h3 class="text-xl leading-6 font-semibold text-slate-900 mb-6" id="modal-title">Kreirajte novu sobu</h3>
                        <div class="space-y-4">
                            <div>
                                <label for="newRoomName" class="block text-sm font-medium text-slate-700">Naziv sobe</label>
                                <input wire:model.live="newRoomName" type="text" id="newRoomName"
                                       class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                @error('newRoomName') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            {{-- NOVO: Polje za opis sobe --}}
                            <div>
                                <label for="newRoomOpis" class="block text-sm font-medium text-slate-700">Opis sobe</label>
                                <textarea wire:model.live="newRoomOpis" id="newRoomOpis" rows="3"
                                          class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                                @error('newRoomOpis') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            {{-- NOVO: Polje za kategoriju --}}
                            <div>
                                <label for="newRoomKategorija" class="block text-sm font-medium text-slate-700">Kategorija</label>
                                <select wire:model.live="newRoomKategorija" id="newRoomKategorija"
                                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                    <option value="osnovna skola">Osnovna škola</option>
                                    <option value="srednja skola">Srednja škola</option>
                                    <option value="fakultet">Fakultet</option>
                                    <option value="jezici">Jezici</option>
                                    <option value="ostalo">Ostalo</option>
                                </select>
                                @error('newRoomKategorija') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            {{-- NOVO: Polje za privatnost --}}
                            <div class="space-y-2">
                                <label class="block text-sm font-medium text-slate-700">Privatnost</label>
                                <div class="flex items-center space-x-4">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio" wire:model.live="newRoomPrivatnost" name="privatnost" value="otvorena" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                        <span class="ml-2 text-sm text-slate-700">Otvorena</span>
                                    </label>
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio" wire:model.live="newRoomPrivatnost" name="privatnost" value="privatna" class="focus:ring-blue-500 h-4 w-4 text-blue-600 border-gray-300">
                                        <span class="ml-2 text-sm text-slate-700">Privatna</span>
                                    </label>
                                </div>
                                @error('newRoomPrivatnost') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            {{-- NOVO: Polje za profilnu sliku --}}
                            <div>
                                <label for="newRoomProfilnaSlika" class="block text-sm font-medium text-slate-700">Profilna slika</label>
                                <input wire:model.live="newRoomProfilnaSlika" type="file" id="newRoomProfilnaSlika"
                                       class="mt-1 block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                @error('newRoomProfilnaSlika') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror

                                {{-- Preview slike --}}
                                @if ($newRoomProfilnaSlika)
                                    <div class="mt-2 relative w-24 h-24 rounded-full overflow-hidden border border-gray-300">
                                        <img src="{{ $newRoomProfilnaSlika->temporaryUrl() }}" class="absolute inset-0 h-full w-full object-cover">
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="mt-6 sm:flex sm:flex-row-reverse">
                            <button type="submit"
                                    class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                                Kreiraj sobu
                            </button>
                            <button type="button" wire:click="$set('showCreateRoomModal', false)"
                                    class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:w-auto sm:text-sm">
                                Otkaži
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif

    {{-- Modal za dodavanje korisnika u sobu --}}
    @if($showAddUserModal && $selectedRoom)
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center" x-data @click.self="$wire.closeAddUserModal()">
            <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md flex flex-col" @click.away="$wire.closeAddUserModal()">
                <h3 class="text-xl font-bold mb-4">Dodaj korisnike u sobu "{{ $selectedRoom->name }}"</h3>
                <div>
                    <input type="text" wire:model.live.debounce.300ms="userSearchQuery" placeholder="Pretraži korisnike..." class="w-full border border-slate-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm p-2">
                </div>
                <div class="mt-4 flex-grow overflow-y-auto h-64 border-t border-b">
                    @forelse($availableUsersToAdd as $user)
                        <div class="flex items-center justify-between p-2 hover:bg-slate-50">
                            <span>{{ $user->username }}</span>
                            <button wire:click="addUserToRoom({{ $user->id }})" class="bg-green-500 text-white px-3 py-1 text-xs rounded-md hover:bg-green-600">Dodaj</button>
                        </div>
                    @empty
                        <p class="text-slate-500 text-center p-4">Nema korisnika za dodavanje ili se podudaraju sa pretragom.</p>
                    @endforelse
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="button" @click="$wire.closeAddUserModal()" class="bg-slate-200 text-slate-700 px-4 py-2 rounded-md hover:bg-slate-300">Zatvori</button>
                </div>
            </div>
        </div>
    @endif

    {{-- Novi Modal za članove grupe --}}
    @if($showMembersModal && $selectedRoom)
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center" x-data @click.self="$wire.closeMembersModal()">
            <div class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md flex flex-col" @click.away="$wire.closeMembersModal()">
                <h3 class="text-xl font-bold mb-4">Članovi grupe "{{ $selectedRoom->name }}"</h3>
                <div class="mt-4 flex-grow overflow-y-auto h-96 border-t border-b">
                    @forelse($roomMembers as $member)
                        <div class="flex items-center justify-between p-3 hover:bg-slate-50 border-b last:border-b-0">
                            <div class="flex items-center">
                                @php
                                    $memberProfilePic = $member->profilna_slika
                                        ? asset('storage/' . $member->profilna_slika)
                                        : null;
                                    $memberInitials = '';
                                    if (!$memberProfilePic) {
                                        $nameParts = explode(' ', $member->username);
                                        foreach ($nameParts as $part) {
                                            $memberInitials .= strtoupper(substr($part, 0, 1));
                                        }
                                        if (strlen($memberInitials) > 2) {
                                            $memberInitials = substr($memberInitials, 0, 2);
                                        }
                                    }
                                @endphp
                                <div class="flex items-center justify-center h-8 w-8 rounded-full overflow-hidden flex-shrink-0 bg-slate-300">
                                    @if($memberProfilePic)
                                        <img src="{{ $memberProfilePic }}" alt="avatar" class="h-full w-full object-cover">
                                    @else
                                        <span class="text-slate-700 text-xs font-bold">{{ $memberInitials }}</span>
                                    @endif
                                </div>
                                <span class="ml-3 text-sm font-semibold text-slate-800">{{ $member->username }}
                                    @if($member->pivot->is_admin)
                                        <span class="ml-2 px-2 py-0.5 bg-blue-100 text-blue-600 text-xs font-medium rounded-full">Admin</span>
                                    @endif
                                    @if($member->id === $selectedRoom->created_by)
                                        <span class="ml-2 px-2 py-0.5 bg-purple-100 text-purple-600 text-xs font-medium rounded-full">Kreator</span>
                                    @endif
                                </span>
                            </div>

                            @if($isCurrentUserAdmin) {{-- Provjeri da li je trenutni korisnik admin --}}
                            <div class="flex space-x-2">
                                @if(!$member->pivot->is_admin && $member->id !== Auth::id()) {{-- Ne prikazuj "Dodaj Admina" za sebe ili već postojećeg admina --}}
                                <button wire:click="makeAdmin({{ $member->id }})" class="bg-blue-500 text-white px-3 py-1 text-xs rounded-md hover:bg-blue-600">Dodaj Admina</button>
                                @endif
                                @if($member->id !== Auth::id() && $member->id !== $selectedRoom->created_by) {{-- Ne dozvoli uklanjanje sebe ili kreatora --}}
                                <button wire:click="removeMember({{ $member->id }})" onclick="return confirm('Da li ste sigurni da želite ukloniti {{ $member->username }} iz grupe?');" class="bg-red-500 text-white px-3 py-1 text-xs rounded-md hover:bg-red-600">Ukloni</button>
                                @endif
                            </div>
                            @endif
                        </div>
                    @empty
                        <p class="text-slate-500 text-center p-4">Nema članova u ovoj grupi.</p>
                    @endforelse
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="button" @click="$wire.closeMembersModal()" class="bg-slate-200 text-slate-700 px-4 py-2 rounded-md hover:bg-slate-300">Zatvori</button>
                </div>
            </div>
        </div>
    @endif

</div>

<script>
    document.addEventListener('livewire:initialized', () => {
        const authUserId = {{ auth()->id() }};
        const typingIndicator = document.getElementById("typing-indicator");
        let typingTimeout;

        // Funkcija za prikaz "typing" indikatora
        function showTypingIndicator(element, text) {
            if (element) {
                element.innerText = text;
                clearTimeout(typingTimeout);
                typingTimeout = setTimeout(() => {
                    element.innerText = '';
                }, 2000);
            }
        }

        // --- NOVI, ISPRAVLJENI DIO ZA SOBE ---

        // Učitavamo ID-jeve SVIH soba čiji je korisnik član.
        //direktiva sigurno pretvara PHP niz u JavaScript niz.
        const userRoomIds = @json(Auth::user()->rooms()->pluck('rooms.id'));

        if (window.Echo && userRoomIds) {
            // Prolazimo kroz svaku sobu i pretplaćujemo se na njen kanal
            userRoomIds.forEach(roomId => {
                window.Echo.private(`room.${roomId}`)
                    .listen('RoomMessageSent', (e) => {
                        // Sada slušamo notifikacije iz svih soba.
                        // Kada stigne poruka, samo je proslijedimo Livewire komponenti.
                        // PHP metoda 'incomingRoomMessage' će odlučiti šta dalje.
                        Livewire.dispatch('incomingRoomMessage', { payload: e });
                    })
                    .listen('UserTypingInRoom', (e) => {
                        // Također, sada i "typing" indikator radi za sve sobe.
                        const chatContainerElement = document.getElementById('chat-container');
                        if (!chatContainerElement) return;

                        const selectedRoomId = Number(chatContainerElement.dataset.selectedRoom);
                        // Prikazujemo indikator samo ako je soba otvorena
                        if (e.roomId == selectedRoomId && e.userId != authUserId) {
                            showTypingIndicator(typingIndicator, `${e.userName} tipka...`);
                        }
                    });
            });
        }

        // --- Kraj novog dijela ---


        // Listener za privatne (1:1) poruke ostaje nepromijenjen
        if (window.Echo) {
            window.Echo.private(`chat3.${authUserId}`)
                .listen('UserTyping', (event) => {
                    const chatContainerElement = document.getElementById('chat-container');
                    if (!chatContainerElement) return;
                    const selectedUserId = Number(chatContainerElement.dataset.selectedUser);
                    if (event.senderId === selectedUserId) {
                        showTypingIndicator(typingIndicator, `${event.senderName ?? 'Korisnik'} tipka...`);
                    }
                });
        }

        // Stari listener 'room-selected' koji je pravio problem je sada uklonjen.
    });

    // Ovu funkciju ostavljamo vani jer je poziva Alpine.js
    function resizeTextarea(element) {
        element.style.height = 'auto';
        element.style.height = element.scrollHeight + 'px';
    }

    document.addEventListener('livewire:initialized', () => {
        Livewire.on('open-video-window', (event) => {
            if (event && event.url) {
                // Otvaranje novog prozora
                window.open(event.url, '_blank', 'noopener,noreferrer');
            }
        });
    });
</script>
