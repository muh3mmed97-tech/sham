@extends('layouts.app')

@section('content')
<div style="max-width: 1000px; margin: 30px auto; font-family: 'Cairo', sans-serif;" dir="rtl">
    
    <!-- رأس صفحة المتجر -->
    <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #E5E7EB; display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px;">
        <div style="display: flex; align-items: center; gap: 20px;">
            <div style="background: #0B1B3D; color: #D4AF37; width: 70px; height: 70px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 1.8rem; font-weight: bold; border: 2px solid #D4AF37;">
                🏪
            </div>
            <div>
                <h1 style="color: #0B1B3D; margin: 0; font-size: 1.8rem;">{{ $storeModel->name ?? $store->name }}</h1>
                <p style="color: #64748B; margin: 5px 0 0 0; font-size: 0.95rem;">
                    📍 العنوان: {{ $storeModel->address ?? 'غير محدد' }} | 📞 الهاتف: {{ $storeModel->phone ?? 'غير متوفر' }}
                </p>
            </div>
        </div>
        <div style="text-align: left;">
            <span style="background: rgba(212, 175, 55, 0.15); color: #0B1B3D; border: 1px solid #D4AF37; padding: 8px 15px; border-radius: 8px; font-weight: bold; font-size: 1rem;">
                ⭐ متوسط التقييم: {{ number_format($avgRating ?? 0, 1) }} ({{ $reviews->count() }} تقييم)
            </span>
        </div>
    </div>

    <!-- منتجات المتجر -->
    <h3 style="color: #0B1B3D; margin-bottom: 20px; border-right: 4px solid #1E6FB8; padding-right: 10px;">📦 منتجات المتجر</h3>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px; margin-bottom: 40px;">
        @forelse($products as $product)
            <div style="background: white; border-radius: 12px; padding: 15px; border: 1px solid #E5E7EB; box-shadow: 0 2px 8px rgba(0,0,0,0.03);">
                <h4 style="margin: 0 0 10px 0; color: #0B1B3D; font-size: 1.1rem;">{{ $product->name }}</h4>
                <p style="color: #1E6FB8; font-weight: bold; margin: 0 0 15px 0;">{{ number_format($product->price, 0) }} ل.س</p>
                <a href="{{ route('product.show', $product->id) }}" style="display: block; text-align: center; background: #0B1B3D; color: white; padding: 8px; border-radius: 6px; text-decoration: none; font-size: 0.9rem; font-weight: bold;">عرض المنتج</a>
            </div>
        @empty
            <p style="color: #64748B; grid-column: span 3;">لا توجد منتجات معروضة حالياً لهذا المتجر.</p>
        @endforelse
    </div>

    <!-- قسم التقييمات والتعليقات -->
    <div style="background: white; padding: 30px; border-radius: 15px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border: 1px solid #E5E7EB;">
        <h3 style="color: #0B1B3D; margin-top: 0; margin-bottom: 20px; border-right: 4px solid #D4AF37; padding-right: 10px;">💬 تقييمات العملاء</h3>

        <!-- نموذج إضافة تقييم (يظهر للمستخدمين المسجلين) -->
        @auth
            <form action="{{ route('store.reviews.store', $store->id) }}" method="POST" style="background: #F6F8FB; padding: 20px; border-radius: 10px; margin-bottom: 30px; border: 1px solid #E5E7EB;">
                @csrf
                <h4 style="margin-top: 0; color: #0B1B3D; font-size: 1rem;">أضف تقييمك للمتجر:</h4>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; color: #374151; font-weight: bold;">التقييم بالنجوم:</label>
                    <select name="rating" required style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #D1D5DB; font-family: 'Cairo';">
                        <option value="5">⭐⭐⭐⭐⭐ (5/5) ممتاز جداً</option>
                        <option value="4">⭐⭐⭐⭐ (4/5) جيد جداً</option>
                        <option value="3">⭐⭐⭐ (3/5) متوسط</option>
                        <option value="2">⭐⭐ (2/5) مقبولة</option>
                        <option value="1">⭐ (1/5) سيء</option>
                    </select>
                </div>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; margin-bottom: 5px; color: #374151; font-weight: bold;">تعليقك:</label>
                    <textarea name="comment" rows="3" placeholder="اكتب تجربتك مع هذا المتجر..." style="width: 100%; padding: 10px; border-radius: 6px; border: 1px solid #D1D5DB; font-family: 'Cairo';"></textarea>
                </div>
                <button type="submit" style="background: #D4AF37; color: #0B1B3D; border: none; padding: 10px 25px; border-radius: 6px; font-weight: bold; cursor: pointer; font-family: 'Cairo';">إرسال التقييم</button>
            </form>
        @else
            <p style="background: #FFFBEB; color: #B45309; padding: 12px; border-radius: 8px; border: 1px solid #FDE68A; text-align: center;">
                يرجى <a href="{{ url('/login-as-customer') }}" style="color: #1E6FB8; font-weight: bold;">تسجيل الدخول</a> لتتمكن من إضافة تقييم لهذا المتجر.
            </p>
        @endauth

        <!-- عرض قائمة التقييمات السابقة -->
        <div style="display: flex; flex-direction: column; gap: 15px;">
            @forelse($reviews as $review)
                <div style="padding: 15px; border-bottom: 1px solid #E5E7EB; background: #FAFAFA; border-radius: 8px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <strong style="color: #0B1B3D;">{{ $review->user->name ?? 'مستخدم' }}</strong>
                        <span style="color: #D4AF37; font-weight: bold;">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $review->rating) ⭐ @else ⚪ @endif
                            @endfor
                        </span>
                    </div>
                    <p style="margin: 5px 0 0 0; color: #4B5563; font-size: 0.95rem;">{{ $review->comment }}</p>
                    <small style="color: #9CA3AF; font-size: 0.75rem;">{{ $review->created_at->diffForHumans() }}</small>
                </div>
            @empty
                <p style="color: #64748B; text-align: center; padding: 20px;">لا توجد تقييمات لهذا المتجر حتى الآن. كن أول من يتقيّم!</p>
            @endforelse
        </div>
    </div>

</div>
@endsection