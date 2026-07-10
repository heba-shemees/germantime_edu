<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseAdminController extends Controller
{
    public function index()
    {
        $courses = Course::orderBy('sort_order')->orderBy('name')->get();
        return response()->json(['data' => $courses]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            return response()->json([
                'message' => 'خطأ في البيانات المدخلة',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $course = Course::create($validator->validated());

        return response()->json([
            'message' => 'تم إضافة الكورس بنجاح',
            'data' => $course,
        ], 201);
    }

    public function update(Request $request, Course $course)
    {
        $validator = Validator::make($request->all(), $this->rules());

        if ($validator->fails()) {
            return response()->json([
                'message' => 'خطأ في البيانات المدخلة',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $course->update($validator->validated());

        return response()->json([
            'message' => 'تم تحديث الكورس بنجاح',
            'data' => $course,
        ]);
    }

    public function destroy(Course $course)
    {
        $course->delete();

        return response()->json(['message' => 'تم حذف الكورس بنجاح']);
    }

    private function rules(): array
    {
        return [
            'name'                     => ['required', 'string', 'max:255'],
            'level'                    => ['required', 'string', 'max:10'],
            'description'              => ['nullable', 'string'],
            'duration_weeks'           => ['nullable', 'integer', 'min:1'],
            'sessions_per_week'        => ['nullable', 'integer', 'min:1'],
            'session_duration_minutes' => ['nullable', 'integer', 'min:1'],
            'price'                    => ['nullable', 'numeric', 'min:0'],
            'is_active'                => ['boolean'],
            'sort_order'               => ['nullable', 'integer'],
        ];
    }
}
