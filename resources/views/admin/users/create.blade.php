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
                          action="{{ $isEdit
                                    ? route('admin.users.update', $user->id)
                                    : route('admin.users.store') }}"
                          autocomplete="off">

                        @csrf

                        {{-- NAME --}}
                        <div class="form-floating mb-3">

                            <input type="text"
                                   name="name"
                                   class="form-control"
                                   value="{{ old('name', $isEdit ? $user->name : '') }}"
                                   required>

                            <label>Name</label>

                        </div>


                        {{-- EMAIL --}}
                        <div class="form-floating mb-3">

                            <input type="email"
                                   name="email"
                                   class="form-control"
                                   value="{{ old('email', $isEdit ? $user->email : '') }}"
                                   required>

                            <label>Email</label>

                        </div>


                        {{-- PASSWORD --}}
                        <div class="form-floating mb-3">

                            <input type="password"
                                   name="password"
                                   class="form-control"
                                   {{ $isEdit ? '' : 'required' }}>

                            <label>

                                {{ $isEdit
                                    ? 'New Password (Optional)'
                                    : 'Password' }}

                            </label>

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

                                        {{
                                            $isEdit &&
                                            $user->hasRole($role->name)
                                            ? 'selected'
                                            : ''
                                        }}>

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

                            <select name="status"
                                    class="form-select">

                                <option value="1"
                                    {{
                                        $isEdit &&
                                        $user->status == 1
                                        ? 'selected'
                                        : ''
                                    }}>

                                    Active

                                </option>

                                <option value="0"
                                    {{
                                        $isEdit &&
                                        $user->status == 0
                                        ? 'selected'
                                        : ''
                                    }}>

                                    Inactive

                                </option>

                            </select>

                        </div>


                        {{-- SUBMIT --}}
                        <button type="submit"
                                class="btn btn-success w-100">

                            {{ $isEdit
                                ? 'Update User'
                                : 'Create User' }}

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