<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallet;
use App\Models\Withdrawal;

class WalletController extends Controller
{
    public function index()
    {
        // جلب المحفظة للمستخدم الحالي
        $wallet = Wallet::firstOrCreate(['user_id' => Auth::id()], ['balance' => 0]);
        $withdrawals = Withdrawal::where('user_id', Auth::id())->latest()->get();
        
        return view('wallet.index', compact('wallet', 'withdrawals'));
    }

    public function charge(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000',
            'method' => 'required|string',
            'details' => 'required|string|max:255', // التحقق من إدخال تفاصيل الإيصال، البطاقة، أو الحوالة
        ]);
        
        $wallet = Wallet::firstOrCreate(['user_id' => Auth::id()], ['balance' => 0]);
        $wallet->balance += $request->amount;
        $wallet->save();
        
        // تحديث علاقة المحفظة في الكائن الحالي للمستخدم ليعرف الـ Navbar بالتغيير فوراً
        Auth::user()->setRelation('wallet', $wallet);
        
        return back()->with('success', 'تم تقديم طلب شحن الرصيد بنجاح وسيتم اعتماده بعد التحقق من البيانات!');
    }

    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1000',
            'method' => 'required|in:sham_cash,bank_transfer',
            'details' => 'nullable|string|max:255', // لحفظ رقم هاتف شام كاش إن وجد
            'account_name' => 'nullable|required_if:method,bank_transfer|string|max:255',
            'bank_name' => 'nullable|required_if:method,bank_transfer|string|max:255',
            'iban' => 'nullable|required_if:method,bank_transfer|string|max:255',
        ]);

        $wallet = Wallet::where('user_id', Auth::id())->first();

        if (!$wallet || $wallet->balance < $request->amount) {
            return back()->with('error', 'عذراً، رصيدك غير كافٍ لإتمام عملية السحب.');
        }

        // بناء نص التفاصيل بناءً على طريقة السحب المختارة
        if ($request->method == 'sham_cash') {
            $detailsText = 'شام كاش - هاتف: ' . $request->details;
        } else {
            $detailsText = 'الاسم الثلاثي: ' . $request->account_name . ' | البنك: ' . $request->bank_name . ' | IBAN: ' . $request->iban;
        }

        $wallet->balance -= $request->amount;
        $wallet->save();

        Withdrawal::create([
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'method' => $request->method,
            'details' => $detailsText, // حفظ التفاصيل المفصولة بشكل مرتب
            'status' => 'pending'
        ]);

        // تحديث العلاقة بعد الخصم
        Auth::user()->setRelation('wallet', $wallet);
        
        return back()->with('success', 'تم تقديم طلب السحب بنجاح وسيتم معالجته قريباً.');
    }
}