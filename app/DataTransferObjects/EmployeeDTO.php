<?php

namespace App\DataTransferObjects;

use Illuminate\Http\Request;

class EmployeeDTO extends DTO
{
    public function __construct(
        public readonly ?int $id,
        public readonly ?string $email,
        public readonly ?string $phone_number,
        public readonly ?string $username,
        public readonly ?string $first_name,
        public readonly ?string $last_name,
        public readonly ?string $middle_initial,
        public readonly ?string $gender,
        public readonly ?string $place_name,
        public readonly ?float $age_in_years,
        public readonly ?float $age_in_company_in_years,
        public readonly ?string $date_of_birth,
        public readonly ?string $date_of_joining,
        public readonly ?string $time_of_birth,
        public readonly ?int $zip_code_id,
        public readonly ?int $prefix_id,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->input('id'),
            $request->input('email'),
            $request->input('phone_number'),
            $request->input('username'),
            $request->input('first_name'),
            $request->input('last_name'),
            $request->input('middle_initial'),
            $request->input('gender'),
            $request->input('place_name'),
            $request->input('age_in_years'),
            $request->input('age_in_company_in_years'),
            $request->input('date_of_birth'),
            $request->input('date_of_joining'),
            $request->input('time_of_birth'),
            $request->input('zip_code_id'),
            $request->input('prefix_id'),
        );
    }
}
