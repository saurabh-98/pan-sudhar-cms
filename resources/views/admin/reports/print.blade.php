<!DOCTYPE html>
<html>
<head>
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; margin: 20px; }
        h3 { margin-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #999; padding: 6px 8px; text-align: left; }
        th { background: #f0f0f0; }
        .summary { margin-top: 10px; }
        .summary span { margin-right: 20px; }
        @media print {
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">

    <div class="no-print" style="text-align:right;">
        <button onclick="window.print()">Print</button>
    </div>

    <h3>{{ $title }}</h3>
    <div>Generated: {{ now()->format('d-m-Y h:i A') }}</div>

    <div class="summary">
        <span><strong>Total Applications:</strong> {{ $summary['applications'] }}</span>
        <span><strong>Total Amount:</strong> ₹{{ number_format($summary['amount'], 2) }}</span>
        <span><strong>Approved:</strong> {{ $summary['approved'] }}</span>
        <span><strong>Pending:</strong> {{ $summary['pending'] }}</span>
        <span><strong>Processing:</strong> {{ $summary['processing'] }}</span>
        <span><strong>Rejected:</strong> {{ $summary['rejected'] }}</span>
    </div>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Application</th>
                <th>Retailer</th>
                <th>Executive</th>
                <th>Amount</th>
                <th>Payment</th>
                <th>Status</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $row->application_no }}</td>
                    <td>{{ optional($row->user)->name }}</td>
                    <td>{{ optional($row->assignedUser)->name }}</td>
                    <td>{{ number_format($row->amount ?? $row->charge ?? 0, 2) }}</td>
                    <td>{{ $row->payment_status }}</td>
                    <td>{{ $row->status }}</td>
                    <td>{{ $row->created_at->format('d-m-Y h:i A') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
