@extends('layout.staff')

@section('content')

<style>
/* ===== DASHBOARD UI FIX ===== */
.card-box {
    border-radius: 12px;
    padding: 20px;
    color: #fff;
    position: relative;
    overflow: hidden;
    transition: 0.3s;
}

/* REMOVE FADED ISSUE */
.card-box * {
    opacity: 1 !important;
}

/* COLORS */
.gradient-red {
    background: linear-gradient(135deg, #ff4e50, #f9d423);
}

.info-card {
    background: linear-gradient(135deg, #36d1dc, #5b86e5);
}

.success-card {
    background: linear-gradient(135deg, #00b09b, #96c93d);
}

/* TEXT */
.card-box h6 {
    font-size: 14px;
    opacity: 0.9;
}

.card-box h2 {
    font-size: 32px;
    font-weight: bold;
}

/* ICON STYLE */
.card-box i {
    background: rgba(255,255,255,0.2);
    padding: 12px;
    border-radius: 50%;
}

/* HOVER */
.card-box:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

/* FORCE FIX IF OVERLAY EXISTS */
.dashboard-title,
.card-box {
    opacity: 1 !important;
    filter: none !important;
}
</style>

<div class="container">

    <h3 class="mb-4 dashboard-title">
        👨‍🍳 Welcome, {{ auth()->user()->name }}
    </h3>

    <!-- QUICK STATS -->
    <div class="row g-4">

        <div class="col-md-4">
            <div class="card-box gradient-red shadow">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Total Orders</h6>
                        <h2 class="counter">{{ $orders }}</h2>
                    </div>
                    <i class="fa fa-shopping-cart fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-box info-card shadow">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Pending Orders</h6>
                        <h2 class="counter">{{ $pending }}</h2>
                    </div>
                    <i class="fa fa-clock fa-2x"></i>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card-box success-card shadow">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h6>Delivered Orders</h6>
                        <h2 class="counter">{{ $delivered }}</h2>
                    </div>
                    <i class="fa fa-check-circle fa-2x"></i>
                </div>
            </div>
        </div>

    </div>

    <!-- RECENT ORDERS -->
    <div class="card mt-4 shadow">
        <div class="card-header">
            <h6>🧾 Recent Orders</h6>
        </div>

        <div class="card-body">
            <table class="table table-hover">

                <thead>
                    <tr>
                        <th>#ID</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($recentOrders as $order)
                        <tr>
                            <td>#{{ $order->id }}</td>
                            <td>{{ $order->user->name ?? 'Guest' }}</td>
                            <td>₹{{ number_format($order->final_total, 2) }}</td>

                            <td>
                                @if($order->status == 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @else
                                    <span class="badge bg-success">Delivered</span>
                                @endif
                            </td>

                            <td>
                                @if($order->status == 'pending')
                                    <button class="btn btn-sm btn-success markDelivered"
                                        data-id="{{ $order->id }}">
                                        ✔ Mark Delivered
                                    </button>
                                @else
                                    <span class="text-muted">✔ Already Delivered</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>

            </table>
        </div>
    </div>

</div>

@endsection


@section('scripts')

<script>
$(document).ready(function(){

    // COUNTER ANIMATION
    $('.counter').each(function(){
        let el = $(this);
        let target = parseInt(el.text());
        let count = 0;

        let interval = setInterval(()=>{
            count += Math.ceil(target / 30);
            if(count >= target){
                el.text(target);
                clearInterval(interval);
            } else {
                el.text(count);
            }
        }, 30);
    });

    // MARK DELIVERED
    $('.markDelivered').click(function(){

        let btn = $(this);
        let id = btn.data('id');

        Swal.fire({
            title: "Mark as Delivered?",
            text: "This action cannot be undone",
            icon: "question",
            showCancelButton: true,
            confirmButtonText: "Yes, Deliver it!"
        }).then((result)=>{

            if(result.isConfirmed){

                btn.prop('disabled', true).text('Processing...');

                $.ajax({
                    url: "/staff/orders/mark-delivered/" + id,
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(){

                        Swal.fire("Updated!", "Order marked as delivered", "success");

                        btn.closest('tr').find('.badge')
                            .removeClass('bg-warning')
                            .addClass('bg-success')
                            .text('Delivered');

                        btn.replaceWith('<span class="text-muted">✔ Already Delivered</span>');
                    },
                    error: function(){
                        Swal.fire("Error!", "Something went wrong", "error");
                        btn.prop('disabled', false).text('✔ Mark Delivered');
                    }
                });

            }

        });

    });

});
</script>

@endsection