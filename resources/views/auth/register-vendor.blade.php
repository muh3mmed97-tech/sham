@extends('layouts.app')

@section('content')
<div style="max-width: 550px; margin: 40px auto; background: white; padding: 40px; border-radius: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); border: 1px solid #E5E7EB; font-family: 'Cairo', sans-serif;" dir="rtl">
    
    <div style="text-align: center; margin-bottom: 30px;">
        <h2 style="color: #0B1B3D; margin: 0 0 10px 0; font-size: 1.8rem;">تسجيل حساب تاجر جديد</h2>
        <p style="color: #64748B; font-size: 0.95rem;">أنشئ متجرك الخاص وابدأ بيع منتجاتك على منصة فُرات ستور</p>
    </div>

    @if($errors->any())
        <div style="background: #FEE2E2; color: #991B1B; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem;">
            <ul style="margin: 0; padding-right: 20px;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register.vendor.submit') }}">
        @csrf

        <h4 style="color: #0B1B3D; border-bottom: 2px solid #D4AF37; padding-bottom: 5px; margin-bottom: 15px;">👤 البيانات الشخصية</h4>
        
        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; color: #374151; font-weight: bold;">الاسم الكامل:</label>
            <input type="text" name="name" value="{{ old('name') }}" required style="width: 100%; padding: 11px; border-radius: 8px; border: 1px solid #D1D5DB; font-family: 'Cairo'; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; color: #374151; font-weight: bold;">البريد الإلكتروني:</label>
            <input type="email" name="email" value="{{ old('email') }}" required style="width: 100%; padding: 11px; border-radius: 8px; border: 1px solid #D1D5DB; font-family: 'Cairo'; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; color: #374151; font-weight: bold;">كلمة المرور:</label>
            <input type="password" name="password" required style="width: 100%; padding: 11px; border-radius: 8px; border: 1px solid #D1D5DB; font-family: 'Cairo'; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 25px;">
            <label style="display: block; margin-bottom: 5px; color: #374151; font-weight: bold;">تأكيد كلمة المرور:</label>
            <input type="password" name="password_confirmation" required style="width: 100%; padding: 11px; border-radius: 8px; border: 1px solid #D1D5DB; font-family: 'Cairo'; box-sizing: border-box;">
        </div>

        <h4 style="color: #0B1B3D; border-bottom: 2px solid #D4AF37; padding-bottom: 5px; margin-bottom: 15px;">🏪 بيانات المتجر</h4>

        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; color: #374151; font-weight: bold;">اسم المتجر:</label>
            <input type="text" name="store_name" value="{{ old('store_name') }}" required style="width: 100%; padding: 11px; border-radius: 8px; border: 1px solid #D1D5DB; font-family: 'Cairo'; box-sizing: border-box;" placeholder="مثال: متجر الشام للالكترونيات">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; color: #374151; font-weight: bold;">رقم الهاتف:</label>
            <input type="text" name="phone" value="{{ old('phone') }}" style="width: 100%; padding: 11px; border-radius: 8px; border: 1px solid #D1D5DB; font-family: 'Cairo'; box-sizing: border-box;" placeholder="09xxxxxxxx">
        </div>

        <div style="margin-bottom: 25px;">
            <label style="display: block; margin-bottom: 5px; color: #374151; font-weight: bold;">عنوان المتجر / المدينة:</label>
            <input type="text" name="address" value="{{ old('address') }}" style="width: 100%; padding: 11px; border-radius: 8px; border: 1px solid #D1D5DB; font-family: 'Cairo'; box-sizing: border-box;" placeholder="مثال: دمشق، شارع بغداد">
        </div>

        <button type="submit" style="width: 100%; background: #D4AF37; color: #0B1B3D; border: none; padding: 14px; border-radius: 10px; font-weight: bold; font-size: 1rem; cursor: pointer; font-family: 'Cairo'; border: 1px solid #D4AF37; transition: opacity 0.3s;">
            فتح المتجر وتسجيل الحساب
        </button>
    </form>

    <div style="margin-top: 20px; text-align: center; font-size: 0.9rem; color: #64748B;">
        لديك حساب بالفعل؟ <a href="{{ route('login') }}" style="color: #0B1B3D; font-weight: bold; text-decoration: underline;">سجل دخولك الآن</a>
    </div>

</div>
@endsection