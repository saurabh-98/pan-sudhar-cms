@extends('layout.admin')

@section('title','Profit Partner Management')

@section('styles')

<link rel="stylesheet"
      href="{{ asset('assets/admin/css/charge-management.css') }}">

@endsection

@section('content')

<div class="container-fluid chx-wrapper">

    {{--==========================
        PAGE HEADER
    ==========================--}}

    <div class="chx-page-header">

        <div>

            <h2>

                <i class="fas fa-handshake me-2"></i>

                Profit Partner Management

            </h2>

            <p>

                Manage Business Profit Sharing Partners

            </p>

        </div>

    </div>

    <div class="row g-4 align-items-start">

        {{--==========================
            FORM
        ==========================--}}

        <div class="col-xl-4 col-lg-5">

            <div class="card chx-card chx-form-card">

                <div class="card-header chx-card-header">

                    <h5>

                        Partner Management

                    </h5>

                </div>

                <div class="card-body">

                    <form id="partnerForm">

                        @csrf

                        <input
                            type="hidden"
                            id="partner_id"
                            name="partner_id"
                        >

                        {{-- Partner Name --}}

                        <div class="mb-3">

                            <label class="chx-label">

                                Partner Name

                            </label>

                            <input
                                type="text"
                                name="partner_name"
                                class="form-control chx-input"
                                placeholder="Enter Partner Name"
                                required
                            >

                            <small class="text-danger error-partner_name"></small>

                        </div>

                        {{-- Percentage --}}

                        <div class="mb-3">

                            <label class="chx-label">

                                Profit Percentage

                            </label>

                            <div class="input-group">

                                <input
                                    type="number"
                                    step="0.01"
                                    min="0"
                                    max="100"
                                    name="profit_percentage"
                                    class="form-control chx-input"
                                    placeholder="25"
                                    required
                                >

                                <span class="input-group-text">

                                    %

                                </span>

                            </div>

                            <small class="text-danger error-profit_percentage"></small>

                        </div>

                        {{-- Status --}}

                        <div class="mb-4">

                            <label class="chx-label">

                                Status

                            </label>

                            <select
                                name="status"
                                class="form-control chx-input"
                            >

                                <option value="1">

                                    Active

                                </option>

                                <option value="0">

                                    Inactive

                                </option>

                            </select>

                        </div>

                        <button
                            type="submit"
                            id="saveBtn"
                            class="btn chx-btn-primary w-100"
                        >

                            <i class="fas fa-save me-2"></i>

                            Save Partner

                        </button>

                    </form>

                </div>

            </div>

        </div>
                {{--==========================
            TABLE SECTION
        ==========================--}}

        <div class="col-xl-8 col-lg-7">

            <div class="card chx-card">

                <div class="card-header chx-card-header">

                    <div class="d-flex justify-content-between align-items-center">

                        <h5 class="mb-0">

                            <i class="fas fa-list me-2"></i>

                            Profit Partners List

                        </h5>

                        <span class="badge bg-primary">

                            Dynamic Percentage Management

                        </span>

                    </div>

                </div>

                <div class="card-body">

                    <div class="table-responsive">

                        <table
                            id="partnerTable"
                            class="table chx-table table-hover align-middle"
                            width="100%"
                        >

                            <thead>

                                <tr>

                                    <th width="60">

                                        ID

                                    </th>

                                    <th>

                                        Partner Name

                                    </th>

                                    <th width="150">

                                        Profit %

                                    </th>

                                    <th width="120">

                                        Status

                                    </th>

                                    <th width="150">

                                        Created

                                    </th>

                                    <th width="130">

                                        Action

                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

@section('scripts')

<link
    rel="stylesheet"
    href="https://cdn.datatables.net/1.13.8/css/jquery.dataTables.min.css"
/>

<script src="https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

