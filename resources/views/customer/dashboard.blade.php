@extends('layouts.app')

@section('content')
<div style="padding: 20px;">
    <h1>مرحباً بك في منصة شام</h1>
    <hr>
    
    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px;">
        @forelse($products as $product)
            <div style="border: 1px solid #ddd; padding: 15px; border-radius: 8px;">
                @if($product->image)
                    <img src="{{ asset('storage/' . $product->image) }}" style="width: 100%; height: 150px; object-fit: cover;">
                @endif
                <h3>{{ $product->name }}</h3>
                <p>السعر: {{ $product->price }}</p>
                <button>إضافة للسلة</button>
            </div>
        @empty
            <p>لا توجد منتجات متاحة حالياً.</p>
        @endforelse
    </div>
</div>
@endsection