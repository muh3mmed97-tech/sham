<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>فُرات ستور | تجارتنا عهد، وثقتكم أمانة</title>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        html, body { height: 100%; margin: 0; padding: 0; font-family: 'Cairo', sans-serif; background-color: #F8FAFC; color: #0F172A; }
        body { display: flex; flex-direction: column; }
        
        /* شريط علوي موحد وهادئ */
        .top-bar { background: #071228; color: #94A3B8; padding: 6px 40px; font-size: 12px; display: flex; justify-content: flex-end; align-items: center; gap: 15px; border-bottom: 1px solid rgba(255,255,255,0.06); }
        .top-bar a { color: #CBD5E1; text-decoration: none; transition: color 0.2s; }
        .top-bar a:hover { color: #D4AF37; }
        
        /* شريط التنقل الأساسي الكحلي الفخم */
        .main-nav { padding: 12px 40px; display: flex; align-items: center; justify-content: space-between; background: #0B1B3D; border-bottom: 2px solid #D4AF37; }
        .brand-name { font-weight: 800; font-size: 1.35rem; color: #D4AF37; line-height: 1.1; }
        .slogan { font-size: 0.75rem; color: #94A3B8; }
        .nav-actions { display: flex; gap: 14px; align-items: center; }
        .nav-actions a, .nav-actions button { text-decoration: none; color: white; font-weight: 700; border: none; background: none; cursor: pointer; font-family: 'Cairo'; font-size: 0.9rem; transition: color 0.2s; }
        .nav-actions a:hover, .nav-actions button:hover { color: #D4AF37; }
        
        /* القائمة المنسدلة العامة */
        .dropdown { position: relative; display: inline-block; }
        .dropdown-content { display: none; position: absolute; left: 0; top: 38px; background: white; min-width: 180px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); border-radius: 8px; border: 1px solid #E2E8F0; z-index: 1000; overflow: hidden; text-align: right; }
        .dropdown-content a { display: block; padding: 10px 16px; color: #0B1B3D; text-decoration: none; border-bottom: 1px solid #F1F5F9; font-size: 0.85rem; font-weight: 600; }
        .dropdown-content a:hover { background: #F8FAFC; color: #1E6FB8; }
        .dropdown:hover .dropdown-content { display: block; }

        /* قائمة الإشعارات */
        .notif-container { position: relative; display: inline-block; }
        .notif-btn { position: relative; font-size: 1.25rem; display: flex; align-items: center; padding: 4px; }
        .notif-badge { position: absolute; top: -4px; right: -6px; background: #EF4444; color: white; border-radius: 50%; padding: 2px 6px; font-size: 0.7rem; font-weight: bold; border: 2px solid #0B1B3D; }
        .notif-dropdown { display: none; position: absolute; left: -80px; top: 40px; width: 330px; background: white; border: 1px solid #E2E8F0; border-radius: 12px; box-shadow: 0 12px 30px rgba(0,0,0,0.18); z-index: 1100; text-align: right; overflow: hidden; }
        .notif-dropdown.show { display: block; }
        .notif-header { padding: 12px 16px; background: #0B1B3D; color: white; display: flex; justify-content: space-between; align-items: center; font-size: 0.9rem; font-weight: bold; }
        .notif-body { max-height: 320px; overflow-y: auto; }
        .notif-item { padding: 12px 14px; border-bottom: 1px solid #F1F5F9; transition: background 0.2s; }
        .notif-item:hover { background: #F8FAFC; }
        .notif-item.unread { background: #F0F9FF; border-right: 3px solid #1E6FB8; }

        .container { flex: 1; padding: 20px 30px; }
        footer { background: #0B1B3D; color: #94A3B8; padding: 18px; text-align: center; border-top: 2px solid #1E6FB8; font-size: 0.85rem; }
        footer span { color: #D4AF37; font-weight: 700; }
    </style>
</head>
<body>

    <div class="top-bar">
        <a href="#">المساعدة</a>
        @guest
            <span>|</span> <a href="{{ route('register.vendor') }}">البيع على فُرات ستور</a>
            <span>|</span> <a href="{{ route('login') }}" style="color: #D4AF37; font-weight: bold;">تسجيل الدخول</a>
        @endguest
    </div>

    <nav class="main-nav">
        <a href="{{ route('home') }}" style="text-decoration: none; display: flex; align-items: center; gap: 12px;">
            <img src="{{ asset('images/logo.png') }}" alt="فُرات ستور" style="height: 44px; width: auto; border-radius: 6px; border: 1px solid #D4AF37; object-fit: cover;">            
            <div style="display: flex; flex-direction: column;">
                <span class="brand-name">فُرات ستور</span>
                <span class="slogan">تجارتنا عهد، وثقتكم أمانة</span>
            </div>
        </a>

        <div class="nav-actions">
            @auth
                <!-- أيقونة وقائمة الإشعارات المشتركة للعميل والتاجر -->
                @php
                    $unreadCount = Auth::user()->unreadNotifications->count();
                @endphp
                <div class="notif-container">
                    <button class="notif-btn" onclick="toggleNotifications(event)" title="الإشعارات">
                        🔔
                        @if($unreadCount > 0)
                            <span class="notif-badge">{{ $unreadCount }}</span>
                        @endif
                    </button>

                    <div id="notif-menu" class="notif-dropdown">
                        <div class="notif-header">
                            <span>الإشعارات 🔔</span>
                            <span style="font-size: 0.75rem; color: #D4AF37;">{{ $unreadCount }} غير مقروء</span>
                        </div>
                        
                        <div class="notif-body">
                            @forelse(Auth::user()->notifications->take(6) as $notification)
                                <div class="notif-item {{ $notification->read_at ? '' : 'unread' }}">
                                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 4px;">
                                        <strong style="color: #0B1B3D; font-size: 0.85rem;">{{ $notification->data['title'] ?? 'إشعار' }}</strong>
                                        <small style="color: #94A3B8; font-size: 0.7rem;">{{ $notification->created_at->diffForHumans() }}</small>
                                    </div>
                                    <p style="margin: 0 0 6px 0; color: #475569; font-size: 0.8rem; line-height: 1.4;">{{ $notification->data['message'] ?? '' }}</p>
                                    
                                    <div style="display: flex; justify-content: space-between; align-items: center;">
                                        @if(isset($notification->data['url']))
                                            <a href="{{ $notification->data['url'] }}" style="color: #1E6FB8; font-size: 0.75rem; text-decoration: none;">عرض التفاصيل ←</a>
                                        @else
                                            <span></span>
                                        @endif

                                        @if(!$notification->read_at)
                                            <form action="{{ route('notifications.read', $notification->id) }}" method="POST" style="margin: 0;">
                                                @csrf
                                                <button type="submit" style="color: #64748B; font-size: 0.75rem; text-decoration: underline; padding: 0;">تعليم كمقروء</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div style="padding: 25px; text-align: center; color: #94A3B8; font-size: 0.85rem;">
                                    لا توجد أي إشعارات حالياً.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                @if(auth()->user()->role == 'customer')
                    <!-- محفظة العميل -->
                    <div style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(212, 175, 55, 0.3); padding: 5px 12px; border-radius: 6px; font-size: 13px; color: #FFFFFF;">
                        رصيدي: <strong style="color: #D4AF37;">{{ number_format(Auth::user()->wallet->balance ?? 0, 0) }} ل.س</strong>
                        <a href="{{ route('wallet.action') }}" style="background: #D4AF37; color: #0B1B3D; padding: 2px 7px; border-radius: 4px; text-decoration: none; margin-right: 6px; font-size: 11px;">+ شحن</a>
                    </div>
                    
                    <div class="dropdown">
                        <button style="background: #1E6FB8; color: white; border: none; padding: 7px 14px; border-radius: 6px; cursor: pointer; font-family: 'Cairo'; font-weight: bold;">
                            حسابي ▾
                        </button>
                        <div class="dropdown-content">
                            <a href="{{ route('customer.profile') }}">⚙️ بيانات الحساب</a>
                            <a href="{{ route('customer.orders') }}">📦 طلباتي</a>
                            <a href="{{ route('wishlist.index') }}">❤️ المفضلة</a>
                            <a href="{{ route('cart.index') }}">🛒 السلة</a>
                        </div>
                    </div>

                    <form action="{{ route('logout') }}" method="POST" style="display: inline; margin: 0;">
                        @csrf 
                        <button type="submit" style="color: #FDA4AF;">خروج</button>
                    </form>

                @elseif(auth()->user()->role == 'vendor')
                    <!-- محفظة التاجر -->
                    <div style="background: rgba(212, 175, 55, 0.15); border: 1px solid #D4AF37; padding: 5px 12px; border-radius: 6px; font-size: 13px; color: #D4AF37;">
                        أرباحي: <strong>{{ number_format(Auth::user()->wallet->balance ?? 0, 0) }} ل.س</strong>
                        <a href="{{ route('wallet.action') }}" style="background: #1E6FB8; color: white; padding: 2px 7px; border-radius: 4px; text-decoration: none; margin-right: 6px; font-size: 11px;">سحب</a>
                    </div>
                    <a href="{{ route('vendor.dashboard') }}">🏪 لوحة التحكم</a>
                    <a href="{{ route('vendor.orders') }}">📋 الطلبات</a>
                    <a href="{{ route('vendor.settings') }}">⚙️ إعدادات المتجر</a>
                    <form action="{{ route('logout') }}" method="POST" style="display: inline; margin: 0;">
                        @csrf 
                        <button type="submit" style="color: #FDA4AF;">خروج</button>
                    </form>
                @endif
            @else
                <a href="{{ route('register.vendor') }}" style="color: #D4AF37;">انضم كتاجر</a>
                <a href="{{ route('register.customer') }}">إنشاء حساب</a>
                <a href="{{ route('login') }}" style="background: #1E6FB8; padding: 6px 14px; border-radius: 6px;">دخول</a>
            @endauth
        </div>
    </nav>

    @if(session('success'))
        <div style="background: #F0FDF4; color: #166534; border-right: 4px solid #16A34A; padding: 12px 20px; margin: 15px auto 0; max-width: 1150px; border-radius: 6px; font-weight: bold; font-size: 0.9rem;">
            {{ session('success') }}
        </div>
    @endif

    <div class="container">
        @yield('content')
    </div>

    <footer>
        جميع الحقوق محفوظة لـ <span>فُرات ستور</span> © 2026
    </footer>

    <script>
        function toggleNotifications(event) {
            event.stopPropagation();
            var menu = document.getElementById('notif-menu');
            if (menu) {
                menu.classList.toggle('show');
            }
        }

        // إغلاق القائمة عند النقر في أي مكان خارجها
        document.addEventListener('click', function(e) {
            var menu = document.getElementById('notif-menu');
            if (menu && !menu.contains(e.target)) {
                menu.classList.remove('show');
            }
        });
    </script>
</body>
</html>