<?php

namespace App\Http\Controllers;

use App\Models\RoomInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomInvitationController extends Controller
{
    /**
     * Prihvaća pozivnicu za sobu, dodaje korisnika u sobu i briše pozivnicu.
     */
    public function accept(RoomInvitation $roomInvitation)
    {
        // Provjera da li je prijavljeni korisnik vlasnik pozivnice
        if (Auth::id() !== $roomInvitation->user_id) {
            return response()->json(['message' => 'Nemate ovlaštenje za pristup ovoj pozivnici.'], 403);
        }

        // Dodavanje korisnika u sobu
        $roomInvitation->room->users()->attach(Auth::id());

        // Brisanje pozivnice
        $roomInvitation->delete();

        // Vraćanje JSON odgovora za front-end
        return response()->json(['message' => 'Pozivnica je uspješno prihvaćena!'], 200);
    }

    /**
     * Odbija pozivnicu za sobu i briše je.
     */
    public function decline(RoomInvitation $roomInvitation)
    {
        // Provjera da li je prijavljeni korisnik vlasnik pozivnice
        if (Auth::id() !== $roomInvitation->user_id) {
            return response()->json(['message' => 'Nemate ovlaštenje za pristup ovoj pozivnici.'], 403);
        }

        // Brisanje pozivnice
        $roomInvitation->delete();

        // Vraćanje JSON odgovora za front-end
        return response()->json(['message' => 'Pozivnica je uspješno odbijena.'], 200);
    }
}
