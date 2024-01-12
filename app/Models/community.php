<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class community extends Model
{
    use HasFactory;

    public function comment()
    {
        return $this->hasMany(comment::class , 'community_id');
    }

    public function user_id()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function addiction_id()
    {
        return $this->belongsTo(addiction::class, 'addiction_id');
    }

}
