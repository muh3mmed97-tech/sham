@extends('layouts.app')

@section('content')

@php
    $store = $order->product->store ?? \App\Models\Store::where('user_id', $order->vendor_id)->first();
    
    // حساب المجموع الفرعي للقطعة
    $unitPrice = $order->price ?? 0;
    $quantity = $order->quantity ?? 1;
    $subtotal = $unitPrice * $quantity;
    
    // الإجمالي النهائي المخزن في الطلب
    $finalTotal = $order->total_price ?? $subtotal;
    
    // حساب قيمة الخصم تلقائياً من الفرق بين السعر الأصلي والإجمالي النهائي
    $discount = $subtotal - $finalTotal;
    if ($discount < 0) {
        $discount = 0;
    }
@endphp

<style>
@media print {
    body * { visibility: hidden; }
    nav, footer, .no-print, header, .btn-print-wrapper { display: none !important; }
    #printable-area, #printable-area * { visibility: visible; }
    #printable-area {
        position: absolute; left: 0; top: 0; width: 100% !important; max-width: 100% !important;
        margin: 0 !important; padding: 20px !important; border: none !important; box-shadow: none !important;
    }
    .watermark-container { opacity: 0.12 !important; display: block !important; }
    .invoice-grid { display: flex !important; flex-direction: row !important; justify-content: space-between !important; gap: 15px !important; }
    .invoice-grid > div { width: 48% !important; }
}
</style>

