<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/export/customers', [\App\Http\Controllers\Customer\CustomerExportController::class, 'export']);
Route::get('/export/customers/status', [\App\Http\Controllers\Customer\CustomerExportController::class, 'getStatus']);


