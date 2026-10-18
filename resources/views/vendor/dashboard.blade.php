@extends('layouts.app')

@section('content')
<div style="max-width: 1200px; margin: 30px auto; padding: 0 15px; font-family: 'Cairo', sans-serif;" dir="rtl">

    <!-- 1. رأس الصفحة والترحيب بالتاجر -->
    <div style="background: white; border-radius: 16px; padding: 24px 30px; margin-bottom: 25px; box-shadow: 0 4px 15px rgba(11, 27, 61, 0.05); border: 1px solid #E2E8F0; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 15px;">
        <div>
            <div style="display: flex; align-items: center; gap: 10px;">
                <h1 style="color: #0B1B3D; font-size: 1.6rem; font-weight: 800; margin: 0;">
                    🏪 {{ auth()->user()->store->name ?? 'متجري' }}
                </h1>
                <span style="background: rgba(212, 175, 55, 0.15); color: #B38E1B; font-weight: bold; font-size: 0.85rem; padding: 3px 10px; border-radius: 20px;">
                    تاجر موثق
                </span>
            </div>
            <p style="color: #64748B; font-size: 0.95rem; margin: 6px 0 0 0;">
                مرحباً بك مجدداً، إليك ملخص لأداء متجرك ومبيعاتك اليوم.
            </p>
        </div>
        <div style="display: flex; gap: 12px; align-items: center;">
            <a href="{{ route('vendor.products.create') }}" style="background: #0B1B3D; color: #D4AF37; text-decoration: none; padding: 12px 22px; border-radius: 10px; font-weight: bold; font-size: 0.95rem; display: flex; align-items: center; gap: 8px; box-shadow: 0 4px 12px rgba(11, 27, 61, 0.15); transition: opacity 0.2s;">
                <span style="font-size: 1.1rem;">+</span> إضافة منتج جديد
            </a>
            <a href="{{ route('store.show', auth()->id()) }}" target="_blank" style="background: #F1F5F9; color: #0B1B3D; text-decoration: none; padding: 12px 18px; border-radius: 10px; font-weight: bold; font-size: 0.95rem; border: 1px solid #CBD5E1;">
                عرض المتجر ↗
            </a>
        </div>
    </div>

    <!-- 2. بطاقات الإحصائيات الأربعة (KPIs) -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 20px; margin-bottom: 30px;">
        
        <!-- أرباح المتجر -->
        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <span style="color: #64748B; font-size: 0.9rem; font-weight: 600;">الرصيد المتاح للسحب</span>
                    <h3 style="color: #0B1B3D; font-size: 1.7rem; font-weight: 800; margin: 8px 0 0 0;">
                        {{ number_format($wallet->balance ?? 0, 0) }} <span style="font-size: 0.9rem; color: #94A3B8;">ل.س</span>
                    </h3>
                </div>
                <div style="background: rgba(212, 175, 55, 0.15); color: #D4AF37; width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
                    💰
                </div>
            </div>
            <div style="margin-top: 15px; border-top: 1px solid #F1F5F9; padding-top: 12px; display: flex; justify-content: space-between; align-items: center;">
                <span style="font-size: 0.85rem; color: #64748B;">محفظة الأرباح</span>
                <a href="{{ route('wallet.action') }}" style="color: #1E6FB8; font-weight: bold; font-size: 0.85rem; text-decoration: none;">طلب سحب ←</a>
            </div>
        </div>

        <!-- الطلبات الجديدة -->
        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <span style="color: #64748B; font-size: 0.9rem; font-weight: 600;">الطلبات بانتظار الشحن</span>
                    <h3 style="color: #0B1B3D; font-size: 1.7rem; font-weight: 800; margin: 8px 0 0 0;">
                        {{ $orders->where('status', 'pending')->count() }}
                    </h3>
                </div>
                <div style="background: rgba(30, 111, 184, 0.12); color: #1E6FB8; width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
                    📦
                </div>
            </div>
            <div style="margin-top: 15px; border-top: 1px solid #F1F5F9; padding-top: 12px;">
                <span style="font-size: 0.85rem; color: #64748B;">إجمالي الطلبات المستلمة: <strong>{{ $orders->count() }}</strong></span>
            </div>
        </div>

        <!-- المنتجات النشطة -->
        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <span style="color: #64748B; font-size: 0.9rem; font-weight: 600;">المنتجات المعروضة</span>
                    <h3 style="color: #0B1B3D; font-size: 1.7rem; font-weight: 800; margin: 8px 0 0 0;">
                        {{ $products->count() }}
                    </h3>
                </div>
                <div style="background: rgba(16, 185, 129, 0.12); color: #10B981; width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
                    🏷️
                </div>
            </div>
            <div style="margin-top: 15px; border-top: 1px solid #F1F5F9; padding-top: 12px;">
                <span style="font-size: 0.85rem; color: #64748B;">المخزون الإجمالي: <strong>{{ $products->sum('stock') }}</strong> قطعة</span>
            </div>
        </div>

        <!-- التقييم العام -->
        <div class="stat-card">
            <div style="display: flex; justify-content: space-between; align-items: flex-start;">
                <div>
                    <span style="color: #64748B; font-size: 0.9rem; font-weight: 600;">تقييم المتجر</span>
                    <h3 style="color: #0B1B3D; font-size: 1.7rem; font-weight: 800; margin: 8px 0 0 0;">
                        4.9 <span style="font-size: 1rem; color: #D4AF37;">★</span>
                    </h3>
                </div>
                <div style="background: rgba(245, 158, 11, 0.12); color: #F59E0B; width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 1.4rem;">
                    ⭐
                </div>
            </div>
            <div style="margin-top: 15px; border-top: 1px solid #F1F5F9; padding-top: 12px;">
                <span style="font-size: 0.85rem; color: #10B981; font-weight: bold;">أداء ممتاز ورضا عالي للعملاء</span>
            </div>
        </div>

    </div>

    <!-- 3. لوحة الجداول المتعددة (تبويبات الطلبات والمنتجات) -->
    <div style="background: white; border-radius: 16px; border: 1px solid #E2E8F0; box-shadow: 0 4px 15px rgba(11, 27, 61, 0.05); overflow: hidden;">
        
        <!-- شريط التبويبات -->
        <div style="display: flex; border-bottom: 1px solid #E2E8F0; background: #F8FAFC; padding: 0 20px;">
            <button onclick="switchTab('orders')" id="tab-btn-orders" class="tab-button active-tab">
                📋 أحدث الطلبات ({{ $orders->count() }})
            </button>
            <button onclick="switchTab('products')" id="tab-btn-products" class="tab-button">
                🛍️ منتجات المتجر ({{ $products->count() }})
            </button>
        </div>

        <!-- محتوى تبويب: أحدث الطلبات -->
        <div id="tab-orders" style="display: block; padding: 20px;">
            @if($orders->count() > 0)
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; text-align: right;">
                        <thead>
                            <tr style="border-bottom: 2px solid #F1F5F9; color: #64748B; font-size: 0.9rem;">
                                <th style="padding: 14px 10px;">رقم الطلب</th>
                                <th style="padding: 14px 10px;">العميل</th>
                                <th style="padding: 14px 10px;">المبلغ الإجمالي</th>
                                <th style="padding: 14px 10px;">حالة الطلب</th>
                                <th style="padding: 14px 10px;">تاريخ الطلب</th>
                                <th style="padding: 14px 10px; text-align: center;">إجراء</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($orders as $order)
                                <tr style="border-bottom: 1px solid #F1F5F9;">
                                    <td style="padding: 16px 10px; font-weight: bold; color: #0B1B3D;">#{{ $order->id }}</td>
                                    <td style="padding: 16px 10px; color: #334155;">{{ $order->customer->name ?? 'عميل' }}</td>
                                    <td style="padding: 16px 10px; font-weight: bold; color: #1E6FB8;">
                                        {{ number_format($order->total_price, 0) }} ل.س
                                    </td>
                                    <td style="padding: 16px 10px;">
                                        @if($order->status == 'pending')
                                            <span class="badge badge-warning">قيد الانتظار</span>
                                        @elseif($order->status == 'processing')
                                            <span class="badge badge-info">قيد التجهيز</span>
                                        @elseif($order->status == 'shipped')
                                            <span class="badge badge-primary">تم الشحن</span>
                                        @elseif($order->status == 'delivered')
                                            <span class="badge badge-success">تم التوصيل</span>
                                        @else
                                            <span class="badge badge-danger">ملغي</span>
                                        @endif
                                    </td>
                                    <td style="padding: 16px 10px; color: #64748B; font-size: 0.85rem;">
                                        {{ $order->created_at->diffForHumans() }}
                                    </td>
                                    <td style="padding: 16px 10px; text-align: center;">
                                        <form action="{{ route('vendor.orders.updateStatus', $order->id) }}" method="POST" style="display: inline-flex; gap: 6px;">
                                            @csrf
                                            @method('PUT')
                                            <select name="status" onchange="this.form.submit()" style="padding: 6px 10px; border-radius: 8px; border: 1px solid #CBD5E1; font-family: 'Cairo'; font-size: 0.85rem; outline: none; cursor: pointer; background: white;">
                                                <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                                                <option value="processing" {{ $order->status == 'processing' ? 'selected' : '' }}>قيد التجهيز</option>
                                                <option value="shipped" {{ $order->status == 'shipped' ? 'selected' : '' }}>تم الشحن</option>
                                                <option value="delivered" {{ $order->status == 'delivered' ? 'selected' : '' }}>تم التوصيل</option>
                                                <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>إلغاء الطلب</option>
                                            </select>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div style="text-align: center; padding: 50px 20px;">
                    <div style="font-size: 3rem; margin-bottom: 10px;">📦</div>
                    <h3 style="color: #0B1B3D; margin: 0 0 6px 0;">لا توجد طلبات جديدة حالياً</h3>
                    <p style="color: #64748B; font-size: 0.95rem; margin: 0;">ستظهر جميع طلبات الزبائن مباشرة هنا فور إتمام الشراء.</p>
                </div>
            @endif
        </div>

        <!-- محتوى تبويب: منتجات المتجر -->
        <div id="tab-products" style="display: none; padding: 20px;">
            @if($products->count() > 0)
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; text-align: right;">
                        <thead>
                            <tr style="border-bottom: 2px solid #F1F5F9; color: #64748B; font-size: 0.9rem;">
                                <th style="padding: 14px 10px;">المنتج</th>
                                <th style="padding: 14px 10px;">القسم</th>
                                <th style="padding: 14px 10px;">السعر</th>
                                <th style="padding: 14px 10px;">المخزون</th>
                                <th style="padding: 14px 10px; text-align: center;">إجراءات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $product)
                                <tr style="border-bottom: 1px solid #F1F5F9;">
                                    <td style="padding: 14px 10px;">
                                        <div style="display: flex; align-items: center; gap: 12px;">
                                            <img src="{{ asset('storage/' . $product->image) }}" 
                                                 onerror="this.src='https://placehold.co/60x60?text=Product'" 
                                                 style="width: 50px; height: 50px; border-radius: 8px; object-fit: cover; border: 1px solid #E2E8F0;">
                                            <div>
                                                <a href="{{ route('product.show', $product->id) }}" style="text-decoration: none; color: #0B1B3D; font-weight: bold; font-size: 0.95rem;">
                                                    {{ $product->name }}
                                                </a>
                                            </div>
                                        </div>
                                    </td>
                                    <td style="padding: 14px 10px; color: #64748B; font-size: 0.9rem;">
                                        {{ $product->category->name ?? $product->category ?? 'غير محدد' }}
                                    </td>
                                    <td style="padding: 14px 10px; font-weight: bold; color: #0B1B3D;">
                                        {{ number_format($product->price, 0) }} ل.س
                                    </td>
                                    <td style="padding: 14px 10px;">
                                        @if($product->stock > 5)
                                            <span style="color: #10B981; font-weight: bold;">{{ $product->stock }} متوفر</span>
                                        @elseif($product->stock > 0)
                                            <span style="color: #F59E0B; font-weight: bold;">{{ $product->stock }} متبقي فقط</span>
                                        @else
                                            <span style="color: #EF4444; font-weight: bold;">نفد من المخزن</span>
                                        @endif
                                    </td>
                                    <td style="padding: 14px 10px; text-align: center;">
                                        <div style="display: inline-flex; gap: 8px;">
                                            <a href="{{ route('vendor.products.edit', $product->id) }}" style="background: #F1F5F9; color: #0B1B3D; text-decoration: none; padding: 6px 12px; border-radius: 6px; font-size: 0.85rem; font-weight: bold;">
                                                تعديل
                                            </a>
                                            <form action="{{ route('vendor.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج؟');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" style="background: #FEE2E2; color: #991B1B; border: none; padding: 6px 12px; border-radius: 6px; font-size: 0.85rem; font-weight: bold; cursor: pointer; font-family: 'Cairo';">
                                                    حذف
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div style="text-align: center; padding: 50px 20px;">
                    <div style="font-size: 3rem; margin-bottom: 10px;">🏷️</div>
                    <h3 style="color: #0B1B3D; margin: 0 0 6px 0;">لم تقم بإضافة أي منتج بعد</h3>
                    <p style="color: #64748B; font-size: 0.95rem; margin: 0 0 15px 0;">ابدأ الآن بعرض بضائعك ليتمكن الزوار من شرائها.</p>
                    <a href="{{ route('vendor.products.create') }}" style="background: #0B1B3D; color: #D4AF37; text-decoration: none; padding: 10px 20px; border-radius: 8px; font-weight: bold;">
                        + أضف منتجك الأول الآن
                    </a>
                </div>
            @endif
        </div>

    </div>

