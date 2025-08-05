<x-layout>
    <?php
    // Dohvaćanje kategorija za instruktora
    $kategorije = $instruktor->instrukcije()->distinct()->pluck('kategorija');
    ?>

    {{-- Header profila --}}
    <x-profileheader opis="{{$instruktor->opis}}" kontakt="{{$instruktor->kontakt}}" id="{{$instruktor->id}}" username="{{$instruktor['username']}}" lokacija="{{$instruktor['lokacija']}}" titula="{{$instruktor['titula']}}" :kategorije="$kategorije">
    </x-profileheader>
    @csrf

    <div class="py-8 px-4 mx-auto max-w-screen-xl lg:py-16 lg:px-6">

        {{-- Dinamički prostor za notifikacije --}}
        <div id="dynamic-notifications-container"></div>
        {{-- NOVO: Dugme za početak razgovora --}}
        @if(auth()->check() && auth()->id() !== $instruktor->id)
            <div class="flex justify-center mb-8">
                <a href="{{ url('/chat?userId=' . $instruktor->id) }}" class="inline-flex items-center justify-center px-6 py-3 text-base font-medium text-center text-white bg-blue-600 rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 dark:focus:ring-blue-900 transition-colors duration-200 shadow-md">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"></path><path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"></path></svg>
                    Pošalji poruku
                </a>
            </div>
        @endif


        <div class="bg-white rounded-2xl shadow-xl shadow-blue-50/10 p-8 md:p-12 mb-12 border border-slate-200">
            {{-- Sekcija "O meni" --}}
            <div class="mb-12 text-center">
                <h2 class="text-3xl sm:text-4xl font-bold tracking-tight text-slate-800 mb-4">
                    O <span class="text-blue-600">meni</span>
                </h2>
                <p class="mt-4 max-w-3xl text-lg text-slate-700 mx-auto leading-relaxed">
                    {{$instruktor->opis }}
                </p>
            </div>

            {{-- Tabs za organizaciju sadržaja --}}
            <div x-data="{ activeTab: 'instrukcije' }">
                {{-- Tab navigacija --}}
                <div class="flex border-b border-slate-200 mb-8 justify-center flex-wrap">
                    <button @click="activeTab = 'instrukcije'"
                            :class="{ 'border-blue-600 text-blue-600': activeTab === 'instrukcije', 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300': activeTab !== 'instrukcije' }"
                            class="py-3 px-6 text-lg font-semibold transition-colors duration-200 border-b-2 focus:outline-none">
                        Instrukcije i kursevi
                    </button>
                    <button @click="activeTab = 'materijali'"
                            :class="{ 'border-blue-600 text-blue-600': activeTab === 'materijali', 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300': activeTab !== 'materijali' }"
                            class="py-3 px-6 text-lg font-semibold transition-colors duration-200 border-b-2 focus:outline-none">
                        Materijali
                    </button>
                    <button @click="activeTab = 'objave'"
                            :class="{ 'border-blue-600 text-blue-600': activeTab === 'objave', 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300': activeTab !== 'objave' }"
                            class="py-3 px-6 text-lg font-semibold transition-colors duration-200 border-b-2 focus:outline-none">
                        Objave
                    </button>
                    <button @click="activeTab = 'obavijesti'"
                            :class="{ 'border-blue-600 text-blue-600': activeTab === 'obavijesti', 'border-transparent text-slate-500 hover:text-slate-700 hover:border-slate-300': activeTab !== 'obavijesti' }"
                            class="py-3 px-6 text-lg font-semibold transition-colors duration-200 border-b-2 focus:outline-none flex items-center">
                        Obavijesti
                        {{-- Badge za nepročitane obavijesti i pozivnice --}}
                        <span id="unread-count-badge" class="ml-2 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-red-100 bg-red-600 rounded-full">
                            {{ $totalUnreadCount }}
                        </span>
                    </button>
                </div>

                {{-- Tab sadržaj: Instrukcije i kursevi --}}
                <div x-show="activeTab === 'instrukcije'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0">
                    <h3 class="text-2xl font-bold text-slate-800 mb-6 text-center">Moje instrukcije i kursevi</h3>
                    @if($instrukcije->isEmpty())
                        <div class="bg-slate-50 rounded-xl p-8 text-center text-slate-600 border border-slate-200">
                            Trenutno nema objavljenih instrukcija i kurseva.
                        </div>
                    @else
                        <div class="grid gap-8 lg:grid-cols-2">
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
                        <div class="mt-8 flex justify-center">
                            {{ $instrukcije->links() }}
                        </div>
                    @endif
                </div>

                {{-- Tab sadržaj: Moji materijali --}}
                <div x-show="activeTab === 'materijali'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0">
                    <h3 class="text-2xl font-bold text-slate-800 mb-6 text-center">Moji materijali</h3>
                    @if($materijali->isEmpty())
                        <div class="bg-slate-50 rounded-xl p-8 text-center text-slate-600 border border-slate-200">
                            Trenutno nema objavljenih materijala.
                        </div>
                    @else
                        <div class="grid gap-8 lg:grid-cols-2">
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
                        <div class="mt-8 flex justify-center">
                            {{ $materijali->links() }}
                        </div>
                    @endif
                </div>

                {{-- Tab sadržaj: Moje objave (pretpostavljam da postoji) --}}
                <div x-show="activeTab === 'objave'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0">
                    <h3 class="text-2xl font-bold text-slate-800 mb-6 text-center">Moje objave</h3>
                    @if($objave->isEmpty())
                        <div class="bg-slate-50 rounded-xl p-8 text-center text-slate-600 border border-slate-200">
                            Trenutno nema objavljenih objava.
                        </div>
                    @else
                        <div class="grid gap-8 grid-cols-1">
                            @foreach($objave as $objava)
                                <x-objavaoglas
                                    :id="$objava->id"
                                    :kategorija="$objava->kategorija"
                                    :starostInstrukcije="$objava->updated_at->diffForHumans()"
                                    :nazivInstrukcije="$objava->naziv"
                                    :opisInstrukcije="$objava->sadrzaj"
                                    :instruktor="$objava->user->username"
                                    :userId="$objava->user->id"
                                    :putanja="$objava->putanja"
                                />
                            @endforeach
                        </div>
                        <div class="mt-8 flex justify-center">
                            {{ $objave->links() }}
                        </div>
                    @endif
                </div>

                {{-- Tab sadržaj: Obavijesti i Pozivnice --}}
                <div x-show="activeTab === 'obavijesti'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform -translate-y-2" x-transition:enter-end="opacity-100 transform translate-y-0">
                    <h3 class="text-2xl font-bold text-slate-800 mb-6 text-center">Vaše obavijesti</h3>
                    <div class="space-y-8">
                        {{-- Sekcija za pozivnice u sobe --}}
                        <div>
                            <h4 class="text-xl font-bold text-slate-700 mb-4 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l-1 12H6L5 9z" />
                                </svg>
                                Pozivnice za sobe
                            </h4>
                            @forelse($roomInvitations as $invitation)
                                <div id="invitation-{{ $invitation->id }}" class="bg-white rounded-xl shadow-md p-6 border border-slate-200 mb-4 flex items-center justify-between">
                                    <div>
                                        <p class="text-lg font-semibold text-slate-800">
                                            Pozivnica za sobu: <span class="text-blue-600">{{ $invitation->room->name }}</span>
                                        </p>
                                        <p class="text-slate-600 text-sm mt-1">
                                            Poslao/la: {{ $invitation->room->creator->username }}
                                        </p>
                                    </div>
                                    <div class="flex space-x-2">
                                        <button onclick="handleInvitation({{ $invitation->id }}, 'accept')" class="bg-green-500 text-white font-semibold py-2 px-4 rounded-md hover:bg-green-600 transition-colors">
                                            Prihvati
                                        </button>
                                        <button onclick="handleInvitation({{ $invitation->id }}, 'decline')" class="bg-red-500 text-white font-semibold py-2 px-4 rounded-md hover:bg-red-600 transition-colors">
                                            Odbij
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="bg-slate-50 rounded-xl p-6 text-center text-slate-600 border border-slate-200">
                                    Trenutno nema pozivnica.
                                </div>
                            @endforelse
                        </div>

                        {{-- Sekcija za opće obavijesti --}}
                        <div>
                            <h4 class="text-xl font-bold text-slate-700 mb-4 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-blue-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                Ostale obavijesti
                            </h4>
                            @forelse($notifications as $notification)
                                <div id="notification-{{ $notification->id }}" @class([
                                    'rounded-xl shadow-md p-6 border mb-4 relative',
                                    'bg-slate-50 border-slate-200' => $notification->procitano,
                                    'bg-blue-50 border-blue-200' => !$notification->procitano,
                                ])>
                                    @unless($notification->procitano)
                                        <span class="absolute top-0 right-0 -mt-2 -mr-2 px-3 py-1 text-xs font-bold text-blue-800 bg-blue-200 rounded-full">
                                            Novo
                                        </span>
                                    @endunless

                                    <h4 @class([
                                        'text-lg font-bold text-slate-900',
                                        'font-normal text-slate-800' => $notification->procitano,
                                        'font-bold text-slate-900' => !$notification->procitano,
                                    ])>
                                        {{ $notification->naslov }}
                                    </h4>

                                    <p class="text-slate-700 mt-2">
                                        {{ $notification->sadrzaj }}
                                    </p>

                                    <div class="flex justify-between items-center mt-4">
                                        <p class="text-sm text-slate-500">
                                            {{ $notification->created_at->diffForHumans() }}
                                        </p>

                                        @unless($notification->procitano)
                                            <button onclick="handleMarkAsRead({{ $notification->id }})" class="text-sm font-medium text-blue-600 hover:text-blue-500">
                                                Označi kao pročitano
                                            </button>
                                        @endunless
                                    </div>
                                </div>
                            @empty
                                <div class="bg-white rounded-xl p-6 text-center text-slate-600 border border-slate-200 shadow-md">
                                    Trenutno nema novih obavijesti.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layout>

