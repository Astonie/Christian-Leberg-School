<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\AcademicYear;

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
        $stream = $this->route('stream');
        $yearId = AcademicYear::active()->value('id') ?? $this->input('academic_year_id');

        return [
            // Allow partial updates (e.g., assigning a class teacher) so 'name' is only validated when present
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('streams')->where(function ($query) use ($yearId, $stream) {
                    $query->where('class_id', $this->input('class_id') ?? optional($stream)->class_id)
                          ->where('academic_year_id', $yearId);
                })->ignore($stream->id ?? null),
            ],
            'class_id' => ['sometimes', 'exists:classes,id'],
            'capacity' => ['nullable', 'integer', 'min:1'],
            'class_teacher_id' => ['nullable', 'exists:teachers,id'],
        ];
    }
}
