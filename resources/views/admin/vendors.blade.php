@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 25px auto 50px; padding: 0 15px; font-family: 'Cairo', sans-serif;" dir="rtl">

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <div>
            <h2 style="color: #0B1B3D; font-size: 1.6rem; font-weight: 800; margin: 0 0 5px 0;">
                🏪 إدارة المتاجر والتجار
            </h2>
            <p style="color: #64748B; font-size: 0.9rem; margin: 0;">متابعة المتاجر المسجلة في منصة فُرات ستور والتحكم بحالتها</p>
        </div>
        <a href="{{ route('admin.dashboard') }}" style="background: #F1F5F9; color: #475569; text-decoration: none; padding: 8px 16px; border-radius: 8px; font-weight: 700; font-size: 0.85rem; border: 1px solid #CBD5E1;">
            ← العودة للوحة الإدارة
        </a>
    </div>

    @if(session('success'))
        <div style="background: #F0FDF4; color: #166534; border-right: 4px solid #16A34A; padding: 12px 18px; border-radius: 8px; margin-bottom: 20px; font-weight: bold; font-size: 0.9rem;">
            {{ session('success') }}
        </div>
    @endif

    <div style="background: white; border-radius: 12px; border: 1px solid #E2E8F0; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: right;">
                <thead>
                    <tr style="background: #F8FAFC; border-bottom: 1px solid #E2E8F0; color: #0B1B3D; font-size: 0.85rem;">
                        <th style="padding: 12px 15px;">اسم المتجر</th>
                        <th style="padding: 12px 15px;">اسم التاجر</th>
                        <th style="padding: 12px 15px;">البريد الإلكتروني</th>
                        <th style="padding: 12px 15px;">رقم الهاتف</th>
                        <th style="padding: 12px 15px;">العنوان</th>
                        <th style="padding: 12px 15px;">تاريخ الانضمام</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stores as $store)
                        <tr style="border-bottom: 1px solid #F1F5F9; font-size: 0.9rem;">
                            <td style="padding: 14px 15px; font-weight: 700; color: #0B1B3D;">{{ $store->name }}</td>
                            <td style="padding: 14px 15px; color: #334155;">{{ $store->user->name ?? 'غير متوفر' }}</td>
                            <td style="padding: 14px 15px; color: #64748B;">{{ $store->user->email ?? 'غير متوفر' }}</td>
                            <td style="padding: 14px 15px; color: #334155;">{{ $store->phone ?? 'غير متوفر' }}</td>
                            <td style="padding: 14px 15px; color: #64748B;">{{ $store->address ?? 'غير متوفر' }}</td>
                            <td style="padding: 14px 15px; color: #64748B; font-size: 0.8rem;">{{ $store->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 30px; color: #64748B;">لا توجد متاجر مسجلة حتى الآن.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection