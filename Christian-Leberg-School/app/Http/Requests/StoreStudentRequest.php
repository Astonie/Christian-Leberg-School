<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Use policy later
    }

    public function rules(): array
    {
        return [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'admission_number' => ['required', 'string', 'unique:students,admission_number'],
            'admission_date' => ['required', 'date'],
            'date_of_birth' => ['required', 'date'],
            'gender' => ['required', 'in:male,female,other'],
            'stream_id' => ['nullable', 'exists:streams,id'],
            'nationality' => ['nullable', 'string'],
            'address' => ['nullable', 'string'],
        ];
    }
}
