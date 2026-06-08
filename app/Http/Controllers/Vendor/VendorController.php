<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use App\Models\Order;
use App\Models\Wallet;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class VendorController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $products = $user->store ? $user->store->products()->with('questions')->latest()->get() : collect();
        $orders = Order::where('vendor_id', $user->id)->latest()->take(5)->get();
        $wallet = Wallet::firstOrCreate(['user_id' => $user->id]);

        return view('vendor.dashboard', compact('products', 'orders', 'wallet'));
    }

    public function products()
    {
        return redirect()->route('vendor.dashboard');
    }

    public function orders()
    {
        $orders = Order::where('vendor_id', Auth::id())->latest()->get();
        return view('vendor.orders', compact('orders'));
    }

    // الدالة الجديدة لتحديث الحالة (تم الربط مع الـ Route الجديد)
    public function updateStatus(Request $request, $id)
    {
        $order = Order::where('vendor_id', Auth::id())->findOrFail($id);

        // المنطق المالي الذكي (إيداع/استرجاع)
        if ($order->status == 'pending' && in_array($request->status, ['processing', 'shipped', 'delivered'])) {
            $vendorWallet = Wallet::firstOrCreate(['user_id' => $order->vendor_id]);
            $vendorWallet->balance += $order->total_price;
            $vendorWallet->save();
        } 
        elseif ($order->status != 'pending' && $order->status != 'cancelled' && $request->status == 'cancelled') {
            $customerWallet = Wallet::where('user_id', $order->customer_id)->first();
            if ($customerWallet) {
                $customerWallet->balance += $order->total_price;
                $customerWallet->save();
            }
        }

        $order->status = $request->status;
        $order->save();

        return back()->with('success', 'تم تحديث حالة الطلب إلى: ' . $request->status);
    }

    // الاحتفاظ بالدالة القديمة للعمليات الأخرى إذا احتجتها
    public function updateOrder(Request $request, $id)
    {
        return $this->updateStatus($request, $id);
    }

    public function createProduct()
    {
        $categories = Category::all();
        return view('vendor.products.create', compact('categories'));
    }

    public function storeProduct(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer', 
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        auth()->user()->store->products()->create($data);
        return redirect()->route('vendor.dashboard')->with('success', 'تم إضافة المنتج بنجاح!');
    }

    public function editProduct($id)
    {
        $product = auth()->user()->store->products()->findOrFail($id);
        $categories = Category::all();
        return view('vendor.products.edit', compact('product', 'categories'));
    }

    public function updateProduct(Request $request, $id)
    {
        $product = auth()->user()->store->products()->findOrFail($id);
        
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('image')) {
            if ($product->image) Storage::disk('public')->delete($product->image);
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);
        return redirect()->route('vendor.dashboard')->with('success', 'تم تحديث المنتج!');
    }

    public function destroyProduct($id)
    {
        $product = auth()->user()->store->products()->findOrFail($id);
        if ($product->image) Storage::disk('public')->delete($product->image);
        $product->delete();
        return redirect()->route('vendor.dashboard')->with('success', 'تم حذف المنتج!');
    }
}