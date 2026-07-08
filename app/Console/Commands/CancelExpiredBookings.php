<?php
namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Command;

class CancelExpiredBookings extends Command
{
    protected $signature   = 'bookings:cancel-expired';
    protected $description = 'إلغاء الحجوزات المعلقة بعد 24 ساعة';

    public function handle()
    {
        $count = Booking::where('status', 'pending')
            ->where('expires_at', '<', now())
            ->update(['status' => 'cancelled']);

        $this->info("تم إلغاء {$count} حجز منتهي الصلاحية");
    }
}
