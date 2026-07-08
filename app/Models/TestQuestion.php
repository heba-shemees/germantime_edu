<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TestQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_text',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_option',
        'level',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * صيغة السؤال للمستخدم العادي (من غير الإجابة الصحيحة)
     */
    public function toPublicArray(): array
    {
        return [
            'id' => $this->id,
            'question_text' => $this->question_text,
            'options' => [
                'A' => $this->option_a,
                'B' => $this->option_b,
                'C' => $this->option_c,
                'D' => $this->option_d,
            ],
        ];
    }
}
