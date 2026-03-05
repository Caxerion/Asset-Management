<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FloorController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\SizeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StockBalanceController;
use App\Http\Controllers\ReceiptController;
use App\Http\Controllers\PickupController;
use App\Http\Controllers\InventoryTransactionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PersediaanController;
use App\Http\Controllers\MasterDataController;
use App\Http\Controllers\StockController;


Route::get('/', function () {
    return view('welcome');
});

// Auth routes
Route::get('/login', [UserController::class, 'loginForm'])->name('login')->middleware('guest');
Route::post('/login', [UserController::class, 'login'])->name('login.process')->middleware('guest');
Route::get('/register', [UserController::class, 'registerForm'])->name('register')->middleware('guest');
Route::post('/register', [UserController::class, 'register'])->name('register.process')->middleware('guest');
Route::post('/logout', [UserController::class, 'logout'])->name('logout')->middleware('auth');

// Protected routes
Route::middleware('auth')->group(function () {

    // Dashboard - accessible to all authenticated users
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Persediaan routes - only viewable, no create/store/destroy for now
    Route::get('/persediaan', [PersediaanController::class, 'index'])->name('persediaan.index');
    Route::post('/persediaan', [PersediaanController::class, 'store'])->name('persediaan.store');
    Route::get('/persediaan/{id}', [PersediaanController::class, 'show'])->name('persediaan.show');
    Route::delete('/persediaan/{id}', [PersediaanController::class, 'destroy'])->name('persediaan.destroy');
    Route::post('/persediaan/reset', [PersediaanController::class, 'reset'])->name('persediaan.reset');
    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
    Route::get('/stock/create', [StockController::class, 'create'])->name('stock.create');
    Route::post('/stock', [StockController::class, 'store'])->name('stock.store');
    Route::post('/stock/{id}/add', [StockController::class, 'add'])->name('stock.add');
    Route::get('/stock/{id}', [StockController::class, 'show'])->name('stock.show');
    Route::get('/stock/{id}/edit', [StockController::class, 'edit'])->name('stock.edit');


    // Semua resource default bisa diakses setelah login
    Route::resource('users', UserController::class);
    Route::resource('floors', FloorController::class);
    Route::resource('sizes', SizeController::class);
    Route::resource('inventory-transactions', InventoryTransactionController::class);

    // Admin only routes
    Route::middleware('role:admin')->group(function () {
        Route::resource('products', ProductController::class);
        Route::resource('categories', CategoryController::class);
        Route::get('/masterdata', [MasterDataController::class, 'index'])->name('masterdata.index');
    });

    // Peminjam only routes
    Route::middleware('role:peminjam')->group(function () {
        Route::resource('receipts', ReceiptController::class);
        Route::resource('pickups', PickupController::class);
    });

    Route::post('/stock-balances/reset-all', [StockBalanceController::class, 'resetAll'])->name('stock-balances.resetAll');

    // Read only routes
    Route::middleware('role:read')->group(function () {
        Route::resource('stock-balances', StockBalanceController::class);
    });

});
