<?php

namespace App\Http\Controllers;

use App\Models\Dispute;
use App\Models\Order;
use App\Models\Wallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DisputeController extends Controller
{
    // تخزين طلب إرجاع / نزاع جديد من العميل مع إرفاق الإثبات (صورة/فيديو)
    public function store(Request $request, $orderId)
    {
        $request->validate([
            'reason'     => 'required|string|max:1000',
            'proof_file' => 'nullable|file|mimes:jpg,jpeg,png,mp4,mov,avi|max:20480', // يدعم الصور والفيديوهات حتى 20 ميجابايت
        ]);

        $order = Order::where('id', $orderId)
                      ->where('customer_id', Auth::id())
                      ->firstOrFail();

        // التأكد من عدم وجود نزاع مسبق لنفس الطلب
        $existingDispute = Dispute::where('order_id', $order->id)->first();
        if ($existingDispute) {
            return redirect()->back()->with('error', 'يوجد طلب إرجاع أو نزاع مسجل مسبقاً لهذا الطلب.');
        }

        $filePath = null;
        if ($request->hasFile('proof_file')) {
            $filePath = $request->file('proof_file')->store('disputes_proofs', 'public');
        }

        Dispute::create([
            'order_id'   => $order->id,
            'user_id'    => Auth::id(),
            'vendor_id'  => $order->vendor_id,
            'reason'     => $request->reason,
            'proof_file' => $filePath,
            'status'     => 'pending',
        ]);

        return redirect()->back()->with('success', 'تم تقديم طلب الإرجاع/النزاع مع مرفق الإثبات بنجاح، سيتم مراجعته من قبل الإدارة.');
    }

    // عرض قائمة النزاعات في لوحة تحكم المدير
    public function adminIndex()
    {
        $disputes = Dispute::with(['order.product', 'user', 'vendor'])->latest()->get();
        return view('admin.disputes', compact('disputes'));
    }

    // تحديث حالة النزاع والرد عليه من قبل الإدارة
    public function adminUpdate(Request $request, $id)
    {
        $request->validate([
            'status'         => 'required|in:resolved,rejected',
            'admin_response' => 'nullable|string|max:1000',
        ]);

        $dispute = Dispute::with('order')->findOrFail($id);
        $dispute->update([
            'status'         => $request->status,
            'admin_response' => $request->admin_response,
        ]);

        // إذا تم حل النزاع بالموافقة على الإرجاع، يتم إعادة المبلغ لمحفظة العميل
        if ($request->status === 'resolved' && $dispute->order) {
            $order = $dispute->order;
            $wallet = Wallet::firstOrCreate(['user_id' => $order->customer_id]);
            $wallet->balance += $order->total_price;
            $wallet->save();

            // تحديث حالة الطلب إلى ملغي/مسترجع
            $order->update(['status' => 'cancelled']);
        }

        return redirect()->back()->with('success', 'تم تحديث حالة النزاع ومعالجته بنجاح.');
    }
}