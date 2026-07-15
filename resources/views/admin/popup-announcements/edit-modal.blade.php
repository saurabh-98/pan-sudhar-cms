<!-- ================= EDIT POPUP MODAL ================= -->

<div class="modal fade" id="editModal" tabindex="-1">

    <div class="modal-dialog modal-lg modal-dialog-scrollable">

        <div class="modal-content">

            <div class="modal-header bg-primary text-white">

                <h5 class="modal-title">
                    ✏️ Edit Popup
                </h5>

                <button
                    type="button"
                    class="btn-close btn-close-white"
                    data-bs-dismiss="modal">
                </button>

            </div>

            <div class="modal-body">

                <input
                    type="hidden"
                    id="edit_id">

                <div class="row">

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Title
                        </label>

                        <input
                            type="text"
                            id="edit_title"
                            class="form-control">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Slug
                        </label>

                        <input
                            type="text"
                            id="edit_slug"
                            class="form-control">

                    </div>

                    <div class="col-md-12 mb-3">

                        <label class="form-label">
                            Description
                        </label>

                        <textarea
                            id="edit_description"
                            rows="5"
                            class="form-control"></textarea>

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Button Text
                        </label>

                        <input
                            type="text"
                            id="edit_button_text"
                            class="form-control">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Button Link
                        </label>

                        <input
                            type="text"
                            id="edit_button_link"
                            class="form-control">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Banner Image
                        </label>

                        <input
                            type="file"
                            id="edit_image"
                            class="form-control">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Current Image
                        </label>

                        <br>

                        <img
                            id="preview_image"
                            src=""
                            width="120"
                            class="img-thumbnail">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            Start Date
                        </label>

                        <input
                            type="date"
                            id="edit_start_date"
                            class="form-control">

                    </div>

                    <div class="col-md-6 mb-3">

                        <label class="form-label">
                            End Date
                        </label>

                        <input
                            type="date"
                            id="edit_end_date"
                            class="form-control">

                    </div>

                </div>

                <hr>

                <div class="row">

                    <div class="col-md-6">

                        <div class="form-check form-switch">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="edit_show_on_login">

                            <label class="form-check-label">
                                Show On Login
                            </label>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="form-check form-switch">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="edit_show_on_dashboard">

                            <label class="form-check-label">
                                Show On Dashboard
                            </label>

                        </div>

                    </div>

                    <div class="col-md-6">

                        <div class="form-check form-switch">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="edit_show_on_home">

                            <label class="form-check-label">
                                Show On Home
                            </label>

                        </div>

                    </div>

                    <div class="col-md-6 mt-3">

                        <div class="form-check form-switch">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="edit_show_once_per_day">

                            <label class="form-check-label">
                                Show Once Per Day
                            </label>

                        </div>

                    </div>

                    <div class="col-md-6 mt-3">

                        <div class="form-check form-switch">

                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="edit_status">

                            <label class="form-check-label">
                                Active
                            </label>

                        </div>

                    </div>

                </div>

            </div>

            <div class="modal-footer">

                <button
                    type="button"
                    class="btn btn-secondary"
                    data-bs-dismiss="modal">

                    Close

                </button>

                <button
                    type="button"
                    id="updateBtn"
                    class="btn btn-primary">

                    <i class="fa fa-save"></i>

                    Update Popup

                </button>

            </div>

        </div>

    </div>

</div>