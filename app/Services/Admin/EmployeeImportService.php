<?php

namespace App\Services\Admin;

use App\Exceptions\ParsingException;
use App\Models\City;
use App\Models\County;
use App\Models\Prefix;
use App\Models\Region;
use App\Models\ZipCode;
use Carbon\Carbon;
use DateTime;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use Throwable;

class EmployeeImportService
{
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
            throw new ParsingException('Failed to parse date!, error message:' . $e->getMessage());
        }
    }

    public function transformTime($time)
    {
        if (!is_string($time)) {
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

    public function firstOrCreatePrefix($prefix): Prefix
    {
        return Prefix::firstOrCreate([
            'prefix' => $prefix,
        ])->fresh();
    }

    public function firstOrCreateRegion($name): Region
    {
        return Region::firstOrCreate([
            'name' => $name,
        ])->fresh();
    }

    public function firstOrCreateCounty($name, $region_id): County
    {
        return County::firstOrCreate([
            'name' => $name,
            'region_id' => $region_id,
        ])->fresh();
    }

    public function firstOrCreateCity($name, $county_id): City
    {
        return City::firstOrCreate([
            'name' => $name,
            'county_id' => $county_id,
        ])->fresh();
    }

    public function firstOrCreateZipCode($code, $city_id): ZipCode
    {
        return ZipCode::firstOrCreate([
            'code' => $code,
            'city_id' => $city_id,
        ])->fresh();
    }
}
