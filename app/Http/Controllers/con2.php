<?php

namespace App\Livewire;

use App\Events\MessageSent3;
use App\Events\RoomMessageSent;
use App\Events\UserTyping;
use App\Events\UserTypingInRoom;
use App\Models\ChatMessage;
use App\Models\Obavijest;
use App\Models\Room;
use App\Models\RoomInvitation;
use App\Models\User;
use App\Services\DailyService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ChatComponent extends Component
{
    use WithFileUploads;

    // Postojeći propertiji
    public $users;
    public $selectedUser;
    public $newMessage;
    public $messages;
    public $loginID;
    public $sender_id;
    public $receiver_id;
    public $rightSidebarOpen = false;
    public $sharedMedia = ['images' => [], 'files' => []];
    public $hasMore = true;
    public $loadingMore = false;
    public $file;

    // Novi propertiji za sobe
    public $rooms;
    public $selectedRoom;
    public $showCreateRoomModal = false;
    public $newRoomName;
    public $showAddUserModal = false;
    public $userSearchQuery = '';
    public $availableUsersToAdd = [];
    public $showMembersModal = false; // NOVO: Za prikaz modala članova
    public $roomMembers = [];         // NOVO: Lista članova sobe
    public $isCurrentUserAdmin = false; // NOVO: Status admina trenutnog korisnika u odabranoj sobe




    public $newRoomOpis;
    public $newRoomKategorija = 'ostalo';
    public $newRoomPrivatnost = 'otvorena';
    public $newRoomProfilnaSlika;


    //video audio 02.08
    public $callUrl = null;
    public $videoCallRoomUrl;

    public function startCall(DailyService $daily)
    {
        // Provjeri je li korisnik odabrao sobu
        if (!$this->selectedRoom) {
            // Možete dodati neku logiku za 1-na-1 pozive ili prikazati grešku
            return;
        }

        // Provjeri postoji li već URL za poziv u sobi
        if ($this->selectedRoom->daily_room_url) {
            $this->callUrl = $this->selectedRoom->daily_room_url;
        } else {
            // Ako ne postoji, kreiraj novu Daily.co sobu
            $newDailyRoom = $daily->createRoom();
            $this->callUrl = $newDailyRoom['url'] ?? null;

            if ($this->callUrl) {
                // Pohrani URL u bazu podataka za ovu sobu
                $this->selectedRoom->daily_room_url = $this->callUrl;
                $this->selectedRoom->save();
            }
        }

        // Uvijek dispatchaj event da obavijesti druge
        if ($this->callUrl) {
            $this->dispatch('call-started', roomUrl: $this->callUrl);
        }
    }

// Unutar ChatComponent.php

// ... (ostale metode)

    public function startVideoCall()
    {
        // Provjeri je li odabrana soba uopće
        if (!$this->selectedRoom) {
            session()->flash('error', 'Prvo morate odabrati sobu.');
            return;
        }

        // Provjeri postoji li već URL za video poziv u odabranoj sobi, koristeći ispravno ime kolone
        if ($this->selectedRoom->daily_room_url) {
            $this->dispatch('open-video-window', url: $this->selectedRoom->daily_room_url);
            return;
        }

        // Ako URL ne postoji, kreiraj novu sobu
        $dailyService = app(DailyService::class);
        $roomData = $dailyService->createRoom();

        if (isset($roomData['url'])) {
            // Ažuriraj odabranu sobu s novim URL-om
            $this->selectedRoom->daily_room_url = $roomData['url'];
            $this->selectedRoom->save();

            // Ponovno učitaj sobu kako bi se ažurirali i svi korisnici
            $this->selectedRoom->refresh();

            // Emituj event s novim URL-om
            $this->dispatch('open-video-window', url: $roomData['url']);
        } else {
            session()->flash('error', 'Greška pri kreiranju sobe za video poziv.');
        }
    }

    public function onCallStarted($roomUrl)
    {
        $this->callUrl = $roomUrl;

    }



    public function mount()
    {
        $this->loginID = Auth::id();
        $this->sender_id = Auth::id();

        $this->loadActiveUsers();
        $this->loadRooms(); // Učitaj sobe

        if ($this->users->isNotEmpty()) {
            $this->selectUser($this->users->first()->id);
        }
    }

    private function resetChatState()
    {
        $this->selectedUser = null;
        $this->selectedRoom = null;
        $this->receiver_id = null;
        $this->messages = collect();
        $this->hasMore = true;
        $this->loadingMore = false;
        $this->rightSidebarOpen = false;
        $this->file = null; // Clear file input on chat state reset
        $this->newMessage = ''; // Clear message input on chat state reset
        $this->showMembersModal = false; // Reset member modal
        $this->roomMembers = []; // Clear room members
        $this->isCurrentUserAdmin = false; // Reset admin status
    }

    public function loadActiveUsers()
    {
        // Ova metoda ostaje ista
        $loggedInUserId = Auth::id();

        $latestMessagesSubquery = ChatMessage::select(
            DB::raw('LEAST(sender_id, receiver_id) as user1'),
            DB::raw('GREATEST(sender_id, receiver_id) as user2'),
            DB::raw('MAX(created_at) as last_message_at')
        )
            ->whereNotNull('receiver_id') // Samo 1-na-1 poruke
            ->where(function ($query) use ($loggedInUserId) {
                $query->where('sender_id', $loggedInUserId)
                    ->orWhere('receiver_id', $loggedInUserId);
            })
            ->groupBy(
                DB::raw('LEAST(sender_id, receiver_id)'),
                DB::raw('GREATEST(sender_id, receiver_id)')
            );

        $unreadCountsSubquery = ChatMessage::select('sender_id', DB::raw('count(*) as unread_count'))
            ->where('receiver_id', $loggedInUserId)
            ->whereNull('read_at')
            ->groupBy('sender_id');

        $this->users = User::query()
            ->select('users.*', 'latest_msgs.last_message_at', DB::raw('COALESCE(unread_counts.unread_count, 0) as unread_messages_count'))
            ->joinSub($latestMessagesSubquery, 'latest_msgs', function ($join) use ($loggedInUserId) {
                $join->on(function ($q) use ($loggedInUserId) {
                    $q->where(function ($sub) use ($loggedInUserId) {
                        $sub->where('latest_msgs.user1', $loggedInUserId)
                            ->whereColumn('latest_msgs.user2', 'users.id');
                    })->orWhere(function ($sub) use ($loggedInUserId) {
                        $sub->where('latest_msgs.user2', $loggedInUserId)
                            ->whereColumn('latest_msgs.user1', 'users.id');
                    });
                });
            })
            ->leftJoinSub($unreadCountsSubquery, 'unread_counts', function ($join) {
                $join->on('users.id', '=', 'unread_counts.sender_id');
            })
            ->where('users.id', '!=', $loggedInUserId)
            ->orderByDesc('latest_msgs.last_message_at')
            ->get();
    }

    // Nova metoda za učitavanje soba
    public function loadRooms()
    {
        // Prvo, učitamo sve sobe kojima korisnik pripada
        $rooms = Auth::user()->rooms()->with('creator')->orderBy('name')->get();

        // NOVO: Iteriramo kroz svaku sobu da izračunamo nepročitane poruke
        foreach ($rooms as $room) {
            // Uzimamo vrijeme kada je korisnik posljednji put bio aktivan u sobi
            // Ovo je moguće zbog migracije i izmjene u User modelu koje ste napravili
            $lastReadAt = $room->pivot->last_read_at;

            // Započinjemo query za brojanje poruka
            $unreadCountQuery = ChatMessage::where('room_id', $room->id)
                ->where('sender_id', '!=', auth()->id()); // Brojimo samo poruke drugih korisnika

            // Ako je korisnik ikada bio u sobi, brojimo samo poruke nakon zadnje posjete
            if ($lastReadAt) {
                $unreadCountQuery->where('created_at', '>', $lastReadAt);
            }

            // Dodajemo novi properti `unread_messages_count` na objekat sobe
            $room->unread_messages_count = $unreadCountQuery->count();
        }

        $this->rooms = $rooms;
    }

    public function selectUser($id)
    {
        $this->resetChatState();
        $this->selectedUser = User::find($id);
        if (!$this->selectedUser) return;

        $this->receiver_id = $this->selectedUser->id;
        $this->sender_id = Auth::id();

        // Mark messages as read
        ChatMessage::where('sender_id', $this->selectedUser->id)
            ->where('receiver_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $this->loadActiveUsers();
        $this->loadMessages();
        $this->loadSharedMedia();
    }

    // Nova metoda za odabir sobe
    public function selectRoom($id)
    {
        $this->resetChatState();

        $this->selectedRoom = Room::with(['users' => function($query) {
            $query->select('users.*', 'room_user.is_admin')
                ->orderBy('room_user.is_admin', 'desc')
                ->orderBy('username');
        }, 'creator'])->find($id);

        if (!$this->selectedRoom) return;

        $currentUserRoomPivot = $this->selectedRoom->users->firstWhere('id', Auth::id());
        $this->isCurrentUserAdmin = $currentUserRoomPivot ? (bool)$currentUserRoomPivot->pivot->is_admin : false;

        // NOVO: Ažuriranje vremena čitanja i ponovno učitavanje liste soba
        // Ovo će postaviti brojač nepročitanih poruka za ovu sobu na 0
        $pivot = auth()->user()->rooms()->find($id);
        if ($pivot) {
            $pivot->pivot->last_read_at = now();
            $pivot->pivot->save();
        }
        // Nakon ažuriranja, ponovo učitaj sobe da se brojač u sidebaru osvježi
        $this->loadRooms();
        // --- Kraj novog koda ---

        $this->loadMessages();
        $this->loadSharedMedia();

        $this->dispatch('room-selected', roomId: $id);
    }

    public function loadMessages()
    {
        $this->hasMore = true;
        $query = ChatMessage::with('sender');

        if ($this->selectedUser) {
            $query->where(function ($q) {
                $q->where('sender_id', Auth::id())->where('receiver_id', $this->receiver_id);
            })->orWhere(function ($q) {
                $q->where('sender_id', $this->receiver_id)->where('receiver_id', Auth::id());
            });
        } elseif ($this->selectedRoom) {
            $query->where('room_id', $this->selectedRoom->id);
        } else {
            return;
        }

        $initialMessages = $query->orderBy('created_at', 'desc')->limit(20)->get();
        $this->messages = $initialMessages->reverse();

        if ($initialMessages->count() < 20) {
            $this->hasMore = false;
        }

        $this->dispatch("messages-loaded");
    }

    public function loadMore()
    {
        if ($this->loadingMore || !$this->hasMore || $this->messages->isEmpty()) return;
        $this->loadingMore = true;

        $oldestMessage = $this->messages->first();
        if (!$oldestMessage) {
            $this->loadingMore = false;
            $this->hasMore = false;
            return;
        }

        $query = ChatMessage::with('sender')->where('created_at', '<', $oldestMessage->created_at);

        if ($this->selectedUser) {
            $query->where(function ($q) {
                $q->where('sender_id', Auth::id())->where('receiver_id', $this->receiver_id);
            })->orWhere(function ($q) {
                $q->where('sender_id', $this->receiver_id)->where('receiver_id', Auth::id());
            });
        } elseif ($this->selectedRoom) {
            $query->where('room_id', $this->selectedRoom->id);
        }

        $olderMessages = $query->orderBy('created_at', 'desc')->limit(20)->get();

        if ($olderMessages->isEmpty()) {
            $this->hasMore = false;
        } else {
            $this->messages = $olderMessages->reverse()->concat($this->messages);
        }

        $this->loadingMore = false;
    }

    public function loadSharedMedia()
    {
        if (!$this->selectedUser && !$this->selectedRoom) {
            $this->sharedMedia = ['images' => [], 'files' => []];
            return;
        }

        $query = ChatMessage::whereNotNull('file_path')->orderBy('created_at', 'desc');

        if ($this->selectedUser) {
            $query->where(function ($q) {
                $q->where('sender_id', Auth::id())->where('receiver_id', $this->selectedUser->id);
            })->orWhere(function ($q) {
                $q->where('sender_id', $this->selectedUser->id)->where('receiver_id', Auth::id());
            });
        } elseif ($this->selectedRoom) {
            $query->where('room_id', $this->selectedRoom->id);
        }

        $chatMessagesWithFiles = $query->get();
        // Ostatak metode ostaje isti...
        $images = [];
        $files = [];

        foreach ($chatMessagesWithFiles as $message) {
            if ($message->file_path) {
                $fileType = $message->file_type;
                if (!$fileType && $message->file_name) {
                    $extension = pathinfo($message->file_name, PATHINFO_EXTENSION);
                    $mimeTypes = [
                        'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png', 'gif' => 'image/gif',
                        'pdf' => 'application/pdf', 'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                        'txt' => 'text/plain', 'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                    ];
                    $fileType = $mimeTypes[strtolower($extension)] ?? null;
                }

                if ($fileType && Str::startsWith($fileType, 'image/')) {
                    $images[] = $message;
                } else {
                    $files[] = $message;
                }
            }
        }
        $this->sharedMedia = ['images' => $images, 'files' => $files];
    }

    public function submit()
    {
        if (!$this->newMessage && !$this->file) return;

        $filePath = null; $fileName = null; $fileNameOriginal = null; $fileType = null;
        if ($this->file) {
            $fileNameOriginal = $this->file->getClientOriginalName();
            $fileType = $this->file->getMimeType();
            if ($fileType === 'application/octet-stream') {
                $extension = $this->file->getClientOriginalExtension();
                $mimeTypes = [
                    'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg', 'png' => 'image/png',
                    'pdf' => 'application/pdf', 'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'txt' => 'text/plain', 'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.document',
                ];
                $fileType = $mimeTypes[strtolower($extension)] ?? $fileType;
            }
            $filePath = $this->file->store('chat_files', 'public');
            $fileName = basename($filePath);
        }



        $message = ChatMessage::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $this->selectedUser ? $this->selectedUser->id : null,
            'message' => $this->newMessage ?? '',
            'file_name' => $fileName,
            'file_name_original' => $fileNameOriginal,
            'file_path' => $filePath,
            'file_type' => $fileType,
            'file_size' => $this->file ? $this->file->getSize() : null, // Dodano za veličinu fajla
            'room_id' => $this->selectedRoom ? $this->selectedRoom->id : null,
        ]);

        $this->newMessage = '';
        $this->file = null;
        $this->messages->push($message);
        $this->loadActiveUsers();
        if ($message->file_path) { $this->loadSharedMedia(); }

        // Prilagođen Broadcast
        if ($this->selectedUser) {
            broadcast(new MessageSent3($message));

        } elseif ($this->selectedRoom) {
            // Emituj novi event za sobu
            broadcast(new RoomMessageSent($message))->toOthers();


        }

        $this->dispatch("messages-updated");
    }

    public function userTyping()
    {
        if($this->selectedUser) {
            broadcast(new UserTyping(Auth::id(), $this->selectedUser->id, Auth::user()->username ?? 'Korisnik'))->toOthers();
        } elseif ($this->selectedRoom) {
            broadcast(new UserTypingInRoom(Auth::id(), Auth::user()->username, $this->selectedRoom->id))->toOthers();

        }
    }

    // Metode za modale
    public function openCreateRoomModal() { $this->showCreateRoomModal = true; }
    public function closeCreateRoomModal() { $this->showCreateRoomModal = false; $this->newRoomName = ''; }

    public function createRoom()
    {
        // Kreiramo niz podataka koje želimo validirati.
        $data = [
            'name' => $this->newRoomName,
            'opis' => $this->newRoomOpis,
            'kategorija' => $this->newRoomKategorija,
            'privatnost' => $this->newRoomPrivatnost,
            'profilna_slika' => $this->newRoomProfilnaSlika,
        ];

        // Definiramo pravila validacije.
        $rules = [
            'name' => 'required|string|min:3|max:50',
            'opis' => 'nullable|string|max:255',
            'kategorija' => 'required|in:osnovna skola,srednja skola,fakultet,jezici,ostalo',
            'privatnost' => 'required|in:otvorena,privatna',
            'profilna_slika' => 'nullable|image|max:1024',
        ];

        // Koristimo standardni Laravel Validator kako bismo izbjegli sukob
        // s Livewire propertijima.
        $validatedData = \Illuminate\Support\Facades\Validator::make($data, $rules)->validate();

        $profileImagePath = null;
        if (isset($validatedData['profilna_slika'])) {
            $profileImagePath = $validatedData['profilna_slika']->store('rooms', 'public');
        }

        $room = Room::create([
            'name' => $validatedData['name'],
            'opis' => $validatedData['opis'] ?? null,
            'kategorija' => $validatedData['kategorija'],
            'privatnost' => $validatedData['privatnost'],
            'profilna_slika' => $profileImagePath,
            'created_by' => Auth::id(),
        ]);

        $room->users()->attach(Auth::id(), ['is_admin' => true, 'last_read_at' => now()]);

        $this->showCreateRoomModal = false;
        $this->resetCreateRoomModal();

        $this->loadRooms();
        $this->selectRoom($room->id);
    }

    public function resetCreateRoomModal() {
        $this->reset(['newRoomName', 'newRoomOpis', 'newRoomKategorija', 'newRoomPrivatnost', 'newRoomProfilnaSlika']);
        $this->newRoomKategorija = 'ostalo';
        $this->newRoomPrivatnost = 'otvorena';
    }
    public function openAddUserModal() {
        if (!$this->selectedRoom) return;
        // Provjerite je li trenutni korisnik admin ili kreator
        if (!$this->isCurrentUserAdmin && Auth::id() !== $this->selectedRoom->created_by) {
            session()->flash('error', 'Nemate dozvolu za dodavanje korisnika u ovu grupu.');
            return;
        }

        $this->userSearchQuery = '';
        $this->searchUsers();
        $this->showAddUserModal = true;
    }
    public function closeAddUserModal() { $this->showAddUserModal = false; }

    public function updatedUserSearchQuery() { $this->searchUsers(); }


    public $sentInvitations = [];

    public function searchUsers()
    {
        // Provjeri da li je soba odabrana
        if (!$this->selectedRoom) {
            $this->availableUsersToAdd = [];
            return;
        }

        // Dohvati sve korisnike koji nisu u odabranoj sobi
        $usersInRoom = $this->selectedRoom->users->pluck('id')->toArray();
        $usersInRoom[] = Auth::id(); // Ne dodajemo sebe

        $query = User::where('id', '!=', Auth::id())
            ->whereNotIn('id', $usersInRoom);

        if ($this->userSearchQuery) {
            $query->where('username', 'like', '%' . $this->userSearchQuery . '%');
        }

        $this->availableUsersToAdd = $query->limit(10)->get();

        // Ovdje dohvaćamo sve pending pozivnice za ovu sobu
        $this->sentInvitations = RoomInvitation::where('room_id', $this->selectedRoom->id)
            ->where('status', 'pending')
            ->pluck('user_id')
            ->toArray();
    }

    public function addUserToRoom($userId)
    {
        if (!$this->selectedRoom) return;

        // Autorizacija (vaš originalni kod)
        if (!$this->isCurrentUserAdmin && Auth::id() !== $this->selectedRoom->created_by) {
            session()->flash('error', 'Nemate dozvolu za dodavanje korisnika u ovu grupu.');
            return;
        }

        $user = User::find($userId);

        // Provjeri da li je korisnik već u grupi ili ima aktivnu pozivnicu
        if ($user && !$this->selectedRoom->users->contains($user) && !in_array($userId, $this->sentInvitations)) {
            // Kreiraj pozivnicu
            RoomInvitation::create([
                'room_id' => $this->selectedRoom->id,
                'user_id' => $userId,
                'status' => 'pending',
            ]);

            // Dodajemo korisnika u listu poslanih pozivnica
            $this->sentInvitations[] = $userId;

            // ... kreiranje obavijesti
            Obavijest::create([
                'korisnik_id' => $userId,
                'naslov' => 'Pozivnica za grupu: ' . $this->selectedRoom->name,
                'sadrzaj' => 'Pozvani ste da se pridružite grupi ' . $this->selectedRoom->name . '.',
                // Možete dodati i link na obavijest u Blade view-u za akcije
            ]);

            session()->flash('message', 'Pozivnica je poslana korisniku ' . $user->username . '.');
            $this->searchUsers(); // Osvježi listu
        } else {
            session()->flash('error', 'Korisnik je već u grupi ili ima pozivnicu na čekanju.');
        }
    }




    // NOVO: Metode za modal članova
    public function openMembersModal()
    {
        if (!$this->selectedRoom) return;

        // Osvježi listu članova sa pivot informacijama
        $this->selectedRoom->load(['users' => function($query) {
            $query->select('users.*', 'room_user.is_admin')
                ->orderBy('room_user.is_admin', 'desc')
                ->orderBy('username');
        }]);
        $this->roomMembers = $this->selectedRoom->users;
        $this->showMembersModal = true;
    }

    public function closeMembersModal()
    {
        $this->showMembersModal = false;
        $this->roomMembers = [];
    }

    public function makeAdmin($userId)
    {
        if (!$this->selectedRoom) return;

        // Autorizacija: Samo kreator grupe može dodijeliti admin status
        if (Auth::id() !== $this->selectedRoom->created_by) {
            session()->flash('error', 'Samo kreator grupe može dodijeliti administratorska prava.');
            return;
        }

        // Provjeri da li je korisnik član grupe
        $member = $this->selectedRoom->users->firstWhere('id', $userId);
        if ($member) {
            $this->selectedRoom->users()->updateExistingPivot($userId, ['is_admin' => true]);
            session()->flash('message', $member->username . ' je sada administrator grupe.');
            $this->openMembersModal(); // Osvježi modal
        } else {
            session()->flash('error', 'Korisnik nije član ove grupe.');
        }
    }

    public function removeMember($userId)
    {
        if (!$this->selectedRoom) return;

        // Autorizacija: Samo kreator grupe ili admin mogu ukloniti članove
        if (Auth::id() !== $this->selectedRoom->created_by && !$this->isCurrentUserAdmin) {
            session()->flash('error', 'Nemate dozvolu za uklanjanje članova iz ove grupe.');
            return;
        }

        // Ne dozvoli uklanjanje kreatora grupe
        if ($userId === $this->selectedRoom->created_by) {
            session()->flash('error', 'Kreator grupe se ne može ukloniti.');
            return;
        }

        // Ako trenutni korisnik nije kreator, ali je admin, ne može ukloniti drugog admina (osim ako nije kreator)
        $memberToRemove = $this->selectedRoom->users->firstWhere('id', $userId);
        if ($memberToRemove && $memberToRemove->pivot->is_admin && Auth::id() !== $this->selectedRoom->created_by) {
            session()->flash('error', 'Kao administrator, ne možete ukloniti drugog administratora.');
            return;
        }

        $this->selectedRoom->users()->detach($userId);
        session()->flash('message', 'Korisnik je uspješno uklonjen iz grupe.');
        $this->selectedRoom->load('users'); // Osvježi listu članova
        $this->openMembersModal(); // Osvježi modal
        $this->loadRooms(); // Osvježi listu soba (ako se promijeni broj članova)
        $this->selectRoom($this->selectedRoom->id); // Re-select room to update count in sidebar
    }


    // Toggle sidebar metoda ostaje ista
    public function toggleRightSidebar()
    {
        $this->rightSidebarOpen = !$this->rightSidebarOpen;
        if ($this->rightSidebarOpen) {
            $this->loadSharedMedia();
        }
    }

    // Listeneri ostaju isti za sada, broadcast za sobe treba dodati
    public function getListeners(): array
    {
        // ISPRAVKA: Uklonjeni dinamički Echo listeneri
        return [
            'refreshComponent' => '$refresh',
            "echo-private:chat3.{$this->loginID},MessageSent3" => "newMessageSentNotification",
            "echo-private:chat3.{$this->loginID},MessageRead" => "messageReadNotification",
            // Definišemo listenere koje će JS pozivati
            'incomingRoomMessage' => 'incomingRoomMessage',
            'userTypingNotification' => 'userTypingNotification',
            'call-started' => 'onCallStarted'
        ];
    }

    //newMessageSentNotification metoda treba prilagodbu za sobe
    public function newMessageSentNotification($payload)
    {
        // ...

        if ($this->selectedUser && $payload['sender_id'] == $this->selectedUser->id) {
            ChatMessage::where('sender_id', $this->selectedUser->id)
                ->where('receiver_id', Auth::id())
                ->whereNull('read_at')
                ->update(['read_at' => now()]);

            $newMessage = ChatMessage::find($payload['id']);
            if($newMessage) {
                $this->messages->push($newMessage);
                $this->dispatch('messages-updated'); // Važno za skrolanje
                // Ako nova poruka sadrži fajl, osvježi listu dijeljenih medija
                if ($newMessage->file_path) {
                    $this->loadSharedMedia();
                }
            }
        } else {
            $this->loadActiveUsers();
        }

    }

    // Method to allow a user to leave a room
    public function leaveRoom()
    {
        if ($this->selectedRoom) {
            Auth::user()->rooms()->detach($this->selectedRoom->id);
            session()->flash('message', 'Uspješno ste napustili grupu.');
            $this->resetChatState();
            $this->loadRooms();
            $this->loadActiveUsers();
        }
    }

    // Method to allow the room creator to delete the room
    public function deleteRoom()
    {
        if ($this->selectedRoom && Auth::id() === $this->selectedRoom->created_by) {
            // Delete associated chat messages first
            ChatMessage::where('room_id', $this->selectedRoom->id)->delete();
            // Then detach all users
            $this->selectedRoom->users()->detach();
            // Finally, delete the room
            $this->selectedRoom->delete();

            session()->flash('message', 'Grupa je uspješno izbrisana.');
            $this->resetChatState();
            $this->loadRooms();
            $this->loadActiveUsers();
        } else {
            session()->flash('error', 'Nemate dozvolu za brisanje ove grupe.');
        }
    }


    public function render()
    {
        return view('livewire.chat-component');
    }



//3007

    // NOVA METODA za primanje poruka u sobi
    public function incomingRoomMessage($payload)
    {


        $messageData = $payload['message'];

        // Provjeri da li je poruka za trenutno aktivnu sobu
        if ($this->selectedRoom && $this->selectedRoom->id == $messageData['room_id']) {
            // AKO JE SOBA OTVORENA: prikaži novu poruku u prozoru
            if ($messageData['sender_id'] != Auth::id()) {
                $newMessage = ChatMessage::with('sender')->find($messageData['id']);
                if ($newMessage) {
                    $this->messages->push($newMessage);
                    $this->dispatch('messages-updated');
                }
            }
        } else {
            // NOVO: AKO SOBA NIJE OTVORENA: ponovo učitaj listu soba
            // Ovo će ponovo pokrenuti logiku u loadRooms() koja broji nepročitane
            // poruke i Livewire će automatski osvježiti prikaz u sidebaru.

            $this->loadRooms();
        }
    }

    // NOVA METODA za "typing" notifikaciju
    public function userTypingNotification($payload)
    {
        if ($this->selectedRoom && $payload['userId'] != Auth::id()) {
            $this->dispatch('user-is-typing', name: $payload['userName']);
        }
    }
}
