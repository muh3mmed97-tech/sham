@extends('layouts.app')

@section('content')
<style>
    .furat-wrap {
        max-width: 1180px;
        margin: 10px auto 40px;
        font-family: 'Cairo', sans-serif;
    }

    /* 1. حقل البحث المركزي */
    .search-box-bar {
        max-width: 580px;
        margin: 0 auto 30px;
        position: relative;
    }
    .search-box-bar input {
        width: 100%;
        padding: 12px 20px 12px 105px;
        border: 1.5px solid #CBD5E1;
        border-radius: 25px;
        font-family: 'Cairo';
        font-size: 0.95rem;
        outline: none;
        background: #FFFFFF;
        transition: border-color 0.2s;
    }
    .search-box-bar input:focus {
        border-color: #0B1B3D;
    }
    .search-box-bar button {
        position: absolute;
        left: 4px;
        top: 4px;
        bottom: 4px;
        padding: 0 22px;
        background: #0B1B3D;
        color: #D4AF37;
        border: none;
        border-radius: 20px;
        font-weight: 700;
        cursor: pointer;
        font-family: 'Cairo';
        font-size: 0.85rem;
    }
    .search-box-bar button:hover {
        background: #1E6FB8;
        color: #FFFFFF;
    }

    /* 2. دوائر الأقسام النظيفة */
    .categories-strip {
        display: flex;
        justify-content: center;
        gap: 22px;
        flex-wrap: wrap;
        margin-bottom: 30px;
    }
    .cat-btn {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 6px;
        text-decoration: none;
        width: 70px;
        cursor: pointer;
    }
    .cat-btn-icon {
        width: 60px;
        height: 60px;
        background: #FFFFFF;
        border: 1.5px solid #E2E8F0;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        transition: all 0.2s ease;
    }
    .cat-btn:hover .cat-btn-icon {
        border-color: #D4AF37;
        transform: translateY(-2px);
    }
    .cat-btn.active .cat-btn-icon {
        border-color: #0B1B3D;
        background: #0B1B3D;
    }
    .cat-btn-title {
        color: #475569;
        font-size: 0.8rem;
        font-weight: 700;
        text-align: center;
    }
    .cat-btn.active .cat-btn-title {
        color: #0B1B3D;
        font-weight: 800;
    }

    /* 3. كتل الإعلانات الهادئة */
    .promo-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 15px;
        margin-bottom: 30px;
    }
    .promo-item {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 10px;
        padding: 14px 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    /* 4. كروت المنتجات الاحترافية */
    .product-section {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 12px;
        padding: 22px;
    }
    .product-grid-layout {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(215px, 1fr));
        gap: 18px;
    }
    .clean-card {
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 10px;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        transition: border-color 0.2s, box-shadow 0.2s;
    }
    .clean-card:hover {
        border-color: #CBD5E1;
        box-shadow: 0 6px 18px rgba(0,0,0,0.06);
    }
    .clean-img-box {
        height: 190px;
        background: #F8FAFC;
        position: relative;
    }
    .clean-img-box img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    .heart-action-btn {
        position: absolute;
        top: 8px;
        left: 8px;
        background: #FFFFFF;
        border: 1px solid #E2E8F0;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 0.9rem;
    }
    .badge-top {
        position: absolute;
        top: 8px;
        right: 8px;
        background: #0B1B3D;
        color: #D4AF37;
        font-size: 0.7rem;
        font-weight: 700;
        padding: 2px 7px;
        border-radius: 4px;
    }
    .clean-details {
        padding: 12px;
        display: flex;
        flex-direction: column;
        flex: 1;
    }
    .clean-btn-cart {
        width: 100%;
        padding: 9px;
        background: #0B1B3D;
        color: #D4AF37;
        border: none;
        border-radius: 6px;
        font-weight: 700;
        font-size: 0.85rem;
        cursor: pointer;
        font-family: 'Cairo';
        transition: background 0.2s;
    }
    .clean-btn-cart:hover {
        background: #1E6FB8;
        color: #FFFFFF;
    }
</style>

