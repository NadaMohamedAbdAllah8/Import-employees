<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\ZipCodeDTO;
use App\Http\Requests\ZipCode\StoreRequest;
use App\Http\Requests\ZipCode\UpdateRequest;
use App\Http\Resources\ZipCodeResource;
use App\Models\ZipCode;
use App\Services\ZipCodeService;
use App\Traits\GeneralResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ZipCodeController extends Controller
{
    use GeneralResponseTrait;

    public function __construct(private ZipCodeService $zip_code_service)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $paginate = $request->paginate ?? 10;
        $zip_codes = $this->zip_code_service->getMany($paginate);

        return $this->returnDataWithPaginate(
            $zip_codes,
            'Zip Codes list',
            ZipCodeResource::class
        );
    }

    /**
     * Store the specified resource.
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $zip_code_dto = ZipCodeDTO::fromRequest($request);
        $zip_code = $this->zip_code_service->createOne($zip_code_dto);

        return $this->returnCreatedWithData(
            new ZipCodeResource($zip_code),
            'Saved successfully'
        );

    }

    /**
     * Display the specified resource.
     *
     * @throws ModelNotFoundException
     */
    public function show(ZipCode $zip_code): JsonResponse
    {
        return $this->returnData(
            new ZipCodeResource($zip_code),
            'Success'
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(UpdateRequest $request, ZipCode $zip_code): JsonResponse
    {
        $zip_code_dto = ZipCodeDTO::fromRequest($request);
        $zip_code = $this->zip_code_service->updateOne($zip_code_dto, $zip_code);

        return $this->returnData(
            new ZipCodeResource($zip_code),
            'Success'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ZipCode $zip_code): JsonResponse
    {
        $this->zip_code_service->deleteOne($zip_code);

        return $this->returnDeletedWithoutData();
    }
}
