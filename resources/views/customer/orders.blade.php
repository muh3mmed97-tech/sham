@extends('layouts.app')

@section('content')
<div style="max-width: 1000px; margin: 20px auto; background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
    <h2 style="color: #333; margin-bottom: 20px;">📦 طلباتي</h2>
    
    @if($orders->isEmpty())
        <p style="text-align: center; color: #777;">ليس لديك أي طلبات حالياً.</p>
    @else
        <table style="width: 100%; border-collapse: collapse; text-align: right;">
            <thead>
                <tr style="background: #f4f4f4;">
                    <th style="padding: 12px; border-bottom: 2px solid #ddd;">المنتج</th>
                    <th style="padding: 12px; border-bottom: 2px solid #ddd;">التاريخ</th>
                    <th style="padding: 12px; border-bottom: 2px solid #ddd;">الحالة</th>
                    <th style="padding: 12px; border-bottom: 2px solid #ddd;">التقييم</th>
                    <th style="padding: 12px; border-bottom: 2px solid #ddd;">الفاتورة</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $order->product_name }}</td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;">
                        {{ $order->created_at->format('Y-m-d') }} <br>
                        <small style="color: #777;">{{ $order->created_at->format('H:i') }}</small>
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;">
                        <span style="padding: 5px 10px; border-radius: 5px; background: #eee;">{{ $order->status }}</span>
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;">
                        @if(trim($order->status) == 'delivered')
                            @if($order->product_id)
                                <form action="{{ route('reviews.store', $order->product_id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                                    <select name="rating" style="padding: 2px;">
                                        <option value="5">⭐⭐⭐⭐⭐</option>
                                        <option value="1">⭐</option>
                                    </select>
                                    <button type="submit" style="background: #005a9c; color: white; border:none; padding: 2px 8px; border-radius: 4px; cursor:pointer;">تقييم</button>
                                </form>
                            @endif
                        @else
                            <small style="color: #999;">متاح بعد التسليم</small>
                        @endif
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;">
                        <a href="{{ route('customer.invoice', $order->id) }}" style="color: #005a9c; text-decoration: underline; font-weight: bold;">عرض الفاتورة</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection