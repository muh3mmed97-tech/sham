@extends('layouts.app')

@section('content')
<div style="max-width: 1250px; margin: 15px auto 50px; padding: 0 15px; font-family: 'Cairo', sans-serif;" dir="rtl">

    <!-- 1. شريط البحث السريع والذكي -->
    <div style="text-align: center; margin-bottom: 25px;">
        <form action="{{ route('home') }}" method="GET" style="display: flex; justify-content: center; gap: 10px; max-width: 600px; margin: 0 auto;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث عن منتجك، قسم، أو ماركة..." 
                   style="flex: 1; padding: 13px 24px; border: 1.5px solid #CBD5E1; border-radius: 30px; outline: none; font-family: 'Cairo'; font-size: 0.95rem; box-shadow: 0 3px 10px rgba(0,0,0,0.03);">
            <button type="submit" style="padding: 13px 30px; background: #0B1B3D; color: #D4AF37; border: none; border-radius: 30px; font-weight: bold; cursor: pointer; font-family: 'Cairo'; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(11,27,61,0.15);">
                🔍 بحث
            </button>
        </form>
    </div>

    <!-- 2. الأقسام الدائرية السريعة بتصميم Trendyol (Circle Badges) -->
    <div class="circle-cat-wrapper" style="margin-bottom: 30px; overflow-x: auto; padding: 10px 5px; white-space: nowrap; scrollbar-width: none;">
        <div style="display: inline-flex; gap: 18px; justify-content: center; min-width: 100%;">
            
            <!-- الكل -->
            <a href="{{ route('home') }}" style="text-decoration: none; text-align: center;">
                <div class="circle-cat-item {{ !request()->route('category') && !request('category') ? 'circle-active' : '' }}">
                    <div class="circle-avatar">🔲</div>
                    <span class="circle-text">الكل</span>
                </div>
            </a>

            <!-- إلكترونيات -->
            <a href="{{ route('home', 'إلكترونيات') }}" style="text-decoration: none; text-align: center;">
                <div class="circle-cat-item {{ (request()->route('category') == 'إلكترونيات') ? 'circle-active' : '' }}">
                    <div class="circle-avatar">📱</div>
                    <span class="circle-text">إلكترونيات</span>
                </div>
            </a>

            <!-- أدوات منزلية -->
            <a href="{{ route('home', 'أدوات منزلية') }}" style="text-decoration: none; text-align: center;">
                <div class="circle-cat-item {{ (request()->route('category') == 'أدوات منزلية') ? 'circle-active' : '' }}">
                    <div class="circle-avatar">🏠</div>
                    <span class="circle-text">أدوات منزلية</span>
                </div>
            </a>

            <!-- أزياء -->
            <a href="{{ route('home', 'أزياء') }}" style="text-decoration: none; text-align: center;">
                <div class="circle-cat-item {{ (request()->route('category') == 'أزياء') ? 'circle-active' : '' }}">
                    <div class="circle-avatar">👕</div>
                    <span class="circle-text">أزياء</span>
                </div>
            </a>

            <!-- مأكولات ومشروبات -->
            <a href="{{ route('home', 'مأكولات ومشروبات') }}" style="text-decoration: none; text-align: center;">
                <div class="circle-cat-item {{ (request()->route('category') == 'مأكولات ومشروبات') ? 'circle-active' : '' }}">
                    <div class="circle-avatar">☕</div>
                    <span class="circle-text">مأكولات ومشروبات</span>
                </div>
            </a>

            <!-- رياضة -->
            <a href="{{ route('home', 'رياضة') }}" style="text-decoration: none; text-align: center;">
                <div class="circle-cat-item {{ (request()->route('category') == 'رياضة') ? 'circle-active' : '' }}">
                    <div class="circle-avatar">⚽</div>
                    <span class="circle-text">رياضة</span>
                </div>
            </a>

            <!-- مستحضرات تجميل -->
            <a href="{{ route('home', 'مستحضرات تجميل') }}" style="text-decoration: none; text-align: center;">
                <div class="circle-cat-item {{ (request()->route('category') == 'مستحضرات تجميل') ? 'circle-active' : '' }}">
                    <div class="circle-avatar">💄</div>
                    <span class="circle-text">مستحضرات تجميل</span>
                </div>
            </a>

        </div>
    </div>

    <!-- 3. كتل الخصومات الترويجية السريعة (Discount Bricks) -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 15px; margin-bottom: 35px;">
        <div class="promo-brick" style="background: linear-gradient(135deg, #FFF7ED 0%, #FFEDD5 100%); border-right: 4px solid #EA580C;">
            <span style="font-size: 1.8rem; font-weight: 800; color: #EA580C;">30%</span>
            <div>
                <strong style="color: #0B1B3D; display: block; font-size: 0.95rem;">تخفيضات الأسبوع</strong>
                <span style="color: #64748B; font-size: 0.8rem;">على كافة الإلكترونيات</span>
            </div>
        </div>
        <div class="promo-brick" style="background: linear-gradient(135deg, #FEFCE8 0%, #FEF08A 100%); border-right: 4px solid #CA8A04;">
            <span style="font-size: 1.8rem; font-weight: 800; color: #CA8A04;">50%</span>
            <div>
                <strong style="color: #0B1B3D; display: block; font-size: 0.95rem;">عروض وتصفيات</strong>
                <span style="color: #64748B; font-size: 0.8rem;">قسم الأزياء والموضة</span>
            </div>
        </div>
        <div class="promo-brick" style="background: linear-gradient(135deg, #EFF6FF 0%, #DBEAFE 100%); border-right: 4px solid #1E6FB8;">
            <span style="font-size: 1.8rem; font-weight: 800; color: #1E6FB8;">مجاناً</span>
            <div>
                <strong style="color: #0B1B3D; display: block; font-size: 0.95rem;">شحن وتوصيل</strong>
                <span style="color: #64748B; font-size: 0.8rem;">للطلبات الأكثر من 250 ألف</span>
            </div>
        </div>
        <div class="promo-brick" style="background: linear-gradient(135deg, #ECFDF5 0%, #D1FAE5 100%); border-right: 4px solid #059669;">
            <span style="font-size: 1.8rem; font-weight: 800; color: #059669;">100%</span>
            <div>
                <strong style="color: #0B1B3D; display: block; font-size: 0.95rem;">ضمان الأمانة</strong>
                <span style="color: #64748B; font-size: 0.8rem;">تجارتنا عهد وثقتكم أمانة</span>
            </div>
        </div>
    </div>

    <!-- 4. سلايدر "المنتجات الأكثر طلباً" بنمط Trendyol الأفقي -->
    <div style="background: #FFFFFF; border-radius: 20px; padding: 22px; margin-bottom: 40px; border: 1px solid #E2E8F0; box-shadow: 0 4px 15px rgba(0,0,0,0.03); position: relative;">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px;">
            <div style="display: flex; align-items: center; gap: 8px;">
                <span style="font-size: 1.4rem;">🔥</span>
                <h3 style="margin: 0; color: #0B1B3D; font-size: 1.3rem; font-weight: 800;">المنتجات الأكثر طلباً</h3>
            </div>
            
            <div style="display: flex; gap: 8px;">
                <button type="button" onclick="scrollSlider('right')" class="slider-nav-btn">❯</button>
                <button type="button" onclick="scrollSlider('left')" class="slider-nav-btn">❮</button>
            </div>
        </div>

        <div id="trendyolSlider" style="display: flex; gap: 16px; overflow-x: auto; scroll-behavior: smooth; padding-bottom: 10px; scrollbar-width: none;">
            @forelse($products as $product)
                <div class="trendy-product-card" style="min-width: 220px; max-width: 220px; flex-shrink: 0;">
                    
                    <div class="trendy-img-box">
                        <span class="badge-badge badge-bestseller">الأكثر طلباً 🔥</span>
                        <span class="badge-badge badge-shipping">شحن سريع</span>
                        
                        <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" style="position: absolute; top: 8px; left: 8px; z-index: 5;">
                            @csrf
                            <button type="submit" class="floating-heart-btn" title="إضافة للمفضلة">❤️</button>
                        </form>

                        <a href="{{ route('product.show', $product->id) }}">
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 onerror="this.src='https://placehold.co/300x200?text=Furat+Store'" 
                                 class="trendy-product-img">
                        </a>
                    </div>

                    <div style="padding: 12px; display: flex; flex-direction: column; flex: 1;">
                        <span style="font-size: 0.75rem; color: #94A3B8; font-weight: 600;">{{ $product->category->name ?? $product->category ?? 'قسم عام' }}</span>
                        
                        <a href="{{ route('product.show', $product->id) }}" style="text-decoration: none; color: #0B1B3D; font-weight: 700; font-size: 0.95rem; margin: 4px 0 8px; min-height: 38px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $product->name }}
                        </a>

                        <div style="display: flex; align-items: center; gap: 5px; margin-bottom: 8px; font-size: 0.8rem;">
                            <span style="color: #F59E0B;">★★★★★</span>
                            <span style="color: #64748B; font-weight: bold;">(4.9)</span>
                        </div>

                        <div style="margin-top: auto;">
                            <div style="margin-bottom: 10px;">
                                <span style="color: #0B1B3D; font-weight: 800; font-size: 1.2rem;">
                                    {{ number_format($product->price, 0) }}
                                </span>
                                <span style="font-size: 0.8rem; color: #64748B;"> ل.س</span>
                            </div>

                            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="trendy-cart-btn">
                                    🛒 إضافة للسلة
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            @empty
                <div style="width: 100%; text-align: center; padding: 40px; color: #64748B;">
                    لا توجد منتجات معروضة حالياً.
                </div>
            @endforelse
        </div>

    </div>

    <!-- 5. شبكة جميع المنتجات المتوفرة (مع التصفية والتقسيم Pagination) -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h3 style="margin: 0; color: #0B1B3D; font-size: 1.3rem; font-weight: 800;">
            {{ request('category') ? 'منتجات قسم: ' . request('category') : 'جميع المنتجات المقترحة' }}
        </h3>
        <span style="color: #64748B; font-size: 0.9rem;">
            إجمالي المنتجات: <strong>{{ $products->total() }}</strong> منتج
        </span>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(230px, 1fr)); gap: 20px; margin-bottom: 35px;">
        @forelse($products as $product)
            <div class="trendy-product-card">
                
                <div class="trendy-img-box">
                    <span class="badge-badge badge-bestseller">الأكثر طلباً 🔥</span>
                    
                    <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" style="position: absolute; top: 8px; left: 8px; z-index: 5;">
                        @csrf
                        <button type="submit" class="floating-heart-btn">❤️</button>
                    </form>

                    <a href="{{ route('product.show', $product->id) }}">
                        <img src="{{ asset('storage/' . $product->image) }}" 
                             onerror="this.src='https://placehold.co/300x200?text=Furat+Store'" 
                             class="trendy-product-img">
                    </a>
                </div>

                <div style="padding: 14px; display: flex; flex-direction: column; flex: 1;">
                    <span style="font-size: 0.75rem; color: #94A3B8; font-weight: 600;">{{ $product->category->name ?? $product->category ?? 'عام' }}</span>

                    <a href="{{ route('product.show', $product->id) }}" style="text-decoration: none; color: #0B1B3D; font-weight: 700; font-size: 0.95rem; margin: 4px 0 8px; min-height: 38px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                        {{ $product->name }}
                    </a>

                    <div style="display: flex; align-items: center; gap: 5px; margin-bottom: 8px; font-size: 0.8rem;">
                        <span style="color: #F59E0B;">★★★★★</span>
                        <span style="color: #64748B; font-weight: bold;">(4.9)</span>
                    </div>

                    <div style="margin-top: auto;">
                        <div style="margin-bottom: 10px;">
                            <span style="color: #0B1B3D; font-weight: 800; font-size: 1.25rem;">
                                {{ number_format($product->price, 0) }}
                            </span>
                            <span style="font-size: 0.8rem; color: #64748B;"> ل.س</span>
                        </div>

                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="trendy-cart-btn">
                                🛒 إضافة للسلة
                            </button>
                        </form>
                    </div>
                </div>

            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 50px 20px; background: white; border-radius: 16px; border: 1px dashed #CBD5E1;">
                <p style="font-size: 1.1rem; color: #64748B; margin: 0;">لا توجد منتجات مسجلة في هذا القسم حتى الآن.</p>
            </div>
        @endforelse
    </div>

    <!-- روابط التقسيم والتنقل بين الصفحات Pagination -->
    <div style="display: flex; justify-content: center; margin-top: 30px;">
        {{ $products->links() }}
    </div>

