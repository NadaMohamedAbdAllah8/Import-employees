<?php

namespace App\Imports\Admin;

use App\Constants\EmployeeHeader;
use App\Exceptions\ParsingException;
use App\Models\Employee;
use App\Models\Prefix;
use App\Services\Admin\EmployeeImportService;
use App\Validators\Admin\EmployeeValidator;
use Carbon\Carbon;
use DateTime;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Throwable;

class EmployeeImport implements ToModel, WithStartRow, WithUpserts,
WithChunkReading
//, WithBatchInserts
, ShouldQueue
{
    use SkipsFailures, SkipsErrors;
    //use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private EmployeeImportService $import_service)
    {
    }

    /**
     * A sample of the imported document
     * Emp ID    Name Prefix    First Name    Middle Initial    Last Name    Gender    E Mail    Date of Birth    Time of Birth    Age in Yrs.    Date of Joining    Age in Company (Years)    Phone No.     Place Name    County    City    Zip    Region    User Name
     * 198429    Mrs.    Serafina    I    Bumgarner    F    serafina.bumgarner@exxonmobil.com    9/21/1982    1:53:14 AM    34.87    02-01-08    9.49    212-376-9125    Clymer    Chautauqua    Clymer    14724    Northeast    sibumgarner
     */
    public function model(array $row): Employee
    {
        EmployeeValidator::validateEmployee($row);

        try {
            $date_of_birth = $row[EmployeeHeader::DATE_OF_BIRTH_INDEX];
            $date_of_joining = $row[EmployeeHeader::DATE_OF_JOINING_INDEX];
            $time_of_birth = $row[EmployeeHeader::TIME_OF_BIRTH_INDEX];

            $this->transformDates($date_of_birth, $date_of_joining, $row);
            $this->transformTime($time_of_birth, $row);

            $prefix_id = $this->firstOrCreatePrefix($row[EmployeeHeader::NAME_PREFIX_INDEX])->id;

            $region_id = $this->import_service->firstOrCreateRegion($row[EmployeeHeader::REGION_INDEX])->id;
            $county_id = $this->import_service->firstOrCreateCounty($row[EmployeeHeader::COUNTY_INDEX], $region_id)->id;
            $city_id = $this->import_service->firstOrCreateCity($row[EmployeeHeader::CITY_INDEX], $county_id)->id;
            $zip_code_id = $this->import_service->firstOrCreateZipCode($row[EmployeeHeader::ZIP_INDEX], $city_id)->id;

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
                'prefix_id' => $prefix_id,
                'zip_code_id' => $zip_code_id,
            ]);
        } catch (Throwable $e) {
            Log::info('in catch');

            // dump($row[EmployeeHeader::DATE_OF_BIRTH_INDEX]);
            // dump($row[EmployeeHeader::TIME_OF_BIRTH_INDEX]);
            // dump($e->getMessage());
            // dump($row[EmployeeHeader::EMP_ID_INDEX]);
            // dump($e->getTrace());
        }
    }

    public function firstOrCreatePrefix($prefix): Prefix
    {
        return Prefix::firstOrCreate([
            'prefix' => $prefix,
        ]);
    }

    private function transformDates(&$date_of_birth, &$date_of_joining, $row)
    {
        try {
            if (is_string($date_of_birth) && preg_match('/^\d{2}-\d{2}-\d{2}$/', $date_of_birth)) {
                $date_of_birth = Carbon::createFromFormat('d-m-y', $date_of_birth)->format('Y-m-d');
                $date_of_birth = Carbon::parse($date_of_birth)->format('Y-m-d');

            } else {
                $date_of_birth = Carbon::createFromTimestamp(strtotime($date_of_birth))->toDateString();
            }

            if (is_string($date_of_joining) && preg_match('/^\d{2}-\d{2}-\d{2}$/', $date_of_joining)) {
                $date_of_joining = Carbon::createFromFormat('d-m-y', $date_of_joining)->format('Y-m-d');
                $date_of_joining = Carbon::parse($date_of_joining)->format('Y-m-d');

            } else {
                $date_of_joining = Carbon::createFromTimestamp(strtotime($date_of_birth))->toDateString();
            }
        } catch (Throwable $e) {
            throw new ParsingException('Transforming dates valid, ' . $e->getMessage());
        }
    }

    private function transformTime(&$time_of_birth, $row)
    {
        if (!is_string($time_of_birth)) {
            $time_of_birth = Date::excelToDateTimeObject($row[EmployeeHeader::TIME_OF_BIRTH_INDEX])->format('H:i:s');
        } else {
            $time_of_birth = DateTime::createFromFormat('g:i:s A', $time_of_birth);

            if ($time_of_birth !== false) {
                $time_of_birth = $time_of_birth->format('H:i:s');
            } else {
                throw new ParsingException("Failed to parse time!");
            }
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
