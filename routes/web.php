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

// Protected routes - requires authentication
Route::middleware('auth')->group(function () {

    // Dashboard - accessible to all authenticated users
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Persediaan - accessible to all authenticated users (basic read access)
    Route::get('/persediaan', [PersediaanController::class, 'index'])->name('persediaan.index');
    Route::get('/persediaan/{persediaan}', [PersediaanController::class, 'show'])->name('persediaan.show');
    
    // Stock - accessible to all authenticated users (basic read access)
    Route::get('/stock', [StockController::class, 'index'])->name('stock.index');
    Route::get('/stock/{stock}', [StockController::class, 'show'])->name('stock.show');
    
    // Stock Balances - accessible to all authenticated users (basic read access)
    Route::get('/stock-balances', [StockBalanceController::class, 'index'])->name('stock-balances.index');
    Route::get('/stock-balances/{stock_balance}', [StockBalanceController::class, 'show'])->name('stock-balances.show');

    // =====================================================
    // ADMIN ROUTES - Full access (create, update, delete, read)
    // =====================================================
    Route::middleware('role:admin')->group(function () {
        // User management
        Route::resource('users', UserController::class);
        
        // Master data management
        Route::resource('products', ProductController::class);
        Route::resource('categories', CategoryController::class);
        Route::resource('floors', FloorController::class);
        Route::resource('sizes', SizeController::class);
        Route::get('/masterdata', [MasterDataController::class, 'index'])->name('masterdata.index');
        
        // Master Data CRUD routes (Category)
        Route::post('/masterdata/category', [MasterDataController::class, 'storeCategory'])->name('masterdata.category.store');
        Route::put('/masterdata/category/{category}', [MasterDataController::class, 'updateCategory'])->name('masterdata.category.update');
        Route::delete('/masterdata/category/{category}', [MasterDataController::class, 'destroyCategory'])->name('masterdata.category.destroy');
        
        // Master Data CRUD routes (Floor)
        Route::post('/masterdata/floor', [MasterDataController::class, 'storeFloor'])->name('masterdata.floor.store');
        Route::put('/masterdata/floor/{floor}', [MasterDataController::class, 'updateFloor'])->name('masterdata.floor.update');
        Route::delete('/masterdata/floor/{floor}', [MasterDataController::class, 'destroyFloor'])->name('masterdata.floor.destroy');
        
        // Master Data CRUD routes (Product)
        Route::post('/masterdata/product', [MasterDataController::class, 'storeProduct'])->name('masterdata.product.store');
        Route::put('/masterdata/product/{product}', [MasterDataController::class, 'updateProduct'])->name('masterdata.product.update');
        Route::delete('/masterdata/product/{product}', [MasterDataController::class, 'destroyProduct'])->name('masterdata.product.destroy');
        
        // Master Data CRUD routes (Size)
        Route::post('/masterdata/size', [MasterDataController::class, 'storeSize'])->name('masterdata.size.store');
        Route::put('/masterdata/size/{size}', [MasterDataController::class, 'updateSize'])->name('masterdata.size.update');
        Route::delete('/masterdata/size/{size}', [MasterDataController::class, 'destroySize'])->name('masterdata.size.destroy');
        
        // Transaction management
        Route::resource('receipts', ReceiptController::class);
        Route::resource('pickups', PickupController::class);
        Route::resource('inventory-transactions', InventoryTransactionController::class);
        
        // Stock management - full access (except index/show which are already defined above)
        Route::resource('stock', StockController::class)->except(['index', 'show']);
        Route::post('/stock/{id}/add', [StockController::class, 'add'])->name('stock.add');
        Route::post('/stock/reset', [StockController::class, 'reset'])->name('stock.reset');
        
        // Persediaan - full access (except index/show which are already defined above)
        Route::resource('persediaan', PersediaanController::class)->except(['index', 'show']);
        Route::post('/persediaan/reset', [PersediaanController::class, 'reset'])->name('persediaan.reset');
        
        // Stock balances - full access (except index/show which are already defined above)
        Route::resource('stock-balances', StockBalanceController::class)->except(['index', 'show']);
        Route::post('/stock-balances/reset-all', [StockBalanceController::class, 'resetAll'])->name('stock-balances.resetAll');
    });

    // =====================================================
    // READ-ONLY ROUTES - Only read access (view/index/show) - for users with 'read' role
    // Note: Basic read access is already available to all authenticated users above.
    // This section can be used for additional read-only features specific to 'read' role.
    // =====================================================
    Route::middleware('role:read')->group(function () {
        // Additional read-only features can be added here if needed
    });

});
