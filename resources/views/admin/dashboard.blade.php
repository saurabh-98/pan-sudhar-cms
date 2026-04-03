@extends('layout.admin')

@section('content')

<h3 class="mb-4 dashboard-title">👋 Welcome back, Admin</h3>

<!-- STATS -->
<div class="row g-4">

    <div class="col-md-3">
        <div class="card-box gradient-green">
            <div class="d-flex justify-content-between">
                <div>
                    <h6>Categories</h6>
                    <h2 class="counter">{{ $categories }}</h2>
                </div>
                <i class="fa fa-list fa-2x opacity-50"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-box gradient-blue">
            <div class="d-flex justify-content-between">
                <div>
                    <h6>Menus</h6>
                    <h2 class="counter">{{ $menus }}</h2>
                </div>
                <i class="fa fa-utensils fa-2x opacity-50"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-box gradient-yellow">
            <div class="d-flex justify-content-between">
                <div>
                    <h6>Sliders</h6>
                    <h2 class="counter">{{ $sliders }}</h2>
                </div>
                <i class="fa fa-image fa-2x opacity-50"></i>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card-box gradient-red">
            <div class="d-flex justify-content-between">
                <div>
                    <h6>Orders</h6>
                    <h2 class="counter">{{ $orders }}</h2>
                </div>
                <i class="fa fa-shopping-cart fa-2x opacity-50"></i>
            </div>
        </div>
    </div>

</div>

<!-- ANALYTICS -->
<div class="row mt-4 g-4">

    <div class="col-md-4">
        <div class="card-box dark-card">
            <h6>Total Revenue</h6>
            <h2>₹{{ number_format($totalRevenue) }}</h2>
            <small class="text-success">↑ Growth this month</small>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card-box info-card">
            <h6>Pending Orders</h6>
            <h2 class="counter">{{ $pending }}</h2>
            <small class="text-warning">Needs attention</small>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card-box success-card">
            <h6>Delivered Orders</h6>
            <h2 class="counter">{{ $delivered }}</h2>
            <small class="text-success">Completed successfully</small>
        </div>
    </div>

</div>

<!-- CHARTS -->
<div class="row mt-4 g-4">

    <div class="col-md-6">
        <div class="card dashboard-card">
            <div class="card-header">
                <h6>📈 Revenue Overview</h6>
            </div>
            <div class="card-body">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>

    <div class="col-md-6">
        <div class="card dashboard-card">
            <div class="card-header">
                <h6>📊 Order Status</h6>
            </div>
            <div class="card-body">
                <canvas id="orderChart"></canvas>
            </div>
        </div>
    </div>

</div>

@endsection


@section('scripts')

<!-- CHART JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
document.addEventListener("DOMContentLoaded", function () {

    /* =========================
       COUNTER ANIMATION (SAFE)
    ========================= */
    document.querySelectorAll('.counter').forEach(el => {

        let target = parseInt(el.innerText) || 0;
        let count = 0;
        let speed = Math.max(1, target / 40);

        function update() {
            count += speed;
            if (count < target) {
                el.innerText = Math.floor(count);
                requestAnimationFrame(update);
            } else {
                el.innerText = target.toLocaleString();
            }
        }

        update();
    });

    /* =========================
       REVENUE CHART
    ========================= */
    const revenueCanvas = document.getElementById('revenueChart');

    if (revenueCanvas) {
        new Chart(revenueCanvas, {
            type: 'line',
            data: {
                labels: ['Jan','Feb','Mar','Apr','May','Jun'],
                datasets: [{
                    label: 'Revenue',
                    data: [100, 300, 250, 400, 350, {{ $totalRevenue }}],
                    borderColor: '#ff5722',
                    backgroundColor: 'rgba(255,87,34,0.1)',
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return '₹ ' + context.raw;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    /* =========================
       ORDER STATUS CHART
    ========================= */
    const orderCanvas = document.getElementById('orderChart');

    if (orderCanvas) {
        new Chart(orderCanvas, {
            type: 'doughnut',
            data: {
                labels: ['Pending','Delivered'],
                datasets: [{
                    data: [{{ $pending }}, {{ $delivered }}],
                    backgroundColor: ['#f39c12', '#27ae60'],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });
    }

});
</script>

@endsection