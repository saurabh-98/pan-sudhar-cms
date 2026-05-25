<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">

    <title>
        Admission Receipt
    </title>

    <style>

        body{
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #1e293b;

            margin: 0;
            padding: 18px;

            background: #f1f5f9;
        }

        *{
            box-sizing: border-box;
        }

        .wrapper{

            background: #ffffff;

            border: 1px solid #dbeafe;
        }

        /* ====================================
           HEADER
        ==================================== */

        .header{

            background: #1d4ed8;

            padding: 28px;

            color: #ffffff;
        }

        .header-table{
            width: 100%;
        }

        .header-table td{
            border: none;
            vertical-align: middle;
        }

        .school-logo{

            width: 70px;
        }

        .school-title{

            font-size: 28px;

            font-weight: bold;

            margin-bottom: 5px;
        }

        .school-subtitle{

            font-size: 13px;

            opacity: .92;
        }

        /* ====================================
           BADGES
        ==================================== */

        .badge{

            display: inline-block;

            padding: 7px 14px;

            background: #2563eb;

            color: #fff;

            font-size: 11px;

            margin-right: 6px;

            margin-top: 10px;
        }

        /* ====================================
           BODY
        ==================================== */

        .body{
            padding: 24px;
        }

        /* ====================================
           PROFILE
        ==================================== */

        .profile{

            margin-bottom: 22px;
        }

        .profile-table{
            width: 100%;
        }

        .profile-table td{
            border: none;
            vertical-align: top;
        }

        .student-name{

            font-size: 24px;

            font-weight: bold;

            color: #1d4ed8;

            margin-bottom: 8px;
        }

        .student-meta{

            font-size: 13px;

            color: #475569;
        }

        .student-photo{

            width: 110px;

            height: 120px;

            border: 2px solid #dbeafe;

            object-fit: cover;
        }

        /* ====================================
           STATUS CARD
        ==================================== */

        .status-card{

            background: #eff6ff;

            border: 1px solid #bfdbfe;

            padding: 16px;

            margin-bottom: 24px;
        }

        .status-label{

            font-size: 11px;

            color: #475569;

            margin-bottom: 6px;
        }

        .status-value{

            display: inline-block;

            padding: 7px 16px;

            background: #dcfce7;

            color: #15803d;

            font-size: 12px;

            font-weight: bold;
        }

        /* ====================================
           SECTION
        ==================================== */

        .section{

            margin-bottom: 24px;

            border: 1px solid #e2e8f0;
        }

        .section-title{

            background: #eff6ff;

            color: #1d4ed8;

            padding: 12px 16px;

            font-size: 14px;

            font-weight: bold;

            border-bottom: 1px solid #dbeafe;
        }

        table{
            width: 100%;
            border-collapse: collapse;
        }

        td{

            padding: 11px 14px;

            border-bottom: 1px solid #f1f5f9;
        }

        tr:last-child td{
            border-bottom: none;
        }

        .label{

            width: 34%;

            background: #f8fafc;

            font-weight: bold;

            color: #334155;
        }

        .value{
            color: #0f172a;
        }

        /* ====================================
           SUCCESS
        ==================================== */

        .success{

            margin-top: 20px;

            padding: 16px;

            background: #ecfdf5;

            border: 1px solid #bbf7d0;

            color: #15803d;

            text-align: center;

            font-weight: bold;
        }

        /* ====================================
           SIGNATURE
        ==================================== */

        .signature{

            margin-top: 45px;
        }

        .signature-table{
            width: 100%;
        }

        .signature-table td{
            border: none;
            text-align: center;
            padding-top: 40px;
        }

        .sign-line{

            width: 190px;

            margin: auto;

            border-top: 1px solid #64748b;

            padding-top: 8px;

            font-size: 11px;
        }

        /* ====================================
           FOOTER
        ==================================== */

        .footer{

            background: #f8fafc;

            border-top: 1px solid #e2e8f0;

            padding: 16px;

            text-align: center;

            font-size: 10px;

            color: #64748b;
        }

    </style>

</head>

<body>

