<?php

namespace App\Http\Controllers\Vendor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class VendorController extends Controller
{
    public function products()
    {
        $products = auth()->user()->store->products; 
        return view('vendor.products.index', compact('products'));
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
        return redirect()->route('vendor.products')->with('success', 'تم إضافة المنتج بنجاح!');
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
            // حذف الصورة القديمة إذا وُجدت
            if ($product->image) Storage::disk('public')->delete($product->image);
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($data);
        return redirect()->route('vendor.products')->with('success', 'تم تحديث المنتج!');
    }

    public function destroyProduct($id)
    {
        $product = auth()->user()->store->products()->findOrFail($id);
        if ($product->image) Storage::disk('public')->delete($product->image);
        $product->delete();
        return redirect()->route('vendor.products')->with('success', 'تم حذف المنتج!');
    }
}