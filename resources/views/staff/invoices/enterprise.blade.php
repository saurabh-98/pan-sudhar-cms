<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>

body {
    font-family: DejaVu Sans;
    font-size: 12px;
    color: #2c3e50;
}

/* HEADER */
.header {
    border-bottom: 2px solid #eaeaea;
    padding-bottom: 12px;
    margin-bottom: 20px;
}

.header-table { width:100%; }

.company {
    font-size: 18px;
    font-weight: bold;
    color: #e67e22;
}

.small {
    font-size: 11px;
    color: #7f8c8d;
}

.invoice-title {
    font-size: 20px;
    font-weight: bold;
}

/* BADGE */
.badge {
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 10px;
    color: #fff;
}

.pending { background: #f39c12; }
.preparing { background: #3498db; }
.delivered { background: #2ecc71; }
.completed { background: #27ae60; }
.cancelled { background: #e74c3c; }

/* TABLE */
table {
    width:100%;
    border-collapse: collapse;
}

th {
    background:#f8f9fa;
    padding:10px;
    font-weight:bold;
}

td {
    padding:10px;
    border-bottom:1px solid #eee;
}

.text-right { text-align:right; }

/* TOTAL */
.total-section { margin-top:20px; }

.total-box {
    width:320px;
    float:right;
}

.total-box td { padding:6px; }

.total-box tr:last-child td {
    border-top:2px solid #ddd;
}

.grand {
    font-size:16px;
    font-weight:bold;
    color:#27ae60;
}

/* QR */
.qr {
    margin-top:40px;
    text-align:center;
}

.qr img {
    border:1px solid #eee;
    padding:5px;
    border-radius:10px;
}

/* FOOTER */
.footer {
    margin-top:60px;
    text-align:center;
    font-size:11px;
    color:#95a5a6;
}

</style>
</head>

<body>

<!-- HEADER -->
<div class="header">
<table class="header-table">
<tr>
<td>
    <div class="company">🍽️ {{ config('app.name') }}</div>
    <div class="small">123 Street, Your City</div>
    <div class="small">GSTIN: 22AAAAA0000A1Z5</div>
    <div class="small">support@restaurant.com</div>
</td>

<td style="text-align:right">
    <div class="invoice-title">GST INVOICE</div>
    <div>#{{ $order->invoice_no ?? $order->id }}</div>

    <div class="small">
        Status:
        <span class="badge {{ $order->status }}">
            {{ ucfirst($order->status) }}
        </span>
    </div>

    <div class="small">
        Date: {{ $order->created_at->timezone('Asia/Kolkata')->format('d M Y, h:i A') }}
    </div>
</td>
</tr>
</table>
</div>

<!-- CUSTOMER -->
<table style="margin-bottom:20px;">
<tr>
<td>
<strong>Billed To:</strong><br>
{{ $order->user->name }}<br>
{{ $order->user->email }}<br>
{{ $order->mobile ?? '' }}<br>
{{ $order->address ?? 'N/A' }}
</td>

<td style="text-align:right">
<strong>Payment:</strong><br>
{{ $order->payment_method ?? 'UPI' }}<br>
Status: {{ ucfirst($order->payment_status ?? 'pending') }}
</td>
</tr>
</table>

<!-- ITEMS -->
<table>
<thead>
<tr>
<th>#</th>
<th>Item</th>
<th>Qty</th>
<th class="text-right">Price</th>
<th class="text-right">Total</th>
</tr>
</thead>

<tbody>
@foreach($order->items as $index => $item)
<tr>
<td>{{ $index+1 }}</td>
<td>{{ $item->menu->name }}</td>
<td>{{ $item->quantity }}</td>
<td class="text-right">₹{{ number_format($item->price,2) }}</td>
<td class="text-right">
₹{{ number_format($item->price * $item->quantity,2) }}
</td>
</tr>
@endforeach
</tbody>
</table>

<!-- TOTAL -->
<div class="total-section">
<table class="total-box">

<tr>
<td>Subtotal</td>
<td class="text-right">
₹{{ number_format($order->total ?? $order->total_price,2) }}
</td>
</tr>

<tr>
<td>Discount</td>
<td class="text-right">
₹{{ number_format($order->discount ?? 0,2) }}
</td>
</tr>

<tr>
<td>CGST (9%)</td>
<td class="text-right">₹{{ number_format($cgst,2) }}</td>
</tr>

<tr>
<td>SGST (9%)</td>
<td class="text-right">₹{{ number_format($sgst,2) }}</td>
</tr>

<tr class="grand">
<td>Total Payable</td>
<td class="text-right">
₹{{ number_format(($order->final_total ?? 0) + $cgst + $sgst,2) }}
</td>
</tr>

</table>
</div>

<div style="clear:both;"></div>

<!-- QR -->
@if($order->status !== 'cancelled')
<div class="qr">
    <img src="{{ $qr }}" width="140"><br>
    <span class="small">Scan & Pay via UPI</span><br>
    <strong class="small">{{ $upi->upi_id ?? '' }}</strong>
</div>
@endif

<!-- FOOTER -->
<div class="footer">
This is a system-generated invoice.<br>
Thank you for your business ❤️<br><br>

<strong>Authorized Signature</strong>
</div>

</body>
</html>