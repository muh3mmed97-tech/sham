@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 25px auto 50px; padding: 0 15px; font-family: 'Cairo', sans-serif;" dir="rtl">

    <!-- أزرار التنقل العلوي -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; flex-wrap: wrap; gap: 15px;">
        <div>
            <h2 style="color: #0B1B3D; font-size: 1.6rem; font-weight: 800; margin: 0 0 5px 0;">
                ⚖️ إدارة النزاعات وطلبات الإرجاع
            </h2>
            <p style="color: #64748B; font-size: 0.9rem; margin: 0;">مراجعة وحل الشكاوى وطلبات الإرجاع المقدمة من العملاء</p>
        </div>

        <div style="display: flex; gap: 10px; flex-wrap: wrap;">
            <a href="{{ route('admin.dashboard') }}" style="background: white; color: #0B1B3D; padding: 9px 18px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 0.9rem; border: 1px solid #CBD5E1;">
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
            <a href="{{ route('admin.disputes') }}" style="background: #0B1B3D; color: #D4AF37; padding: 9px 18px; border-radius: 8px; text-decoration: none; font-weight: bold; font-size: 0.9rem; border: 1px solid #D4AF37;">
                ⚖️ النزاعات
            </a>
        </div>
    </div>

    @if(session('success'))
        <div style="background: #DCFCE7; color: #166534; padding: 12px 20px; border-radius: 10px; border: 1px solid #86EFAC; margin-bottom: 20px; font-weight: bold;">
            ✓ {{ session('success') }}
        </div>
    @endif

    <!-- جدول النزاعات -->
    <div style="background: white; border-radius: 12px; border: 1px solid #E2E8F0; padding: 24px; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
        <h3 style="color: #0B1B3D; font-size: 1.2rem; font-weight: 800; margin-top: 0; margin-bottom: 18px; border-bottom: 1px solid #F1F5F9; padding-bottom: 10px;">
            قائمة النزاعات وطلبات الإرجاع المسجلة ({{ $disputes->count() }})
        </h3>

        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: right;">
                <thead>
                    <tr style="background: #F8FAFC; border-bottom: 1px solid #E2E8F0; color: #0B1B3D; font-size: 0.85rem;">
                        <th style="padding: 12px 15px;">رقم النزاع / الطلب</th>
                        <th style="padding: 12px 15px;">العميل</th>
                        <th style="padding: 12px 15px;">المنتج والتاجر</th>
                        <th style="padding: 12px 15px;">سبب الإرجاع / الشكوى</th>
                        <th style="padding: 12px 15px;">الحالة</th>
                        <th style="padding: 12px 15px; text-align: center;">الإجراء والرد</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($disputes as $dispute)
                        <tr style="border-bottom: 1px solid #F1F5F9; font-size: 0.9rem; vertical-align: top;">
                            <td style="padding: 14px 15px; font-weight: 700; color: #0B1B3D;">
                                #{{ $dispute->id }} <br>
                                <span style="font-size: 0.78rem; color: #64748B;">للطلب: #{{ $dispute->order_id }}</span>
                            </td>
                            <td style="padding: 14px 15px; color: #334155;">
                                {{ $dispute->user->name ?? 'عميل' }} <br>
                                <small style="color: #64748B;">{{ $dispute->user->email ?? '' }}</small>
                            </td>
                            <td style="padding: 14px 15px; color: #334155;">
                                <strong>{{ $dispute->order->product_name ?? 'منتج' }}</strong> <br>
                                <span style="font-size: 0.8rem; color: #1E6FB8;">الإجمالي: {{ number_format($dispute->order->total_price ?? 0, 0) }} ل.س</span>
                            </td>
                            <td style="padding: 14px 15px; color: #475569; max-width: 250px; line-height: 1.4;">
                                {{ $dispute->reason }}
                            </td>
                            <td style="padding: 14px 15px;">
                                @php
                                    $statusMap = [
                                        'pending'  => ['text' => 'قيد المراجعة', 'bg' => '#FEF9C3', 'color' => '#854D0E'],
                                        'resolved' => ['text' => 'تم الحل وإعادة المبلغ ✓', 'bg' => '#DCFCE7', 'color' => '#166534'],
                                        'rejected' => ['text' => 'مرفوض ✕', 'bg' => '#FEE2E2', 'color' => '#991B1B'],
                                    ];
                                    $st = $statusMap[$dispute->status] ?? ['text' => $dispute->status, 'bg' => '#F1F5F9', 'color' => '#475569'];
                                @endphp
                                <span style="background: {{ $st['bg'] }}; color: {{ $st['color'] }}; padding: 4px 10px; border-radius: 6px; font-size: 0.75rem; font-weight: 700; white-space: nowrap;">
                                    {{ $st['text'] }}
                                </span>
                                @if($dispute->admin_response)
                                    <div style="margin-top: 6px; font-size: 0.78rem; color: #334155; background: #F8FAFC; padding: 6px; border-radius: 6px;">
                                        <strong>رد الإدارة:</strong> {{ $dispute->admin_response }}
                                    </div>
                                @endif
                            </td>
                            <td style="padding: 14px 15px; text-align: center;">
                                @if($dispute->status === 'pending')
                                    <form action="{{ route('admin.disputes.update', $dispute->id) }}" method="POST" style="display: flex; flex-direction: column; gap: 8px;">
                                        @csrf
                                        @method('PUT')
                                        <select name="status" required style="padding: 6px; border-radius: 6px; border: 1px solid #CBD5E1; font-family: 'Cairo'; font-size: 0.8rem;">
                                            <option value="resolved">قبول وإعادة المبلغ (حل)</option>
                                            <option value="rejected">رفض الطلب</option>
                                        </select>
                                        <input type="text" name="admin_response" placeholder="اكتب سبب القرار أو رد الإدارة..." style="padding: 6px; border-radius: 6px; border: 1px solid #CBD5E1; font-family: 'Cairo'; font-size: 0.8rem;">
                                        <button type="submit" style="background: #0B1B3D; color: #D4AF37; border: none; padding: 7px; border-radius: 6px; font-weight: bold; cursor: pointer; font-size: 0.8rem;">
                                            حفظ المعالجة ✓
                                        </button>
                                    </form>
                                @else
                                    <span style="color: #64748B; font-size: 0.85rem;">تمت المعالجة</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center; padding: 30px; color: #64748B;">لا توجد نزاعات أو طلبات إرجاع مسجلة حتى الآن.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection