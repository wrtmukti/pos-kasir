<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', [App\Http\Controllers\GuestController::class, 'index']);
Route::post('/order/store', [App\Http\Controllers\GuestController::class, 'store']);
Route::get('/order/status', [App\Http\Controllers\GuestController::class, 'status']);
Route::get('/category/{id}', [App\Http\Controllers\GuestController::class, 'category']);
Route::post('/search', [App\Http\Controllers\GuestController::class, 'search']);


Auth::routes();

Route::prefix('admin')->middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\AdminController::class, 'index']);

    Route::get('/table', [App\Http\Controllers\AdminController::class, 'table']);
    Route::put('/table/{id}', [App\Http\Controllers\AdminController::class, 'updateTable']);

    Route::get('/order/online', [App\Http\Controllers\OrderController::class, 'online']);
    Route::get('/order/manual', [App\Http\Controllers\OrderController::class, 'manual']);
    Route::get('/order/online/{id}', [App\Http\Controllers\OrderController::class, 'onlineShow']);
    Route::get('/order/manual/{id}', [App\Http\Controllers\OrderController::class, 'manualShow']);
    Route::get('/order/create', [App\Http\Controllers\OrderController::class, 'create']);
    Route::post('/order', [App\Http\Controllers\OrderController::class, 'store']);
    Route::patch('/order/{id}', [App\Http\Controllers\OrderController::class, 'update']);
    Route::delete('/order/delete/{id}', [App\Http\Controllers\OrderController::class, 'operatorDestroy']);


    Route::get('/transaction', [App\Http\Controllers\TransactionController::class, 'index']);
    Route::post('/transaction/payment', [App\Http\Controllers\TransactionController::class, 'payment']);
    Route::get('/transaction/invoice/online/{id}', [App\Http\Controllers\TransactionController::class, 'invoiceOnline']);
    Route::get('/transaction/invoice/manual/{id}', [App\Http\Controllers\TransactionController::class, 'invoiceManual']);
    Route::get('/transaction/summary/{id}', [App\Http\Controllers\TransactionController::class, 'summary']);
    Route::get('/transaction/summary/show/{date}', [App\Http\Controllers\TransactionController::class, 'summaryShow']);
    Route::get('/transaction/create', [App\Http\Controllers\TransactionController::class, 'create']);
    Route::post('/transaction', [App\Http\Controllers\TransactionController::class, 'store']);
    Route::get('/transaction/{id}', [App\Http\Controllers\TransactionController::class, 'show']);
    Route::get('/transaction/report/{id}', [App\Http\Controllers\TransactionController::class, 'sale']);
    Route::get('/transaction/report/sale/{date}', [App\Http\Controllers\TransactionController::class, 'saleShow']);
    Route::delete('/transaction/delete/{id}', [App\Http\Controllers\TransactionController::class, 'operatorDestroy']);


    Route::get('/product/food', [App\Http\Controllers\ProductController::class, 'food']);
    Route::get('/product/drink', [App\Http\Controllers\ProductController::class, 'drink']);
    Route::put('/product/active/{id}', [App\Http\Controllers\ProductController::class, 'active']);
    Route::post('/product/category/create', [App\Http\Controllers\ProductController::class, 'categoryCreate']);
    Route::post('/product/category', [App\Http\Controllers\ProductController::class, 'categoryChoose']);
    Route::post('/product/category/store', [App\Http\Controllers\ProductController::class, 'categoryStore']);
    Route::post('/product/create', [App\Http\Controllers\ProductController::class, 'create']);
    Route::post('/product', [App\Http\Controllers\ProductController::class, 'store']);
    Route::delete('/product/{id}', [App\Http\Controllers\ProductController::class, 'store']);

    Route::get('/stock', [App\Http\Controllers\StockController::class, 'index']);
    Route::get('/stock/create', [App\Http\Controllers\StockController::class, 'create']);
    Route::post('/stock', [App\Http\Controllers\StockController::class, 'store']);

    Route::prefix('operator')->middleware('operator')->group(function () {
        Route::get('/report/{id}', [App\Http\Controllers\ReportController::class, 'sale']);
        Route::get('/report/sale/{date}', [App\Http\Controllers\ReportController::class, 'saleShow']);
        Route::get('/customer', [App\Http\Controllers\ReportController::class, 'customer']);
        Route::get('/employee', [App\Http\Controllers\OperatorController::class, 'employee']);
        Route::delete('/employee/delete/{id}', [App\Http\Controllers\OperatorController::class, 'employeeDestroy']);
        Route::get('/stock/edit/{id}', [App\Http\Controllers\StockController::class, 'operatorEdit']);
        Route::put('/stock/update/{id}', [App\Http\Controllers\StockController::class, 'operatorUpdate']);
        Route::delete('/stock/delete/{id}', [App\Http\Controllers\StockController::class, 'operatorDestroy']);
    });
});
