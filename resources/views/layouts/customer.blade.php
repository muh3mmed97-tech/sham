<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>منصة شام - لوحة العميل</title>
    <style>
        body { font-family: sans-serif; margin: 0; padding: 0; }
        nav { background: #333; color: #fff; padding: 15px; text-align: center; }
        .container { padding: 20px; }
    </style>
</head>
<body>

    <nav>
        مرحباً بك في متجر شام - لوحة التحكم
    </nav>

    <div class="container">
        @yield('content')
    </div>

</body>
</html>