@extends('layout.app')

@section('content')

<h2>Add Menu</h2>

<form method="POST" action="/admin/menus" enctype="multipart/form-data">
    @csrf

    <input type="text" name="name" class="form-control mb-2" placeholder="Menu Name">

    <input type="number" name="price" class="form-control mb-2" placeholder="Price">

    <select name="category_id" class="form-control mb-2">
        <option value="">Select Category</option>

        @foreach($categories as $cat)
            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
        @endforeach

    </select>

    <input type="file" name="image" class="form-control mb-2">

    <button class="btn btn-success">Save</button>

</form>

@endsection