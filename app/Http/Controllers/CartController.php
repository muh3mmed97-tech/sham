<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Wallet;
use App\Models\Order; 
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail; // تأكد من وجود هذا السطر

class CartController extends Controller
{
    // عرض السلة
    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        return view('cart.index', compact('cartItems'));
    }

    // إضافة منتج للسلة
    public function addToCart(Request $request, $productId)
    {
        $userId = Auth::id();
        if (!$userId) return redirect()->route('login');

        $cartItem = Cart::where('user_id', $userId)->where('product_id', $productId)->first();

        if ($cartItem) {
            $cartItem->quantity += 1;
            $cartItem->save();
        } else {
            Cart::create(['user_id' => $userId, 'product_id' => $productId, 'quantity' => 1]);
        }
        return back()->with('success', 'تمت إضافة المنتج للسلة!');
    }

    // حذف منتج من السلة
    public function remove($id)
    {
        $cartItem = Cart::where('user_id', Auth::id())->where('id', $id)->first();
        
        if ($cartItem) {
            $cartItem->delete();
            return back()->with('success', 'تم حذف المنتج من السلة');
        }
        return back()->with('error', 'المنتج غير موجود في السلة');
    }

    // تحديث كمية منتج في السلة
    public function update(Request $request, $id)
    {
        $cartItem = Cart::where('user_id', Auth::id())->where('id', $id)->first();
        
        if ($cartItem && $request->quantity > 0) {
            $cartItem->quantity = $request->quantity;
            $cartItem->save();
            return back()->with('success', 'تم تحديث الكمية بنجاح');
        }
        return back()->with('error', 'حدث خطأ أثناء تحديث الكمية');
    }

    // إتمام الطلب
    public function checkout()
    {
        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)->with('product.store.user')->get();
        
        if ($cartItems->isEmpty()) {
            return back()->with('error', 'السلة فارغة.');
        }

        $total = $cartItems->sum(fn($i) => $i->product->price * $i->quantity);
        $wallet = $user->wallet;

        if ($wallet && $wallet->balance >= $total) {
            
            return DB::transaction(function () use ($user, $cartItems, $total, $wallet) {
                $wallet->balance -= $total;
                $wallet->save();

                $lastOrder = null;

                foreach ($cartItems as $item) {
                    $vendor = $item->product->store->user;
                    
                    $lastOrder = Order::create([
                        'vendor_id' => $vendor->id,
                        'customer_id' => $user->id,
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name,
                        'price' => $item->product->price,
                        'quantity' => $item->quantity,
                        'total_price' => $item->product->price * $item->quantity,
                        'status' => 'pending'
                    ]);
                }

                Cart::where('user_id', $user->id)->delete();

                // إرسال الفاتورة عبر البريد الإلكتروني
                Mail::send('emails.invoice', ['order' => $lastOrder], function($message) use ($user, $lastOrder) {
                    $message->to($user->email)
                            ->subject('فاتورة طلبك من منصة شام #' . $lastOrder->id);
                });

                // التوجيه إلى الفاتورة
                return redirect()->route('customer.invoice', $lastOrder->id)
                                 ->with('success', 'تمت عملية الشراء بنجاح وتم إرسال نسخة لبريدك!');
            });
        }

        return back()->with('error', 'رصيدك غير كافٍ لإتمام عملية الشراء.');
    }
}