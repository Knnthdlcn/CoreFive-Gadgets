<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductStockController;
use App\Http\Controllers\PhilippinesAddressController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/products/{id}/stock', [ProductStockController::class, 'show']);

Route::prefix('ph')->group(function () {
    Route::get('/regions', [PhilippinesAddressController::class, 'regions']);
    Route::get('/regions/{regionCode}/provinces', [PhilippinesAddressController::class, 'provinces']);
    Route::get('/provinces/{provinceCode}/cities', [PhilippinesAddressController::class, 'cities']);
    Route::get('/cities/{cityCode}/barangays', [PhilippinesAddressController::class, 'barangays']);
});
