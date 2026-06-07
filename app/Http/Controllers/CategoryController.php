<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // عرض قائمة الأقسام
    public function index() {
        $categories = Category::all();
        return view('vendor.categories.index', compact('categories'));
    }

    // حفظ قسم جديد
    public function store(Request $request) {
        $request->validate(['name' => 'required|unique:categories|max:255']);
        Category::create(['name' => $request->name]);
        return back()->with('success', 'تم إضافة القسم بنجاح!');
    }

    // حذف قسم
    public function destroy($id) {
        Category::findOrFail($id)->delete();
        return back()->with('success', 'تم حذف القسم.');
    }
}