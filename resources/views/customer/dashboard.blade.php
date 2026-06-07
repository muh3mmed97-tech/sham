@extends('layouts.app')

@section('content')
<style>
    /* 1. إخفاء التشيك بوكس */
    .wishlist-checkbox { display: none; }

    /* 2. التنسيق الافتراضي للكرت */
    .product-card {
        border: 1px solid #ddd; 
        padding: 15px; 
        border-radius: 8px; 
        text-align: center;
        transition: 0.3s;
        background-color: white; /* اللون الأبيض الافتراضي */
    }

    /* 3. عند الضغط (التشيك بوكس مفعل)، نغير لون خلفية الكرت */
    .wishlist-checkbox:checked ~ .product-card {
        background-color: #fff0f0; /* لون أحمر خفيف جداً عند الإعجاب */
        border-color: #ff4757;
    }
</style>

<div style="padding: 20px;">
    <h1>مرحباً بك في منصة شام</h1>
    <hr>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px;">
        @forelse($products as $product)
            <input type="checkbox" id="wishlist-{{ $product->id }}" class="wishlist-checkbox">
            
            <div class="product-card">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" style="width: 100%; height: 150px; object-fit: cover; border-radius: 4px;">
                @endif
                <h3>{{ $product->name }}</h3>
                <p>السعر: {{ number_format($product->price, 2) }}</p>
                
                <div style="display: flex; gap: 10px; justify-content: center; align-items: center;">
                    <button style="padding: 8px 16px; cursor: pointer;">إضافة للسلة</button>
                    
                    <label for="wishlist-{{ $product->id }}" style="cursor: pointer; font-size: 1.5em;">
                        ❤️
                    </label>
                </div>
            </div>
        @empty
            <p>لا توجد منتجات متاحة حالياً.</p>
        @endforelse
    </div>
</div>
@endsection