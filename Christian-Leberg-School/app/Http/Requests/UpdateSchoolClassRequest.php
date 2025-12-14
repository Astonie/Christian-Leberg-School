<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateSchoolClassRequest extends FormRequest
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
        $classId = $this->route('class');
        // Handle if object is passed (binding) or ID
        if ($classId instanceof \App\Models\SchoolClass) {
            $classId = $classId->id;
        }
        
        return [
            'name' => ['required', 'string', 'max:255', 'unique:classes,name,' . $classId],
            'level' => ['required', 'integer', 'min:1'],
            'description' => ['nullable', 'string'],
        ];
    }
}
