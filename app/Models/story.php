<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class story extends Model
{
    use HasFactory;

    public function getPathAttribute()
    {
        // return $this->path ? asset('public/' . $this->path) : null;
    }
    
}
