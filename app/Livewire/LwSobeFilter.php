<?php

namespace App\Livewire;

use App\Models\Room;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class LwSobeFilter extends Component
{
    use WithPagination;

    public $naziv = '';
    public $kategorije = [];
    public $successMessage = '';
    public $failureMessage = '';

    public function updated($propertyName)
    {
        if ($propertyName === 'naziv' || $propertyName === 'kategorije') {
            $this->resetPage();
        }
    }

    public function resetFiltersKategorije()
    {
        $this->kategorije = [];
    }

    public function resetFiltersAll()
    {
        $this->naziv = '';
        $this->kategorije = [];
    }

    /**
     * Metoda za pridruživanje trenutno autentificiranog korisnika u sobu.
     *
     * @param int $roomId
     */
    public function joinRoom($roomId)
    {
        // Resetirajte poruke prije svake akcije
        $this->successMessage = '';
        $this->failureMessage = '';

        $user = Auth::user();
        $room = Room::find($roomId);

        if (!$user || !$room) {
            $this->failureMessage = 'Došlo je do greške. Pokušajte ponovno.';
            return;
        }

        if ($room->users()->where('user_id', $user->id)->exists()) {
            $this->successMessage = 'Već ste član ove sobe.';
        } else {
            $room->users()->attach($user->id, ['is_admin' => false]);
            $this->successMessage = 'Uspješno ste se pridružili sobi "' . $room->name . '".';
        }

        // Napomena: Ne vraćate redirect() tako da se stranica ne preusmjerava.
        // Livewire će automatski ponovo renderirati komponentu s novim porukama.
    }

    public function render()
    {
        $query = Room::query();

        if (!empty($this->kategorije)) {
            $query->whereIn('kategorija', $this->kategorije);
        }

        if (!empty($this->naziv)) {
            $query->where('name', 'like', '%' . $this->naziv . '%');
        }

        $sobe = $query->withCount('users')->latest()->simplePaginate(6);

        return view('livewire.lw-sobe-filter', compact('sobe'));
    }
}
