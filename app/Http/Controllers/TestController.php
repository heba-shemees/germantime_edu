<?php

namespace App\Http\Controllers;

use App\Models\TestQuestion;
use App\Models\TestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TestController extends Controller
{
    /**
     * عدد الأسئلة اللي هتتاخد من كل مستوى (يعني الإجمالي = 4 × العدد ده)
     */
    private const QUESTIONS_PER_LEVEL = 3;

    /**
     * جلب أسئلة عشوائية متوازنة على المستويات الأربعة (بدون الإجابة الصحيحة)
     */
    public function questions()
    {
        $levels = ['A1', 'A2', 'B1', 'B2'];
        $questions = collect();

        foreach ($levels as $level) {
            $levelQuestions = TestQuestion::where('is_active', true)
                ->where('level', $level)
                ->inRandomOrder()
                ->limit(self::QUESTIONS_PER_LEVEL)
                ->get();

            $questions = $questions->merge($levelQuestions);
        }

        // خلط الأسئلة عشان الترتيب ميبقاش حسب المستوى
        $questions = $questions->shuffle()->values();

        return response()->json([
            'data' => $questions->map->toPublicArray(),
        ]);
    }

    /**
     * استقبال إجابات اليوزر، وحساب النتيجة والمستوى بناءً على النسبة المئوية للصح
     */
    public function submit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'answers' => ['required', 'array', 'min:1'],
            'answers.*.question_id' => ['required', 'integer', 'exists:test_questions,id'],
            'answers.*.selected_option' => ['required', 'in:A,B,C,D'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'خطأ في البيانات المرسلة',
                'errors' => $validator->errors(),
            ], 422);
        }

        $answers = collect($request->answers);
        $questionIds = $answers->pluck('question_id');

        $questions = TestQuestion::whereIn('id', $questionIds)->get()->keyBy('id');

        $correctCount = 0;

        foreach ($answers as $answer) {
            $question = $questions->get($answer['question_id']);
            if ($question && $question->correct_option === $answer['selected_option']) {
                $correctCount++;
            }
        }

        $total = $answers->count();
        $percentage = $total > 0 ? round(($correctCount / $total) * 100) : 0;

        $level = $this->levelFromPercentage($percentage);

        // حفظ النتيجة (مربوطة باليوزر لو مسجل دخول، أو من غير user_id لو زائر)
        TestResult::create([
            'user_id' => auth('sanctum')->id(),
            'level' => $level,
            'correct_count' => $correctCount,
            'total' => $total,
            'percentage' => $percentage,
        ]);

        return response()->json([
            'correct_count' => $correctCount,
            'total' => $total,
            'percentage' => $percentage,
            'level' => $level,
        ]);
    }

    /**
     * تحويل النسبة المئوية للإجابات الصحيحة إلى مستوى (4 مستويات متساوية)
     */
    private function levelFromPercentage(float $percentage): string
    {
        return match (true) {
            $percentage <= 25 => 'A1',
            $percentage <= 50 => 'A2',
            $percentage <= 75 => 'B1',
            default => 'B2',
        };
    }
}
