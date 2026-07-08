<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
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
        'is_active'         => 'boolean',
        'schedule'          => 'array',
        'learning_outcomes' => 'array',
    ];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
