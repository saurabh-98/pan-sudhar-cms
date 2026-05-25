<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
body {
    font-family: DejaVu Sans, sans-serif;
    font-size: 12px;
}

.container {
    border: 2px solid #2c3e50;
    padding: 20px;
    width: 100%;
}

.header {
    text-align: center;
    border-bottom: 2px solid #000;
    padding-bottom: 10px;
    margin-bottom: 15px;
}

.header h2 {
    margin: 0;
    font-size: 20px;
}

.school {
    font-size: 14px;
    color: #555;
}

.info p {
    margin: 6px 0;
}

.table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 15px;
}

.table th, .table td {
    border: 1px solid #000;
    padding: 8px;
}

.total {
    text-align: right;
    margin-top: 10px;
    font-size: 14px;
}

.footer {
    margin-top: 30px;
    text-align: center;
    font-size: 10px;
    color: #777;
}
</style>
</head>

<body>

<div class="container">

    <div class="header">
        <h2>🏫 School Management System</h2>
        <div class="school">Admission Fee Receipt</div>
    </div>

    <div class="info">
        <p><strong>Name:</strong> {{ $name }}</p>
        <p><strong>Registration No:</strong> {{ $regNo }}</p>
        <p><strong>Date:</strong> {{ $date }}</p>
    </div>

    <table class="table">
        <tr>
            <th>Particular</th>
            <th>Amount (₹)</th>
        </tr>
        <tr>
            <td>Admission Fee</td>
            <td>₹5000</td>
        </tr>
        <tr>
            <td>Registration Fee</td>
            <td>₹500</td>
        </tr>
    </table>

    <div class="total">
        <strong>Total: ₹5500</strong>
    </div>

    <div class="footer">
        This is a computer-generated receipt. No signature required.
    </div>

</div>

</body>
</html>