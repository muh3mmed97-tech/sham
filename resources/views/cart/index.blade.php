@extends('layouts.app')

@section('content')
<div style="padding: 20px 50px;">
    <h2 style="color: #0B1B3D;">🛒 سلة المشتريات</h2>
    <hr style="border: 0; border-top: 1px solid #E5E7EB;">

    @if($cartItems->isEmpty())
        <div style="text-align: center; padding: 50px; background: white; border-radius: 10px; border: 1px solid #E5E7EB;">
            <p style="color: #64748B; font-size: 1.1rem;">سلة التسوق فارغة تماماً.</p>
            <a href="{{ route('home') }}" style="background: #0B1B3D; color: white; padding: 10px 20px; border-radius: 20px; text-decoration: none; transition: background 0.3s;">تسوق الآن</a>
        </div>
    @else
        <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; border: 1px solid #E5E7EB;">
            <tr style="background: #F6F8FB; text-align: right; color: #0B1B3D;">
                <th style="padding: 15px;">المنتج</th>
                <th style="padding: 15px;">السعر</th>
                <th style="padding: 15px;">الكمية</th>
                <th style="padding: 15px;">الإجمالي</th>
                <th style="padding: 15px;">إجراءات</th>
            </tr>
            @php $grandTotal = 0; @endphp
            @foreach($cartItems as $item)
                @php $grandTotal += $item->product->price * $item->quantity; @endphp
                <tr style="border-bottom: 1px solid #E5E7EB;">
                    <td style="padding: 15px; color: #111827;">{{ $item->product->name }}</td>
                    <td style="padding: 15px; color: #1E6FB8; font-weight: bold;">{{ number_format($item->product->price, 0) }} ل.س</td>
                    <td style="padding: 15px;">
                        <form action="{{ route('cart.update', $item->id) }}" method="POST" style="display: flex; gap: 5px;">
                            @csrf @method('PATCH')
                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" style="width: 50px; padding: 5px; border: 1px solid #E5E7EB; border-radius: 4px;">
                            <button type="submit" style="padding: 5px 10px; cursor: pointer; background: #1E6FB8; color: white; border: none; border-radius: 4px; transition: background 0.3s;">تحديث</button>
                        </form>
                    </td>
                    <td style="padding: 15px; color: #0B1B3D; font-weight: bold;">{{ number_format($item->product->price * $item->quantity, 0) }} ل.س</td>
                    <td style="padding: 15px;">
                        <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" style="background: #ef4444; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">حذف</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>

        <div style="margin-top: 20px; text-align: left; background: white; padding: 20px; border-radius: 10px; border: 1px solid #E5E7EB;">
            <h3 style="color: #0B1B3D;">الإجمالي الكلي: {{ number_format($grandTotal, 0) }} ل.س</h3>
            <form action="{{ route('cart.checkout') }}" method="POST">
                @csrf
                <button type="submit" style="background: #D4AF37; color: #0B1B3D; padding: 15px 30px; border: none; border-radius: 8px; cursor: pointer; font-size: 1.1rem; font-weight: bold; transition: opacity 0.3s;">إتمام عملية الشراء</button>
            </form>
        </div>
    @endif
</div>
@endsection