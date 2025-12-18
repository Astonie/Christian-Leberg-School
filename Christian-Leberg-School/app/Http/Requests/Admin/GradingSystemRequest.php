<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class GradingSystemRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()?->role?->slug === 'admin';
    }

    public function rules()
    {
        return [
            'name' => ['required','string','max:191'],
            'slug' => ['required','string','max:191','unique:grading_systems,slug'.($this->grading_system?" ,$this->grading_system->id":"")],
            'description' => ['nullable','string'],
        ];
    }
}
