<?php

namespace App\Http\Controllers;

use App\Models\Odgovor;
use Illuminate\Http\Request;

class OdgovorController extends Controller
{
    //

    public function store(Request $request){

        $request->validate([
            'sadrzaj' => 'required',

        ]);

        Odgovor::create([
            'sadrzaj'=>request('sadrzaj'),
            'user_id'=>auth()->id(),
            'komentar_id'=>request('komentar_id'),
        ]);

        return redirect()->back()->with('success', 'Komentar uspješno kreiran!');

    }

    public function destroy(Odgovor $odgovor){

        $odgovor->delete();
        return redirect()->back();

    }

}
