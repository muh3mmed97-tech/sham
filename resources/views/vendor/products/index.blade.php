@extends('layouts.app')

@section('content')
<h2>قائمة منتجاتي</h2>

@if(session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif

<a href="{{ route('vendor.products.create') }}">إضافة منتج جديد</a>

<table border="1" width="100%" style="margin-top: 20px;">
    <thead>
        <tr>
            <th>الصورة</th>
            <th>الاسم</th>
            <th>السعر</th>
            <th>الكمية</th>
            <th>العمليات</th> 
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
        <tr>
            <td>
                @if($product->image)
                    {{-- التعديل هنا: نستخدم asset مع المسار المخزن --}}
                    <img src="{{ asset('storage/' . $product->image) }}" width="50" alt="صورة المنتج">
                @else
                    لا توجد صورة
                @endif
            </td>
            <td>{{ $product->name }}</td>
            <td>{{ $product->price }}</td>
            <td>{{ $product->stock }}</td>
            
            <td>
                <a href="{{ route('vendor.products.edit', $product->id) }}">تعديل</a>
                
                <form action="{{ route('vendor.products.destroy', $product->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" onclick="return confirm('هل أنت متأكد من الحذف؟')">حذف</button>
                </form>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>
@endsection