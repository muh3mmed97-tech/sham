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
        $request->validate(['amount' => 'required|numeric|min:1']);
        
        $wallet = Wallet::firstOrCreate(['user_id' => Auth::id()], ['balance' => 0]);
        $wallet->balance += $request->amount;
        $wallet->save();
        
        // تحديث علاقة المحفظة في الكائن الحالي للمستخدم ليعرف الـ Navbar بالتغيير فوراً
        Auth::user()->setRelation('wallet', $wallet);
        
        return back()->with('success', 'تم شحن رصيدك بنجاح!');
    }

    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'method' => 'required|string'
        ]);

        $wallet = Wallet::where('user_id', Auth::id())->first();

        if (!$wallet || $wallet->balance < $request->amount) {
            return back()->with('error', 'عذراً، رصيدك غير كافٍ.');
        }

        $wallet->balance -= $request->amount;
        $wallet->save();

        Withdrawal::create([
            'user_id' => Auth::id(),
            'amount' => $request->amount,
            'method' => $request->method,
            'status' => 'pending'
        ]);

        // تحديث العلاقة بعد الخصم
        Auth::user()->setRelation('wallet', $wallet);
        
        return back()->with('success', 'تم تقديم طلب السحب بنجاح.');
    }
}