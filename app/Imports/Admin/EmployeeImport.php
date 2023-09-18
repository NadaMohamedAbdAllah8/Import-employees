<?php

namespace App\Imports\Admin;

use App\Constants\EmployeeHeader;
use App\Models\Employee;
use App\Validators\Admin\EmployeeValidator;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Throwable;

class EmployeeImport implements ToModel, WithStartRow, WithUpserts, WithChunkReading, WithBatchInserts
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
        EmployeeValidator::validateEmployee($row);

        try {
            if (is_string($row[EmployeeHeader::TIME_OF_BIRTH_INDEX])) {
                $time_of_birth = $row[EmployeeHeader::TIME_OF_BIRTH_INDEX];
            } else {
                $time_of_birth = Date::excelToDateTimeObject($row[EmployeeHeader::TIME_OF_BIRTH_INDEX])->format('H:i:s');
            }

            $date_of_birth = Date::excelToDateTimeObject($row[EmployeeHeader::DATE_OF_BIRTH_INDEX])->format('Y-m-d');
            $date_of_joining = Date::excelToDateTimeObject($row[EmployeeHeader::DATE_OF_JOINING_INDEX])->format('Y-m-d');

            return new Employee([
                'id' => $row[EmployeeHeader::EMP_ID_INDEX],
                'first_name' => $row[EmployeeHeader::FIRST_NAME_INDEX],
                'middle_initial' => $row[EmployeeHeader::MIDDLE_INITIAL_INDEX],
                'last_name' => $row[EmployeeHeader::LAST_NAME_INDEX],
                'gender' => $row[EmployeeHeader::GENDER_INDEX],
                'email' => $row[EmployeeHeader::E_MAIL_INDEX],
                'date_of_birth' => $date_of_birth,
                'time_of_birth' => $time_of_birth,
                'age_in_years' => $row[EmployeeHeader::AGE_IN_YRS_INDEX],
                'date_of_joining' => $date_of_joining,
                'age_in_company_in_years' => $row[EmployeeHeader::AGE_IN_COMPANY_YEARS_INDEX],
                'phone_number' => $row[EmployeeHeader::PHONE_NO_INDEX],
                'place_name' => $row[EmployeeHeader::PLACE_NAME_INDEX],
                'username' => $row[EmployeeHeader::USER_NAME_INDEX],
            ]);
        } catch (Throwable $e) {
            dump($row[EmployeeHeader::DATE_OF_BIRTH_INDEX]);
            dump($row[EmployeeHeader::TIME_OF_BIRTH_INDEX]);
            dump($e->getMessage());
            dump($row[EmployeeHeader::EMP_ID_INDEX]);
            dump($e->getTrace());
        }
    }

    /**
     * @return int
     */
    public function startRow(): int
    {
        return 2;
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
        return config('excel_custom.import.batch_size', 500);
    }

    /**
     * Defines the chuck sizes of reading the imported file
     *
     * @return int The chunk size.
     */
    public function chunkSize(): int
    {
        return config('excel_custom.import.chunk_size', 500);
    }
}
