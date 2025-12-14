<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSubjectRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255', 'unique:subjects,name,' . $this->subject->id],
            'code' => ['required', 'string', 'max:20', 'unique:subjects,code,' . $this->subject->id],
            'description' => ['nullable', 'string'],
        ];
    }
}
