<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <title>
        Aadhaar Receipt - {{ $application->application_no }}
    </title>

    <link rel="stylesheet" href="{{ public_path('assets/css/aadhaar-receipt.css') }}">
</head>

<body>

{{-- ==========================================================
    PAN SUDHAR PORTAL
    PREMIUM RECEIPT
========================================================== --}}

<div class="receipt">

<div class="receipt-border">

<div class="receipt-top">

<table class="header-table">

<tr>

<td width="18%" class="logo-area">

<img
src="{{ public_path('assets/images/logo.png') }}"
class="logo">

</td>

<td width="64%" class="title-area">

<h1>

PAN SUDHAR PORTAL

</h1>

<h2>

AADHAAR SERVICE ACKNOWLEDGEMENT RECEIPT

</h2>

<p>

Digital Citizen Service Center

</p>

</td>

<td width="18%" class="receipt-copy">

<div class="copy-box">

OFFICE COPY

</div>

</td>

</tr>

</table>

</div>

{{-- ============================= --}}
{{-- ACKNOWLEDGEMENT BAR --}}
{{-- ============================= --}}

<table class="acknowledge-table">

<tr>

<td width="25%">

<strong>

Receipt No

</strong>

<br>

RCPT-{{ $application->application_no }}

</td>

<td width="25%">

<strong>

Application No

</strong>

<br>

{{ $application->application_no }}

</td>

<td width="25%">

<strong>

Date

</strong>

<br>

{{ $application->created_at->format('d M Y') }}

</td>

<td width="25%">

<strong>

Time

</strong>

<br>

{{ $application->created_at->format('h:i A') }}

</td>

</tr>

</table>

{{-- ============================= --}}
{{-- BARCODE --}}
{{-- ============================= --}}

<table class="barcode-table">

<tr>

<td width="70%">

<div class="barcode-box">

<img

src="{{ public_path('assets/images/barcode-demo.png') }}"

class="barcode">

</div>

</td>

<td width="30%" align="center">

Receipt Verification

</td>

</tr>

</table>

{{-- ============================= --}}
{{-- APPLICATION INFORMATION --}}
{{-- ============================= --}}

<div class="section-heading">

APPLICATION INFORMATION

</div>

<table class="info-table">

<tr>

<th width="25%">

Application Number

</th>

<td width="25%">

{{ $application->application_no }}

</td>

<th width="25%">

Application Date

</th>

<td width="25%">

{{ $application->created_at->format('d-m-Y') }}

</td>

</tr>

<tr>

<th>

Service

</th>

<td>

{{ $application->service_name }}

</td>

<th>

Status

</th>

<td>

{{ strip_tags($application->status_badge) }}

</td>

</tr>

<tr>

<th>

Payment Status

</th>

<td>

{{ strip_tags($application->payment_badge) }}

</td>

<th>

Amount Paid

</th>

<td>

₹ {{ number_format($application->amount,2) }}

</td>

</tr>

</table>

{{-- ============================= --}}
{{-- RETAILER DETAILS --}}
{{-- ============================= --}}

<div class="section-heading">

RETAILER INFORMATION

</div>

<table class="info-table">

<tr>

<th width="25%">

Retailer ID

</th>

<td width="25%">

{{ auth()->user()->registration_no }}

</td>

<th width="25%">

Retailer Name

</th>

<td width="25%">

{{ auth()->user()->name }}

</td>

</tr>

<tr>

<th>

Mobile

</th>

<td>

{{ auth()->user()->mobile }}

</td>

<th>

Email

</th>

<td>

{{ auth()->user()->email }}

</td>

</tr>

</table>

{{-- ==========================================================
    APPLICANT INFORMATION
========================================================== --}}

<div class="section-heading">

    APPLICANT INFORMATION

</div>

<table class="applicant-table">

    <tr>

        <th width="18%">

            Applicant Name

        </th>

        <td width="32%">

            {{ $application->form_data['applicant_name'] ?? '-' }}

        </td>

        <th width="18%">

            Passport Photo

        </th>

        <td width="32%" rowspan="6" class="photo-column">

            @php

                $photo =
                    $application->documents['photo']
                    ?? $application->documents['passport_photo']
                    ?? null;

            @endphp

            @if($photo)

                <img
                    src="{{ public_path('storage/'.$photo) }}"
                    class="passport-photo">

            @else

                <div class="photo-placeholder">

                    PASSPORT PHOTO

                </div>

            @endif

        </td>

    </tr>

    <tr>

        <th>

            Father Name

        </th>

        <td>

            {{ $application->form_data['father_name'] ?? '-' }}

        </td>

        <th>

            Gender

        </th>

    </tr>

    <tr>

        <th>

            Mother Name

        </th>

        <td>

            {{ $application->form_data['mother_name'] ?? '-' }}

        </td>

        <td>

            {{ $application->form_data['gender'] ?? '-' }}

        </td>

    </tr>

    <tr>

        <th>

            Aadhaar Number

        </th>

        <td>

            XXXXXXXX{{ substr($application->form_data['aadhaar_number'] ?? '',-4) }}

        </td>

        <th>

            DOB

        </th>

    </tr>

    <tr>

        <th>

            Mobile Number

        </th>

        <td>

            {{ $application->form_data['mobile'] ?? '-' }}

        </td>

        <td>

            {{ $application->form_data['dob'] ?? '-' }}

        </td>

    </tr>

    <tr>

        <th>

            Email Address

        </th>

        <td>

            {{ $application->form_data['email'] ?? '-' }}

        </td>

        <th>

            Category

        </th>

    </tr>

    <tr>

        <th>

            Category

        </th>

        <td>

            {{ $application->form_data['category'] ?? 'General' }}

        </td>

        <th>

            Marital Status

        </th>

        <td>

            {{ $application->form_data['marital_status'] ?? '-' }}

        </td>

    </tr>

</table>

{{-- ==========================================================
    ADDRESS DETAILS
========================================================== --}}

<div class="section-heading">

    ADDRESS INFORMATION

</div>

<table class="address-table">

    <tr>

        <th width="20%">

            Address

        </th>

        <td colspan="5">

            {{ $application->form_data['address'] ?? '-' }}

        </td>

    </tr>

    <tr>

        <th>

            Village / City

        </th>

        <td>

            {{ $application->form_data['city'] ?? '-' }}

        </td>

        <th>

            District

        </th>

        <td>

            {{ $application->form_data['district'] ?? '-' }}

        </td>

        <th>

            State

        </th>

        <td>

            {{ $application->form_data['state'] ?? '-' }}

        </td>

    </tr>

    <tr>

        <th>

            PIN Code

        </th>

        <td>

            {{ $application->form_data['pin_code'] ?? '-' }}

        </td>

        <th>

            Country

        </th>

        <td colspan="3">

            {{ $application->form_data['country'] ?? 'India' }}

        </td>

    </tr>

</table>

{{-- ==========================================================
    IDENTITY DETAILS
========================================================== --}}

<div class="section-heading">

    IDENTITY INFORMATION

</div>

<table class="identity-table">

    <tr>

        <th width="20%">

            Aadhaar Number

        </th>

        <td width="30%">

            XXXXXXXX{{ substr($application->form_data['aadhaar_number'] ?? '',-4) }}

        </td>

        <th width="20%">

            Mobile Linked

        </th>

        <td width="30%">

            {{ $application->form_data['mobile_linked'] ?? 'Yes' }}

        </td>

    </tr>

    <tr>

        <th>

            Service Requested

        </th>

        <td>

            {{ $application->service_name }}

        </td>

        <th>

            Submitted By

        </th>

        <td>

            {{ auth()->user()->name }}

        </td>

    </tr>

</table>

{{-- ==========================================================
    APPLICANT DECLARATION
========================================================== --}}

<div class="section-heading">

    APPLICANT DECLARATION

</div>

<div class="declaration-box">

    I hereby declare that all the information furnished by me in this
    application is true and correct to the best of my knowledge and
    belief. I understand that if any information is found false or
    incorrect, my application may be rejected without any notice.

</div>

</body>

</html>