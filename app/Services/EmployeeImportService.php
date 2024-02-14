<?php

namespace App\Services;

use App\DataTransferObjects\CityDTO;
use App\DataTransferObjects\CountyDTO;
use App\DataTransferObjects\RegionDTO;
use App\DataTransferObjects\ZipCodeDTO;
use App\Exceptions\ParsingException;
use App\Models\ZipCode;
use Carbon\Carbon;
use DateTime;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Throwable;

class EmployeeImportService
{
    public function __construct(private RegionService $region_service,
        private CountyService $county_service,
        private CityService $city_service,
        private ZipCodeService $zip_code_service)
    {
    }

    public function transformDate($date)
    {
        try {
            if (is_string($date)
                && preg_match('/^\d{2}-\d{2}-\d{2}$/', $date)
            ) {
                $date = Carbon::createFromFormat('d-m-y', $date)->format('Y-m-d');
                $date = Carbon::parse($date)->format('Y-m-d');
            } elseif (is_string($date)) {
                $date = Carbon::createFromTimestamp(strtotime($date))->toDateString();
            } else {
                $date = Date::excelToDateTimeObject($date)->format('Y-m-d');
            }

            return $date;
        } catch (Throwable $e) {
            throw new ParsingException('Failed to parse date!, error message:'.$e->getMessage());
        }
    }

    public function transformTime($time)
    {
        if (! is_string($time)) {
            $time = Date::excelToDateTimeObject($time)->format('H:i:s');
        } else {
            $time = DateTime::createFromFormat('g:i:s A', $time);

            if ($time !== false) {
                $time = $time->format('H:i:s');
            } else {
                throw new ParsingException('Failed to parse time!');
            }
        }

        return $time;
    }

    public function getZipCode($region_name, $county_name, $city_name, $zip_code): ZipCode
    {
        $region_dto = new RegionDTO($region_name);
        $region_id = $this->region_service->firstOrCreate($region_dto)->id;

        $county_dto = new CountyDTO($county_name, $region_id);
        $county_id = $this->county_service->firstOrCreate($county_dto)->id;

        $city_dto = new CityDTO($city_name, $county_id);
        $city_id = $this->city_service->firstOrCreate($city_dto)->id;

        $zip_code_dto = new ZipCodeDTO($zip_code, $city_id);

        return $this->zip_code_service->firstOrCreate($zip_code_dto);
    }
}
