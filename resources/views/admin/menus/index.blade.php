@extends('layout.admin')

@section('content')

<div class="container-fluid">

    <h3 class="mb-4 fw-bold">Manage Menus</h3>

    <div class="row g-4 align-items-stretch">

        <!-- FORM -->
        <div class="col-lg-4 d-flex">
            <div class="modern-card w-100">

                <h5 class="mb-3">Add / Edit Menu</h5>

                <form id="menuForm" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" id="menu_id">

                    <!-- Name -->
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control modern-input">
                    </div>

                    <!-- Price -->
                    <div class="mb-3">
                        <label class="form-label">Price</label>
                        <input type="number" name="price" class="form-control modern-input">
                    </div>

                    <!-- Category -->
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-control modern-input">
                            <option value="">Select Category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Image -->
                    <div class="mb-3">
                        <label class="form-label">Image</label>
                        <input type="file" name="image" class="form-control modern-input" id="imageInput">
                    </div>

                    <!-- Preview -->
                    <div class="mb-3 text-center">
                        <img id="previewImage" class="table-img" style="display:none;">
                    </div>

                    <!-- Description -->
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control modern-input" rows="3"></textarea>
                    </div>

                    <!-- Specifications -->
                    <div class="mb-3">
                        <label class="form-label">Specifications</label>
                        <textarea name="specifications" class="form-control modern-input" rows="2"
                            placeholder="Spicy: Medium | Serve: 2 | Type: Veg"></textarea>
                    </div>

                    <button class="btn btn-gradient w-100">
                        Save Menu
                    </button>

                </form>

            </div>
        </div>

        <!-- TABLE -->
        <div class="col-lg-8 d-flex">
            <div class="modern-card w-100">

                <h5 class="mb-3">Menu List</h5>

                <div class="table-responsive">
                    <table id="menuTable" class="modern-table w-100">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Category</th>
                                <th>Description</th>
                                <th>Specs</th>
                                <th>Image</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
        </div>

    </div>

</div>

@endsection

@section('scripts')

<script>
$(document).ready(function(){

    // IMAGE PREVIEW
    $('#imageInput').change(function(e){
        let reader = new FileReader();
        reader.onload = function(e){
            $('#previewImage').attr('src', e.target.result).show();
        }
        reader.readAsDataURL(this.files[0]);
    });

    // DATATABLE
    let table = $('#menuTable').DataTable({
        ajax: {
            url: "{{ route('admin.menus.list') }}",
            dataSrc: 'data'
        },
        responsive: true,
        autoWidth: false,
        columns: [
            { data: 'id' },
            { data: 'name' },
            { data: 'price', render: data => `<strong>₹${data}</strong>` },
            { data: 'category.name' },

            {
                data: 'description',
                render: data => data ? data.substring(0, 40)+'...' : 'N/A'
            },

            {
                data: 'specifications',
                render: function(data){
                    if(!data) return 'N/A';
                    return data.split('|').map(s => `<span class="badge bg-info me-1">${s}</span>`).join('');
                }
            },

            {
                data: 'image',
                render: function(data){
                    return `<img src="/storage/${data}" class="table-img">`;
                }
            },

            {
                data: null,
                orderable: false,
                render: function(row){
                    return `
                        <button class="btn btn-edit editBtn"
                            data-id="${row.id}"
                            data-name="${row.name}"
                            data-price="${row.price}"
                            data-category="${row.category_id}"
                            data-description="${row.description ?? ''}"
                            data-specifications="${row.specifications ?? ''}">
                            Edit
                        </button>

                        <button class="btn btn-delete deleteBtn"
                            data-id="${row.id}">
                            Delete
                        </button>
                    `;
                }
            }
        ]
    });

    // EDIT
    $(document).on('click','.editBtn',function(){

        $('#menu_id').val($(this).data('id'));
        $('input[name="name"]').val($(this).data('name'));
        $('input[name="price"]').val($(this).data('price'));
        $('select[name="category_id"]').val($(this).data('category'));

        $('textarea[name="description"]').val($(this).data('description'));
        $('textarea[name="specifications"]').val($(this).data('specifications'));

        Swal.fire('Edit Mode','Update menu','info');
    });

    // SAVE
    $('#menuForm').submit(function(e){
        e.preventDefault();

        let id = $('#menu_id').val();

        let url = id 
            ? "{{ route('admin.menus.update', ':id') }}".replace(':id', id)
            : "{{ route('admin.menus.store') }}";

        let formData = new FormData(this);

        $.ajax({
            url: url,
            method: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function(){

                Swal.fire('Success','Saved successfully','success');

                $('#menuForm')[0].reset();
                $('#menu_id').val('');
                $('#previewImage').hide();

                table.ajax.reload();
            }
        });
    });

    // DELETE
    $(document).on('click','.deleteBtn',function(){

        let id = $(this).data('id');

        Swal.fire({
            title:'Delete?',
            icon:'warning',
            showCancelButton:true
        }).then((res)=>{

            if(res.isConfirmed){

                $.ajax({
                    url: "{{ route('admin.menus.delete', ':id') }}".replace(':id', id),
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: "DELETE"
                    },
                    success: function(){
                        Swal.fire('Deleted','','success');
                        table.ajax.reload();
                    }
                });

            }

        });

    });

});
</script>

@endsection