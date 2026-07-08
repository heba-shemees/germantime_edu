<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'method',       // vodafone_cash | instapay
        'amount',
        'proof_url',
        'status',       // pending | verified | rejected
        'notes',
        'verified_at',
    ];

    protected $casts = [
        'amount'      => 'integer',
        'verified_at' => 'datetime',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
