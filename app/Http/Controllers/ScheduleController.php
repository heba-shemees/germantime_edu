<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ScheduleController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Schedule::active()->with('course');

        if ($request->course_id) {
            $query->where('course_id', $request->course_id);
        }

        if ($request->level) {
            $query->whereHas('course', fn($q) => $q->where('level', $request->level));
        }

        $schedules = $query->orderBy('day_of_week')->orderBy('start_time')->get();

        return response()->json([
            'data' => $schedules->map(fn($s) => [
                'id'              => $s->id,
                'title'           => $s->title,
                'course'          => ['id' => $s->course->id, 'level' => $s->course->level, 'name' => $s->course->name],
                'day_of_week'     => $s->day_of_week,
                'start_time'      => $s->start_time,
                'end_time'        => $s->end_time,
                'start_date'      => $s->start_date->format('Y-m-d'),
                'end_date'        => $s->end_date->format('Y-m-d'),
                'max_seats'       => $s->max_seats,
                'available_seats' => $s->available_seats,
                'is_full'         => $s->is_full,
                'notes'           => $s->notes,
            ])
        ]);
    }

    public function adminIndex(): JsonResponse
    {
        $schedules = Schedule::with('course')
            ->withCount(['bookings' => fn($q) => $q->where('status', 'confirmed')])
            ->orderByDesc('created_at')
            ->get();

        return response()->json(['data' => $schedules]);
    }

    public function trashedIndex(): JsonResponse
    {
        $schedules = Schedule::onlyTrashed()
            ->with('course')
            ->where('deleted_at', '>=', now()->subHour())
            ->orderByDesc('deleted_at')
            ->get();

        return response()->json([
            'data' => $schedules->map(fn($s) => [
                'id'                => $s->id,
                'title'             => $s->title,
                'course'            => $s->course,
                'day_of_week'       => $s->day_of_week,
                'start_time'        => $s->start_time,
                'end_time'          => $s->end_time,
                'start_date'        => $s->start_date->format('Y-m-d'),
                'end_date'          => $s->end_date->format('Y-m-d'),
                'max_seats'         => $s->max_seats,
                'deleted_at'        => $s->deleted_at,
                'seconds_remaining' => max(0, 3600 - now()->diffInSeconds($s->deleted_at)),
            ])
        ]);
    }

    public function show($id): JsonResponse
    {
        $schedule = Schedule::active()->with('course')->findOrFail($id);
        return response()->json(['data' => $schedule]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'course_id'   => ['required', 'exists:courses,id'],
            'title'       => ['required', 'string', 'max:100'],
            'day_of_week' => ['required', 'in:Saturday,Sunday,Monday,Tuesday,Wednesday,Thursday,Friday'],
            'start_time'  => ['required', 'date_format:H:i'],
            'end_time'    => ['required', 'date_format:H:i'],
            'start_date'  => ['required', 'date'],
            'end_date'    => ['required', 'date'],
            'max_seats'   => ['required', 'integer', 'min:1', 'max:50'],
            'notes'       => ['nullable', 'string', 'max:500'],
        ]);

        $schedule = Schedule::create($validated);
        return response()->json(['data' => $schedule], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $schedule = Schedule::findOrFail($id);

        $validated = $request->validate([
            'title'       => ['sometimes', 'string', 'max:100'],
            'day_of_week' => ['sometimes', 'in:Saturday,Sunday,Monday,Tuesday,Wednesday,Thursday,Friday'],
            'start_time'  => ['sometimes', 'date_format:H:i'],
            'end_time'    => ['sometimes', 'date_format:H:i'],
            'start_date'  => ['sometimes', 'date'],
            'end_date'    => ['sometimes', 'date'],
            'max_seats'   => ['sometimes', 'integer', 'min:' . $schedule->booked_seats],
            'is_active'   => ['sometimes', 'boolean'],
            'notes'       => ['nullable', 'string'],
        ]);

        $schedule->update($validated);
        return response()->json(['data' => $schedule]);
    }

    public function destroy($id): JsonResponse
    {
        $schedule = Schedule::findOrFail($id);

        if ($schedule->booked_seats > 0) {
            return response()->json([
                'message' => 'لا يمكن حذف موعد فيه حجوزات مؤكدة'
            ], 422);
        }

        $schedule->delete();
        return response()->json(['message' => 'تم الحذف — يمكنك استعادته خلال ساعة']);
    }

    public function restore($id): JsonResponse
    {
        $schedule = Schedule::onlyTrashed()->findOrFail($id);

        if ($schedule->deleted_at->lt(now()->subHour())) {
            return response()->json([
                'message' => 'انتهت مدة استعادة هذا الموعد'
            ], 422);
        }

        $schedule->restore();
        return response()->json(['message' => 'تم استعادة الموعد', 'data' => $schedule]);
    }

    public function purgeExpired(): int
    {
        $count = 0;

        // حذف المواعيد المحذوفة (soft deleted) بعد ساعة
        $expiredDeleted = Schedule::onlyTrashed()
            ->where('deleted_at', '<', now()->subHour())
            ->get();

        foreach ($expiredDeleted as $schedule) {
            $schedule->forceDelete();
            $count++;
        }

        // حذف المواعيد المكتملة بعد 12 ساعة من اكتمالها
        $fullSchedules = Schedule::where('booked_seats', '>=', \DB::raw('max_seats'))
            ->where('updated_at', '<', now()->subHours(12))
            ->get();

        foreach ($fullSchedules as $schedule) {
            $schedule->forceDelete();
            $count++;
        }

        return $count;
    }
}
