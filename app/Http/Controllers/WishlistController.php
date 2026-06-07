<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    public function index()
    {
        $wishlistItems = Wishlist::where('user_id', Auth::id())->with('product')->get();
        return view('wishlist.index', compact('wishlistItems'));
    }

    public function toggle($productId)
    {
        $userId = Auth::id();
        $item = Wishlist::where('user_id', $userId)->where('product_id', $productId)->first();

        if ($item) {
            $item->delete();
            return back()->with('success', 'تمت إزالة المنتج من المفضلة');
        } else {
            Wishlist::create(['user_id' => $userId, 'product_id' => $productId]);
            return back()->with('success', 'تمت إضافة المنتج للمفضلة!');
        }
    }
}