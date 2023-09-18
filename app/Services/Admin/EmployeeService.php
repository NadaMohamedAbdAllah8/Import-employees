<?php

namespace App\Services\Admin;

use App\Imports\Admin\EmployeeImport;
use App\Models\Employee;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\SimpleExcel\SimpleExcelReader;
use Throwable;

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

    public function spatieImport($request): void
    {
        $file = $request->file('file');

        SimpleExcelReader::create($file, $file->getClientOriginalExtension())
            ->getRows()
            ->each(function (array $row) {
                try {
                    // dump($row['Time of Birth']);
                    $emp_id = $row['Emp ID'];
                    $first_name = $row['First Name'];
                    $middle_initial = $row['Middle Initial'];
                    $last_name = $row['Last Name'];
                    $gender = $row['Gender'];
                    $e_mail = $row['E Mail'];
                    // $date_of_birth = Carbon::parse($row['Date of Birth'])->format('Y-m-d');
                    // $time_of_birth = Carbon::parse($row['Time of Birth'])->format('H:i:s');
                    $age_in_yrs = $row['Age in Yrs.'];
                    // $date_of_joining = Carbon::parse($row['Date of Joining'])->format('Y-m-d');
                    $age_in_company_years = $row['Age in Company (Years)'];
                    $phone_no = $row['Phone No. '];
                    $place_name = $row['Place Name'];
                    $user_name = $row['User Name'];
                } catch (Throwable $e) {
                    dump($row['Emp ID'], $row['Date of Birth'], $e->getMessage(), $e->getTrace());
                }

                Employee::updateOrCreate(
                    ['id' => $emp_id], [
                        'first_name' => $first_name,
                        'middle_initial' => $middle_initial,
                        'last_name' => $last_name,
                        'gender' => $gender,
                        'email' => $e_mail,
                        // 'date_of_birth' => $date_of_birth,
                        // 'time_of_birth' => $time_of_birth,
                        'age_in_years' => $age_in_yrs,
                        // 'date_of_joining' => $date_of_joining,
                        'age_in_company_in_years' => $age_in_company_years,
                        'phone_number' => $phone_no,
                        'place_name' => $place_name,
                        'username' => $user_name,
                    ]);

            });

    }
}
