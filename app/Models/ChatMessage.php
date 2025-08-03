<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatMessage extends Model
{
    use HasFactory;

    protected $fillable = ["sender_id", "receiver_id", "message",'file_name', 'file_name_original', 'file_path', 'file_type','file_size','room_id'];
    public function sender(){
        return $this->belongsTo(User::class, "sender_id");
    }
    public function receiver(){

        return $this->belongsTo(User::class, "receiver_id");
    }


    public function room(){

        return $this->belongsTo(Room::class);
    }

}
