@extends('layouts.app')

@section('content')
<div style="padding: 20px 50px;">
    <h2 style="color: #005a9c;">🛒 سلة المشتريات</h2>
    <hr>

    @if($cartItems->isEmpty())
        <div style="text-align: center; padding: 50px;">
            <p>سلة التسوق فارغة تماماً.</p>
            <a href="{{ route('home') }}" style="background: #005a9c; color: white; padding: 10px 20px; border-radius: 20px; text-decoration: none;">تسوق الآن</a>
        </div>
    @else
        <table style="width: 100%; border-collapse: collapse; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <tr style="background: #f8f8f8; text-align: right;">
                <th style="padding: 15px;">المنتج</th>
                <th style="padding: 15px;">السعر</th>
                <th style="padding: 15px;">الكمية</th>
                <th style="padding: 15px;">الإجمالي</th>
                <th style="padding: 15px;">إجراءات</th>
            </tr>
            @php $grandTotal = 0; @endphp
            @foreach($cartItems as $item)
                @php $grandTotal += $item->product->price * $item->quantity; @endphp
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 15px;">{{ $item->product->name }}</td>
                    <td style="padding: 15px;">{{ number_format($item->product->price, 2) }} $</td>
                    <td style="padding: 15px;">
                        <form action="{{ route('cart.update', $item->id) }}" method="POST" style="display: flex; gap: 5px;">
                            @csrf @method('PATCH')
                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" style="width: 50px; padding: 5px;">
                            <button type="submit" style="padding: 5px 10px; cursor: pointer;">تحديث</button>
                        </form>
                    </td>
                    <td style="padding: 15px;">{{ number_format($item->product->price * $item->quantity, 2) }} $</td>
                    <td style="padding: 15px;">
                        <form action="{{ route('cart.remove', $item->id) }}" method="POST">
                            @csrf @method('DELETE')
                            <button type="submit" style="background: #ff5252; color: white; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer;">حذف</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </table>

        <div style="margin-top: 20px; text-align: left; background: white; padding: 20px; border-radius: 10px;">
            <h3>الإجمالي الكلي: {{ number_format($grandTotal, 2) }} $</h3>
            <form action="{{ route('cart.checkout') }}" method="POST">
                @csrf
                <button type="submit" style="background: #2e7d32; color: white; padding: 15px 30px; border: none; border-radius: 8px; cursor: pointer; font-size: 1.1rem;">إتمام عملية الشراء</button>
            </form>
        </div>
    @endif
</div>
@endsection