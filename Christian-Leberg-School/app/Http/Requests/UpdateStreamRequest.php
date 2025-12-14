<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStreamRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'class_teacher_id' => ['nullable', 'exists:teachers,id'],
        ];
    }
}
