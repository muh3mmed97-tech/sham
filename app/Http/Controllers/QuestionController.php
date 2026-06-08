<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QuestionController extends Controller
{
    // إضافة سؤال من العميل
    public function store(Request $request, $productId)
    {
        $request->validate(['content' => 'required|string']);
        
        Question::create([
            'product_id' => $productId,
            'user_id' => Auth::id(),
            'content' => $request->content
        ]);
        
        return back()->with('success', 'تم إرسال سؤالك بنجاح!');
    }

    // رد التاجر على السؤال مع التحقق من الصلاحية
    public function answer(Request $request, $id)
    {
        $request->validate(['answer' => 'required|string']);

        // 1. جلب السؤال مع علاقة المنتج بالمتجر
        $question = Question::with('product.store')->findOrFail($id);

        // 2. التحقق: هل المستخدم الحالي هو التاجر صاحب هذا المتجر؟
        if ($question->product->store->user_id !== Auth::id()) {
            return back()->with('error', 'ليس لديك صلاحية للرد على هذا السؤال.');
        }

        // 3. تحديث الرد
        $question->update(['answer' => $request->answer]);
        
        return back()->with('success', 'تم الرد على السؤال!');
    }
}