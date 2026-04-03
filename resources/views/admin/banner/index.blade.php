@extends('layout.admin')

@section('content')

<div class="container-fluid">

    <h3 class="dashboard-title mb-4">Manage Hero Banner</h3>

    <div class="row g-4">

        <!-- FORM -->
        <div class="col-lg-4">
            <div class="modern-card">

                <h5 class="card-title">Add / Edit Banner</h5>

                <form id="bannerForm" enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" id="banner_id">

                    <div class="mb-3">
                        <label>Title</label>
                        <input type="text" name="title" class="form-control modern-input">
                    </div>

                    <div class="mb-3">
                        <label>Subtitle</label>
                        <input type="text" name="subtitle" class="form-control modern-input">
                    </div>

                    <div class="mb-3">
                        <label>Button Text</label>
                        <input type="text" name="button_text" class="form-control modern-input">
                    </div>

                    <div class="mb-3">
                        <label>Images (Multiple)</label>
                        <input type="file" id="bannerImages" name="image[]" class="form-control" multiple>

                        <small class="text-muted">Select multiple images. Click ❌ to remove.</small>

                        <div id="bannerPreviewContainer" 
                             style="display:flex;gap:10px;flex-wrap:wrap;margin-top:10px;"></div>
                    </div>

                    <button class="btn btn-gradient w-100">Save Banner</button>

                </form>

            </div>
        </div>

        <!-- TABLE -->
        <div class="col-lg-8">
            <div class="modern-card">

                <h5>Banner List</h5>

                <table id="bannerTable" class="modern-table table table-bordered text-center">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Title</th>
                            <th>Subtitle</th>
                            <th>Button</th>
                            <th>Images</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                </table>

            </div>
        </div>

    </div>

</div>

@endsection


@section('scripts')

<script>
$(document).ready(function(){

    let selectedFiles = [];
    let existingImages = []; // ✅ ADDED

    /* ================= IMAGE PREVIEW ================= */
    $('#bannerImages').on('change', function(e){

        let files = Array.from(e.target.files);

        files.forEach(file => {
            selectedFiles.push(file);
        });

        renderPreview();
    });

    function renderPreview(){

        let container = $('#bannerPreviewContainer');
        container.html('');

        // ✅ EXISTING IMAGES (ADDED)
        existingImages.forEach((img, index) => {
            container.append(`
                <div style="position:relative;">
                    <img src="/storage/${img}" 
                        style="width:80px;height:60px;object-fit:cover;border-radius:6px;">

                    <span onclick="removeExisting(${index})"
                        style="position:absolute;top:-5px;right:-5px;
                               background:red;color:#fff;
                               border-radius:50%;cursor:pointer;
                               padding:2px 6px;font-size:12px;">
                        ×
                    </span>
                </div>
            `);
        });

        // EXISTING CODE (UNCHANGED)
        selectedFiles.forEach((file, index) => {

            let reader = new FileReader();

            reader.onload = function(e){

                container.append(`
                    <div style="position:relative;">
                        <img src="${e.target.result}" 
                            style="width:80px;height:60px;object-fit:cover;border-radius:6px;">

                        <span onclick="removeImage(${index})"
                            style="position:absolute;top:-5px;right:-5px;
                                   background:red;color:#fff;
                                   border-radius:50%;cursor:pointer;
                                   padding:2px 6px;font-size:12px;">
                            ×
                        </span>
                    </div>
                `);
            };

            reader.readAsDataURL(file);
        });
    }

    window.removeImage = function(index){
        selectedFiles.splice(index, 1);
        renderPreview();
    }

    // ✅ NEW FUNCTION
    window.removeExisting = function(index){
        existingImages.splice(index, 1);
        renderPreview();
    }

    /* ================= DATATABLE ================= */
    let table = $('#bannerTable').DataTable({
        processing: true,
        ajax: {
            url: "{{ route('admin.banners.index') }}",
            type: "GET",
            dataSrc: ""
        },
        columns: [
            { data:'id' },
            { data:'title' },
            { data:'subtitle', defaultContent:'' },
            { data:'button_text', defaultContent:'' },

            {
                data:'image',
                render: function(d){

                    if(!d) return '';

                    let images = Array.isArray(d) ? d : [];

                    return images.map(img => 
                        `<img src="/storage/${img}" 
                             style="width:50px;height:40px;margin:2px;border-radius:4px;">`
                    ).join('');
                }
            },

            {
                data:null,
                orderable:false,
                render: function(row){
                    return `
                        <button class="btn btn-sm btn-primary editBtn"
                            data-id="${row.id}"
                            data-title="${row.title}"
                            data-subtitle="${row.subtitle ?? ''}"
                            data-button="${row.button_text ?? ''}"
                            data-image='${JSON.stringify(row.image)}'>
                            Edit
                        </button>

                        <button class="btn btn-sm btn-danger deleteBtn"
                            data-id="${row.id}">
                            Delete
                        </button>
                    `;
                }
            }
        ]
    });

    /* ================= EDIT ================= */
    $(document).on('click', '.editBtn', function(){

        let btn = $(this);

        $('#banner_id').val(btn.data('id'));
        $('input[name="title"]').val(btn.data('title'));
        $('input[name="subtitle"]').val(btn.data('subtitle'));
        $('input[name="button_text"]').val(btn.data('button'));

        selectedFiles = [];

        // ✅ FIX: store existing images
        existingImages = btn.data('image') || [];

        renderPreview(); // ✅ important

        Swal.fire('Edit Mode Enabled','','info');

        $('html, body').animate({
            scrollTop: $("#bannerForm").offset().top
        }, 400);
    });

    /* ================= DELETE ================= */
    $(document).on('click', '.deleteBtn', function(){

        let id = $(this).data('id');

        Swal.fire({
            title: 'Delete Banner?',
            icon: 'warning',
            showCancelButton: true
        }).then((result) => {

            if(result.isConfirmed){

                $.ajax({
                    url: "{{ route('admin.banners.delete', ':id') }}".replace(':id', id),
                    method: "DELETE",
                    data: { _token: "{{ csrf_token() }}" },
                    success: function(){
                        Swal.fire('Deleted!','','success');
                        table.ajax.reload();
                    }
                });

            }

        });
    });

    /* ================= SAVE ================= */
    $('#bannerForm').on('submit', function(e){
        e.preventDefault();

        let id = $('#banner_id').val();

        let url = id
            ? "{{ route('admin.banners.update', ':id') }}".replace(':id', id)
            : "{{ route('admin.banners.store') }}";

        let formData = new FormData();

        formData.append('_token', "{{ csrf_token() }}");
        formData.append('title', $('input[name="title"]').val());
        formData.append('subtitle', $('input[name="subtitle"]').val());
        formData.append('button_text', $('input[name="button_text"]').val());

        selectedFiles.forEach(file => {
            formData.append('image[]', file);
        });

        // ✅ CRITICAL FIX (ADD THIS)
        existingImages.forEach(img => {
            formData.append('existing_images[]', img);
        });

        Swal.fire({
            title: 'Confirm Save?',
            showCancelButton: true
        }).then((result)=>{

            if(result.isConfirmed){

                $.ajax({
                    url: url,
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: () => {

                        Swal.fire('Success','','success');

                        $('#bannerForm')[0].reset();
                        $('#bannerPreviewContainer').html('');
                        $('#banner_id').val('');
                        selectedFiles = [];
                        existingImages = []; // ✅ RESET

                        table.ajax.reload();
                    }
                });

            }

        });

    });

});
</script>

@endsection
