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
            'email' => ['required', 'email', 'max:191',
                Rule::unique('employees', 'email')
                    ->ignore($this->employee->id)],
            // 'name' => 'required|alpha|min:2|string',
        ];
    }
}
