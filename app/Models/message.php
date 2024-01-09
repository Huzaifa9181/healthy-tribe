<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class message extends Model
{
    use HasFactory;
    protected $fillable = ['user_id' , 'message' , 'another_user_id' , 'voice' , 'images' , 'chat_room_id'];
}
