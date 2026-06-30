@extends('layout.retailer')

@section('title','PAN Find Service')

@section('content')

<div class="container-fluid py-4">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

                <div class="card-header bg-primary text-white p-4">

                    <div class="d-flex align-items-center">

                        <div class="service-icon me-3">

                            <i class="fas fa-search"></i>

                        </div>

                        <div>

                            <h3 class="mb-1">

                                PAN Find Service

                            </h3>

                            <p class="mb-0 opacity-75">

                                Search PAN using Aadhaar Number

                            </p>

                        </div>

                    </div>

                </div>

                <div class="card-body p-5">

                    <div class="row mb-4">

                        <div class="col-md-6">

                            <div class="charge-card">

                                <small>

                                    Service Charge

                                </small>

                                <h2>

                                    ₹ {{ number_format($charge->value ?? 0,2) }}

                                </h2>

                            </div>

                        </div>

                        <div class="col-md-6">

                            <div class="wallet-card">

                                <small>

                                    Wallet Balance

                                </small>

                                <h2>

                                    ₹ {{ number_format(auth()->user()->wallet_balance,2) }}

                                </h2>

                            </div>

                        </div>

                    </div>

                    <form

                        id="panFindForm"

                        method="POST"

                        action="{{ route('retailer.pan-find.store') }}"

                    >

                        @csrf

                        <div class="mb-4">

                            <label class="form-label fw-bold">

                                Aadhaar Number

                                <span class="text-danger">*</span>

                            </label>

                            <input

                                type="text"

                                name="aadhaar_number"

                                id="aadhaar_number"

                                maxlength="12"

                                class="form-control form-control-lg"

                                placeholder="Enter 12 Digit Aadhaar Number"

                                autocomplete="off"

                            >

                            <div

                                class="invalid-feedback"

                                id="aadhaar_error"

                            ></div>

                        </div>

                        <div class="alert alert-warning">

                            <div class="d-flex">

                                <div class="me-3">

                                    <i class="fas fa-wallet fa-lg"></i>

                                </div>

                                <div>

                                    Wallet amount will be deducted immediately after submission.

                                    <br>

                                    Please verify the Aadhaar Number before continuing.

                                </div>

                            </div>

                        </div>

                        <div class="text-center mt-4">

                            <button

                                type="submit"

                                id="submitBtn"

                                class="btn btn-primary btn-lg px-5"

                            >

                                <i class="fas fa-search me-2"></i>

                                Find PAN

                            </button>

                            <button

                                type="reset"

                                class="btn btn-light btn-lg px-5 ms-2"

                            >

                                <i class="fas fa-undo me-2"></i>

                                Reset

                            </button>

                            <a

                                href="{{ route('retailer.pan-find.history') }}"

                                class="btn btn-dark btn-lg px-5 ms-2"

                            >

                                <i class="fas fa-history me-2"></i>

                                History

                            </a>

                        </div>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection

@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

$(document).ready(function () {

    /*
    |--------------------------------------------------------------------------
    | Aadhaar Validation
    |--------------------------------------------------------------------------
    */

    $("#aadhaar_number").on("input", function () {

        let value = $(this).val();

        value = value.replace(/\D/g, "");

        value = value.substring(0, 12);

        $(this).val(value);

    });

    /*
    |--------------------------------------------------------------------------
    | Submit Form
    |--------------------------------------------------------------------------
    */

    $("#panFindForm").submit(function (e) {

        e.preventDefault();

        $(".is-invalid").removeClass("is-invalid");

        $(".invalid-feedback").html("");

        Swal.fire({

            title: "Are you sure?",

            html:
                "Your wallet will be charged <b>₹" +
                $(".charge-card h2").text() +
                "</b><br><br>Do you want to continue?",

            icon: "warning",

            showCancelButton: true,

            confirmButtonText: "Yes, Continue",

            cancelButtonText: "Cancel",

            confirmButtonColor: "#0d6efd",

            cancelButtonColor: "#dc3545",

            reverseButtons: true

        }).then(function (result) {

            if (!result.isConfirmed) {

                return;

            }

            let btn = $("#submitBtn");

            btn.prop("disabled", true);

            btn.html(

                '<span class="spinner-border spinner-border-sm me-2"></span>Please Wait...'

            );

            $.ajax({

                url: $("#panFindForm").attr("action"),

                type: "POST",

                data: $("#panFindForm").serialize(),

                dataType: "json",

                success: function (response) {

                    btn.prop("disabled", false);

                    btn.html(

                        '<i class="fas fa-search me-2"></i>Find PAN'

                    );

                    Swal.fire({

                        icon: "success",

                        title: "Success",

                        text: response.message,

                        confirmButtonColor: "#198754"

                    }).then(function () {

                        if (response.redirect) {

                            window.location.href = response.redirect;

                        } else {

                            window.location.reload();

                        }

                    });

                },

                error: function (xhr) {

                    btn.prop("disabled", false);

                    btn.html(

                        '<i class="fas fa-search me-2"></i>Find PAN'

                    );

                    /*
                    |--------------------------------------------------------------------------
                    | Validation Error
                    |--------------------------------------------------------------------------
                    */

                    if (xhr.status === 422) {

                        let errors = xhr.responseJSON.errors;

                        $.each(errors, function (key, value) {

                            $("#" + key)

                                .addClass("is-invalid");

                            $("#" + key + "_error")

                                .html(value[0]);

                        });

                        Swal.fire({

                            icon: "error",

                            title: "Validation Error",

                            text: "Please correct the highlighted fields."

                        });

                        return;

                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Custom Error
                    |--------------------------------------------------------------------------
                    */

                    if (xhr.responseJSON && xhr.responseJSON.message) {

                        Swal.fire({

                            icon: "error",

                            title: "Oops...",

                            text: xhr.responseJSON.message

                        });

                        return;

                    }

                    /*
                    |--------------------------------------------------------------------------
                    | Server Error
                    |--------------------------------------------------------------------------
                    */

                    Swal.fire({

                        icon: "error",

                        title: "Server Error",

                        text: "Something went wrong. Please try again."

                    });

                }

            });

        });

    });

});

</script>

@endsection



