<?php

namespace App\Jobs;

use App\Repositories\Customer\CustomerExportRepository;
use Illuminate\Bus\Batchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;

class ExportCustomersBatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, Batchable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        protected string $filePath,
        protected int $offset,
        protected int $limit,
        protected int $lines
    )
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // check lines va limit -> set batch lai
        // => limit = min(batch, lines)

        $customers = app(CustomerExportRepository::class)
            ->getCustomersWithOffsetLimit(
                $this->offset,
                $this->limit
            );

        $handle = fopen($this->filePath, 'a');

        foreach ($customers as $customer) {
            fputcsv($handle, [
                $customer->id,
                $customer->name,
                $customer->email
            ]);
        }

        fclose($handle);

        if($this->offset + $this->limit < $this->lines) {
            ExportCustomersBatchJob::dispatch(
                $this->filePath,
                $this->offset + $this->limit,
                $this->limit,
                $this->lines
            );
        }
    }
}
