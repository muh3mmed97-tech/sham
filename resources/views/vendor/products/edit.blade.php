@extends('layouts.app')

@section('content')
<h2>تعديل المنتج: {{ $product->name }}</h2>

<form action="{{ route('vendor.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    
    <div>
        <label>اسم المنتج</label>
        <input type="text" name="name" value="{{ $product->name }}" required>
    </div>

    <div>
        <label>الوصف</label>
        <textarea name="description">{{ $product->description }}</textarea>
    </div>
    
    <div>
        <label>السعر</label>
        <input type="number" name="price" value="{{ $product->price }}" step="0.01" required>
    </div>
    
    <div>
        <label>الكمية</label>
        <input type="number" name="stock" value="{{ $product->stock }}" required>
    </div>
    
    <div>
        <label>التصنيف</label>
        <select name="category_id" required>
            @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label>صورة المنتج الحالية</label><br>
        @if($product->image)
            <img src="{{ asset('storage/' . $product->image) }}" width="100"><br>
        @endif
        <input type="file" name="image" accept="image/*">
    </div>
    
    <button type="submit">حفظ التعديلات</button>
</form>
@endsection