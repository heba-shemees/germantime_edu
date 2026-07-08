<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\TestQuestion;
use App\Models\Testimonial;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── COURSES ─────────────────────────────────────────────
        $courses = [
            [
                'name'                     => 'Deutsch A1 – المبتدئ',
                'level'                    => 'A1',
                'description'              => 'كورس A1 مصمم للي بتبدأ من الصفر. هتتعلمي الحروف، أرقام، تعارف، أوامر يومية بسيطة.',
                'duration_weeks'           => 8,
                'sessions_per_week'        => 2,
                'session_duration_minutes' => 60,
                'price'                    => 800,
                'learning_outcomes'        => [
                    'التعارف وتقديم النفس',
                    'الأرقام والتواريخ',
                    'الأوامر والجمل البسيطة',
                    'الألوان والأشياء اليومية',
                    'الضمائر والأفعال الأساسية',
                ],
                'schedule' => [
                    ['day' => 'السبت', 'time' => '7:00 م'],
                    ['day' => 'الثلاثاء', 'time' => '7:00 م'],
                ],
                'is_active'  => true,
                'sort_order' => 1,
            ],
            [
                'name'                     => 'Deutsch A2 – الأساسي',
                'level'                    => 'A2',
                'description'              => 'للي عندها أساس A1 وعايزة تتوسع في المحادثة اليومية والمفردات.',
                'duration_weeks'           => 8,
                'sessions_per_week'        => 2,
                'session_duration_minutes' => 60,
                'price'                    => 900,
                'learning_outcomes'        => [
                    'وصف الأماكن والأشخاص',
                    'التسوق والمواصلات',
                    'قواعد الفعل المنتظم وغير المنتظم',
                    'الحديث عن الماضي (Perfekt)',
                    'فهم نصوص قصيرة',
                ],
                'schedule' => [
                    ['day' => 'الأحد', 'time' => '8:00 م'],
                    ['day' => 'الأربعاء', 'time' => '8:00 م'],
                ],
                'is_active'  => true,
                'sort_order' => 2,
            ],
            [
                'name'                     => 'Deutsch B1 – المتوسط',
                'level'                    => 'B1',
                'description'              => 'قواعد متقدمة، فهم نصوص أطول، والتعبير عن الرأي بشكل صحيح.',
                'duration_weeks'           => 10,
                'sessions_per_week'        => 2,
                'session_duration_minutes' => 75,
                'price'                    => 1100,
                'learning_outcomes'        => [
                    'الجمل الفرعية المعقدة (Nebensätze)',
                    'التصريف بالمضارع والماضي والمستقبل',
                    'كتابة رسائل ورسمية',
                    'فهم نصوص إخبارية',
                    'التعبير عن الرأي والمقارنة',
                ],
                'schedule' => [
                    ['day' => 'الاثنين', 'time' => '7:30 م'],
                    ['day' => 'الخميس', 'time' => '7:30 م'],
                ],
                'is_active'  => true,
                'sort_order' => 3,
            ],
            [
                'name'                     => 'Deutsch B2 – المتقدم',
                'level'                    => 'B2',
                'description'              => 'الوصول لمستوى الطلاقة — مثالي لمن يريد العمل أو الدراسة في ألمانيا.',
                'duration_weeks'           => 12,
                'sessions_per_week'        => 2,
                'session_duration_minutes' => 90,
                'price'                    => 1300,
                'learning_outcomes'        => [
                    'الكتابة الأكاديمية والمهنية',
                    'فهم المحاضرات والبرامج الوثائقية',
                    'المحادثة بطلاقة حول مواضيع معقدة',
                    'التحضير لاختبار Goethe B2',
                    'قواعد لغوية متقدمة',
                ],
                'schedule' => [
                    ['day' => 'الثلاثاء', 'time' => '9:00 م'],
                    ['day' => 'الجمعة', 'time' => '9:00 م'],
                ],
                'is_active'  => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($courses as $data) {
            Course::create($data);
        }

        // ── TEST QUESTIONS ───────────────────────────────────────
        $questions = [
            // A1 Questions
            [
                'question_text' => 'Wie heißt du? — ما معنى هذه الجملة؟',
                'options'       => ['a' => 'كيف حالك؟', 'b' => 'ما اسمك؟', 'c' => 'من أين أنتِ؟', 'd' => 'كم عمرك؟'],
                'correct_answer' => 'b', 'points' => 10, 'order' => 1, 'level_hint' => 'A1'
            ],
            [
                'question_text' => 'أكملي الجملة: Ich ___ aus Ägypten.',
                'options'       => ['a' => 'bin', 'b' => 'bist', 'c' => 'komme', 'd' => 'heißt'],
                'correct_answer' => 'c', 'points' => 10, 'order' => 2, 'level_hint' => 'A1'
            ],
            [
                'question_text' => 'ما معنى "Guten Morgen"؟',
                'options'       => ['a' => 'مساء الخير', 'b' => 'صباح الخير', 'c' => 'إلى اللقاء', 'd' => 'شكراً'],
                'correct_answer' => 'b', 'points' => 10, 'order' => 3, 'level_hint' => 'A1'
            ],
            [
                'question_text' => 'أي رقم يمثل "zwanzig" بالألماني؟',
                'options'       => ['a' => '12', 'b' => '20', 'c' => '22', 'd' => '200'],
                'correct_answer' => 'b', 'points' => 10, 'order' => 4, 'level_hint' => 'A1'
            ],
            // A2 Questions
            [
                'question_text' => 'اختاري الصيغة الصحيحة: Ich ___ gestern ins Kino gegangen.',
                'options'       => ['a' => 'habe', 'b' => 'hatte', 'c' => 'bin', 'd' => 'war'],
                'correct_answer' => 'c', 'points' => 10, 'order' => 5, 'level_hint' => 'A2'
            ],
            [
                'question_text' => 'ما المعنى الصحيح لـ "Wo wohnst du?"',
                'options'       => ['a' => 'ما مهنتك؟', 'b' => 'أين تسكنين؟', 'c' => 'كيف تذهبين؟', 'd' => 'متى تعودين؟'],
                'correct_answer' => 'b', 'points' => 10, 'order' => 6, 'level_hint' => 'A2'
            ],
            [
                'question_text' => 'أكملي: Das ist ___ Mann. (رجل محدد)',
                'options'       => ['a' => 'ein', 'b' => 'eine', 'c' => 'der', 'd' => 'die'],
                'correct_answer' => 'c', 'points' => 10, 'order' => 7, 'level_hint' => 'A2'
            ],
            // B1 Questions
            [
                'question_text' => 'اختاري الصيغة الصحيحة: Obwohl es regnet, ___ ich spazieren.',
                'options'       => ['a' => 'gehe', 'b' => 'gehst', 'c' => 'geht', 'd' => 'gehen'],
                'correct_answer' => 'a', 'points' => 10, 'order' => 8, 'level_hint' => 'B1'
            ],
            [
                'question_text' => 'ما معنى "Konjunktiv II" في ألماني؟',
                'options'       => [
                    'a' => 'صيغة الأمر',
                    'b' => 'الماضي البسيط',
                    'c' => 'صيغة الاحتمال/المجاملة',
                    'd' => 'المبني للمجهول'
                ],
                'correct_answer' => 'c', 'points' => 10, 'order' => 9, 'level_hint' => 'B1'
            ],
            // B2 Question
            [
                'question_text' => 'أكملي: Das Buch, ___ ich lese, ist sehr interessant.',
                'options'       => ['a' => 'der', 'b' => 'das', 'c' => 'dem', 'd' => 'dessen'],
                'correct_answer' => 'b', 'points' => 10, 'order' => 10, 'level_hint' => 'B2'
            ],
        ];

        foreach ($questions as $q) {
            TestQuestion::create($q);
        }

        // ── TESTIMONIALS ─────────────────────────────────────────
        $a1Id = Course::byLevel('A1')->first()?->id;
        $a2Id = Course::byLevel('A2')->first()?->id;
        $b1Id = Course::byLevel('B1')->first()?->id;

        $testimonials = [
            ['course_id' => $a1Id, 'student_name' => 'سارة أحمد', 'body' => 'بدأت من الصفر وبعد 8 أسابيع بقيت أقدر أتكلم جمل كاملة. الأسلوب بسيط ومرتب جداً.', 'rating' => 5],
            ['course_id' => $a2Id, 'student_name' => 'مريم خالد', 'body' => 'كورس A2 غير نظرتي للغة الألمانية. نور بتشرح القواعد بطريقة ما توقعتيش إنها تبقى سهلة.', 'rating' => 5],
            ['course_id' => $b1Id, 'student_name' => 'دينا يوسف', 'body' => 'وصلت B1 وبقيت قادرة أفهم الفيديوهات الألمانية بدون ترجمة. شكراً جداً!', 'rating' => 5],
        ];

        foreach ($testimonials as $t) {
            if ($t['course_id']) Testimonial::create($t + ['is_active' => true]);
        }
    }
}
