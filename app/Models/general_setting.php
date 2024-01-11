<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class general_setting extends Model
{
    use HasFactory;

    public function getImageLinkAttribute()
    {
        return $this->logo ? asset('public/' . $this->logo) : null;
    }
}
