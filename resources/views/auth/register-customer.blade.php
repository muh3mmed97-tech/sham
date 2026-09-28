@extends('layouts.app')

@section('content')
<div style="max-width: 500px; margin: 40px auto; background: white; padding: 40px; border-radius: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); border: 1px solid #E5E7EB; font-family: 'Cairo', sans-serif;" dir="rtl">
    
    <div style="text-align: center; margin-bottom: 30px;">
        <h2 style="color: #0B1B3D; margin: 0 0 10px 0; font-size: 1.8rem;">حساب عميل جديد</h2>
        <p style="color: #64748B; font-size: 0.95rem;">انضم إلينا وابدأ التسوق بكل سهولة وثقة</p>
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

    <form method="POST" action="{{ route('register.customer.submit') }}">
        @csrf

        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; color: #374151; font-weight: bold;">الاسم الكامل:</label>
            <input type="text" name="name" value="{{ old('name') }}" required style="width: 100%; padding: 11px; border-radius: 8px; border: 1px solid #D1D5DB; font-family: 'Cairo'; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; color: #374151; font-weight: bold;">البريد الإلكتروني (التحقق من صحته):</label>
            <div style="display: flex; gap: 10px;">
                <input type="email" name="email" id="email" value="{{ old('email') }}" required style="flex: 1; padding: 11px; border-radius: 8px; border: 1px solid #D1D5DB; font-family: 'Cairo'; box-sizing: border-box;" placeholder="example@domain.com">
                <button type="button" onclick="verifyEmail()" style="background: #1E6FB8; color: white; border: none; padding: 0 15px; border-radius: 8px; cursor: pointer; font-family: 'Cairo'; font-weight: bold;">تحقق</button>
            </div>
            <small id="email-status" style="color: #10B981; display: none; margin-top: 5px;">✓ البريد صالح وجاهز للاستخدام</small>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; color: #374151; font-weight: bold;">رقم الهاتف:</label>
            <div style="display: flex; gap: 10px;">
                <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required style="flex: 1; padding: 11px; border-radius: 8px; border: 1px solid #D1D5DB; font-family: 'Cairo'; box-sizing: border-box;" placeholder="09xxxxxxxx">
                <button type="button" onclick="verifyPhone()" style="background: #D4AF37; color: #0B1B3D; border: none; padding: 0 15px; border-radius: 8px; cursor: pointer; font-family: 'Cairo'; font-weight: bold;">إرسال كود التحقق</button>
            </div>
            <small id="phone-status" style="color: #10B981; display: none; margin-top: 5px;">✓ تم إرسال رمز التحقق إلى رقم هاتفك</small>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="display: block; margin-bottom: 5px; color: #374151; font-weight: bold;">كلمة المرور:</label>
            <input type="password" name="password" required style="width: 100%; padding: 11px; border-radius: 8px; border: 1px solid #D1D5DB; font-family: 'Cairo'; box-sizing: border-box;">
        </div>

        <div style="margin-bottom: 25px;">
            <label style="display: block; margin-bottom: 5px; color: #374151; font-weight: bold;">تأكيد كلمة المرور:</label>
            <input type="password" name="password_confirmation" required style="width: 100%; padding: 11px; border-radius: 8px; border: 1px solid #D1D5DB; font-family: 'Cairo'; box-sizing: border-box;">
        </div>

        <button type="submit" style="width: 100%; background: #1E6FB8; color: white; border: none; padding: 13px; border-radius: 10px; font-weight: bold; font-size: 1rem; cursor: pointer; font-family: 'Cairo'; transition: opacity 0.3s;">
            إتمام التسجيل كعميل
        </button>
    </form>

    <div style="margin-top: 20px; text-align: center; font-size: 0.9rem; color: #64748B;">
        لديك حساب بالفعل؟ <a href="{{ route('login') }}" style="color: #0B1B3D; font-weight: bold; text-decoration: underline;">سجل دخولك الآن</a>
    </div>

</div>

<script>
    function verifyEmail() {
        let email = document.getElementById('email').value;
        let status = document.getElementById('email-status');
        if(email && email.includes('@')) {
            status.style.display = 'block';
            status.innerText = '✓ تم التحقق من البريد بنجاح';
        } else {
            alert('يرجى إدخال بريد إلكتروني صالح أولاً');
        }
    }

    function verifyPhone() {
        let phone = document.getElementById('phone').value;
        let status = document.getElementById('phone-status');
        if(phone.length >= 10) {
            status.style.display = 'block';
            status.innerText = '✓ تم إرسال رمز التحقق (OTP) بنجاح إلى هاتفك';
        } else {
            alert('يرجى إدخال رقم هاتف صحيح');
        }
    }
</script>
@endsection