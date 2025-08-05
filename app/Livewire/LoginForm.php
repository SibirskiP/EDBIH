<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class LoginForm extends Component
{
    public $email = '';
    public $password = '';

    public function rules()
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function login()
    {
        $this->validate();

        if (!Auth::attempt(['email' => $this->email, 'password' => $this->password])) {
            $this->addError('email', 'Navedeni podaci nisu ispravni.');
            return;
        }

        request()->session()->regenerate();

        return redirect('/instrukcije');
    }

    public function render()
    {
        return view('livewire.login-form');
    }
}
