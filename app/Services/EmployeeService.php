<?php

namespace App\Services;

use App\DataTransferObjects\EmployeeDTO;
use App\Imports\EmployeeImport;
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

    public function createOne(EmployeeDTO $employee_data): ?Employee
    {
        return DB::transaction(function () use ($employee_data) {
            return Employee::create([
                'id' => $employee_data->id,
                'email' => $employee_data->email,
                'phone_number' => $employee_data->phone_number,
                'first_name' => $employee_data->first_name,
                'last_name' => $employee_data->last_name,
                'middle_initial' => $employee_data->middle_initial,
                'gender' => $employee_data->gender,
                'zip_code_id' => $employee_data->zip_code_id,
                'prefix_id' => $employee_data->prefix_id,
                'age_in_company_in_years' => $employee_data->age_in_company_in_years,
                'place_name' => $employee_data->place_name,
                'age_in_years' => $employee_data->age_in_years,
                'time_of_birth' => $employee_data->time_of_birth,
                'date_of_birth' => $employee_data->date_of_birth,
            ])->fresh();
        });
    }

    public function firstOrCreate($employee_id, $employee_data): ?Employee
    {
        return DB::transaction(function () use ($employee_id, $employee_data) {
            return Employee::firstOrCreate([
                'id' => $employee_id,
            ], $employee_data)->fresh();
        });
    }

    public function getOne(int $id): ?Employee
    {
        $employee = Employee::findOrFail($id);

        return $employee;
    }

    public function updateOne(EmployeeDTO $employee_data, Employee $employee): Employee
    {
        return DB::transaction(function () use ($employee_data, $employee) {
            return tap($employee)->update(
                $employee_data->nonNullToArray()
            );
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
