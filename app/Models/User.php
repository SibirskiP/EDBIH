<?php

namespace App\Models;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable,CanResetPassword;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
    public function instrukcije ()
    {
        return $this->hasMany(Instrukcija::class);

    }

    public function materijali()
    {
        return $this->hasMany(Materijal::class);
    }

    public function objave(){

        return $this->hasMany(Objava::class);
    }

    public function rooms()
    {
        // Dodajemo withPivot('last_read_at') da bismo imali pristup ovoj vrijednosti
        return $this->belongsToMany(Room::class)->withPivot('is_admin', 'last_read_at')->withTimestamps();
    }

    public function obavijesti()
    {
        return $this->hasMany(Obavijest::class, 'korisnik_id');
    }



}
