@extends('layout.customer')

@section('content')

<style>
/* ===== DASHBOARD STYLING ===== */

.dashboard-cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
}

.card-box {
    background: linear-gradient(135deg, #ffffff, #f8f9fa);
    border-radius: 18px;
    padding: 20px;
    text-align: center;
    transition: 0.3s;
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
}

.card-box:hover {
    transform: translateY(-5px) scale(1.02);
    box-shadow: 0 15px 30px rgba(0,0,0,0.1);
}

.card-icon {
    font-size: 28px;
    margin-bottom: 10px;
}

.card-box h2 {
    font-weight: bold;
    color: #ff5a00;
}

/* ===== QUICK ACTIONS ===== */
.quick-actions {
    display: flex;
    gap: 15px;
    margin-top: 25px;
}

.action-btn {
    flex: 1;
    padding: 14px;
    text-align: center;
    border-radius: 12px;
    font-weight: bold;
    text-decoration: none;
    transition: 0.3s;
}

.action-btn.primary {
    background: linear-gradient(135deg, #ff5a00, #ff8c42);
    color: #fff;
}

.action-btn.secondary {
    background: #f1f1f1;
    color: #333;
}

.action-btn:hover {
    transform: translateY(-3px);
    opacity: 0.9;
}

/* ===== RECENT ORDERS ===== */
.recent-box {
    background: #fff;
    border-radius: 16px;
    padding: 20px;
    box-shadow: 0 10px 25px rgba(0,0,0,0.05);
}

.table tr:hover {
    background: #fafafa;
    transition: 0.2s;
}

.badge {
    padding: 6px 10px;
    border-radius: 8px;
}

/* ===== EMPTY STATE ===== */
.empty-state {
    text-align: center;
    padding: 30px;
    background: #f9f9f9;
    border-radius: 12px;
    color: #777;
}

/* ===== HEADER ===== */
.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.dashboard-header h3 {
    font-weight: bold;
}

</style>

<div class="container mt-4">

    <!-- HEADER -->
    <div class="dashboard-header mb-4">
        <h3>👋 Welcome, {{ auth()->user()->name }}</h3>
        <span class="text-muted">Have a great day 🍽️</span>
    </div>

    <!-- STATS -->
    <div class="dashboard-cards">

        <div class="card-box">
            <div class="card-icon">🛒</div>
            <h6>Total Orders</h6>
            <h2>{{ $ordersCount }}</h2>
        </div>

        <div class="card-box">
            <div class="card-icon">📅</div>
            <h6>Reservations</h6>
            <h2>{{ $reservationCount }}</h2>
        </div>

        <div class="card-box">
            <div class="card-icon">💰</div>
            <h6>Total Spent</h6>
            <h2>₹{{ number_format($totalSpent, 2) }}</h2>
        </div>

    </div>

    <!-- ACTIONS -->
    <div class="quick-actions">

        <a href="{{ route('customer.orders') }}" class="action-btn primary">
            🛒 Order Food
        </a>

        <a href="{{ route('customer.reservations') }}" class="action-btn secondary">
            📅 Book Table
        </a>

    </div>

    <!-- RECENT ORDERS -->
    <div class="recent-box mt-4">

        <h5 class="mb-3">📦 Recent Orders</h5>

        @if($recentOrders->count())

            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                        <tr>
                            <td>#ORD-{{ $order->id }}</td>
                            <td>₹{{ number_format($order->total, 2) }}</td>
                            <td>
                                <span class="badge 
                                    @if($order->status == 'pending') bg-warning
                                    @elseif($order->status == 'confirmed') bg-primary
                                    @elseif($order->status == 'preparing') bg-info
                                    @elseif($order->status == 'delivered') bg-success
                                    @else bg-danger
                                    @endif
                                ">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

        @else

            <div class="empty-state">
                📦 No orders yet <br>
                <small>Start ordering delicious food 🍔</small>
            </div>

        @endif

    </div>

</div>

@endsection