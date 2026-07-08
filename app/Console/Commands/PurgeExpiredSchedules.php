<?php

namespace App\Console\Commands;

use App\Http\Controllers\ScheduleController;
use Illuminate\Console\Command;

class PurgeExpiredSchedules extends Command
{
    protected $signature   = 'schedules:purge-expired';
    protected $description = 'حذف المواعيد المكتملة بعد 12 ساعة والمحذوفة بعد ساعة';

    public function handle()
    {
        $count = app(ScheduleController::class)->purgeExpired();
        $this->info("تم حذف {$count} موعد");
    }
}
