@extends('layouts.app')

@section('content')
<div style="padding: 20px 40px; background: #F6F8FB; min-height: 80vh;" dir="rtl">

    <!-- قسم الإشعارات الجديدة -->
    <div style="background: white; padding: 20px; border-radius: 15px; border: 1px solid #E5E7EB; margin-bottom: 25px; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
        <h4 style="color: #0B1B3D; margin-top: 0; display: flex; align-items: center; gap: 8px; font-size: 1.1rem;">
            🔔 إشعارات التقييمات 
            <span style="background: #D4AF37; color: #0B1B3D; padding: 2px 10px; border-radius: 12px; font-size: 0.85rem; font-weight: bold;">
                {{ Auth::user()->unreadNotifications->count() }}
            </span>
        </h4>

        <div style="display: flex; flex-direction: column; gap: 10px; margin-top: 15px;">
            @forelse(Auth::user()->unreadNotifications as $notification)
                <div style="background: #F6F8FB; padding: 12px 15px; border-radius: 8px; border-right: 4px solid #1E6FB8; display: flex; justify-content: space-between; align-items: center; border: 1px solid #E5E7EB;">
                    <div>
                        <strong style="color: #0B1B3D; display: block; font-size: 0.95rem;">{{ $notification->data['title'] }}</strong>
                        <p style="margin: 3px 0 0 0; color: #4B5563; font-size: 0.9rem;">{{ $notification->data['message'] }}</p>
                        <small style="color: #9CA3AF; font-size: 0.75rem;">{{ $notification->created_at->diffForHumans() }}</small>
                    </div>
                    <div>
                        <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                            @csrf
                            <button type="submit" style="background: #0B1B3D; color: #D4AF37; border: none; padding: 6px 12px; border-radius: 6px; font-size: 0.8rem; cursor: pointer; font-family: 'Cairo'; font-weight: bold;">تعليم كمقروء</button>
                        </form>
                    </div>
                </div>
            @empty
                <p style="color: #64748B; text-align: center; margin: 10px 0; font-size: 0.9rem;">لا توجد إشعارات جديدة في الوقت الحالي.</p>
            @endforelse
        </div>
    </div>

    @php $newOrdersCount = $orders->where('status', 'pending')->count(); @endphp
    
    @if($newOrdersCount > 0)
        <div style="background: rgba(212, 175, 55, 0.15); color: #0B1B3D; padding: 20px; border-radius: 15px; margin-bottom: 25px; border: 1px solid #D4AF37; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
            <div style="font-size: 1.1rem;">⚠️ <strong>تنبيه:</strong> لديك {{ $newOrdersCount }} طلبات جديدة تنتظر المعالجة!</div>
            <a href="{{ route('vendor.orders') }}" style="background: #D4AF37; color: #0B1B3D; padding: 10px 20px; border-radius: 10px; text-decoration: none; font-weight: bold; transition: opacity 0.3s;">عرض الطلبات الآن</a>
        </div>
    @else
        <div style="background: #e6f4ea; color: #137333; padding: 15px 25px; border-radius: 15px; margin-bottom: 25px; border: 1px solid #ceead6; font-weight: bold;">
            ✅ لا توجد طلبات جديدة حالياً، متجرك في حالة ممتازة!
        </div>
    @endif

    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div style="background: #0B1B3D; color: white; padding: 20px; border-radius: 15px; text-align: center; border-bottom: 3px solid #D4AF37;">
            <div style="font-size: 0.9rem; opacity: 0.8;">إجمالي الأرباح</div>
            <div style="font-size: 1.5rem; font-weight: bold; color: #D4AF37;">{{ number_format($wallet->balance ?? 0, 0) }} ل.س</div>
        </div>
        <div style="background: #1E6FB8; color: white; padding: 20px; border-radius: 15px; text-align: center;">
            <div style="font-size: 0.9rem; opacity: 0.8;">طلبات جديدة</div>
            <div style="font-size: 1.5rem; font-weight: bold;">{{ $newOrdersCount }}</div>
        </div>
        <div style="background: #0B1B3D; color: white; padding: 20px; border-radius: 15px; text-align: center; border-bottom: 3px solid #1E6FB8;">
            <div style="font-size: 0.9rem; opacity: 0.8;">المنتجات النشطة</div>
            <div style="font-size: 1.5rem; font-weight: bold;">{{ $products->count() }}</div>
        </div>
    </div>

    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="color: #0B1B3D;">إدارة الطلبات والمنتجات</h2>
        <a href="{{ route('vendor.products.create') }}" style="background: #D4AF37; color: #0B1B3D; padding: 10px 20px; border-radius: 25px; text-decoration: none; font-weight: bold; transition: opacity 0.3s;">+ إضافة منتج جديد</a>
    </div>

    <div style="background: white; padding: 20px; border-radius: 20px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 30px; border: 1px solid #E5E7EB;">
        <h3 style="margin-bottom: 15px; color: #0B1B3D;">أحدث الطلبات</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <thead>
                <tr style="text-align: right; border-bottom: 2px solid #E5E7EB; background: #F6F8FB; color: #0B1B3D;">
                    <th style="padding: 15px;">المنتج</th>
                    <th style="padding: 15px;">العميل</th>
                    <th style="padding: 15px;">الحالة</th>
                    <th style="padding: 15px;">إجراء</th>
                </tr>
            </thead>
            <tbody>
                @foreach($orders->take(5) as $order)
                <tr style="border-bottom: 1px solid #E5E7EB;">
                    <td style="padding: 15px; color: #111827;">{{ $order->product->name ?? 'منتج محذوف' }}</td>
                    <td style="padding: 15px; color: #111827;">{{ $order->customer->name ?? 'غير معروف' }}</td>
                    <td style="padding: 15px;">
                        <span style="padding: 5px 10px; border-radius: 10px; background: #F6F8FB; font-size: 0.8rem; color: #0B1B3D; border: 1px solid #E5E7EB; font-weight: bold;">{{ $order->status }}</span>
                    </td>
                    <td style="padding: 15px;">
                        <form action="{{ route('vendor.orders.update', $order->id) }}" method="POST">
                            @csrf @method('PUT')
                            <select name="status" onchange="this.form.submit()" style="border: 1px solid #E5E7EB; background: white; padding: 5px; border-radius: 5px; color: #111827; cursor: pointer;">
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
        <div style="background: white; padding: 15px; border-radius: 15px; box-shadow: 0 4px 10px rgba(0,0,0,0.05); text-align: center; border: 1px solid #E5E7EB;">
            <img src="{{ asset('storage/' . $product->image) }}" style="width: 100%; height: 150px; object-fit: cover; border-radius: 10px; border: 1px solid #E5E7EB;">
            <h4 style="margin: 15px 0; color: #0B1B3D;">{{ $product->name }}</h4>
            <div style="display: flex; gap: 10px; justify-content: center;">
                <a href="{{ route('vendor.products.edit', $product->id) }}" style="text-decoration: none; color: #1E6FB8; font-weight: bold;">تعديل</a>
                <form action="{{ route('vendor.products.destroy', $product->id) }}" method="POST">
                    @csrf @method('DELETE')
                    <button type="submit" style="border: none; background: none; color: #ef4444; cursor: pointer; font-weight: bold;">حذف</button>
                </form>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection