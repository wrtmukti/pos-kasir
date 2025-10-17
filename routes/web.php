<?php

use App\Http\Controllers\DiscountController;
use App\Models\Discount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;


Route::get('/', [App\Http\Controllers\GuestController::class, 'index'])->name('home');
Route::post('/order/store', [App\Http\Controllers\GuestController::class, 'store']);
Route::post('/orders', [App\Http\Controllers\GuestController::class, 'checkout']);
Route::get('/checkout/review', [App\Http\Controllers\GuestController::class, 'review'])->name('checkout.review'); // halaman isi note & pembayaran
Route::post('/submits', [App\Http\Controllers\GuestController::class, 'submit'])->name('checkout.submit'); // simpan ke DB
Route::get('/orders/status', [App\Http\Controllers\GuestController::class, 'orderStatus'])->name('order.status');
Route::post('/check-voucher', [App\Http\Controllers\GuestController::class, 'check']);


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
    Route::put('/product/{id}', [App\Http\Controllers\ProductController::class, 'update'])->name('product.update');
    Route::delete('/product/{id}', [App\Http\Controllers\ProductController::class, 'destroy']);

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

        Route::get('/voucher', [App\Http\Controllers\VoucherController::class, 'index'])->name('voucher.index');
        Route::get('/voucher/create', [App\Http\Controllers\VoucherController::class, 'create'])->name('voucher.create');
        Route::post('/voucher/store', [App\Http\Controllers\VoucherController::class, 'store'])->name('voucher.store');
        Route::get('/voucher/edit/{id}', [App\Http\Controllers\VoucherController::class, 'edit'])->name('voucher.edit');
        Route::post('/voucher/update/{id}', [App\Http\Controllers\VoucherController::class, 'update'])->name('voucher.update');
        Route::post('/voucher/delete/{id}', [App\Http\Controllers\VoucherController::class, 'destroy'])->name('voucher.destroy');

        Route::get('/discount', [DiscountController::class, 'index'])->name('discount.index');          // daftar semua diskon
        Route::get('/discount/create', [DiscountController::class, 'create'])->name('discount.create'); // form tambah
        Route::post('/discount', [DiscountController::class, 'store'])->name('discount.store');         // simpan data baru
        Route::get('/discount/{diskon}/edit', [DiscountController::class, 'edit'])->name('discount.edit'); // form edit
        Route::put('/discount/{diskon}', [DiscountController::class, 'update'])->name('discount.update');  // update data
        Route::delete('/discount/{diskon}', [DiscountController::class, 'destroy'])->name('discount.destroy'); // hapus data


        //deva
        Route::get('/report/category', [App\Http\Controllers\SalesSummaryController::class, 'categoryReport']);
        Route::get('/report/best-products', [App\Http\Controllers\SalesSummaryController::class, 'bestSellingProducts']);
        //deva end

        Route::get('/logs', [App\Http\Controllers\ActivityLogController::class, 'index'])->name('logs.index');
        Route::prefix('reports')->group(function () {
            Route::get('/', [App\Http\Controllers\SalesSummaryController::class, 'index'])->name('reports.index');
            Route::get('/daily', [App\Http\Controllers\SalesSummaryController::class, 'daily'])->name('reports.daily');
            Route::get('/weekly', [App\Http\Controllers\SalesSummaryController::class, 'weekly'])->name('reports.weekly');
            Route::get('/monthly', [App\Http\Controllers\SalesSummaryController::class, 'monthly'])->name('reports.monthly');
            Route::get('/yearly', [App\Http\Controllers\SalesSummaryController::class, 'yearly'])->name('reports.yearly');
        });
    });
});
