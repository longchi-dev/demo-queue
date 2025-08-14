<?php

namespace App\Repositories\Customer;

use Illuminate\Support\Facades\DB;

class CustomerExportRepository
{
    public function getAllCustomers(): \Illuminate\Support\Collection
    {
        return DB::table('customers')->get();
    }

    public function getCustomersWithOffsetLimit(int $offset, int $limit): \Illuminate\Support\Collection
    {
        return DB::table('customers')
            ->offset($offset)
            ->limit($limit)
            ->get();
    }
}
