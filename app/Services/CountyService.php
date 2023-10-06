<?php

namespace App\Services;

use App\DataTransferObjects\CountyDTO;
use App\Models\County;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class CountyService
{
    public function getMany($paginate): LengthAwarePaginator
    {
        return County::orderBy('id')->paginate($paginate);
    }

    public function createOne(CountyDTO $county_dto): ?County
    {
        return DB::transaction(function () use ($county_dto) {
            return County::create(
                $county_dto->nonNullToArray()
            )->fresh();
        });
    }

    public function getOne(int $id): ?County
    {
        return County::findOrFail($id);
    }

    public function updateOne(CountyDTO $county_dto, County $county): County
    {
        return DB::transaction(function () use ($county_dto, $county) {
            return tap($county)->update(
                $county_dto->nonNullToArray()
            );
        });
    }

    public function updateOrCreate(CountyDTO $county_dto): ?County
    {
        return DB::transaction(function () use ($county_dto) {
            return County::updateOrCreate(['name' => $county_dto->name],
                $county_dto->nonNullToArray())->fresh();
        });
    }

    public function deleteOne(County $county): bool
    {
        return DB::transaction(function () use ($county) {
            return $county->delete();
        });
    }
}
