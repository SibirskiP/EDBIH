<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class RegisterForm extends Component
{
    use WithFileUploads;

    public $username = '';
    public $email = '';
    public $titula = '';
    public $kontakt = '';
    public $opis = '';
    public $lokacija = '';
    public $password = '';
    public $password_confirmation = '';
    public $profilna_slika;

    public function rules()
    {
        return [
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'titula' => ['required', 'string', 'max:255'],
            'kontakt' => ['required', 'string', 'max:255'],
            'opis' => ['required', 'string', 'max:255'],
            'lokacija' => ['required'],
            'password' => ['required', Password::min(8)->mixedCase()->numbers(), 'confirmed'],
            'profilna_slika' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:1024'],
        ];
    }

    public function updated($propertyName)
    {
        // Validacija se pokreće u realnom vremenu
        $this->validateOnly($propertyName);
    }

    public function store()
    {
        $validatedData = $this->validate();

        if ($this->profilna_slika) {
            $validatedData['profilna_slika'] = $this->profilna_slika->store('profilne_slike', 'public');
        }

        $user = User::create($validatedData);

        Auth::login($user);

        return redirect('/');
    }

    public function render()
    {
        $lokacije = config('mojconfig.lokacije');
        return view('livewire.register-form', compact('lokacije'));
    }
}
