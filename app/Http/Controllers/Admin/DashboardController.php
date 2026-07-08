<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    /**
     * قائمة كل المستخدمين (غير الأدمن) مع دعم البحث بالاسم/الإيميل/الهاتف
     */
    public function users(Request $request)
    {
        $search = $request->query('search');

        $users = User::where('is_admin', false)
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            })
            ->with([
                'bookings' => fn($q) => $q->latest(),
                'bookings.schedule.course',
                'testResults' => fn($q) => $q->latest(),
            ])
            ->get()
            ->map(function (User $user) {
                $latestBooking = $user->bookings->first();
                $latestTest = $user->testResults->first();
                $courses = $user->bookings
                    ->pluck('schedule.course')
                    ->filter()
                    ->unique('id')
                    ->values()
                    ->map(fn($course) => [
                        'id' => $course->id,
                        'name' => $course->name,
                        'level' => $course->level,
                    ]);

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    // لو محدد phone على اليوزر نفسه استخدمه، وإلا خد آخر رقم من الحجوزات
                    'phone' => $user->phone ?? ($latestBooking->whatsapp ?? null),
                    'courses' => $courses,
                    'bookings' => $user->bookings->map(fn($b) => [
                        'id' => $b->id,
                        'reference' => $b->reference,
                        'status' => $b->status,
                        'level' => $b->level,
                        'schedule' => $b->schedule ? [
                            'title' => $b->schedule->title,
                            'day_of_week' => $b->schedule->day_of_week,
                            'start_date' => $b->schedule->start_date,
                        ] : null,
                        'created_at' => $b->created_at,
                    ]),
                    'test_result' => $latestTest ? [
                        'level' => $latestTest->level,
                        'percentage' => $latestTest->percentage,
                        'correct_count' => $latestTest->correct_count,
                        'total' => $latestTest->total,
                        'date' => $latestTest->created_at,
                    ] : null,
                ];
            });

        return response()->json(['data' => $users]);
    }

    /**
     * تعديل بيانات طالب (الاسم، الإيميل، رقم الهاتف)
     */
    public function update(Request $request, User $user)
    {
        if ($user->is_admin) {
            return response()->json(['message' => 'غير مسموح بتعديل حساب أدمن من هنا'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name'  => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone' => ['nullable', 'string', 'max:20'],
        ], [
            'email.unique' => 'هذا البريد الإلكتروني مستخدم بالفعل لحساب آخر.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'خطأ في البيانات المدخلة',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $user->update($validator->validated());

        return response()->json([
            'message' => 'تم تحديث بيانات الطالب بنجاح',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
            ],
        ]);
    }

    /**
     * حذف حساب طالب نهائيًا (مع كل الحجوزات ونتائج الاختبار المرتبطة به)
     */
    public function destroy(User $user)
    {
        if ($user->is_admin) {
            return response()->json(['message' => 'غير مسموح بحذف حساب أدمن'], 403);
        }

        $user->testResults()->delete();
        $user->bookings()->delete();
        $user->delete();

        return response()->json(['message' => 'تم حذف حساب الطالب نهائيًا']);
    }
}
