<?php

namespace App\Services;

use App\DataTransferObjects\ZipCodeDTO;
use App\Models\ZipCode;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

class ZipCodeService
{
    public function getMany($paginate): LengthAwarePaginator
    {
        return ZipCode::orderBy('id')->paginate($paginate);
    }

    public function createOne(ZipCodeDTO $zip_code_dto): ?ZipCode
    {
        return DB::transaction(function () use ($zip_code_dto) {
            return ZipCode::create(
                $zip_code_dto->nonNullToArray()
            )->fresh();
        });
    }

    public function getOne(int $id): ?ZipCode
    {
        return ZipCode::findOrFail($id);
    }

    public function updateOne(ZipCodeDTO $zip_code_dto, ZipCode $zip_code): ZipCode
    {
        return DB::transaction(function () use ($zip_code_dto, $zip_code) {
            return tap($zip_code)->update(
                $zip_code_dto->nonNullToArray()
            );
        });
    }

    public function updateOrCreateOne(ZipCodeDTO $zip_code_dto): ?ZipCode
    {
        return DB::transaction(function () use ($zip_code_dto) {
            return ZipCode::updateOrCreate([
                'zip_code' => $zip_code_dto->code,
            ])->fresh();
        });
    }

    public function deleteOne(ZipCode $zip_code): bool
    {
        return DB::transaction(function () use ($zip_code) {
            return $zip_code->delete();
        });
    }
}
