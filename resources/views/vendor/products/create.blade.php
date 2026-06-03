@extends('layouts.app')

@section('content')
<h2>إضافة منتج جديد</h2>

<form action="{{ route('vendor.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    
    <div>
        <label>اسم المنتج</label>
        <input type="text" name="name" required>
    </div>

    <div>
        <label>الوصف</label>
        <textarea name="description"></textarea>
    </div>
    
    <div>
        <label>السعر</label>
        <input type="number" name="price" step="0.01" required>
    </div>
    
    <div>
        <label>الكمية</label>
        <input type="number" name="stock" required>
    </div>
    
    <div>
        <label>التصنيف</label>
        <select name="category_id" required>
            <option value="">اختر تصنيفاً</option> 
            @foreach($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label>صورة المنتج</label>
        <input type="file" name="image" accept="image/*">
    </div>
    
    <button type="submit">حفظ المنتج</button>
    
    @if ($errors->any())
        <div style="color: red;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
</form>
@endsection