<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class plan extends Model
{
    use HasFactory;

    public function videos()
    {
        return $this->hasMany(video::class , 'plan_id');
    }


    
    public function getImageLinkAttribute()
    {
        return $this->image ? asset('public/' . $this->image) : null;
    }
}
