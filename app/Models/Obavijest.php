<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Obavijest extends Model
{
    use HasFactory;
    protected $table = 'obavijesti';

    protected $fillable = [
        'korisnik_id',
        'naslov',
        'sadrzaj',
        'procitano',
    ];

    public function korisnik()
    {
        return $this->belongsTo(User::class, 'korisnik_id');
    }
}
