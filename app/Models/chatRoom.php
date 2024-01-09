<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class chatRoom extends Model
{
    use HasFactory;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function anotherUser()
    {
        return $this->belongsTo(User::class, 'another_user_id', 'id');
    }
}
