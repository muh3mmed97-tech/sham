<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product; // تأكد من استيراد موديل المنتج

class CustomerController extends Controller
{
    public function index()
    {
        // جلب جميع المنتجات من قاعدة البيانات
        $products = Product::all();
        
        // تمرير البيانات إلى صفحة العميل
        return view('customer.dashboard', compact('products'));
    }
}