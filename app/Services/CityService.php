<?php

namespace App\Services;

use App\DataTransferObjects\CityDTO;
use App\Models\City;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class CityService
{
    public function getMany($paginate): LengthAwarePaginator
    {
        return City::orderBy('id')->paginate($paginate);
    }

    public function createOne(CityDTO $city_dto): ?City
    {
        return DB::transaction(function () use ($city_dto) {
            return City::create(
                $city_dto->nonNullToArray()
            )->fresh();
        });
    }

    public function getOne(int $id): ?City
    {
        return City::findOrFail($id);
    }

    public function updateOne(CityDTO $city_dto, City $city): City
    {
        return DB::transaction(function () use ($city_dto, $city) {
            return tap($city)->update(
                $city_dto->nonNullToArray()
            );
        });
    }

    public function updateOrCreate(CityDTO $city_dto): ?City
    {
        return DB::transaction(function () use ($city_dto) {
            return City::updateOrCreate(['name' => $city_dto->name],
                $city_dto->nonNullToArray())->fresh();
        });
    }

    public function deleteOne(City $city): bool
    {
        return DB::transaction(function () use ($city) {
            return $city->delete();
        });
    }
}