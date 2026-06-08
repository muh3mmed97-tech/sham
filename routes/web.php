<?php

use App\Http\Controllers\Vendor\VendorController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\QuestionController;
use App\Models\User;
use App\Models\Store;
use App\Models\Product; 
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

// 1. مسارات تسجيل الدخول السريع
Route::get('/login-as-vendor', function () {
    $user = User::updateOrCreate(['email' => 'vendor@sham.com'], [
        'name' => 'تاجر تجريبي', 'password' => Hash::make('password'), 'role' => 'vendor'
    ]);
    Store::firstOrCreate(['user_id' => $user->id], ['name' => 'متجر شام التجريبي']);
    Auth::login($user);
    return redirect()->route('vendor.dashboard');
});

Route::get('/login-as-customer', function () {
    $user = User::updateOrCreate(['email' => 'customer@sham.com'], [
        'name' => 'عميل تجريبي', 'password' => Hash::make('password'), 'role' => 'customer'
    ]);
    Auth::login($user);
    return redirect()->route('customer.dashboard');
});

Route::get('/login', function () { 
    return "أنت الآن في صفحة تسجيل الدخول."; 
})->name('login');

// 2. المسارات المحمية
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', function () { Auth::logout(); return redirect('/'); })->name('logout');

    // مسارات المحفظة
    Route::get('/wallet', [WalletController::class, 'index'])->name('wallet.action');
    Route::post('/wallet/charge', [WalletController::class, 'charge'])->name('wallet.charge');
    Route::post('/wallet/withdraw', [WalletController::class, 'withdraw'])->name('wallet.withdraw');

    // مسارات السلة
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::patch('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
    Route::post('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout');

    // مسارات المفضلة
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/toggle/{id}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    
    // مسار التقييمات
    Route::post('/reviews/{productId}', [ReviewController::class, 'store'])->name('reviews.store');

    // مسارات الأسئلة والأجوبة
    Route::post('/questions/{productId}', [QuestionController::class, 'store'])->name('questions.store');
    Route::post('/questions/{id}/answer', [QuestionController::class, 'answer'])->name('questions.answer');

    // مسارات التاجر
    Route::prefix('vendor')->middleware('role:vendor')->group(function () {
        Route::get('/dashboard', [VendorController::class, 'index'])->name('vendor.dashboard');
        Route::get('/products', [VendorController::class, 'products'])->name('vendor.products');
        Route::get('/orders', [VendorController::class, 'orders'])->name('vendor.orders');
        
        // المسار الجديد لتحديث حالة الطلب
        Route::put('/orders/{id}/update-status', [VendorController::class, 'updateStatus'])->name('vendor.orders.updateStatus');
        
        Route::put('/orders/{id}', [VendorController::class, 'updateOrder'])->name('vendor.orders.update');
        Route::get('/products/create', [VendorController::class, 'createProduct'])->name('vendor.products.create');
        Route::post('/products', [VendorController::class, 'storeProduct'])->name('vendor.products.store');
        Route::get('/products/{id}/edit', [VendorController::class, 'editProduct'])->name('vendor.products.edit');
        Route::put('/products/{id}', [VendorController::class, 'updateProduct'])->name('vendor.products.update');
        Route::delete('/products/{id}', [VendorController::class, 'destroyProduct'])->name('vendor.products.destroy');
    });

    // مسارات العميل
    Route::prefix('customer')->middleware('role:customer')->group(function () {
        Route::get('/dashboard', [CustomerController::class, 'index'])->name('customer.dashboard');
        Route::get('/orders', [CustomerController::class, 'orders'])->name('customer.orders'); 
        Route::get('/invoice/{id}', [CustomerController::class, 'invoice'])->name('customer.invoice');
    });
});

// 3. مسار تفاصيل المنتج
Route::get('/product/{id}', function ($id) {
    $product = Product::with(['reviews.user', 'questions.user']) 
        ->findOrFail($id);
    return view('product.show', compact('product'));
})->name('product.show');

// 4. المسار العام
Route::get('/{category?}', function (Request $request, $categoryId = null) {
    if (Auth::check()) {
        if (Auth::user()->role == 'vendor') return redirect()->route('vendor.dashboard');
        if (Auth::user()->role == 'customer') return redirect()->route('customer.dashboard');
    }

    $products = Product::query()->with('reviews')->latest();
    if ($categoryId) { $products->where('category_id', $categoryId); }
    if ($request->filled('search')) { $products->where('name', 'like', '%' . $request->search . '%'); }
    
    return view('welcome', ['products' => $products->get()]);
})->name('home');