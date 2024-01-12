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
}
