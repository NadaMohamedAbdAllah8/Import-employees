<?php

namespace App\Exceptions;

use App\Traits\GeneralResponseTrait;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

class ValidationException extends Exception
{
    use GeneralResponseTrait;

    /**
     * Render the exception into an HTTP response.
     *
     * @return JsonResponse
     */
    public function render(): JsonResponse
    {
        return $this->returnError($this->getMessage(), Response::HTTP_BAD_REQUEST);
    }
}
