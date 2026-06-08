@extends('layouts.app')

@section('content')

<div style="text-align: center; padding: 20px 0; background: #fff; margin-bottom: 20px; border-radius: 15px;">
    <form action="{{ route('home') }}" method="GET" style="display: flex; justify-content: center; gap: 10px;">
        <input type="text" name="search" placeholder="ابحث عن منتج..." 
               style="padding: 12px 20px; width: 350px; border: 1px solid #ddd; border-radius: 25px; outline: none;">
        <button type="submit" style="padding: 12px 30px; background: #005a9c; color: white; border: none; border-radius: 25px; cursor: pointer;">بحث</button>
    </form>
</div>

<div class="categories-bar" style="display: flex; justify-content: center; gap: 10px; margin-bottom: 30px; flex-wrap: wrap;">
    <a href="{{ route('home') }}" class="cat-item" style="padding: 10px 20px; background: #eee; border-radius: 20px; text-decoration: none; color: #333;">الكل</a>
    @foreach(\App\Models\Category::all() as $cat)
        <a href="{{ route('home', $cat->id) }}" class="cat-item" style="padding: 10px 20px; background: #eee; border-radius: 20px; text-decoration: none; color: #333;">{{ $cat->name }}</a>
    @endforeach
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 25px;">
    @forelse($products as $product)
        <div style="background: white; border-radius: 20px; padding: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); display: flex; flex-direction: column;">
            
            <a href="{{ route('product.show', $product->id) }}" style="text-decoration: none; color: #333;">
                <img src="{{ asset('storage/' . $product->image) }}" style="width: 100%; height: 180px; object-fit: cover; border-radius: 15px;">
                <h3 style="margin: 15px 0;">{{ $product->name }}</h3>
            </a>

            <p style="color: #e65100; font-weight: bold; font-size: 1.2rem;">{{ number_format($product->price, 0) }} ل.س</p>

            <div style="margin-top: auto; display: flex; gap: 10px;">
                <form action="{{ route('cart.add', $product->id) }}" method="POST" style="flex: 1;">
                    @csrf
                    <button type="submit" style="width: 100%; padding: 10px; background: #005a9c; color: white; border: none; border-radius: 10px; cursor: pointer;">إضافة للسلة</button>
                </form>
                <form action="{{ route('wishlist.toggle', $product->id) }}" method="POST">
                    @csrf
                    <button type="submit" style="padding: 10px 15px; background: #f0f0f0; border: none; border-radius: 10px; cursor: pointer;">❤️</button>
                </form>
            </div>
        </div>
    @empty
        <p>لا توجد منتجات.</p>
    @endforelse
</div>
@endsection