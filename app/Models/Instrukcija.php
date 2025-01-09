<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Instrukcija extends Model
{
    use HasFactory;
    protected $table = 'instrukcije';
    protected $guarded = [];


public function User ()

{
    return $this->belongsTo(User::class, 'user_id');


}

public function Tags(){

    return $this->belongsToMany(Tag::class);
}

}
