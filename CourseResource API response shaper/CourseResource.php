<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CourseResource extends JsonResource
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
        ];
    }
}
