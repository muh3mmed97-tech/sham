<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallet;
use App\Models\Withdrawal; // تأكد من إنشاء هذا الموديل لاحقاً

class WalletController extends Controller
{
    // دالة عرض الصفحة مع سجل العمليات
    public function index()
    {
        $wallet = Wallet::firstOrCreate(['user_id' => Auth::id()], ['balance' => 0]);
        // جلب سجل السحوبات الخاصة بهذا التاجر
        $withdrawals = Withdrawal::where('user_id', Auth::id())->latest()->get();
        
        return view('wallet.index', compact('wallet', 'withdrawals'));
    }

    // دالة شحن الرصيد
    public function charge(Request $request)
    {
        $request->validate(['amount' => 'required|numeric|min:1']);
        $wallet = Wallet::firstOrCreate(['user_id' => Auth::id()], ['balance' => 0]);
        
        $wallet->balance += $request->amount;
        $wallet->save();
        
        return back()->with('success', 'تم شحن رصيدك بنجاح!');
    }

    // دالة سحب الرصيد (معدلة لحفظ السجل)
    public function withdraw(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'method' => 'required|string'
        ]);

        $user = Auth::user();
        $wallet = Wallet::where('user_id', $user->id)->first();

        if (!$wallet || $wallet->balance < $request->amount) {
            return back()->with('error', 'عذراً، رصيدك غير كافٍ.');
        }

        // خصم الرصيد
        $wallet->balance -= $request->amount;
        $wallet->save();

        // حفظ طلب السحب في سجل السحوبات
        Withdrawal::create([
            'user_id' => $user->id,
            'amount' => $request->amount,
            'method' => $request->method,
            'status' => 'pending'
        ]);
        
        return back()->with('success', 'تم تقديم طلب السحب بنجاح. سيتم مراجعته قريباً.');
    }
}