@extends('layout.admin')

@section('content')

<div class="container-fluid">


<div class="card">

    <div class="card-header">

        <h4>

            Manage Retailer Modules

        </h4>

        <p class="mb-0">

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

            <div class="row">

                @foreach($modules as $module)

                    <div class="col-md-4 mb-3">

                        <div class="form-check">

                            <input
                                type="checkbox"
                                class="form-check-input"
                                name="modules[]"
                                value="{{ $module->id }}"
                                id="module_{{ $module->id }}"
                                {{ in_array($module->id, $assignedModules) ? 'checked' : '' }}
                            >

                            <label
                                class="form-check-label"
                                for="module_{{ $module->id }}"
                            >

                                {{ $module->name }}

                            </label>

                        </div>

                    </div>

                @endforeach

            </div>

            <div class="mt-3">

                <button
                    type="submit"
                    class="btn btn-success"
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
