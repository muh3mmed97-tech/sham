@extends('layouts.app')

@section('content')
<div style="padding: 20px 40px; background: #f4f7f6; min-height: 80vh;">

    @php $newOrdersCount = $orders->where('status', 'pending')->count(); @endphp
    
    @if($newOrdersCount > 0)
        <div style="background: #fff3cd; color: #856404; padding: 20px; border-radius: 15px; margin-bottom: 25px; border: 1px solid #ffeeba; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
            <div style="font-size: 1.1rem;">⚠️ <strong>تنبيه:</strong> لديك {{ $newOrdersCount }} طلبات جديدة تنتظر المعالجة!</div>
            <a href="{{ route('vendor.orders') }}" style="background: #856404; color: white; padding: 10px 20px; border-radius: 10px; text-decoration: none; font-weight: bold;">عرض الطلبات الآن</a>
        </div>
    @else
        <div style="background: #d4edda; color: #155724; padding: 15px 25px; border-radius: 15px; margin-bottom: 25px; border: 1px solid #c3e6cb; font-weight: bold;">
            ✅ لا توجد طلبات جديدة حالياً، متجرك في حالة ممتازة!
        </div>
    @endif

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div style="background: #005a9c; color: white; padding: 20px; border-radius: 15px; text-align: center;">
            <div style="font-size: 0.9rem; opacity: 0.8;">إجمالي الأرباح</div>
            <div style="font-size: 1.5rem; font-weight: bold;">{{ number_format($wallet->balance ?? 0, 0) }} ل.س</div>
        </div>
        <div style="background: #27ae60; color: white; padding: 20px; border-radius: 15px; text-align: center;">
            <div style="font-size: 0.9rem; opacity: 0.8;">طلبات جديدة</div>
            <div style="font-size: 1.5rem; font-weight: bold;">{{ $newOrdersCount }}</div>
        </div>
        <div style="background: #f39c12; color: white; padding: 20px; border-radius: 15px; text-align: center;">
            <div style="font-size: 0.9rem; opacity: 0.8;">المنتجات النشطة</div>
            <div style="font-size: 1.5rem; font-weight: bold;">{{ $products->count() }}</div>
        </div>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="color: #005a9c;">إدارة الطلبات والمنتجات</h2>
        <a href="{{ route('vendor.products.create') }}" style="background: #005a9c; color: white; padding: 10px 20px; border-radius: 25px; text-decoration: none; font-weight: bold;">+ إضافة منتج جديد</a>
    </div>

    <div style="background: white; padding: 20px; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 30px;">
        <h3 style="margin-bottom: 15px; color: #333;">أحدث الطلبات</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: right; border-bottom: 2px solid #eee;">
                    <th style="padding: 15px;">المنتج</th>
                    <th style="padding: 15px;">العميل</th>
                    <th style="padding: 15px;">الحالة</th>
                    <th style="padding: 15px;">إجراء</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders->take(5) as $order)
                <tr style="border-bottom: 1px solid #eee;">
                    <td style="padding: 15px;">{{ $order->product->name ?? 'منتج محذوف' }}</td>
                    <td style="padding: 15px;">{{ $order->customer->name ?? 'غير معروف' }}</td>
                    <td style="padding: 15px;">
                        <span style="padding: 5px 10px; border-radius: 10px; background: #eef2f7; font-size: 0.8rem;">{{ $order->status }}</span>
                    </td>
                    <td style="padding: 15px;">
                        <form action="{{ route('vendor.orders.update', $order->id) }}" method="POST">
                            @csrf @method('PUT')
                            <select name="status" onchange="this.form.submit()" style="border: none; background: #f4f4f4; padding: 5px; border-radius: 5px;">
                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>معلق</option>
                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>تم التسليم</option>
                            </select>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px;">
        @foreach($products as $product)
        <div style="background: white; padding: 15px; border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); text-align: center;">
            <img src="{{ asset('storage/' . $product->image) }}" style="width: 100%; height: 150px; object-fit: cover; border-radius: 10px;">
            <h4 style="margin: 15px 0;">{{ $product->name }}</h4>
            <div style="display: flex; gap: 10px; justify-content: center;">
                <a href="{{ route('vendor.products.edit', $product->id) }}" style="text-decoration: none; color: #005a9c; font-weight: bold;">تعديل</a>
                <form action="{{ route('vendor.products.destroy', $product->id) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" style="border: none; background: none; color: #e74c3c; cursor: pointer; font-weight: bold;">حذف</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection