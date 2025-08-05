<?php

namespace App\Http\Controllers;

use App\Models\Komentar;
use App\Models\Obavijest;
use App\Models\Objava;
use Illuminate\Http\Request;

class KomentarController extends Controller
{
    //


    public function store(Request $request){

        $request->validate([
            'sadrzaj' => 'required',

        ]);

        Komentar::create([
            'sadrzaj'=>request('sadrzaj'),
            'user_id'=>auth()->id(),
            'objava_id'=>request('objava_id'),
        ]);

        $objava=Objava::find(request('objava_id'));
        $primatelj=$objava->user_id;

        Obavijest::create([
            'korisnik_id' => $primatelj,
            'naslov' => 'Komentar na objavu: ' . $objava->naziv,
            'sadrzaj' => 'Dobili ste komentar:' . request('sadrzaj') . '.',
            // Možete dodati i link na obavijest u Blade view-u za akcije
        ]);




       return redirect()->back()->with('success', 'Komentar uspješno kreiran!');
    }

    public function destroy(Komentar $komentar){

        $komentar->delete();
        return redirect()->back();

    }

}
