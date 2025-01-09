<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class
Tag extends Model
{
    use HasFactory;
    protected $table = 'tagovi';
    protected $guarded=[];

    public function Instrukcije(){

        return $this->belongsToMany(Instrukcija::class);
    }

}
