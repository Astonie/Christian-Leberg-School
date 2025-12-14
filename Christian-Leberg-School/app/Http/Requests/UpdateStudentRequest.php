<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
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
        $student = $this->route('student');
        if (! $student instanceof \App\Models\Student) {
            $student = \App\Models\Student::find($student);
        }

        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', \Illuminate\Validation\Rule::unique('users', 'email')->ignore($student->user_id)],
            'admission_number' => ['required', 'string', \Illuminate\Validation\Rule::unique('students', 'admission_number')->ignore($student->id)],
            'admission_date' => ['required', 'date'],
            'date_of_birth' => ['required', 'date'],
            'gender' => ['required', 'in:male,female,other'],
            'stream_id' => ['nullable', 'exists:streams,id'],
            'nationality' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
        ];
    }
}
