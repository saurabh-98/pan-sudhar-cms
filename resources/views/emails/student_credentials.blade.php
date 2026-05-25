<!DOCTYPE html>

<html>
<head>
<meta charset="UTF-8">
<title>Admission Approved</title>
</head>

<body style="margin:0; padding:0; font-family: Arial, sans-serif; background:#f4f6f9;">

<table width="100%" cellpadding="0" cellspacing="0" style="background:#f4f6f9; padding:20px;">
<tr>
<td align="center">

<!-- MAIN CARD -->

<table width="600" cellpadding="0" cellspacing="0" style="background:#ffffff; border-radius:10px; overflow:hidden; box-shadow:0 5px 15px rgba(0,0,0,0.08);">

```
<!-- HEADER -->
<tr>
    <td style="background:linear-gradient(135deg,#4CAF50,#2E7D32); color:#fff; text-align:center; padding:20px;">
        <h2 style="margin:0;">🎓 Admission Approved</h2>
    </td>
</tr>

<!-- BODY -->
<tr>
    <td style="padding:25px; color:#333;">

        <p style="font-size:16px;">Dear <strong>{{ $name }}</strong>,</p>

        <p style="font-size:15px;">
            We are pleased to inform you that your admission has been 
            <strong style="color:#2E7D32;">successfully approved</strong>.
        </p>

        <!-- INFO BOX -->
        <table width="100%" cellpadding="10" cellspacing="0" style="background:#f9fafc; border-radius:8px; margin:20px 0;">
            <tr>
                <td><strong>Registration No:</strong></td>
                <td>{{ $regNo }}</td>
            </tr>
            <tr>
                <td><strong>Email:</strong></td>
                <td>{{ $email }}</td>
            </tr>
            <tr>
                <td><strong>Password:</strong></td>
                <td>{{ $password }}</td>
            </tr>
        </table>

        <p style="font-size:14px; color:#666;">
            🔐 For security reasons, please login and change your password after your first login.
        </p>

        <!-- BUTTON -->
        <div style="text-align:center; margin:25px 0;">
            <a href="{{ url('/login') }}" 
               style="background:#4CAF50; color:#fff; padding:12px 25px; 
                      text-decoration:none; border-radius:6px; font-weight:600;">
                Login Now
            </a>
        </div>

        <p style="font-size:14px;">Thank you,<br><strong>School Management</strong></p>

    </td>
</tr>

<!-- FOOTER -->
<tr>
    <td style="background:#f1f1f1; text-align:center; padding:12px; font-size:12px; color:#777;">
        © {{ date('Y') }} School Management System. All rights reserved.
    </td>
</tr>
```

</table>

</td>
</tr>
</table>

</body>
</html>
