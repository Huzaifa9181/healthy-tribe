<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class reply extends Model
{
    use HasFactory;

    public function user_id()
    {
        return $this->belongsTo(User::class, 'user_id');
    }


    public function InnerReply()
    {
        return $this->hasMany(inner_reply::class , 'reply_id');
    }
    
}
