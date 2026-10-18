@extends('layouts.app')

@section('content')
<div style="max-width: 800px; margin: 40px auto; background: white; padding: 40px; border-radius: 20px; box-shadow: 0 8px 25px rgba(11, 27, 61, 0.08); border: 1px solid #E2E8F0; font-family: 'Cairo', sans-serif;" dir="rtl">
    
    <div style="text-align: center; margin-bottom: 30px; border-bottom: 2px solid #F1F5F9; padding-bottom: 20px;">
        <h2 style="color: #0B1B3D; margin: 0 0 8px 0; font-size: 1.6rem; font-weight: bold;">
            إضافة منتج جديد لمتجرك
        </h2>
        <p style="color: #64748B; font-size: 0.95rem; margin: 0;">اختر القسم المناسب ليظهر منتجك مباشرة ضمن بطاقة القسم في الصفحة الرئيسية</p>
    </div>

    @if ($errors->any())
        <div style="background: #FEE2E2; color: #991B1B; padding: 15px; border-radius: 12px; margin-bottom: 25px;">
            <ul style="margin: 0; padding-right: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('vendor.products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #0B1B3D;">اسم المنتج</label>
            <input type="text" name="name" value="{{ old('name') }}" required placeholder="مثال: سماعات بلوتوث لاسلكية" 
                   style="width: 100%; padding: 14px; border: 1.5px solid #CBD5E1; border-radius: 12px; box-sizing: border-box; font-family: 'Cairo'; outline: none;">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #0B1B3D;">وصف المنتج</label>
            <textarea name="description" rows="4" placeholder="اكتب تفاصيل ومواصفات المنتج بدقة..." 
                      style="width: 100%; padding: 14px; border: 1.5px solid #CBD5E1; border-radius: 12px; box-sizing: border-box; font-family: 'Cairo'; outline: none;">{{ old('description') }}</textarea>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #0B1B3D;">السعر (ل.س)</label>
                <input type="number" name="price" value="{{ old('price') }}" required placeholder="0" min="0" step="any"
                       style="width: 100%; padding: 14px; border: 1.5px solid #CBD5E1; border-radius: 12px; box-sizing: border-box; font-family: 'Cairo'; outline: none;">
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #0B1B3D;">الكمية المتوفرة</label>
                <input type="number" name="stock" value="{{ old('stock') }}" required placeholder="1" min="1"
                       style="width: 100%; padding: 14px; border: 1.5px solid #CBD5E1; border-radius: 12px; box-sizing: border-box; font-family: 'Cairo'; outline: none;">
            </div>
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #0B1B3D;">قسم المنتج</label>
            <select name="category" required style="width: 100%; padding: 14px; border: 1.5px solid #CBD5E1; border-radius: 12px; background: white; font-family: 'Cairo'; outline: none; cursor: pointer;">
                <option value="" disabled selected>-- اختر القسم الذي ينتمي إليه المنتج --</option>
                <option value="إلكترونيات" {{ old('category') == 'إلكترونيات' ? 'selected' : '' }}>📱 إلكترونيات</option>
                <option value="أدوات منزلية" {{ old('category') == 'أدوات منزلية' ? 'selected' : '' }}>🏠 أدوات منزلية</option>
                <option value="أزياء" {{ old('category') == 'أزياء' ? 'selected' : '' }}>👕 أزياء</option>
                <option value="مأكولات ومشروبات" {{ old('category') == 'مأكولات ومشروبات' ? 'selected' : '' }}>☕ مأكولات ومشروبات</option>
                <option value="رياضة" {{ old('category') == 'رياضة' ? 'selected' : '' }}>⚽ رياضة</option>
                <option value="مستحضرات تجميل" {{ old('category') == 'مستحضرات تجميل' ? 'selected' : '' }}>💄 مستحضرات تجميل</option>
            </select>
        </div>

        <div style="margin-bottom: 30px;">
            <label style="display: block; margin-bottom: 8px; font-weight: bold; color: #0B1B3D;">صورة المنتج</label>
            <input type="file" name="image" accept="image/*" required 
                   style="width: 100%; padding: 14px; border: 2px dashed #1E6FB8; border-radius: 12px; background: #F8FAFC; cursor: pointer; font-family: 'Cairo'; box-sizing: border-box;">
        </div>

        <button type="submit" style="width: 100%; padding: 16px; background: #0B1B3D; color: #D4AF37; border: none; border-radius: 12px; font-size: 1.15rem; font-weight: bold; cursor: pointer; font-family: 'Cairo'; transition: all 0.3s; box-shadow: 0 4px 15px rgba(11, 27, 61, 0.2);">
            إضافة المنتج للمنصة
        </button>
    </form>
</div>
@endsection