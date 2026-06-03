@extends('layouts.app')

@section('content')
<div style="padding: 20px;">
    <div style="background: #f7931e; color: white; padding: 40px; text-align: center; border-radius: 10px; margin-bottom: 20px;">
        <h1>أهلاً بك في منصة "شام"</h1>
        <p>اكتشف أحدث العروض والمنتجات المميزة</p>
    </div>

    <h2 style="border-bottom: 2px solid #f7931e; display: inline-block;">منتجات مميزة</h2>
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px; margin-top: 20px;">
        @forelse($products as $product)
            <div style="border: 1px solid #eee; padding: 10px; text-align: center;">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" style="width: 100%; height: 150px; object-fit: cover;">
                @endif
                <h3>{{ $product->name }}</h3>
                <p style="color: #f7931e; font-weight: bold;">السعر: {{ $product->price }} ل.س</p>
                <button style="width: 100%; background: #2c3e50; color: white; border: none; padding: 8px;">إضافة للسلة</button>
            </div>
        @empty
            <p>لا توجد منتجات لعرضها حالياً.</p>
        @endforelse
    </div>
</div>
@endsection