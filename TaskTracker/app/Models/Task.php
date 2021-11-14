<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'description',
        'completed',
        'assigned_user_id'
    ];

    public function scopeNoCompleted($q)
    {
        return $q->where('completed', 0);
    }
}
