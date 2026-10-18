@extends('layouts.app')

@section('content')
<div style="max-width: 900px; margin: 40px auto; font-family: 'Cairo', sans-serif;" dir="rtl">
    
    <!-- زر العودة -->
    <div style="margin-bottom: 20px;">
        <a href="{{ route('home') }}" style="color: #1E6FB8; text-decoration: none; font-weight: bold;">← العودة إلى الصفحة الرئيسية</a>
    </div>

    <!-- صندوق تفاصيل المنتج -->
    <div style="background: white; padding: 40px; border-radius: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); border: 1px solid #E5E7EB; display: flex; gap: 40px; align-items: start; margin-bottom: 30px; flex-wrap: wrap;">
        
        <!-- صورة المنتج -->
        <div style="flex: 1; min-width: 260px; text-align: center;">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" style="max-width: 100%; height: auto; border-radius: 12px; border: 1px solid #E5E7EB; box-shadow: 0 4px 10px rgba(0,0,0,0.04);">
            @else
                <div style="background: #F6F8FB; height: 250px; border-radius: 12px; display: flex; align-items: center; justify-content: center; color: #9CA3AF; border: 1px solid #E5E7EB;">
                    صورة المنتج غير متوفرة
                </div>
            @endif
        </div>

        <!-- معلومات المنتج والمتجر -->
        <div style="flex: 1.5; min-width: 280px;">
            <div style="display: flex; justify-content: space-between; align-items: start; gap: 10px;">
                <h1 style="color: #0B1B3D; margin-top: 0; font-size: 1.8rem;">{{ $product->name }}</h1>
                @if(($product->stock ?? 1) > 0)
                    <span style="background: #DCFCE7; color: #166534; padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: bold; white-space: nowrap;">
                        متوفر ({{ $product->stock }} قطعة)
                    </span>
                @else
                    <span style="background: #FEE2E2; color: #991B1B; padding: 4px 12px; border-radius: 20px; font-size: 0.8rem; font-weight: bold; white-space: nowrap;">
                        نفدت الكمية
                    </span>
                @endif
            </div>

            <p style="color: #1E6FB8; font-size: 1.5rem; font-weight: bold; margin: 10px 0;">{{ number_format($product->price, 0) }} ل.س</p>
            
            <div style="margin: 20px 0; background: #F6F8FB; padding: 15px; border-radius: 10px; border: 1px solid #E5E7EB;">
                <h4 style="margin: 0 0 8px 0; color: #0B1B3D; font-size: 1rem;">وصف المنتج:</h4>
                <p style="color: #4B5563; margin: 0; line-height: 1.6;">{{ $product->description ?? 'لا يوجد وصف متاح لهذا المنتج.' }}</p>
            </div>

            <!-- بطاقة هوية المتجر وشعاره -->
            @if($product->store)
                <div style="background: rgba(212, 175, 55, 0.08); padding: 16px; border-radius: 14px; border: 1.5px solid #D4AF37; margin-bottom: 20px;">
                    <div style="display: flex; align-items: center; justify-content: space-between; gap: 15px; flex-wrap: wrap;">
                        
                        <div style="display: flex; align-items: center; gap: 12px;">
                            @if($product->store->logo)
                                <img src="{{ asset('storage/' . $product->store->logo) }}" alt="{{ $product->store->name }}" style="width: 58px; height: 58px; border-radius: 12px; object-fit: cover; border: 2px solid #D4AF37; background: white;">
                            @else
                                <img src="{{ asset('images/logo.png') }}" alt="{{ $product->store->name }}" style="width: 58px; height: 58px; border-radius: 12px; object-fit: cover; border: 2px solid #0B1B3D; background: white;">
                            @endif

                            <div>
                                <span style="font-size: 0.75rem; color: #64748B; display: block;">يُباع بواسطة المتجر المعتمد:</span>
                                <h4 style="margin: 2px 0; color: #0B1B3D; font-size: 1.1rem;">{{ $product->store->name }}</h4>
                                <div style="display: flex; gap: 12px; flex-wrap: wrap; margin-top: 4px; font-size: 0.82rem; color: #475569;">
                                    <span>📍 {{ $product->store->address ?? 'غير محدد' }}</span>
                                    <span>📞 {{ $product->store->phone ?? 'غير متوفر' }}</span>
                                </div>
                            </div>
                        </div>

                        <!-- زر الانتقال لصفحة المتجر والتقييمات -->
                        <div>
                            <a href="{{ route('store.show', $product->store->user_id ?? 1) }}" style="background: #0B1B3D; color: #D4AF37; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-size: 0.85rem; font-weight: bold; display: inline-block; border: 1px solid #D4AF37; transition: 0.2s;">
                                ⭐ زيارة المتجر والتقييمات
                            </a>
                        </div>

                    </div>
                </div>
            @endif

            <!-- زر إضافة إلى السلة -->
            @if(($product->stock ?? 1) > 0)
                <form action="{{ route('cart.add', $product->id) }}" method="POST">
                    @csrf
                    <button type="submit" style="background: #D4AF37; color: #0B1B3D; border: none; padding: 12px 30px; border-radius: 10px; font-weight: bold; cursor: pointer; font-size: 1rem; width: 100%; transition: opacity 0.3s; box-shadow: 0 4px 12px rgba(212,175,55,0.25);">
                        إضافة إلى السلة 🛒
                    </button>
                </form>
            @else
                <button disabled style="background: #CBD5E1; color: #64748B; border: none; padding: 12px 30px; border-radius: 10px; font-weight: bold; cursor: not-allowed; font-size: 1rem; width: 100%;">
                    غير متوفر حالياً 🚫
                </button>
            @endif
        </div>
    </div>

    <!-- قسم آراء وتجارب العملاء (التقييمات) -->
    <div style="background: white; padding: 30px; border-radius: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); border: 1px solid #E5E7EB; margin-bottom: 30px;">
        <h3 style="color: #0B1B3D; margin-top: 0; border-right: 4px solid #1E6FB8; padding-right: 10px;">آراء وتجارب العملاء</h3>
        
        @forelse($product->reviews as $review)
            <div style="padding: 15px; border-bottom: 1px solid #E5E7EB; margin-top: 15px;">
                <strong>{{ $review->user->name ?? 'مستخدم' }}</strong>
                <span style="color: #D4AF37; float: left;">{{ str_repeat('⭐', $review->rating) }}</span>
                <p style="margin: 5px 0 0 0; color: #4B5563;">{{ $review->comment }}</p>
            </div>
        @empty
            <p style="color: #64748B; margin-top: 15px;">لا توجد تقييمات لهذا المنتج حتى الآن.</p>
        @endforelse

        <!-- نموذج إضافة تقييم -->
        @auth
            <form action="{{ route('reviews.store', $product->id) }}" method="POST" style="margin-top: 25px; background: #F6F8FB; padding: 20px; border-radius: 12px;">
                @csrf
                <h4 style="margin-top: 0; color: #0B1B3D;">أضف تقييمك للمنتج:</h4>
                <div style="margin-bottom: 12px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #374151;">التقييم:</label>
                    <select name="rating" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #D1D5DB; font-family: 'Cairo';">
                        <option value="5">⭐⭐⭐⭐⭐ (ممتاز)</option>
                        <option value="4">⭐⭐⭐⭐ (جيد جداً)</option>
                        <option value="3">⭐⭐⭐ (متوسط)</option>
                        <option value="2">⭐⭐ (ضعيف)</option>
                        <option value="1">⭐ (سيء)</option>
                    </select>
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="display: block; margin-bottom: 5px; font-weight: bold; color: #374151;">التعليق:</label>
                    <textarea name="comment" rows="3" placeholder="اكتب رأيك بصراحة..." style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #D1D5DB; font-family: 'Cairo';"></textarea>
                </div>
                <button type="submit" style="background: #0B1B3D; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: bold; cursor: pointer; font-family: 'Cairo';">إرسال التقييم</button>
            </form>
        @endauth
    </div>

    <!-- قسم الأسئلة والأجوبة -->
    <div style="background: white; padding: 30px; border-radius: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); border: 1px solid #E5E7EB;">
        <h3 style="color: #0B1B3D; margin-top: 0; border-right: 4px solid #D4AF37; padding-right: 10px;">أسئلة العملاء</h3>
        
        @forelse($product->questions as $question)
            <div style="padding: 15px; border-bottom: 1px solid #E5E7EB; margin-top: 15px;">
                <p style="margin: 0; font-weight: bold; color: #0B1B3D;">سؤال: {{ $question->question }} <span style="font-weight: normal; font-size: 0.85rem; color: #64748B;">({{ $question->user->name ?? 'عميل' }})</span></p>
                @if($question->answer)
                    <p style="margin: 8px 0 0 15px; color: #1E6FB8; background: #F6F8FB; padding: 10px; border-radius: 8px;"><strong>إجابة المتجر:</strong> {{ $question->answer }}</p>
                @else
                    <p style="margin: 5px 0 0 15px; color: #9CA3AF; font-size: 0.85rem;">في انتظار رد التاجر...</p>
                @endif
            </div>
        @empty
            <p style="color: #64748B; margin-top: 15px;">لا توجد أسئلة مسجلة لهذا المنتج.</p>
        @endforelse

        <!-- نموذج طرح سؤال -->
        @auth
            <form action="{{ route('questions.store', $product->id) }}" method="POST" style="margin-top: 25px; background: #F6F8FB; padding: 20px; border-radius: 12px;">
                @csrf
                <h4 style="margin-top: 0; color: #0B1B3D;">لديك سؤال عن المنتج؟</h4>
                <div style="margin-bottom: 12px;">
                    <textarea name="question" rows="2" placeholder="اكتب سؤالك هنا..." required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #D1D5DB; font-family: 'Cairo';"></textarea>
                </div>
                <button type="submit" style="background: #1E6FB8; color: white; border: none; padding: 10px 20px; border-radius: 8px; font-weight: bold; cursor: pointer; font-family: 'Cairo';">طرح السؤال</button>
            </form>
        @endauth
    </div>

</div>
@endsection