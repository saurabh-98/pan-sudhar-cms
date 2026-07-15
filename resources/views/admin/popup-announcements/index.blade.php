@extends('layout.admin')

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid mt-4">

    <div class="row">

        <!-- ================= ADD POPUP ================= -->

        <div class="col-lg-4">

            <div class="card shadow border-0">

                <div class="card-header bg-primary text-white">

                    <h5 class="mb-0">

                        ➕ Add Popup

                    </h5>

                </div>

                <div class="card-body">

                    <form id="popupForm"
                          enctype="multipart/form-data">

                        @csrf

                        <div class="mb-3">

                            <label>Title</label>

                            <input
                                type="text"
                                name="title"
                                class="form-control">

                        </div>

                        <div class="mb-3">

                            <label>Slug</label>

                            <input
                                type="text"
                                name="slug"
                                class="form-control">

                        </div>

                        <div class="mb-3">

                            <label>Banner Image</label>

                            <input
                                type="file"
                                name="image"
                                class="form-control">

                        </div>

                        <div class="mb-3">

                            <label>Description</label>

                            <textarea
                                name="description"
                                rows="5"
                                class="form-control"></textarea>

                        </div>

                        <div class="mb-3">

                            <label>Button Text</label>

                            <input
                                type="text"
                                name="button_text"
                                class="form-control">

                        </div>

                        <div class="mb-3">

                            <label>Button Link</label>

                            <input
                                type="text"
                                name="button_link"
                                class="form-control">

                        </div>

                        <div class="row">

                            <div class="col-md-6">

                                <label>Start Date</label>

                                <input
                                    type="date"
                                    name="start_date"
                                    class="form-control">

                            </div>

                            <div class="col-md-6">

                                <label>End Date</label>

                                <input
                                    type="date"
                                    name="end_date"
                                    class="form-control">

                            </div>

                        </div>

                        <hr>

                        <div class="form-check">

                            <input
                                type="checkbox"
                                name="show_on_login"
                                value="1"
                                class="form-check-input">

                            <label class="form-check-label">

                                Show on Login

                            </label>

                        </div>

                        <div class="form-check">

                            <input
                                type="checkbox"
                                name="show_on_dashboard"
                                value="1"
                                class="form-check-input">

                            <label class="form-check-label">

                                Show on Dashboard

                            </label>

                        </div>

                        <div class="form-check">

                            <input
                                type="checkbox"
                                name="show_once_per_day"
                                value="1"
                                checked
                                class="form-check-input">

                            <label class="form-check-label">

                                Show Once Per Day

                            </label>

                        </div>

                        <div class="form-check">

                            <input
                                type="checkbox"
                                name="status"
                                value="1"
                                checked
                                class="form-check-input">

                            <label class="form-check-label">

                                Active

                            </label>

                        </div>

                        <button
                            class="btn btn-primary w-100 mt-3">

                            Save Popup

                        </button>

                    </form>

                </div>

            </div>

        </div>

        <!-- ================= TABLE ================= -->

        <div class="col-lg-8">

            <div class="card shadow border-0">

                <div class="card-header bg-dark text-white">

                    <h5 class="mb-0">

                        Popup List

                    </h5>

                </div>

                <div class="card-body">

                    <table
                        id="popupTable"
                        class="table table-bordered">

                        <thead>

                        <tr>

                            <th>ID</th>

                            <th>Image</th>

                            <th>Title</th>

                            <th>Login</th>

                            <th>Dashboard</th>

                            <th>Status</th>

                            <th>Action</th>

                        </tr>

                        </thead>

                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

@include('admin.popup-announcements.edit-modal')

@endsection