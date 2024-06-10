<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CategoriesSubjectsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,        
            'class' => $this->id,
            'subject' => $this->subject->name,
            'number_of_students' => count($this->students),
            'teacher_name' => $this->teacher->user->name ?? NULL
        ]; 
    }
}
