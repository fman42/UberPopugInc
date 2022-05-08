<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Audit extends Model
{
    use HasFactory;

    public function scopeToDay($q)
    {
        return $q->whereDate('created_at', '=', \Carbon\Carbon::now()->format('Y-m-d'));
    }
}
