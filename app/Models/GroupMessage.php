<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupMessage extends Model
{
    use HasFactory;

    public function user_id()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function group_id()
    {
        return $this->belongsTo(Group::class, 'group_id', 'id');
    }
}
