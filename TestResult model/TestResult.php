<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TestResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'answers',       // JSON: {questionId: selectedOption}
        'score',
        'level',         // A1 | A2 | B1 | B2
        'course_id',     // recommended course
        'session_id',    // anonymous tracking
    ];

    protected $casts = [
        'answers' => 'array',
        'score'   => 'integer',
    ];

    public function recommendedCourse()
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function booking()
    {
        return $this->hasOne(Booking::class);
    }
}
