<?php

namespace App\Services\Admin;

use App\DataTransferObjects\RegionDTO;
use App\Models\Region;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class RegionService
{
    public function getMany($paginate): LengthAwarePaginator
    {
        return Region::orderBy('id')->paginate($paginate);
    }

    public function createOne(RegionDTO $region_data): ?Region
    {
        return DB::transaction(function () use ($region_data) {
            return Region::create(
                $region_data->nonNullToArray()
            )->fresh();
        });
    }

    public function updateOrCreateOne($name): ?Region
    {
        return DB::transaction(function () use ($name) {
            return Region::updateOrCreate([
                'name' => $name,
            ])->fresh();
        });
    }

    public function getOne(int $id): ?Region
    {
        return Region::findOrFail($id);
    }

    public function updateOne(RegionDTO $region_data, Region $region): Region
    {
        return DB::transaction(function () use ($region, $region_data) {
            return tap($region)->update(
                $region_data->nonNullToArray()
            );
        });
    }

    public function deleteOne(Region $region): bool
    {
        return DB::transaction(function () use ($region) {
            return $region->delete();
        });
    }
}
