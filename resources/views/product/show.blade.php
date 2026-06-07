@extends('layouts.app')

@section('content')
<div style="max-width: 1100px; margin: 30px auto; padding: 0 20px;">

    <div style="margin-bottom: 20px;">
        <a href="{{ route('home') }}" style="text-decoration: none; color: #005a9c; font-weight: bold; display: flex; align-items: center; gap: 5px;">
            <span style="font-size: 1.2rem;">←</span> العودة إلى الصفحة الرئيسية
        </a>
    </div>

    <div style="display: flex; gap: 40px; background: white; padding: 30px; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.08); flex-wrap: wrap;">
        
        <div style="flex: 1; min-width: 300px;">
            @if($product->image)
                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" 
                     style="width: 100%; height: 400px; object-fit: cover; border-radius: 15px; border: 1px solid #eee;">
            @else
                <div style="width: 100%; height: 400px; background: #f0f0f0; display: flex; align-items: center; justify-content: center; border-radius: 15px;">
                    <span style="color: #999;">لا توجد صورة</span>
                </div>
            @endif
        </div>

        <div style="flex: 1; min-width: 300px; display: flex; flex-direction: column; justify-content: space-between;">
            <div>
                <h1 style="color: #333; margin-top: 0; font-size: 2rem;">{{ $product->name }}</h1>
                
                <div style="margin: 15px 0; color: #f39c12; font-size: 1.2rem;">
                    @php $avgRating = $product->reviews->avg('rating'); @endphp
                    @for($i = 1; $i <= 5; $i++)
                        {{ $i <= $avgRating ? '★' : '☆' }}
                    @endfor
                    <span style="color: #777; font-size: 0.9rem; margin-right: 10px;">({{ $product->reviews->count() }} تقييم)</span>
                </div>

                <p style="color: #e65100; font-size: 1.8rem; font-weight: bold; margin: 20px 0;">
                    {{ number_format($product->price, 0) }} ل.س
                </p>

                <div style="background: #fdfdfd; padding: 15px; border-radius: 10px; border-right: 4px solid #005a9c; margin-bottom: 25px;">
                    <h4 style="margin-top: 0; color: #005a9c;">وصف المنتج:</h4>
                    <p style="line-height: 1.7; color: #555;">{{ $product->description }}</p>
                </div>
            </div>

            <div style="display: flex; gap: 15px;">
                <form action="{{ route('cart.add', $product->id) }}" method="POST" style="flex: 3;">
                    @csrf
                    <button type="submit" style="width: 100%; padding: 15px; background: #005a9c; color: white; border: none; border-radius: 10px; cursor: pointer; font-size: 1.1rem; font-weight: bold;">
                        إضافة إلى السلة 🛒
                    </button>
                </form>
                <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" style="flex: 1;">
                    @csrf
                    <button type="submit" style="width: 100%; padding: 15px; background: #f0f0f0; border: none; border-radius: 10px; cursor: pointer; font-size: 1.2rem;">
                        {{ auth()->check() && auth()->user()->wishlist()->where('product_id', $product->id)->exists() ? '❤️' : '🤍' }}
                    </button>
                </form>
            </div>
        </div>
    </div>

    <div style="margin-top: 40px; background: white; padding: 30px; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.05);">
        <h3 style="color: #005a9c; border-bottom: 2px solid #eee; padding-bottom: 15px;">آراء وتجارب العملاء 💬</h3>
        
        <div style="margin-top: 20px;">
            @forelse($product->reviews as $review)
                <div style="padding: 15px; border-bottom: 1px solid #f5f5f5; margin-bottom: 10px;">
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <strong style="color: #333;">{{ $review->user->name }}</strong>
                        <span style="color: #f39c12;">
                            @for($i=1; $i<=5; $i++) {{ $i <= $review->rating ? '★' : '☆' }} @endfor
                        </span>
                    </div>
                    <p style="margin: 10px 0 0; color: #666; font-size: 0.95rem;">{{ $review->comment }}</p>
                </div>
            @empty
                <p style="text-align: center; color: #999; padding: 20px;">لا توجد تعليقات لهذا المنتج بعد.</p>
            @endforelse
        </div>
    </div>

    <div style="margin-top: 40px; background: #fff; padding: 30px; border-radius: 20px; border: 1px solid #e0e0e0;">
        <h3 style="color: #005a9c; margin-bottom: 25px;">أسئلة العملاء ❓</h3>
        
        <div style="margin-bottom: 30px;">
            @forelse($product->questions as $question)
                <div style="background: #fcfcfc; padding: 15px; border-radius: 10px; margin-bottom: 15px; border: 1px solid #f0f0f0;">
                    <p style="font-weight: bold; color: #333; margin-bottom: 10px;">👤 {{ $question->user->name }}: 
                        <span style="font-weight: normal;">{{ $question->content }}</span>
                    </p>
                    
                    @if($question->answer)
                        <div style="margin-right: 20px; background: #e3f2fd; padding: 10px; border-radius: 8px; border-right: 3px solid #005a9c;">
                            <p style="margin: 0; color: #005a9c;"><strong>رد التاجر:</strong> {{ $question->answer }}</p>
                        </div>
                    @else
                        <p style="font-size: 0.85rem; color: #999; margin-right: 20px;">(بانتظار رد التاجر...)</p>
                    @endif
                </div>
            @empty
                <p style="color: #999;">لا توجد أسئلة بعد.</p>
            @endforelse
        </div>

        @auth
            <form action="{{ route('questions.store', $product->id) }}" method="POST" style="background: #f8f9fa; padding: 20px; border-radius: 10px;">
                @csrf
                <label style="display: block; margin-bottom: 10px; font-weight: bold;">اطرح سؤالاً:</label>
                <textarea name="content" rows="3" style="width: 100%; border: 1px solid #ddd; border-radius: 8px; padding: 10px;" required></textarea>
                <button type="submit" style="margin-top: 10px; background: #005a9c; color: white; padding: 8px 20px; border: none; border-radius: 5px; cursor: pointer;">إرسال</button>
            </form>
        @else
            <p style="text-align: center; background: #fff3e0; padding: 10px; border-radius: 8px;">يجب <a href="/login-as-customer" style="color: #e65100; font-weight: bold;">تسجيل الدخول</a> لطرح الأسئلة.</p>
        @endauth
    </div>
</div>
@endsection