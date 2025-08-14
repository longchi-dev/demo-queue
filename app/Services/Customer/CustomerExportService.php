<?php

namespace App\Services\Customer;

use App\Constants\Export\ExportCache;
use App\Constants\Keys\CacheKey;
use App\Jobs\ExportCustomersBatchJob;
use App\Repositories\Customer\CustomerExportRepository;
use App\Services\File\CsvFileService;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Cache;

class CustomerExportService
{
    protected int $batchSize;
    /**
     * Create a new class instance.
     */
    public function __construct(
        protected CustomerExportRepository $repository,
        protected CsvFileService $csvFileService,
        protected ExportCache $exportCache
    )
    {
        $this->batchSize = config('export.batch_size');
    }

    /**
     * @throws \Throwable
     */
    public function exportCustomerData(string $uuid): string
    {
        $filePath = $this->csvFileService->createCsvFile($uuid);

        $this->exportCache->init($uuid);

        ExportCustomersBatchJob::dispatch(
            $uuid,
            $filePath,
            0,
            $this->batchSize
        );

        return $filePath;
    }
}
