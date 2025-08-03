<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class LwInstruktorFilter extends Component
{
    use WithPagination;

    public $kategorije = [];
    public $lokacije = [];
    public $naziv = '';
    public $cijena; // Iako nije korišteno u Bladeu, ostavljeno je zbog kompatibilnosti

    // Čuva filtere u URL-u
    protected $updatesQueryString = ['kategorije', 'lokacije', 'naziv', 'cijena'];

    public function render()
    {
        $query = User::query();

        // Filtriranje po lokacijama
        if (!empty($this->lokacije)) {
            $query->whereIn('lokacija', $this->lokacije);
        }

        // Filtriranje po nazivu korisnika
        if (!empty($this->naziv)) {
            $query->where('username', 'like', '%' . $this->naziv . '%');
        }

        // Dohvaćanje instruktora s paginacijom
        $instruktori = $query->latest()->simplePaginate(8);

        // Dohvaćanje svih mogućih kategorija i lokacija iz konfiguracije
        $allKategorije = config('mojconfig.kategorije');
        $allLokacije = config('mojconfig.lokacije');

        return view('livewire.lw-instruktor-filter', [
            'instruktori' => $instruktori,
            'allKategorije' => $allKategorije, // Proslijeđujemo sve kategorije za filtere
            'allLokacije' => $allLokacije // Proslijeđujemo sve lokacije za filtere
        ]);
    }

    // Resetira sve filtere
    public function resetFiltersAll()
    {
        $this->lokacije = [];
        $this->naziv = '';
        $this->cijena = null; // Postavljamo na null, ako se ne koristi, bit će ignorirano
        $this->kategorije = [];
        $this->resetPage(); // Resetira paginaciju nakon resetiranja filtera
    }

    // Resetira samo filtere lokacije
    public function resetFiltersLokacije()
    {
        $this->lokacije = [];
        $this->resetPage(); // Resetira paginaciju
    }

    // Resetira paginaciju kada se promijene filteri
    public function updated($propertyName)
    {
        $this->resetPage();
    }
}
