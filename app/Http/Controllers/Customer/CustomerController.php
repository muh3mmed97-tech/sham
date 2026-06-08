<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product; 
use App\Models\Order; 
use Illuminate\Support\Facades\Auth; 

class CustomerController extends Controller
{
    // عرض لوحة تحكم العميل مع دعم البحث والفلترة
    public function index(Request $request)
    {
        $query = Product::query();

        // 1. الفلترة حسب التصنيف
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }

        // 2. البحث حسب الاسم
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $query->latest()->get();

        return view('customer.dashboard', compact('products'));
    }

    // عرض طلبات العميل
    public function orders()
    {
        $orders = Order::where('customer_id', Auth::id())->with('product')->latest()->get();
        return view('customer.orders', compact('orders'));
    }

    // الدالة المعدلة لعرض الفاتورة مع معلومات المتجر
    public function invoice($id)
    {
        // جلب الطلب مع بيانات المنتج وعلاقة المتجر المرتبط بالمنتج
        $order = Order::where('customer_id', Auth::id())
                      ->with(['product', 'product.store']) // جلب بيانات المتجر عبر المنتج
                      ->findOrFail($id);
                      
        return view('customer.invoice', compact('order'));
    }
}