<?php

namespace App\DataTransferObjects;

use Illuminate\Http\Request;

class PrefixDTO extends DTO
{
    public function __construct(
        public readonly ?string $prefix
    ) {
    }

    public static function fromRequest(Request $request): self
    {
        return new self($request->input('prefix'));
    }
}
