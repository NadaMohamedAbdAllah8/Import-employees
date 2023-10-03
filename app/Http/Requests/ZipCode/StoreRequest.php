<?php

namespace App\Http\Requests\ZipCode;

use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends FormRequest
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
            'code' => 'required|integer|min:3|unique:zip_codes,code',
            'city_id' => 'required|exists:cities,id|unique:zip_codes,city_id',
        ];
    }
}
