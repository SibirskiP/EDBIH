<?php

namespace App\Http\Controllers;

use App\Models\Objava;
use App\Models\User;
use Illuminate\Http\Request;

class ObjavaController extends Controller
{
    //

    public function index(Request $request){

        $query=Objava::query();
        if ($request->has('kategorije') && !empty($request->kategorije)) {
            $query->whereIn('kategorija', $request->kategorije);
        }

        if ($request->has('naziv') && $request->naziv != '') {
            $query->where('naziv', 'like', '%' . $request->naziv . '%');
        }

        $objave = $query->latest()->simplePaginate(6)->appends($request->all());

            return view('objave.index', compact('objave'));
    }



    public function store(Request $request)
    {
        $request->validate([
            'naziv' => 'required',
            'sadrzaj' => 'required',
            'materijal' => 'nullable|mimes:jpg,jpeg,png,pdf,rar|max:4048',
            'kategorija' => 'required',
        ]);

        $path = null;
        if ($request->hasFile('materijal')) {
            $uploadedFile = $request->file('materijal');
            $originalName = $uploadedFile->getClientOriginalName();
            $timestamp = time();
            $filename = $timestamp . '_' . $originalName;
            $path = $uploadedFile->storeAs('objave_materijal', $filename, 'public');
        }

        Objava::create([
            'naziv' => $request->naziv,
            'sadrzaj' => $request->sadrzaj,
            'putanja' => $path,
            'kategorija' => $request->kategorija,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('objave.index')->with('success', 'Objava uspješno kreirana!');
    }


    public function show(Objava $objava){
        if(\Auth::guest()){
            return redirect('/login');
        }
        $komentari=$objava->komentari;

        $user=User::with('objave')->where('id',$objava->user_id)->first();
        $kategorije = $user->instrukcije()->distinct()->pluck('kategorija');

        return view('objave.show', ['objava'=>$objava,'komentari'=>$komentari,'kategorije'=>$kategorije]);


    }


    public function update(Request $request, Objava $objava){

        $request->validate([
            'naziv' => 'required',
            'sadrzaj' => 'required',
            'kategorija' => 'required',

        ]);
        try {
            $objava->update([

                'naziv'=>request('naziv'),
                'sadrzaj'=>request('sadrzaj'),
                'kategorija'=>request('kategorija'),
            ]);
        }
        catch (\Exception $e) {
            dd($e->getMessage());
        }

        return redirect('/objave/'.$objava->id);



    }

    public function destroy (Objava $objava)
    {
        $objava->delete();
        return redirect()->route('objave.index');


    }

}
