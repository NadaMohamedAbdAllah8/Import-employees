<?php

namespace App\Http\Requests\County;

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
            'name' => ['nullable', 'string', 'min:2',
                Rule::unique('counties', 'name')->ignore($this->id)],
            'region_id' => ['nullable', 'integer', 'exists:regions,id',
                Rule::unique('counties', 'region_id')->ignore($this->region_id)],
        ];
    }
}
