<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TestimonialAdminController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::latest()->get();
        return response()->json(['data' => $testimonials]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'      => ['required', 'string', 'max:255'],
            'level'     => ['nullable', 'string', 'max:10'],
            'content'   => ['required', 'string'],
            'rating'    => ['required', 'integer', 'min:1', 'max:5'],
            'is_active' => ['boolean'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'خطأ في البيانات المدخلة',
                'errors'  => $validator->errors(),
            ], 422);
        }

        $testimonial = Testimonial::create($validator->validated());

        return response()->json([
            'message' => 'تم إضافة رأي الطالب بنجاح',
            'data' => $testimonial,
        ], 201);
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return response()->json(['message' => 'تم حذف رأي الطالب بنجاح']);
    }
}
