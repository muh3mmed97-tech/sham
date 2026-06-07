@extends('layouts.app')

@section('content')
<div style="max-width: 600px; margin: 40px auto; padding: 20px; background: #fff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
    <h2>إدارة الأقسام</h2>
    
    <form action="{{ route('vendor.categories.store') }}" method="POST" style="margin-bottom: 20px;">
        @csrf
        <input type="text" name="name" placeholder="اسم القسم الجديد" style="padding: 10px; width: 70%; border: 1px solid #ddd; border-radius: 4px;" required>
        <button type="submit" style="padding: 10px 20px; background: #f7931e; color: white; border: none; border-radius: 4px; cursor: pointer;">إضافة</button>
    </form>

    <table style="width: 100%; border-collapse: collapse;">
        @foreach($categories as $category)
            <tr style="border-bottom: 1px solid #eee;">
                <td style="padding: 10px;">{{ $category->name }}</td>
                <td style="padding: 10px; text-align: left;">
                    <form action="{{ route('vendor.categories.destroy', $category->id) }}" method="POST">
                        @csrf @method('DELETE')
                        <button type="submit" style="color: red; border: none; background: none; cursor: pointer;">حذف</button>
                    </form>
                </td>
            </tr>
        @endforeach
    </table>
</div>
@endsection