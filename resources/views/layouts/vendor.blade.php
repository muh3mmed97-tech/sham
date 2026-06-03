<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>لوحة تحكم شام</title>
</head>
<body>
    <nav>
        <ul>
            <li><a href="{{ route('vendor.dashboard') }}">الرئيسية</a></li>
        </ul>
    </nav>

    <main>
        @yield('content')
    </main>
</body>
</html>