<?php

namespace App\Services\Admin;

use App\Models\Employee;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

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
}
