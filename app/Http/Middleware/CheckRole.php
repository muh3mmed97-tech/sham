<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. التحقق مما إذا كان المستخدم مسجلاً
        if (!Auth::check()) {
            // نستخدم route('login') بدلاً من المسار النصي
            // هذا سيعمل فقط إذا قمت بتعريف المسار في web.php بـ name('login')
            return redirect()->route('login');
        }

        // 2. التحقق من الدور (Role)
        // قمنا بالإبقاء على dd() هنا لكي نعرف فوراً إذا كان هناك اختلاف في الأدوار
        $userRole = $request->user()->role;
        
        if ($userRole !== $role) {
            dd(
                "مشكلة: الصلاحية لا تطابق.",
                "الدور المطلوب للمسار هو: " . $role,
                "الدور الحالي للمستخدم في قاعدة البيانات هو: " . ($userRole ?? 'null (فارغ)')
            );
        }

        return $next($request);
    }
}