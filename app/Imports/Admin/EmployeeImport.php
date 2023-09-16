<?php

namespace App\Imports\Admin;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\SkipsErrors;
use Maatwebsite\Excel\Concerns\SkipsFailures;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeeImport implements ToCollection, WithHeadingRow, WithChunkReading
{
    use Importable, SkipsFailures, SkipsErrors;

    public function __construct(
    ) {
    }

    public function collection(Collection $collection)
    {
    }

    /**
     * Gets the chunk size for importing data.
     *
     * @return int The chunk size.
     */
    public function chunkSize(): int
    {
        return env('IMPORT_CHUNK_SIZE', 500);
    }
}
