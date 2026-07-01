<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Aadhaar Receipt - {{ $application->application_no }}</title>
    <link rel="stylesheet" href="{{ public_path('assets/css/aadhaar-receipt.css') }}">
</head>
<body>

@php
    // Normalize form_data (handles both array and JSON-string cases)
    $formData = is_array($application->form_data)
        ? $application->form_data
        : (json_decode($application->form_data, true) ?? []);

    $documents = is_array($application->documents)
        ? $application->documents
        : (json_decode($application->documents, true) ?? []);

    // Helper: try a list of possible keys, return first non-empty match
    $pick = function (array $keys, $default = '-') use ($formData) {
        foreach ($keys as $key) {
            if (!empty($formData[$key])) {
                return $formData[$key];
            }
        }
        return $default;
    };

    $applicantName = $pick(['applicant_name', 'customer_name', 'name']);
    $fatherName    = $pick(['father_name']);
    $motherName    = $pick(['mother_name']);
    $gender        = $pick(['gender']);
    $dob           = $pick(['dob', 'date_of_birth']);
    $mobile        = $pick(['mobile', 'mobile_number', 'new_mobile']);
    $email         = $pick(['email', 'email_address']);
    $category      = $pick(['category'], 'General');
    $maritalStatus = $pick(['marital_status']);
    $aadhaarNumber = $pick(['aadhaar_number']);
    $mobileLinked  = $pick(['mobile_linked'], 'Yes');

    $address  = $pick(['address']);
    $city     = $pick(['city', 'village']);
    $district = $pick(['district']);
    $state    = $pick(['state']);
    $pinCode  = $pick(['pin_code', 'pincode']);
    $country  = $pick(['country'], 'India');

    // Photo: check common photo keys in documents first
    $photo = $documents['photo'] ?? $documents['passport_photo'] ?? null;

    // Fields already shown above, excluded from the generic "extra fields" dump
    $shownKeys = [
        'applicant_name', 'customer_name', 'name',
        'father_name', 'mother_name', 'gender', 'dob', 'date_of_birth',
        'mobile', 'mobile_number', 'new_mobile', 'email', 'email_address',
        'category', 'marital_status', 'aadhaar_number', 'mobile_linked',
        'address', 'city', 'village', 'district', 'state', 'pin_code', 'pincode', 'country',
    ];

    $extraFields = collect($formData)
        ->reject(fn ($value, $key) => in_array($key, $shownKeys) || empty($value))
        ->mapWithKeys(fn ($value, $key) => [ucwords(str_replace('_', ' ', $key)) => $value]);

    $extraDocuments = collect($documents)
        ->reject(fn ($value, $key) => in_array($key, ['photo', 'passport_photo']) || empty($value))
        ->mapWithKeys(fn ($value, $key) => [ucwords(str_replace('_', ' ', $key)) => $value]);
@endphp

{{-- ==========================================================
     PAN SUDHAR PORTAL — PREMIUM RECEIPT
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
                        <h2>AADHAAR SERVICE ACKNOWLEDGEMENT RECEIPT</h2>
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
                <th>Service</th>
                <td>{{ $application->service_name }}</td>
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
                <td width="32%">{{ $applicantName }}</td>
                <th width="18%">Passport Photo</th>
                <td width="32%" rowspan="6" class="photo-column">
                    @if($photo)
                        <img src="{{ public_path('storage/'.$photo) }}" class="passport-photo">
                    @else
                        <div class="photo-placeholder">PASSPORT PHOTO</div>
                    @endif
                </td>
            </tr>
            <tr>
                <th>Father Name</th>
                <td>{{ $fatherName }}</td>
                <th>Gender</th>
                <td>{{ $gender }}</td>
            </tr>
            <tr>
                <th>Mother Name</th>
                <td>{{ $motherName }}</td>
                <th>DOB</th>
                <td>{{ $dob }}</td>
            </tr>
            <tr>
                <th>Aadhaar Number</th>
                <td>
                    @if($aadhaarNumber !== '-')
                        XXXXXXXX{{ substr($aadhaarNumber, -4) }}
                    @else
                        -
                    @endif
                </td>
                <th>Category</th>
                <td>{{ $category }}</td>
            </tr>
            <tr>
                <th>Mobile Number</th>
                <td>{{ $mobile }}</td>
                <th>Marital Status</th>
                <td>{{ $maritalStatus }}</td>
            </tr>
            <tr>
                <th>Email Address</th>
                <td colspan="3">{{ $email }}</td>
            </tr>
        </table>

        {{-- ADDRESS INFORMATION --}}
        <div class="section-heading">ADDRESS INFORMATION</div>
        <table class="address-table">
            <tr>
                <th width="20%">Address</th>
                <td colspan="5">{{ $address }}</td>
            </tr>
            <tr>
                <th>Village / City</th>
                <td>{{ $city }}</td>
                <th>District</th>
                <td>{{ $district }}</td>
                <th>State</th>
                <td>{{ $state }}</td>
            </tr>
            <tr>
                <th>PIN Code</th>
                <td>{{ $pinCode }}</td>
                <th>Country</th>
                <td colspan="3">{{ $country }}</td>
            </tr>
        </table>

        {{-- IDENTITY INFORMATION --}}
        <div class="section-heading">IDENTITY INFORMATION</div>
        <table class="identity-table">
            <tr>
                <th width="20%">Aadhaar Number</th>
                <td width="30%">
                    @if($aadhaarNumber !== '-')
                        XXXXXXXX{{ substr($aadhaarNumber, -4) }}
                    @else
                        -
                    @endif
                </td>
                <th width="20%">Mobile Linked</th>
                <td width="30%">{{ $mobileLinked }}</td>
            </tr>
            <tr>
                <th>Service Requested</th>
                <td>{{ $application->service_name }}</td>
                <th>Submitted By</th>
                <td>{{ auth()->user()->name ?? '-' }}</td>
            </tr>
        </table>

        {{-- ==========================================================
             ADDITIONAL SERVICE DETAILS
             (auto-generated from any form_data / documents fields not
             already shown above — keeps this template generic across
             every Aadhaar service type, e.g. Mobile Number Update,
             Address Update, Name Update, etc.)
        ========================================================== --}}
        @if($extraFields->isNotEmpty() || $extraDocuments->isNotEmpty())
            <div class="section-heading">ADDITIONAL SERVICE DETAILS</div>
            <table class="info-table">
                @foreach($extraFields->chunk(2) as $pair)
                    <tr>
                        @foreach($pair as $label => $value)
                            <th width="25%">{{ $label }}</th>
                            <td width="25%">{{ $value }}</td>
                        @endforeach
                        @if($pair->count() === 1)
                            <th width="25%"></th>
                            <td width="25%"></td>
                        @endif
                    </tr>
                @endforeach

                @if($extraDocuments->isNotEmpty())
                    <tr>
                        <th width="25%">Documents Uploaded</th>
                        <td colspan="3">{{ $extraDocuments->keys()->implode(', ') }}</td>
                    </tr>
                @endif
            </table>
        @endif

        {{-- APPLICANT DECLARATION --}}
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