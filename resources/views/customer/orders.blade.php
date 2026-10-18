@extends('layouts.app')

@section('content')
<div style="max-width: 1000px; margin: 20px auto; background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); border: 1px solid #E5E7EB; font-family: 'Cairo';" dir="rtl">
    <h2 style="color: #0B1B3D; margin-bottom: 20px; font-weight: 800;">📦 طلباتي</h2>
    
    @if(session('success'))
        <div style="background: #F0FDF4; color: #166534; border-right: 4px solid #16A34A; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-weight: bold; font-size: 0.9rem;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: #FEF2F2; color: #991B1B; border-right: 4px solid #DC2626; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-weight: bold; font-size: 0.9rem;">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    @if($orders->isEmpty())
        <p style="text-align: center; color: #64748B; padding: 30px;">ليس لديك أي طلبات حالياً.</p>
    @else
        <div style="overflow-x: auto;">
            <table style="width: 100%; border-collapse: collapse; text-align: right;">
                <thead>
                    <tr style="background: #F6F8FB; color: #0B1B3D; font-size: 0.9rem;">
                        <th style="padding: 12px; border-bottom: 2px solid #E5E7EB;">المنتج</th>
                        <th style="padding: 12px; border-bottom: 2px solid #E5E7EB;">التاريخ</th>
                        <th style="padding: 12px; border-bottom: 2px solid #E5E7EB;">الحالة والنزاعات</th>
                        <th style="padding: 12px; border-bottom: 2px solid #E5E7EB;">التقييم</th>
                        <th style="padding: 12px; border-bottom: 2px solid #E5E7EB; text-align: center;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($orders as $order)
                    @php 
                        $isCancelled = trim($order->status) == 'cancelled';
                        $isDelivered = in_array(trim($order->status), ['delivered', 'completed', 'shipped']);
                    @endphp
                    <tr style="font-size: 0.9rem; background: {{ $isCancelled ? '#F8FAFC' : 'transparent' }}; opacity: {{ $isCancelled ? '0.75' : '1' }};">
                        
                        <td style="padding: 12px; border-bottom: 1px solid #E5E7EB; color: #111827; font-weight: bold;">
                            {{ $order->product_name }}
                            @if($isCancelled)
                                <span style="display: block; font-size: 0.75rem; color: #DC2626; font-weight: normal;">(تم إلغاء هذا الطلب)</span>
                            @endif
                        </td>

                        <td style="padding: 12px; border-bottom: 1px solid #E5E7EB; color: #111827;">
                            {{ $order->created_at->format('Y-m-d') }} <br>
                            <small style="color: #64748B;">{{ $order->created_at->format('H:i') }}</small>
                        </td>

                        <td style="padding: 12px; border-bottom: 1px solid #E5E7EB;">
                            @if($isCancelled)
                                <span style="padding: 5px 10px; border-radius: 5px; background: #FEE2E2; color: #DC2626; border: 1px solid #FCA5A5; font-weight: bold; font-size: 0.8rem;">
                                    ملغى
                                </span>
                            @else
                                <span style="padding: 5px 10px; border-radius: 5px; background: #FEF9C3; color: #854D0E; border: 1px solid #FEF08A; font-weight: bold; font-size: 0.8rem;">
                                    {{ $order->status }}
                                </span>
                            @endif

                            <!-- نظام النزاعات والإرجاع مع رفع الملفات والتنبيه -->
                            @if(in_array($order->status, ['completed', 'shipped', 'delivered']))
                                @php
                                    $existingDispute = \App\Models\Dispute::where('order_id', $order->id)->first();
                                @endphp

                                <div style="margin-top: 8px;">
                                    @if($existingDispute)
                                        <span style="display: inline-block; background: #FEF9C3; color: #854D0E; padding: 4px 8px; border-radius: 4px; font-size: 0.75rem; font-weight: bold;">
                                            📌 حالة النزاع: 
                                            @if($existingDispute->status == 'pending') قيد المراجعة
                                            @elseif($existingDispute->status == 'resolved') تم الحل ✓
                                            @else مرفوض ✕
                                            @endif
                                        </span>
                                    @else
                                        <!-- نموذج النزاع مع دعم رفع الصور/الفيديوهات والتنبيه -->
                                        <form action="{{ route('customer.dispute.store', $order->id) }}" method="POST" enctype="multipart/form-data" style="display: flex; flex-direction: column; gap: 6px; margin-top: 6px; background: #F8FAFC; padding: 10px; border-radius: 8px; border: 1px solid #E2E8F0;">
                                            @csrf
                                            
                                            <!-- التنبيه الإرشادي لضمان الحقوق -->
                                            <div style="background: #FEF3C7; color: #92400E; padding: 6px 8px; border-radius: 4px; font-size: 0.7rem; font-weight: bold; border: 1px solid #FDE68A; line-height: 1.4;">
                                                ⚠️ يرجى تصوير فيديو أثناء فتح المنتج لضمان حقوق العميل والتاجر وإرفاقه أدناه.
                                            </div>

                                            <input type="text" name="reason" placeholder="سبب الإرجاع (تالف، خطأ بالشحن...)" required style="padding: 6px 8px; border: 1px solid #CBD5E1; border-radius: 4px; font-size: 0.78rem; font-family: 'Cairo';">
                                            
                                            <div style="display: flex; flex-direction: column; gap: 3px;">
                                                <label style="font-size: 0.72rem; color: #334155; font-weight: bold;">إرفاق صورة أو فيديو الإثبات:</label>
                                                <input type="file" name="proof_file" accept="image/*,video/*" style="font-size: 0.72rem; font-family: 'Cairo';">
                                            </div>

                                            <button type="submit" style="background: #D4AF37; color: #0B1B3D; border: none; padding: 6px; border-radius: 4px; font-weight: bold; cursor: pointer; font-size: 0.78rem; font-family: 'Cairo';">
                                                🔄 إرسال طلب الإرجاع/النزاع
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            @endif
                        </td>

                        <td style="padding: 12px; border-bottom: 1px solid #E5E7EB;">
                            @if($isDelivered)
                                @if($order->product_id)
                                    <form action="{{ route('reviews.store', $order->product_id) }}" method="POST" style="display: flex; gap: 5px; align-items: center;">
                                        @csrf
                                        <input type="hidden" name="order_id" value="{{ $order->id }}">
                                        <select name="rating" style="padding: 2px; border: 1px solid #E5E7EB; border-radius: 4px; font-family: 'Cairo';">
                                            <option value="5">⭐⭐⭐⭐⭐</option>
                                            <option value="1">⭐</option>
                                        </select>
                                        <button type="submit" style="background: #1E6FB8; color: white; border:none; padding: 4px 10px; border-radius: 4px; cursor:pointer; font-weight: bold; font-family: 'Cairo';">تقييم</button>
                                    </form>
                                @endif
                            @else
                                <small style="color: #64748B;">{{ $isCancelled ? 'غير متاح' : 'متاح بعد التسليم' }}</small>
                            @endif
                        </td>

                        <td style="padding: 12px; border-bottom: 1px solid #E5E7EB; text-align: center;">
                            <div style="display: flex; gap: 8px; justify-content: center; align-items: center;">
                                @if($isCancelled)
                                    <span style="color: #DC2626; font-size: 0.8rem; font-weight: bold;">أُلغي الطلب</span>
                                @else
                                    <a href="{{ route('customer.invoice', $order->id) }}" style="background: #F1F5F9; color: #1E6FB8; padding: 5px 10px; border-radius: 6px; text-decoration: none; font-weight: bold; font-size: 0.8rem; border: 1px solid #CBD5E1;">
                                        الفاتورة
                                    </a>

                                    @if(trim($order->status) == 'pending')
                                        <form action="{{ route('customer.orders.cancel', $order->id) }}" method="POST" style="margin: 0;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" onclick="return confirm('هل أنت متأكد من رغبتك في إلغاء هذا الطلب؟')" 
                                                    style="background: #FEE2E2; color: #DC2626; border: 1px solid #FCA5A5; padding: 5px 10px; border-radius: 6px; cursor: pointer; font-size: 0.8rem; font-weight: bold; font-family: 'Cairo';">
                                                إلغاء
                                            </button>
                                        </form>
                                    @endif
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection