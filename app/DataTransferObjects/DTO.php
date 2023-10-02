<?php

namespace App\DataTransferObjects;

use Illuminate\Http\Request;

abstract class DTO
{
    public function toArray(): array
    {
        $array = [];
        foreach ($this as $key => $value) {
            if ($value !== null) {
                $array[$key] = $value;
            }
        }
        return $array;
    }

    abstract public static function fromRequest(Request $request): self;

}
