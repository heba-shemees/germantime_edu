<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name'        => 'required|string',
            'whatsapp'    => 'required|string',
            'level'       => 'required|string',
            'schedule_id' => 'nullable|exists:schedules,id',
            'notes'       => 'nullable|string',
        ]);

        $booking = Booking::create([
            // لو اليوزر مسجل دخول (بعت Bearer token صحيح) هيترّبط الحجز بحسابه تلقائيًا
            'user_id'     => auth('sanctum')->id(),
            'reference'   => 'BK-' . strtoupper(substr(uniqid(), -8)),
            'name'        => $request->name,
            'whatsapp'    => $request->whatsapp,
            'level'       => $request->level,
            'schedule_id' => $request->schedule_id,
            'notes'       => $request->notes,
            'status'      => 'pending',
            'expires_at'  => now()->addHours(24),
        ]);

        return response()->json([
            'data' => [
                'id'        => $booking->id,
                'reference' => $booking->reference,
            ]
        ]);
    }

    public function uploadReceipt(Request $request, Booking $booking)
    {
        $request->validate([
            'receipt' => 'required|image|max:5120',
        ]);

        if ($booking->status !== 'pending') {
            return response()->json(['message' => 'الحجز مش في حالة انتظار'], 422);
        }

        $path = $request->file('receipt')->store('receipts', 'public');

        $booking->update([
            'receipt_path' => $path,
            'status'       => 'uploaded',
        ]);

        return response()->json(['message' => 'تم رفع الإيصال بنجاح']);
    }

    public function show($reference)
    {
        $booking = Booking::with('schedule')
            ->where('reference', $reference)
            ->firstOrFail();

        return response()->json(['data' => $booking]);
    }

    public function cancel($reference)
    {
        $booking = Booking::where('reference', $reference)->firstOrFail();

        if (!in_array($booking->status, ['pending', 'uploaded'])) {
            return response()->json(['message' => 'مش ممكن تلغي الحجز ده'], 422);
        }

        $booking->update(['status' => 'cancelled']);

        return response()->json(['message' => 'تم إلغاء الحجز']);
    }

    public function adminIndex(Request $request)
    {
        $bookings = Booking::with('schedule')
            ->when($request->status, fn($q) => $q->where('status', $request->status))
            ->when($request->level,  fn($q) => $q->where('level',  $request->level))
            ->latest()
            ->get();

        return response()->json(['data' => $bookings]);
    }

    public function confirm($id)
    {
        $booking = Booking::with('schedule')->findOrFail($id);

        if ($booking->status !== 'uploaded') {
            return response()->json(['message' => 'لازم يكون رفع الإيصال الأول'], 422);
        }

        $booking->update(['status' => 'confirmed']);

        // ── نقص كرسي من الموعد بعد الموافقة ──
        if ($booking->schedule_id) {
            $schedule = \App\Models\Schedule::find($booking->schedule_id);
            if ($schedule && $schedule->booked_seats < $schedule->max_seats) {
                $schedule->increment('booked_seats');
            }
        }

        return response()->json(['message' => 'تم تأكيد الحجز']);
    }

    public function adminCancel($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'cancelled']);

        return response()->json(['message' => 'تم إلغاء الحجز']);
    }

    public function approveReceipt($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->update(['status' => 'confirmed']);

        return response()->json(['message' => 'تم تأكيد الإيصال']);
    }

    public function restore($id)
    {
        $booking = Booking::findOrFail($id);

        if ($booking->status !== 'cancelled') {
            return response()->json(['message' => 'الحجز مش ملغي'], 422);
        }

        $status = $booking->receipt_path ? 'uploaded' : 'pending';

        $booking->update([
            'status'     => $status,
            'expires_at' => now()->addHours(24),
        ]);

        return response()->json(['message' => 'تم استعادة الحجز']);
    }
}
