@extends('layout.app')

@section('content')

<h2>Add Category</h2>

<form method="POST" action="/admin/categories" enctype="multipart/form-data">
    @csrf

    <input type="text" name="name" class="form-control mb-2" placeholder="Name">
    <input type="file" name="image" class="form-control mb-2">

    <button class="btn btn-success">Save</button>
</form>

@endsection