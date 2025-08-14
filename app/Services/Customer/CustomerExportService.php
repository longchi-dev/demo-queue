<?php

namespace App\Services\Customer;

use App\Jobs\ExportCustomersBatchJob;
use App\Repositories\Customer\CustomerExportRepository;
use Illuminate\Support\Facades\Bus;

class CustomerExportService
{
    protected int $batchSize;
    /**
     * Create a new class instance.
     */
    public function __construct(protected CustomerExportRepository $repository)
    {
        $this->batchSize = config('export.batch_size');
    }

    /**
     * Export toan bo du lieu khong chia batch
     */
    public function exportAll(string $filePath): void
    {
        $customers = $this->repository->getAllCustomers();

        $handle = fopen($filePath, 'w');

        fputcsv($handle, ['uuid', 'name', 'email']);

        foreach ($customers as $customer) {
            fputcsv($handle, [$customer->uuid, $customer->name, $customer->email]);
        }

        fclose($handle);
    }

    /**
     * @throws \Throwable
     */
    public function exportBatches(string $filePath, int $lines): void
    {
        if (!is_dir(dirname($filePath))) {
            mkdir(dirname($filePath), 0755, true);
        }
        $handle = fopen($filePath, 'w');
        fputcsv($handle, ['uuid', 'name', 'email']);
        fclose($handle);

        ExportCustomersBatchJob::dispatch($filePath, 0, $this->batchSize, $lines);
    }
}
