@extends('layout.app')

@section('content')

<style>
.booking-card {
    max-width: 650px;
    margin: auto;
    background: #fff;
    border-radius: 18px;
    padding: 30px;
    box-shadow: 0 15px 40px rgba(0,0,0,0.08);
}

.booking-title {
    font-weight: 700;
    text-align: center;
    margin-bottom: 25px;
}

.form-floating input {
    border-radius: 10px;
}

/* SLOT */
.slot-btn {
    border-radius: 10px;
    padding: 10px 14px;
    transition: 0.2s;
    min-width: 90px;
}

.slot-btn small {
    display:block;
    font-size:11px;
}

.slot-btn.active {
    background:#ff5a00 !important;
    color:#fff !important;
    transform:scale(1.05);
}

/* GUEST */
.guest-box {
    display: flex;
    justify-content: space-between;
    background: #f8f8f8;
    border-radius: 12px;
    padding: 10px 15px;
}

.guest-btn {
    width: 35px;
    height: 35px;
    border: none;
    border-radius: 8px;
    background: #ff5a00;
    color: #fff;
}

/* BUTTON */
.reserve-btn {
    background: linear-gradient(135deg, #ff5a00, #ff8c42);
    border: none;
    border-radius: 12px;
    padding: 12px;
    font-weight: 600;
}
</style>

<div class="container mt-5">

<div class="booking-card">

<h3 class="booking-title">🍽️ Book a Table</h3>

<form id="bookingForm">

<!-- NAME -->
<div class="form-floating mb-3">
<input type="text" id="name" class="form-control"
       value="{{ auth()->user()->name ?? '' }}" required>
<label>Your Name</label>
</div>

<!-- EMAIL -->
<div class="form-floating mb-3">
<input type="email" id="email" class="form-control"
       value="{{ auth()->user()->email ?? '' }}" required>
<label>Email Address</label>
</div>

<!-- PHONE -->
<div class="form-floating mb-3">
<input type="text" id="phone" class="form-control"
       value="{{ auth()->user()->phone ?? '' }}" required>
<label>Phone Number</label>
</div>

<!-- DATE -->
<div class="form-floating mb-3">
<input type="date" id="date" class="form-control" required>
<label>Select Date</label>
</div>

<!-- TIME SLOT -->
<div class="mb-3">
<label class="fw-bold mb-2">Select Time Slot</label>
<div id="timeSlots" class="d-flex flex-wrap gap-2"></div>
<input type="hidden" id="time">
</div>

<!-- GUEST -->
<div class="mb-3">
<label class="fw-bold mb-2">Guests</label>

<div class="guest-box">
<button type="button" class="guest-btn" onclick="changeGuest(-1)">−</button>
<span id="guestCount">2</span>
<button type="button" class="guest-btn" onclick="changeGuest(1)">+</button>
</div>
</div>

<button type="submit" class="btn reserve-btn w-100">
Reserve Table
</button>

</form>
</div>
</div>

@endsection


@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

let guests = 2;

/* =========================
   GUEST CONTROL 🔥
========================= */
function changeGuest(change){
    guests += change;

    if(guests < 1) guests = 1;
    if(guests > 20) guests = 20;

    $('#guestCount').text(guests);

    // 🔥 reload slots automatically
    let date = $('#date').val();
    if(date){
        loadSlots(date);
    }
}

/* =========================
   DATE VALIDATION
========================= */
$(document).ready(function(){
    let today = new Date().toISOString().split('T')[0];
    $('#date').attr('min', today);
});

/* =========================
   LOAD SLOTS 🔥🔥🔥
========================= */
function loadSlots(date){

    $('#timeSlots').html('Loading...');

    $.get('/available-slots', {
        date: date,
        guests: guests
    }, function(slots){

        let html = '';

        slots.forEach(slot => {

            let color = slot.remaining > 10 ? 'success'
                       : slot.remaining > 5 ? 'warning'
                       : 'danger';

            if(slot.available){

                html += `
                    <button type="button"
                        class="btn btn-outline-${color} slot-btn"
                        data-time="${slot.time}">
                        
                        ⏰ ${slot.time}
                        <small>${slot.remaining} seats</small>
                    </button>
                `;

            } else {

                html += `
                    <button class="btn btn-danger slot-btn" disabled>
                        ${slot.time}
                        <small>Full</small>
                    </button>
                `;
            }

        });

        $('#timeSlots').html(html);
    });
}

/* =========================
   DATE CHANGE
========================= */
$('#date').on('change', function(){

    let date = $(this).val();

    if(date){
        loadSlots(date);
    }
});

/* =========================
   SELECT SLOT
========================= */
$(document).on('click','.slot-btn',function(){

    if($(this).is('[disabled]')) return;

    $('.slot-btn')
        .removeClass('active btn-success')
        .addClass('btn-outline-success');

    $(this).addClass('active btn-success');

    $('#time').val($(this).data('time'));
});

/* =========================
   SUBMIT FORM
========================= */
$('#bookingForm').on('submit', function(e){

    e.preventDefault();

    let name  = $('#name').val();
    let email = $('#email').val();
    let phone = $('#phone').val();
    let date  = $('#date').val();
    let time  = $('#time').val();

    if(!name || !email || !phone || !date || !time){
        Swal.fire('Please fill all fields');
        return;
    }

    Swal.fire({
        title:'Confirm Booking?',
        html:`${name}<br>${email}<br>${phone}<br>${date}<br>${time}<br>Guests: ${guests}`,
        icon:'question',
        showCancelButton:true,
        confirmButtonText:'Confirm Booking'
    }).then(result => {

        if(result.isConfirmed){

            // 🔥 LOADING STATE
            Swal.fire({
                title: 'Booking...',
                text: 'Please wait',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            $.post("{{ route('post.reservation') }}",{
                _token:"{{ csrf_token() }}",
                name,
                email,
                phone,
                date,
                time,
                guests
            }, function(res){

                if(res.success){

                    Swal.fire({
                        title: 'Booked! 🎉',
                        text: res.message || 'Reservation successful',
                        icon: 'success',
                        confirmButtonText: 'Go to Dashboard'
                    }).then(() => {

                        // 🔥 REDIRECT
                        window.location.href = res.redirect || "/customer/dashboard";

                    });

                } else {

                    Swal.fire({
                        title: 'Error',
                        text: res.message,
                        icon: 'error'
                    });
                }

            }).fail(() => {

                Swal.fire({
                    title: 'Error',
                    text: 'Something went wrong',
                    icon: 'error'
                });

            });

        }

    });

});
</script>

@endsection