@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 25px auto 50px; padding: 0 15px; font-family: 'Cairo', sans-serif;" dir="rtl">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <h2 style="color: #0B1B3D; font-size: 1.6rem; font-weight: 800; margin: 0;">🎟️ إدارة الكوبونات وقسائم الخصم</h2>
        <a href="{{ route('admin.dashboard') }}" style="background: #0B1B3D; color: #D4AF37; padding: 8px 16px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 0.9rem;">العودة للوحة التحكم</a>
    </div>

    @if(session('success'))
        <div style="background: #DCFCE7; color: #166534; padding: 12px 20px; border-radius: 10px; margin-bottom: 20px; font-weight: bold;">✓ {{ session('success') }}</div>
    @endif

    <!-- نموذج إضافة كوبون جديد -->
    <div style="background: white; border-radius: 12px; border: 1px solid #E2E8F0; padding: 20px; margin-bottom: 25px; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
        <h3 style="color: #0B1B3D; font-size: 1.1rem; font-weight: 800; margin-top: 0; margin-bottom: 15px;">إنشاء كوبون خصم جديد</h3>
        
        <form action="{{ route('admin.coupons.store') }}" method="POST" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 15px; align-items: end;">
            @csrf
            <div>
                <label style="font-size: 0.8rem; font-weight: bold; color: #334155; display: block; margin-bottom: 5px;">كود الكوبون (مثال: FURAT2026)</label>
                <input type="text" name="code" required style="width: 100%; padding: 8px; border: 1px solid #CBD5E1; border-radius: 6px; font-family: 'Cairo';">
            </div>
            <div>
                <label style="font-size: 0.8rem; font-weight: bold; color: #334155; display: block; margin-bottom: 5px;">نوع الخصم</label>
                <select name="type" style="width: 100%; padding: 8px; border: 1px solid #CBD5E1; border-radius: 6px; font-family: 'Cairo';">
                    <option value="percent">نسبة مئوية (%)</option>
                    <option value="fixed">مبلغ ثابت (ل.س)</option>
                </select>
            </div>
            <div>
                <label style="font-size: 0.8rem; font-weight: bold; color: #334155; display: block; margin-bottom: 5px;">قيمة الخصم</label>
                <input type="number" step="0.01" name="value" required style="width: 100%; padding: 8px; border: 1px solid #CBD5E1; border-radius: 6px; font-family: 'Cairo';">
            </div>
            <div>
                <label style="font-size: 0.8rem; font-weight: bold; color: #334155; display: block; margin-bottom: 5px;">الحد الأدنى للطلب (اختياري)</label>
                <input type="number" step="0.01" name="min_spend" value="0" style="width: 100%; padding: 8px; border: 1px solid #CBD5E1; border-radius: 6px; font-family: 'Cairo';">
            </div>
            <div>
                <label style="font-size: 0.8rem; font-weight: bold; color: #334155; display: block; margin-bottom: 5px;">تاريخ انتهاء الصلاحية</label>
                <input type="datetime-local" name="expires_at" style="width: 100%; padding: 8px; border: 1px solid #CBD5E1; border-radius: 6px; font-family: 'Cairo';">
            </div>
            <div>
                <button type="submit" style="width: 100%; background: #D4AF37; color: #0B1B3D; border: none; padding: 9px; border-radius: 6px; font-weight: bold; cursor: pointer; font-family: 'Cairo';">إضافة الكوبون ✓</button>
            </div>
        </form>
    </div>

    <!-- جدول الكوبونات الحالية -->
    <div style="background: white; border-radius: 12px; border: 1px solid #E2E8F0; padding: 20px;">
        <h3 style="color: #0B1B3D; font-size: 1.1rem; font-weight: 800; margin-top: 0; margin-bottom: 15px;">قسائم الخصم النشطة</h3>
        <table style="width: 100%; border-collapse: collapse; text-align: right;">
            <thead>
                <tr style="background: #F8FAFC; border-bottom: 1px solid #E2E8F0; color: #0B1B3D; font-size: 0.85rem;">
                    <th style="padding: 10px;">الكود</th>
                    <th style="padding: 10px;">النوع والقيمة</th>
                    <th style="padding: 10px;">الحد الأدنى</th>
                    <th style="padding: 10px;">تاريخ الانتهاء</th>
                    <th style="padding: 10px; text-align: center;">الإجراء</th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                <tr style="border-bottom: 1px solid #F1F5F9; font-size: 0.9rem;">
                    <td style="padding: 12px; font-weight: bold; color: #1E6FB8;">{{ $coupon->code }}</td>
                    <td style="padding: 12px;">{{ $coupon->value }} {{ $coupon->type == 'percent' ? '%' : 'ل.س' }}</td>
                    <td style="padding: 12px;">{{ number_format($coupon->min_spend, 0) }} ل.س</td>
                    <td style="padding: 12px;">{{ $coupon->expires_at ? $coupon->expires_at->format('Y-m-d H:i') : 'دائم' }}</td>
                    <td style="padding: 12px; text-align: center;">
                        <form action="{{ route('admin.coupons.delete', $coupon->id) }}" method="POST" style="margin: 0;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" onclick="return confirm('هل أنت متأكد من الحذف؟')" style="background: #FEE2E2; color: #DC2626; border: none; padding: 5px 10px; border-radius: 4px; cursor: pointer; font-size: 0.8rem; font-weight: bold;">حذف</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" style="text-align: center; padding: 20px; color: #64748B;">لا توجد كوبونات خصم مضافة حالياً.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>
@endsection