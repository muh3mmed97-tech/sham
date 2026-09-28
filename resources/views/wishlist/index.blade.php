@extends('layouts.app')

@section('content')
<div style="max-width: 800px; margin: 40px auto; padding: 20px; background: #fff; border-radius: 12px; border: 1px solid #E5E7EB; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
    <h2 style="color: #0B1B3D; margin-bottom: 10px;">❤️ منتجاتك المفضلة</h2>
    <hr style="border: 0; border-top: 1px solid #E5E7EB; margin-bottom: 20px;">
    
    @if($wishlistItems->isEmpty())
        <p style="text-align: center; color: #64748B; padding: 30px;">لا توجد منتجات في المفضلة.</p>
    @else
        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 20px;">
            @foreach($wishlistItems as $item)
                <div style="border: 1px solid #E5E7EB; padding: 15px; border-radius: 10px; text-align: center; background: #F6F8FB; display: flex; flex-direction: column; justify-content: space-between;">
                    <div>
                        <h3 style="color: #0B1B3D; font-size: 1.1rem; margin-bottom: 10px;">{{ $item->product->name }}</h3>
                        <p style="color: #1E6FB8; font-weight: bold; margin-bottom: 15px;">{{ number_format($item->product->price, 0) }} ل.س</p>
                    </div>
                    <form action="{{ route('wishlist.toggle', $item->product_id) }}" method="POST">
                        @csrf
                        <button type="submit" style="background: #ef4444; color: white; border: none; padding: 8px 12px; border-radius: 6px; cursor: pointer; font-size: 0.9rem; transition: opacity 0.3s; width: 100%;">
                            إزالة من المفضلة
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection