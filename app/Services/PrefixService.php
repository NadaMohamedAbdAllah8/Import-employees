<?php

namespace App\Services;

use App\DataTransferObjects\PrefixDTO;
use App\Models\Prefix;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class PrefixService
{
    public function getMany($paginate): LengthAwarePaginator
    {
        return Prefix::orderBy('id')->paginate($paginate);
    }

    public function createOne(PrefixDTO $prefix_dto): ?Prefix
    {
        return DB::transaction(function () use ($prefix_dto) {
            return Prefix::create(
                $prefix_dto->nonNullToArray()
            )->fresh();
        });
    }

    public function getOne(int $id): ?Prefix
    {
        return Prefix::findOrFail($id);
    }

    public function updateOne(PrefixDTO $prefix_dto, Prefix $prefix): Prefix
    {
        return DB::transaction(function () use ($prefix_dto, $prefix) {
            return tap($prefix)->update(
                $prefix_dto->nonNullToArray()
            );
        });
    }

    public function updateOrCreate(PrefixDTO $prefix_dto): ?Prefix
    {
        return DB::transaction(function () use ($prefix_dto) {
            return Prefix::updateOrCreate([
                'prefix' => $prefix_dto->prefix,
            ])->fresh();
        });
    }

    public function deleteOne(Prefix $prefix): bool
    {
        return DB::transaction(function () use ($prefix) {
            return $prefix->delete();
        });
    }
}
