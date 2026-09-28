@extends('layouts.app')

@section('content')
<div style="max-width: 600px; margin: 40px auto; background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #E5E7EB; font-family: 'Cairo';" dir="rtl">
    
    <h2 style="color: #0B1B3D; margin-bottom: 20px; border-bottom: 2px solid #D4AF37; padding-bottom: 10px;">⚙️ إعدادات ومعلومات الحساب</h2>

    @if(session('success'))
        <div style="background: #D1FAE5; color: #065F46; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-weight: bold;">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('customer.profile.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; color: #374151; font-weight: bold;">الاسم الكامل:</label>
            <input type="text" name="name" value="{{ Auth::user()->name }}" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #D1D5DB; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; color: #374151; font-weight: bold;">البريد الإلكتروني (غير قابل للتعديل):</label>
            <input type="email" value="{{ Auth::user()->email }}" disabled style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #E5E7EB; background: #F3F4F6; color: #6B7280; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; color: #374151; font-weight: bold;">رقم الهاتف:</label>
            <input type="text" name="phone" value="{{ Auth::user()->phone ?? '' }}" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #D1D5DB; box-sizing: border-box;" placeholder="09xxxxxxxx">
        </div>

        <div style="margin-bottom: 25px;">
            <label style="display: block; margin-bottom: 8px; color: #374151; font-weight: bold;">عنوان الشحن السكني:</label>
            <textarea name="address" rows="3" style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #D1D5DB; box-sizing: border-box;" placeholder="المدينة، الحي، الشارع، بناء...">{{ Auth::user()->address ?? '' }}</textarea>
        </div>

        <div style="display: flex; gap: 10px;">
            <button type="submit" style="flex: 1; background: #0B1B3D; color: #D4AF37; border: none; padding: 12px; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 1rem;">حفظ التعديلات</button>
            <a href="{{ route('customer.dashboard') }}" style="flex: 1; background: #E5E7EB; color: #374151; text-align: center; padding: 12px; border-radius: 8px; text-decoration: none; font-weight: bold;">العودة للرئيسية</a>
        </div>
    </form>
</div>
@endsection