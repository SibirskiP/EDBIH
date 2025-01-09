<?php

namespace App\Http\Controllers;

use App\Mail\InstrukcijaMade;
use App\Models\Instrukcija;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class InstrukcijaController extends Controller
{
    //
    public function index(Request $request){

        $query = Instrukcija::query();

        // Filtriranje po kategoriji
        if ($request->has('kategorije') && !empty($request->kategorije)) {
            $query->whereIn('kategorija', $request->kategorije);
        }


        // Filtriranje po lokaciji
        if ($request->has('lokacije') && !empty($request->lokacije)) {
            $query->whereIn('lokacija', $request->lokacije);
        }

        // Pretraga po nazivu (username)
        if ($request->has('naziv') && $request->naziv != '') {
            $query->where('naziv', 'like', '%' . $request->naziv . '%');
        }

        if ($request->has('cijena') && !empty($request->cijena)) {
            $query->where('cijena', '<=', $request->cijena);
        }

        $instrukcije = $query->latest()->simplePaginate(6)->appends($request->all());

        return view('instrukcije/index',[

            'instrukcije'=>$instrukcije
        ]);
    }
    public function show(Instrukcija $instrukcija){

        if(\Auth::guest()){
            return redirect('/login');
        }
        $user=User::with('instrukcije')->where('id',$instrukcija->user_id)->first();
        $kategorije = $user->instrukcije()->distinct()->pluck('kategorija');



        return view('instrukcije/show',['instrukcija'=>$instrukcija,'kategorije'=>$kategorije]);

    }
    public function store(){



        if(Auth::guest()){
            return redirect('/login');
        }
        try {
            request()->validate([
                'kategorija'=>'required',
                'vrsta'=>'required',
                'lokacija'=>'required',
                'naziv'=>['required','string'],
                'cijena'=>['required','numeric','min:5','max:999'],
                'opis2'=>'required',


            ]);
        }
        catch (\Exception $e){
            session()->flash('failure', $e->getMessage());
            return redirect('/instrukcije');
        }


       $instrukcija= Instrukcija::create([
            'user_id'=>Auth::id(),
            'kategorija'=>request('kategorija'),
            'vrsta'=>request('vrsta'),
            'lokacija'=>request('lokacija'),
            'naziv'=>request('naziv'),
            'cijena'=>request('cijena'),
            'opis'=>request('opis2')


        ]);
//        Mail::to($instrukcija->user)->send(new InstrukcijaMade($instrukcija));
//         session()->flash('success', 'Instrukcija je uspješno unesena!');
//         return redirect('/instrukcije');

        Mail::to($instrukcija->user)->queue(new InstrukcijaMade($instrukcija));
        session()->flash('success', 'Instrukcija je uspješno unesena!');
        return redirect('/instrukcije');

    }

    public function update(Instrukcija $instrukcija){

        if(Auth::guest()){
            return redirect('/login');
        }

        try {
            request()->validate([
                'kategorija'=>'required',
                'vrsta'=>'required',
                'lokacija'=>'required',
                'naziv'=>['required','string'],
                'cijena'=>['required','numeric','min:5','max:999'],
                'opis2'=>'required',


            ]);
        }
        catch (\Exception $e){
            session()->flash('failure', $e->getMessage());
            return redirect('/instrukcije');
        }

        $instrukcija->update([
            'kategorija'=>request('kategorija'),
            'vrsta'=>request('vrsta'),
            'lokacija'=>request('lokacija'),
            'naziv'=>request('naziv'),
            'cijena'=>request('cijena'),
            'opis'=>request('opis2')

        ]);

        return redirect('/instrukcije/'.$instrukcija->id);

    }

    public function destroy (Instrukcija $instrukcija)

    {

        $instrukcija->delete();
        return redirect('/instrukcije');


    }


//API METODE NAKNADNO DODANE SAMO ZA POKAZIVANJE FUNKCIONALNOSTI
    public function indexAPI(Request $request)
    {

        $query = Instrukcija::query();

        // Filtriranje po kategoriji
        if ($request->has('kategorije') && !empty($request->kategorije)) {
            $query->whereIn('kategorija', $request->kategorije);
        }

        // Filtriranje po lokaciji
        if ($request->has('lokacije') && !empty($request->lokacije)) {
            $query->whereIn('lokacija', $request->lokacije);
        }

        // Pretraga po nazivu
        if ($request->has('naziv') && $request->naziv != '') {
            $query->where('naziv', 'like', '%' . $request->naziv . '%');
        }

        // Filtriranje po cijeni
        if ($request->has('cijena') && !empty($request->cijena)) {
            $query->where('cijena', '<=', $request->cijena);
        }

        $instrukcije = $query->latest()->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $instrukcije
        ]);
    }

    public function showAPI(Instrukcija $instrukcija)
    {
        return response()->json([
            'success' => true,
            'data' => $instrukcija
        ]);
    }

    public function storeAPI(Request $request)
    {
        $validated = $request->validate([
            'user_id'=>'required',
            'kategorija' => 'required',
            'vrsta' => 'required',
            'lokacija' => 'required',
            'naziv' => ['required', 'string'],
            'cijena' => ['required', 'numeric', 'min:5', 'max:999'],
            'opis' => 'required',
        ]);

        $instrukcija = Instrukcija::create([
            'user_id' => $validated['user_id'],
            'kategorija' => $validated['kategorija'],
            'vrsta' => $validated['vrsta'],
            'lokacija' => $validated['lokacija'],
            'naziv' => $validated['naziv'],
            'cijena' => $validated['cijena'],
            'opis' => $validated['opis'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Instrukcija je uspješno kreirana!',
            'data' => $instrukcija
        ], 201);
    }

    public function updateAPI(Request $request, Instrukcija $instrukcija)



    {

        try {
            $validated = $request->validate([
                'kategorija' => 'required',
                'vrsta' => 'required',
                'lokacija' => 'required',
                'naziv' => ['required', 'string'],
                'cijena' => ['required', 'numeric', 'min:5', 'max:999'],
                'opis' => 'required',
            ]);
        }
        catch (\Exception $e){
            return $e->getMessage();
        }




        $instrukcija->update([
            'kategorija' => $validated['kategorija'],
            'vrsta' => $validated['vrsta'],
            'lokacija' => $validated['lokacija'],
            'naziv' => $validated['naziv'],
            'cijena' => $validated['cijena'],
            'opis' => $validated['opis'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Instrukcija je uspješno ažurirana!',
            'data' => $instrukcija
        ]);
    }

    public function destroyAPI(Instrukcija $instrukcija)
    {
        $instrukcija->delete();

        return response()->json([
            'success' => true,
            'message' => 'Instrukcija je uspješno obrisana!'
        ]);
    }



}
