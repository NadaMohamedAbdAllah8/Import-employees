<?php

namespace App\Imports\Admin;

use App\Services\Admin\EmployeeService;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class EmployeeImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    use Importable, SkipsFailures, SkipsErrors;

    public function __construct(private EmployeeService $employee_service
    ) {
    }

    /**
     * A sample
     * Emp ID    Name Prefix    First Name    Middle Initial    Last Name    Gender    E Mail    Date of Birth    Time of Birth    Age in Yrs.    Date of Joining    Age in Company (Years)    Phone No.     Place Name    County    City    Zip    Region    User Name
     * 198429    Mrs.    Serafina    I    Bumgarner    F    serafina.bumgarner@exxonmobil.com    9/21/1982    1:53:14 AM    34.87    02-01-08    9.49    212-376-9125    Clymer    Chautauqua    Clymer    14724    Northeast    sibumgarner
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            $emp_id = $row['emp_id'];
            $name_prefix = $row['name_prefix'];
            $first_name = $row['first_name'];
            $middle_initial = $row['middle_initial'];
            $last_name = $row['last_name'];
            $gender = $row['gender'];
            $e_mail = $row['e_mail'];
            $date_of_birth = $row['date_of_birth'];
            $time_of_birth = $row['time_of_birth'];
            $age_in_yrs = $row['age_in_yrs'];
            $date_of_joining = $row['date_of_joining'];
            $age_in_company_years = $row['age_in_company_years'];
            $phone_no = $row['phone_no'];
            $place_name = $row['place_name'];
            $user_name = $row['user_name'];
            $county = $row['county'];
            $city = $row['city'];
            $zip = $row['zip'];
            $region = $row['region'];

            $data = [
                'first_name' => $first_name,
                'middle_initial' => $middle_initial,
                'last_name' => $last_name,
                'gender' => $gender,
                'email' => $e_mail,
                'date_of_birth' => $date_of_birth,
                'time_of_birth' => Date::excelToDateTimeObject($row['time_of_birth'])->format('H:i:s'),
                'age_in_years' => $age_in_yrs,
                'date_of_joining' => $date_of_joining,
                'age_in_company_in_years' => $age_in_company_years,
                'phone_number' => $phone_no,
                'place_name' => $place_name,
                'username' => $user_name,
            ];

            $this->employee_service->updateOneOrCreate($emp_id, $data);
        }
    }

    /**
     * Gets the chunk size for importing data.
     *
     * @return int The chunk size.
     */
    public function chunkSize(): int
    {
        return env('IMPORT_CHUNK_SIZE', 500);
    }
}