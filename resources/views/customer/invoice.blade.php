@extends('layouts.app')

@section('content')

<div id="printable-area" style="max-width: 800px; margin: 40px auto; background: white; padding: 40px; border-radius: 20px; box-shadow: 0 5px 20px rgba(0,0,0,0.05); border: 1px solid #E5E7EB; font-family: 'Cairo', sans-serif;" dir="rtl">
    
    <!-- هيدر الفاتورة والشعار -->
    <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 3px solid #0B1B3D; padding-bottom: 20px; margin-bottom: 25px;">
        <div style="display: flex; align-items: center; gap: 15px;">
            <img src="{{ asset('images/logo.png') }}" alt="فُرات ستور" style="max-height: 70px; width: auto; border-radius: 8px; border: 1px solid #D4AF37; object-fit: cover;">
            <div>
                <h1 style="color: #0B1B3D; margin: 0; font-size: 1.5rem;">فُرات ستور</h1>
                <p style="margin: 3px 0 0 0; color: #64748B; font-size: 0.85rem;">تجارتنا عهد، وثقتكم أمانة</p>
            </div>
        </div>

        <div style="text-align: left;">
            <h2 style="margin: 0; color: #0B1B3D; font-size: 1.3rem;">فاتورة طلب</h2>
            <p style="margin: 4px 0 0 0; color: #64748B; font-size: 0.85rem;">رقم الطلب: #{{ $order->id }}</p>
            <p style="margin: 2px 0 0 0; color: #64748B; font-size: 0.85rem;">التاريخ: {{ $order->created_at->format('Y-m-d H:i') }}</p>
        </div>
    </div>

    <!-- معلومات المتجر ومعلومات المشتري -->
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 30px;">
        
        <!-- تفاصيل المتجر -->
        <div style="background: #F6F8FB; padding: 15px; border-radius: 12px; border: 1px solid #E5E7EB;">
            <h3 style="color: #0B1B3D; margin-top: 0; margin-bottom: 10px; font-size: 1rem; border-bottom: 2px solid #D4AF37; padding-bottom: 5px;">🏪 معلومات المتجر:</h3>
            <p style="margin: 4px 0; color: #111827; font-size: 0.9rem;"><strong>اسم المتجر:</strong> {{ $order->product->store->name ?? 'متجر فُرات الرئيسي' }}</p>
            <p style="margin: 4px 0; color: #64748B; font-size: 0.9rem;"><strong>📍 العنوان:</strong> {{ $order->product->store->address ?? 'غير محدد' }}</p>
            <p style="margin: 4px 0; color: #64748B; font-size: 0.9rem;"><strong>📞 الهاتف:</strong> {{ $order->product->store->phone ?? 'غير متوفر' }}</p>
        </div>

        <!-- تفاصيل المشتري -->
        <div style="background: #F6F8FB; padding: 15px; border-radius: 12px; border: 1px solid #E5E7EB;">
            <h3 style="color: #0B1B3D; margin-top: 0; margin-bottom: 10px; font-size: 1rem; border-bottom: 2px solid #1E6FB8; padding-bottom: 5px;">👤 معلومات المشتري:</h3>
            <p style="margin: 4px 0; color: #111827; font-size: 0.9rem;"><strong>اسم العميل:</strong> {{ $order->customer->name ?? (Auth::user()->name ?? 'غير متوفر') }}</p>
            <p style="margin: 4px 0; color: #64748B; font-size: 0.9rem;"><strong>📍 العنوان:</strong> {{ $order->customer->address ?? ($order->address ?? 'غير محدد') }}</p>
            <p style="margin: 4px 0; color: #64748B; font-size: 0.9rem;"><strong>📞 الهاتف:</strong> {{ $order->customer->phone ?? ($order->phone ?? 'غير متوفر') }}</p>
        </div>

    </div>

    <!-- جدول المنتجات -->
    <table style="width: 100%; border-collapse: collapse; margin-bottom: 30px;">
        <thead>
            <tr style="background: #0B1B3D; color: white; text-align: right;">
                <th style="padding: 12px 15px; border-top-right-radius: 8px; border-bottom-right-radius: 8px;">المنتج</th>
                <th style="padding: 12px 15px; text-align: center;">الكمية</th>
                <th style="padding: 12px 15px; text-align: center;">السعر الفردي</th>
                <th style="padding: 12px 15px; text-align: left; border-top-left-radius: 8px; border-bottom-left-radius: 8px;">الإجمالي</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td style="padding: 15px; border-bottom: 1px solid #E5E7EB; color: #111827; font-weight: bold;">{{ $order->product_name ?? ($order->product->name ?? 'غير معروف') }}</td>
                <td style="padding: 15px; border-bottom: 1px solid #E5E7EB; color: #111827; text-align: center;">{{ $order->quantity }}</td>
                <td style="padding: 15px; border-bottom: 1px solid #E5E7EB; color: #64748B; text-align: center;">{{ number_format($order->price ?? 0, 0) }} ل.س</td>
                <td style="padding: 15px; border-bottom: 1px solid #E5E7EB; color: #1E6FB8; font-weight: bold; text-align: left;">{{ number_format($order->total_price ?? 0, 0) }} ل.س</td>
            </tr>
        </tbody>
    </table>

    <!-- المجموع الكلي -->
    <div style="display: flex; justify-content: flex-end; border-top: 2px solid #0B1B3D; padding-top: 15px;">
        <div style="text-align: left;">
            <p style="margin: 0; color: #64748B; font-size: 0.9rem;">المجموع الكلي:</p>
            <h3 style="margin: 3px 0 0 0; color: #0B1B3D; font-size: 1.5rem;">{{ number_format($order->total_price ?? 0, 0) }} ل.س</h3>
        </div>
    </div>
</div>

<!-- أزرار التحكم -->
<div style="margin-top: 20px; text-align: center;">
    <button onclick="printInvoice()" style="padding: 12px 30px; background: #D4AF37; color: #0B1B3D; border: none; border-radius: 10px; cursor: pointer; font-size: 1rem; font-weight: bold; transition: opacity 0.3s;">
        طباعة الفاتورة 🖨️
    </button>
    <a href="{{ route('customer.orders') }}" style="display: block; margin-top: 12px; color: #1E6FB8; text-decoration: none; font-weight: bold;">العودة لطلباتي</a>
</div>

<script>
function printInvoice() {
    var printContents = document.getElementById('printable-area').innerHTML;
    var printWindow = window.open('', '_blank');
    printWindow.document.write('<html><head><title>فاتورة رقم #{{ $order->id }} - فُرات ستور</title>');
    printWindow.document.write('<style>');
    printWindow.document.write('body { font-family: "Cairo", sans-serif; direction: rtl; padding: 20px; background: #fff; color: #000; }');
    printWindow.document.write('table { width: 100%; border-collapse: collapse; margin-top: 20px; }');
    printWindow.document.write('th, td { border: 1px solid #E5E7EB; padding: 10px; text-align: right; }');
    printWindow.document.write('h1 { color: #0B1B3D; margin: 0; }');
    printWindow.document.write('img { max-height: 70px; width: auto; }');
    printWindow.document.write('</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write(printContents);
    printWindow.document.write('</body></html>');
    printWindow.document.close();
    printWindow.focus();
    
    // تأخير بسيط لضمان تحميل الصورة قبل أمر الطباعة
    setTimeout(function() {
        printWindow.print();
        printWindow.close();
    }, 250);
}
</script>

@endsection