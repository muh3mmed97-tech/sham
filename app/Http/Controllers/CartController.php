<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Wallet;
use App\Models\Order; 
use App\Models\User;
use App\Notifications\NewOrderNotification; // استيراد كلاس إشعار التاجر
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
    public function checkout(Request $request)
    {
        // التحقق من صحة طريقة الدفع المدخلة
        $request->validate([
            'payment_method' => 'required|in:wallet,cod',
        ]);

        $user = Auth::user();
        $cartItems = Cart::where('user_id', $user->id)->with('product.store.user')->get();
        
        if ($cartItems->isEmpty()) {
            return back()->with('error', 'السلة فارغة.');
        }

        // حساب الإجمالي
        $total = $cartItems->sum(function($item) {
            return $item->product ? ($item->product->price * $item->quantity) : 0;
        });

        $wallet = Wallet::firstOrCreate(['user_id' => $user->id]);

        // التحقق من الرصيد فقط في حال اختيار الدفع عبر المحفظة
        if ($request->payment_method == 'wallet' && $wallet->balance < $total) {
            return back()->with('error', 'رصيدك غير كافٍ لإتمام عملية الشراء. يرجى شحن المحفظة أو اختيار الدفع عند الاستلام.');
        }

        return DB::transaction(function () use ($user, $cartItems, $total, $wallet, $request) {
            
            // خصم المبلغ من المحفظة فقط عند اختيار الدفع عبر المحفظة
            if ($request->payment_method == 'wallet') {
                $wallet->balance -= $total;
                $wallet->save();
            }

            $lastOrder = null;

            foreach ($cartItems as $item) {
                if (!$item->product) continue;

                // جلب معرف التاجر بأمان
                $vendorId = $item->product->store->user_id 
                        ?? $item->product->store->user->id 
                        ?? $item->product->vendor_id 
                        ?? 1;

                $order = Order::create([
                    'vendor_id'      => $vendorId,
                    'customer_id'    => $user->id,
                    'product_id'     => $item->product_id,
                    'product_name'   => $item->product->name,
                    'price'          => $item->product->price,
                    'quantity'       => $item->quantity,
                    'total_price'    => $item->product->price * $item->quantity,
                    'payment_method' => $request->payment_method, // حفظ طريقة الدفع (wallet أو cod)
                    'status'         => 'pending'
                ]);

                $lastOrder = $order;

                // إنقاص المخزون
                $item->product->decrement('stock', $item->quantity);

                // إرسال إشعار فوري للتاجر بوجود طلب جديد
                $vendor = User::find($vendorId);
                if ($vendor) {
                    $vendor->notify(new NewOrderNotification($order));
                }
            }

            // تفريغ السلة بعد إتمام الطلب بنجاح
            Cart::where('user_id', $user->id)->delete();

            // محاولة إرسال البريد الإلكتروني دون توقف النظام في حال البيئة المحلية
            try {
                if (view()->exists('emails.invoice') && $lastOrder) {
                    Mail::send('emails.invoice', ['order' => $lastOrder], function($message) use ($user, $lastOrder) {
                        $message->to($user->email)
                                ->subject('فاتورة طلبك من فُرات ستور #' . $lastOrder->id);
                    });
                }
            } catch (\Exception $e) {
                // تجاوز الخطأ محلياً
            }

            $successMessage = $request->payment_method == 'wallet' 
                ? 'تمت عملية الشراء بنجاح وخصم المبلغ من محفظتك!' 
                : 'تم تقديم طلبك بنجاح وسيتم الدفع عند الاستلام!';

            if ($lastOrder) {
                return redirect()->route('customer.invoice', $lastOrder->id)
                                 ->with('success', $successMessage);
            }

            return redirect()->route('customer.orders')->with('success', 'تم تسجيل طلباتك بنجاح!');
        });
    }
}