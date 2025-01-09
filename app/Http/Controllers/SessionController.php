<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    //
    public function create(){
        return view('auth/login');
    }
    public function store(){

      //validate
       $att= request()->validate([
            'email' => 'required|email',
            'password' => 'required'

        ]);


       //attmpt to login the user
       if(! Auth::attempt($att) ){

           throw ValidationException::withMessages([
               'email' => ['The provided credentials are incorrect.'],

           ]);
       }

        // regenerate the session token
        request()->session()->regenerate();
        //redirect
        return redirect('/instrukcije');

    }
    public function destroy(){
        Auth::logout();
        return redirect('/');
    }


}
