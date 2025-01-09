<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Odgovor extends Model
{
    use HasFactory;

    protected $guarded=[];

    protected $table='odgovori';

    public function komentar(){
        return $this->belongsTo(Komentar::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
}
