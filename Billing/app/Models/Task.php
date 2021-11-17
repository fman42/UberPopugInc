<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    public function scopeToDay($q)
    {
        return $q->whereDate('created_at', '=', \Carbon\Carbon::now()->format('Y-m-d'));
    }

    public function scopeCompleted($q)
    {
        return $q->where('completed', 1);
    }

    public function scopeActive($q)
    {
        return $q->where('completed', 0);
    }
}
