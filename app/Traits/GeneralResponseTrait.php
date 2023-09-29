<?php

namespace App\Traits;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

trait GeneralResponseTrait
{
    public function returnSuccessMessage($message = ''): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $message,
        ], Response::HTTP_OK);
    }

    public function returnError($message, $code): JsonResponse
    {
        return response()->json([
            'status' => false,
            'message' => $message,
        ], $code);
    }

    public function returnData($value, $message): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'item' => $value,
        ], Response::HTTP_OK);
    }

    public function returnErrorWithData($value, $message, $validationCode, $code): JsonResponse
    {
        return response()->json([
            'status' => false,
            'message' => $message,
            'code' => $validationCode,
            'item' => $value,
        ], $code);
    }

    public function returnCreatedWithData($value, $message): JsonResponse
    {
        return response()->json([
            'status' => true,
            'message' => $message,
            'item' => $value,
        ], Response::HTTP_CREATED);
    }

    public function returnDeletedWithoutData(): JsonResponse
    {
        return response()->json([], Response::HTTP_NO_CONTENT);
    }
    public function returnDataWithPaginate($value, $message, $resourcePath, $groupBy = null): JsonResponse
    {
        $items = $resourcePath::collection($value);
        if ($groupBy) {
            $items = $items->groupBy($groupBy);
        }

        return response()->json([
            'success' => 'true',
            'message' => $message,
            'items' => $items,
            'size' => $value->count(),
            'page' => $value->currentPage(),
            'total_pages' => $value->lastPage(),
            'total_size' => $value->total(),
            'per_page' => $value->perPage(),
        ]);
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->returnError($validator->errors(), Response::HTTP_UNPROCESSABLE_ENTITY));
    }
}