</div>

<!-- التنسيقات العصرية وسكربت التبديل -->
<style>
    .stat-card {
        background: white;
        border-radius: 16px;
        padding: 22px;
        border: 1px solid #E2E8F0;
        box-shadow: 0 4px 15px rgba(11, 27, 61, 0.04);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 22px rgba(11, 27, 61, 0.08);
    }
    .tab-button {
        background: none;
        border: none;
        padding: 16px 24px;
        font-family: 'Cairo', sans-serif;
        font-size: 0.95rem;
        font-weight: bold;
        color: #64748B;
        cursor: pointer;
        border-bottom: 3px solid transparent;
        transition: all 0.2s;
    }
    .tab-button.active-tab {
        color: #0B1B3D;
        border-bottom-color: #D4AF37;
        background: white;
    }
    .badge {
        padding: 5px 12px;
        border-radius: 20px;
        font-size: 0.8rem;
        font-weight: bold;
        display: inline-block;
    }
    .badge-warning { background: #FEF3C7; color: #92400E; }
    .badge-info { background: #E0F2FE; color: #075985; }
    .badge-primary { background: #DBEAFE; color: #1E40AF; }
    .badge-success { background: #D1FAE5; color: #065F46; }
    .badge-danger { background: #FEE2E2; color: #991B1B; }
</style>

<script>
    function switchTab(tabName) {
        document.getElementById('tab-orders').style.display = (tabName === 'orders') ? 'block' : 'none';
        document.getElementById('tab-products').style.display = (tabName === 'products') ? 'block' : 'none';

        document.getElementById('tab-btn-orders').classList.toggle('active-tab', tabName === 'orders');
        document.getElementById('tab-btn-products').classList.toggle('active-tab', tabName === 'products');
    }
</script>
@endsection