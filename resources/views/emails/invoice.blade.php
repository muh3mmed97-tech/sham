<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
</head>
<body style="font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 10px; border-top: 5px solid #005a9c;">
        <h1 style="color: #005a9c;">منصة شام</h1>
        <p>مرحباً <strong>{{ $order->customer->name ?? 'عميلنا العزيز' }}</strong>،</p>
        <p>شكراً لثقتك بنا. لقد تم تأكيد طلبك بنجاح، وإليك تفاصيل فاتورتك:</p>

        <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
            <thead>
                <tr style="background-color: #005a9c; color: #ffffff;">
                    <th style="padding: 10px; text-align: right;">المنتج</th>
                    <th style="padding: 10px; text-align: center;">الكمية</th>
                    <th style="padding: 10px; text-align: center;">السعر</th>
                </tr>
            </thead>
            <tbody>
                <tr style="border-bottom: 1px solid #ddd;">
                    <td style="padding: 10px;">{{ $order->product_name }}</td>
                    <td style="padding: 10px; text-align: center;">{{ $order->quantity }}</td>
                    <td style="padding: 10px; text-align: center;">{{ number_format($order->price, 0) }} ل.س</td>
                </tr>
            </tbody>
        </table>

        <div style="text-align: left; font-size: 1.2rem; font-weight: bold; color: #333;">
            الإجمالي: {{ number_format($order->total_price, 0) }} ل.س
        </div>

        <p style="margin-top: 30px; color: #777;">شكراً لاختيارك منصة شام، نتمنى لك تجربة تسوق رائعة.</p>
    </div>
</body>
</html>