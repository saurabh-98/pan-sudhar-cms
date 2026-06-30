@extends('layout.admin')

@section('content')

<div class="container-fluid">

    <h3 class="mb-4 fw-bold">Notice Board</h3>

    <div class="row g-4 align-items-stretch">

        <!-- FORM -->
        <div class="col-lg-4 d-flex">
            <div class="card w-100 p-3">

                <h5 class="mb-3">Add / Update Notice</h5>

                <form id="noticeForm">
                    @csrf

                    <input type="hidden" id="notice_id">

                    <div class="mb-3">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Description</label>
                        <textarea name="description" class="form-control" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label>Publish Date</label>
                        <input type="date" name="publish_date" class="form-control">
                    </div>

                    <div class="mb-3">
                        <label>Expiry Date</label>
                        <input type="date" name="expiry_date" class="form-control">
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Save Notice</button>

                    <button type="button" id="resetBtn" class="btn btn-secondary w-100 mt-2">
                        Reset
                    </button>

                </form>

            </div>
        </div>

        <!-- TABLE -->
        <div class="col-lg-8 d-flex">
            <div class="card w-100 p-3">

                <h5 class="mb-3">Notice List</h5>

                <div class="table-responsive">
                    <table id="noticeTable" class="table table-bordered w-100">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Publish</th>
                                <th>Expiry</th>
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

    let table = $('#noticeTable').DataTable({
        processing: true,
        serverSide: true,

        ajax: {
            url: "{{ route('admin.notice.index') }}",
            data: function(d){
                d.page = (d.start / d.length) + 1;
            }
        },

        columns: [
            { data: 'id' },
            { data: 'title' },
            { data: 'description' },

            {
                data: 'publish_date',
                render: function(data){
                    if(!data) return '-';
                    return new Date(data).toLocaleDateString('en-GB');
                }
            },
            {
                data: 'expiry_date',
                render: function(data){
                    if(!data) return '<span class="badge bg-success">No Expiry</span>';

                    let today = new Date();
                    let expiry = new Date(data);

                    return expiry >= today
                        ? '<span class="badge bg-success">Active</span>'
                        : '<span class="badge bg-danger">Expired</span>';
                }
            },

            {
                data: null,
                orderable: false,
                render: function(row){
                    return `
                        <button class="btn btn-sm btn-warning editBtn"
                            data-id="${row.id}"
                            data-title="${row.title}"
                            data-description="${row.description}"
                            data-publish="${row.publish_date}"
                            data-expiry="${row.expiry_date}">
                            Edit
                        </button>

                        <button class="btn btn-sm btn-danger deleteBtn"
                            data-id="${row.id}">
                            Delete
                        </button>
                    `;
                }
            }
        ]
    });

    /* ================= DATE FORMAT ================= */
    function formatDateForInput(dateString){
        if(!dateString) return '';
        let d = new Date(dateString);
        return d.toISOString().split('T')[0];
    }

    /* ================= EDIT ================= */
    $(document).on('click','.editBtn',function(){

        $('#notice_id').val($(this).data('id'));
        $('input[name="title"]').val($(this).data('title'));
        $('textarea[name="description"]').val($(this).data('description'));
        $('input[name="publish_date"]').val(formatDateForInput($(this).data('publish')));
        $('input[name="expiry_date"]').val(formatDateForInput($(this).data('expiry')));

        Swal.fire('Edit Mode','','info');
    });

    /* ================= RESET ================= */
    $('#resetBtn').click(function(){
        $('#noticeForm')[0].reset();
        $('#notice_id').val('');
    });

    /* ================= SAVE ================= */
    $('#noticeForm').submit(function(e){
        e.preventDefault();

        let id = $('#notice_id').val();

        let url = id
            ? "{{ route('admin.notice.update', ':id') }}".replace(':id', id)
            : "{{ route('admin.notice.store') }}";

        Swal.fire({
            title: 'Confirm?',
            text: 'Save this notice?',
            icon: 'question',
            showCancelButton: true
        }).then((result)=>{

            if(result.isConfirmed){

                $.ajax({
                    url: url,
                    method: "POST",
                    data: $('#noticeForm').serialize(),

                    beforeSend: function(){
                        $('button[type="submit"]').text('Saving...');
                    },
                    complete: function(){
                        $('button[type="submit"]').text('Save Notice');
                    },

                    success: function(){
                        Swal.fire('Success','Saved successfully','success');
                        $('#noticeForm')[0].reset();
                        $('#notice_id').val('');
                        table.ajax.reload();
                    },
                    error: function(){
                        Swal.fire('Error','Something went wrong','error');
                    }
                });

            }

        });

    });

    /* ================= DELETE ================= */
    $(document).on('click','.deleteBtn',function(){

        let id = $(this).data('id');

        Swal.fire({
            title: 'Delete?',
            icon: 'warning',
            showCancelButton: true
        }).then((result)=>{

            if(result.isConfirmed){

                $.ajax({
                    url: "{{ route('admin.notice.delete', ':id') }}".replace(':id', id),
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        _method: "DELETE"
                    },
                    success: function(){
                        table.ajax.reload();
                        Swal.fire('Deleted','','success');
                    }
                });

            }

        });

    });

});
</script>

@endsection