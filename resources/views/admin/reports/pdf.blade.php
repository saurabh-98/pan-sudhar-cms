<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        /* dompdf has limited CSS support, so this is kept intentionally plain */
        body { font-family: sans-serif; font-size: 11px; }
        h3 { margin-bottom: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #999; padding: 5px 6px; text-align: left; }
        th { background: #eee; }
    </style>
</head>
<body>

    <h3>{{ $title }}</h3>
    <div>Generated: {{ now()->format('d-m-Y h:i A') }}</div>

    <table>
        <tr>
            <td><strong>Total Applications:</strong> {{ $summary['applications'] }}</td>
            <td><strong>Total Amount:</strong> Rs. {{ number_format($summary['amount'], 2) }}</td>
            <td><strong>Approved:</strong> {{ $summary['approved'] }}</td>
            <td><strong>Pending:</strong> {{ $summary['pending'] }}</td>
        </tr>
    </table>

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
