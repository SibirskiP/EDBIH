<?php

namespace App\Http\Controllers;

use App\Models\Ucenik;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use function Illuminate\Events\queueable;

class RegisteredUserController extends Controller
{
    //

    public function create(){
        return view('auth/register');
    }
    public function store()
    {

            $att = request()->validate([

                'username' => ['required', 'string', 'max:255', 'unique:users'],
                'profilna_slika' => ['image', 'mimes:jpeg,png,jpg', 'max:1024'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'titula' => ['required', 'string', 'max:255'],
                'kontakt' => ['required', 'string', 'max:255'],
                'opis' => ['required', 'string', 'max:255'],
                'lokacija' => ['required'],
                'password' => ['required', Password::min(8)->mixedCase()->numbers(), 'confirmed'],
            ]);
            if (request()->hasFile('profilna_slika')) {
                $att['profilna_slika'] = request()->file('profilna_slika')->store('profilne_slike', 'public');
            }

            $user=User::create($att);
//            event(new Registered($user));
            Auth::login($user);

            return redirect('/');


    }
}
