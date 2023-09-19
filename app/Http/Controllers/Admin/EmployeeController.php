<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Employee\ImportRequest;
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
    {
    }

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
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee): JsonResponse
    {
        $this->employee_service->deleteOne($employee);

        return $this->returnSuccessMessage('Deleted successfully');
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
