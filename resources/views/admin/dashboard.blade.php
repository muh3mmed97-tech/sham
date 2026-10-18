@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 25px auto 50px; padding: 0 15px; font-family: 'Cairo', sans-serif;" dir="rtl">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <div>
            <h2 style="color: #0B1B3D; font-size: 1.6rem; font-weight: 800; margin: 0 0 5px 0;">
                🛠️ لوحة الإدارة العامة (المدير)
            </h2>
            <p style="color: #64748B; font-size: 0.9rem; margin: 0;">مرحباً بك، نظرة عامة على نشاط منصة فُرات ستور</p>
        </div>
    </div>

    <!-- بطاقات الإحصائيات السريعة -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 30px;">
        
        <div style="background: white; border-radius: 12px; border: 1px solid #E2E8F0; padding: 22px; border-right: 4px solid #0B1B3D; box-shadow: 0 2px 6px rgba(0,0,0,0.02);">
            <span style="color: #64748B; font-size: 0.85rem; font-weight: 700; display: block; margin-bottom: 5px;">إجمالي المستخدمين</span>
            <span style="color: #0B1B3D; font-size: 1.8rem; font-weight: 800;">{{ $stats['users_count'] }}</span>
        </div>

        <div style="background: white; border-radius: 12px; border: 1px solid #E2E8F0; padding: 22px; border-right: 4px solid #D4AF37; box-shadow: 0 2px 6px rgba(0,0,0,0.02);">
            <span style="color: #64748B; font-size: 0.85rem; font-weight: 700; display: block; margin-bottom: 5px;">المتاجر المسجلة</span>
            <span style="color: #0B1B3D; font-size: 1.8rem; font-weight: 800;">{{ $stats['vendors_count'] }}</span>
        </div>

        <div style="background: white; border-radius: 12px; border: 1px solid #E2E8F0; padding: 22px; border-right: 4px solid #1E6FB8; box-shadow: 0 2px 6px rgba(0,0,0,0.02);">
            <span style="color: #64748B; font-size: 0.85rem; font-weight: 700; display: block; margin-bottom: 5px;">إجمالي المنتجات</span>
            <span style="color: #0B1B3D; font-size: 1.8rem; font-weight: 800;">{{ $stats['products_count'] }}</span>
        </div>

        <div style="background: white; border-radius: 12px; border: 1px solid #E2E8F0; padding: 22px; border-right: 4px solid #16A34A; box-shadow: 0 2px 6px rgba(0,0,0,0.02);">
            <span style="color: #64748B; font-size: 0.85rem; font-weight: 700; display: block; margin-bottom: 5px;">إجمالي الطلبات</span>
            <span style="color: #0B1B3D; font-size: 1.8rem; font-weight: 800;">{{ $stats['orders_count'] }}</span>
        </div>

    </div>

    <!-- أحدث الطلبات في المنصة -->
    <div style="background: white; border-radius: 12px; border: 1px solid #E2E8F0; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
        <h3 style="color: #0B1B3D; font-size: 1.2rem; font-weight: 800; margin-top: 0; margin-bottom: 18px; border-bottom: 1px solid #F1F5F9; padding-bottom: 10px;">
            📦 أحدث الطلبات المسجلة في المنصة
        </h3>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: right;">
                <thead>
                    <tr style="background: #F8FAFC; border-bottom: 1px solid #E2E8F0; color: #0B1B3D; font-size: 0.85rem;">
                        <th style="padding: 12px 15px;">رقم الطلب</th>
                        <th style="padding: 12px 15px;">العميل</th>
                        <th style="padding: 12px 15px;">المنتج</th>
                        <th style="padding: 12px 15px;">الإجمالي</th>
                        <th style="padding: 12px 15px;">الحالة</th>
                        <th style="padding: 12px 15px;">التاريخ</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentOrders as $order)
                        <tr style="border-bottom: 1px solid #F1F5F9; font-size: 0.9rem;">
                            <td style="padding: 14px 15px; font-weight: 700; color: #0B1B3D;">#{{ $order->id }}</td>
                            <td style="padding: 14px 15px; color: #334155;">{{ $order->customer->name ?? 'غير متوفر' }}</td>
                            <td style="padding: 14px 15px; color: #334155;">{{ $order->product_name ?? ($order->product->name ?? 'منتج') }}</td>
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
                            <td colspan="6" style="text-align: center; padding: 30px; color: #64748B;">لا توجد طلبات مسجلة حتى الآن.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection