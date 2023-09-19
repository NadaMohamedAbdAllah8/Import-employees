<?php

namespace App\Services\Admin;

use App\Models\City;
use App\Models\County;
use App\Models\Prefix;
use App\Models\Region;
use App\Models\ZipCode;

class EmployeeImportService
{
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
