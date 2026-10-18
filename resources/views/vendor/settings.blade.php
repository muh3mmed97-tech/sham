@extends('layouts.app')

@section('content')
<div style="max-width: 750px; margin: 30px auto; background: white; padding: 35px; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid #E2E8F0;" dir="rtl">

    <div style="border-bottom: 2px solid #F1F5F9; padding-bottom: 15px; margin-bottom: 25px;">
        <h2 style="color: #0B1B3D; margin: 0; font-size: 1.4rem;">⚙️ إعدادات المتجر والهوية</h2>
        <p style="color: #64748B; margin: 5px 0 0; font-size: 0.85rem;">يمكنك تعديل اسم المتجر، شعاره، ومعلومات الاتصال التي تظهر للعملاء وفي الفواتير.</p>
    </div>

    @if ($errors->any())
        <div style="background: #FEF2F2; color: #991B1B; padding: 12px 16px; border-radius: 8px; margin-bottom: 20px; font-size: 0.85rem;">
            <ul style="margin: 0; padding-right: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('vendor.settings.update') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- قسم الشعار / اللوغو -->
        <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 25px; background: #F8FAFC; padding: 16px; border-radius: 12px; border: 1px dashed #CBD5E1;">
            <div style="width: 85px; height: 85px; border-radius: 12px; background: #E2E8F0; overflow: hidden; display: flex; align-items: center; justify-content: center; border: 2px solid #D4AF37;">
                @if($store && $store->logo)
                    <img id="logo-preview" src="{{ asset('storage/' . $store->logo) }}" alt="لوغو المتجر" style="width: 100%; height: 100%; object-fit: cover;">
                @else
                    <img id="logo-preview" src="{{ asset('images/logo.png') }}" alt="افتراضي" style="width: 100%; height: 100%; object-fit: cover;">
                @endif
            </div>
            <div style="flex: 1;">
                <label style="display: block; font-weight: bold; color: #0B1B3D; margin-bottom: 6px; font-size: 0.9rem;">شعار المتجر (اللوغو):</label>
                <input type="file" name="logo" id="logo-input" accept="image/*" style="font-size: 0.85rem; color: #64748B;">
                <small style="display: block; color: #94A3B8; margin-top: 4px; font-size: 0.75rem;">يفضل مقاس مربع (PNG, JPG, WEBP) بحد أقصى 2MB</small>
            </div>
        </div>

        <!-- اسم المتجر -->
        <div style="margin-bottom: 18px;">
            <label style="display: block; font-weight: bold; color: #0B1B3D; margin-bottom: 6px; font-size: 0.9rem;">اسم المتجر:</label>
            <input type="text" name="name" value="{{ old('name', $store->name ?? '') }}" required style="width: 100%; padding: 10px 14px; border: 1.5px solid #CBD5E1; border-radius: 8px; font-family: 'Cairo'; font-size: 0.9rem; outline: none;">
        </div>

        <!-- رقم هاتف التواصل -->
        <div style="margin-bottom: 18px;">
            <label style="display: block; font-weight: bold; color: #0B1B3D; margin-bottom: 6px; font-size: 0.9rem;">رقم هاتف المتجر:</label>
            <input type="text" name="phone" value="{{ old('phone', $store->phone ?? '') }}" placeholder="مثال: 0912345678" style="width: 100%; padding: 10px 14px; border: 1.5px solid #CBD5E1; border-radius: 8px; font-family: 'Cairo'; font-size: 0.9rem; outline: none;">
        </div>

        <!-- العنوان والموقع الجغرافي -->
        <div style="margin-bottom: 18px;">
            <label style="display: block; font-weight: bold; color: #0B1B3D; margin-bottom: 6px; font-size: 0.9rem;">عنوان المتجر / المدينة:</label>
            <input type="text" name="address" value="{{ old('address', $store->address ?? '') }}" placeholder="مثال: دير الزور - شارع الوادي" style="width: 100%; padding: 10px 14px; border: 1.5px solid #CBD5E1; border-radius: 8px; font-family: 'Cairo'; font-size: 0.9rem; outline: none;">
        </div>

        <!-- نبذة أو وصف المتجر -->
        <div style="margin-bottom: 25px;">
            <label style="display: block; font-weight: bold; color: #0B1B3D; margin-bottom: 6px; font-size: 0.9rem;">نبذة عن المتجر (اختياري):</label>
            <textarea name="description" rows="3" placeholder="اكتب نبذة مختصرة تصف متجرك ومنتجاتك..." style="width: 100%; padding: 10px 14px; border: 1.5px solid #CBD5E1; border-radius: 8px; font-family: 'Cairo'; font-size: 0.9rem; outline: none;">{{ old('description', $store->description ?? '') }}</textarea>
        </div>

        <!-- زر الحفظ -->
        <div style="text-align: left;">
            <button type="submit" style="background: #1E6FB8; color: white; border: none; padding: 12px 28px; border-radius: 8px; font-weight: bold; font-family: 'Cairo'; font-size: 0.95rem; cursor: pointer; transition: background 0.2s;">
                💾 حفظ التعديلات
            </button>
        </div>
    </form>
</div>

<script>
    // معاينة الصورة فور اختيارها
    document.getElementById('logo-input').addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            const reader = new FileReader();
            reader.onload = function(evt) {
                document.getElementById('logo-preview').src = evt.target.result;
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });
</script>
@endsection