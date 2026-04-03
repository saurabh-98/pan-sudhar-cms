@extends('layout.customer')

@section('content')

<style>
/* ===== HEADER ===== */
.page-title {
    font-weight:700;
    margin-bottom:20px;
}

/* ===== CARD ===== */
.res-card {
    background:#fff;
    border-radius:18px;
    padding:20px;
    margin-bottom:15px;
    box-shadow:0 10px 30px rgba(0,0,0,0.05);
    transition:0.3s;
    display:flex;
    justify-content:space-between;
    align-items:center;
}

.res-card:hover {
    transform:translateY(-5px);
    box-shadow:0 20px 40px rgba(0,0,0,0.08);
}

/* LEFT */
.res-left {
    display:flex;
    flex-direction:column;
    gap:5px;
}

/* RIGHT */
.res-right {
    text-align:right;
}

/* BADGE */
.badge-status {
    padding:5px 12px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
}

.pending { background:#fff3cd; color:#856404; }
.confirmed { background:#cce5ff; color:#004085; }
.cancelled { background:#f8d7da; color:#721c24; }

/* BUTTON */
.btn-cancel {
    border-radius:8px;
    padding:6px 12px;
}

/* EMPTY */
.empty {
    text-align:center;
    padding:50px;
    color:#999;
}
</style>

<div class="container mt-4">

<h3 class="page-title">📅 My Reservations</h3>

<div id="reservationList"></div>

</div>

@endsection


@section('scripts')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>

function loadReservations(){

    $.get("{{ route('customer.reservations.list') }}", function(res){

        let html = '';

        if(!res.data.length){
            html = `<div class="empty">No reservations yet 😔</div>`;
        }

        res.data.forEach(r => {

            let statusClass = r.status;

            html += `
            <div class="res-card">

                <div class="res-left">

                    <strong>🍽️ ${r.table ?? 'Table not assigned'}</strong>

                    <span>📅 ${formatDate(r.date)}</span>
                    <span>⏰ ${formatTime(r.time)}</span>
                    <span>👥 ${r.guests} Guests</span>

                </div>

                <div class="res-right">

                    <span class="badge-status ${statusClass}">
                        ${r.status}
                    </span>

                    <br><br>

                    ${
                        r.status !== 'cancelled'
                        ? `<button class="btn btn-danger btn-cancel cancelBtn" data-id="${r.id}">
                            ❌ Cancel
                           </button>`
                        : `<span class="text-muted">Cancelled</span>`
                    }

                </div>

            </div>
            `;
        });

        $('#reservationList').html(html);

    });
}


/* FORMAT DATE */
function formatDate(d){
    let date = new Date(d);
    return date.toLocaleDateString('en-IN',{
        day:'2-digit',
        month:'short',
        year:'numeric'
    });
}

/* FORMAT TIME */
function formatTime(t){

    let clean = t.split('T').pop();
    let time = new Date('1970-01-01T' + clean);

    return time.toLocaleTimeString('en-IN',{
        hour:'2-digit',
        minute:'2-digit',
        hour12:true
    });
}


/* CANCEL */
$(document).on('click','.cancelBtn',function(){

    let id = $(this).data('id');

    Swal.fire({
        title:'Cancel Reservation?',
        text:'Are you sure?',
        icon:'warning',
        showCancelButton:true
    }).then(res=>{

        if(res.isConfirmed){

            $.post("{{ url('/customer/reservation/cancel') }}/" + id,{
                _token: "{{ csrf_token() }}"
            },function(response){

                if(response.status === 'success'){
                    Swal.fire('Cancelled!', response.message, 'success');
                    loadReservations();
                } else {
                    Swal.fire('Error', response.message || 'Something went wrong', 'error');
                }

            }).fail(function(){
                Swal.fire('Error','Request failed','error');
            });

        }

    });

});

/* INIT */
loadReservations();

</script>

@endsection