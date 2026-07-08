<?php
namespace App\Http\Controllers\Admin;

use App\Models\Booking;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class BookingAdminController extends Controller
{
    // قائمة الحجوزات
    public function index(Request $request)
    {
        $bookings = Booking::with('schedule')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->latest()
            ->get();

        return response()->json(['data' => $bookings]);
    }

    // موافقة على الحجز + إرسال واتساب
    public function confirm(Booking $booking)
    {
        if ($booking->status !== 'uploaded') {
            return response()->json(['message' => 'لازم يكون رفع الإيصال الأول'], 422);
        }

        $booking->update(['status' => 'confirmed']);

        // إرسال رسالة واتساب للطالب
        $this->sendWhatsApp($booking);

        return response()->json(['message' => 'تم تأكيد الحجز وإرسال الواتساب']);
    }

    // إلغاء يدوي
    public function cancel(Booking $booking)
    {
        $booking->update(['status' => 'cancelled']);
        return response()->json(['message' => 'تم إلغاء الحجز']);
    }

    private function sendWhatsApp(Booking $booking)
    {
        $schedule = $booking->schedule;
        $message  = "✅ مرحباً {$booking->name}!\n\n"
                  . "تم تأكيد حجزك في كورس الألماني 🎉\n\n"
                  . "📚 المستوى: {$booking->level}\n"
                  . ($schedule ? "📅 الموعد: {$schedule->title} — {$schedule->day_of_week} {$schedule->start_time}\n" : "")
                  . "🔖 رقم الحجز: {$booking->reference}\n\n"
                  . "سيتم إرسال لينك الكلاس قبل الموعد بـ 24 ساعة إن شاء الله 🙏";

        // استخدم WhatsApp Business API أو Twilio هنا
        // Http::post('YOUR_WHATSAPP_API', [
        //     'to'   => '2' . $booking->whatsapp,
        //     'body' => $message,
        // ]);

        // أو افتح الواتساب يدوياً من الـ admin
        $booking->update(['wa_message' => $message]);
    }
}
