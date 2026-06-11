@extends('layout.admin')

@section('content')

<style>

.module-card{
border:none;
border-radius:16px;
overflow:hidden;
box-shadow:0 8px 25px rgba(0,0,0,.08);
}

.module-header{
background:linear-gradient(
135deg,
#0d6efd,
#2563eb
);
color:#fff;
padding:14px 18px;
font-size:15px;
font-weight:600;
}

.module-check{
padding:8px 10px;
border-radius:10px;
transition:.3s;
}

.module-check:hover

.standalone-module{
background:#fff;
border:1px solid #e5e7eb;
border-radius:12px;
padding:14px;
}

.form-check-input{
cursor:pointer;
}

.form-check-label{
cursor:pointer;
width:100%;
}

</style>

<div class="container-fluid">

<div class="card shadow-sm border-0">

    <div class="card-header bg-white">

        <h4 class="page-title">

            Manage Retailer Modules

        </h4>

        <p class="mb-0 retailer-name">

            {{ $user->name }}

        </p>

    </div>

    <div class="card-body">

        <div
            id="moduleMessage"
            style="display:none;"
        ></div>

        <form
            id="moduleForm"
            method="POST"
            action="{{ route('admin.retailer-approvals.modules.update', $user->id) }}"
        >

            @csrf

            {{-- TOP MODULES --}}

            <div class="row mb-4">


            @foreach($modules->whereNull('parent_id') as $module)

                @if($module->children->count() == 0)

                    <div class="col-md-3 mb-3">

                        <div class="form-check standalone-module">

                            <input
                                type="checkbox"
                                class="form-check-input"
                                name="modules[]"
                                value="{{ $module->id }}"
                                id="module_{{ $module->id }}"
                                {{ in_array($module->id, $assignedModules) ? 'checked' : '' }}
                            >

                            <label
                                class="form-check-label fw-semibold"
                                for="module_{{ $module->id }}"
                            >
                                {{ $module->name }}
                            </label>

                        </div>

                    </div>

                @endif

            @endforeach


            </div>

            {{-- CATEGORY MODULES --}}

            @foreach($modules->whereNull('parent_id') as $parent)

            @if($parent->children->count() > 0)

                <div class="module-card card mb-4">

                    <div class="module-header">

                        <i class="{{ $parent->icon ?? 'fa fa-folder' }}"></i>

                        {{ $parent->name }}

                    </div>

                    <div class="card-body">

                        <div class="row">

                            @foreach($parent->children->sortBy('sort_order') as $child)

                                <div class="col-lg-4 col-md-6 mb-2">

                                    <div class="form-check module-check">

                                        <input
                                            type="checkbox"
                                            class="form-check-input"
                                            name="modules[]"
                                            value="{{ $child->id }}"
                                            id="module_{{ $child->id }}"
                                            {{ in_array($child->id, $assignedModules) ? 'checked' : '' }}
                                        >

                                        <label
                                            class="form-check-label"
                                            for="module_{{ $child->id }}"
                                        >
                                            {{ $child->name }}
                                        </label>

                                    </div>

                                </div>

                            @endforeach

                        </div>

                    </div>

                </div>

            @endif

            @endforeach

            <div class="mt-4">


            <button
                type="submit"
                class="btn btn-success save-btn"
                id="saveModulesBtn"
            >

                <i class="fa fa-save"></i>

                Update Modules

            </button>


            </div>



        </form>

    </div>

</div>


</div>

@endsection

@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

$(document).ready(function () {

    $('#moduleForm').on(
        'submit',
        function (e) {

            e.preventDefault();

            let form =
                $(this);

            let submitBtn =
                $('#saveModulesBtn');

            let checkedModules =
                $('input[name="modules[]"]:checked')
                .length;

            if (
                checkedModules === 0
            ) {

                Swal.fire({

                    icon: 'warning',

                    title: 'Module Required',

                    text:
                        'Please select at least one module.'

                });

                return false;
            }

            Swal.fire({

                title:
                    'Update Modules?',

                text:
                    'Are you sure you want to update retailer module access?',

                icon:
                    'question',

                showCancelButton:
                    true,

                confirmButtonColor:
                    '#198754',

                cancelButtonColor:
                    '#dc3545',

                confirmButtonText:
                    'Yes, Update',

                cancelButtonText:
                    'Cancel'

            }).then((result) => {

                if (
                    result.isConfirmed
                ) {

                    submitBtn

                        .prop(
                            'disabled',
                            true
                        )

                        .html(
                            '<i class="fa fa-spinner fa-spin"></i> Saving...'
                        );

                    $.ajax({

                        url:
                            form.attr(
                                'action'
                            ),

                        type:
                            'POST',

                        data:
                            form.serialize(),

                        success: function (
                            response
                        ) {

                            submitBtn

                                .prop(
                                    'disabled',
                                    false
                                )

                                .html(
                                    '<i class="fa fa-save"></i> Update Modules'
                                );

                            Swal.fire({

                                icon:
                                    'success',

                                title:
                                    'Success',

                                text:
                                    response.message
                                    ||
                                    'Modules updated successfully.',

                                confirmButtonText:
                                    'OK'

                            }).then(() => {

                                window.location.href =
                                    response.redirect;

                            });

                        },

                        error: function (
                            xhr
                        ) {

                            submitBtn

                                .prop(
                                    'disabled',
                                    false
                                )

                                .html(
                                    '<i class="fa fa-save"></i> Update Modules'
                                );

                            let message =
                                'Something went wrong.';

                            if (
                                xhr.responseJSON
                                &&
                                xhr.responseJSON.message
                            ) {

                                message =
                                    xhr.responseJSON.message;
                            }

                            if (
                                xhr.responseJSON
                                &&
                                xhr.responseJSON.errors
                            ) {

                                let errors =
                                    Object.values(
                                        xhr.responseJSON.errors
                                    )
                                    .flat();

                                message =
                                    errors.join(
                                        '<br>'
                                    );
                            }

                            Swal.fire({

                                icon:
                                    'error',

                                title:
                                    'Error',

                                html:
                                    message

                            });

                        }

                    });

                }

            });

        }
    );

});

</script>

@endsection
