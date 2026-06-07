<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wallet;

class WalletController extends Controller
{
    // دالة عرض الصفحة
    public function index()
    {
        $wallet = Wallet::firstOrCreate(['user_id' => Auth::id()], ['balance' => 0]);
        return view('wallet.index', compact('wallet'));
    }

    // دالة شحن الرصيد
    public function charge(Request $request)
    {
        $request->validate(['amount' => 'required|numeric|min:1']);
        $user = Auth::user();
        
        $wallet = Wallet::firstOrCreate(
            ['user_id' => $user->id],
            ['balance' => 0]
        );
        
        $wallet->balance += $request->amount;
        $wallet->save();
        
        return back()->with('success', 'تم شحن رصيدك بنجاح!');
    }

    // دالة سحب الرصيد
    public function withdraw(Request $request)
    {
        $request->validate(['amount' => 'required|numeric|min:1']);
        $user = Auth::user();
        
        $wallet = Wallet::where('user_id', $user->id)->first();

        if (!$wallet || $wallet->balance < $request->amount) {
            return back()->with('error', 'عذراً، رصيدك غير كافٍ أو لا تملك محفظة حالياً.');
        }

        $wallet->balance -= $request->amount;
        $wallet->save();
        
        return back()->with('success', 'تم تقديم طلب السحب بنجاح.');
    }
}