@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 25px auto 50px; padding: 0 15px; font-family: 'Cairo', sans-serif;" dir="rtl">

    <!-- ترويسة لوحة الإدارة وأزرار التنقل السريع -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 15px;">
        <div>
            <h2 style="color: #0B1B3D; font-size: 1.6rem; font-weight: 800; margin: 0 0 5px 0;">
                🛠️ لوحة الإدارة العامة (المدير)
            </h2>
            <p style="color: #64748B; font-size: 0.9rem; margin: 0;">مرحباً بك، نظرة شاملة على الأداء المالي والتشغيلي لمنصة فُرات ستور</p>
        </div>

        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('admin.dashboard') }}" style="background: #0B1B3D; color: #D4AF37; padding: 9px 18px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 0.9rem; border: 1px solid #D4AF37;">
                📊 الإحصائيات
            </a>
            <a href="{{ route('admin.vendors') }}" style="background: white; color: #0B1B3D; padding: 9px 18px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 0.9rem; border: 1px solid #CBD5E1;">
                🏪 إدارة المتاجر
            </a>
            <a href="{{ route('admin.wallets') }}" style="background: white; color: #0B1B3D; padding: 9px 18px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 0.9rem; border: 1px solid #CBD5E1;">
                💰 إدارة المحافظ
            </a>
            <a href="{{ route('admin.orders') }}" style="background: white; color: #0B1B3D; padding: 9px 18px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 0.9rem; border: 1px solid #CBD5E1;">
                📦 جميع الطلبات
            </a>
            <a href="{{ route('admin.disputes') }}" style="background: white; color: #0B1B3D; padding: 9px 18px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 0.9rem; border: 1px solid #CBD5E1;">
                ⚖️ النزاعات
            </a>
            <!-- زر الكوبونات المضاف حديثاً -->
            <a href="{{ route('admin.coupons') }}" style="background: white; color: #0B1B3D; padding: 9px 18px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 0.9rem; border: 1px solid #CBD5E1;">
                🎟️ الكوبونات
            </a>
        </div>
    </div>

    <!-- الصف الأول: البطاقات المالية وأرباح المنصة -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-bottom: 20px;">
        
        <div style="background: linear-gradient(135deg, #0B1B3D 0%, #1E3A8A 100%); color: white; border-radius: 14px; padding: 22px; box-shadow: 0 4px 12px rgba(11,27,61,0.12);">
            <span style="color: #CBD5E1; font-size: 0.85rem; font-weight: 700; display: block; margin-bottom: 6px;">💵 إجمالي المبيعات المكتملة</span>
            <span style="color: #ffffff; font-size: 1.7rem; font-weight: 800;">{{ number_format($stats['total_sales'] ?? 0, 0) }} <small style="font-size: 0.9rem;">ل.س</small></span>
            <small style="display: block; color: #93C5FD; margin-top: 6px; font-size: 0.78rem;">من أصل {{ $stats['completed_orders'] ?? 0 }} طلب مكتمل</small>
        </div>

        <div style="background: linear-gradient(135deg, #D4AF37 0%, #B48A1E 100%); color: #0B1B3D; border-radius: 14px; padding: 22px; box-shadow: 0 4px 12px rgba(212,175,55,0.2);">
            <span style="color: #0B1B3D; font-size: 0.85rem; font-weight: 800; display: block; margin-bottom: 6px;">🏆 صافي عمولة المنصة (5%)</span>
            <span style="color: #0B1B3D; font-size: 1.7rem; font-weight: 900;">{{ number_format($stats['platform_profit'] ?? 0, 0) }} <small style="font-size: 0.9rem;">ل.س</small></span>
            <small style="display: block; color: #1E293B; margin-top: 6px; font-size: 0.78rem; font-weight: bold;">عوائد منصة فُرات ستور الصافية</small>
        </div>

        <div style="background: white; border-radius: 14px; border: 1px solid #E2E8F0; padding: 22px; border-right: 5px solid #16A34A; box-shadow: 0 2px 6px rgba(0,0,0,0.02);">
            <span style="color: #64748B; font-size: 0.85rem; font-weight: 700; display: block; margin-bottom: 6px;">🏦 إجمالي أرصدة المحافظ النشطة</span>
            <span style="color: #16A34A; font-size: 1.7rem; font-weight: 800;">{{ number_format($stats['total_wallets'] ?? 0, 0) }} <small style="font-size: 0.9rem;">ل.س</small></span>
            <small style="display: block; color: #64748B; margin-top: 6px; font-size: 0.78rem;">مجموع أرصدة العملاء والتجار</small>
        </div>

    </div>

    <!-- الصف الثاني: بطاقات الإحصائيات العامة -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom: 30px;">
        
        <div style="background: white; border-radius: 12px; border: 1px solid #E2E8F0; padding: 20px; border-right: 4px solid #0B1B3D;">
            <span style="color: #64748B; font-size: 0.85rem; font-weight: 700; display: block; margin-bottom: 5px;">👥 إجمالي المستخدمين</span>
            <span style="color: #0B1B3D; font-size: 1.6rem; font-weight: 800;">{{ $stats['users_count'] }}</span>
        </div>

        <div style="background: white; border-radius: 12px; border: 1px solid #E2E8F0; padding: 20px; border-right: 4px solid #D4AF37;">
            <span style="color: #64748B; font-size: 0.85rem; font-weight: 700; display: block; margin-bottom: 5px;">🏪 المتاجر المسجلة</span>
            <span style="color: #0B1B3D; font-size: 1.6rem; font-weight: 800;">{{ $stats['vendors_count'] }}</span>
        </div>

        <div style="background: white; border-radius: 12px; border: 1px solid #E2E8F0; padding: 20px; border-right: 4px solid #1E6FB8;">
            <span style="color: #64748B; font-size: 0.85rem; font-weight: 700; display: block; margin-bottom: 5px;">🏷️ إجمالي المنتجات</span>
            <span style="color: #0B1B3D; font-size: 1.6rem; font-weight: 800;">{{ $stats['products_count'] }}</span>
        </div>

        <div style="background: white; border-radius: 12px; border: 1px solid #E2E8F0; padding: 20px; border-right: 4px solid #F59E0B;">
            <span style="color: #64748B; font-size: 0.85rem; font-weight: 700; display: block; margin-bottom: 5px;">📦 إجمالي الطلبات ({{ $stats['pending_orders'] ?? 0 }} قيد التنفيذ)</span>
            <span style="color: #0B1B3D; font-size: 1.6rem; font-weight: 800;">{{ $stats['orders_count'] }}</span>
        </div>

    </div>

    <!-- أحدث الطلبات في المنصة -->
    <div style="background: white; border-radius: 12px; border: 1px solid #E2E8F0; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 18px; border-bottom: 1px solid #F1F5F9; padding-bottom: 10px;">
            <h3 style="color: #0B1B3D; font-size: 1.2rem; font-weight: 800; margin: 0;">
                📦 أحدث الطلبات المسجلة في المنصة
            </h3>
            <a href="{{ route('admin.orders') }}" style="color: #1E6FB8; text-decoration: none; font-size: 0.85rem; font-weight: bold;">عرض كافة الطلبات ←</a>
        </div>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: right;">
                <thead>
                    <tr style="background: #F8FAFC; border-bottom: 1px solid #E2E8F0; color: #0B1B3D; font-size: 0.85rem;">
                        <th style="padding: 12px 15px;">رقم الطلب</th>
                        <th style="padding: 12px 15px;">العميل</th>
                        <th style="padding: 12px 15px;">المنتج</th>
                        <th style="padding: 12px 15px;">طريقة الدفع</th>
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
                            <td style="padding: 14px 15px; color: #334155; font-weight: bold;">{{ $order->product_name ?? ($order->product->name ?? 'منتج') }}</td>
                            <td style="padding: 14px 15px;">
                                @if(($order->payment_method ?? 'wallet') === 'cod')
                                    <span style="color: #92400E; font-size: 0.8rem; font-weight: bold;">عند الاستلام</span>
                                @else
                                    <span style="color: #166534; font-size: 0.8rem; font-weight: bold;">محفظة إلكترونية</span>
                                @endif
                            </td>
                            <td style="padding: 14px 15px; font-weight: 800; color: #1E6FB8;">{{ number_format($order->total_price, 0) }} ل.س</td>
                            <td style="padding: 14px 15px;">
                                @php
                                    $statusMap = [
                                        'pending'    => ['text' => 'قيد الانتظار', 'bg' => '#FEF9C3', 'color' => '#854D0E'],
                                        'processing' => ['text' => 'قيد التجهيز', 'bg' => '#DBEAFE', 'color' => '#1E40AF'],
                                        'shipped'    => ['text' => 'تم الشحن', 'bg' => '#E0E7FF', 'color' => '#3730A3'],
                                        'completed'  => ['text' => 'مكتمل ✓', 'bg' => '#DCFCE7', 'color' => '#166534'],
                                        'cancelled'  => ['text' => 'ملغي ✕', 'bg' => '#FEE2E2', 'color' => '#991B1B'],
                                    ];
                                    $st = $statusMap[$order->status] ?? ['text' => $order->status, 'bg' => '#F1F5F9', 'color' => '#475569'];
                                @endphp
                                <span style="background: {{ $st['bg'] }}; color: {{ $st['color'] }}; padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 700;">
                                    {{ $st['text'] }}
                                </span>
                            </td>
                            <td style="padding: 14px 15px; color: #64748B; font-size: 0.8rem;">{{ $order->created_at->format('Y-m-d H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 30px; color: #64748B;">لا توجد طلبات مسجلة حتى الآن.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection