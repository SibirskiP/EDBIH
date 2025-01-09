<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materijal extends Model
{
    use HasFactory;
    protected $guarded=[];

    protected $table='materijali';

    public function User(){
        return $this->belongsTo(User::class,'user_id');
    }
}
