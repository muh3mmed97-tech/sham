<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\Order;
use App\Models\Wallet;
use App\Models\Coupon;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // لوحة تحكم المدير الرئيسية مع التقارير المالية والعمولات
    public function index()
    {
        // حساب المبيعات للطلبات المكتملة أو التي تم شحنها/تسليمها
        $completedSales = Order::whereIn('status', ['completed', 'shipped', 'delivered'])->sum('total_price');
        $commissionRate = 0.05; // عمولة المنصة 5%
        $platformProfit = $completedSales * $commissionRate;

        $stats = [
            'users_count'      => User::count(),
            'vendors_count'    => Store::count(),
            'products_count'   => Product::count(),
            'orders_count'     => Order::count(),
            'completed_orders' => Order::whereIn('status', ['completed', 'shipped', 'delivered'])->count(),
            'pending_orders'   => Order::whereIn('status', ['pending', 'processing'])->count(),
            'total_sales'      => $completedSales,
            'platform_profit'  => $platformProfit,
            'total_wallets'    => Wallet::sum('balance'),
        ];

        $recentOrders = Order::with(['customer', 'vendor', 'product'])->latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }

    // 2. إدارة المتاجر والتجار
    public function vendors()
    {
        $stores = Store::with('user')->withCount('products')->latest()->get();
        return view('admin.vendors', compact('stores'));
    }

    // حذف متجر من المنصة
    public function deleteVendor($id)
    {
        $store = Store::findOrFail($id);
        $store->delete();

        return redirect()->back()->with('success', 'تم حذف المتجر بنجاح من المنصة.');
    }

    // 3. إدارة الطلبات العامة
    public function orders()
    {
        $orders = Order::with(['customer', 'vendor', 'product'])->latest()->get();
        return view('admin.orders', compact('orders'));
    }

    // تحديث حالة أي طلب من قبل الإدارة العليا
    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,completed,cancelled'
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        return redirect()->back()->with('success', 'تم تحديث حالة الطلب رقم #' . $order->id . ' بنجاح.');
    }

    // 4. إدارة المحافظ والأرصدة المالية
    public function wallets()
    {
        $users = User::with('wallet')->latest()->get();
        return view('admin.wallets', compact('users'));
    }

    // شحن أو خصم رصيد لمستخدم (تاجر أو عميل) من قبل الإدارة
    public function adjustWallet(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount'  => 'required|numeric|min:1',
            'type'    => 'required|in:deposit,withdraw',
        ]);

        $wallet = Wallet::firstOrCreate(
            ['user_id' => $request->user_id],
            ['balance' => 0]
        );

        if ($request->type === 'deposit') {
            $wallet->balance += $request->amount;
            $msg = 'تم إيداع ' . number_format($request->amount) . ' ل.س في محفظة المستخدم بنجاح.';
        } else {
            if ($wallet->balance < $request->amount) {
                return redirect()->back()->with('error', 'رصيد المستخدم الحالي لا يكفي لإجراء هذا الخصم.');
            }
            $wallet->balance -= $request->amount;
            $msg = 'تم خصم ' . number_format($request->amount) . ' ل.س من محفظة المستخدم بنجاح.';
        }

        $wallet->save();

        return redirect()->back()->with('success', $msg);
    }

    // 5. إدارة الكوبونات وقسائم الخصم
    public function coupons()
    {
        $coupons = Coupon::latest()->get();
        return view('admin.coupons', compact('coupons'));
    }

    // تخزين كوبون جديد
    public function storeCoupon(Request $request)
    {
        $request->validate([
            'code'        => 'required|string|unique:coupons,code',
            'type'        => 'required|in:fixed,percent',
            'value'       => 'required|numeric|min:0',
            'min_spend'   => 'nullable|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'expires_at'  => 'nullable|date',
        ]);

        Coupon::create([
            'code'        => strtoupper(trim($request->code)),
            'type'        => $request->type,
            'value'       => $request->value,
            'min_spend'   => $request->min_spend ?? 0,
            'usage_limit' => $request->usage_limit,
            'expires_at'  => $request->expires_at,
        ]);

        return redirect()->back()->with('success', 'تم إنشاء كود الخصم بنجاح.');
    }

    // حذف الكوبون
    public function deleteCoupon($id)
    {
        $coupon = Coupon::findOrFail($id);
        $coupon->delete();

        return redirect()->back()->with('success', 'تم حذف كود الخصم بنجاح.');
    }
}