<?php

namespace App\Services\Admin;

use App\Constants\EmployeeHeader;
use App\Exceptions\ValidationException;
use App\Imports\Admin\EmployeeImport;
use App\Models\Employee;
use App\Validators\Admin\EmployeeValidator;
use Carbon\Carbon;
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
        $start = now();

        $file = $request->file('file');

        $import = app()->make(EmployeeImport::class);

        Excel::import($import, $file);

        if ($import->failures()->isNotEmpty()) {
            throw new \Exception('There were failures while importing employees.');
        }

        $duration = now()->diffInSeconds($start);
        echo "Execution time: $duration seconds";
    }

    public function spatieImport($request): void
    {
        $start = now();

        $file = $request->file('file');

        SimpleExcelReader::create($file, $file->getClientOriginalExtension())
            ->skip(1)
            ->noHeaderRow()
            ->getRows()
            ->each(function (array $row) {

                $emp_id = $row[EmployeeHeader::EMP_ID_INDEX];

                $date_of_birth = $row[EmployeeHeader::DATE_OF_BIRTH_INDEX];
                $date_of_joining = $row[EmployeeHeader::DATE_OF_JOINING_INDEX];

                $this->transformDates($date_of_birth, $date_of_joining, $row);

                EmployeeValidator::validateEmployee($row);

                $data = [
                    'first_name' => $row[EmployeeHeader::FIRST_NAME_INDEX],
                    'middle_initial' => $row[EmployeeHeader::MIDDLE_INITIAL_INDEX],
                    'last_name' => $row[EmployeeHeader::LAST_NAME_INDEX],
                    'gender' => $row[EmployeeHeader::GENDER_INDEX],
                    'email' => $row[EmployeeHeader::E_MAIL_INDEX],

                    'date_of_birth' => $date_of_birth,
                    'date_of_joining' => $date_of_joining,

                    'time_of_birth' => Carbon::parse($row[EmployeeHeader::TIME_OF_BIRTH_INDEX])->format('H:i:s'),
                    'age_in_years' => $row[EmployeeHeader::AGE_IN_YRS_INDEX],

                    'age_in_company_in_years' => $row[EmployeeHeader::AGE_IN_COMPANY_YEARS_INDEX],
                    'phone_number' => $row[EmployeeHeader::PHONE_NO_INDEX],
                    'place_name' => $row[EmployeeHeader::PLACE_NAME_INDEX],
                    'username' => $row[EmployeeHeader::USER_NAME_INDEX],
                ];

                $this->updateOneOrCreate($emp_id, $data);
            });

        $duration = now()->diffInSeconds($start);
        echo "Execution time: $duration seconds";
    }

    private function transformDates(&$date_of_birth, &$date_of_joining, $row)
    {
        try {
            $date_of_birth = $row[EmployeeHeader::DATE_OF_BIRTH_INDEX];
            if (preg_match('/^\d{2}-\d{2}-\d{2}$/', $date_of_birth)) {
                $date_of_birth = Carbon::createFromFormat('d-m-y', $date_of_birth)->format('Y-m-d');
            }
            $date_of_birth = Carbon::parse($date_of_birth)->format('Y-m-d');

            $date_of_joining = $row[EmployeeHeader::DATE_OF_JOINING_INDEX];
            if (preg_match('/^\d{2}-\d{2}-\d{2}$/', $date_of_joining)) {
                $date_of_joining = Carbon::createFromFormat('d-m-y', $date_of_joining);
            }
            $date_of_joining = Carbon::parse($date_of_joining)->format('Y-m-d');

        } catch (Throwable $e) {
            throw new ValidationException($e->getMessage());
        }
    }
}
