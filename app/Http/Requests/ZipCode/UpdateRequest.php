<?php

namespace App\Http\Requests\ZipCode;

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
            'code' => ['nullable', 'integer', 'min:3',
                Rule::unique('zip_codes', 'code')->ignore($this->id)],
            'city_id' => ['nullable', 'integer', 'exists:cities,id',
                Rule::unique('zip_codes', 'city_id')->ignore($this->city_id)],
        ];
    }
}
