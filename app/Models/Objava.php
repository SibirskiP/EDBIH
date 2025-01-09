<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Objava extends Model
{
    protected $guarded=[];

    protected $table='objave';
    use HasFactory;

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function komentari()
    {
        return $this->hasMany(Komentar::class);

    }
}
