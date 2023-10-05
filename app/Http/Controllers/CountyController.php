<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\CountyDTO;
use App\Http\Requests\County\StoreRequest;
use App\Http\Requests\County\UpdateRequest;
use App\Http\Resources\CountyResource;
use App\Models\County;
use App\Services\CountyService;
use App\Traits\GeneralResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CountyController extends Controller
{
    use GeneralResponseTrait;

    public function __construct(private CountyService $county_service)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $paginate = $request->paginate ?? 10;
        $counties = $this->county_service->getMany($paginate);

        return $this->returnDataWithPaginate(
            $counties,
            'Counties list',
            CountyResource::class
        );
    }

    /**
     * Store the specified resource.
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $county_dto = CountyDTO::fromRequest($request);
        $county = $this->county_service->createOne($county_dto);

        return $this->returnCreatedWithData(
            new CountyResource($county),
            'Saved successfully'
        );

    }

    /**
     * Display the specified resource.
     *
     * @throws ModelNotFoundException
     */
    public function show(County $county): JsonResponse
    {
        return $this->returnData(
            new CountyResource($county),
            'Success'
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(UpdateRequest $request, County $county): JsonResponse
    {
        $county_dto = CountyDTO::fromRequest($request);
        $county = $this->county_service->updateOne($county_dto, $county);

        return $this->returnData(
            new CountyResource($county),
            'Success'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(County $county): JsonResponse
    {
        $this->county_service->deleteOne($county);

        return $this->returnDeletedWithoutData();
    }
}
