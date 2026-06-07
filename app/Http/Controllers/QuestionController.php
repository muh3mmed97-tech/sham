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

    // رد التاجر على السؤال
    public function answer(Request $request, $id)
    {
        $question = Question::findOrFail($id);
        $question->update(['answer' => $request->answer]);
        return back()->with('success', 'تم الرد على السؤال!');
    }
}