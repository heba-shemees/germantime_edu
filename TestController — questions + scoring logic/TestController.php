<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\TestQuestion;
use App\Models\TestResult;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TestController extends Controller
{
    /**
     * Return all active test questions (without correct_answer).
     */
    public function questions(): JsonResponse
    {
        $questions = TestQuestion::ordered()->get();

        return response()->json([
            'data' => $questions->map(fn($q) => [
                'id'            => $q->id,
                'question_text' => $q->question_text,
                'options'       => $q->options,
                'order'         => $q->order,
            ])
        ]);
    }

    /**
     * Accept submitted answers, calculate score, return level + course.
     *
     * Scoring table:
     *   0–30  → A1
     *   31–50 → A2
     *   51–70 → B1
     *   71–100 → B2
     */
    public function submit(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'answers'    => ['required', 'array'],
            'answers.*'  => ['required', 'string', 'in:a,b,c,d'],
            'session_id' => ['nullable', 'string', 'max:100'],
        ]);

        $answers   = $validated['answers'];
        $questions = TestQuestion::whereIn('id', array_keys($answers))->get()->keyBy('id');

        // Calculate raw score
        $totalPoints = $questions->sum('points') ?: ($questions->count() * 10);
        $earned      = 0;

        foreach ($answers as $qId => $selected) {
            $question = $questions->get($qId);
            if ($question && $question->correct_answer === $selected) {
                $earned += $question->points ?? 10;
            }
        }

        // Normalise to 0–100
        $score = $totalPoints > 0
            ? (int) round(($earned / $totalPoints) * 100)
            : 0;

        $level = $this->scoreToLevel($score);

        // Find matching course
        $course = Course::active()->byLevel($level)->first();

        // Persist result
        $result = TestResult::create([
            'answers'    => $answers,
            'score'      => $score,
            'level'      => $level,
            'course_id'  => $course?->id,
            'session_id' => $validated['session_id'] ?? null,
        ]);

        return response()->json([
            'data' => [
                'id'        => $result->id,
                'score'     => $score,
                'level'     => $level,
                'course_id' => $course?->id,
                'course'    => $course ? [
                    'id'    => $course->id,
                    'name'  => $course->name,
                    'level' => $course->level,
                    'price' => $course->price,
                ] : null,
            ]
        ]);
    }

    private function scoreToLevel(int $score): string
    {
        return match(true) {
            $score <= 30 => 'A1',
            $score <= 50 => 'A2',
            $score <= 70 => 'B1',
            default      => 'B2',
        };
    }
}
