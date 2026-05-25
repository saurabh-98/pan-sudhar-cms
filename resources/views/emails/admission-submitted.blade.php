<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">

    <title>
        Admission Submitted
    </title>

</head>

<body style="
    margin:0;
    padding:0;
    background:#f1f5f9;
    font-family:Arial,sans-serif;
">

<div style="
    width:100%;
    padding:40px 0;
">

    <div style="
        max-width:700px;
        margin:auto;
        background:#ffffff;
        border:1px solid #dbeafe;
        overflow:hidden;
    ">

        <!-- =====================================
             HEADER
        ====================================== -->

        <div style="
            background:#2563eb;
            padding:35px 30px;
            text-align:center;
            color:#ffffff;
        ">

            <h1 style="
                margin:0;
                font-size:30px;
                font-weight:bold;
            ">

                SCHOOL MANAGEMENT SYSTEM

            </h1>

            <p style="
                margin-top:10px;
                font-size:14px;
                opacity:.92;
            ">

                Admission Application Submitted Successfully

            </p>

        </div>

        <!-- =====================================
             BODY
        ====================================== -->

        <div style="
            padding:35px 30px;
            color:#1e293b;
        ">

            <h2 style="
                margin-top:0;
                color:#1d4ed8;
                font-size:24px;
            ">

                Dear Parent,

            </h2>

            <p style="
                font-size:15px;
                line-height:1.8;
                margin-bottom:25px;
            ">

                Thank you for submitting your child's admission application.
                Your application has been received successfully and is currently under review by the admission department.

            </p>

            <!-- =====================================
                 INFO CARD
            ====================================== -->

            <div style="
                border:1px solid #dbeafe;
                background:#f8fbff;
                padding:22px;
                margin-bottom:28px;
            ">

                <table style="
                    width:100%;
                    border-collapse:collapse;
                    font-size:14px;
                ">

                    <tr>

                        <td style="
                            padding:10px 0;
                            font-weight:bold;
                            width:35%;
                            color:#334155;
                        ">

                            Student Name

                        </td>

                        <td style="
                            padding:10px 0;
                            color:#0f172a;
                        ">

                            {{ $admission->name ?? '-' }}

                        </td>

                    </tr>

                    <tr>

                        <td style="
                            padding:10px 0;
                            font-weight:bold;
                            color:#334155;
                        ">

                            Application Number

                        </td>

                        <td style="
                            padding:10px 0;
                            color:#0f172a;
                        ">

                            {{ $admission->application_no ?? '-' }}

                        </td>

                    </tr>

                    <tr>

                        <td style="
                            padding:10px 0;
                            font-weight:bold;
                            color:#334155;
                        ">

                            Class

                        </td>

                        <td style="
                            padding:10px 0;
                            color:#0f172a;
                        ">

                            {{ $admission->studentClass?->name ?? '-' }}

                        </td>

                    </tr>

                    <tr>

                        <td style="
                            padding:10px 0;
                            font-weight:bold;
                            color:#334155;
                        ">

                            Section

                        </td>

                        <td style="
                            padding:10px 0;
                            color:#0f172a;
                        ">

                            {{ $admission->section?->name ?? 'Pending Verification' }}

                        </td>

                    </tr>

                    <tr>

                        <td style="
                            padding:10px 0;
                            font-weight:bold;
                            color:#334155;
                        ">

                            Admission Status

                        </td>

                        <td style="
                            padding:10px 0;
                        ">

                            <span style="
                                display:inline-block;
                                background:#dcfce7;
                                color:#15803d;
                                padding:6px 14px;
                                font-size:12px;
                                font-weight:bold;
                            ">

                                {{ strtoupper($admission->status ?? 'PENDING') }}

                            </span>

                        </td>

                    </tr>

                </table>

            </div>

            <!-- =====================================
                 NOTICE
            ====================================== -->

            <div style="
                background:#eff6ff;
                border:1px solid #bfdbfe;
                padding:18px;
                margin-bottom:30px;
                color:#1e40af;
                font-size:14px;
                line-height:1.7;
            ">

                Please find the attached admission receipt PDF for your records.

                <br><br>

                Our admission team will review the application and notify you regarding further verification and approval process.

            </div>

            <!-- =====================================
                 BUTTON
            ====================================== -->

            <div style="
                text-align:center;
                margin-bottom:35px;
            ">

                <a href="{{ url('/track-admission') }}"
                   style="
                        display:inline-block;
                        background:#2563eb;
                        color:#ffffff;
                        text-decoration:none;
                        padding:14px 30px;
                        font-size:14px;
                        font-weight:bold;
                   ">

                    Track Admission Status

                </a>

            </div>

            <!-- =====================================
                 FOOTER TEXT
            ====================================== -->

            <p style="
                margin-bottom:8px;
                font-size:15px;
            ">

                Regards,

            </p>

            <p style="
                margin-top:0;
                font-size:15px;
                font-weight:bold;
                color:#1d4ed8;
            ">

                School Management Team

            </p>

        </div>

        <!-- =====================================
             FOOTER
        ====================================== -->

        <div style="
            background:#f8fafc;
            border-top:1px solid #e2e8f0;
            padding:22px;
            text-align:center;
            font-size:12px;
            color:#64748b;
            line-height:1.8;
        ">

            This is an automated email generated by the
            School Management System.

            <br>

            Please do not reply directly to this email.

        </div>

    </div>

</div>

</body>

</html>