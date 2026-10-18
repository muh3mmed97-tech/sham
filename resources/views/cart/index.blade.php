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
                    @php $grandTotal = 0; @endphp
                    @foreach($cartItems as $item)
                        @php 
                            $itemPrice = $item->product ? $item->product->price : 0;
                            $rowTotal = $itemPrice * $item->quantity;
                            $grandTotal += $rowTotal;
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

        <!-- صندوق الفاتورة وزر إتمام الشراء الصريح مع خيارات الدفع -->
        <div style="background: white; border-radius: 12px; border: 1px solid #E2E8F0; padding: 22px 28px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; box-shadow: 0 4px 12px rgba(0,0,0,0.02); position: relative; z-index: 10;">
            <div>
                <span style="color: #64748B; font-size: 0.95rem; display: block; margin-bottom: 4px;">المبلغ الإجمالي المستحق:</span>
                <span style="color: #0B1B3D; font-size: 1.7rem; font-weight: 800;">{{ number_format($grandTotal, 0) }}</span>
                <span style="color: #64748B; font-size: 0.9rem;"> ليرة سورية</span>
            </div>

            <!-- نموذج الشراء مع خيارات الدفع -->
            <form action="{{ route('cart.checkout') }}" method="POST" id="checkout-form" style="margin: 0; display: flex; align-items: center; gap: 15px; flex-wrap: wrap;">
                @csrf
                
                <div style="display: flex; gap: 15px; align-items: center; background: #F8FAFC; padding: 10px 15px; border-radius: 8px; border: 1px solid #E2E8F0;">
                    <label style="font-size: 0.85rem; font-weight: bold; color: #0B1B3D; cursor: pointer; display: flex; align-items: center; gap: 5px;">
                        <input type="radio" name="payment_method" value="wallet" checked style="accent-color: #0B1B3D;">
                        الدفع عبر المحفظة
                    </label>

                    <label style="font-size: 0.85rem; font-weight: bold; color: #0B1B3D; cursor: pointer; display: flex; align-items: center; gap: 5px;">
                        <input type="radio" name="payment_method" value="cod" style="accent-color: #0B1B3D;">
                        الدفع عند الاستلام
                    </label>
                </div>

                <button type="submit" id="btn-submit-order" 
                        style="background: #0B1B3D; color: #D4AF37; padding: 14px 28px; border: 2px solid #D4AF37; border-radius: 10px; cursor: pointer; font-size: 1.05rem; font-weight: 800; font-family: 'Cairo'; box-shadow: 0 4px 14px rgba(11,27,61,0.2);">
                    💳 إتمام عملية الشراء
                </button>
            </form>
        </div>
    @endif

</div>
@endsection