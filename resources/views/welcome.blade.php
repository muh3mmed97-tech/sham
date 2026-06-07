@extends('layouts.app')

@section('content')

<div style="text-align: center; padding: 25px 0;">
    <form action="{{ route('home') }}" method="GET" style="display: inline-block;">
        <input type="text" name="search" placeholder="ابحث عن منتج..." 
                style="padding: 12px 20px; width: 400px; border: 1px solid #ddd; border-radius: 25px; outline: none; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
        <button type="submit" style="padding: 12px 30px; background: #005a9c; color: white; border: none; border-radius: 25px; cursor: pointer; font-weight: bold;">بحث</button>
    </form>
</div>

<div class="categories-bar">
    <a href="{{ route('home') }}" class="cat-item">الكل</a>
    @foreach(\App\Models\Category::all() as $cat)
        <a href="{{ route('home', $cat->id) }}" class="cat-item">{{ $cat->name }}</a>
    @endforeach
</div>

<div style="padding: 0 40px 40px 40px;">
    <h2 style="color: #005a9c; margin-bottom: 20px;">المنتجات المتاحة</h2>
    <hr style="border: 0; height: 1px; background: #ddd; margin-bottom: 30px;">
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 25px;">
        @forelse($products as $product)
            <div style="background: white; border-radius: 15px; padding: 15px; text-align: center; box-shadow: 0 4px 10px rgba(0,0,0,0.06); transition: 0.3s;">
                
                <a href="{{ route('product.show', $product->id) }}" style="text-decoration: none; color: inherit;">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" style="width: 100%; height: 180px; object-fit: cover; border-radius: 12px;">
                    @endif
                    
                    <h3 style="font-size: 1.1rem; margin: 15px 0; color: #333;">{{ $product->name }}</h3>
                </a>

                <div style="color: #f39c12; font-size: 0.9rem; margin-bottom: 10px;">
                    @php
                        $avgRating = $product->reviews->avg('rating');
                        $count = $product->reviews->count();
                    @endphp
                    @for($i = 1; $i <= 5; $i++)
                        {{ $i <= $avgRating ? '★' : '☆' }}
                    @endfor
                    <span style="color: #777; font-size: 0.8rem;">({{ $count }})</span>
                </div>

                <p style="color: #e65100; font-weight: bold; font-size: 1.1rem;">{{ number_format($product->price, 0) }} ل.س</p>
                
                <div style="display: flex; gap: 10px; justify-content: center; align-items: center; margin-top: 15px;">
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" style="flex: 2;">
                        @csrf
                        <button type="submit" style="width: 100%; padding: 10px; background: #005a9c; color: white; border: none; border-radius: 8px; cursor: pointer; font-weight: bold;">إضافة للسلة 🛒</button>
                    </form>

                    <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" style="flex: 0 0 45px;">
                        @csrf
                        <button type="submit" style="width: 100%; padding: 10px; background: #f0f0f0; border: none; border-radius: 8px; cursor: pointer; font-size: 18px;">
                            @if(auth()->check() && auth()->user()->wishlist()->where('product_id', $product->id)->exists())
                                ❤️
                            @else
                                🤍
                            @endif
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <p style="text-align: center; color: #777;">لا توجد منتجات متاحة حالياً.</p>
        @endforelse
    </div>
</div>
@endsection