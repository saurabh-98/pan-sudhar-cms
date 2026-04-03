@extends('layout.admin')

@section('content')

<style>
.page-header { display:flex; justify-content:space-between; margin-bottom:20px; }
.card-box { background:#fff; border-radius:16px; padding:20px; box-shadow:0 10px 30px rgba(0,0,0,0.05); }
.upi-grid { display:grid; grid-template-columns:1fr 2fr; gap:20px; }
@media(max-width:768px){ .upi-grid { grid-template-columns:1fr; } }
.badge-active { background:#28a745; color:#fff; padding:6px 12px; border-radius:20px; }
</style>

<div class="container">

    <div class="page-header">
        <h3>💳 UPI Settings</h3>
    </div>

    <div class="upi-grid">

        <!-- FORM -->
        <div class="card-box">
            <h5>➕ Add UPI</h5>

            <form id="upiForm">
                @csrf
                <input type="text" name="upi_id" class="form-control mb-3" placeholder="UPI ID" required>
                <input type="text" name="name" class="form-control mb-3" placeholder="Name">
                <button class="btn btn-primary w-100">Add UPI</button>
            </form>
        </div>

        <!-- TABLE -->
        <div class="card-box">
            <h5>📋 UPI List</h5>

            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>UPI ID</th>
                            <th>Name</th>
                            <th>Status</th>
                            <th class="text-end">Action</th>
                        </tr>
                    </thead>
                    <tbody id="upiTable"></tbody>
                </table>
            </div>
        </div>

    </div>

</div>

@endsection


@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

// LOAD DATA
function loadUPI(){
    $.get("{{ route('admin.upi.index') }}?ajax=1", function(res){

        let html = '';

        res.forEach(upi => {

            html += `
            <tr>

                <td><strong>${upi.upi_id}</strong></td>
                <td>${upi.name ?? '-'}</td>

                <td>
                    ${upi.is_active 
                        ? `<span class="badge-active">Active</span>` 
                        : `<span class="text-muted">Inactive</span>`}
                </td>

                <td class="text-end">

                    ${!upi.is_active
                        ? `<button class="btn btn-warning btn-sm activateBtn" data-id="${upi.id}">Activate</button>`
                        : `<button class="btn btn-success btn-sm" disabled>Active</button>`
                    }

                    <button class="btn btn-info btn-sm editBtn"
                        data-id="${upi.id}"
                        data-upi="${upi.upi_id}"
                        data-name="${upi.name}">
                        Edit
                    </button>

                    <button class="btn btn-danger btn-sm deleteBtn" data-id="${upi.id}">
                        Delete
                    </button>

                </td>

            </tr>
            `;
        });

        $('#upiTable').html(html);
    });
}


// ADD
$('#upiForm').submit(function(e){
    e.preventDefault();

    $.post("{{ route('admin.upi.store') }}", $(this).serialize(), function(){
        Swal.fire('Success','UPI Added','success');
        $('#upiForm')[0].reset();
        loadUPI();
    });
});


// ACTIVATE
$(document).on('click','.activateBtn',function(){

    let id = $(this).data('id');

    $.get(`/admin/upi/activate/${id}`, function(){
        Swal.fire('Activated','UPI Activated','success');
        loadUPI();
    });
});


// EDIT
$(document).on('click','.editBtn',function(){

    let id = $(this).data('id');
    let upi = $(this).data('upi');
    let name = $(this).data('name');

    Swal.fire({
        title:'Edit UPI',
        html: `
            <input id="upi_id" class="swal2-input" value="${upi}">
            <input id="name" class="swal2-input" value="${name ?? ''}">
        `,
        preConfirm: () => {
            return {
                upi_id: document.getElementById('upi_id').value,
                name: document.getElementById('name').value
            }
        }
    }).then(res => {

        if(res.isConfirmed){

            $.ajax({
                url:`/admin/upi/update/${id}`,
                method:'POST',
                data:{
                    _token:"{{ csrf_token() }}",
                    ...res.value
                },
                success:function(){
                    Swal.fire('Updated','UPI Updated','success');
                    loadUPI();
                }
            });

        }

    });

});


// DELETE
$(document).on('click','.deleteBtn',function(){

    let id = $(this).data('id');

    Swal.fire({
        title:'Delete UPI?',
        icon:'warning',
        showCancelButton:true
    }).then(res=>{

        if(res.isConfirmed){

            $.ajax({
                url:`/admin/upi/delete/${id}`,
                method:'POST',
                data:{ _token:"{{ csrf_token() }}" },
                success:function(){
                    Swal.fire('Deleted','UPI removed','success');
                    loadUPI();
                }
            });

        }

    });

});


// INIT
loadUPI();

</script>

@endsection