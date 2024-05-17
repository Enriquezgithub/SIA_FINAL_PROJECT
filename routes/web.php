<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Route::get('/', function () {
//     return Inertia::render('Welcome', [
//         'canLogin' => Route::has('login'),
//         'canRegister' => Route::has('register'),
//         'laravelVersion' => Application::VERSION,
//         'phpVersion' => PHP_VERSION,
//     ]);
// });

// Route::get('/dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::middleware('auth')->group(function () {
//     Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
//     Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
//     Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
// });
Route::resource('/', DashboardController::class);

// Route::get('/dashboard', function () {
//     return inertia('Landing');
// });



Route::get('/products/pdf', [ProductController::class, 'show']);
Route::get('/dowload/csv', [ProductController::class, 'generateCSV']);
// Route::post('/products/import', [ProductController::class, 'import']);
Route::post('/products/import', [ProductController::class, 'import'])->name('products.import');
Route::resource('/products', ProductController::class);

// Route::get('/scans', [ProductController::class, 'scan']);

Route::resource('/categories', CategoryController::class);

// Route::get('/products/pdf', [ProductController::class, 'show']);

Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
Route::post('/customers/import', [CustomerController::class, 'import'])->name('customers.import');
Route::get('/customers/export', [CustomerController::class, 'export'])->name('customers.export');


require __DIR__ . '/auth.php';
