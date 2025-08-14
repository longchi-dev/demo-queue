<?php

namespace App\Console\Commands;

use App\Services\Customer\CustomerExportService;
use Illuminate\Console\Command;

class ExportCustomersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customers:export
                            {--lines=100 : Number of rows to export}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct(protected CustomerExportService $service)
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     * @throws \Throwable
     */
    public function handle(): void
    {
//        $filePath = storage_path('app/csv/customers.csv');
//        $lines = (int)$this->option('lines');
//
//        $this->info("Starting export {$lines} customer to {$filePath}");
//
//        $this->service->exportBatches($filePath, $lines);
//
//        $this->info("End of export to {$filePath}");
    }
}
