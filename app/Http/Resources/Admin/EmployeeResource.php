<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'first_name' => $this->first_name,
            'middle_initial' => $this->middle_initial,
            'last_name' => $this->last_name,
            'username' => $this->user_name,
            'gender' => $this->gender,
            'email' => $this->email,
            'date_of_birth' => $this->date_of_birth,
            'time_of_birth' => $this->time_of_birth,
            'age_in_years' => $this->age_in_years,
            'phone_number' => $this->phone_number,
            'place_name' => $this->place_name,
            'date_of_joining' => $this->date_of_joining,
            'age_in_company_in_years' => $this->age_in_company_in_years,
            'prefix' => $this->prefix ? $this->prefix->prefix : null,
            'zip_code_id' => $this->zipCode ? $this->zipCode->code : null,
            'city' => $this->city ? $this->city->name : null,
            'county' => $this->county ? $this->county->name : null,
            'region' => $this->region ? $this->region->name : null,
        ];
    }
}
