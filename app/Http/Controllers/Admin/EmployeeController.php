<?php

namespace App\Http\Controllers\Admin;

use App\DataTransferObjects\EmployeeDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Employee\ImportRequest;
use App\Http\Requests\Admin\Employee\StoreRequest;
use App\Http\Requests\Admin\Employee\UpdateRequest;
use App\Http\Resources\Admin\EmployeeResource;
use App\Models\Employee;
use App\Services\Admin\EmployeeService;
use App\Traits\GeneralResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    use GeneralResponseTrait;

    public function __construct(private EmployeeService $employee_service)
    {}

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        $paginate = $request->paginate ?? 10;
        $employees = $this->employee_service->getMany($paginate);

        return $this->returnDataWithPaginate(
            $employees,
            'Employees list',
            EmployeeResource::class
        );
    }

    /**
     * Store the specified resource.
     */
    public function store(StoreRequest $request): JsonResponse
    {
        $employee_data = EmployeeDTO::fromRequest($request);
        $employee = $this->employee_service->createOne($employee_data);

        return $this->returnCreatedWithData(
            new EmployeeResource($employee),
            'Saved successfully'
        );

    }

    /**
     * Display the specified resource.
     *
     * @throws ModelNotFoundException
     */
    public function show(Employee $employee): JsonResponse
    {
        return $this->returnData(
            new EmployeeResource($employee),
            'Success'
        );
    }

    /**
     * Update the specified resource.
     */
    public function update(UpdateRequest $request, Employee $employee): JsonResponse
    {
        // $employee_data = EmployeeDTO::fromRequest($request);
        $employee = $this->employee_service->updateOne($request->validated(), $employee);

        return $this->returnData(
            new EmployeeResource($employee),
            'Success'
        );
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee): JsonResponse
    {
        $this->employee_service->deleteOne($employee);

        return $this->returnDeletedWithoutData();
    }

    /**
     * Imports employee data from a file.
     */
    public function import(ImportRequest $request)
    {
        $this->employee_service->import($request);

        return $this->returnSuccessMessage('Added to queue successfully');
    }
}
