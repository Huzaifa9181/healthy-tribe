<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class addiction_management extends Model
{
    use HasFactory;

    public function option_id(){
        return $this->hasOne(addiction_type_option::class , 'id' , 'option_id');

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
