@extends('layouts.app')

@section('content')
<div style="background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
    <h2>📋 سجل الطلبات الواردة</h2>
    <table style="width: 100%; border-collapse: collapse; margin-top: 20px;">
        <thead>
            <tr style="background: #f8f9fa;">
                <th style="padding: 10px; border-bottom: 2px solid #ddd;">المنتج</th>
                <th style="padding: 10px; border-bottom: 2px solid #ddd;">الكمية</th>
                <th style="padding: 10px; border-bottom: 2px solid #ddd;">الإجمالي</th>
                <th style="padding: 10px; border-bottom: 2px solid #ddd;">الحالة</th>
                <th style="padding: 10px; border-bottom: 2px solid #ddd;">تحديث الحالة</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;">{{ $order->product_name }}</td>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;">{{ $order->quantity }}</td>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;">{{ number_format($order->total_price, 2) }} $</td>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                    <span style="padding: 5px 10px; border-radius: 5px; 
                        {{ $order->status == 'pending' ? 'background:#fff3cd; color:#856404;' : '' }}
                        {{ $order->status == 'processing' ? 'background:#cce5ff; color:#004085;' : '' }}
                        {{ $order->status == 'shipped' ? 'background:#d1ecf1; color:#0c5460;' : '' }}
                        {{ $order->status == 'delivered' ? 'background:#d4edda; color:#155724;' : '' }}
                        {{ $order->status == 'cancelled' ? 'background:#f8d7da; color:#721c24;' : '' }}">
                        {{ $order->status }}
                    </span>
                </td>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;">
                    <form action="{{ route('vendor.orders.update', $order->id) }}" method="POST">
                        @csrf @method('PUT')
                        <select name="status" onchange="this.form.submit()" style="padding: 5px; border-radius: 5px; border: 1px solid #ccc; cursor: pointer;">
                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>بانتظار الموافقة</option>
                            <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>قيد التجهيز</option>
                            <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>تم الشحن</option>
                            <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>تم التسليم</option>
                            <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>إلغاء</option>
                        </select>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection