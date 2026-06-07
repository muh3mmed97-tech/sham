<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product; 
use App\Models\Order; 
use Illuminate\Support\Facades\Auth; 

class CustomerController extends Controller
{
    // عرض لوحة تحكم العميل
    public function index()
    {
        $products = Product::all();
        return view('customer.dashboard', compact('products'));
    }

    // عرض طلبات العميل
    public function orders()
    {
        // استخدام with('product') لجلب بيانات المنتج المرتبط تلقائياً
        $orders = Order::where('customer_id', Auth::id())->with('product')->latest()->get();
        return view('customer.orders', compact('orders'));
    }
}