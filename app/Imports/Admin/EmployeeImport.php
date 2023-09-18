<?php

namespace App\Imports\Admin;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Throwable;

class EmployeeImport implements ToModel, WithHeadingRow, WithUpserts, WithChunkReading, WithBatchInserts
{
    use SkipsFailures, SkipsErrors;

    /**
     * Defines the unique column in used by the upsert
     * @return string The unique column
     */
    /**
     * A sample of the imported document
     * Emp ID    Name Prefix    First Name    Middle Initial    Last Name    Gender    E Mail    Date of Birth    Time of Birth    Age in Yrs.    Date of Joining    Age in Company (Years)    Phone No.     Place Name    County    City    Zip    Region    User Name
     * 198429    Mrs.    Serafina    I    Bumgarner    F    serafina.bumgarner@exxonmobil.com    9/21/1982    1:53:14 AM    34.87    02-01-08    9.49    212-376-9125    Clymer    Chautauqua    Clymer    14724    Northeast    sibumgarner
     */
    public function model(array $row): Employee
    {
        try {
            return new Employee([
                'id' => $row['emp_id'],
                'first_name' => $row['first_name'],
                'middle_initial' => $row['middle_initial'],
                'last_name' => $row['last_name'],
                'gender' => $row['gender'],
                'email' => $row['e_mail'],
                'date_of_birth' => $row['date_of_birth'],
                //'time_of_birth' => DateTime::createFromFormat('h:i:s A', $row['time_of_birth'])->format('H:i:s'),
                // 'time_of_birth' => Date::excelToDateTimeObject($row['time_of_birth'])->format('H:i:s'),
                'age_in_years' => $row['age_in_yrs'],
                //'date_of_joining' => $row['date_of_joining'],
                'age_in_company_in_years' => $row['age_in_company_years'],
                'phone_number' => $row['phone_no'],
                'place_name' => $row['place_name'],
                'username' => $row['user_name'],
            ]);} catch (Throwable $e) {
            dump($row['emp_id'], $row['time_of_birth'], $e->getMessage(), $e->getTrace());
        }
    }

    /**
     * Defines the unique column in used by the upsert
     * @return string The unique column
     */
    public function uniqueBy(): string
    {
        return 'id';
    }

    /**
     * Defines the number of inserted rows at once
     *
     * @return int The batch size.
     */
    public function batchSize(): int
    {
        return env('IMPORT_BATCH_SIZE', 500);
    }

    /**
     * Defines the chuck sizes of reading the imported file
     *
     * @return int The chunk size.
     */
    public function chunkSize(): int
    {
        return env('IMPORT_CHUNK_SIZE', 500);
    }
}
