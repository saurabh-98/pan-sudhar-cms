@extends('layout.retailer')

@section('title', 'ITR History')

@section('content')

<div class="container-fluid py-4">

    <div class="card border-0 shadow-lg rounded-4 overflow-hidden">

        <!-- HEADER -->
        <div class="card-header bg-white border-0 py-4">

            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">

                <div>

                    <h3 class="fw-bold mb-1">
                        ITR History
                    </h3>

                    <p class="text-muted mb-0">
                        View all submitted ITR records
                    </p>

                </div>

                <button class="btn btn-dark rounded-pill px-4"
                        id="refreshBtn">

                    <i class="fa fa-rotate-right me-2"></i>
                    Refresh

                </button>

            </div>

        </div>

        <!-- BODY -->
        <div class="card-body">

            <!-- LOADER -->
            <div id="loaderBox"
                 class="text-center py-5">

                <div class="spinner-border text-dark"></div>

                <p class="mt-3 text-muted">
                    Loading ITR Records...
                </p>

            </div>

            <!-- TABLE -->
            <div class="table-responsive d-none"
                 id="tableWrapper">

                <table class="table align-middle table-hover">

                    <thead class="table-dark">

                        <tr>

                            <th>#</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                            <th>Remarks</th>
                            <th>Admin Remarks</th>
                            <th>Date</th>
                            <th>Aadhaar Front</th>
                            <th>Aadhaar Back</th>
                            <th>PAN Card</th>
                            <th>Receipt</th>

                        </tr>

                    </thead>

                    <tbody id="itrTableBody"></tbody>

                </table>

                <!-- PAGINATION -->
                <div class="d-flex justify-content-center mt-4">

                    <nav>

                        <ul class="pagination"
                            id="paginationLinks">
                        </ul>

                    </nav>

                </div>

            </div>

            <!-- EMPTY -->
            <div class="text-center py-5 d-none"
                 id="emptyBox">

                <i class="fa fa-folder-open fa-4x text-muted mb-3"></i>

                <h4 class="fw-bold">
                    No ITR History Found
                </h4>

                <p class="text-muted">
                    No records available.
                </p>

            </div>

        </div>

    </div>

</div>

@endsection

@section('scripts')

<script>

    $(document).ready(function () {

        loadItrHistory();

        $('#refreshBtn').click(function () {

            loadItrHistory();

        });

    });

    function getFileUrl(path)
    {
        if (!path) {
            return null;
        }

        // Cloudinary URL
        if (
            path.startsWith('http://') ||
            path.startsWith('https://')
        ) {
            return path;
        }

        // Local/Public uploads
        return "{{ url('/') }}/" + path;
    }

    function loadItrHistory(page = 1)
    {

        $('#loaderBox').removeClass('d-none');
        $('#tableWrapper').addClass('d-none');
        $('#emptyBox').addClass('d-none');

        $.ajax({

            url: "{{ route('retailer.itr.history') }}?page=" + page,

            type: "GET",

            success: function (response)
            {

                $('#loaderBox').addClass('d-none');

                let rows = '';

                if (response.data.length > 0)
                {

                    $.each(response.data, function (index, itr)
                    {

                        let statusBadge = '';

                        if (itr.status === 'Approved') {

                            statusBadge =
                                `<span class="badge-status badge-approved">
                                    Approved
                                </span>`;

                        }
                        else if (
                            itr.status === 'Processing' ||
                            itr.status === 'pending'
                        ) {

                            statusBadge =
                                `<span class="badge-status badge-pending">
                                    Processing
                                </span>`;

                        }
                        else {

                            statusBadge =
                                `<span class="badge-status badge-rejected">
                                    Rejected
                                </span>`;
                        }

                        let aadhaarFrontUrl =
                            getFileUrl(itr.aadhaar_front);

                        let aadhaarBackUrl =
                            getFileUrl(itr.aadhaar_back);

                        let panCardUrl =
                            getFileUrl(itr.pan_card);

                        rows += `

                            <tr>

                                <td>${index + 1}</td>

                                <td>${itr.name ?? 'N/A'}</td>

                                <td>${itr.email ?? 'N/A'}</td>


                                <td>${statusBadge}</td>

                                <td>${itr.remarks ?? 'N/A'}</td>

                                <td>${itr.admin_remarks ?? 'N/A'}</td>

                                <td>
                                    ${
                                        itr.created_at
                                        ?
                                        new Date(itr.created_at)
                                        .toLocaleString('en-IN', {
                                            day:'2-digit',
                                            month:'short',
                                            year:'numeric',
                                            hour:'2-digit',
                                            minute:'2-digit',
                                            hour12:true
                                        })
                                        :
                                        'N/A'
                                    }
                                </td>

                                <td>

                                    ${
                                        aadhaarFrontUrl
                                        ?
                                        `<a href="${aadhaarFrontUrl}"
                                            target="_blank"
                                            class="file-btn">

                                            View

                                        </a>`
                                        :
                                        'N/A'
                                    }

                                </td>

                                <td>

                                    ${
                                        aadhaarBackUrl
                                        ?
                                        `<a href="${aadhaarBackUrl}"
                                            target="_blank"
                                            class="file-btn">

                                            View

                                        </a>`
                                        :
                                        'N/A'
                                    }

                                </td>

                                <td>

                                    ${
                                        panCardUrl
                                        ?
                                        `<a href="${panCardUrl}"
                                            target="_blank"
                                            class="file-btn">

                                            View

                                        </a>`
                                        :
                                        'N/A'
                                    }

                                </td>

                                <td>
                                        ${
                                            itr.service_document
                                                ? `
                                                    <a href="${itr.service_document}" target="_blank" class="btn btn-success btn-sm">
                                                        <i class="fa fa-eye"></i> View
                                                    </a>

                                                    <a href="${itr.service_document}" download class="btn btn-primary btn-sm">
                                                        <i class="fa fa-download"></i> Download
                                                    </a>
                                                `
                                                : '<span class="badge bg-warning">Pending</span>'
                                        }
                                    </td>
                            </tr>

                        `;

                    });

                    $('#itrTableBody').html(rows);

                    $('#tableWrapper').removeClass('d-none');

                    renderPagination(
                        response.pagination
                    );

                }
                else {

                    $('#emptyBox').removeClass('d-none');

                }

            },

            error: function ()
            {

                $('#loaderBox').addClass('d-none');

                $('#emptyBox').removeClass('d-none');

            }

        });

    }

    function renderPagination(pagination)
    {

        let html = '';

        for (
            let i = 1;
            i <= pagination.last_page;
            i++
        ) {

            html += `

                <li class="page-item ${
                    pagination.current_page == i
                    ? 'active'
                    : ''
                }">

                    <a
                        href="javascript:void(0)"
                        onclick="loadItrHistory(${i})"
                        class="page-link"
                    >

                        ${i}

                    </a>

                </li>

            `;
        }

        $('#paginationLinks').html(html);

    }

</script>
@endsection