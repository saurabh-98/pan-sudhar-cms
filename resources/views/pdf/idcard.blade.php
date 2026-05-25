<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
body {
    font-family: DejaVu Sans, sans-serif;
}

/* WRAPPER */
.wrapper {
    width: 100%;
    text-align: center;
}

/* CARD */
.card {
    width: 320px;
    height: 200px;
    border: 2px solid #2c3e50;
    border-radius: 12px;
    overflow: hidden;
    display: inline-block;
    margin: 10px;
    vertical-align: top;
    position: relative;
}

/* HEADER */
.header {
    background: linear-gradient(90deg, #2c3e50, #4ca1af);
    color: #fff;
    text-align: center;
    padding: 6px;
    font-size: 12px;
    font-weight: bold;
}

/* LOGO */
.logo {
    text-align: center;
    margin-top: 5px;
}

.logo img {
    height: 28px;
}

/* BODY */
.body {
    padding: 6px;
    text-align: center;
}

/* PHOTO */
.photo img {
    width: 70px;
    height: 80px;
    border-radius: 5px;
    border: 2px solid #ccc;
}

/* TEXT */
.name {
    font-size: 13px;
    font-weight: bold;
    margin-top: 4px;
}

.reg {
    font-size: 11px;
    color: #555;
}

.class {
    font-size: 11px;
    margin-top: 2px;
}

/* QR */
.qr {
    position: absolute;
    bottom: 10px;
    right: 10px;
}

.qr img {
    width: 60px;
}

/* BACK BODY */
.back-body {
    padding: 8px;
    font-size: 11px;
    text-align: left;
}

.back-body p {
    margin: 3px 0;
}

/* SIGNATURE */
.signature {
    text-align: center;
    margin-top: 8px;
}

.signature img {
    height: 25px;
}

/* FOOTER */
.footer {
    position: absolute;
    bottom: 5px;
    left: 0;
    right: 0;
    text-align: center;
    font-size: 9px;
    color: #777;
}
</style>
</head>

<body>

<div class="wrapper">

<!-- ================= FRONT ================= -->
<div class="card">

    <div class="header">
        School Management System
    </div>

    <div class="logo">
        @if(file_exists(public_path('logo.png')))
            <img src="{{ public_path('logo.png') }}">
        @endif
    </div>

    <div class="body">

        <div class="photo">
            @if(!empty($photo) && file_exists(public_path('uploads/'.$photo)))
                <img src="{{ public_path('uploads/'.$photo) }}">
            @endif
        </div>

        <div class="name">{{ $name }}</div>
        <div class="reg">Reg No: {{ $regNo }}</div>
        <div class="class">{{ $class }} - {{ $section }}</div>

    </div>

    <!-- QR -->
    <div class="qr">
        @if(!empty($qr))
            <img src="{{ $qr }}">
        @endif
    </div>

    <div class="footer">
        Scan QR to verify
    </div>

</div>

<!-- ================= BACK ================= -->
<div class="card">

    <div class="header">
        Student Information
    </div>

    <div class="back-body">

        <p><b>Name:</b> {{ $name }}</p>
        <p><b>Reg No:</b> {{ $regNo }}</p>
        <p><b>Class:</b> {{ $class }}</p>
        <p><b>Section:</b> {{ $section }}</p>
        <p><b>Valid Till:</b> {{ date('Y') }}</p>

        <div style="text-align:center; margin-top:5px;">
            @if(!empty($qr))
                <img src="{{ $qr }}" width="70">
            @endif
        </div>

        <div class="signature">
            @if(file_exists(public_path('signature.png')))
                <img src="{{ public_path('signature.png') }}"><br>
            @endif
            <span>Principal Signature</span>
        </div>

    </div>

    <div class="footer">
        If found, please return to school
    </div>

</div>

</div>

</body>
</html>