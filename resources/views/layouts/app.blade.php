<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>منصة شام | تكنولوجيا التجارة المتكاملة</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <style>
        html, body { height: 100%; margin: 0; font-family: 'Cairo', sans-serif; }
        body {
            display: flex; flex-direction: column;
            background-color: #f4f7f6;
            background-image: url('https://www.transparenttextures.com/patterns/arabesque.png');
            background-repeat: repeat;
        }

        .top-bar { background: #005a9c; color: white; padding: 5px 20px; font-size: 12px; display: flex; justify-content: flex-end; gap: 15px; }
        .top-bar a { color: white; text-decoration: none; }

        .main-nav { padding: 15px 40px; display: flex; align-items: center; justify-content: space-between; background: rgba(255, 255, 255, 0.95); border-bottom: 3px solid #005a9c; backdrop-filter: blur(5px); }
        .brand-name { font-weight: bold; font-size: 1.6em; color: #005a9c; line-height: 1; }
        .slogan { font-size: 0.8rem; color: #666; margin-top: 5px; }
        .nav-actions { display: flex; gap: 20px; align-items: center; }
        .nav-actions a, .nav-actions button { text-decoration: none; color: #333; font-weight: bold; border: none; background: none; cursor: pointer; font-family: 'Cairo'; }
        
        /* تنسيق الأقسام وتأثير التظليل (Hover) */
        .categories-bar { display: flex; justify-content: center; gap: 10px; margin-bottom: 30px; flex-wrap: wrap; }
        .cat-item {
            text-decoration: none; color: #005a9c; font-weight: bold; padding: 10px 20px;
            border-radius: 20px; background: #f4f4f4; transition: all 0.3s ease;
        }
        .cat-item:hover { background: #005a9c; color: white; }

        .container { flex: 1; padding: 20px 50px; }
        footer { background: #005a9c; color: white; padding: 20px; text-align: center; }
    </style>
</head>
<body>

    <div class="top-bar">
        <a href="#">المساعدة</a> | <a href="#">البيع على منصة شام</a>
    </div>

    <nav class="main-nav">
        <a href="{{ route('home') }}" style="text-decoration: none; display: flex; flex-direction: column;">
            <span class="brand-name">منصة شام</span>
            <span class="slogan"> كل ماتحتاجه تحت سقف واحد</span>
        </a>

        <div class="nav-actions">
            @auth
                @if(auth()->user()->role == 'customer')
                    <div style="background: #e8f5e9; color: #2e7d32; padding: 6px 12px; border-radius: 8px; font-size: 13px; font-weight: bold;">
                        رصيدي: {{ number_format(auth()->user()->wallet->balance ?? 0, 2) }} $
                        <a href="{{ route('wallet.action') }}" style="background: #2e7d32; color: white; padding: 2px 8px; border-radius: 4px; text-decoration: none; margin-right: 5px;">شحن</a>
                    </div>
                    <a href="{{ route('customer.orders') }}">📦 طلباتي</a>
                    <a href="{{ route('wishlist.index') }}">❤️ المفضلة</a>
                    <a href="{{ route('cart.index') }}">🛒 السلة</a>
                    <form action="{{ route('logout') }}" method="POST">@csrf <button type="submit">خروج</button></form>
                @elseif(auth()->user()->role == 'vendor')
                    <div style="background: #fff3e0; color: #e65100; padding: 6px 12px; border-radius: 8px; font-size: 13px; font-weight: bold;">
                        أرباحي: {{ number_format(auth()->user()->wallet->balance ?? 0, 2) }} $
                        <a href="{{ route('wallet.action') }}" style="background: #e65100; color: white; padding: 2px 8px; border-radius: 4px; text-decoration: none; margin-right: 5px;">سحب</a>
                    </div>
                    <a href="{{ route('vendor.dashboard') }}">🏪 لوحة التحكم</a>
                    <a href="{{ route('vendor.orders') }}">📋 طلباتي</a>
                    <form action="{{ route('logout') }}" method="POST">@csrf <button type="submit">خروج</button></form>
                @endif
            @else
                <a href="{{ url('/login-as-vendor') }}">دخول تاجر</a>
                <a href="{{ url('/login-as-customer') }}">دخول عميل</a>
            @endauth
        </div>
    </nav>

    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 15px; margin: 20px auto; max-width: 1100px; border-radius: 8px; text-align: center;">
            {{ session('success') }}
        </div>
    @endif

    <div class="container">
        @yield('content')
    </div>

    <footer><p>جميع الحقوق محفوظة لمنصة شام 2026</p></footer>
</body>
</html>