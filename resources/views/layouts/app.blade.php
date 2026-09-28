<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فُرات ستور | تجارتنا عهد، وثقتكم أمانة</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <style>
        html, body { height: 100%; margin: 0; font-family: 'Cairo', sans-serif; }
        body { display: flex; flex-direction: column; background-color: #F6F8FB; background-image: url('https://www.transparenttextures.com/patterns/arabesque.png'); background-repeat: repeat; }
        .top-bar { background: #0B1B3D; color: white; padding: 5px 20px; font-size: 12px; display: flex; justify-content: flex-end; gap: 15px; }
        .top-bar a { color: white; text-decoration: none; transition: color 0.3s; }
        .top-bar a:hover { color: #D4AF37; }
        .main-nav { padding: 15px 40px; display: flex; align-items: center; justify-content: space-between; background: rgba(11, 27, 61, 0.95); border-bottom: 3px solid #D4AF37; backdrop-filter: blur(5px); }
        .brand-name { font-weight: bold; font-size: 1.4em; color: #D4AF37; line-height: 1.2; }
        .slogan { font-size: 0.75rem; color: #94A3B8; }
        .nav-actions { display: flex; gap: 20px; align-items: center; }
        .nav-actions a, .nav-actions button { text-decoration: none; color: white; font-weight: bold; border: none; background: none; cursor: pointer; font-family: 'Cairo'; transition: color 0.3s; }
        .nav-actions a:hover, .nav-actions button:hover { color: #D4AF37; }
        
        /* تنسيق القائمة المنسدلة الإيماءية */
        .dropdown { position: relative; display: inline-block; }
        .dropdown-content { display: none; position: absolute; left: 0; top: 40px; background: white; min-width: 180px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); border-radius: 8px; border: 1px solid #E5E7EB; z-index: 1000; overflow: hidden; text-align: right; }
        .dropdown-content a { display: block; padding: 10px 15px; color: #0B1B3D; text-decoration: none; border-bottom: 1px solid #F3F4F6; font-size: 0.9rem; transition: background 0.2s; }
        .dropdown-content a:hover { background: #F8FAFC; color: #1E6FB8; }
        .dropdown:hover .dropdown-content { display: block; }

        .container { flex: 1; padding: 20px 50px; }
        footer { background: #0B1B3D; color: white; padding: 20px; text-align: center; border-top: 3px solid #1E6FB8; }
        footer p { margin: 0; color: #94A3B8; }
        footer span { color: #D4AF37; font-weight: bold; }
    </style>
</head>
<body>

    <div class="top-bar">
        <a href="#">المساعدة</a> | 
        <a href="{{ route('register.vendor') }}">البيع على فُرات ستور</a> | 
        <a href="{{ route('login') }}">تسجيل الدخول</a>
    </div>

    <nav class="main-nav">
        <a href="{{ route('home') }}" style="text-decoration: none; display: flex; align-items: center; gap: 12px;">
            <img src="{{ asset('images/logo.png') }}" alt="فُرات ستور" style="height: 50px; width: auto; border-radius: 8px; border: 1px solid #D4AF37; object-fit: cover;">            
            <div style="display: flex; flex-direction: column;">
                <span class="brand-name">فُرات ستور</span>
                <span class="slogan">تجارتنا عهد، وثقتكم أمانة</span>
            </div>
        </a>
        <div class="nav-actions">
            @auth
                @if(auth()->user()->role == 'customer')
                    <div style="background: rgba(30, 111, 184, 0.15); color: #1E6FB8; border: 1px solid #1E6FB8; padding: 6px 12px; border-radius: 8px; font-size: 13px; font-weight: bold;">
                        رصيدي: {{ number_format(Auth::user()->wallet->balance ?? 0, 0) }} ل.س
                        <a href="{{ route('wallet.action') }}" style="background: #D4AF37; color: #0B1B3D; padding: 2px 8px; border-radius: 4px; text-decoration: none; margin-right: 5px;">شحن</a>
                    </div>
                    
                    <!-- القائمة المنسدلة الإيماءية للعميل -->
                    <div class="dropdown">
                        <button style="background: #1E6FB8; color: white; border: none; padding: 8px 15px; border-radius: 8px; cursor: pointer; font-family: 'Cairo'; font-weight: bold; display: flex; align-items: center; gap: 5px;">
                            👤 حسابي ▾
                        </button>
                        <div class="dropdown-content">
                            <a href="{{ route('customer.profile') }}">⚙️ معلومات الحساب</a>
                            <a href="{{ route('customer.orders') }}">📦 طلباتي</a>
                            <a href="{{ route('wishlist.index') }}">❤️ المفضلة</a>
                            <a href="{{ route('cart.index') }}">🛒 السلة</a>
                        </div>
                    </div>

                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">@csrf <button type="submit">خروج</button></form>

                @elseif(auth()->user()->role == 'vendor')
                    <div style="background: rgba(212, 175, 55, 0.15); color: #D4AF37; border: 1px solid #D4AF37; padding: 6px 12px; border-radius: 8px; font-size: 13px; font-weight: bold;">
                        أرباحي: {{ number_format(Auth::user()->wallet->balance ?? 0, 0) }} ل.س
                        <a href="{{ route('wallet.action') }}" style="background: #1E6FB8; color: white; padding: 2px 8px; border-radius: 4px; text-decoration: none; margin-right: 5px;">سحب</a>
                    </div>
                    <a href="{{ route('vendor.dashboard') }}">🏪 لوحة التحكم</a>
                    <a href="{{ route('vendor.orders') }}">📋 طلباتي</a>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline;">@csrf <button type="submit">خروج</button></form>
                @endif
            @else
                <a href="{{ route('register.vendor') }}" style="color: #D4AF37;">دخول تاجر</a>
                <a href="{{ route('register.customer') }}">دخول عميل</a>
            @endauth
        </div>
    </nav>

    @if(session('success'))
        <div style="background: #E5E7EB; color: #0B1B3D; border-right: 4px solid #D4AF37; padding: 15px; margin: 10px auto; max-width: 1100px; border-radius: 8px; text-align: center; font-weight: bold;">
            {{ session('success') }}
        </div>
    @endif

    <div class="container">
        @yield('content')
    </div>

    <footer><p>جميع الحقوق محفوظة لـ <span>فُرات ستور</span> © 2026</p></footer>
</body>
</html>