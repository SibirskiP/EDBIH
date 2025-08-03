<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = ['name','created_by','is_admin','opis','kategorija','privatnost','profilna_slika'];

    public function users(){

        return $this->belongsToMany(User::class)->withPivot('is_admin');
    }

    public function messages(){

        return $this->hasMany(ChatMessage::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected function membersCount(): Attribute
    {
        return new Attribute(
            get: fn () => $this->users_count ?? 0,
        );
    }


}
