<?php

namespace App\Http\Controllers;

use App\DataTransferObjects\PrefixDTO;
use App\Http\Requests\Prefix\StoreRequest;
use App\Http\Requests\Prefix\UpdateRequest;
use App\Http\Resources\PrefixResource;
use App\Models\Prefix;
use App\Services\PrefixService;
use App\Traits\GeneralResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PrefixController extends Controller
{
    use GeneralResponseTrait;

    public function __construct(private PrefixService $prefix_service)
    {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $paginate = $request->paginate ?? 10;
        $prefixs = $this->prefix_service->getMany($paginate);

        return $this->returnDataWithPaginate(
            $prefixs,
            'Prefixes list',
            PrefixResource::class
        );
    }

    /**
     * Store the specified resource.
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $prefix_dto = PrefixDTO::fromRequest($request);
        $prefix = $this->prefix_service->createOne($prefix_dto);

        return $this->returnCreatedWithData(
            new PrefixResource($prefix),
            'Saved successfully'
        );

    }

    /**
     * Display the specified resource.
     *
     * @throws ModelNotFoundException
     */
    public function show(Prefix $prefix): JsonResponse
    {
        return $this->returnData(
            new PrefixResource($prefix),
            'Success'
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(UpdateRequest $request, Prefix $prefix): JsonResponse
    {
        $prefix_dto = PrefixDTO::fromRequest($request);
        $prefix = $this->prefix_service->updateOne($prefix_dto, $prefix);

        return $this->returnData(
            new PrefixResource($prefix),
            'Success'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Prefix $prefix): JsonResponse
    {
        $this->prefix_service->deleteOne($prefix);

        return $this->returnDeletedWithoutData();
    }
}
