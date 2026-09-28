@extends('layouts.app')

@section('content')
<div style="max-width: 1000px; margin: 20px auto; background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); border: 1px solid #E5E7EB;">
    <h2 style="color: #0B1B3D; margin-bottom: 20px;">📦 طلباتي</h2>
    
    @if($orders->isEmpty())
        <p style="text-align: center; color: #64748B;">ليس لديك أي طلبات حالياً.</p>
    @else
        <table style="width: 100%; border-collapse: collapse; text-align: right;">
            <thead>
                <tr style="background: #F6F8FB; color: #0B1B3D;">
                    <th style="padding: 12px; border-bottom: 2px solid #E5E7EB;">المنتج</th>
                    <th style="padding: 12px; border-bottom: 2px solid #E5E7EB;">التاريخ</th>
                    <th style="padding: 12px; border-bottom: 2px solid #E5E7EB;">الحالة</th>
                    <th style="padding: 12px; border-bottom: 2px solid #E5E7EB;">التقييم</th>
                    <th style="padding: 12px; border-bottom: 2px solid #E5E7EB;">الفاتورة</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td style="padding: 12px; border-bottom: 1px solid #E5E7EB; color: #111827;">{{ $order->product_name }}</td>
                    <td style="padding: 12px; border-bottom: 1px solid #E5E7EB; color: #111827;">
                        {{ $order->created_at->format('Y-m-d') }} <br>
                        <small style="color: #64748B;">{{ $order->created_at->format('H:i') }}</small>
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #E5E7EB;">
                        <span style="padding: 5px 10px; border-radius: 5px; background: #F6F8FB; color: #0B1B3D; border: 1px solid #E5E7EB; font-weight: bold;">{{ $order->status }}</span>
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #E5E7EB;">
                        @if(trim($order->status) == 'delivered')
                            @if($order->product_id)
                                <form action="{{ route('reviews.store', $order->product_id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="order_id" value="{{ $order->id }}">
                                    <select name="rating" style="padding: 2px; border: 1px solid #E5E7EB; border-radius: 4px;">
                                        <option value="5">⭐⭐⭐⭐⭐</option>
                                        <option value="1">⭐</option>
                                    </select>
                                    <button type="submit" style="background: #1E6FB8; color: white; border:none; padding: 4px 10px; border-radius: 4px; cursor:pointer; font-weight: bold;">تقييم</button>
                                </form>
                            @endif
                        @else
                            <small style="color: #64748B;">متاح بعد التسليم</small>
                        @endif
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #E5E7EB;">
                        <a href="{{ route('customer.invoice', $order->id) }}" style="color: #1E6FB8; text-decoration: underline; font-weight: bold;">عرض الفاتورة</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection