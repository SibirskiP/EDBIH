<?php

namespace App\Http\Controllers;

use App\Models\Komentar;
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

       return redirect()->back()->with('success', 'Komentar uspješno kreiran!');
    }

    public function destroy(Komentar $komentar){

        $komentar->delete();
        return redirect()->back();

    }

}
