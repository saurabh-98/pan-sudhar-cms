<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payment Receipt</title>

    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .container {
            border: 2px solid #2c3e50;
            padding: 15px;
            border-radius: 10px;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #2c3e50;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }

        .header h2 {
            margin: 0;
            color: #2c3e50;
        }

        .info {
            margin-bottom: 10px;
        }

        .info strong {
            width: 140px;
            display: inline-block;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th, table td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }

        table th {
            background: #f4f4f4;
        }

        .total {
            margin-top: 10px;
            text-align: right;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 11px;
            color: #777;
        }

        .status {
            padding: 4px 8px;
            border-radius: 5px;
            font-weight: bold;
        }

        .pending { background: orange; color: #fff; }
        .approved { background: green; color: #fff; }
        .rejected { background: red; color: #fff; }

    </style>
</head>

<body>

<div class="container">

    <!-- HEADER -->
    <div class="header">
        <h2>School Payment Receipt</h2>
        <p>Academic Year: {{ $admission->academic_year }}</p>
    </div>

    <!-- STUDENT -->
    <div class="info">
        <strong>Student Name:</strong> {{ $admission->name }}
    </div>

    <div class="info">
        <strong>Class:</strong> {{ optional($admission->studentClass)->name }}
    </div>

    <div class="info">
        <strong>Registration No:</strong> {{ $admission->registration_no ?? '-' }}
    </div>

    <div class="info">
        <strong>Payment ID:</strong> {{ $admission->payment_id }}
    </div>

    <div class="info">
        <strong>Date:</strong> {{ $admission->paid_at }}
    </div>

    <div class="info">
        <strong>UTR No:</strong> {{ $admission->utr_no ?? 'N/A' }}
    </div>

    <!-- STATUS -->
    <div class="info">
        <strong>Status:</strong>
        <span class="status {{ $admission->verification_status }}">
            {{ ucfirst($admission->verification_status ?? 'pending') }}
        </span>
    </div>

    <!-- BREAKDOWN -->
    <h4>Fee Breakdown</h4>

    <table>
        <thead>
            <tr>
                <th>Fee Type</th>
                <th>Amount (₹)</th>
            </tr>
        </thead>
        <tbody>

        @if($admission->fee_breakdown)
            @foreach($admission->fee_breakdown as $fee)
                <tr>
                    <td>{{ ucfirst($fee['type']) }}</td>
                    <td>{{ number_format($fee['amount'], 2) }}</td>
                </tr>
            @endforeach
        @endif

        </tbody>
    </table>

    <!-- TOTAL -->
    <div class="total">
        <p><strong>Total:</strong> ₹ {{ number_format($admission->total_fee, 2) }}</p>
        <p><strong>Paid:</strong> ₹ {{ number_format($admission->paid_amount, 2) }}</p>
        <p><strong>Due:</strong> ₹ {{ number_format($admission->due_amount, 2) }}</p>
    </div>

    <!-- FOOTER -->
    <div class="footer">
        <p>This is a system-generated receipt.</p>
    </div>

</div>

</body>
</html>