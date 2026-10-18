@extends('layouts.app')

@section('content')
<style>
    @media print {
        form, button, .no-print { display: none !important; }
        body { padding: 20px; }
        table { width: 100% !important; border: 1px solid #ddd; }
    }
</style>

<div style="max-width: 800px; margin: 40px auto; padding: 30px; background: #fff; border: 1px solid #ddd; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
    
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="color: #333; margin: 0;">
            {{ auth()->user()->role == 'customer' ? 'شحن رصيد المحفظة' : 'طلب سحب الأرباح' }}
        </h2>
        <span style="background: #f1f5f9; color: #0f172a; padding: 8px 15px; border-radius: 8px; font-weight: bold; font-size: 0.95rem;">
            الرصيد الحالي: {{ number_format($wallet->balance ?? 0, 0) }} ل.س
        </span>
    </div>

    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px; text-align: center;">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px; text-align: center;">{{ session('error') }}</div>
    @endif

    <form action="{{ auth()->user()->role == 'customer' ? route('wallet.charge') : route('wallet.withdraw') }}" method="POST" style="margin-bottom: 40px;">
        @csrf
        <div style="margin-bottom: 15px;">
            <label style="font-weight: bold; display: block; margin-bottom: 5px;">المبلغ (ل.س):</label>
            <input type="number" name="amount" required step="1" min="1000" placeholder="أدخل المبلغ (الحد الأدنى 1000)..." style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; font-family: 'Cairo';">
        </div>

        @if(auth()->user()->role == 'customer')
        <!-- خيارات الشحن للعميل -->
        <div style="margin-bottom: 15px;">
            <label style="font-weight: bold; display: block; margin-bottom: 5px;">طريقة الشحن والدفع:</label>
            <select name="method" id="chargeMethod" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; background: white; font-family: 'Cairo';" onchange="updateChargeFields()">
                <option value="sham_cash">شام كاش (رقم الحوالة / الإيصال)</option>
                <option value="card">بطاقة إلكترونية / ائتمانية</option>
                <option value="bank_transfer">تحويل بنكي مباشر</option>
            </select>
        </div>

        <div style="margin-bottom: 15px;">
            <label style="font-weight: bold; display: block; margin-bottom: 5px;" id="chargeLabel">معلومات الإيصال أو رقم الحوالة:</label>
            <input type="text" name="details" id="chargeDetails" required placeholder="أدخل رقم الإيصال أو تفاصيل عملية الدفع..." style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; font-family: 'Cairo';">
        </div>
        @endif

        @if(auth()->user()->role == 'vendor')
        <!-- خيارات السحب للتاجر -->
        <div style="margin-bottom: 15px;">
            <label style="font-weight: bold; display: block; margin-bottom: 5px;">طريقة السحب:</label>
            <select name="method" id="withdrawMethod" style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; background: white; font-family: 'Cairo';" onchange="updateWithdrawFields()">
                <option value="sham_cash">شام كاش (Sham Cash)</option>
                <option value="bank_transfer">حوالة بنكية (IBAN منفصل)</option>
            </select>
        </div>

        <!-- حقل شام كاش (يظهر افتراضياً) -->
        <div id="shamCashBox" style="margin-bottom: 15px;">
            <label style="font-weight: bold; display: block; margin-bottom: 5px;">رقم هاتف شام كاش:</label>
            <input type="text" name="details" id="shamCashInput" placeholder="أدخل رقم الهاتف المرتبط بحساب شام كاش..." style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box; font-family: 'Cairo';">
        </div>

        <!-- حقول الحوالة البنكية المنفصلة (تظهر عند اختيار التحويل البنكي) -->
        <div id="bankTransferBox" style="display: none; background: #F8FAFC; padding: 15px; border-radius: 8px; border: 1px solid #E2E8F0; margin-bottom: 15px;">
            <div style="margin-bottom: 12px;">
                <label style="font-weight: bold; display: block; margin-bottom: 5px; font-size: 0.9rem;">الاسم الثلاثي للمستفيد:</label>
                <input type="text" name="account_name" id="accountNameInput" placeholder="أدخل الاسم الثلاثي صريحاً..." style="width: 100%; padding: 10px; border: 1px solid #CBD5E1; border-radius: 6px; background: white; font-family: 'Cairo'; box-sizing: border-box;">
            </div>

            <div style="margin-bottom: 12px;">
                <label style="font-weight: bold; display: block; margin-bottom: 5px; font-size: 0.9rem;">اسم البنك:</label>
                <input type="text" name="bank_name" id="bankNameInput" placeholder="مثال: البنك التجاري السوري، بابل، البركة..." style="width: 100%; padding: 10px; border: 1px solid #CBD5E1; border-radius: 6px; background: white; font-family: 'Cairo'; box-sizing: border-box;">
            </div>

            <div>
                <label style="font-weight: bold; display: block; margin-bottom: 5px; font-size: 0.9rem;">رقم الحساب أو الآيبان (IBAN):</label>
                <input type="text" name="iban" id="ibanInput" placeholder="أدخل رقم الآيبان الكامل (SY...)" style="width: 100%; padding: 10px; border: 1px solid #CBD5E1; border-radius: 6px; background: white; font-family: 'Cairo'; box-sizing: border-box;">
            </div>
        </div>
        @endif

        <button type="submit" style="width: 100%; padding: 12px; background: {{ auth()->user()->role == 'customer' ? '#2e7d32' : '#e65100' }}; color: white; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; font-family: 'Cairo';">
            {{ auth()->user()->role == 'customer' ? 'تأكيد طلب شحن الرصيد' : 'تأكيد طلب السحب' }}
        </button>
    </form>

    <hr style="border: 0; border-top: 1px solid #eee; margin-bottom: 30px;">

    <h3 style="color: #333; margin-bottom: 15px;">سجل الطلبات والعمليات السابقة</h3>
    
    @if(isset($withdrawals) && $withdrawals->count() > 0)
        <table style="width: 100%; border-collapse: collapse; margin-top: 10px;">
            <thead>
                <tr style="background: #f8f9fa;">
                    <th style="padding: 10px; border-bottom: 2px solid #ddd; text-align: right;">المبلغ</th>
                    <th style="padding: 10px; border-bottom: 2px solid #ddd; text-align: right;">الطريقة</th>
                    <th style="padding: 10px; border-bottom: 2px solid #ddd; text-align: right;">التفاصيل المدخلة</th>
                    <th style="padding: 10px; border-bottom: 2px solid #ddd; text-align: right;">التاريخ والساعة</th>
                    <th style="padding: 10px; border-bottom: 2px solid #ddd; text-align: right;">الحالة</th>
                    <th style="padding: 10px; border-bottom: 2px solid #ddd; text-align: right;" class="no-print">إجراء</th>
                </tr>
            </thead>
            <tbody>
                @foreach($withdrawals as $w)
                <tr>
                    <td style="padding: 10px; border-bottom: 1px solid #eee; font-weight: bold; color: #0f172a;">
                        {{ number_format($w->amount, 0) }} ل.س
                    </td>
                    <td style="padding: 10px; border-bottom: 1px solid #eee;">
                        @if($w->method == 'sham_cash')
                            شام كاش
                        @elseif($w->method == 'bank_transfer')
                            حوالة بنكية (IBAN)
                        @else
                            {{ $w->method }}
                        @endif
                    </td>
                    <td style="padding: 10px; border-bottom: 1px solid #eee; color: #64748B; font-size: 0.85rem;">
                        {{ $w->details ?? '-' }}
                    </td>
                    <td style="padding: 10px; border-bottom: 1px solid #eee; font-size: 0.9rem;">
                        {{ $w->created_at->format('Y-m-d H:i') }}
                    </td>
                    <td style="padding: 10px; border-bottom: 1px solid #eee;">
                        <span style="padding: 4px 8px; border-radius: 4px; font-size: 0.85rem; font-weight: bold; {{ $w->status == 'pending' ? 'background: #fef3c7; color: #92400e;' : 'background: #dcfce7; color: #166534;' }}">
                            {{ $w->status == 'pending' ? 'قيد المعالجة' : 'مكتمل' }}
                        </span>
                    </td>
                    <td style="padding: 10px; border-bottom: 1px solid #eee;" class="no-print">
                        <button onclick="window.print()" style="padding: 5px 10px; background: #607d8b; color: white; border: none; border-radius: 4px; cursor: pointer; font-family: 'Cairo';">طباعة</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="color: #64748B; text-align: center; padding: 20px; background: #f8fafc; border-radius: 8px;">لا توجد طلبات سحب أو عمليات مسجلة حتى الآن.</p>
    @endif
</div>

<script>
function updateWithdrawFields() {
    const method = document.getElementById('withdrawMethod').value;
    const shamBox = document.getElementById('shamCashBox');
    const bankBox = document.getElementById('bankTransferBox');
    
    const shamInput = document.getElementById('shamCashInput');
    const accountNameInput = document.getElementById('accountNameInput');
    const bankNameInput = document.getElementById('bankNameInput');
    const ibanInput = document.getElementById('ibanInput');

    if (method === 'sham_cash') {
        shamBox.style.display = 'block';
        bankBox.style.display = 'none';
        shamInput.setAttribute('required', 'required');
        accountNameInput.removeAttribute('required');
        bankNameInput.removeAttribute('required');
        ibanInput.removeAttribute('required');
    } else if (method === 'bank_transfer') {
        shamBox.style.display = 'none';
        bankBox.style.display = 'block';
        shamInput.removeAttribute('required');
        accountNameInput.setAttribute('required', 'required');
        bankNameInput.setAttribute('required', 'required');
        ibanInput.setAttribute('required', 'required');
    }
}
</script>
@endsection