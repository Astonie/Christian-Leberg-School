<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeacherRequest extends FormRequest
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
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'employee_number' => ['required', 'string', 'unique:teachers,employee_number'],
            'hire_date' => ['required', 'date'],
            'qualification' => ['nullable', 'string'],
            'specialization' => ['nullable', 'string'],
            'phone_number' => ['required', 'string'],
            'address' => ['nullable', 'string'],
            'employment_type' => ['required', 'in:full-time,part-time,contract'],
        ];
    }
}
