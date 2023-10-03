<?php

namespace App\DataTransferObjects;

use Illuminate\Http\Request;

class ZipCodeDTO extends DTO
{
    public function __construct(
        public readonly ?string $code,
        public readonly ?int $city_id,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->input('code'),
            $request->input('city_id')
        );
    }
}
