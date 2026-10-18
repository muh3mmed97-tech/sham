@extends('layouts.app')

@section('content')
<div style="max-width: 750px; margin: 30px auto 60px; padding: 0 15px; font-family: 'Cairo', sans-serif;" dir="rtl">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <div>
            <h2 style="color: #0B1B3D; font-size: 1.45rem; font-weight: 800; margin: 0 0 5px 0;">
                ✏️ تعديل المنتج: {{ $product->name }}
            </h2>
            <p style="color: #64748B; font-size: 0.85rem; margin: 0;">قم بتحديث تفاصيل المنتج والسعر والمخزون</p>
        </div>
        <a href="{{ route('vendor.products') }}" style="background: #F1F5F9; color: #475569; text-decoration: none; padding: 8px 16px; border-radius: 8px; font-weight: 700; font-size: 0.85rem; border: 1px solid #CBD5E1;">
            ← العودة للمنتجات
        </a>
    </div>

    @if(session('success'))
        <div style="background: #F0FDF4; color: #166534; border-right: 4px solid #16A34A; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-weight: bold; font-size: 0.9rem;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background: #FEF2F2; color: #991B1B; border-right: 4px solid #DC2626; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-size: 0.85rem;">
            <ul style="margin: 0; padding-right: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- بطاقة نموذج التعديل -->
    <div style="background: #FFFFFF; border-radius: 14px; border: 1px solid #E2E8F0; padding: 30px; box-shadow: 0 4px 15px rgba(11, 27, 61, 0.04);">
        <form action="{{ route('vendor.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <!-- اسم المنتج -->
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 700; color: #0B1B3D; margin-bottom: 8px; font-size: 0.9rem;">اسم المنتج <span style="color: #DC2626;">*</span></label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required 
                       style="width: 100%; padding: 11px 15px; border: 1.5px solid #CBD5E1; border-radius: 8px; font-family: 'Cairo'; font-size: 0.95rem; outline: none; box-sizing: border-box; transition: border-color 0.2s;">
            </div>

            <!-- الوصف -->
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: 700; color: #0B1B3D; margin-bottom: 8px; font-size: 0.9rem;">وصف المنتج</label>
                <textarea name="description" rows="4" 
                          style="width: 100%; padding: 11px 15px; border: 1.5px solid #CBD5E1; border-radius: 8px; font-family: 'Cairo'; font-size: 0.95rem; outline: none; box-sizing: border-box; resize: vertical;">{{ old('description', $product->description) }}</textarea>
            </div>

            <!-- السعر والكمية في سطر واحد -->
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px;">
                <div>
                    <label style="display: block; font-weight: 700; color: #0B1B3D; margin-bottom: 8px; font-size: 0.9rem;">السعر (ليرة سورية) <span style="color: #DC2626;">*</span></label>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}" step="any" required 
                           style="width: 100%; padding: 11px 15px; border: 1.5px solid #CBD5E1; border-radius: 8px; font-family: 'Cairo'; font-size: 0.95rem; outline: none; box-sizing: border-box;">
                </div>
                <div>
                    <label style="display: block; font-weight: 700; color: #0B1B3D; margin-bottom: 8px; font-size: 0.9rem;">الكمية في المخزون <span style="color: #DC2626;">*</span></label>
                    <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" min="0" required 
                           style="width: 100%; padding: 11px 15px; border: 1.5px solid #CBD5E1; border-radius: 8px; font-family: 'Cairo'; font-size: 0.95rem; outline: none; box-sizing: border-box;">
                </div>
            </div>

            <!-- القسم / التصنيف -->
            <div style="margin-bottom: 25px;">
                <label style="display: block; font-weight: 700; color: #0B1B3D; margin-bottom: 8px; font-size: 0.9rem;">التصنيف / القسم</label>
                <select name="category_id" style="width: 100%; padding: 11px 15px; border: 1.5px solid #CBD5E1; border-radius: 8px; font-family: 'Cairo'; font-size: 0.95rem; outline: none; box-sizing: border-box; background: white;">
                    @if(isset($categories))
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ (old('category_id', $product->category_id) == $cat->id) ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    @else
                        <option value="إلكترونيات" {{ old('category', $product->category) == 'إلكترونيات' ? 'selected' : '' }}>إلكترونيات</option>
                        <option value="أدوات منزلية" {{ old('category', $product->category) == 'أدوات منزلية' ? 'selected' : '' }}>أدوات منزلية</option>
                        <option value="أزياء" {{ old('category', $product->category) == 'أزياء' ? 'selected' : '' }}>أزياء</option>
                        <option value="مأكولات ومشروبات" {{ old('category', $product->category) == 'مأكولات ومشروبات' ? 'selected' : '' }}>مأكولات ومشروبات</option>
                        <option value="رياضة" {{ old('category', $product->category) == 'رياضة' ? 'selected' : '' }}>رياضة</option>
                        <option value="مستحضرات تجميل" {{ old('category', $product->category) == 'مستحضرات تجميل' ? 'selected' : '' }}>مستحضرات تجميل</option>
                    @endif
                </select>
            </div>

            <!-- معاينة وتحديث الصورة -->
            <div style="margin-bottom: 30px; background: #F8FAFC; padding: 16px; border-radius: 10px; border: 1px dashed #CBD5E1;">
                <label style="display: block; font-weight: 700; color: #0B1B3D; margin-bottom: 12px; font-size: 0.9rem;">صورة المنتج</label>
                <div style="display: flex; align-items: center; gap: 20px; flex-wrap: wrap;">
                    @if($product->image)
                        <div style="width: 90px; height: 90px; border-radius: 8px; overflow: hidden; border: 1px solid #CBD5E1; background: white;">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="الصورة الحالية" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                    @endif
                    <div style="flex: 1;">
                        <input type="file" name="image" accept="image/*" 
                               style="display: block; width: 100%; font-family: 'Cairo'; font-size: 0.85rem; color: #475569;">
                        <span style="display: block; margin-top: 5px; color: #94A3B8; font-size: 0.75rem;">اترك الحقل فارغاً إذا كنت لا ترغب بتغيير الصورة الحالية.</span>
                    </div>
                </div>
            </div>

            <!-- أزرار الإجراء -->
            <div style="display: flex; gap: 12px;">
                <button type="submit" 
                        style="flex: 1; padding: 12px; background: #0B1B3D; color: #D4AF37; border: 1.5px solid #D4AF37; border-radius: 8px; font-weight: 800; font-size: 1rem; font-family: 'Cairo'; cursor: pointer; transition: background 0.2s;">
                    💾 حفظ التعديلات
                </button>
                <a href="{{ route('vendor.products') }}" 
                   style="padding: 12px 24px; background: #F1F5F9; color: #475569; border-radius: 8px; font-weight: 700; font-size: 0.95rem; text-decoration: none; border: 1px solid #CBD5E1; display: inline-flex; align-items: center;">
                    إلغاء
                </a>
            </div>

        </form>
    </div>

</div>
@endsection