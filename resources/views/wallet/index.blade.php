@extends('layouts.app')

@section('content')
<div style="max-width: 500px; margin: 40px auto; padding: 30px; background: #fff; border: 1px solid #ddd; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
    
    <h2 style="text-align: center; color: #333;">
        {{ auth()->user()->role == 'customer' ? 'شحن رصيد المحفظة' : 'طلب سحب الأرباح' }}
    </h2>

    @if(session('success'))
        <div style="background: #d4edda; color: #155724; padding: 10px; border-radius: 5px; margin-bottom: 20px; text-align: center;">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div style="background: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; margin-bottom: 20px; text-align: center;">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ auth()->user()->role == 'customer' ? route('wallet.charge') : route('wallet.withdraw') }}" method="POST">
        @csrf
        
        <div style="margin-bottom: 20px;">
            <label style="display: block; margin-bottom: 8px; font-weight: bold;">أدخل المبلغ:</label>
            <input type="number" name="amount" required step="0.01" min="1" 
                   style="width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 5px; box-sizing: border-box;">
        </div>

        <button type="submit" style="width: 100%; padding: 12px; background: {{ auth()->user()->role == 'customer' ? '#2e7d32' : '#e65100' }}; color: white; border: none; border-radius: 5px; font-weight: bold; cursor: pointer; font-size: 16px;">
            {{ auth()->user()->role == 'customer' ? 'تأكيد عملية الشحن' : 'تأكيد طلب السحب' }}
        </button>
    </form>
</div>
@endsection