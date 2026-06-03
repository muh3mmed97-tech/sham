<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        // إذا كان المستخدم غير مسجل دخول
        if (!$request->user()) {
            return redirect('/login')->with('error', 'يجب عليك تسجيل الدخول أولاً');
        }

        // إذا كان مسجل دخول ولكن دوره لا يطابق الدور المطلوب
        if ($request->user()->role !== $role) {
            return redirect('/')->with('error', 'ليس لديك صلاحية الوصول لهذه الصفحة');
        }

        return $next($request);
    }
}