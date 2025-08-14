<?php

namespace App\Http\Controllers\Customer;

use App\Constants\Export\ExportCache;
use App\Http\Controllers\BaseController;
use App\Http\Controllers\Controller;
use App\Services\Customer\CustomerExportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CustomerExportController extends BaseController
{

    public function __construct(
        protected CustomerExportService $exportService,
        protected ExportCache           $exportCache
    )
    {
    }

    /**
     * @throws \Throwable
     */
    public function export(Request $request)
    {
        $uuid = (string)Str::uuid();
        $this->exportService->exportCustomerData($uuid);

        return $this->successResponse([
            'uuid' => $uuid,
        ],
            'Export data successfully exported.'
        );
    }

    public function getStatus(Request $request)
    {
        $uuid = $request->query('uuid');

        $cacheData = $this->exportCache->getCache($uuid);

        return $this->successResponse($cacheData);
    }
}
