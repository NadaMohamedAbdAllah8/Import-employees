<?php

namespace App\Http\Requests\Employee;

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
            'id' => 'nullable|integer',
            'email' => 'required|string|unique:employees,email',
            'username' => 'required|string|unique:employees,username',
            'first_name' => 'required|string|min:2|max:120',
            'last_name' => 'required|string|min:2|max:120',
            'middle_initial' => 'required|string|size:1',
            'gender' => 'required|in:M,F',
            'zip_code_id' => 'required|exists:zip_codes,id',
            'prefix_id' => 'required|exists:prefixes,id',
            /**The regex accepts
             * +123456789012 (Country code +123, 10-digit number)
            +1-2345678901 (Country code +1 with a dash, 10-digit number)
            +12 3456789012 (Country code +12 with a space, 10-digit number)
            1234567890 (Just a 10-digit number, without a country code)
             */
            'phone_number' => 'required|string|unique:employees,phone_number|regex:/^(\+\d{1,3}[- ]?)?\d{10}$/',
            'place_name' => 'required|string|min:2|max:225',
            'age_in_years' => 'required|numeric',
            'age_in_company_in_years' => 'required|numeric',
            'date_of_birth' => 'required|date_format:Y-m-d',
            'date_of_joining' => 'required|date_format:Y-m-d',
            'time_of_birth' => 'required|date_format:H:i:s',
        ];
    }
}
