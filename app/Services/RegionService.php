<?php

namespace App\Services;

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

    public function createOne(RegionDTO $region_dto): ?Region
    {
        return DB::transaction(function () use ($region_dto) {
            return Region::create(
                $region_dto->nonNullToArray()
            )->fresh();
        });
    }

    public function getOne(int $id): ?Region
    {
        return Region::findOrFail($id);
    }

    public function updateOne(RegionDTO $region_dto, Region $region): Region
    {
        return DB::transaction(function () use ($region_dto, $region) {
            return tap($region)->update(
                $region_dto->nonNullToArray()
            );
        });
    }

    public function updateOrCreate(RegionDTO $region_dto): ?Region
    {
        return DB::transaction(function () use ($region_dto) {
            return Region::updateOrCreate([
                'name' => $region_dto->name,
            ])->fresh();
        });
    }

    public function deleteOne(Region $region): bool
    {
        return DB::transaction(function () use ($region) {
            return $region->delete();
        });
    }
}
