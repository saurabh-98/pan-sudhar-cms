@extends('layout.admin')

@section('content')

<div class="container-fluid">

    <!-- HEADER -->
    <div class="page-header mb-3">
        <h3>🔗 Manage Footer Links</h3>
        <p>Add, update and organize footer links</p>
    </div>

    <div class="row g-4">

        <!-- FORM -->
        <div class="col-lg-4">
            <div class="modern-card">

                <h5 class="mb-3">➕ Add / Edit Footer Link</h5>

                <form id="footerForm">
                    @csrf
                    <input type="hidden" id="link_id">

                    <div class="mb-3">
                        <label>Section</label>
                        <select name="section" class="form-control modern-input">
                            <option value="quick_links">Quick Links</option>
                            <option value="services">Services</option>
                            <option value="support">Support</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Link Name</label>
                        <input type="text" name="name" class="form-control modern-input" placeholder="Enter name">
                    </div>

                    <div class="mb-3">
                        <label>URL</label>
                        <input type="text" name="url" class="form-control modern-input" placeholder="/about">
                    </div>

                    <div class="mb-3">
                        <label>Sort Order</label>
                        <input type="number" name="sort_order" class="form-control modern-input" value="0">
                    </div>

                    <button class="btn btn-gradient w-100" id="saveBtn">
                        💾 Save Link
                    </button>

                </form>

            </div>
        </div>

        <!-- TABLE -->
        <div class="col-lg-8">
            <div class="modern-card">

                <h5 class="mb-3">📋 Footer Links List</h5>

                <div class="table-responsive">
                    <table id="footerTable" class="modern-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Section</th>
                                <th>Name</th>
                                <th>URL</th>
                                <th>Order</th>
                                <th width="150">Action</th>
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

    /* DATATABLE */
    let table = $('#footerTable').DataTable({
        ajax: {
            url: "{{ route('admin.footer.list') }}",
            dataSrc: ''
        },
        columns: [
            { data: 'id' },
            { data: 'section' },
            { data: 'name' },
            { data: 'url' },
            { data: 'sort_order' },
            {
                data: null,
                render: function(row){
                    return `
                        <button class="btn btn-sm btn-edit editBtn"
                            data-id="${row.id}"
                            data-section="${row.section}"
                            data-name="${row.name}"
                            data-url="${row.url}"
                            data-order="${row.sort_order}">
                            Edit
                        </button>

                        <button class="btn btn-sm btn-delete deleteBtn"
                            data-id="${row.id}">
                            Delete
                        </button>
                    `;
                }
            }
        ]
    });

    /* EDIT */
    $(document).on('click','.editBtn',function(){
        $('#link_id').val($(this).data('id'));
        $('[name="section"]').val($(this).data('section'));
        $('[name="name"]').val($(this).data('name'));
        $('[name="url"]').val($(this).data('url'));
        $('[name="sort_order"]').val($(this).data('order'));

        Swal.fire('Edit Mode','Update and save','info');
    });

    /* SAVE */
    $('#footerForm').submit(function(e){
        e.preventDefault();

        let btn = $('#saveBtn');
        btn.prop('disabled', true).text('Saving...');

        let id = $('#link_id').val();

        let url = id
            ? "{{ route('admin.footer.update', ':id') }}".replace(':id', id)
            : "{{ route('admin.footer.store') }}";

        let formData = $(this).serialize();

        $.post(url, formData, function(){
            Swal.fire('Success','Saved successfully','success');

            $('#footerForm')[0].reset();
            $('#link_id').val('');

            table.ajax.reload();
            btn.prop('disabled', false).text('Save Link');
        }).fail(function(err){

            Swal.fire('Error','Something went wrong','error');
            btn.prop('disabled', false).text('Save Link');

        });

    });

    /* DELETE */
    $(document).on('click','.deleteBtn',function(){

        let id = $(this).data('id');

        Swal.fire({
            title:'Delete this link?',
            icon:'warning',
            showCancelButton:true,
            confirmButtonColor:'#ff5722'
        }).then((res)=>{

            if(res.isConfirmed){

                $.post("{{ route('admin.footer.delete', ':id') }}".replace(':id', id), {
                    _token: "{{ csrf_token() }}",
                    _method: "DELETE"
                }, function(){
                    Swal.fire('Deleted','','success');
                    table.ajax.reload();
                });

            }

        });

    });

});
</script>

@endsection