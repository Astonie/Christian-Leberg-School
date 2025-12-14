<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeacherRequest extends FormRequest
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
        $teacherId = $this->route('teacher');
        $userId = null;
        
        if ($teacherId instanceof \App\Models\Teacher) {
            $userId = $teacherId->user_id;
            $teacherId = $teacherId->id;
        } elseif (is_numeric($teacherId)) {
            // Fallback if not bound, fetch manually to get user_id
            $teacher = \App\Models\Teacher::find($teacherId);
             if ($teacher) {
                $userId = $teacher->user_id;
            }
        }

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', \Illuminate\Validation\Rule::unique('users', 'email')->ignore($userId)],
            'employee_number' => ['required', 'string', \Illuminate\Validation\Rule::unique('teachers', 'employee_number')->ignore($teacherId)],
            'hire_date' => ['required', 'date'],
            'qualification' => ['nullable', 'string'],
            'specialization' => ['nullable', 'string'],
            'phone_number' => ['required', 'string'],
            'address' => ['nullable', 'string'],
            'employment_type' => ['required', 'in:full-time,part-time,contract'],
        ];
    }
}
