<h2>💳 Payment Verified</h2>

<p>Dear Parent,</p>

<p>Your child's admission payment has been successfully verified.</p>

<hr>

<p><strong>Name:</strong> {{ $admission->name }}</p>
<p><strong>Class:</strong> {{ $admission->studentClass?->name }}</p>

@if($admission->utr_no)
<p><strong>UTR No:</strong> {{ $admission->utr_no }}</p>
@endif

<p><strong>Payment ID:</strong> {{ $admission->payment_id }}</p>
<p><strong>Date:</strong> {{ $admission->paid_at }}</p>

<hr>

<p>Thank you.</p>