@extends('layouts.app')

@section('content')

<div style="text-align: center; padding: 25px 0;">
    <form action="{{ route('customer.dashboard') }}" method="GET" style="display: inline-block;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث عن منتج..." 
               style="padding: 12px 20px; width: 400px; border: 1px solid #ddd; border-radius: 25px; outline: none; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
        <button type="submit" style="padding: 12px 30px; background: #005a9c; color: white; border: none; border-radius: 25px; cursor: pointer; font-weight: bold;">بحث</button>
    </form>
</div>

<div class="categories-bar" style="display: flex; justify-content: center; gap: 15px; padding: 15px; flex-wrap: wrap;">
    <a href="{{ route('customer.dashboard') }}" class="cat-item">الكل</a>
    @foreach(\App\Models\Category::all() as $cat)
        <a href="{{ route('customer.dashboard', ['category' => $cat->id]) }}" class="cat-item">{{ $cat->name }}</a>
    @endforeach
</div>

<div style="padding: 20px 40px;">
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 25px;">
        @forelse($products as $product)
            <div style="background: white; border-radius: 20px; padding: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.08); display: flex; flex-direction: column;">
                
                <a href="{{ route('product.show', $product->id) }}" style="text-decoration: none; color: inherit; display: block;">
                    <img src="{{ asset('storage/' . $product->image) }}" style="width: 100%; height: 180px; object-fit: cover; border-radius: 12px;">
                    <h3 style="margin: 15px 0; font-size: 1.1rem;">{{ $product->name }}</h3>
                </a>

                <p style="color: #e65100; font-weight: bold;">{{ number_format($product->price, 0) }} ل.س</p>

                <div style="margin-top: auto; display: flex; gap: 10px;">
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" style="flex: 2;">
                        @csrf
                        <button type="submit" style="width: 100%; padding: 10px; background: #005a9c; color: white; border: none; border-radius: 8px; cursor: pointer;">إضافة للسلة 🛒</button>
                    </form>

                    <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" style="flex: 0 0 50px;">
                        @csrf
                        <button type="submit" style="padding: 10px; border: none; border-radius: 8px; cursor: pointer; 
                            background: {{ auth()->user()->wishlist->contains('product_id', $product->id) ? '#ff4d4d' : '#f0f0f0' }}; 
                            color: {{ auth()->user()->wishlist->contains('product_id', $product->id) ? 'white' : 'black' }};">
                            ❤️
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <p style="text-align: center; width: 100%; color: #777;">لا توجد منتجات متاحة حالياً.</p>
        @endforelse
    </div>
</div>
@endsection