$(function(){

    $.ajaxSetup({

        headers:{

            'X-CSRF-TOKEN':'{{ csrf_token() }}'

        }

    });

    /*
    |--------------------------------------------------------------------------
    | DATATABLE
    |--------------------------------------------------------------------------
    */

    const table = $('#partnerTable').DataTable({

        processing:true,

        serverSide:true,

        responsive:true,

        ajax:"{{ route('admin.profit-partners.list') }}",

        columns:[

            {
                data:'id',
                name:'id'
            },

            {
                data:'partner_name',
                name:'partner_name'
            },

            {
                data:'profit_percentage',
                name:'profit_percentage'
            },

            {
                data:'status',
                name:'status',
                orderable:false,
                searchable:false
            },

            {
                data:'created_at',
                name:'created_at'
            },

            {
                data:'action',
                name:'action',
                orderable:false,
                searchable:false
            }

        ],

        order:[[0,'desc']],

        pageLength:10

    });

        /*
    |--------------------------------------------------------------------------
    | STORE / UPDATE PARTNER
    |--------------------------------------------------------------------------
    */

    $('#partnerForm').on('submit', function(e){

        e.preventDefault();

        $('.text-danger').html('');

        let id = $('#partner_id').val();

        let url = id
            ? "{{ url('admin/profit-partners/update') }}/"+id
            : "{{ route('admin.profit-partners.store') }}";

        $('#saveBtn')
            .prop('disabled',true)
            .html('<i class="fa fa-spinner fa-spin"></i> Processing...');

        $.ajax({

            url:url,

            type:'POST',

            data:$(this).serialize(),

            success:function(response){

                $('#partnerForm')[0].reset();

                $('#partner_id').val('');

                $('#saveBtn')
                    .prop('disabled',false)
                    .html('<i class="fas fa-save me-2"></i> Save Partner');

                $('.text-danger').html('');

                table.ajax.reload(null,false);

                Swal.fire({

                    icon:'success',

                    title:'Success',

                    text:response.message,

                    timer:2000,

                    showConfirmButton:false

                });

            },

            error:function(xhr){

                $('#saveBtn')
                    .prop('disabled',false)
                    .html('<i class="fas fa-save me-2"></i> Save Partner');

                if(xhr.status===422){

                    $.each(xhr.responseJSON.errors,function(key,value){

                        $('.error-'+key).html(value[0]);

                    });

                }else{

                    Swal.fire({

                        icon:'error',

                        title:'Error',

                        text:'Something went wrong.'

                    });

                }

            }

        });

    });

    /*
    |--------------------------------------------------------------------------
    | EDIT
    |--------------------------------------------------------------------------
    */

    $(document).on('click','.editPartner',function(){

        let id=$(this).data('id');

        $.ajax({

            url:"{{ url('admin/profit-partners/edit') }}/"+id,

            type:'GET',

            success:function(res){

                $('#partner_id').val(res.id);

                $('[name="partner_name"]').val(res.partner_name);

                $('[name="profit_percentage"]').val(res.profit_percentage);

                $('[name="status"]').val(res.status);

                $('#saveBtn').html(

                    '<i class="fas fa-edit me-2"></i> Update Partner'

                );

                $('html,body').animate({

                    scrollTop:0

                },500);

            },

            error:function(){

                Swal.fire({

                    icon:'error',

                    title:'Error',

                    text:'Unable to load partner.'

                });

            }

        });

    });

    /*
    |--------------------------------------------------------------------------
    | DELETE
    |--------------------------------------------------------------------------
    */

    $(document).on('click','.deletePartner',function(){

        let id=$(this).data('id');

        Swal.fire({

            title:'Delete Partner?',

            text:'This action cannot be undone.',

            icon:'warning',

            showCancelButton:true,

            confirmButtonColor:'#dc3545',

            cancelButtonColor:'#6c757d',

            confirmButtonText:'Yes, Delete'

        }).then((result)=>{

            if(result.isConfirmed){

                $.ajax({

                    url:"{{ url('admin/profit-partners/delete') }}/"+id,

                    type:'DELETE',

                    data:{
                        _token:'{{ csrf_token() }}'
                    },

                    success:function(response){

                        table.ajax.reload(null,false);

                        Swal.fire({

                            icon:'success',

                            title:'Deleted',

                            text:response.message

                        });

                    },

                    error:function(){

                        Swal.fire({

                            icon:'error',

                            title:'Error',

                            text:'Unable to delete partner.'

                        });

                    }

                });

            }

        });

    });

});
</script>

@endsection