<div class="furat-wrap" dir="rtl">

    <!-- شريط البحث -->
    <div class="search-box-bar">
        <form action="{{ route('customer.dashboard') }}" method="GET" style="margin: 0;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث عما ترغب بشرائه اليوم...">
            <button type="submit">بحث</button>
        </form>
    </div>

    <!-- الأقسام المتناسقة -->
    <div class="categories-strip">
        <a href="{{ route('customer.dashboard') }}" class="cat-btn {{ !request('category') ? 'active' : '' }}">
            <div class="cat-btn-icon">🔲</div>
            <span class="cat-btn-title">الكل</span>
        </a>
        <a href="{{ route('customer.dashboard', ['category' => 'إلكترونيات']) }}" class="cat-btn {{ request('category') == 'إلكترونيات' ? 'active' : '' }}">
            <div class="cat-btn-icon">📱</div>
            <span class="cat-btn-title">إلكترونيات</span>
        </a>
        <a href="{{ route('customer.dashboard', ['category' => 'أدوات منزلية']) }}" class="cat-btn {{ request('category') == 'أدوات منزلية' ? 'active' : '' }}">
            <div class="cat-btn-icon">🏠</div>
            <span class="cat-btn-title">أدوات منزلية</span>
        </a>
        <a href="{{ route('customer.dashboard', ['category' => 'أزياء']) }}" class="cat-btn {{ request('category') == 'أزياء' ? 'active' : '' }}">
            <div class="cat-btn-icon">👕</div>
            <span class="cat-btn-title">أزياء</span>
        </a>
        <a href="{{ route('customer.dashboard', ['category' => 'مأكولات ومشروبات']) }}" class="cat-btn {{ request('category') == 'مأكولات ومشروبات' ? 'active' : '' }}">
            <div class="cat-btn-icon">☕</div>
            <span class="cat-btn-title">مأكولات</span>
        </a>
        <a href="{{ route('customer.dashboard', ['category' => 'رياضة']) }}" class="cat-btn {{ request('category') == 'رياضة' ? 'active' : '' }}">
            <div class="cat-btn-icon">⚽</div>
            <span class="cat-btn-title">رياضة</span>
        </a>
        <a href="{{ route('customer.dashboard', ['category' => 'مستحضرات تجميل']) }}" class="cat-btn {{ request('category') == 'مستحضرات تجميل' ? 'active' : '' }}">
            <div class="cat-btn-icon">💄</div>
            <span class="cat-btn-title">تجميل</span>
        </a>
    </div>

    <!-- كتل التنبيه والمزايا (ألوان هادئة دون تداخل) -->
    <div class="promo-row">
        <div class="promo-item" style="border-right: 3px solid #0B1B3D;">
            <div>
                <strong style="color: #0B1B3D; display: block; font-size: 0.9rem;">أفضل الأسعار</strong>
                <span style="color: #64748B; font-size: 0.75rem;">عروض وتخفيضات مستمرة</span>
            </div>
            <span style="font-size: 1.25rem; color: #D4AF37;">⭐</span>
        </div>
        <div class="promo-item" style="border-right: 3px solid #1E6FB8;">
            <div>
                <strong style="color: #0B1B3D; display: block; font-size: 0.9rem;">شحن وتوصيل فوري</strong>
                <span style="color: #64748B; font-size: 0.75rem;">مباشرة إلى باب منزلك</span>
            </div>
            <span style="font-size: 1.25rem;">🚚</span>
        </div>
        <div class="promo-item" style="border-right: 3px solid #D4AF37;">
            <div>
                <strong style="color: #0B1B3D; display: block; font-size: 0.9rem;">تجارتنا عهد</strong>
                <span style="color: #64748B; font-size: 0.75rem;">وثقتكم أمانة فُرات ستور</span>
            </div>
            <span style="font-size: 1.25rem;">🤝</span>
        </div>
    </div>

    <!-- شبكة المنتجات -->
    <div class="product-section">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; border-bottom: 1px solid #F1F5F9; padding-bottom: 10px;">
            <h3 style="margin: 0; color: #0B1B3D; font-size: 1.15rem; font-weight: 800;">
                {{ request('category') ? 'منتجات قسم: ' . request('category') : 'المنتجات المقترحة لك' }}
            </h3>
            <span style="color: #64748B; font-size: 0.8rem;">
                العدد: <strong>{{ $products->count() }}</strong>
            </span>
        </div>

        <div class="product-grid-layout">
            @forelse($products as $product)
                <div class="clean-card">
                    <div class="clean-img-box">
                        <span class="badge-top">مميز</span>
                        <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="heart-action-btn" title="المفضلة">❤️</button>
                        </form>
                        <a href="{{ route('product.show', $product->id) }}">
                            <img src="{{ asset('storage/' . $product->image) }}" onerror="this.src='https://placehold.co/300x300?text=Furat+Store'">
                        </a>
                    </div>

                    <div class="clean-details">
                        <span style="font-size: 0.7rem; color: #64748B; font-weight: 600;">
                            {{ $product->category->name ?? $product->category ?? 'عام' }}
                        </span>

                        <a href="{{ route('product.show', $product->id) }}" style="text-decoration: none; color: #0B1B3D; font-weight: 700; font-size: 0.9rem; margin: 4px 0 8px; min-height: 38px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                            {{ $product->name }}
                        </a>

                        <div style="display: flex; align-items: center; gap: 4px; margin-bottom: 10px; font-size: 0.75rem;">
                            <span style="color: #D4AF37;">★★★★★</span>
                            <span style="color: #94A3B8;">(4.9)</span>
                        </div>

                        <div style="margin-top: auto;">
                            <div style="margin-bottom: 8px;">
                                <span style="color: #1E6FB8; font-weight: 800; font-size: 1.15rem;">
                                    {{ number_format($product->price, 0) }}
                                </span>
                                <span style="font-size: 0.75rem; color: #64748B;"> ل.س</span>
                            </div>

                            <form action="{{ route('cart.add', $product->id) }}" method="POST">
                                @csrf
                                <button type="submit" class="clean-btn-cart">
                                    🛒 إضافة للسلة
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <div style="grid-column: 1 / -1; text-align: center; padding: 50px 20px; color: #64748B;">
                    لا توجد منتجات مسجلة في هذا القسم حتى الآن.
                </div>
            @endforelse
        </div>
    </div>

</div>
@endsection