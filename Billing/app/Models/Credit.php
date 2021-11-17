<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Credit extends Model
{
    use HasFactory;

    public function scopeClosed($q)
    {
        return $q->where('closed', 1);
    }

    public function scopeActive($q)
    {
        return $q->where('closed', 0);
    }
}
