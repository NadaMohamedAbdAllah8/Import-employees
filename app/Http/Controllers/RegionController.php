<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\RegionDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Region\StoreRequest;
use App\Http\Requests\Region\UpdateRequest;
use App\Http\Resources\RegionResource;
use App\Models\Region;
use App\Services\RegionService;
use App\Traits\GeneralResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RegionController extends Controller
{
    use GeneralResponseTrait;

    public function __construct(private RegionService $region_service)
    {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $paginate = $request->paginate ?? 10;
        $regions = $this->region_service->getMany($paginate);

        return $this->returnDataWithPaginate(
            $regions,
            'Regions list',
            RegionResource::class
        );
    }

    /**
     * Store the specified resource.
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $region_dto = RegionDTO::fromRequest($request);
        $region = $this->region_service->createOne($region_dto);

        return $this->returnCreatedWithData(
            new RegionResource($region),
            'Saved successfully'
        );

    }

    /**
     * Display the specified resource.
     *
     * @throws ModelNotFoundException
     */
    public function show(Region $region): JsonResponse
    {
        return $this->returnData(
            new RegionResource($region),
            'Success'
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(UpdateRequest $request, Region $region): JsonResponse
    {
        $region_dto = RegionDTO::fromRequest($request);
        $region = $this->region_service->updateOne($region_dto, $region);

        return $this->returnData(
            new RegionResource($region),
            'Success'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Region $region): JsonResponse
    {
        $this->region_service->deleteOne($region);

        return $this->returnDeletedWithoutData();
    }
}
