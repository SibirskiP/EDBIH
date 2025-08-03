<?php

namespace App\Livewire;

use App\Events\MessageSent3;
use App\Events\RoomMessageSent;
use App\Events\UserTyping;
use App\Events\UserTypingInRoom;
use App\Models\ChatMessage;
use App\Models\Room; // Dodano
use App\Models\User;
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


    protected $rules = [
        'file' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,pdf,docx,txt',
        'newRoomName' => 'required|string|min:3|max:50', // Pravilo za naziv sobe
    ];

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
        $this->rooms = Auth::user()->rooms()->with('creator')->orderBy('name')->get();
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
        $this->selectedRoom = Room::with('users')->find($id);
        if (!$this->selectedRoom) return;

        $this->loadMessages();
        $this->loadSharedMedia();

        // ISPRAVKA: Emituj browser event sa ID-em nove sobe
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
                    'txt' => 'text/plain', 'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
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

        $room = Room::create([
            'name' => $this->newRoomName,
            'created_by' => Auth::id(),
        ]);
        $room->users()->attach(Auth::id()); // Dodaj kreatora u sobu
        $this->closeCreateRoomModal();
        $this->loadRooms();
        $this->selectRoom($room->id);


    }

    public function openAddUserModal() {
        $this->userSearchQuery = '';
        $this->searchUsers();
        $this->showAddUserModal = true;
    }
    public function closeAddUserModal() { $this->showAddUserModal = false; }

    public function updatedUserSearchQuery() { $this->searchUsers(); }

    public function searchUsers()
    {
        if (!$this->selectedRoom) return;

        $existingUserIds = $this->selectedRoom->users->pluck('id');

        $this->availableUsersToAdd = User::where('id', '!=', Auth::id())
            ->whereNotIn('id', $existingUserIds)
            ->where('username', 'like', '%' . $this->userSearchQuery . '%')
            ->limit(10)
            ->get();
    }

    public function addUserToRoom($userId)
    {
        if ($this->selectedRoom) {
            $this->selectedRoom->users()->attach($userId);
            $this->selectedRoom->load('users'); // Ponovo učitaj relaciju
            $this->searchUsers(); // Osvježi listu dostupnih korisnika
            // Opcionalno: pošalji sistemsku poruku u chat
        }
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
            'userTypingNotification' => 'userTypingNotification'
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

    public function render()
    {
        return view('livewire.chat-component');
    }



//3007

    // NOVA METODA za primanje poruka u sobi
    public function incomingRoomMessage($payload)
    {

        // Provjeri da li je poruka za trenutno aktivnu sobu
        $messageData = $payload['message'];
        if ($this->selectedRoom && $this->selectedRoom->id == $messageData['room_id']) {
            // Ne dodaji poruku ako je pošiljalac trenutni korisnik (broadcast je toOthers())
            if ($messageData['sender_id'] != Auth::id()) {
                $newMessage = ChatMessage::with('sender')->find($messageData['id']);
                if ($newMessage) {
                    $this->messages->push($newMessage);
                    $this->dispatch('messages-updated');
                }
            }
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




