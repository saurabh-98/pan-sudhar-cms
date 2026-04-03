<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">

<style>

body {
    font-family: DejaVu Sans;
    font-size: 12px;
    background: #f4f6f9;
    color: #2c3e50;
    margin: 0;
}

/* CONTAINER */
.container {
    padding: 25px;
}

/* HEADER */
.header {
    display: table;
    width: 100%;
    margin-bottom: 20px;
}

.header-left {
    display: table-cell;
    width: 60%;
}

.header-right {
    display: table-cell;
    width: 40%;
    text-align: right;
}

.logo {
    font-size: 24px;
    font-weight: bold;
    color: #ff6b3d;
}

.invoice-box {
    border: 1px solid #eee;
    padding: 12px;
    border-radius: 8px;
    background: #ffffff;
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

/* CARD */
.card {
    border: 1px solid #eee;
    border-radius: 10px;
    padding: 15px;
    margin-bottom: 20px;
    background: #ffffff;
}

/* TABLE */
table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background: linear-gradient(90deg, #ff6b3d, #ff8c42);
    color: #fff;
    padding: 10px;
    text-align: left;
}

td {
    padding: 10px;
    border-bottom: 1px solid #eee;
}

.text-right {
    text-align: right;
}

/* TOTAL BOX */
.total-box {
    width: 320px;
    float: right;
    margin-top: 20px;
    border-radius: 10px;
    border: 1px solid #eee;
    background: #ffffff;
    padding: 12px;
}

.total-box td {
    padding: 6px;
}

/* FINAL TOTAL */
.total-final {
    font-size: 18px;
    font-weight: bold;
    color: #27ae60;
}

/* QR SECTION */
.qr {
    text-align: center;
    margin-top: 40px;
}

.qr img {
    border: 2px dashed #ddd;
    padding: 10px;
    border-radius: 12px;
}

/* PAYMENT BOX */
.pay-box {
    display: inline-block;
    background: #e8fff3;
    padding: 10px 15px;
    border-radius: 8px;
    margin-top: 10px;
    border: 1px solid #b2f2d7;
}

/* FOOTER */
.footer {
    text-align: center;
    margin-top: 60px;
    font-size: 11px;
    color: #777;
}

</style>
</head>

<body>

<div class="container">

<!-- HEADER -->
<div class="header">
    <div class="header-left">
        <div class="logo">🍽️ {{ config('app.name') }}</div>
        <div>123 Street, Your City</div>
        <div>GSTIN: 22AAAAA0000A1Z5</div>
    </div>

    <div class="header-right">
        <div class="invoice-box">
            <strong>GST INVOICE</strong><br>
            #{{ $order->invoice_no ?? $order->id }}<br><br>

            Status:
            <span class="badge {{ $order->status }}">
                {{ ucfirst($order->status) }}
            </span><br>

            {{ $order->created_at->format('d M Y, h:i A') }}
        </div>
    </div>
</div>

<!-- CUSTOMER -->
<div class="card">
    <strong>Customer:</strong> {{ $order->user->name }} <br>
    <strong>Email:</strong> {{ $order->user->email ?? '-' }} <br>
    <strong>Mobile:</strong> {{ $order->mobile ?? '-' }} <br>
    <strong>Order Type:</strong> {{ ucfirst($order->order_type) }} <br>

    @if($order->order_type == 'inside')
        <strong>Table:</strong> {{ $order->table_number }}
    @else
        <strong>Address:</strong> {{ $order->address }}
    @endif
</div>

<!-- ITEMS -->
<table>
<thead>
<tr>
<th>Item</th>
<th>Qty</th>
<th class="text-right">Price</th>
<th class="text-right">Total</th>
</tr>
</thead>

<tbody>
@foreach($order->items as $item)
<tr>
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
<div class="total-box">
<table>

<tr>
<td>Subtotal</td>
<td class="text-right">₹{{ number_format($subtotal,2) }}</td>
</tr>

<tr>
<td>Discount</td>
<td class="text-right">- ₹{{ number_format($discount,2) }}</td>
</tr>

<tr>
<td>Delivery</td>
<td class="text-right">₹{{ number_format($delivery,2) }}</td>
</tr>

<tr>
<td>CGST (9%)</td>
<td class="text-right">₹{{ number_format($cgst,2) }}</td>
</tr>

<tr>
<td>SGST (9%)</td>
<td class="text-right">₹{{ number_format($sgst,2) }}</td>
</tr>

<tr class="total-final">
<td>Total</td>
<td class="text-right">₹{{ number_format($finalTotal,2) }}</td>
</tr>

</table>
</div>

<div style="clear: both;"></div>

<!-- QR -->
@if(!empty($qr))
<div class="qr">

    <img src="{{ $qr }}" width="140"><br>

    <div class="pay-box">
        <strong>Scan & Pay ₹{{ number_format($finalTotal,2) }}</strong>
    </div>

    <div style="font-size:11px; color:#777; margin-top:5px;">
        UPI Accepted • GPay • PhonePe • Paytm
    </div>

</div>
@endif

<!-- FOOTER -->
<div class="footer">
    This is a computer-generated invoice <br>
    Thank you for your order ❤️ <br><br>
    <strong>Authorized Signature</strong>
</div>

</div>

</body>
</html>