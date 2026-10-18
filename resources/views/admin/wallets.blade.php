@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 25px auto 50px; padding: 0 15px; font-family: 'Cairo', sans-serif;" dir="rtl">

    <!-- أزرار التنقل العلوي -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 15px;">
        <div>
            <h2 style="color: #0B1B3D; font-size: 1.6rem; font-weight: 800; margin: 0 0 5px 0;">
                💰 إدارة المحافظ والأرصدة المالية
            </h2>
            <p style="color: #64748B; font-size: 0.9rem; margin: 0;">مراقبة وشحن أرصدة المستخدمين في منصة فُرات ستور</p>
        </div>

        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('admin.dashboard') }}" style="background: white; color: #0B1B3D; padding: 9px 18px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 0.9rem; border: 1px solid #CBD5E1;">
                📊 الإحصائيات
            </a>
            <a href="{{ route('admin.vendors') }}" style="background: white; color: #0B1B3D; padding: 9px 18px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 0.9rem; border: 1px solid #CBD5E1;">
                🏪 إدارة المتاجر
            </a>
            <a href="{{ route('admin.wallets') }}" style="background: #0B1B3D; color: #D4AF37; padding: 9px 18px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 0.9rem; border: 1px solid #D4AF37;">
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

    @if(session('error'))
        <div style="background: #FEE2E2; color: #991B1B; padding: 12px 20px; border-radius: 10px; border: 1px solid #FCA5A5; margin-bottom: 20px; font-weight: bold;">
            ✕ {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div style="background: #FEE2E2; color: #991B1B; padding: 12px 20px; border-radius: 10px; border: 1px solid #FCA5A5; margin-bottom: 20px;">
            <ul style="margin: 0; padding-right: 20px;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- نموذج تعديل رصيد مستخدم -->
    <div style="background: white; border-radius: 12px; border: 1px solid #E2E8F0; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.02); margin-bottom: 30px;">
        <h3 style="color: #0B1B3D; font-size: 1.15rem; font-weight: 800; margin-top: 0; margin-bottom: 15px;">
            ⚡ تعديل رصيد مستخدم (شحن أو خصم يدوي)
        </h3>
        
        <form action="{{ route('admin.wallets.adjust') }}" method="POST" style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)) 140px; gap: 15px; align-items: end;">
            @csrf
            <div>
                <label style="display: block; font-weight: bold; color: #334155; margin-bottom: 5px; font-size: 0.9rem;">اختر المستخدم:</label>
                <select name="user_id" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #CBD5E1; font-family: 'Cairo';">
                    <option value="">-- اختر مستخدم --</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->email }}) - الدور: {{ $u->role }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label style="display: block; font-weight: bold; color: #334155; margin-bottom: 5px; font-size: 0.9rem;">نوع العملية:</label>
                <select name="type" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #CBD5E1; font-family: 'Cairo';">
                    <option value="deposit">إيداع (شحن رصيد)</option>
                    <option value="withdraw">خصم (سحب رصيد)</option>
                </select>
            </div>

            <div>
                <label style="display: block; font-weight: bold; color: #334155; margin-bottom: 5px; font-size: 0.9rem;">المبلغ (ل.س):</label>
                <input type="number" name="amount" min="1" step="any" placeholder="مثال: 50000" required style="width: 100%; padding: 10px; border-radius: 8px; border: 1px solid #CBD5E1; font-family: 'Cairo'; box-sizing: border-box;">
            </div>

            <div>
                <button type="submit" style="width: 100%; background: #D4AF37; color: #0B1B3D; border: none; padding: 11px; border-radius: 8px; font-weight: bold; cursor: pointer; font-size: 0.95rem; box-shadow: 0 4px 10px rgba(212,175,55,0.25);">
                    تنفيذ العملية ✓
                </button>
            </div>
        </form>
    </div>

    <!-- جدول أرصدة المحافظ -->
    <div style="background: white; border-radius: 12px; border: 1px solid #E2E8F0; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
        <h3 style="color: #0B1B3D; font-size: 1.2rem; font-weight: 800; margin-top: 0; margin-bottom: 18px; border-bottom: 1px solid #F1F5F9; padding-bottom: 10px;">
            أرصدة محافظ المستخدمين في المنصة
        </h3>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: right;">
                <thead>
                    <tr style="background: #F8FAFC; border-bottom: 1px solid #E2E8F0; color: #0B1B3D; font-size: 0.85rem;">
                        <th style="padding: 12px 15px;">المستخدم</th>
                        <th style="padding: 12px 15px;">البريد الإلكتروني</th>
                        <th style="padding: 12px 15px;">نوع الحساب (الدور)</th>
                        <th style="padding: 12px 15px; text-align: left;">رصيد المحفظة الحالي</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <tr style="border-bottom: 1px solid #F1F5F9; font-size: 0.9rem;">
                            <td style="padding: 12px 15px; font-weight: 700; color: #0B1B3D;">{{ $user->name }}</td>
                            <td style="padding: 12px 15px; color: #475569;">{{ $user->email }}</td>
                            <td style="padding: 12px 15px;">
                                @if($user->role === 'admin')
                                    <span style="background: #0B1B3D; color: #D4AF37; padding: 3px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: bold;">مدير</span>
                                @elseif($user->role === 'vendor')
                                    <span style="background: #FEF3C7; color: #92400E; padding: 3px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: bold;">تاجر</span>
                                @else
                                    <span style="background: #E0E7FF; color: #3730A3; padding: 3px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: bold;">عميل</span>
                                @endif
                            </td>
                            <td style="padding: 12px 15px; text-align: left; font-weight: 900; color: #16A34A; font-size: 1.05rem;">
                                {{ number_format($user->wallet->balance ?? 0, 0) }} ل.س
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 30px; color: #64748B;">لا يوجد مستخدمين مسجلين.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection