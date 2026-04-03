@extends('layout.customer')

@section('content')

<div class="container">

    <h3 class="mb-4">👤 My Profile</h3>

    <div class="row">

        <!-- PROFILE UPDATE -->
        <div class="col-md-6">
            <div class="card p-3 shadow">

                <h5>Update Profile</h5>

                <!-- ✅ FORM START (IMPORTANT) -->
                <form id="profileForm" enctype="multipart/form-data">
                    @csrf

                    <!-- IMAGE (MOVED INSIDE FORM) -->
                    <div class="text-center mb-3">
                        <img id="previewImage"
                             src="{{ $user->image ? asset('uploads/'.$user->image) : 'https://ui-avatars.com/api/?name='.urlencode($user->name) }}"
                             class="rounded-circle"
                             width="120" height="120"
                             style="object-fit: cover;">

                        <!-- ✅ MUST BE INSIDE FORM -->
                        <input type="file" id="image" name="image" class="form-control mt-2">
                    </div>

                    <!-- NAME -->
                    <div class="mb-3">
                        <label>Name</label>
                        <input type="text" name="name"
                               value="{{ $user->name }}"
                               class="form-control" required>
                    </div>

                    <!-- EMAIL -->
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email"
                               value="{{ $user->email }}"
                               class="form-control" required>
                    </div>

                    <button class="btn btn-primary w-100">Update</button>

                </form>

            </div>
        </div>

        <!-- PASSWORD CHANGE -->
        <div class="col-md-6">
            <div class="card p-3 shadow">

                <h5>Change Password</h5>

                <form id="passwordForm">
                    @csrf

                    <div class="mb-3">
                        <label>Current Password</label>
                        <input type="password" name="current_password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>New Password</label>
                        <input type="password" name="new_password" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label>Confirm Password</label>
                        <input type="password" name="new_password_confirmation" class="form-control" required>
                    </div>

                    <button class="btn btn-success w-100">Change Password</button>

                </form>

            </div>
        </div>

    </div>

</div>

@endsection
@section('scripts')

<script>
$(document).ready(function(){

    // IMAGE PREVIEW
    $('#image').on('change', function(e){
        let file = e.target.files[0];

        if(file){
            let reader = new FileReader();
            reader.onload = function(e){
                $('#previewImage').attr('src', e.target.result);
            }
            reader.readAsDataURL(file);
        }
    });

    // PROFILE UPDATE (FIXED)
    $('#profileForm').submit(function(e){
    e.preventDefault();

    let form = this;

    Swal.fire({
        title: "Update Profile?",
        text: "Do you want to save these changes?",
        icon: "question",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Update"
    }).then((result) => {

        if(result.isConfirmed){

            let formData = new FormData(form);

            $.ajax({
                url: "{{ route('customer.profile.update') }}",
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,

                success: function(res){

                    Swal.fire({
                        icon: 'success',
                        title: 'Updated!',
                        text: res.message
                    });

                    // UPDATE HEADER
                    $('#headerUserName').text($('input[name="name"]').val());

                    let file = $('#image')[0].files[0];
                    if(file){
                        let reader = new FileReader();
                        reader.onload = function(e){
                            $('#headerProfileImage').attr('src', e.target.result);
                        }
                        reader.readAsDataURL(file);
                    }
                },

                error: function(err){

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: err.responseJSON?.message || 'Validation failed'
                    });
                }
            });

        }

    });

});

    // PASSWORD CHANGE
   $('#passwordForm').submit(function(e){
    e.preventDefault();

    let form = this;

    // 🔥 CONFIRMATION POPUP
    Swal.fire({
        title: "Change Password?",
        text: "Are you sure you want to update your password?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#28a745",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, Change"
    }).then((result) => {

        if(result.isConfirmed){

            // 🔄 LOADER
            Swal.fire({
                title: 'Updating...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.ajax({
                url: "{{ route('customer.profile.password') }}",
                type: "POST",
                data: $(form).serialize(),

                success: function(res){

                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: res.message
                    });

                    form.reset();
                },

                error: function(err){

                    let msg = 'Something went wrong';

                    // ✅ Handle validation errors
                    if(err.responseJSON){
                        if(err.responseJSON.message){
                            msg = err.responseJSON.message;
                        }

                        // 🔥 show first validation error
                        if(err.responseJSON.errors){
                            let firstError = Object.values(err.responseJSON.errors)[0][0];
                            msg = firstError;
                        }
                    }

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: msg
                    });
                }
            });

        }

    });

});

});
</script>

@endsection