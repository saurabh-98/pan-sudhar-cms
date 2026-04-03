@extends('layout.customer')

@section('content')

<style>

/* ===== GLOBAL ===== */
.invoice-box {
    background:#fff;
    border-radius:18px;
    padding:20px;
    box-shadow:0 10px 30px rgba(0,0,0,0.08);
}

/* HEADER */
.order-header {
    display:flex;
    justify-content:space-between;
    flex-wrap:wrap;
    gap:10px;
}

/* STATUS */
.status-badge {
    padding:6px 14px;
    border-radius:20px;
    font-weight:600;
}
.status-pending { background:#fff3cd; }
.status-confirmed { background:#cce5ff; }
.status-preparing { background:#d1ecf1; }
.status-delivered { background:#d4edda; }
.status-cancelled { background:#f8d7da; }

/* INFO */
.order-info {
    display:flex;
    flex-wrap:wrap;
    gap:10px;
}
.info-box {
    background:#f8f9fa;
    padding:10px;
    border-radius:10px;
    font-size:13px;
}

/* PROGRESS */
.progress-container {
    background:#eee;
    height:8px;
    border-radius:20px;
}
.progress-bar {
    height:100%;
    background:#ff5a00;
    transition:1s;
}

/* TIMELINE */
.timeline {
    display:flex;
    justify-content:space-between;
    margin:20px 0;
}
.step span {
    width:40px;height:40px;
    border-radius:50%;
    background:#ddd;
    display:flex;
    align-items:center;
    justify-content:center;
}
.step.active span {
    background:#ff5a00;
    color:#fff;
}

/* PAYMENT STATUS */
.pay-badge {
    padding: 6px 14px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 13px;
    display: inline-block;
}

/* COLORS */
.pay-paid {
    background: #d4edda;
    color: #155724;
}

.pay-pending {
    background: #fff3cd;
    color: #856404;
}

.pay-failed {
    background: #f8d7da;
    color: #721c24;
}

.pay-cod {
    background: #e2e3ff;
    color: #2c2cff;
}

/* MOBILE TABLE */
@media(max-width:768px){
    .table thead { display:none; }

    .table tr {
        display:block;
        margin-bottom:10px;
        padding:10px;
        border-radius:10px;
        background:#fff;
    }

    .table td {
        display:flex;
        justify-content:space-between;
        border:none;
        font-size:13px;
    }
}

/* TOTAL */
.total-box {
    background:#f8f9fa;
    padding:15px;
    border-radius:12px;
}

</style>

<div class="container mt-3">

    <x-back-button />

    <h4 class="mb-3">🧾 Order Details</h4>

    <div class="invoice-box">

        <!-- HEADER -->
        <div class="order-header">
            <div>
                <strong>#ORD-{{ $order->id }}</strong><br>
                <small>{{ $order->created_at->format('d M Y, h:i A') }}</small>
            </div>

            <span class="status-badge status-{{ $order->status }}">
                {{ ucfirst($order->status) }}
            </span>
                
                @php
                    $pay = strtolower($order->payment_status);
                @endphp

                <span class="pay-badge pay-{{ $pay }}">
                    @if($pay == 'paid')
                        💰 Paid
                    @elseif($pay == 'pending')
                        ⏳ Pending
                    @elseif($pay == 'failed')
                        ❌ Failed
                    @elseif($pay == 'cod')
                        💵 COD
                    @else
                        {{ ucfirst($pay) }}
                    @endif
                </span>
        </div>

        <!-- CANCELLED -->
        @if($order->status === 'cancelled')
        <div class="alert alert-danger mt-2">
            ❌ Order Cancelled
        </div>
        @endif

        <!-- INFO -->
        <div class="order-info mt-3">
            <div class="info-box">📞 {{ $order->mobile }}</div>
            <div class="info-box">🍽 {{ ucfirst($order->order_type) }}</div>
            @if($order->address)
            <div class="info-box">📍 {{ $order->address }}</div>
            @endif
        </div>

        <!-- PROGRESS -->
        <div class="progress-container mt-3">
            <div id="progressBar" class="progress-bar"></div>
        </div>

        <!-- TIMELINE -->
        <div class="timeline">
            <div class="step step1"><span>📝</span></div>
            <div class="step step2"><span>✅</span></div>
            <div class="step step3"><span>👨‍🍳</span></div>
            <div class="step step4"><span>🚚</span></div>
        </div>

        <!-- ITEMS -->
        <table class="table">
            <tbody>
            @foreach($order->items as $item)
                <tr>
                    <td><strong>Item</strong> {{ $item->menu->name }}</td>
                    <td><strong>Qty</strong> {{ $item->quantity }}</td>
                    <td><strong>Price</strong> ₹{{ number_format($item->price,2) }}</td>
                    <td><strong>Total</strong> ₹{{ number_format($item->price * $item->quantity,2) }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <!-- TOTAL (🔥 FIXED) -->
        <div class="total-box mt-3">
            <p>Subtotal: ₹{{ number_format($summary['subtotal'],2) }}</p>
            <p>Discount: -₹{{ number_format($summary['discount'],2) }}</p>
            <p>Tax: ₹{{ number_format($summary['tax'],2) }}</p>
            <p>Delivery: ₹{{ number_format($summary['delivery'],2) }}</p>

            <hr>

            <h5 class="text-success">
                Final Amount: ₹{{ number_format($summary['total'],2) }}
            </h5>
        </div>

        <!-- ACTIONS -->
        <div class="mt-3 d-flex flex-wrap gap-2">
            <a target="_blank"
               href="{{ route('customer.orders.invoice',$order->id) }}"
               class="btn btn-primary">
               Invoice
            </a>

            @if(in_array($order->status,['pending','confirmed']))
            <button class="btn btn-danger" id="cancelBtn">
                Cancel
            </button>
            @endif
        </div>

    </div>
</div>

@endsection


@section('scripts')
<script>

// STATUS PROGRESS
let map = {
    pending:25,
    confirmed:50,
    preparing:75,
    delivered:100,
    cancelled:0
};

let status = "{{ $order->status }}";

$('#progressBar').css('width', map[status] + '%');

if(status !== 'cancelled'){
    if(map[status]>=25) $('.step1').addClass('active');
    if(map[status]>=50) $('.step2').addClass('active');
    if(map[status]>=75) $('.step3').addClass('active');
    if(map[status]>=100) $('.step4').addClass('active');
}

// CANCEL ORDER
$('#cancelBtn').click(function(){

    Swal.fire({
        title:'Cancel Order?',
        icon:'warning',
        showCancelButton:true
    }).then(res=>{

        if(res.isConfirmed){

            $.post("{{ route('customer.orders.cancel',$order->id) }}",{
                _token:"{{ csrf_token() }}"
            },function(response){

                if(response.success){
                    Swal.fire('Cancelled!', response.message, 'success')
                        .then(()=> location.reload());
                }

            });

        }

    });

});

</script>
@endsection