@extends('layouts.app')

@section('content')
<style>
    @media print {
        form, button, .no-print { display: none !important; }
        body { padding: 20px; }
        table { width: 100% !important; border: 1px solid #ddd; }
    }
</style>

<div style="max-width: 800px; margin: 40px auto; padding: 30px; background: #fff; border: 1px solid #ddd; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
    
    <h2 style="text-align: center; color: #333;">
        {{ auth()->user()->role == 'customer' ? 'شحن رصيد المحفظة' : 'طلب سحب الأرباح' }}
    </h2>

    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px; text-align: center;">{{ session('success') }}</div>
    @endif

    <form action="{{ auth()->user()->role == 'customer' ? route('wallet.charge') : route('wallet.withdraw') }}" method="POST" style="margin-bottom: 40px;">
        @csrf
        <div style="margin-bottom: 15px;">
            <label style="font-weight: bold;">أدخل المبلغ:</label>
            <input type="number" name="amount" required step="1" min="1" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;">
        </div>

        @if(auth()->user()->role == 'vendor')
        <div style="margin-bottom: 15px;">
            <label style="font-weight: bold;">طريقة السحب:</label>
            <select name="method" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px;">
                <option value="sham_cash">شام كاش</option>
                <option value="bank">حوالة بنكية</option>
            </select>
        </div>
        @endif

        <button type="submit" style="width: 100%; padding: 12px; background: {{ auth()->user()->role == 'customer' ? '#2e7d32' : '#e65100' }}; color: white; border: none; border-radius: 5px; font-weight: bold; cursor: pointer;">
            {{ auth()->user()->role == 'customer' ? 'تأكيد عملية الشحن' : 'تأكيد طلب السحب' }}
        </button>
    </form>

    <hr style="border: 0; border-top: 1px solid #eee; margin-bottom: 30px;">

    <h3 style="color: #333;">سجل العمليات السابقة</h3>
    <table style="width: 100%; border-collapse: collapse; margin-top: 15px;">
        <thead>
            <tr style="background: #f8f9fa;">
                <th style="padding: 10px; border-bottom: 2px solid #ddd; text-align: right;">المبلغ</th>
                <th style="padding: 10px; border-bottom: 2px solid #ddd; text-align: right;">الطريقة/النوع</th>
                <th style="padding: 10px; border-bottom: 2px solid #ddd; text-align: right;">التاريخ والساعة</th>
                <th style="padding: 10px; border-bottom: 2px solid #ddd; text-align: right;">الحالة</th>
                <th style="padding: 10px; border-bottom: 2px solid #ddd; text-align: right;" class="no-print">إجراء</th>
            </tr>
        </thead>
        <tbody>
            @foreach($withdrawals ?? [] as $w)
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ number_format($w->amount, 0) }} ل.س</td>
                <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $w->method ?? 'شحن رصيد' }}</td>
                <td style="padding: 10px; border-bottom: 1px solid #eee;">{{ $w->created_at->format('Y-m-d H:i') }}</td>
                <td style="padding: 10px; border-bottom: 1px solid #eee;">
                    <span style="color: {{ $w->status == 'completed' ? 'green' : 'orange' }}; font-weight: bold;">
                        {{ $w->status == 'completed' ? 'مكتمل' : 'قيد المعالجة' }}
                    </span>
                </td>
                <td style="padding: 10px; border-bottom: 1px solid #eee;" class="no-print">
                    <button onclick="window.print()" style="padding: 5px 10px; background: #607d8b; color: white; border: none; border-radius: 4px; cursor: pointer;">طباعة</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection