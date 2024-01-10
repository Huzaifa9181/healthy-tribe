<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class motivation extends Model
{
    use HasFactory;

    public function getImageLinkAttribute()
    {
        return $this->image ? asset('public/' . $this->image) : null;
    }
}
