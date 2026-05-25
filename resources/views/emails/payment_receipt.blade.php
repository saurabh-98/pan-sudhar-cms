<h2>Payment Submitted</h2>

<p>Dear {{ $admission->name }},</p>

<p>Your payment has been successfully submitted and is under verification.</p>

<hr>

<p><strong>Payment ID:</strong> {{ $admission->payment_id }}</p>
<p><strong>Total:</strong> ₹ {{ $admission->total_fee }}</p>
<p><strong>Paid:</strong> ₹ {{ $admission->paid_amount }}</p>
<p><strong>Due:</strong> ₹ {{ $admission->due_amount }}</p>

<hr>

<p>The receipt is attached with this email.</p>

<p>Thank you,<br>School Management</p>