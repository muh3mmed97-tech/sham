@extends('layouts.app')

@section('content')
<div style="max-width: 1100px; margin: 25px auto 60px; padding: 0 15px; font-family: 'Cairo', sans-serif;" dir="rtl">

    <h2 style="color: #0B1B3D; font-size: 1.5rem; font-weight: 800; margin-bottom: 20px;">🛒 سلة المشتريات</h2>

    @if(session('success'))
        <div style="background: #F0FDF4; color: #166534; border-right: 4px solid #16A34A; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-weight: bold;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: #FEF2F2; color: #991B1B; border-right: 4px solid #DC2626; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-weight: bold;">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    @if($cartItems->isEmpty())
        <div style="text-align: center; padding: 60px 20px; background: white; border-radius: 12px; border: 1px solid #E2E8F0; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
            <div style="font-size: 3rem; margin-bottom: 10px;">🛍️</div>
            <p style="color: #64748B; font-size: 1.1rem; margin-bottom: 20px;">سلة التسوق فارغة تماماً.</p>
            <a href="{{ route('home') }}" style="background: #0B1B3D; color: #D4AF37; padding: 10px 25px; border-radius: 25px; text-decoration: none; font-weight: bold; display: inline-block;">
                تصفح المنتجات الآن
            </a>
        </div>
    @else
        <!-- صندوق تطبيق كوبون الخصم -->
        <div style="background: white; border-radius: 12px; border: 1px solid #E2E8F0; padding: 18px 22px; margin-bottom: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
            <form action="{{ route('cart.applyCoupon') }}" method="POST" style="display: flex; gap: 12px; align-items: center; flex-wrap: wrap; margin: 0;">
                @csrf
                <div style="flex: 1; min-width: 240px;">
                    <input type="text" name="coupon_code" placeholder="أدخل كود قسيمة الخصم (مثال: FURAT2026)" value="{{ session('applied_coupon')['code'] ?? '' }}" required style="width: 100%; padding: 10px 14px; border: 1px solid #CBD5E1; border-radius: 8px; font-size: 0.9rem; font-family: 'Cairo';">
                </div>
                <button type="submit" style="background: #0B1B3D; color: #D4AF37; border: none; padding: 10px 22px; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 0.9rem; font-family: 'Cairo';">
                    تطبيق الكوبون
                </button>
            </form>

            @if(session('applied_coupon'))
                <div style="margin-top: 10px; color: #166534; font-size: 0.88rem; font-weight: bold; background: #F0FDF4; padding: 8px 12px; border-radius: 6px; border: 1px solid #BBF7D0;">
                    ✓ تم تطبيق كود الخصم ({{ session('applied_coupon')['code'] }}) بقيمة تخفيض: {{ number_format(session('applied_coupon')['discount'], 0) }} ل.س
                </div>
            @endif
        </div>

        <!-- جدول المنتجات -->
        <div style="background: white; border-radius: 12px; border: 1px solid #E2E8F0; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.02); margin-bottom: 25px;">
            <table style="width: 100%; border-collapse: collapse; text-align: right;">
                <thead>
                    <tr style="background: #F8FAFC; border-bottom: 1px solid #E2E8F0; color: #0B1B3D; font-size: 0.9rem;">
                        <th style="padding: 14px 18px;">المنتج</th>
                        <th style="padding: 14px 18px;">السعر</th>
                        <th style="padding: 14px 18px;">الكمية</th>
                        <th style="padding: 14px 18px;">الإجمالي</th>
                        <th style="padding: 14px 18px; text-align: center;">إجراء</th>
                    </tr>
                </thead>
                <tbody>
                    @php 
                        $grandTotal = 0; 
                        $subtotal = 0;
                    @endphp
                    @foreach($cartItems as $item)
                        @php 
                            $itemPrice = $item->product ? $item->product->price : 0;
                            $rowTotal = $itemPrice * $item->quantity;
                            $subtotal += $rowTotal;
                        @endphp
                        <tr style="border-bottom: 1px solid #F1F5F9;">
                            <td style="padding: 16px 18px; color: #0F172A; font-weight: 700;">
                                {{ $item->product->name ?? 'منتج غير متوفر' }}
                            </td>
                            <td style="padding: 16px 18px; color: #1E6FB8; font-weight: 800;">
                                {{ number_format($itemPrice, 0) }} ل.س
                            </td>
                            <td style="padding: 16px 18px;">
                                <form action="{{ route('cart.update', $item->id) }}" method="POST" style="display: inline-flex; align-items: center; gap: 6px; margin: 0;">
                                    @csrf 
                                    @method('PATCH')
                                    <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" 
                                           style="width: 55px; padding: 6px 8px; border: 1.5px solid #CBD5E1; border-radius: 6px; text-align: center; font-family: 'Cairo';">
                                    <button type="submit" style="padding: 6px 10px; cursor: pointer; background: #0B1B3D; color: #D4AF37; border: none; border-radius: 6px; font-weight: bold; font-family: 'Cairo'; font-size: 0.8rem;">
                                        تحديث
                                    </button>
                                </form>
                            </td>
                            <td style="padding: 16px 18px; color: #0B1B3D; font-weight: 800;">
                                {{ number_format($rowTotal, 0) }} ل.س
                            </td>
                            <td style="padding: 16px 18px; text-align: center;">
                                <form action="{{ route('cart.remove', $item->id) }}" method="POST" style="display: inline; margin: 0;">
                                    @csrf 
                                    @method('DELETE')
                                    <button type="submit" style="background: #FEE2E2; color: #DC2626; border: 1px solid #FCA5A5; padding: 6px 12px; border-radius: 6px; cursor: pointer; font-family: 'Cairo'; font-weight: bold; font-size: 0.8rem;">
                                        حذف
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @php
            $discount = session('applied_coupon')['discount'] ?? 0;
            $grandTotal = max(0, $subtotal - $discount);
        @endphp

        <!-- صندوق الفاتورة وزر إتمام الشراء الصريح مع خيارات الدفع -->
        <div style="background: white; border-radius: 12px; border: 1px solid #E2E8F0; padding: 22px 28px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.02); position: relative; z-index: 10;">
            <div>
                @if($discount > 0)
                    <span style="color: #64748B; font-size: 0.85rem; display: block;">المجموع الفرعي: {{ number_format($subtotal, 0) }} ل.س</span>
                    <span style="color: #166534; font-size: 0.85rem; display: block; margin-bottom: 2px;">قيمة الخصم: -{{ number_format($discount, 0) }} ل.س</span>
                @endif
                <span style="color: #64748B; font-size: 0.95rem; display: block; margin-bottom: 4px;">المبلغ الإجمالي المستحق:</span>
                <span style="color: #0B1B3D; font-size: 1.7rem; font-weight: 800;">{{ number_format($grandTotal, 0) }}</span>
                <span style="color: #64748B; font-size: 0.9rem;"> ليرة سورية</span>
            </div>

            <!-- نموذج الشراء مع خيارات الدفع -->
            <form action="{{ route('cart.checkout') }}" method="POST" id="checkout-form" style="margin: 0; display: flex; flex-direction: column; gap: 12px; align-items: flex-end; width: 380px; max-width: 100%;">
                @csrf
                
                <!-- خيارات الدفع الاحترافية -->
                <div style="display: flex; flex-direction: column; gap: 10px; background: #F8FAFC; padding: 12px 15px; border-radius: 8px; border: 1px solid #E2E8F0; width: 100%; box-sizing: border-box;">
                    <div style="display: flex; gap: 15px; align-items: center; flex-wrap: wrap;">
                        <label style="font-size: 0.85rem; font-weight: bold; color: #0B1B3D; cursor: pointer; display: flex; align-items: center; gap: 5px;">
                            <input type="radio" name="payment_method" value="wallet" checked style="accent-color: #0B1B3D;" onclick="toggleCardFields()">
                            المحفظة
                        </label>

                        <label style="font-size: 0.85rem; font-weight: bold; color: #0B1B3D; cursor: pointer; display: flex; align-items: center; gap: 5px;">
                            <input type="radio" name="payment_method" value="card" style="accent-color: #0B1B3D;" onclick="toggleCardFields()">
                            بطاقة ائتمانية 💳
                        </label>

                        <label style="font-size: 0.85rem; font-weight: bold; color: #0B1B3D; cursor: pointer; display: flex; align-items: center; gap: 5px;">
                            <input type="radio" name="payment_method" value="cod" style="accent-color: #0B1B3D;" onclick="toggleCardFields()">
                            عند الاستلام
                        </label>
                    </div>

                    <!-- حقول تفاصيل البطاقة (اسم الحامل، رقم البطاقة، التاريخ، والـ CVV) -->
                    <div id="card-details-box" style="display: none; margin-top: 10px; padding-top: 10px; border-top: 1px dashed #CBD5E1;">
                        <!-- اسم حامل البطاقة -->
                        <div style="margin-bottom: 8px;">
                            <input type="text" name="cardholder_name" placeholder="اسم حامل البطاقة (كما هو مكتوب عليها)" style="width: 100%; padding: 8px 10px; border: 1px solid #CBD5E1; border-radius: 6px; font-size: 0.85rem; font-family: 'Cairo'; background: white; box-sizing: border-box;">
                        </div>
                        <!-- رقم البطاقة -->
                        <div style="margin-bottom: 8px;">
                            <input type="text" name="card_number" placeholder="رقم البطاقة (4532 •••• •••• 8920)" maxlength="19" style="width: 100%; padding: 8px 10px; border: 1px solid #CBD5E1; border-radius: 6px; font-size: 0.85rem; font-family: 'Cairo'; background: white; box-sizing: border-box;">
                        </div>
                        <!-- تواريخ الصلاحية ورمز الأمان -->
                        <div style="display: flex; gap: 8px;">
                            <select name="card_month" style="flex: 1; padding: 8px; border: 1px solid #CBD5E1; border-radius: 6px; font-size: 0.85rem; font-family: 'Cairo'; background: white;">
                                <option value="">الشهر</option>
                                @for($i=1; $i<=12; $i++)
                                    <option value="{{ sprintf('%02d', $i) }}">{{ sprintf('%02d', $i) }}</option>
                                @endfor
                            </select>
                            <select name="card_year" style="flex: 1; padding: 8px; border: 1px solid #CBD5E1; border-radius: 6px; font-size: 0.85rem; font-family: 'Cairo'; background: white;">
                                <option value="">السنة</option>
                                @for($i=2026; $i<=2035; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                            <input type="text" name="card_cvv" placeholder="CVV" maxlength="4" style="width: 70px; padding: 8px; border: 1px solid #CBD5E1; border-radius: 6px; text-align: center; font-size: 0.85rem; font-family: 'Cairo'; background: white; box-sizing: border-box;">
                        </div>
                    </div>
                </div>

                <button type="submit" id="btn-submit-order" 
                        style="background: #0B1B3D; color: #D4AF37; padding: 14px 28px; border: 2px solid #D4AF37; border-radius: 10px; cursor: pointer; font-size: 1.05rem; font-weight: 800; font-family: 'Cairo'; box-shadow: 0 4px 14px rgba(11,27,61,0.2); width: 100%; box-sizing: border-box;">
                    💳 إتمام عملية الشراء
                </button>
            </form>
        </div>
    @endif

</div>

<script>
function toggleCardFields() {
    const cardSelected = document.querySelector('input[name="payment_method"][value="card"]').checked;
    const cardBox = document.getElementById('card-details-box');
    if (cardSelected) {
        cardBox.style.display = 'block';
    } else {
        cardBox.style.display = 'none';
    }
}
</script>
@endsection