<?php

use App\Http\Controllers\Vendor\VendorController;
use App\Http\Controllers\Customer\CustomerController;
use App\Models\User;
use App\Models\Store;
use App\Models\Product; 
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

// الصفحة الرئيسية
Route::get('/', function () {
    $products = Product::latest()->take(8)->get(); 
    return view('welcome', compact('products'));
});

// مسارات التاجر
Route::prefix('vendor')->middleware(['auth', 'role:vendor'])->group(function () {
    Route::get('/dashboard', [VendorController::class, 'index'])->name('vendor.dashboard');
    Route::get('/products', [VendorController::class, 'products'])->name('vendor.products');
    Route::get('/products/create', [VendorController::class, 'createProduct'])->name('vendor.products.create');
    Route::post('/products/store', [VendorController::class, 'storeProduct'])->name('vendor.products.store');
    Route::get('/products/{product}/edit', [VendorController::class, 'editProduct'])->name('vendor.products.edit');
    Route::put('/products/{product}', [VendorController::class, 'updateProduct'])->name('vendor.products.update');
    Route::delete('/products/{product}', [VendorController::class, 'destroyProduct'])->name('vendor.products.destroy');
});

// مسارات العميل
Route::prefix('customer')->middleware(['auth', 'role:customer'])->group(function () {
    Route::get('/dashboard', [CustomerController::class, 'index'])->name('customer.dashboard');
});

// مسار تسجيل الدخول
Route::get('/login', function () {
    return "صفحة تسجيل الدخول ستكون هنا لاحقاً.";
})->name('login');

// مسار الخروج
Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->name('logout');

// مسار الدخول كتاجر تجريبي (مع تحويل تلقائي)
Route::get('/login-as-vendor', function () {
    $user = User::firstOrCreate(
        ['email' => 'vendor@sham.com'],
        ['name' => 'تاجر تجريبي', 'password' => Hash::make('password'), 'role' => 'vendor']
    );

    Store::firstOrCreate(
        ['user_id' => $user->id],
        ['name' => 'متجر شام التجريبي']
    );

    Auth::login($user);
    return redirect()->route('vendor.products');
});

// مسار الدخول كعميل تجريبي (مع تحويل تلقائي)
Route::get('/login-as-customer', function () {
    $user = User::firstOrCreate(
        ['email' => 'customer@sham.com'],
        ['name' => 'عميل تجريبي', 'password' => Hash::make('password'), 'role' => 'customer']
    );

    Auth::login($user);
    return redirect()->route('customer.dashboard');
});