<div class="wrapper">

    <!-- HEADER -->

    <div class="header">

        <table class="header-table">

            <tr>

                <td width="80%">

                    <div class="school-title">

                        SCHOOL MANAGEMENT SYSTEM

                    </div>

                    <div class="school-subtitle">

                        Admission Confirmation Receipt

                    </div>

                    <div>

                        <span class="badge">

                            Application No :
                            {{ $admission->application_no ?? '-' }}

                        </span>

                        <span class="badge">

                            Date :
                            {{ date('d M Y') }}

                        </span>

                    </div>

                </td>

            </tr>

        </table>

    </div>

    <!-- BODY -->

    <div class="body">

        <!-- PROFILE -->

        <div class="profile">

            <table class="profile-table">

                <tr>

                    <td width="75%">

                        <div class="student-name">

                            {{ $admission->name ?? '-' }}

                        </div>

                        <div class="student-meta">

                            Class :
                            {{ $admission->studentClass?->name ?? '-' }}

                            |

                            Section :
                            {{ $admission->section?->name ?? '-' }}

                        </div>

                    </td>

                    <td align="right">

                        @if(
                            !empty($admission->photo)
                            &&
                            file_exists(
                                public_path(
                                    'storage/' .
                                    $admission->photo
                                )
                            )
                        )

                            <img
                                src="{{ public_path('storage/' . $admission->photo) }}"
                                class="student-photo">

                        @endif

                    </td>

                </tr>

            </table>

        </div>

        <!-- STATUS -->

        <div class="status-card">

            <div class="status-label">

                Admission Status

            </div>

            <div class="status-value">

                {{ strtoupper($admission->status ?? 'PENDING') }}

            </div>

        </div>

        <!-- STUDENT -->

        <div class="section">

            <div class="section-title">

                Student Information

            </div>

            <table>

                <tr>
                    <td class="label">Student Name</td>
                    <td class="value">{{ $admission->name ?? '-' }}</td>
                </tr>

                <tr>
                    <td class="label">Gender</td>
                    <td class="value">{{ $admission->gender ?? '-' }}</td>
                </tr>

                <tr>
                    <td class="label">Date of Birth</td>
                    <td class="value">{{ $admission->dob ?? '-' }}</td>
                </tr>

                <tr>
                    <td class="label">Blood Group</td>
                    <td class="value">{{ $admission->blood_group ?? '-' }}</td>
                </tr>

                <tr>
                    <td class="label">Religion</td>
                    <td class="value">{{ $admission->religion ?? '-' }}</td>
                </tr>

                <tr>
                    <td class="label">Category</td>
                    <td class="value">{{ $admission->category ?? '-' }}</td>
                </tr>

            </table>

        </div>

        <!-- PARENT -->

        <div class="section">

            <div class="section-title">

                Parent Information

            </div>

            <table>

                <tr>
                    <td class="label">Father Name</td>
                    <td class="value">{{ $admission->father_name ?? '-' }}</td>
                </tr>

                <tr>
                    <td class="label">Father Mobile</td>
                    <td class="value">{{ $admission->father_mobile ?? '-' }}</td>
                </tr>

                <tr>
                    <td class="label">Father Email</td>
                    <td class="value">{{ $admission->father_email ?? '-' }}</td>
                </tr>

                <tr>
                    <td class="label">Mother Name</td>
                    <td class="value">{{ $admission->mother_name ?? '-' }}</td>
                </tr>

                <tr>
                    <td class="label">Mother Mobile</td>
                    <td class="value">{{ $admission->mother_mobile ?? '-' }}</td>
                </tr>

            </table>

        </div>

        <!-- ADDRESS -->

        <div class="section">

            <div class="section-title">

                Address Information

            </div>

            <table>

                <tr>
                    <td class="label">State</td>
                    <td class="value">{{ $admission->state?->name ?? '-' }}</td>
                </tr>

                <tr>
                    <td class="label">District</td>
                    <td class="value">{{ $admission->district?->name ?? '-' }}</td>
                </tr>

                <tr>
                    <td class="label">Pincode</td>
                    <td class="value">{{ $admission->pincode ?? '-' }}</td>
                </tr>

                <tr>
                    <td class="label">Permanent Address</td>
                    <td class="value">{{ $admission->permanent_address ?? '-' }}</td>
                </tr>

            </table>

        </div>

        <!-- SUCCESS -->

        <div class="success">

            Thank you for submitting your admission application successfully.

        </div>

        <!-- SIGNATURE -->

        <div class="signature">

            <table class="signature-table">

                <tr>

                    <td>

                        <div class="sign-line">

                            Parent Signature

                        </div>

                    </td>

                    <td>

                        <div class="sign-line">

                            Authorized Signature

                        </div>

                    </td>

                </tr>

            </table>

        </div>

    </div>

    <!-- FOOTER -->

    <div class="footer">

        This is a system generated admission receipt.
        No physical signature is required.

    </div>

</div>

</body>
</html>