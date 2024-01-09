<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    use HasFactory;
    
    public function leader_id()
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function members()
    {
        return $this->hasMany(GroupMember::class);
    }

    public function messages()
    {
        return $this->hasMany(GroupMessage::class);
    }

}
