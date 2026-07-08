<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TestQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_text',
        'options',        // JSON: {"a": "...", "b": "...", "c": "...", "d": "..."}
        'correct_answer', // "a" | "b" | "c" | "d"
        'points',         // score weight (default 10)
        'order',
        'level_hint',     // which level this Q targets (A1/A2/B1/B2)
    ];

    protected $casts = [
        'options' => 'array',
        'points'  => 'integer',
        'order'   => 'integer',
    ];

    // Don't expose correct_answer to frontend
    protected $hidden = ['correct_answer', 'created_at', 'updated_at'];

    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }
}
