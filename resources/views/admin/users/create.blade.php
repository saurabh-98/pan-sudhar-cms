@extends('layout.admin')

@section('content')

@php
    $isEdit = isset($user);

    $isSuperDistributor = auth()->user()->hasRole('Super Distributor');
@endphp

<div class="container">
    <div class="row justify-content-center">

        <div class="col-md-6">

            <div class="card shadow-lg border-0 rounded-4">

                <div class="card-body p-4">

                    <h3 class="text-center mb-4">

                        @if($isSuperDistributor)

                            👤 {{ $isEdit ? 'Manage Distributor' : 'Add Distributor' }}

                        @else

                            👤 {{ $isEdit ? 'Edit User' : 'Create User' }}

                        @endif

                    </h3>

                    {{-- SUCCESS --}}
                    @if(session('success'))

                        <div class="alert alert-success">

                            {{ session('success') }}

                        </div>

                    @endif


                    {{-- ERRORS --}}
                    @if($errors->any())

                        <div class="alert alert-danger">

                            @foreach($errors->all() as $error)

                                <div>{{ $error }}</div>

                            @endforeach

                        </div>

                    @endif


                   <form id="userForm"
                        method="POST"
                        action="{{ $isEdit ? route('admin.users.update', $user->id) : route('admin.users.store') }}"
                        autocomplete="off">

                        @csrf

                        {{-- Prevent Chrome Autofill --}}
                        <input type="text"
                            name="fake_username"
                            autocomplete="username"
                            style="display:none">

                        <input type="password"
                            name="fake_password"
                            autocomplete="new-password"
                            style="display:none">

                        {{-- NAME --}}
                        <div class="form-floating mb-3">

                            <input type="text"
                                id="name"
                                name="name"
                                class="form-control"
                                value="{{ old('name', $isEdit ? $user->name : '') }}"
                                autocomplete="off"
                                required>

                            <label>Name</label>

                        </div>

                        {{-- EMAIL --}}
                        <div class="form-floating mb-3">

                            <input type="email"
                                id="email"
                                name="email"
                                class="form-control"
                                value="{{ $isEdit ? old('email', $user->email) : '' }}"
                                autocomplete="new-email"
                                spellcheck="false"
                                autocorrect="off"
                                autocapitalize="off"
                                required>

                            <label>Email</label>

                        </div>

                        {{-- PASSWORD --}}
                        <div class="mb-3">

                            @if($isEdit)

                                <small class="text-muted">

                                 Leave blank if you don't want to change the password.

                                </small>

                            @endif

                            <div class="input-group">

                                <input type="password"
                                    id="password"
                                    name="password"
                                    class="form-control"
                        
                                    {{ $isEdit ? '' : 'required' }}>

                                <button class="btn btn-outline-secondary"
                                        type="button"
                                        id="togglePassword">

                                    <i class="fa fa-eye"></i>

                                </button>

                            </div>

                        </div>

                        {{-- ROLE --}}
                        <div class="mb-3">

                            <label class="form-label">

                                Select Role

                            </label>

                            <select name="role"
                                    class="form-select"
                                    required>

                                <option value="">

                                    -- Select Role --

                                </option>

                                @foreach($roles as $role)

                                    <option value="{{ $role->name }}"
                                        {{ $isEdit && $user->hasRole($role->name) ? 'selected' : '' }}>

                                        {{ $role->name }}

                                    </option>

                                @endforeach

                            </select>

                        </div>

                        {{-- STATUS --}}
                        <div class="mb-3">

                            <label class="form-label">

                                Status

                            </label>

                            <select
                                name="status"
                                class="form-select"
                                required>

                                <option value="1"

                                    {{ old(
                                        'status',
                                        $isEdit ? $user->status : 1
                                    ) == 1 ? 'selected' : '' }}>

                                    Active

                                </option>

                                <option value="0"

                                    {{ old(
                                        'status',
                                        $isEdit ? $user->status : 1
                                    ) == 0 ? 'selected' : '' }}>

                                    Inactive

                                </option>

                            </select>

                        </div>

                        <button type="submit"
                                class="btn btn-success w-100">

                            @if($isSuperDistributor)

                                {{ $isEdit ? 'Update Distributor' : 'Create Distributor' }}

                            @else

                                {{ $isEdit ? 'Update User' : 'Create User' }}

                            @endif

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

    $('#togglePassword').click(function () {

        let password = $('#password');

        let icon = $(this).find('i');

        if (password.attr('type') === 'password') {

            password.attr('type', 'text');

            icon.removeClass('fa-eye');

            icon.addClass('fa-eye-slash');

        } else {

            password.attr('type', 'password');

            icon.removeClass('fa-eye-slash');

            icon.addClass('fa-eye');

        }

    });

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