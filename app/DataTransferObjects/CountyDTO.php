<?php

namespace App\DataTransferObjects;

use Illuminate\Http\Request;

class CountyDTO extends DTO
{
    public function __construct(
        public readonly ?string $name,
        public readonly ?int $region_id,
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self(
            $request->input('name'),
            $request->input('region_id')
        );
    }
}
