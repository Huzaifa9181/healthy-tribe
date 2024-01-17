<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class inner_reply extends Model
{
    use HasFactory;

    public function UnderInnerReply()
    {
        return $this->hasMany(inner_reply::class , 'reply_id');
    }
}
