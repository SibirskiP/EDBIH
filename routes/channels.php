<?php

use App\Models\Room;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('chat', function ($user) {
    return true; // ili validacija korisnika
});


// Javni kanal demo-channel - nema autentifikacije, svi mogu da slušaju
Broadcast::channel('demo-channel', function () {
    return true;
});



Broadcast::channel('chat3.{receiver_id}', function ($user, $receiver_id) {
    return (int) $user->id === (int) $receiver_id;
});


//dodano za sobe 3007

Broadcast::channel('room.{roomId}', function ($user, $roomId) {
    // Provjeri da li je korisnik član sobe.
    $room = Room::find($roomId);
    if ($room && $room->users->contains($user)) {
        return ['id' => $user->id, 'name' => $user->username]; // Vrati podatke o korisniku
    }
    return false;
});

