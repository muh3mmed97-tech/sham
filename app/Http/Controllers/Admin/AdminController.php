<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Store;
use App\Models\Product;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    // لوحة تحكم المدير الرئيسية
    public function index()
    {
        $stats = [
            'users_count'   => User::count(),
            'vendors_count' => Store::count(),
            'products_count'=> Product::count(),
            'orders_count'  => Order::count(),
        ];

        $recentOrders = Order::with(['customer', 'vendor', 'product'])->latest()->take(10)->get();

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }

    // إدارة المتاجر والتجار
    public function vendors()
    {
        $stores = Store::with('user')->latest()->get();
        return view('admin.vendors', compact('stores'));
    }

    // إدارة الطلبات العامة
    public function orders()
    {
        $orders = Order::with(['customer', 'vendor', 'product'])->latest()->get();
        return view('admin.orders', compact('orders'));
    }
}