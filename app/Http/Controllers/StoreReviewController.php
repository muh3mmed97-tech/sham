<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StoreReview;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class StoreReviewController extends Controller
{
    // حفظ تقييم جديد للمتجر
    public function store(Request $request, $storeId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        StoreReview::create([
            'store_id' => $storeId,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        // إرسال إشعار لصاحب المتجر
        $vendorUser = User::find($storeId);
        if ($vendorUser) {
            $vendorUser->notify(new \App\Notifications\StoreReviewNotification(Auth::user()->name, $request->rating));
        }

        return redirect()->back()->with('success', 'شكراً لك! تم إضافة تقييمك بنجاح.');
    }
}