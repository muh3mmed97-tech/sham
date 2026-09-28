@extends('layouts.app')

@section('content')
<div style="background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); border: 1px solid #E5E7EB; max-width: 1100px; margin: 20px auto;">
    <h2 style="color: #0B1B3D; margin-bottom: 20px;">📋 سجل الطلبات الواردة</h2>
    <table style="width: 100%; border-collapse: collapse; text-align: right;">
        <thead>
            <tr style="background: #F6F8FB; color: #0B1B3D;">
                <th style="padding: 12px; border-bottom: 2px solid #E5E7EB;">المنتج</th>
                <th style="padding: 12px; border-bottom: 2px solid #E5E7EB;">الكمية</th>
                <th style="padding: 12px; border-bottom: 2px solid #E5E7EB;">الإجمالي</th>
                <th style="padding: 12px; border-bottom: 2px solid #E5E7EB;">الحالة</th>
                <th style="padding: 12px; border-bottom: 2px solid #E5E7EB;">تحديث الحالة</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
            <tr>
                <td style="padding: 12px; border-bottom: 1px solid #E5E7EB; color: #111827;">{{ $order->product_name }}</td>
                <td style="padding: 12px; border-bottom: 1px solid #E5E7EB; color: #111827;">{{ $order->quantity }}</td>
                <td style="padding: 12px; border-bottom: 1px solid #E5E7EB; color: #1E6FB8; font-weight: bold;">{{ number_format($order->total_price, 0) }} ل.س</td>
                <td style="padding: 12px; border-bottom: 1px solid #E5E7EB;">
                    <span style="padding: 5px 10px; border-radius: 5px; font-weight: bold; border: 1px solid #E5E7EB;
                        {{ $order->status == 'pending' ? 'background: rgba(212, 175, 55, 0.15); color: #0B1B3D;' : '' }}
                        {{ $order->status == 'processing' ? 'background: rgba(30, 111, 184, 0.15); color: #1E6FB8;' : '' }}
                        {{ $order->status == 'shipped' ? 'background: rgba(30, 111, 184, 0.25); color: #0B1B3D;' : '' }}
                        {{ $order->status == 'delivered' ? 'background: #e6f4ea; color: #137333;' : '' }}
                        {{ $order->status == 'cancelled' ? 'background: #fce8e6; color: #c5221f;' : '' }}">
                        {{ $order->status }}
                    </span>
                </td>
                <td style="padding: 12px; border-bottom: 1px solid #E5E7EB;">
                    <form action="{{ route('vendor.orders.updateStatus', $order->id) }}" method="POST">
                        @csrf @method('PUT')
                        <select name="status" onchange="this.form.submit()" style="padding: 6px; border-radius: 6px; border: 1px solid #E5E7EB; cursor: pointer; background: white; color: #111827;">
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