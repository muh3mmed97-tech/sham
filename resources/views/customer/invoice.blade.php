@extends('layouts.app')

@section('content')

<div id="printable-area" style="max-width: 800px; margin: 40px auto; background: white; padding: 50px; border-radius: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); border: 1px solid #eee;">
    
    <div style="display: flex; justify-content: space-between; align-items: start; border-bottom: 3px solid #005a9c; padding-bottom: 20px; margin-bottom: 30px;">
        <div style="text-align: right;">
            <h1 style="color: #005a9c; margin: 0;">{{ $order->product->store->name ?? 'منصة شام' }}</h1>
            <p style="margin: 5px 0; color: #555;">📍 العنوان: {{ $order->product->store->address ?? 'غير محدد' }}</p>
            <p style="margin: 0; color: #555;">📞 هاتف: {{ $order->product->store->phone ?? 'غير متوفر' }}</p>
        </div>

        <div style="text-align: left;">
            <h2 style="margin: 0; color: #333;">فاتورة طلب</h2>
            <p style="margin: 5px 0; color: #555;">رقم الطلب: #{{ $order->id }}</p>
            <p style="margin: 0; color: #555;">التاريخ: {{ $order->created_at->format('Y-m-d') }}</p>
        </div>
    </div>

    <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
        <thead>
            <tr style="background: #f8f9fa; text-align: right;">
                <th style="padding: 15px; border-bottom: 1px solid #ddd;">المنتج</th>
                <th style="padding: 15px; border-bottom: 1px solid #ddd;">الكمية</th>
                <th style="padding: 15px; border-bottom: 1px solid #ddd;">السعر الفردي</th>
                <th style="padding: 15px; border-bottom: 1px solid #ddd;">الإجمالي</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="padding: 15px; border-bottom: 1px solid #eee;">{{ $order->product_name ?? ($order->product->name ?? 'غير معروف') }}</td>
                <td style="padding: 15px; border-bottom: 1px solid #eee;">{{ $order->quantity }}</td>
                <td style="padding: 15px; border-bottom: 1px solid #eee;">{{ number_format($order->price ?? 0, 0) }} ل.س</td>
                <td style="padding: 15px; border-bottom: 1px solid #eee;">{{ number_format($order->total_price ?? 0, 0) }} ل.س</td>
            </tr>
        </tbody>
    </table>

    <div style="text-align: left; border-top: 2px solid #005a9c; padding-top: 20px;">
        <h3 style="margin: 0; color: #333;">المجموع الكلي: <span style="color: #e65100;">{{ number_format($order->total_price ?? 0, 0) }} ل.س</span></h3>
    </div>
</div>

<div style="margin-top: 20px; text-align: center;">
    <button onclick="printInvoice()" style="padding: 12px 30px; background: #005a9c; color: white; border: none; border-radius: 10px; cursor: pointer; font-size: 1rem;">
        طباعة الفاتورة 🖨️
    </button>
    <a href="{{ route('customer.orders') }}" style="display: block; margin-top: 15px; color: #005a9c; text-decoration: none;">العودة لطلباتي</a>
</div>

<script>
function printInvoice() {
    var printContents = document.getElementById('printable-area').innerHTML;
    var printWindow = window.open('', '_blank');
    printWindow.document.write('<html><head><title>فاتورة - {{ $order->product->store->name ?? "منصة شام" }}</title>');
    printWindow.document.write('<style>body{font-family: sans-serif; padding: 40px;} table{width:100%; border-collapse:collapse; margin-top:20px;} th,td{border:1px solid #ccc; padding:12px; text-align:right;} h1{color:#005a9c; margin:0;} .store-info{margin-bottom:20px;}</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write(printContents);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.focus();
    printWindow.print();
    printWindow.close();
}
</script>

@endsection