<?php

namespace App\Livewire;

use App\Models\Materijal;
use Livewire\Component;
use Livewire\WithPagination;




class LwMaterijaliFilter extends Component

{
    public $kategorije = [];
    public $lokacije = [];
    public $naziv = '';
    public $cijena;

    protected $updatesQueryString = ['kategorije', 'lokacije', 'naziv', 'cijena']; // Čuva filtere u URL-u


    use WithPagination;
    public function render()
    {

        $query=Materijal::query();
        if (!empty($this->kategorije)) {
            $query->whereIn('kategorija', $this->kategorije);
        }

        if (!empty($this->naziv)) {
            $query->where('naziv', 'like', '%' . $this->naziv . '%');
        }

        $materijali=$query->latest()->simplePaginate(6);

        return view('livewire.lw-materijali-filter',
        [
            'materijali'=>$materijali,
            'kategorije' => config( 'mojconfig.kategorije'), // Za prikaz filtera

        ]);
    }


    public function resetFiltersAll()
    {
        $this->lokacije = []; // Resetira sve lokacije
        $this->naziv = '';
        $this->cijena = '';
        $this->kategorije = [];

    }

    public function resetFiltersKategorije()
    {
        $this->kategorije = []; // Resetira sve lokacije

    }
}
