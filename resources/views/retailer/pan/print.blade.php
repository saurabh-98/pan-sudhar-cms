<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PAN Application Receipt - {{ $application->application_no }}</title>
    <link rel="stylesheet" href="{{ public_path('assets/css/aadhaar-receipt.css') }}">
</head>
<body>

{{-- ==========================================================
     PAN SUDHAR PORTAL — PAN APPLICATION PRINT RECEIPT
     Built directly from App\Models\PanApplication fields/accessors
========================================================== --}}
<div class="receipt">
    <div class="receipt-border">

        {{-- HEADER --}}
        <div class="receipt-top">
            <table class="header-table">
                <tr>
                    <td width="18%" class="logo-area">
                        <img src="{{ public_path('assets/images/logo.png') }}" class="logo">
                    </td>
                    <td width="64%" class="title-area">
                        <h1>PAN SUDHAR PORTAL</h1>
                        <h2>NEW PAN APPLICATION ACKNOWLEDGEMENT (FORM 49A)</h2>
                        <p>Digital Citizen Service Center</p>
                    </td>
                    <td width="18%" class="receipt-copy">
                        <div class="copy-box">OFFICE COPY</div>
                    </td>
                </tr>
            </table>
        </div>

        {{-- ACKNOWLEDGEMENT BAR --}}
        <table class="acknowledge-table">
            <tr>
                <td width="25%">
                    <strong>Receipt No</strong><br>
                    RCPT-{{ $application->application_no }}
                </td>
                <td width="25%">
                    <strong>Application No</strong><br>
                    {{ $application->application_no }}
                </td>
                <td width="25%">
                    <strong>Date</strong><br>
                    {{ $application->created_at->format('d M Y') }}
                </td>
                <td width="25%">
                    <strong>Time</strong><br>
                    {{ $application->created_at->format('h:i A') }}
                </td>
            </tr>
        </table>

        {{-- BARCODE --}}
        <table class="barcode-table">
            <tr>
                <td width="70%">
                    <div class="barcode-box">
                        <img src="{{ public_path('assets/images/barcode-demo.png') }}" class="barcode">
                    </div>
                </td>
                <td width="30%" align="center">Receipt Verification</td>
            </tr>
        </table>

        {{-- APPLICATION INFORMATION --}}
        <div class="section-heading">APPLICATION INFORMATION</div>
        <table class="info-table">
            <tr>
                <th width="25%">Application Number</th>
                <td width="25%">{{ $application->application_no }}</td>
                <th width="25%">Application Date</th>
                <td width="25%">{{ $application->created_at->format('d-m-Y') }}</td>
            </tr>
            <tr>
                <th>PAN Type</th>
                <td>{{ $application->pan_type ?? '-' }}</td>
                <th>Status</th>
                <td>{{ $application->status }}</td>
            </tr>
            <tr>
                <th>Payment Status</th>
                <td>{{ $application->payment_status }}</td>
                <th>Amount Paid</th>
                <td>₹ {{ number_format($application->amount, 2) }}</td>
            </tr>
        </table>

        {{-- RETAILER INFORMATION --}}
        <div class="section-heading">RETAILER INFORMATION</div>
        <table class="info-table">
            <tr>
                <th width="25%">Retailer ID</th>
                <td width="25%">{{ auth()->user()->registration_no ?? '-' }}</td>
                <th width="25%">Retailer Name</th>
                <td width="25%">{{ auth()->user()->name ?? '-' }}</td>
            </tr>
            <tr>
                <th>Mobile</th>
                <td>{{ auth()->user()->mobile ?? '-' }}</td>
                <th>Email</th>
                <td>{{ auth()->user()->email ?? '-' }}</td>
            </tr>
        </table>

        {{-- APPLICANT INFORMATION --}}
        <div class="section-heading">APPLICANT INFORMATION</div>
        <table class="applicant-table">
            <tr>
                <th width="18%">Applicant Name</th>
                <td width="32%">{{ $application->applicant_name }}</td>
                <th width="18%">Photo</th>
                <td width="32%" rowspan="7" class="photo-column">
                    @if($application->photo_url)
                        <img src="{{ $application->photo_url }}" class="passport-photo">
                    @else
                        <div class="photo-placeholder">PASSPORT PHOTO</div>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Name on PAN Card</th>
                <td>{{ $application->pan_print_name ?? $application->applicant_name }}</td>
                <th>Gender</th>
                <td>{{ $application->gender ?? '-' }}</td>
            </tr>
            <tr>
                <th>Father's Name</th>
                <td>{{ $application->father_full_name ?: '-' }}</td>
                <th>DOB</th>
                <td>{{ $application->dob ? $application->dob_formatted : '-' }}</td>
            </tr>
            <tr>
                <th>Mother's Name</th>
                <td>{{ $application->mother_full_name ?: '-' }}</td>
                <th>Aadhaar Number</th>
                <td>{{ $application->masked_aadhaar ?: '-' }}</td>
            </tr>
            <tr>
                <th>Aadhaar Name</th>
                <td>{{ $application->aadhaar_name ?? '-' }}</td>
                <th>Mobile Number</th>
                <td>{{ $application->mobile_no ?? '-' }}</td>
            </tr>
            <tr>
                <th>Email Address</th>
                <td colspan="3">{{ $application->email ?? '-' }}</td>
            </tr>
            <tr>
                <th>Signature</th>
                <td colspan="3">
                    @if($application->signature_url)
                        <img src="{{ $application->signature_url }}" style="height:35px;">
                    @else
                        Not uploaded
                    @endif
                </td>
            </tr>
        </table>

        {{-- ADDRESS INFORMATION --}}
        <div class="section-heading">ADDRESS INFORMATION</div>
        <table class="address-table">
            <tr>
                <th width="20%">House No.</th>
                <td width="30%">{{ $application->house_no ?? '-' }}</td>
                <th width="20%">Village / Area</th>
                <td width="30%">{{ collect([$application->village, $application->area])->filter()->implode(', ') ?: '-' }}</td>
            </tr>
            <tr>
                <th>Post Office</th>
                <td>{{ $application->post_office ?? '-' }}</td>
                <th>District</th>
                <td>{{ $application->districtData->name ?? '-' }}</td>
            </tr>
            <tr>
                <th>State</th>
                <td>{{ $application->stateData->name ?? '-' }}</td>
                <th>PIN Code</th>
                <td>{{ $application->pincode ?? '-' }}</td>
            </tr>
        </table>

        {{-- DOCUMENTS SUBMITTED --}}
        <div class="section-heading">DOCUMENTS SUBMITTED</div>
        <table class="info-table">
            <tr>
                <th width="25%">Proof of Identity</th>
                <td width="25%">{{ $application->identity_proof ?? '-' }}</td>
                <th width="25%">Proof of Address</th>
                <td width="25%">{{ $application->address_proof ?? '-' }}</td>
            </tr>
            <tr>
                <th>Proof of DOB</th>
                <td>{{ $application->dob_proof ?? '-' }}</td>
                <th>Signature Type</th>
                <td>{{ $application->signature_type ?? '-' }}</td>
            </tr>
            <tr>
                <th>Aadhaar Card Uploaded</th>
                <td>{{ $application->aadhaar_card_url ? 'Yes' : 'No' }}</td>
                <th>DOB Proof Uploaded</th>
                <td>{{ $application->dob_proof_file_url ? 'Yes' : 'No' }}</td>
            </tr>
            @if($application->supporting_document_url)
            <tr>
                <th>Supporting Document</th>
                <td colspan="3">Uploaded</td>
            </tr>
            @endif
        </table>

        {{-- ADMIN REMARK (only shown if present) --}}
        @if(!empty($application->admin_remark))
        <div class="section-heading">ADMIN REMARK</div>
        <div class="declaration-box">
            {{ $application->admin_remark }}
        </div>
        @endif

        {{-- DECLARATION --}}
        <div class="section-heading">APPLICANT DECLARATION</div>
        <div class="declaration-box">
            I hereby declare that all the information furnished by me in this
            application is true and correct to the best of my knowledge and
            belief. I understand that if any information is found false or
            incorrect, my application may be rejected without any notice.
        </div>

    </div>
</div>

</body>
</html>