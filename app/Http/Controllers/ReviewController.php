<?php

namespace App\Http\Controllers;

use App\Models\Review;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    public function store(Request $request, $productId)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
            'order_id' => 'required|exists:orders,id',
        ]);

        // التأكد من أن العميل اشترى هذا المنتج وأن الطلب تم تسليمه
        $order = Order::where('id', $request->order_id)
                      ->where('customer_id', Auth::id())
                      ->where('product_id', $productId)
                      ->where('status', 'delivered')
                      ->first();

        if (!$order) {
            return back()->with('error', 'لا يمكنك تقييم هذا المنتج إلا بعد استلامه.');
        }

        Review::create([
            'user_id' => Auth::id(),
            'product_id' => $productId,
            'order_id' => $request->order_id,
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return back()->with('success', 'شكراً لتقييمك!');
    }
}