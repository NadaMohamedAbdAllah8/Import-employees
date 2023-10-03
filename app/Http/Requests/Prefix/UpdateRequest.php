<?php

namespace App\Http\Requests\Prefix;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

// D:\Programming-Studying\Laravel\Projects\Import-employees\import-employees-totally-new\app\Http\Requests\Employee\Region\UpdateRequest.php
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
            'prefix' => ['nullable', 'string',
                Rule::unique('prefixes', 'prefix')->ignore($this->id)],
        ];
    }
}
