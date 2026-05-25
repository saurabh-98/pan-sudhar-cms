<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
</head>

<body style="font-family: Arial; background:#f4f6f9; padding:20px;">

<div style="max-width:600px; margin:auto; background:#fff; padding:20px; border-radius:10px;">

    <h2 style="color:#2E7D32;">👨‍👩‍👦 Parent Login Credentials</h2>

    <p>Dear Parent,</p>

    <p>Your child <strong>{{ $name }}</strong> has been successfully admitted.</p>

    <hr>

    <p><strong>Email:</strong> {{ $email }}</p>
    <p><strong>Password:</strong> {{ $password }}</p>

    <hr>

    <p>Please login and monitor your child's progress.</p>

    <a href="{{ url('/login') }}"
       style="display:inline-block; padding:10px 20px; background:#2E7D32; color:#fff; text-decoration:none; border-radius:5px;">
       Login Now
    </a>

    <p style="margin-top:20px;">Thank you,<br>School Management</p>

</div>

</body>
</html>