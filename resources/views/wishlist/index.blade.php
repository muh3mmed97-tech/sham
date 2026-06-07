@extends('layouts.app')

@section('content')
<div style="max-width: 800px; margin: 40px auto; padding: 20px; background: #fff; border-radius: 8px;">
    <h2>❤️ منتجاتك المفضلة</h2>
    <hr>
    @if($wishlistItems->isEmpty())
        <p>لا توجد منتجات في المفضلة.</p>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px;">
            @foreach($wishlistItems as $item)
                <div style="border: 1px solid #ddd; padding: 15px; border-radius: 8px; text-align: center;">
                    <h3>{{ $item->product->name }}</h3>
                    <p>{{ number_format($item->product->price, 2) }} $</p>
                    <form action="{{ route('wishlist.toggle', $item->product_id) }}" method="POST">
                        @csrf
                        <button type="submit" style="background: #dc3545; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">
                            إزالة من المفضلة
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection