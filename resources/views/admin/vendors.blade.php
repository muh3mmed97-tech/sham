@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 25px auto 50px; padding: 0 15px; font-family: 'Cairo', sans-serif;" dir="rtl">

    <!-- أزرار التنقل العلوي -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 15px;">
        <div>
            <h2 style="color: #0B1B3D; font-size: 1.6rem; font-weight: 800; margin: 0 0 5px 0;">
                🏪 إدارة المتاجر والتجار
            </h2>
            <p style="color: #64748B; font-size: 0.9rem; margin: 0;">متابعة المتاجر النشطة على منصة فُرات ستور</p>
        </div>

        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('admin.dashboard') }}" style="background: white; color: #0B1B3D; padding: 9px 18px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 0.9rem; border: 1px solid #CBD5E1;">
                📊 الإحصائيات
            </a>
            <a href="{{ route('admin.vendors') }}" style="background: #0B1B3D; color: #D4AF37; padding: 9px 18px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 0.9rem; border: 1px solid #D4AF37;">
                🏪 إدارة المتاجر
            </a>
            <a href="{{ route('admin.wallets') }}" style="background: white; color: #0B1B3D; padding: 9px 18px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 0.9rem; border: 1px solid #CBD5E1;">
                💰 إدارة المحافظ
            </a>
            <a href="{{ route('admin.orders') }}" style="background: white; color: #0B1B3D; padding: 9px 18px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 0.9rem; border: 1px solid #CBD5E1;">
                📦 جميع الطلبات
            </a>
        </div>
    </div>

    @if(session('success'))
        <div style="background: #DCFCE7; color: #166534; padding: 12px 20px; border-radius: 10px; border: 1px solid #86EFAC; margin-bottom: 20px; font-weight: bold;">
            ✓ {{ session('success') }}
        </div>
    @endif

    <!-- جدول المتاجر -->
    <div style="background: white; border-radius: 12px; border: 1px solid #E2E8F0; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
        <h3 style="color: #0B1B3D; font-size: 1.2rem; font-weight: 800; margin-top: 0; margin-bottom: 18px; border-bottom: 1px solid #F1F5F9; padding-bottom: 10px;">
            قائمة المتاجر المسجلة ({{ $stores->count() }})
        </h3>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: right;">
                <thead>
                    <tr style="background: #F8FAFC; border-bottom: 1px solid #E2E8F0; color: #0B1B3D; font-size: 0.85rem;">
                        <th style="padding: 12px 15px;">الشعار</th>
                        <th style="padding: 12px 15px;">اسم المتجر</th>
                        <th style="padding: 12px 15px;">التاجر المالك</th>
                        <th style="padding: 12px 15px;">رقم الهاتف</th>
                        <th style="padding: 12px 15px;">العنوان</th>
                        <th style="padding: 12px 15px; text-align: center;">المنتجات</th>
                        <th style="padding: 12px 15px; text-align: center;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stores as $store)
                        <tr style="border-bottom: 1px solid #F1F5F9; font-size: 0.9rem;">
                            <td style="padding: 12px 15px;">
                                @if($store->logo)
                                    <img src="{{ asset('storage/' . $store->logo) }}" alt="{{ $store->name }}" style="width: 45px; height: 45px; border-radius: 8px; object-fit: cover; border: 1.5px solid #D4AF37;">
                                @else
                                    <div style="width: 45px; height: 45px; border-radius: 8px; background: #0B1B3D; color: #D4AF37; display: flex; align-items: center; justify-content: center; font-weight: bold;">🏪</div>
                                @endif
                            </td>
                            <td style="padding: 12px 15px; font-weight: 700; color: #0B1B3D;">
                                <a href="{{ route('store.show', $store->user_id) }}" target="_blank" style="color: #0B1B3D; text-decoration: none;">
                                    {{ $store->name }} ↗
                                </a>
                            </td>
                            <td style="padding: 12px 15px; color: #334155;">{{ $store->user->name ?? 'غير متوفر' }} <br><small style="color: #64748B;">{{ $store->user->email ?? '' }}</small></td>
                            <td style="padding: 12px 15px; color: #475569;">{{ $store->phone ?? 'غير متوفر' }}</td>
                            <td style="padding: 12px 15px; color: #475569;">{{ $store->address ?? 'غير محدد' }}</td>
                            <td style="padding: 12px 15px; text-align: center; font-weight: bold; color: #1E6FB8;">
                                <span style="background: #E0E7FF; padding: 3px 10px; border-radius: 6px;">{{ $store->products_count ?? 0 }}</span>
                            </td>
                            <td style="padding: 12px 15px; text-align: center;">
                                <form action="{{ route('admin.vendors.delete', $store->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا المتجر؟');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" style="background: #FEE2E2; color: #991B1B; border: none; padding: 6px 12px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 0.8rem;">
                                        حذف المتجر ✕
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" style="text-align: center; padding: 30px; color: #64748B;">لا توجد متاجر مسجلة حالياً.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection