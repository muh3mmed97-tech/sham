<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>منصة شام</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;700&display=swap" rel="stylesheet">
    <style>
        /* التعديل الجوهري لضبط الـ Footer في الأسفل */
        html, body {
            height: 100%;
            margin: 0;
        }
        body {
            display: flex;
            flex-direction: column;
            font-family: 'Cairo', sans-serif;
            background-color: #eef2f7;
            background-image: radial-gradient(#d1d9e6 1px, transparent 1px);
            background-size: 20px 20px;
        }
        
        .top-bar { background: #005a9c; color: white; padding: 5px 20px; font-size: 12px; display: flex; justify-content: flex-end; gap: 15px; }
        .top-bar a { color: white; text-decoration: none; }

        .main-nav { padding: 15px 40px; display: flex; align-items: center; justify-content: space-between; background: #ffffff; border-bottom: 3px solid #005a9c; }
        .brand-name { font-weight: bold; font-size: 1.6em; color: #005a9c; }
        .nav-actions { display: flex; gap: 20px; align-items: center; }
        .nav-actions a { text-decoration: none; color: #333; font-weight: bold; }
        
        /* تنسيقات الأقسام الجديدة */
        .categories-bar {
            display: flex;
            justify-content: center;
            gap: 15px;
            padding: 15px;
            background: #ffffff;
            margin: 10px 40px;
            border-radius: 50px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.05);
            flex-wrap: wrap;
        }
        .cat-item {
            text-decoration: none;
            color: #005a9c;
            font-weight: bold;
            padding: 8px 20px;
            border-radius: 20px;
            background: #f4f4f4;
            transition: 0.3s;
        }
        .cat-item:hover {
            background: #005a9c;
            color: white;
        }

        .container { 
            flex: 1;
            padding: 20px 50px; 
        }

        footer { 
            background: #005a9c; 
            color: white; 
            padding: 20px; 
            text-align: center; 
        }
    </style>
</head>
<body>

    <div class="top-bar">
        <a href="#">المساعدة</a> | <a href="#">البيع على منصة شام</a>
    </div>

    <nav class="main-nav">
        <a href="/" style="text-decoration: none;"><span class="brand-name">منصة شام</span></a>

        <div class="nav-actions">
            @auth
                @if(auth()->user()->role == 'customer')
                    <div class="wallet-badge" style="background: #e8f5e9; color: #2e7d32; padding: 6px 12px; border-radius: 8px; font-size: 13px; font-weight: bold;">
                        رصيدي: {{ number_format(auth()->user()->wallet->balance ?? 0, 2) }} $
                        <a href="{{ route('wallet.action') }}" style="background: #2e7d32; color: white; padding: 2px 8px; border-radius: 4px; text-decoration: none; margin-right: 5px;">شحن</a>
                    </div>
                    <a href="{{ route('customer.orders') }}">📦 طلباتي</a>
                    <a href="{{ route('wishlist.index') }}">❤️ المفضلة</a>
                    <a href="{{ route('cart.index') }}">🛒 السلة</a>
                    <form action="{{ route('logout') }}" method="POST">@csrf <button type="submit" style="border:none; background:none; cursor:pointer; font-weight:bold;">خروج</button></form>
                @elseif(auth()->user()->role == 'vendor')
                    <div class="wallet-badge" style="background: #fff3e0; color: #e65100; padding: 6px 12px; border-radius: 8px; font-size: 13px; font-weight: bold;">
                        أرباحي: {{ number_format(auth()->user()->wallet->balance ?? 0, 2) }} $
                        <a href="{{ route('wallet.action') }}" style="background: #e65100; color: white; padding: 2px 8px; border-radius: 4px; text-decoration: none; margin-right: 5px;">سحب</a>
                    </div>
                    <a href="{{ route('vendor.products') }}">📦 منتجاتي</a>
                    <a href="{{ route('vendor.orders') }}">📋 طلباتي</a>
                    <form action="{{ route('logout') }}" method="POST">@csrf <button type="submit" style="border:none; background:none; cursor:pointer; font-weight:bold;">خروج</button></form>
                @endif
            @else
                <a href="{{ url('/login-as-vendor') }}">دخول تاجر</a>
                <a href="{{ url('/login-as-customer') }}">دخول عميل</a>
            @endauth
        </div>
    </nav>

    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 15px; margin: 20px; border-radius: 8px; text-align: center;">{{ session('success') }}</div>
    @endif

    <div class="container">
        @yield('content')
    </div>

    <footer><p>جميع الحقوق محفوظة لمنصة شام 2026</p></footer>
</body>
</html>