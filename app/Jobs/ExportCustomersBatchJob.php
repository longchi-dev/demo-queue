<?php

namespace App\Jobs;

use App\Constants\Export\ExportCache;
use App\Repositories\Customer\CustomerExportRepository;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;

class ExportCustomersBatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, Batchable;

    protected ExportCache $exportCache;
    /**
     * Create a new job instance.
     */
    public function __construct(
        protected string $uuid,
        protected string $filePath,
        protected int    $offset,
        protected int    $limit
    )
    {
        $this->exportCache = new ExportCache();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $customers = app(CustomerExportRepository::class)
            ->getCustomersWithOffsetLimit(
                $this->offset,
                $this->limit
            );

        if (empty($customers)) {
            return;
        }

        $handle = fopen($this->filePath, 'a');

        foreach ($customers as $customer) {
            fputcsv($handle, [
                $customer->id,
                $customer->name,
                $customer->email
            ]);
        }

        fclose($handle);

        $currentTotal = count($customers) + $this->offset;

        $this->exportCache->start(
            $this->uuid,
            $currentTotal
        );

        $total = app(CustomerExportRepository::class)->countAllCustomers();

        if ($this->offset + $this->limit < $total) {
            ExportCustomersBatchJob::dispatch(
                $this->uuid,
                $this->filePath,
                $this->offset + $this->limit,
                $this->limit,
            );
        } else {
            $url = asset("storage/csv/user_{$this->uuid}_customers.csv");
            $this->exportCache->done(
                $this->uuid,
                $url,
                $currentTotal
            );
        }
    }
}
