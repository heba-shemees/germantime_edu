<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TestQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TestQuestionController extends Controller
{
    /**
     * عرض كل الأسئلة (بما فيها الإجابة الصحيحة - للأدمن بس)
     */
    public function index()
    {
        $questions = TestQuestion::orderBy('level')
            ->orderBy('sort_order')
            ->get();

        return response()->json(['data' => $questions]);
    }

    /**
     * إضافة سؤال جديد
     */
    public function store(Request $request)
    {
        $validated = $this->validateQuestion($request);

        $question = TestQuestion::create($validated);

        return response()->json([
            'message' => 'تم إضافة السؤال بنجاح',
            'data' => $question,
        ], 201);
    }

    /**
     * تعديل سؤال موجود
     */
    public function update(Request $request, TestQuestion $testQuestion)
    {
        $validated = $this->validateQuestion($request, $testQuestion->id);

        $testQuestion->update($validated);

        return response()->json([
            'message' => 'تم تعديل السؤال بنجاح',
            'data' => $testQuestion,
        ]);
    }

    /**
     * حذف سؤال
     */
    public function destroy(TestQuestion $testQuestion)
    {
        $testQuestion->delete();

        return response()->json([
            'message' => 'تم حذف السؤال بنجاح',
        ]);
    }

    private function validateQuestion(Request $request, ?int $ignoreId = null): array
    {
        $validator = Validator::make($request->all(), [
            'question_text'  => ['required', 'string'],
            'option_a'       => ['required', 'string', 'max:255'],
            'option_b'       => ['required', 'string', 'max:255'],
            'option_c'       => ['required', 'string', 'max:255'],
            'option_d'       => ['required', 'string', 'max:255'],
            'correct_option' => ['required', 'in:A,B,C,D'],
            'level'          => ['required', 'in:A1,A2,B1,B2'],
            'is_active'      => ['boolean'],
            'sort_order'     => ['integer'],
        ]);

        if ($validator->fails()) {
            abort(response()->json([
                'message' => 'خطأ في البيانات المدخلة',
                'errors' => $validator->errors(),
            ], 422));
        }

        return $validator->validated();
    }
}
