<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>منصة شام</title>
    <style>
        body { font-family: sans-serif; margin: 0; padding: 0; background-color: #fcfcfc; }
        
        /* الشريط العلوي */
        .top-bar { background: #f8f8f8; padding: 5px 20px; font-size: 12px; display: flex; justify-content: flex-end; gap: 15px; border-bottom: 1px solid #eee; }
        .top-bar a { color: #555; text-decoration: none; }

        /* الشريط الرئيسي */
        .main-nav { padding: 15px 20px; display: flex; align-items: center; justify-content: space-between; gap: 20px; background: #ffffff; border-bottom: 1px solid #eee; }

        /* الشعار (مع قلبه للألوان كما طلبت) */
        .brand-link { text-decoration: none; display: flex; flex-direction: column; }
        .brand-name { font-weight: bold; font-size: 1.4em; color: #f7931e; line-height: 1; }
        .brand-slogan { font-size: 0.7em; color: #333; margin-top: 5px; font-weight: bold; }

        .search-container { flex: 1; max-width: 500px; display: flex; }
        .search-container input { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px 0 0 4px; }
        .search-container button { padding: 10px 20px; background: #f7931e; border: none; color: white; border-radius: 0 4px 4px 0; cursor: pointer; }

        .nav-actions { display: flex; gap: 20px; align-items: center; }
        .nav-actions a, .nav-actions button { color: #333; text-decoration: none; font-weight: bold; border: none; background: none; cursor: pointer; font-size: 14px; }
        
        .dropdown { position: relative; }
        .dropdown-content { display: none; position: absolute; background: white; min-width: 150px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); padding: 10px; border-radius: 5px; top: 100%; right: 0; z-index: 10; }
        .dropdown:hover .dropdown-content { display: block; }
        .dropdown-content a, .dropdown-content button { display: block; padding: 5px 0; width: 100%; text-align: right; }

        .category-bar { background: white; padding: 10px 20px; display: flex; justify-content: center; gap: 20px; border-bottom: 1px solid #eee; }
        .category-bar a { text-decoration: none; color: #333; font-weight: bold; font-size: 14px; }
        
        .container { padding: 20px; }
        footer { background: #f8f8f8; padding: 40px 20px; border-top: 1px solid #ddd; display: flex; justify-content: space-around; flex-wrap: wrap; margin-top: 50px; }
        footer div h4 { color: #333; }
        footer div p { color: #666; font-size: 0.9em; cursor: pointer; }
    </style>
</head>
<body>

    <div class="top-bar">
        <a href="#">تغيير اللغة (AR)</a>
        <a href="#">المساعدة والدعم</a>
        <a href="#">نبذة عنا</a>
        <a href="#">البيع على منصة شام</a>
        <a href="#">كوبونات</a>
    </div>

    <nav class="main-nav">
        <a href="/" class="brand-link">
            <span class="brand-name">منصة شام</span>
            <span class="brand-slogan">كل ما تحتاج تحت سقف واحد</span>
        </a>

        <form class="search-container" action="{{ route('customer.dashboard') }}" method="GET">
            <input type="text" name="search" placeholder="ابحث عن منتج...">
            <button type="submit">🔍</button>
        </form>

        <div class="nav-actions">
            @auth
                @if(auth()->user()->role == 'customer')
                    <div class="dropdown">
                        <a href="#">👤 الحساب</a>
                        <div class="dropdown-content">
                            <a href="#">طلباتي</a>
                            <a href="#">بياناتي</a>
                            <form action="{{ route('logout') }}" method="POST">@csrf <button type="submit">خروج</button></form>
                        </div>
                    </div>
                    <a href="#">❤️ المفضلة</a>
                    <a href="#">🛒 السلة</a>
                @elseif(auth()->user()->role == 'vendor')
                    <a href="{{ route('vendor.products') }}">📦 منتجاتي</a>
                    <a href="#">📊 الطلبات</a>
                    <form action="{{ route('logout') }}" method="POST">@csrf <button type="submit">خروج</button></form>
                @endif
            @else
                <a href="{{ url('/login-as-vendor') }}" style="color: #d35400;">دخول تاجر</a>
                <a href="{{ url('/login-as-customer') }}" style="color: #27ae60;">دخول عميل</a>
            @endauth
        </div>
    </nav>

    <div class="category-bar">
        <a href="#">النساء</a> <a href="#">الرجال</a> <a href="#">مستحضرات التجميل</a> <a href="#">المنزل</a> <a href="#">أجهزة كهربائية</a> <a href="#">عروض</a>
    </div>

    <div class="container">
        @yield('content')
    </div>

    <footer>
        <div><h4>منصة شام</h4><p>نبذة عنا</p><p>تواصل معنا</p></div>
        <div><h4>الحملات</h4><p>عروض التسوق</p><p>أفكار الهدايا</p></div>
        <div><h4>البائعون</h4><p>البيع على منصة شام</p><p>أكاديمية التجار</p></div>
        <div><h4>المساعدة</h4><p>الأسئلة المتكررة</p><p>سياسة الإرجاع</p></div>
    </footer>
</body>
</html>