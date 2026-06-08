@extends('layouts.app')

@section('content')
<div style="max-width: 800px; margin: 40px auto; background: white; padding: 40px; border-radius: 25px; box-shadow: 0 10px 30px rgba(0,0,0,0.1);">
    
    <h2 style="color: #005a9c; text-align: center; margin-bottom: 30px; border-bottom: 2px solid #f4f4f4; padding-bottom: 20px;">
        إضافة منتج جديد لمتجرك
    </h2>

    <form action="{{ route('vendor.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">اسم المنتج</label>
            <input type="text" name="name" required style="width: 100%; padding: 15px; border: 1px solid #ddd; border-radius: 12px; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">وصف المنتج</label>
            <textarea name="description" rows="4" style="width: 100%; padding: 15px; border: 1px solid #ddd; border-radius: 12px; box-sizing: border-box;"></textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">السعر</label>
                <input type="number" name="price" required style="width: 100%; padding: 15px; border: 1px solid #ddd; border-radius: 12px; box-sizing: border-box;">
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">الكمية</label>
                <input type="number" name="stock" required style="width: 100%; padding: 15px; border: 1px solid #ddd; border-radius: 12px; box-sizing: border-box;">
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">التصنيف</label>
            <select name="category_id" style="width: 100%; padding: 15px; border: 1px solid #ddd; border-radius: 12px; background: white;">
                @foreach(\App\Models\Category::all() as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div style="margin-bottom: 30px;">
            <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #333;">صورة المنتج</label>
            <input type="file" name="image" required style="width: 100%; padding: 15px; border: 1px dashed #005a9c; border-radius: 12px; cursor: pointer;">
        </div>

        <button type="submit" style="width: 100%; padding: 18px; background: #005a9c; color: white; border: none; border-radius: 15px; font-size: 1.1rem; font-weight: bold; cursor: pointer; transition: 0.3s;">
            إضافة المنتج للمنصة
        </button>
    </form>
</div>
@endsection