<script>
    // Globalna varijabla za praćenje broja nepročitanih obavijesti
    let currentUnreadCount = {{ $totalUnreadCount }};

    // Funkcija za ažuriranje badge-a
    function updateBadge() {
        const badge = document.getElementById('unread-count-badge');
        if (badge) {
            badge.textContent = currentUnreadCount;
            // Sakrij badge ako je broj 0
            if (currentUnreadCount === 0) {
                badge.classList.add('hidden');
            } else {
                badge.classList.remove('hidden');
            }
        }
    }

    // Funkcija za prikazivanje dinamičkih notifikacija
    function showNotification(message, type = 'success') {
        const container = document.getElementById('dynamic-notifications-container');
        const colorClass = type === 'success' ? 'bg-green-100 border-green-400 text-green-700' : 'bg-red-100 border-red-400 text-red-700';
        const titleText = type === 'success' ? 'Uspjeh!' : 'Greška!';

        const notificationHtml = `
            <div class="px-4 py-3 rounded-lg relative mb-6 ${colorClass}" role="alert">
                <strong class="font-bold">${titleText}</strong>
                <span class="block sm:inline">${message}</span>
            </div>
        `;
        container.innerHTML = notificationHtml;

        setTimeout(() => {
            container.innerHTML = '';
        }, 5000);
    }

    async function handleInvitation(invitationId, action) {
        const url = `/room-invitations/${invitationId}/${action}`;
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]')?.value;

        if (!csrfToken) {
            console.error('CSRF token not found.');
            showNotification('Došlo je do greške: CSRF token nije pronađen.', 'error');
            return;
        }

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
            });

            const data = await response.json();

            if (response.ok) {
                const invitationCard = document.getElementById(`invitation-${invitationId}`);
                if (invitationCard) {
                    invitationCard.remove();
                    // Smanji broj nepročitanih i ažuriraj badge
                    currentUnreadCount--;
                    updateBadge();
                    showNotification(data.message, 'success');
                } else {
                    console.warn(`Pozivnica sa ID ${invitationId} ne postoji u DOM-u.`);
                    showNotification(data.message, 'success');
                }
            } else {
                showNotification(data.message || 'Došlo je do greške.', 'error');
            }

        } catch (error) {
            console.error('Došlo je do mrežne greške:', error);
            showNotification('Došlo je do greške prilikom obrade pozivnice. Pokušajte ponovo.', 'error');
        }
    }

    async function handleMarkAsRead(notificationId) {
        const url = `/notifications/${notificationId}/mark-as-read`;
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || document.querySelector('input[name="_token"]')?.value;

        if (!csrfToken) {
            console.error('CSRF token not found.');
            showNotification('Došlo je do greške: CSRF token nije pronađen.', 'error');
            return;
        }

        try {
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                },
            });

            const data = await response.json();

            if (response.ok) {
                const notificationCard = document.getElementById(`notification-${notificationId}`);
                if (notificationCard) {
                    // Ažuriraj klase i ukloni "Novo" oznaku
                    notificationCard.classList.remove('bg-blue-50', 'border-blue-200');
                    notificationCard.classList.add('bg-slate-50', 'border-slate-200');

                    const newBadge = notificationCard.querySelector('.text-blue-800');
                    if (newBadge) {
                        newBadge.remove();
                    }

                    const markAsReadButton = notificationCard.querySelector('button');
                    if (markAsReadButton) {
                        markAsReadButton.remove();
                    }

                    // Smanji broj nepročitanih i ažuriraj badge
                    currentUnreadCount--;
                    updateBadge();
                    showNotification(data.message, 'success');
                } else {
                    console.warn(`Obavijest sa ID ${notificationId} ne postoji u DOM-u.`);
                    showNotification(data.message, 'success');
                }
            } else {
                showNotification(data.message || 'Došlo je do greške.', 'error');
            }

        } catch (error) {
            console.error('Došlo je do mrežne greške:', error);
            showNotification('Došlo je do greške prilikom obrade zahtjeva. Pokušajte ponovo.', 'error');
        }
    }

    // Pozovi updateBadge() pri učitavanju stranice da se prikaže početni broj
    document.addEventListener('DOMContentLoaded', updateBadge);
</script>
