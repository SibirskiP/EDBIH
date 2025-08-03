<?php

namespace App\Livewire;

use App\Events\MessageSent3;
use App\Events\UserTyping;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str; // Dodaj za Str::startsWith

class ChatComponent extends Component
{
    use WithFileUploads;

    public $users;
    public $selectedUser;
    public $newMessage;
    public $messages;
    public $loginID;
    public $sender_id;
    public $receiver_id;

    // Dodano za desni sidebar
    public $rightSidebarOpen = false;
    public $sharedMedia = [
        'images' => [],
        'files' => [],
    ];

    public $hasMore = true;
    public $loadingMore = false;

    public $file;
    protected $rules = [
        'file' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,pdf,docx,txt',
    ];

    public function mount()
    {
        $this->loginID = Auth::id();
        $this->sender_id = Auth::id();

        $this->loadActiveUsers();

        if ($this->users->isNotEmpty()) {
            $this->selectUser($this->users->first()->id);
        }
    }

    private function loadActiveUsers()
    {
        $loggedInUserId = Auth::id();

        $latestMessagesSubquery = ChatMessage::select(
            DB::raw('LEAST(sender_id, receiver_id) as user1'),
            DB::raw('GREATEST(sender_id, receiver_id) as user2'),
            DB::raw('MAX(created_at) as last_message_at')
        )
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


    public function selectUser($id)
    {
        $this->selectedUser = User::find($id);
        if (!$this->selectedUser) return;

        $this->receiver_id = $this->selectedUser->id;
        $this->sender_id = Auth::id();

        $this->messages = collect();
        $this->hasMore = true;
        $this->loadingMore = false;

        ChatMessage::where('sender_id', $this->selectedUser->id)
            ->where('receiver_id', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        $this->loadActiveUsers();
        $this->loadMessages();
        $this->loadSharedMedia(); // Dodano: Učitaj dijeljene medije kad se odabere korisnik
    }

    public function loadMessages()
    {
        if (!$this->selectedUser) return;
        $this->hasMore = true;

        $initialMessages = ChatMessage::with('sender')
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->where('sender_id', Auth::id())
                        ->where('receiver_id', $this->receiver_id);
                })->orWhere(function ($q) {
                    $q->where('sender_id', $this->receiver_id)
                        ->where('receiver_id', Auth::id());
                });
            })
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        $this->messages = $initialMessages->reverse();

        if ($initialMessages->count() < 20) {
            $this->hasMore = false;
        }

        $this->dispatch("messages-loaded");
    }

    public function loadMore()
    {
        if ($this->loadingMore || !$this->hasMore || $this->messages->isEmpty()) {
            return;
        }

        $this->loadingMore = true;

        $oldestMessage = $this->messages->first();
        if (!$oldestMessage) {
            $this->loadingMore = false;
            $this->hasMore = false;
            return;
        }

        $olderMessages = ChatMessage::with('sender')
            ->where(function ($query) {
                $query->where(function ($q) {
                    $q->where('sender_id', Auth::id())
                        ->where('receiver_id', $this->receiver_id);
                })->orWhere(function ($q) {
                    $q->where('sender_id', $this->receiver_id)
                        ->where('receiver_id', Auth::id());
                });
            })
            ->where('created_at', '<', $oldestMessage->created_at)
            ->orderBy('created_at', 'desc')
            ->limit(20)
            ->get();

        if ($olderMessages->isEmpty()) {
            $this->hasMore = false;
        } else {
            $this->messages = $olderMessages->reverse()->concat($this->messages);
            // Nema dispatcha 'messages-prepended' jer se Alpine.js handleScroll oslanja na promjenu visine
            // ali poziv dispatch('messages-loaded'); u mount i selectUser će ponovno skrolati dolje.
            // Vaša logika u blade fajlu za handleScroll je već dobra za zadržavanje scroll pozicije.
        }

        $this->loadingMore = false;
    }

    // Nova metoda za dohvaćanje dijeljenih medija
    public function loadSharedMedia()
    {
        if (!$this->selectedUser) {
            $this->sharedMedia = ['images' => [], 'files' => []];
            return;
        }

        $chatMessagesWithFiles = ChatMessage::where(function ($query) {
            $query->where(function ($q) {
                $q->where('sender_id', Auth::id())
                    ->where('receiver_id', $this->selectedUser->id);
            })->orWhere(function ($q) {
                $q->where('sender_id', $this->selectedUser->id)
                    ->where('receiver_id', Auth::id());
            });
        })
            ->whereNotNull('file_path')
            ->orderBy('created_at', 'desc')
            ->get();

        $images = [];
        $files = [];

        foreach ($chatMessagesWithFiles as $message) {
            if ($message->file_path) {
                // Provjeri da li je file_type postavljen, ako nije, pokušaj pogoditi iz file_name
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

        $this->sharedMedia = [
            'images' => $images,
            'files' => $files,
        ];
    }

    // Metoda za otvaranje/zatvaranje desnog sidebara
    public function toggleRightSidebar()
    {
        $this->rightSidebarOpen = !$this->rightSidebarOpen;
        // Ako se otvara sidebar, ponovno učitaj medije da budu ažurni
        if ($this->rightSidebarOpen) {
            $this->loadSharedMedia();
        }
    }


    public function getListeners(): array
    {
        return [
            // 'loadMore' => 'loadMore', // Uklonjeno jer se sada direktno poziva iz Alpine.js
            'refreshComponent' => '$refresh',
            "echo-private:chat3.{$this->loginID},MessageSent3" => "newMessageSentNotification",
            "echo-private:chat3.{$this->loginID},MessageRead" => "messageReadNotification",
        ];
    }

    public function newMessageSentNotification($payload)
    {
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

    public function submit()
    {
        if (!$this->newMessage && !$this->file) return;

        $filePath = null;
        $fileName = null;
        $fileNameOriginal = null;
        $fileType = null;

        if ($this->file) {
            $fileNameOriginal = $this->file->getClientOriginalName();
            $fileType = $this->file->getMimeType();

            if ($fileType === 'application/octet-stream') {
                $extension = $this->file->getClientOriginalExtension();
                $mimeTypes = [
                    'jpg' => 'image/jpeg',
                    'jpeg' => 'image/jpeg',
                    'png' => 'image/png',
                    'pdf' => 'application/pdf',
                    'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    'txt' => 'text/plain',
                    'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', // Dodano za Excel
                ];
                $fileType = $mimeTypes[strtolower($extension)] ?? $fileType;
            }

            $filePath = $this->file->store('chat_files', 'public');
            $fileName = basename($filePath);
        }
        $message = ChatMessage::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $this->selectedUser->id,
            'message' => $this->newMessage ?? '',
            'file_name' => $fileName,
            'file_name_original' => $fileNameOriginal,
            'file_path' => $filePath,
            'file_type' => $fileType,
            'file_size' => $this->file ? $this->file->getSize() : null, // Dodano za veličinu fajla
        ]);

        $this->newMessage = '';
        $this->file = null;
        $this->messages->push($message);

        $this->loadActiveUsers();
        // Ako je poslat fajl, osvježi listu dijeljenih medija
        if ($message->file_path) {
            $this->loadSharedMedia();
        }

        broadcast(new MessageSent3($message));

        $this->dispatch("messages-updated");
    }

    public function userTyping()
    {
        broadcast(new UserTyping(Auth::id(), $this->selectedUser->id, Auth::user()->username ?? 'Korisnik'))->toOthers();
    }

    public function render()
    {
        return view('livewire.chat-component');
    }
}
