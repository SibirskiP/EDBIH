<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Instrukcija;
class LwInstrukcijeFilter extends Component
{

    use WithPagination;

    public $kategorije = [];
    public $lokacije = [];
    public $naziv = '';
    public $cijena;

    protected $updatesQueryString = ['kategorije', 'lokacije', 'naziv', 'cijena']; // Čuva filtere u URL-u


    public function render()
    {
        $query = Instrukcija::query();

        if (!empty($this->kategorije)) {
            $query->whereIn('kategorija', $this->kategorije);
        }

        if (!empty($this->lokacije)) {
            $query->whereIn('lokacija', $this->lokacije);
        }

        if (!empty($this->naziv)) {
            $query->where('naziv', 'like', '%' . $this->naziv . '%');
        }

        if (!empty($this->cijena)) {
            $query->where('cijena', '<=', $this->cijena);
        }

        $instrukcije = $query->latest()->simplePaginate(6);

        return view('livewire.lw-instrukcije-filter', [
            'instrukcije' => $instrukcije
        ]);
    }

    public function azurirajFiltriranje()
    {
        $this->resetPage(); // Resetuje paginaciju kad se filteri promijene
    }
    public function resetFiltersKategorije()
    {
        $this->kategorije = []; // Resetira sve kategorije

    }

    public function resetFiltersLokacije()
    {
        $this->lokacije = []; // Resetira sve kategorije

    }

    public function resetFiltersAll()
    {
        $this->lokacije = []; // Resetira sve kategorije
        $this->naziv = '';
        $this->cijena = '';
        $this->kategorije = [];

    }
}
