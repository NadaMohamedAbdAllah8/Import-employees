<?php

namespace App\DataTransferObjects;

use Illuminate\Http\Request;

class RegionDTO extends DTO
{
    public function __construct(
        public readonly ?string $name
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self($request->input('name'));
    }
}
