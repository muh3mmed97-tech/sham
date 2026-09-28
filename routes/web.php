<?php

use App\Http\Controllers\Vendor\VendorController;
use App\Http\Controllers\Customer\CustomerController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\StoreReviewController;
use App\Http\Controllers\QuestionController;
use App\Models\User;
use App\Models\Store;
use App\Models\Product; 
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

// 0. مسارات المصادقة العامة (تسجيل عميل، تسجيل تاجر، وتسجيل الدخول)
Route::middleware('guest')->group(function () {
    // صفحة ووظيفة تسجيل الدخول
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/login', function (Request $request) {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            if (Auth::user()->role == 'vendor') {
                return redirect()->route('vendor.dashboard');
            }
            return redirect()->route('customer.dashboard');
        }

        return back()->withErrors([
            'email' => 'البريد الإلكتروني أو كلمة المرور غير صحيحة.',
        ]);
    })->name('login.submit');

    // صفحة ووظيفة تسجيل عميل جديد (مع حقل الهاتف)
    Route::get('/register/customer', function () {
        return view('auth.register-customer');
    })->name('register.customer');

    Route::post('/register/customer', function (Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20|unique:users,phone',
            'password' => 'required|string|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role' => 'customer',
        ]);

        Auth::login($user);
        return redirect()->route('customer.dashboard');
    })->name('register.customer.submit');

    // صفحة ووظيفة تسجيل تاجر جديد
    Route::get('/register/vendor', function () {
        return view('auth.register-vendor');
    })->name('register.vendor');

    Route::post('/register/vendor', function (Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
            'store_name' => 'required|string|max:255|unique:stores,name',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'vendor',
        ]);

        Store::create([
            'user_id' => $user->id,
            'name' => $request->store_name,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        Auth::login($user);
        return redirect()->route('vendor.dashboard');
    })->name('register.vendor.submit');
});

// 1. مسارات تسجيل الدخول السريع (للاختبار والتطوير)
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

// 2. المسارات المحمية
Route::middleware(['auth'])->group(function () {
    Route::post('/logout', function () { Auth::logout(); return redirect('/'); })->name('logout');

    // مسارات الملف الشخصي ومعلومات الحساب العامة
    Route::get('/profile', function () {
        return view('customer.profile');
    })->name('customer.profile');

    Route::put('/profile/update', function (Request $request) {
        $user = Auth::user();
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);

        $user->update([
            'name' => $request->name,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->back()->with('success', 'تم تحديث معلومات الحساب بنجاح.');
    })->name('customer.profile.update');

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
    
    // مسار تقييمات المنتجات
    Route::post('/reviews/{productId}', [ReviewController::class, 'store'])->name('reviews.store');

    // مسار تقييمات المتاجر الجديد
    Route::post('/store-reviews/{storeId}', [StoreReviewController::class, 'store'])->name('store.reviews.store');

    // مسار تعليم الإشعار كمقروء الجديد
    Route::post('/notifications/{id}/read', function ($id) {
        $notification = Auth::user()->notifications()->where('id', $id)->first();
        if ($notification) {
            $notification->markAsRead();
        }
        return redirect()->back();
    })->name('notifications.read');

    // مسارات الأسئلة والأجوبة
    Route::post('/questions/{productId}', [QuestionController::class, 'store'])->name('questions.store');
    Route::post('/questions/{id}/answer', [QuestionController::class, 'answer'])->name('questions.answer');

    // مسارات التاجر
    Route::prefix('vendor')->middleware('role:vendor')->group(function () {
        Route::get('/dashboard', [VendorController::class, 'index'])->name('vendor.dashboard');
        Route::get('/products', [VendorController::class, 'products'])->name('vendor.products');
        Route::get('/orders', [VendorController::class, 'orders'])->name('vendor.orders');
        
        // المسار لتحديث حالة الطلب
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

// مسار تفاصيل المتجر والتقييمات المحدث
Route::get('/store/{id}', function ($id) {
    $store = User::where('role', 'vendor')->findOrFail($id);
    $storeModel = Store::where('user_id', $store->id)->first();
    $products = Product::where('store_id', $storeModel?->id)->get();
    $reviews = \App\Models\StoreReview::where('store_id', $store->id)->with('user')->latest()->get();
    $avgRating = $reviews->avg('rating') ?? 0;

    return view('store.show', compact('store', 'storeModel', 'products', 'reviews', 'avgRating'));
})->name('store.show');

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