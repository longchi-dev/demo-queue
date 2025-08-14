<?php

namespace App\Services\File;

class CsvFileService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function createCsvFile(string $uuid): string
    {
        $filePath = base_path(config('export.path') . "/user_{$uuid}_customers.csv");

        if (!is_dir(dirname($filePath))) {
            mkdir(dirname($filePath), 0755, true);
        }

        $handle = fopen($filePath, 'w');
        fputcsv($handle, ['uuid', 'name', 'email']);
        fclose($handle);

        return $filePath;
    }
}
