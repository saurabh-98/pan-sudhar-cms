@extends('layout.staff')

@section('content')

<div class="container">

    <h3 class="mb-4">📦 Order Tracking</h3>

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
        $percentage = ($currentStep / (count($steps)-1)) * 100;
    @endphp

    <div class="card mb-4 p-4">

        <h5>Order #{{ $order->id }} - {{ $order->user->name }}</h5>

        <div class="timeline" style="--progress: {{ $percentage }}%">

            @foreach($steps as $key => $label)
                @php
                    $stepIndex = array_search($key, $keys);
                @endphp

                <div class="step {{ $stepIndex <= $currentStep ? 'active' : '' }}">
                    
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