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

    public function getOne(int $id): ?Employee
    {
        $employee = Employee::findOrFail($id);

        return $employee;
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
