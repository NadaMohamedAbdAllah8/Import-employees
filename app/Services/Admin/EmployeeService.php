<?php

namespace App\Services\Admin;

use App\Imports\Admin\EmployeeImport;
use App\Models\Employee;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class EmployeeService
{
    public function getMany($paginate): LengthAwarePaginator
    {
        return Employee::orderBy('id')->paginate($paginate);
    }

    public function createOne($employee_data): ?Employee
    {
        return DB::transaction(function () use ($employee_data) {
            return Employee::create($employee_data)->fresh();
        });
    }

    public function updateOneOrCreate($employee_id, $employee_data): ?Employee
    {
        return DB::transaction(function () use ($employee_id, $employee_data) {
            return Employee::updateOrCreate([
                'id' => $employee_id,
            ], $employee_data)->fresh();
        });
    }

    public function getOne(int $id): ?Employee
    {
        $employee = Employee::findOrFail($id);

        return $employee;
    }

    public function updateOne($employee_data, Employee $employee): Employee
    {
        return DB::transaction(function () use ($employee, $employee_data) {
            return tap($employee)->update($employee_data);
        });
    }

    public function deleteOne(Employee $employee): bool
    {
        return DB::transaction(function () use ($employee) {
            return $employee->delete();
        });
    }

    public function import($request): void
    {
        $file = $request->file('file');

        $import = app()->make(EmployeeImport::class);

        Excel::import($import, $file);

        if ($import->failures()->isNotEmpty()) {
            throw new \Exception('There were failures while importing employees.');
        }

    }
}
