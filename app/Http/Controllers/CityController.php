<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\CityDTO;
use App\Http\Requests\City\StoreRequest;
use App\Http\Requests\City\UpdateRequest;
use App\Http\Resources\CityResource;
use App\Models\City;
use App\Services\CityService;
use App\Traits\GeneralResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CityController extends Controller
{
    use GeneralResponseTrait;

    public function __construct(private CityService $city_service)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $paginate = $request->paginate ?? 10;
        $cities = $this->city_service->getMany($paginate);

        return $this->returnDataWithPaginate(
            $cities,
            'Cities list',
            CityResource::class
        );
    }

    /**
     * Store the specified resource.
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $city_dto = CityDTO::fromRequest($request);
        $city = $this->city_service->createOne($city_dto);

        return $this->returnCreatedWithData(
            new CityResource($city),
            'Saved successfully'
        );
    }

    /**
     * Display the specified resource.
     *
     * @throws ModelNotFoundException
     */
    public function show(City $city): JsonResponse
    {
        return $this->returnData(
            new CityResource($city),
            'Success'
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(UpdateRequest $request, City $city): JsonResponse
    {
        $city_dto = CityDTO::fromRequest($request);
        $city = $this->city_service->updateOne($city_dto, $city);

        return $this->returnData(
            new CityResource($city),
            'Success'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(City $city): JsonResponse
    {
        $this->city_service->deleteOne($city);

        return $this->returnDeletedWithoutData();
    }
}
