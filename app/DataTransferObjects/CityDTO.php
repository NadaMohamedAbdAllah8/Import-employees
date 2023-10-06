<?php

namespace App\DataTransferObjects;

use Illuminate\Http\Request;

class CityDTO extends DTO
{
    public function __construct(
        public readonly ?string $name,
        public readonly ?int $county_id,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->input('name'),
            $request->input('county_id')
        );
    }
}
