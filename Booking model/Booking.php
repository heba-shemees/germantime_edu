<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'reference',
        'name',
        'whatsapp',
        'level',
        'course_id',
        'notes',
        'status',          // pending | confirmed | cancelled
        'test_result_id',
    ];

    protected static function booted(): void
    {
        static::creating(function (Booking $booking) {
            $booking->reference = 'BK-' . strtoupper(Str::random(8));
        });
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function testResult()
    {
        return $this->belongsTo(TestResult::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }
}