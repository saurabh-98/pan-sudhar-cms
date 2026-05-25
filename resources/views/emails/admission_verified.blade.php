<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(90deg, #2c3e50, #4ca1af);
            color: #fff;
            text-align: center;
            padding: 15px;
            font-size: 18px;
            font-weight: bold;
        }
        .content {
            padding: 20px;
            color: #333;
            font-size: 14px;
        }
        .info-box {
            background: #f8f9fa;
            border-left: 4px solid #28a745;
            padding: 12px;
            margin: 15px 0;
            border-radius: 5px;
        }
        .payment-box {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 12px;
            margin: 15px 0;
            border-radius: 5px;
        }
        .btn {
            display: block;
            width: 100%;
            text-align: center;
            padding: 14px;
            background: #28a745;
            color: #fff !important;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 15px;
            font-weight: bold;
            font-size: 15px;
        }
        .btn:hover {
            background: #218838;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #777;
            padding: 10px;
            border-top: 1px solid #eee;
        }
        .link {
            word-break: break-all;
            font-size: 12px;
            color: #007bff;
        }
    </style>
</head>
<body>

<div class="container">

    <div class="header">
        🎓 Admission Verified
    </div>

    <div class="content">

        <p>Dear Parent,</p>

        <p>
            We are pleased to inform you that your child's admission has been
            <strong style="color:#28a745;">successfully verified</strong>.
        </p>

        <!-- STUDENT INFO -->
        <div class="info-box">
            <strong>👤 Student Name:</strong> {{ $admission->name }} <br>
            <strong>🆔 Application ID:</strong> {{ $admission->id }} <br>
            <strong>🏫 Class:</strong> {{ optional($admission->studentClass)->name }}
        </div>

        <!-- PAYMENT INFO -->
        <div class="payment-box">
            <strong>💰 Admission Fee:</strong> ₹ {{ $admission->fee ?? '---' }} <br>
            <strong>📅 Last Date:</strong> {{ now()->addDays(7)->format('d M Y') }}
        </div>

        <p><strong>Next Step:</strong></p>
        <p>
            Please complete the payment to confirm admission and generate login credentials.
        </p>

        <!-- PAYMENT BUTTON -->
        <a href="{{ url('/payment/'.$admission->id) }}" class="btn">
            💳 Proceed to Secure Payment
        </a>

        <!-- FALLBACK LINK (IMPORTANT FOR EMAIL CLIENTS) -->
        <p style="margin-top:15px;">
            If the button doesn't work, copy & paste this link:
        </p>

        <p class="link">
            {{ url('/payment/'.$admission->id) }}
        </p>

        <p style="margin-top:20px;">
            If you have any questions, feel free to contact us.
        </p>

        <p>
            Thank you,<br>
            <strong>School Management</strong>
        </p>

    </div>

    <div class="footer">
        © {{ date('Y') }} School Management System. All rights reserved.
    </div>

</div>

</body>
</html>