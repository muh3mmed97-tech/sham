@extends('layouts.app')

@section('content')
<div style="max-width: 900px; margin: 20px auto; background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
    <h2 style="color: #333; margin-bottom: 20px;">📦 طلباتي</h2>
    
    @if($orders->isEmpty())
        <p style="text-align: center; color: #777;">ليس لديك أي طلبات حالياً.</p>
    @else
        <table style="width: 100%; border-collapse: collapse; text-align: right;">
            <thead>
                <tr style="background: #f4f4f4;">
                    <th style="padding: 12px; border-bottom: 2px solid #ddd;">المنتج</th>
                    <th style="padding: 12px; border-bottom: 2px solid #ddd;">الحالة</th>
                    <th style="padding: 12px; border-bottom: 2px solid #ddd;">التقييم</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;">{{ $order->product_name }}</td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;">
                        <span style="padding: 5px 10px; border-radius: 5px; background: #eee;">{{ $order->status }}</span>
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #eee;">
                        {{-- التأكد من أن الحالة delivered وأن معرف المنتج موجود --}}
                        @if(trim($order->status) == 'delivered')
                            @if($order->product_id)
                                <form action="{{ route('reviews.store', $order->product_id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                                    <select name="rating" style="padding: 2px;">
                                        <option value="5">⭐⭐⭐⭐⭐</option>
                                        <option value="4">⭐⭐⭐⭐</option>
                                        <option value="3">⭐⭐⭐</option>
                                        <option value="2">⭐⭐</option>
                                        <option value="1">⭐</option>
                                    </select>
                                    <input type="text" name="comment" placeholder="تعليقك..." style="padding: 2px; width: 80px;">
                                    <button type="submit" style="background: #005a9c; color: white; border:none; padding: 2px 8px; border-radius: 4px; cursor:pointer;">تقييم</button>
                                </form>
                            @else
                                <small style="color: #999;">لا يمكن تقييم الطلبات القديمة.</small>
                            @endif
                        @else
                            <small style="color: #999;">التقييم متاح بعد التسليم</small>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection