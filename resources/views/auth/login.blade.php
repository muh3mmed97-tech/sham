@extends('layouts.app')

@section('content')
<div style="max-width: 450px; margin: 50px auto; background: white; padding: 40px; border-radius: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); border: 1px solid #E5E7EB; font-family: 'Cairo', sans-serif;" dir="rtl">
    
    <div style="text-align: center; margin-bottom: 30px;">
        <h2 style="color: #0B1B3D; margin: 0 0 10px 0; font-size: 2rem;">تسجيل الدخول</h2>
        <p style="color: #64748B; font-size: 0.95rem;">مرحباً بك مجدداً في منصة فُرات ستور</p>
    </div>

    @if($errors->any())
        <div style="background: #FEE2E2; color: #991B1B; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 0.9rem;">
            البريد الإلكتروني أو كلمة المرور غير صحيحة.
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; color: #374151; font-weight: bold;">البريد الإلكتروني:</label>
            <input type="email5" name="email" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #D1D5DB; font-family: 'Cairo'; box-sizing: border-box;" placeholder="example@domain.com">
        </div>

        <div style="margin-bottom: 25px;">
            <label style="display: block; margin-bottom: 8px; color: #374151; font-weight: bold;">كلمة المرور:</label>
            <input type="password" name="password" required style="width: 100%; padding: 12px; border-radius: 8px; border: 1px solid #D1D5DB; font-family: 'Cairo'; box-sizing: border-box;" placeholder="••••••••">
        </div>

        <button type="submit" style="width: 100%; background: #0B1B3D; color: #D4AF37; border: none; padding: 14px; border-radius: 10px; font-weight: bold; font-size: 1rem; cursor: pointer; font-family: 'Cairo'; border: 1px solid #D4AF37; transition: opacity 0.3s;">
            دخول
        </button>
    </form>

    <div style="margin-top: 25px; text-align: center; font-size: 0.9rem; color: #64748B; border-top: 1px solid #E5E7EB; padding-top: 20px;">
        ليس لديك حساب؟ 
        <a href="{{ route('register.customer') }}" style="color: #1E6FB8; font-weight: bold; text-decoration: none; margin-left: 5px;">سجل كعميل</a> أو 
        <a href="{{ route('register.vendor') }}" style="color: #D4AF37; font-weight: bold; text-decoration: none; margin-right: 5px;">سجل كتاجر</a>
    </div>

</div>
@endsection