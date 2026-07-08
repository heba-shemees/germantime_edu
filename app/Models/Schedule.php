<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'course_id', 'title', 'day_of_week',
        'start_time', 'end_time', 'start_date', 'end_date',
        'max_seats', 'booked_seats', 'is_active', 'notes'
    ];

    protected $casts = [
        'is_active'    => 'boolean',
        'start_date'   => 'date',
        'end_date'     => 'date',
        'max_seats'    => 'integer',
        'booked_seats' => 'integer',
        'deleted_at'   => 'datetime',
    ];

    protected $appends = ['available_seats', 'is_full'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function getAvailableSeatsAttribute(): int
    {
        return max(0, $this->max_seats - $this->booked_seats);
    }

    public function getIsFullAttribute(): bool
    {
        return $this->booked_seats >= $this->max_seats;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)->where('end_date', '>=', now());
    }
}
