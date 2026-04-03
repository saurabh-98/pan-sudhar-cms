@extends('layout.admin')

@section('content')

@php
    $isEdit = isset($user);
@endphp

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body p-4">

                    <h3 class="text-center mb-4">
                        👤 {{ $isEdit ? 'Edit User' : 'Create User' }}
                    </h3>

                    <!-- SUCCESS -->
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <!-- ERRORS -->
                    @if($errors->any())
                        <div class="alert alert-danger">
                            @foreach($errors->all() as $error)
                                <div>{{ $error }}</div>
                            @endforeach
                        </div>
                    @endif

                    <form id="userForm"
                          method="POST"
                          action="{{ $isEdit ? route('admin.users.update',$user->id) : route('admin.users.store') }}"
                          autocomplete="off">
                        @csrf

                        @if($isEdit)
                            @method('POST')
                        @endif

                        <!-- NAME -->
                        <div class="form-floating mb-3">
                            <input type="text" name="name"
                                class="form-control"
                                value="{{ $isEdit ? old('name', $user->name) : '' }}"
                                required>
                            <label>Name</label>
                        </div>

                        <!-- EMAIL -->
                        <div class="form-floating mb-3">
                            <input type="email" name="email"
                                class="form-control"
                                value="{{ $isEdit ? old('email', $user->email) : '' }}"
                                required>
                            <label>Email</label>
                        </div>

                        <!-- PASSWORD (NEVER PREFILL) -->
                        <div class="form-floating mb-3">
                            <input type="password" name="password"
                                class="form-control"
                                placeholder="Password">
                            <label>
                                {{ $isEdit ? 'New Password (optional)' : 'Password' }}
                            </label>
                        </div>

                        <!-- ROLE -->
                        <div class="mb-3">
                            <label>Select Role</label>
                            <select name="role" class="form-select" required>
                                <option value="">-- Select Role --</option>

                                <option value="admin"
                                    {{ $isEdit && $user->role == 'admin' ? 'selected' : '' }}>
                                    👑 Admin
                                </option>

                                <option value="staff"
                                    {{ $isEdit && $user->role == 'staff' ? 'selected' : '' }}>
                                    👨‍🍳 Staff
                                </option>

                                <option value="customer"
                                    {{ $isEdit && $user->role == 'customer' ? 'selected' : '' }}>
                                    🛒 Customer
                                </option>

                            </select>
                        </div>

                       

                        <button class="btn btn-success w-100">
                            {{ $isEdit ? 'Update User' : 'Create User' }}
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

@endsection

@section('scripts')

<script>
$(document).ready(function(){

    // CREATE / UPDATE CONFIRM
    $('#userForm').submit(function(e){
        e.preventDefault();

        Swal.fire({
            title: "Are you sure?",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Yes"
        }).then((result)=>{
            if(result.isConfirmed){
                e.currentTarget.submit();
            }
        });
    });

   

});
</script>

@endsection