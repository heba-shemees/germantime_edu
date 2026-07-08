<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TestResult extends Model
{
    protected $fillable = [
        'user_id',
        'level',
        'correct_count',
        'total',
        'percentage',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
