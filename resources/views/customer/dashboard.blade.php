@extends('layouts.app')

@section('content')

<!-- 1. خانة البحث فوق -->
<div style="text-align: center; padding: 25px 0 10px 0;">
    <form action="{{ route('customer.dashboard') }}" method="GET" style="display: inline-block;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="ابحث عن منتج..." 
               style="padding: 12px 20px; width: 400px; border: 1px solid #E5E7EB; border-radius: 25px; outline: none; box-shadow: 0 2px 5px rgba(0,0,0,0.05); background: white; color: #111827;">
        <button type="submit" style="padding: 12px 30px; background: #1E6FB8; color: white; border: none; border-radius: 25px; cursor: pointer; font-weight: bold; transition: background 0.3s;">بحث</button>
    </form>
</div>

<!-- 2. شريط الأقسام تحت البحث -->
<div style="display: flex; justify-content: center; gap: 15px; padding: 10px 15px 25px 15px; flex-wrap: wrap;">
    <a href="{{ route('customer.dashboard') }}" style="text-decoration: none; color: #0B1B3D; font-weight: bold; padding: 10px 20px; border-radius: 20px; background: #FFFFFF; border: 1px solid #E5E7EB; transition: 0.3s;">الكل</a>
    @foreach(\App\Models\Category::all() as $cat)
        <a href="{{ route('customer.dashboard', ['category' => $cat->id]) }}" style="text-decoration: none; color: #0B1B3D; font-weight: bold; padding: 10px 20px; border-radius: 20px; background: #FFFFFF; border: 1px solid #E5E7EB; transition: 0.3s;">{{ $cat->name }}</a>
    @endforeach
</div>

<!-- 3. عرض المنتجات -->
<div style="padding: 20px 40px;">
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(260px, 1fr)); gap: 25px;">
        @forelse($products as $product)
            <div style="background: white; border-radius: 20px; padding: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.08); display: flex; flex-direction: column; border: 1px solid #E5E7EB;">
                <a href="{{ route('product.show', $product->id) }}" style="text-decoration: none; color: inherit; display: block;">
                    <img src="{{ asset('storage/' . $product->image) }}" style="width: 100%; height: 180px; object-fit: cover; border-radius: 12px;">
                    <h3 style="margin: 15px 0; font-size: 1.1rem; color: #0B1B3D;">{{ $product->name }}</h3>
                </a>
                <p style="color: #1E6FB8; font-weight: bold; font-size: 1.1rem;">{{ number_format($product->price, 0) }} ل.س</p>
                <div style="margin-top: auto; display: flex; gap: 10px;">
                    <form action="{{ route('cart.add', $product->id) }}" method="POST" style="flex: 2;">
                        @csrf
                        <button type="submit" style="width: 100%; padding: 10px; background: #D4AF37; color: #0B1B3D; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; transition: opacity 0.3s;">إضافة للسلة 🛒</button>
                    </form>
                    <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST" style="flex: 0 0 50px;">
                        @csrf
                        <button type="submit" style="padding: 10px; border: none; border-radius: 8px; cursor: pointer; 
                            background: {{ auth()->user()->wishlist->contains('product_id', $product->id) ? '#ef4444' : '#F6F8FB' }}; 
                            color: {{ auth()->user()->wishlist->contains('product_id', $product->id) ? 'white' : '#0B1B3D' }}; border: 1px solid #E5E7EB;">❤️</button>
                    </form>
                </div>
            </div>
        @empty
            <p style="text-align: center; width: 100%; color: #64748B;">لا توجد منتجات متاحة حالياً.</p>
        @endforelse
    </div>
</div>
@endsection