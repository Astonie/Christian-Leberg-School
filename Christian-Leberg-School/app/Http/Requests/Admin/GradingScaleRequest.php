<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Schema;

class GradingScaleRequest extends FormRequest
{
    public function authorize()
    {
        return $this->user()?->role?->slug === 'admin';
    }

    public function rules()
    {
        $rules = [
            'label' => ['required','string'],
            'remark' => ['nullable','string'],
        ];

        // if old schema (accept old column names OR new ones)
        if (Schema::hasColumn('grading_scales', 'min_percentage')) {
            $rules['min_percentage'] = ['required_without:min_score','numeric','min:0','max:100'];
            $rules['max_percentage'] = ['required_without:max_score','numeric','min:0','max:100'];
        }

        // if new schema (accept new column names OR old ones)
        if (Schema::hasColumn('grading_scales', 'grading_system_id')) {
            $rules['grading_system_id'] = ['required','exists:grading_systems,id'];
            $rules['code'] = ['nullable','string'];
            $rules['min_score'] = ['required_without:min_percentage','numeric','min:0','max:100'];
            $rules['max_score'] = ['required_without:max_percentage','numeric','min:0','max:100'];
            $rules['points'] = ['nullable','numeric'];
        }

        return $rules;
    }
}
