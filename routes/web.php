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
        
        // =====================================================
        // Separate Master Data Routes
        // =====================================================
        
        // Kategori (Category) Routes
        Route::get('/masterdata/kategori', [CategoryController::class, 'index'])->name('masterdata.kategori.index');
        Route::post('/masterdata/kategori', [CategoryController::class, 'store'])->name('masterdata.kategori.store');
        Route::put('/masterdata/kategori/{category}', [CategoryController::class, 'update'])->name('masterdata.kategori.update');
        Route::delete('/masterdata/kategori/{category}', [CategoryController::class, 'destroy'])->name('masterdata.kategori.destroy');
        
        // Ukuran (Size) Routes
        Route::get('/masterdata/ukuran', [SizeController::class, 'index'])->name('masterdata.ukuran.index');
        Route::post('/masterdata/ukuran', [SizeController::class, 'store'])->name('masterdata.ukuran.store');
        Route::put('/masterdata/ukuran/{size}', [SizeController::class, 'update'])->name('masterdata.ukuran.update');
        Route::delete('/masterdata/ukuran/{size}', [SizeController::class, 'destroy'])->name('masterdata.ukuran.destroy');
        
        // Lantai (Floor) Routes
        Route::get('/masterdata/lantai', [FloorController::class, 'index'])->name('masterdata.lantai.index');
        Route::post('/masterdata/lantai', [FloorController::class, 'store'])->name('masterdata.lantai.store');
        Route::put('/masterdata/lantai/{floor}', [FloorController::class, 'update'])->name('masterdata.lantai.update');
        Route::delete('/masterdata/lantai/{floor}', [FloorController::class, 'destroy'])->name('masterdata.lantai.destroy');
        
        // Produk (Product) Routes
        Route::get('/masterdata/produk', [ProductController::class, 'index'])->name('masterdata.produk.index');
        Route::post('/masterdata/produk', [ProductController::class, 'store'])->name('masterdata.produk.store');
        Route::put('/masterdata/produk/{product}', [ProductController::class, 'update'])->name('masterdata.produk.update');
        Route::delete('/masterdata/produk/{product}', [ProductController::class, 'destroy'])->name('masterdata.produk.destroy');
        
        // Legacy masterdata index (for backward compatibility)
        Route::get('/masterdata', [MasterDataController::class, 'index'])->name('masterdata.index');
        
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
