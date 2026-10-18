<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Wallet;
use App\Models\Order; 
use App\Models\User;
use App\Notifications\NewOrderNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class CartController extends Controller
{
    // عرض السلة
    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        return view('cart.index', compact('cartItems'));
    }

    // إضافة منتج للسلة مع فحص المخزون
    public function addToCart(Request $request, $productId)
    {
        $userId = Auth::id();
        if (!$userId) return redirect()->route('login');

        $product = Product::findOrFail($productId);

        if (($product->stock ?? 0) <= 0) {
            return back()->with('error', 'عذراً، هذا المنتج نفد من المخزون حالياً.');
        }

        $cartItem = Cart::where('user_id', $userId)->where('product_id', $productId)->first();
        $currentQtyInCart = $cartItem ? $cartItem->quantity : 0;

        if (($currentQtyInCart + 1) > $product->stock) {
            return back()->with('error', 'عذراً، الكمية المطلوبة تتجاوز المخزون المتوفر (' . $product->stock . ' قطعة فقط).');
        }

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

    // تحديث كمية منتج في السلة مع التحقق من المخزون
    public function update(Request $request, $id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $cartItem = Cart::where('user_id', Auth::id())->where('id', $id)->with('product')->first();
        
        if (!$cartItem || !$cartItem->product) {
            return back()->with('error', 'المنتج غير موجود في السلة');
        }

        if ($request->quantity > $cartItem->product->stock) {
            return back()->with('error', 'عذراً، الكمية المتوفرة في المخزون هي ' . $cartItem->product->stock . ' قطعة فقط.');
        }

        $cartItem->quantity = $request->quantity;
        $cartItem->save();

        return back()->with('success', 'تم تحديث الكمية بنجاح');
    }

    // تطبيق كوبون الخصم
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string',
        ]);

        $coupon = \App\Models\Coupon::where('code', strtoupper(trim($request->coupon_code)))->first();

        if (!$coupon) {
            return redirect()->back()->with('error', 'كود الخصم غير صحيح أو غير موجود.');
        }

        if ($coupon->expires_at && $coupon->expires_at->isPast()) {
            return redirect()->back()->with('error', 'عذراً، انتهت صلاحية هذا الكوبون.');
        }

        if ($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit) {
            return redirect()->back()->with('error', 'عذراً، تجاوز هذا الكوبون الحد الأقصى للاستخدام.');
        }

        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        $subtotal = $cartItems->sum(function($item) {
            return $item->product ? ($item->product->price * $item->quantity) : 0;
        });

        if ($subtotal < $coupon->min_spend) {
            return redirect()->back()->with('error', 'الحد الأدنى لطلب هذا الكوبون هو ' . number_format($coupon->min_spend, 0) . ' ل.س');
        }

        $discount = 0;
        if ($coupon->type === 'percent') {
            $discount = ($subtotal * $coupon->value) / 100;
        } else {
            $discount = min($coupon->value, $subtotal);
        }

        session()->put('applied_coupon', [
            'code' => $coupon->code,
            'discount' => $discount,
        ]);

        return redirect()->back()->with('success', 'تم تطبيق كود الخصم بنجاح بمقدار: ' . number_format($discount, 0) . ' ل.س');
    }

    // إتمام الطلب مع حماية المخزون وتطبيق الكوبون
    public function checkout(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:wallet,cod,card',
        ]);

        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)->with('product.store.user')->get();
        
        if ($cartItems->isEmpty()) {
            return back()->with('error', 'السلة فارغة.');
        }

        foreach ($cartItems as $item) {
            if (!$item->product) {
                return back()->with('error', 'أحد المنتجات في السلة لم يعد متوفراً.');
            }
            if ($item->quantity > $item->product->stock) {
                return back()->with('error', 'المنتج "' . $item->product->name . '" لا يتوفر منه سوى ' . $item->product->stock . ' قطعة في المخزون.');
            }
        }

        // حساب المجموع الفرعي الصحيح
        $subtotal = $cartItems->sum(function($item) {
            return $item->product ? ($item->product->price * $item->quantity) : 0;
        });

        // خصم الكوبون إن وجد
        $couponData = session()->get('applied_coupon');
        $discountAmount = 0;
        $couponCode = null;

        if ($couponData) {
            $discountAmount = $couponData['discount'];
            $couponCode = $couponData['code'];
        }

        // الإجمالي النهائي بعد الخصم
        $total = max(0, $subtotal - $discountAmount);

        $wallet = Wallet::firstOrCreate(['user_id' => $user->id]);

        if ($request->payment_method == 'wallet' && $wallet->balance < $total) {
            return back()->with('error', 'رصيدك غير كافٍ لإتمام عملية الشراء. يرجى شحن المحفظة.');
        }

        return DB::transaction(function () use ($user, $cartItems, $total, $subtotal, $discountAmount, $couponCode, $wallet, $request) {
            
            if ($request->payment_method == 'wallet') {
                $wallet->balance -= $total;
                $wallet->save();
            }

            $lastOrder = null;
            foreach ($cartItems as $item) {
                if (!$item->product) continue;

                $vendorId = $item->product->store->user_id 
                        ?? $item->product->store->user->id 
                        ?? $item->product->vendor_id 
                        ?? 1;

                $itemPrice = $item->product->price;
                $itemTotal = $itemPrice * $item->quantity;

                // حساب حصة هذا المنتج من الخصم الكلي إن وجد
                $itemDiscount = 0;
                if ($subtotal > 0 && $discountAmount > 0) {
                    $itemDiscount = ($itemTotal / $subtotal) * $discountAmount;
                }
                $itemFinalTotal = max(0, $itemTotal - $itemDiscount);

                $order = Order::create([
                    'vendor_id'      => $vendorId,
                    'customer_id'    => $user->id,
                    'product_id'     => $item->product_id,
                    'product_name'   => $item->product->name,
                    'price'          => $itemPrice,
                    'quantity'       => $item->quantity,
                    'total_price'    => $itemFinalTotal, // حفظ السعر النهائي الصافي بعد الخصم
                    'coupon_code'    => $couponCode,
                    'discount_amount'=> $itemDiscount, // حفظ قيمة الخصم الخاصة بهذا السطر
                    'payment_method' => $request->payment_method,
                    'status'         => 'pending'
                ]);

                $lastOrder = $order;

                // إنقاص المخزون
                $item->product->decrement('stock', $item->quantity);

                // إشعار التاجر
                $vendor = User::find($vendorId);
                if ($vendor) {
                    $vendor->notify(new NewOrderNotification($order));
                }
            }

            // تحديث عدد مرات استخدام الكوبون
            if ($couponCode) {
                $couponRecord = \App\Models\Coupon::where('code', $couponCode)->first();
                if ($couponRecord) {
                    $couponRecord->increment('used_count');
                }
            }

            // تفريغ السلة وحذف الكوبون من الجلسة
            Cart::where('user_id', $user->id)->delete();
            session()->forget('applied_coupon');

            $successMessage = 'تمت عملية الشراء بنجاح!';

            if ($lastOrder) {
                return redirect()->route('customer.invoice', $lastOrder->id)
                                 ->with('success', $successMessage);
            }

            return redirect()->route('customer.orders')->with('success', 'تم تسجيل طلبك بنجاح!');
        });
    }
}