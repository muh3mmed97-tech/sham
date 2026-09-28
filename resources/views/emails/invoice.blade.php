<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: Arial, sans-serif; background-color: #F6F8FB; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 10px; border-top: 5px solid #0B1B3D; border: 1px solid #E5E7EB;">
        
        <!-- هيدر البريد الإلكتروني مع الشعار -->
        <div style="text-align: center; margin-bottom: 20px; border-bottom: 2px solid #E5E7EB; padding-bottom: 15px;">
            <img src="{{ asset('images/logo.png') }}" alt="فُرات ستور" style="max-height: 70px; width: auto; border-radius: 6px;">
            <h1 style="color: #0B1B3D; margin: 10px 0 0 0; font-size: 1.5rem;">فُرات ستور</h1>
            <p style="color: #64748B; font-size: 0.8rem; margin: 2px 0 0 0;">تجارتنا عهد، وثقتكم أمانة</p>
        </div>

        <p style="color: #111827;">مرحباً <strong>{{ $order->customer->name ?? 'عميلنا العزيز' }}</strong>،</p>
        <p style="color: #4B5563;">شكراً لثقتك بنا. لقد تم تأكيد طلبك بنجاح، وإليك تفاصيل فاتورتك:</p>

        <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
            <thead>
                <tr style="background-color: #0B1B3D; color: #ffffff;">
                    <th style="padding: 12px; text-align: right; border-top-right-radius: 6px; border-bottom-right-radius: 6px;">المنتج</th>
                    <th style="padding: 12px; text-align: center;">الكمية</th>
                    <th style="padding: 12px; text-align: center; border-top-left-radius: 6px; border-bottom-left-radius: 6px;">السعر</th>
                </tr>
            </thead>
            <tbody>
                <tr style="border-bottom: 1px solid #E5E7EB;">
                    <td style="padding: 12px; color: #111827;">{{ $order->product_name }}</td>
                    <td style="padding: 12px; text-align: center; color: #111827;">{{ $order->quantity }}</td>
                    <td style="padding: 12px; text-align: center; color: #1E6FB8; font-weight: bold;">{{ number_format($order->price, 0) }} ل.س</td>
                </tr>
            </tbody>
        </table>

        <div style="text-align: left; font-size: 1.2rem; font-weight: bold; color: #0B1B3D; background: #F6F8FB; padding: 15px; border-radius: 8px;">
            الإجمالي: {{ number_format($order->total_price, 0) }} ل.س
        </div>

        <p style="margin-top: 30px; color: #64748B; text-align: center; font-size: 0.9rem;">شكراً لاختيارك <strong style="color: #D4AF37;">فُرات ستور</strong>، نتمنى لك تجربة تسوق رائعة.</p>
    </div>
</body>
</html>