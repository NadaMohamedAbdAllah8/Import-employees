<?php

namespace App\Imports;

use App\Constants\EmployeeHeader;
use App\DataTransferObjects\PrefixDTO;
use App\Models\Employee;
use App\Services\EmployeeImportService;
use App\Services\PrefixService;
use App\Validators\EmployeeValidator;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Throwable;

class EmployeeImport implements ShouldQueue, ToModel, WithBatchInserts, WithChunkReading, WithStartRow, WithUpserts
{
    use SkipsErrors, SkipsFailures;

    public function __construct(private EmployeeImportService $import_service,
        private PrefixService $prefix_service, ) {
    }

    /**
     * A sample of the imported document
     * Emp ID  Name Prefix  First Name  Middle Initial  Last Name  Gender  E Mail
     * 198429 Mrs.        Serafina       I              Bumgarner   F      serafina.bumgarner@exxonmobil.com
     * Date of Birth  Time of Birth  Age in Yrs.  Date of Joining  Age in Company (Years)
     * 9/21/1982     1:53:14 AM     34.87         02-01-08         9.49
     * Phone No.       Place Name  County        City      Zip    Region      User Name
     * 212-376-9125    Clymer      Chautauqua    Clymer    14724  Northeast   sibumgarner
     *
     * @throws ValidationException|ParsingException
     */
    public function model(array $row)
    {
        try {
            $emp_id = $row[EmployeeHeader::EMP_ID_INDEX];

            EmployeeValidator::validateEmployee($row);

            $date_of_birth = $this->import_service->transformDate($row[EmployeeHeader::DATE_OF_BIRTH_INDEX]);
            $date_of_joining = $this->import_service->transformDate($row[EmployeeHeader::DATE_OF_JOINING_INDEX]);
            $time_of_birth = $this->import_service->transformTime($row[EmployeeHeader::TIME_OF_BIRTH_INDEX]);

            $prefix_dto = new PrefixDTO($row[EmployeeHeader::NAME_PREFIX_INDEX]);
            $prefix_id = $this->prefix_service->firstOrCreate($prefix_dto)
                ->id;

            $zip_code_id = $this->import_service->getZipCode($row[EmployeeHeader::REGION_INDEX],
                $row[EmployeeHeader::COUNTY_INDEX], $row[EmployeeHeader::CITY_INDEX],
                $row[EmployeeHeader::ZIP_INDEX])
                ->id;

            return new Employee([
                'id' => $emp_id,
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
            Log::error('Error happened for ' . $emp_id . ' ,error: ' . $e->getMessage());
        }
    }

    /**
     * Defines the start row
     * 2 to skip the header
     */
    public function startRow(): int
    {
        return 2;
    }

    /**
     * Defines the unique column in used by the upsert
     *
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
