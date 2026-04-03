@extends('layout.app')

@section('content')

<h2>Menu</h2>

<input type="text" id="name" placeholder="Item Name" class="form-control mb-2">
<input type="number" id="price" placeholder="Price" class="form-control mb-2">

<button class="btn btn-success" onclick="addMenu()">Add Menu</button>

<div id="menuList"></div>

<script>
function addMenu() {
    $.ajax({
        url: "/api/menu",
        type: "POST",
        data: {
            name: $("#name").val(),
            price: $("#price").val()
        },
        success: function() {
            alert("Added");
        }
    });
}
</script>

@endsection