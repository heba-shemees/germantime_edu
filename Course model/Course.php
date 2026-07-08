<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Casts\AsCollection;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'level',
        'description',
        'duration_weeks',
        'sessions_per_week',
        'session_duration_minutes',
        'price',
        'schedule',
        'learning_outcomes',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'learning_outcomes' => 'array',
        'schedule'          => 'array',
        'is_active'         => 'boolean',
        'price'             => 'integer',
    ];

    // Relations
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function testimonials()
    {
        return $this->hasMany(Testimonial::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)->orderBy('sort_order');
    }

    public function scopeByLevel($query, string $level)
    {
        return $query->where('level', $level);
    }

    // Helpers
    public static function levelOrder(): array
    {
        return ['A1' => 1, 'A2' => 2, 'B1' => 3, 'B2' => 4];
    }
}