<div id="printable-area" style="position: relative; overflow: hidden; max-width: 800px; margin: 40px auto; background: #ffffff; padding: 40px; border-radius: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); border: 1px solid #E5E7EB; font-family: 'Cairo', sans-serif;" dir="rtl">
    
    <!-- العلامة المائية -->
    <div class="watermark-container" style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); opacity: 0.14; pointer-events: none; z-index: 0; text-align: center; width: 100%;">
        <img src="{{ asset('images/logo.png') }}" alt="Watermark" style="width: 380px; max-width: 75%; height: auto; filter: grayscale(15%);">
    </div>

    <div style="position: relative; z-index: 1;">

        <!-- هيدر الفاتورة -->
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 3px solid #0B1B3D; padding-bottom: 20px; margin-bottom: 25px;">
            <div style="display: flex; align-items: center; gap: 15px;">
                @if($store && $store->logo)
                    <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" style="width: 70px; height: 70px; border-radius: 12px; border: 2px solid #D4AF37; object-fit: cover;">
                @else
                    <img src="{{ asset('images/logo.png') }}" alt="شعار المتجر" style="width: 70px; height: 70px; border-radius: 12px; border: 2px solid #D4AF37; object-fit: cover;">
                @endif
                <div>
                    <h1 style="color: #0B1B3D; margin: 0; font-size: 1.4rem;">{{ $store->name ?? 'متجر فُرات' }}</h1>
                    <p style="margin: 3px 0 0 0; color: #D4AF37; font-weight: bold; font-size: 0.8rem;">عبر منصة فُرات ستور</p>
                </div>
            </div>

            <div style="text-align: left;">
                <h2 style="margin: 0; color: #0B1B3D; font-size: 1.3rem;">فاتورة طلب</h2>
                <p style="margin: 4px 0 0 0; color: #64748B; font-size: 0.85rem;">رقم الطلب: #{{ $order->id }}</p>
                <p style="margin: 2px 0 0 0; color: #64748B; font-size: 0.85rem;">التاريخ: {{ $order->created_at->format('Y-m-d H:i') }}</p>
            </div>
        </div>

        <!-- معلومات المتجر والمشتري -->
        <div class="invoice-grid" style="display: flex; justify-content: space-between; gap: 20px; margin-bottom: 25px;">
            <div style="flex: 1; padding: 15px; border-radius: 12px; border: 1.5px solid #CBD5E1;">
                <h3 style="color: #0B1B3D; margin-top: 0; margin-bottom: 10px; font-size: 1rem; border-bottom: 2px solid #D4AF37; padding-bottom: 5px;">🏪 معلومات المتجر (البائع):</h3>
                <p style="margin: 6px 0; color: #111827; font-size: 0.9rem;"><strong>اسم المتجر:</strong> {{ $store->name ?? 'متجر فُرات الرئيسي' }}</p>
                <p style="margin: 6px 0; color: #475569; font-size: 0.9rem;"><strong>📍 العنوان:</strong> {{ $store->address ?? 'غير محدد' }}</p>
                <p style="margin: 6px 0; color: #475569; font-size: 0.9rem;"><strong>📞 الهاتف:</strong> {{ $store->phone ?? 'غير متوفر' }}</p>
            </div>

            <div style="flex: 1; padding: 15px; border-radius: 12px; border: 1.5px solid #CBD5E1;">
                <h3 style="color: #0B1B3D; margin-top: 0; margin-bottom: 10px; font-size: 1rem; border-bottom: 2px solid #1E6FB8; padding-bottom: 5px;">👤 معلومات المشتري:</h3>
                <p style="margin: 6px 0; color: #111827; font-size: 0.9rem;"><strong>اسم العميل:</strong> {{ $order->customer->name ?? (Auth::user()->name ?? 'غير متوفر') }}</p>
                <p style="margin: 6px 0; color: #475569; font-size: 0.9rem;"><strong>📍 العنوان:</strong> {{ $order->customer->address ?? ($order->address ?? 'غير محدد') }}</p>
                <p style="margin: 6px 0; color: #475569; font-size: 0.9rem;"><strong>📞 الهاتف:</strong> {{ $order->customer->phone ?? ($order->phone ?? 'غير متوفر') }}</p>
            </div>
        </div>

        <!-- جدول المنتجات -->
        <table style="width: 100%; border-collapse: collapse; margin-bottom: 25px;">
            <thead>
                <tr style="background: #0B1B3D; color: white; text-align: right;">
                    <th style="padding: 12px 15px; border-top-right-radius: 8px;">المنتج</th>
                    <th style="padding: 12px 15px; text-align: center;">الكمية</th>
                    <th style="padding: 12px 15px; text-align: center;">السعر الفردي</th>
                    <th style="padding: 12px 15px; text-align: left; border-top-left-radius: 8px;">الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding: 15px; border-bottom: 1.5px solid #CBD5E1; color: #111827; font-weight: bold;">{{ $order->product_name ?? ($order->product->name ?? 'غير معروف') }}</td>
                    <td style="padding: 15px; border-bottom: 1.5px solid #CBD5E1; text-align: center;">{{ $quantity }}</td>
                    <td style="padding: 15px; border-bottom: 1.5px solid #CBD5E1; text-align: center;">{{ number_format($unitPrice, 0) }} ل.س</td>
                    <td style="padding: 15px; border-bottom: 1.5px solid #CBD5E1; color: #1E6FB8; font-weight: bold; text-align: left;">{{ number_format($subtotal, 0) }} ل.س</td>
                </tr>
            </tbody>
        </table>

        <!-- قسم المجموع وطريقة الدفع -->
        <div style="display: flex; justify-content: space-between; align-items: flex-start; border-top: 2px solid #0B1B3D; padding-top: 15px; flex-wrap: wrap; gap: 15px;">
            <div>
                @php $method = $order->payment_method ?? 'wallet'; @endphp
                @if($method === 'cod')
                    <div style="color: #92400E; border: 1.5px solid #F59E0B; padding: 8px 16px; border-radius: 8px; font-weight: bold; font-size: 0.95rem;">
                        📌 ملاحظة: الدفع عند الاستلام (COD)
                    </div>
                @elseif($method === 'card')
                    <div style="color: #1E40AF; border: 1.5px solid #3B82F6; padding: 8px 16px; border-radius: 8px; font-weight: bold; font-size: 0.95rem;">
                        💳 تم الدفع إلكترونياً (بطاقة ائتمانية)
                    </div>
                @else
                    <div style="color: #166534; border: 1.5px solid #16A34A; padding: 8px 16px; border-radius: 8px; font-weight: bold; font-size: 0.95rem;">
                        ✅ تم الدفع إلكترونياً (المحفظة)
                    </div>
                @endif
            </div>

            <!-- عرض المجموع الفرعي والخصم بذكاء بناءً على الفرق -->
            <div style="text-align: left;">
                @if($discount > 0)
                    <p style="margin: 0; color: #64748B; font-size: 0.85rem;">المجموع الفرعي: {{ number_format($subtotal, 0) }} ل.س</p>
                    <p style="margin: 2px 0 4px 0; color: #166534; font-size: 0.85rem; font-weight: bold;">
                        قيمة الخصم @if(!empty($order->coupon_code)) (كوبون: {{ $order->coupon_code }}) @endif: -{{ number_format($discount, 0) }} ل.س
                    </p>
                @endif

                <p style="margin: 0; color: #64748B; font-size: 0.9rem;">المجموع الكلي:</p>
                <h3 style="margin: 3px 0 0 0; color: #0B1B3D; font-size: 1.5rem;">{{ number_format($finalTotal, 0) }} ل.س</h3>
            </div>
        </div>

        <div style="margin-top: 35px; text-align: center; border-top: 1px dashed #CBD5E1; padding-top: 15px; color: #94A3B8; font-size: 0.8rem;">
            شكراً لتعاملكم مع <strong>{{ $store->name ?? 'متجرنا' }}</strong> عبر منصة <strong>فُرات ستور</strong> | تجارتنا عهد، وثقتكم أمانة
        </div>
    </div>
</div>

<div class="no-print btn-print-wrapper" style="margin-top: 20px; text-align: center;">
    <button onclick="window.print()" style="padding: 12px 30px; background: #D4AF37; color: #0B1B3D; border: none; border-radius: 10px; cursor: pointer; font-size: 1rem; font-weight: bold; box-shadow: 0 4px 12px rgba(212,175,55,0.3);">
        طباعة الفاتورة 🖨️
    </button>
    <a href="{{ route('customer.orders') }}" style="display: block; margin-top: 12px; color: #1E6FB8; text-decoration: none; font-weight: bold;">العودة لطلباتي</a>
</div>

@endsection