</div>

<!-- التنسيقات العصرية بنمط Trendyol وهيكلية فُرات ستور -->
<style>
    /* دوائر الأقسام السريعة (Circle Badges) */
    .circle-cat-item {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        transition: transform 0.2s;
    }
    .circle-cat-item:hover {
        transform: translateY(-3px);
    }
    .circle-avatar {
        width: 62px;
        height: 62px;
        background: #FFFFFF;
        border: 2px solid #E2E8F0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.6rem;
        box-shadow: 0 4px 10px rgba(0,0,0,0.04);
        transition: all 0.2s;
    }
    .circle-text {
        font-size: 0.85rem;
        font-weight: 700;
        color: #0B1B3D;
        white-space: nowrap;
    }
    .circle-active .circle-avatar {
        border-color: #D4AF37;
        background: #0B1B3D;
        transform: scale(1.05);
        box-shadow: 0 4px 14px rgba(11,27,61,0.2);
    }
    .circle-active .circle-text {
        color: #1E6FB8;
    }

    /* كتل الخصومات السريعة (Discount Bricks) */
    .promo-brick {
        padding: 16px 20px;
        border-radius: 14px;
        display: flex;
        align-items: center;
        gap: 16px;
        box-shadow: 0 3px 8px rgba(0,0,0,0.03);
        transition: transform 0.2s;
    }
    .promo-brick:hover {
        transform: translateY(-2px);
    }

    /* بطاقة المنتج العصرية بنمط Trendyol */
    .trendy-product-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 16px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: all 0.25s ease;
        box-shadow: 0 4px 12px rgba(11,27,61,0.03);
    }
    .trendy-product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 10px 24px rgba(11,27,61,0.1);
        border-color: #D4AF37;
    }

    .trendy-img-box {
        position: relative;
        height: 190px;
        background: #F8FAFC;
        overflow: hidden;
    }
    .trendy-product-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.35s ease;
    }
    .trendy-product-card:hover .trendy-product-img {
        transform: scale(1.05);
    }

    /* الشارات العائمة */
    .badge-badge {
        position: absolute;
        z-index: 4;
        font-size: 0.7rem;
        font-weight: 800;
        padding: 3px 8px;
        border-radius: 6px;
    }
    .badge-bestseller {
        top: 8px;
        right: 8px;
        background: #EA580C;
        color: white;
    }
    .badge-shipping {
        bottom: 8px;
        right: 8px;
        background: rgba(16, 185, 129, 0.95);
        color: white;
    }

    /* زر القلب العائم */
    .floating-heart-btn {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 50%;
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: 0 2px 6px rgba(0,0,0,0.1);
        transition: transform 0.2s, background 0.2s;
    }
    .floating-heart-btn:hover {
        transform: scale(1.15);
        background: #FEF2F2;
    }

    /* زر إضافة للسلة الذهبي-الكحلي */
    .trendy-cart-btn {
        width: 100%;
        padding: 9px;
        background: #0B1B3D;
        color: #D4AF37;
        border: none;
        border-radius: 8px;
        font-weight: bold;
        font-size: 0.85rem;
        cursor: pointer;
        font-family: 'Cairo';
        transition: all 0.2s;
    }
    .trendy-cart-btn:hover {
        background: #1E6FB8;
        color: white;
    }

    /* أزرار السلايدر التفاعلي */
    .slider-nav-btn {
        background: #F1F5F9;
        border: 1px solid #CBD5E1;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        cursor: pointer;
        font-weight: bold;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
        color: #0B1B3D;
    }
    .slider-nav-btn:hover {
        background: #0B1B3D;
        color: #D4AF37;
        border-color: #0B1B3D;
    }
</style>

<!-- سكربت التحكم في سلايدر المنتجات بالسهم -->
<script>
    function scrollSlider(direction) {
        const slider = document.getElementById('trendyolSlider');
        const scrollAmount = 260;
        if (direction === 'left') {
            slider.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
        } else {
            slider.scrollBy({ left: scrollAmount, behavior: 'smooth' });
        }
    }
</script>
@endsection