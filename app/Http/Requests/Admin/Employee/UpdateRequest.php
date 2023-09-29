<?php

namespace App\Http\Requests\Admin\Employee;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'email' => ['nullable', 'email', 'max:191',
                Rule::unique('employees', 'email')->ignore($this->employee->id)],
            'username' => ['nullable', 'string',
                Rule::unique('employees', 'username')->ignore($this->employee->id)],
            'first_name' => 'nullable|string|min:2|max:120',
            'last_name' => 'nullable|string|min:2|max:120',
            'middle_initial' => 'nullable|string|size:1',
            'gender' => 'nullable|in:M,F',
            'zip_code_id' => 'nullable|exists:zip_codes,id',
            'prefix_id' => 'nullable|exists:prefixes,id',
            'phone_number' => ['nullable', 'string',
                Rule::unique('employees', 'phone_number')->ignore($this->employee->id), 'regex:/^(\+\d{1,3}[- ]?)?\d{10}$/'],
            'place_name' => 'nullable|string|min:2|max:225',
            'age_in_years' => 'nullable|numeric',
            'age_in_company_in_years' => 'nullable|numeric',
            'date_of_birth' => 'nullable|date_format:Y-m-d',
            'date_of_joining' => 'nullable|date_format:Y-m-d',
            'time_of_birth' => 'nullable|date_format:H:i:s',
        ];
    }
}
