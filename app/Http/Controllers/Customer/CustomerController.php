<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product; 
use App\Models\Order; 
use Illuminate\Support\Facades\Auth; 

class CustomerController extends Controller
{
    // عرض لوحة تحكم العميل مع دعم البحث والفلترة الذكية
    public function index(Request $request)
    {
        $query = Product::query()->with('category');

        // 1. الفلترة حسب القسم (بالاسم أو بالـ ID)
        if ($request->filled('category')) {
            $category = $request->category;

            $query->where(function ($q) use ($category) {
                if (is_numeric($category)) {
                    $q->where('category_id', $category);
                } else {
                    $cleanName = preg_replace('/^ال/', '', trim($category));

                    $q->whereHas('category', function ($catQuery) use ($category, $cleanName) {
                        $catQuery->where('name', 'like', '%' . $cleanName . '%')
                                 ->orWhere('name', 'like', '%' . $category . '%');
                    });
                }
            });
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

    // عرض الفاتورة مع معلومات المتجر
    public function invoice($id)
    {
        $order = Order::where('customer_id', Auth::id())
                      ->with(['product', 'product.store'])
                      ->findOrFail($id);
                      
        return view('customer.invoice', compact('order'));
    }
}