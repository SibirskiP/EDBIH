<?php

namespace App\Http\Controllers;

use App\Models\Obavijest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ObavijestController extends Controller
{
    public function markAsRead(Obavijest $obavijest)
    {
        if (Auth::id() !== $obavijest->korisnik_id) {
            return response()->json(['message' => 'Nemate ovlaštenje za pristup ovoj obavijesti.'], 403);
        }

        if ($obavijest->procitano) {
            return response()->json(['message' => 'Obavijest je već označena kao pročitana.'], 200);
        }

        $obavijest->procitano = true;
        $obavijest->save();

        // Vraćanje JSON odgovora za front-end
        return response()->json(['message' => 'Obavijest je uspješno označena kao pročitana.'], 200);
    }
}
