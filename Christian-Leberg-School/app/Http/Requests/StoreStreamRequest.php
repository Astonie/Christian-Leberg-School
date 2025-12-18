<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\AcademicYear;

class StoreStreamRequest extends FormRequest
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
        $yearId = AcademicYear::active()->value('id') ?? $this->input('academic_year_id');

        // For bulk creation
        if ($this->has('bulk_names')) {
            return [
                'bulk_names' => ['required', 'string', 'max:500'],
                'bulk_capacity' => ['nullable', 'integer', 'min:1'],
                'class_id' => ['required', 'exists:classes,id'],
            ];
        }

        // For single stream creation
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('streams')->where(function ($query) use ($yearId) {
                    return $query->where('class_id', $this->input('class_id'))->where('academic_year_id', $yearId);
                }),
            ],
            'class_id' => ['required', 'exists:classes,id'],
            'capacity' => ['nullable', 'integer', 'min:1'],
        ];
    }
}
