@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 25px auto 50px; padding: 0 15px; font-family: 'Cairo', sans-serif;" dir="rtl">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <div>
            <h2 style="color: #0B1B3D; font-size: 1.6rem; font-weight: 800; margin: 0 0 5px 0;">
                📦 سجل طلبات المنصة بالكامل
            </h2>
            <p style="color: #64748B; font-size: 0.9rem; margin: 0;">متابعة حركة الطلبات بين العملاء والتجار في فُرات ستور</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" style="background: #F1F5F9; color: #475569; text-decoration: none; padding: 8px 16px; border-radius: 8px; font-weight: 700; font-size: 0.85rem; border: 1px solid #CBD5E1;">
            ← العودة للوحة الإدارة
        </a>
    </div>

    <div style="background: white; border-radius: 12px; border: 1px solid #E2E8F0; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: right;">
                <thead>
                    <tr style="background: #F8FAFC; border-bottom: 1px solid #E2E8F0; color: #0B1B3D; font-size: 0.85rem;">
                        <th style="padding: 12px 15px;">رقم الطلب</th>
                        <th style="padding: 12px 15px;">العميل</th>
                        <th style="padding: 12px 15px;">المنتج</th>
                        <th style="padding: 12px 15px;">الكمية</th>
                        <th style="padding: 12px 15px;">الإجمالي</th>
                        <th style="padding: 12px 15px;">الحالة</th>
                        <th style="padding: 12px 15px;">التاريخ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr style="border-bottom: 1px solid #F1F5F9; font-size: 0.9rem;">
                            <td style="padding: 14px 15px; font-weight: 700; color: #0B1B3D;">#{{ $order->id }}</td>
                            <td style="padding: 14px 15px; color: #334155;">{{ $order->customer->name ?? 'غير متوفر' }}</td>
                            <td style="padding: 14px 15px; color: #334155;">{{ $order->product_name ?? ($order->product->name ?? 'منتج') }}</td>
                            <td style="padding: 14px 15px; color: #334155;">{{ $order->quantity }}</td>
                            <td style="padding: 14px 15px; font-weight: 800; color: #1E6FB8;">{{ number_format($order->total_price, 0) }} ل.س</td>
                            <td style="padding: 14px 15px;">
                                <span style="background: #FEF9C3; color: #854D0E; padding: 3px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 700;">
                                    {{ $order->status }}
                                </span>
                            </td>
                            <td style="padding: 14px 15px; color: #64748B; font-size: 0.8rem;">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 30px; color: #64748B;">لا توجد طلبات مسجلة في المنصة حتى الآن.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection