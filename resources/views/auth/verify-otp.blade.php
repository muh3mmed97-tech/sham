@extends('layouts.app')

@section('content')
<div style="max-width: 450px; margin: 60px auto; background: white; padding: 35px; border-radius: 14px; border: 1px solid #E2E8F0; box-shadow: 0 4px 15px rgba(11,27,61,0.04); font-family: 'Cairo';" dir="rtl">
    
    <div style="text-align: center; margin-bottom: 25px;">
        <h2 style="color: #0B1B3D; font-size: 1.5rem; font-weight: 800; margin-bottom: 8px;">🔐 تأكيد رقم الهاتف</h2>
        <p style="color: #64748B; font-size: 0.9rem;">أدخل رمز التحقق (OTP) المرسل إلى رقم هاتفك لتفعيل حسابك.</p>
    </div>

    @if(session('success'))
        <div style="background: #F0FDF4; color: #166534; border-right: 4px solid #16A34A; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 0.85rem; font-weight: bold;">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div style="background: #FEF2F2; color: #991B1B; border-right: 4px solid #DC2626; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 0.85rem;">
            @foreach($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <form action="{{ route('otp.verify.submit') }}" method="POST">
        @csrf
        <div style="margin-bottom: 20px;">
            <label style="display: block; font-weight: 700; color: #0B1B3D; margin-bottom: 8px; font-size: 0.9rem;">رمز التحقق (OTP)</label>
            <input type="text" name="otp_code" required placeholder="أدخل الرمز المكون من أرقام" 
                   style="width: 100%; padding: 12px; border: 1.5px solid #CBD5E1; border-radius: 8px; text-align: center; font-size: 1.2rem; letter-spacing: 4px; font-weight: bold; outline: none; box-sizing: border-box;">
        </div>

        <button type="submit" style="width: 100%; padding: 12px; background: #0B1B3D; color: #D4AF37; border: 1.5px solid #D4AF37; border-radius: 8px; font-weight: 800; font-size: 1rem; cursor: pointer;">
            تحقق وتفعيل الحساب
        </button>
    </form>

</div>
@endsection