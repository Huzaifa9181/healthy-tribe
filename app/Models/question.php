<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class question extends Model
{
    use HasFactory;

    public function addiction_id()
    {
        return $this->belongsTo(addiction::class, 'addiction_id', 'id');
    }
}
