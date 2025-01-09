<?php

namespace App\Http\Controllers;

use App\Models\Materijal;


use Illuminate\Http\Request;

class MaterijalController extends Controller
{
    public function index(Request $request){
        $query = Materijal::query();

        if ($request->has('kategorije') && !empty($request->kategorije)) {
            $query->whereIn('kategorija', $request->kategorije);
        }

        if ($request->has('naziv') && $request->naziv != '') {
            $query->where('naziv', 'like', '%' . $request->naziv . '%');
        }

        $materijali = $query->latest()->simplePaginate(6)->appends($request->all());

        return view('materijali.index', compact('materijali'));
    }

    public function store(Request $request){
        $request->validate([
            'materijal' => 'required|mimes:jpg,jpeg,png,pdf,rar|max:4048'
        ]);

        // Uzimanje originalnog imena fajla
        $uploadedFile = $request->file('materijal');
        $originalName = $uploadedFile->getClientOriginalName();

        // Dodavanje vremenskog pečata u ime fajla da bude jedinstveno
        $timestamp = time();  // Trenutni vremenski pečat
        $filename = $timestamp . '_' . $originalName; // Kreiranje jedinstvenog imena fajla

        // Čuvanje fajla uz novo ime
        $path = $uploadedFile->storeAs('uploads', $filename, 'public');

        // Kreiranje zapisa u bazi
        $materijal = Materijal::create([
            'naziv' => $filename, // Koristimo novo ime fajla
            'opis' => $request->opis,
            'putanja' => $path,
            'kategorija'=>request('kategorija'),
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('materijali.index')->with('success', 'Materijal uspješno uploadovan!');
    }

    public function download(Materijal $materijal)
    {
        $filePath = storage_path('app/public/' . $materijal->putanja);

        if (!file_exists($filePath)) {
            abort(404, 'Fajl nije pronađen.');
        }

        return response()->download($filePath, $materijal->naziv);
    }

    public function show(){

    }

    public function create(){
        return view('materijali.create');
    }

    public function destroy( $id){

        $materijal=Materijal::find($id);
        $filePath = storage_path('app/public/' . $materijal->putanja);

        if (file_exists($filePath)) {
            unlink($filePath);
        }

        $materijal->delete();

        return redirect()->route('materijali.index')->with('success', 'Materijal je uspješno obrisan.');

    }
}
