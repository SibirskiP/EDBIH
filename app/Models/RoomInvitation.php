<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Room;
use App\Models\User;

class RoomInvitation extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'room_id',
        'user_id',
        'status',
    ];

    /**
     * Get the room that the invitation belongs to.
     */
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    /**
     * Get the user that the invitation is for.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
