@extends('layout.retailer')

@section('title', 'PAN Details')

@section('content')

<style>
    .pan-card{
        border:none;
        border-radius:18px;
        overflow:hidden;
        box-shadow:0 15px 35px rgba(0,0,0,.08);
    }

    .pan-header{
        background:linear-gradient(135deg,#0d6efd,#3b82f6);
        color:#fff;
        padding:25px;
    }

    .pan-header h3{
        font-weight:700;
        margin-bottom:5px;
    }

    .info-card{
        background:#fff;
        border-radius:15px;
        padding:18px;
        border:1px solid #edf1f7;
        box-shadow:0 6px 15px rgba(0,0,0,.04);
        transition:.3s;
        height:100%;
    }

    .info-card:hover{
        transform:translateY(-4px);
        box-shadow:0 12px 30px rgba(0,0,0,.08);
    }

    .info-label{
        color:#6c757d;
        font-size:13px;
        font-weight:600;
        text-transform:uppercase;
        margin-bottom:6px;
    }

    .info-value{
        font-size:18px;
        font-weight:700;
        color:#212529;
        word-break:break-word;
    }

    .icon-box{
        width:55px;
        height:55px;
        border-radius:15px;
        display:flex;
        align-items:center;
        justify-content:center;
        background:#eef5ff;
        color:#0d6efd;
        font-size:22px;
        margin-bottom:15px;
    }

    .status-box{
        text-align:center;
        background:#fff;
        border-radius:18px;
        padding:25px;
        box-shadow:0 10px 25px rgba(0,0,0,.05);
        border:1px solid #edf1f7;
    }

    .status-box h5{
        font-weight:700;
        margin-bottom:15px;
    }

    .badge{
        font-size:15px;
        padding:10px 18px;
    }

    .back-btn{
        border-radius:50px;
        padding:8px 18px;
        font-weight:600;
    }
</style>

<div class="container-fluid py-4">

    <div class="card pan-card">

        <div class="pan-header d-flex justify-content-between align-items-center">

            <div>

                <h3>

                    <i class="fas fa-id-card me-2"></i>

                    PAN Details

                </h3>

                <small>

                    View PAN information uploaded by Executive

                </small>

            </div>

            <a href="{{ route('retailer.pan-find.history') }}"
               class="btn btn-light back-btn">

                <i class="fas fa-arrow-left me-2"></i>

                Back

            </a>

        </div>

        <div class="card-body p-4">

            <div class="row">

                <div class="col-lg-4 mb-4">

                    <div class="status-box">

                        <i class="fas fa-check-circle text-success fa-3x mb-3"></i>

                        <h5>Application Status</h5>

                        @if($history->status=='Approved' || $history->status=='Completed')

                            <span class="badge bg-success">

                                {{ $history->status }}

                            </span>

                        @elseif($history->status=='Rejected')

                            <span class="badge bg-danger">

                                Rejected

                            </span>

                        @else

                            <span class="badge bg-warning text-dark">

                                {{ $history->status }}

                            </span>

                        @endif

                        <hr>

                        <div class="mt-3">

                            <small class="text-muted">

                                Application Number

                            </small>

                            <h5 class="mt-2">

                                {{ $history->application_no }}

                            </h5>

                        </div>

                    </div>

                </div>

                <div class="col-lg-8">

                    <div class="row">

                        <div class="col-md-6 mb-4">

                            <div class="info-card">

                                <div class="icon-box">

                                    <i class="fas fa-user"></i>

                                </div>

                                <div class="info-label">

                                    Full Name

                                </div>

                                <div class="info-value">

                                    {{ $history->full_name ?: '-' }}

                                </div>

                            </div>

                        </div>

                        <div class="col-md-6 mb-4">

                            <div class="info-card">

                                <div class="icon-box">

                                    <i class="fas fa-user"></i>

                                </div>

                                <div class="info-label">

                                    Father's Name

                                </div>

                                <div class="info-value">

                                    {{ $history->father_name ?: '-' }}

                                </div>

                            </div>

                        </div>


                        <div class="col-md-6 mb-4">

                            <div class="info-card">

                                <div class="icon-box">

                                    <i class="fas fa-id-badge"></i>

                                </div>

                                <div class="info-label">

                                    PAN Number

                                </div>

                                <div class="info-value">

                                    {{ strtoupper($history->pan_number ?? '-') }}

                                </div>

                            </div>

                        </div>

                        <div class="col-md-6 mb-4">

                            <div class="info-card">

                                <div class="icon-box">

                                    <i class="fas fa-venus-mars"></i>

                                </div>

                                <div class="info-label">

                                    Gender

                                </div>

                                <div class="info-value">

                                    {{ $history->gender ?: '-' }}

                                </div>

                            </div>

                        </div>

                        <div class="col-md-6 mb-4">

                            <div class="info-card">

                                <div class="icon-box">

                                    <i class="fas fa-calendar"></i>

                                </div>

                                <div class="info-label">

                                    Date of Birth

                                </div>

                                <div class="info-value">

                                    {{ $history->dob ? \Carbon\Carbon::parse($history->dob)->format('d M Y') : '-' }}

                                </div>

                            </div>

                        </div>

                        <div class="col-md-6 mb-4">

                            <div class="info-card">

                                <div class="icon-box">

                                    <i class="fas fa-clock"></i>

                                </div>

                                <div class="info-label">

                                    Submitted On

                                </div>

                                <div class="info-value">

                                    {{ $history->created_at->format('d M Y h:i A') }}

                                </div>

                            </div>

                        </div>

                        <div class="col-md-6 mb-4">

                            <div class="info-card">

                                <div class="icon-box">

                                    <i class="fas fa-sync"></i>

                                </div>

                                <div class="info-label">

                                    Last Updated

                                </div>

                                <div class="info-value">

                                    {{ $history->updated_at->format('d M Y h:i A') }}

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection