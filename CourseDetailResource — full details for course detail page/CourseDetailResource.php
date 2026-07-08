<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseDetailResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id'                       => $this->id,
            'name'                     => $this->name,
            'level'                    => $this->level,
            'description'              => $this->description,
            'duration_weeks'           => $this->duration_weeks,
            'sessions_per_week'        => $this->sessions_per_week,
            'session_duration_minutes' => $this->session_duration_minutes,
            'price'                    => $this->price,
            'schedule'                 => $this->schedule,
            'learning_outcomes'        => $this->learning_outcomes ?? [],
            'testimonials'             => $this->whenLoaded('testimonials', fn() =>
                $this->testimonials->where('is_active', true)->map(fn($t) => [
                    'id'           => $t->id,
                    'student_name' => $t->student_name,
                    'body'         => $t->body,
                    'rating'       => $t->rating,
                    'photo_url'    => $t->photo_url,
                ])->values()
            ),
        ];
    }
}
