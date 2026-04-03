@extends('layout.admin')

@section('content')

<style>

/* 🔥 CARD */
.order-card {
    background:#fff;
    border-radius:12px;
    padding:20px;
    margin-bottom:20px;
    box-shadow:0 4px 15px rgba(0,0,0,0.05);
}

/* HEADER FIX */
.order-header {
    display:flex;
    justify-content:space-between;
    align-items:center;
    flex-wrap:wrap;
    gap:10px;
}

/* 🔥 TIMELINE */
.timeline {
    position: relative;
    display: flex;
    justify-content: space-between;
    margin-top: 30px;
    overflow-x: auto;
    padding-bottom: 20px;
    gap: 20px;
}

/* SCROLLBAR */
.timeline::-webkit-scrollbar {
    height: 6px;
}
.timeline::-webkit-scrollbar-thumb {
    background: #ccc;
    border-radius: 10px;
}

/* LINE */
.timeline::before {
    content: '';
    position: absolute;
    top: 20px;
    left: 0;
    width: 100%;
    height: 4px;
    background: #ddd;
}

/* PROGRESS */
.timeline::after {
    content: '';
    position: absolute;
    top: 20px;
    left: 0;
    width: var(--progress);
    height: 4px;
    background: linear-gradient(90deg,#ff6b3d,#28a745);
    transition: width 0.5s ease;
}

/* STEP */
.step {
    text-align: center;
    min-width: 100px;
    flex-shrink: 0;
}

/* CIRCLE */
.circle {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    background: #ddd;
    color: #fff;
    line-height: 38px;
    margin: auto;
    font-weight: bold;
}

/* ACTIVE */
.step.active .circle {
    background: #28a745;
}

/* CURRENT */
.step.current .circle {
    background: #ff9800;
}

/* TEXT */
.step p {
    margin-top: 6px;
    font-size: 12px;
}

/* =========================
   MOBILE RESPONSIVE
========================= */
@media(max-width:768px){

    .order-card {
        padding:15px;
    }

    .order-header h5 {
        font-size:14px;
    }

    .badge {
        font-size:11px;
    }

    .timeline {
        justify-content:flex-start;
        padding-left:10px;
    }

    .step {
        min-width:90px;
    }

    .circle {
        width:34px;
        height:34px;
        line-height:34px;
        font-size:12px;
    }

    .step p {
        font-size:11px;
    }

}

</style>

<div class="container">

    <h3 class="mb-4 fw-bold">📦 Order Tracking</h3>

    @foreach($orders as $order)

    @php
        $steps = [
            'pending' => 'Pending',
            'confirmed' => 'Confirmed',
            'preparing' => 'Preparing',
            'out_for_delivery' => 'Out for Delivery',
            'delivered' => 'Delivered'
        ];

        $keys = array_keys($steps);
        $currentStep = array_search($order->status, $keys);
        if($currentStep === false) $currentStep = 0;

        $percentage = ($currentStep / (count($steps)-1)) * 100;
    @endphp

    <div class="order-card">

        <!-- HEADER -->
        <div class="order-header mb-3">
            <h5>🧾 Order #{{ $order->id }}</h5>
            <span class="badge bg-success">
                {{ ucfirst(str_replace('_',' ',$order->status)) }}
            </span>
        </div>

        <!-- INFO -->
        <div class="mb-2 small">
            👤 <b>{{ $order->user->name ?? 'Guest' }}</b><br>
            📅 {{ $order->created_at->format('d M Y, h:i A') }}
        </div>

        <!-- 🔥 TIMELINE -->
        <div class="timeline" style="--progress: {{ $percentage }}%">

            @foreach($steps as $key => $label)

                @php
                    $stepIndex = array_search($key, $keys);
                @endphp

                <div class="step 
                    {{ $stepIndex < $currentStep ? 'active' : '' }}
                    {{ $stepIndex == $currentStep ? 'current' : '' }}
                ">
                    
                    <div class="circle">
                        @if($stepIndex < $currentStep)
                            ✔
                        @elseif($stepIndex == $currentStep)
                            ⏳
                        @endif
                    </div>

                    <p>{{ $label }}</p>

                </div>

            @endforeach

        </div>

    </div>

    @endforeach

</div>

@endsection