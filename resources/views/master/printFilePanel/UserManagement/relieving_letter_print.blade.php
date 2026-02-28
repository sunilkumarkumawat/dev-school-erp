@php
$getSetting = Helper::getSetting();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Relieving Letter</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    font-family: Arial, Helvetica, sans-serif;
    background: #f2f2f2;
    padding: 20px;
}

.letter-container {
    max-width: 850px;
    margin: auto;
    background: #fff;
    border: 2px solid #000;
    padding: 50px 60px 120px 60px;
    position: relative;
}

/* Header */
.letter-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
}

.logo-section {
    width: 30%;
}

.logo-section img {
    height: 65px;
}



.company-info {
    width: 30%;
    text-align: left;
    font-size: 14px;
    line-height: 1.7;
}

.company-info strong {
    font-size: 16px;
}

/* Body */
.letter-body {
    margin-top: 20px;
    font-size: 15px;
    line-height: 1.4;
    color: #222;
}

.subject {
    text-align: center;
    font-weight: 600;
    margin: 15px 0;
    font-size: 16px;
}

.signature-section {
    margin-top: 60px;
}

/* Seal */
.seal-section {
    position: absolute;
    right: 70px;
    bottom: 110px;
}

.seal-section img {
    height: 100px;
}

/* Footer Strip */
.letter-footer {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
}

.footer-top {
    background: #0b5fa5;
    color: #fff;
    text-align: center;
    padding: 8px;
    font-size: 13px;
    font-weight: 600;
}

.footer-bottom {
    background: #1fa2d6;
    color: #fff;
    text-align: center;
    padding: 6px;
    font-size: 12px;
}

@media print {
    body {
        background: none;
        padding: 0;
    }
}
</style>
</head>

<body>

<div class="letter-container">

    <!-- Header -->
    <div class="letter-header">

        <div class="logo-section">
            <img src="{{ env('IMAGE_SHOW_PATH').'/setting/left_logo/'.$getSetting['left_logo'] }}"
                 onerror="this.src='{{ env('IMAGE_SHOW_PATH').'/default/rukmani_logo.png' }}'">
        </div>


        <div class="company-info">
            <strong>{{$getSetting['name'] ?? ''}}</strong><br>
            <b>Address:</b> {{ $getSetting['address'] ?? '' }}<br>
            <b>Phone:</b> {{ $getSetting['mobile'] ?? '' }}<br>
            <b>Email:</b> {{ $getSetting['gmail'] ?? '' }}
        </div>

    </div>

    <!-- Body -->
    <div class="letter-body">

        <p>
            <strong>Date:</strong> {{ date('d F Y') }} <br>
            <strong>{{ $data->first_name }} {{ $data->last_name }}</strong><br>
            {{ $data->role_name }}<br>
            Employee ID: {{ $data->id }}
        </p>

           

        <div class="subject">
            Subject: Relieving Letter
        </div>

        <b>Dear {{ $data->first_name }} {{ $data->last_name }},</b><br><br>

        <b>
            We are writing to confirm that {{ $data->first_name }} {{ $data->last_name }},
            employed with <mark>{{$getSetting['name'] ?? ''}}</mark> since 
            {{ $data->created_at->format('d F Y') }},
            has been relieved from their duties effective 
            {{ date('d F Y') }}.
        </b><br><br>

        <b>
            During their tenure with us, {{ $data->first_name }} performed duties diligently 
            and responsibly. We appreciate their contributions and wish them success in future endeavors.
        </b><br><br>

        <b>
            Please feel free to contact us at {{ $getSetting['mobile'] ?? '' }} 
            or {{ $getSetting['gmail'] ?? '' }} for any further information.
        </b><br><br>

        <b>Thank you for your cooperation.</b>

        <div class="signature-section">
            <p>Sincerely,</p>
            <br><br>
            <p><strong>Authorized Signatory</strong></p>
            <p>{{$getSetting['name'] ?? ''}}</p>
        </div>

    </div>

    <!-- Seal -->
    <div class="seal-section">
        <img src="{{ env('IMAGE_SHOW_PATH').'/setting/seal_sign/'.$getSetting['seal_sign'] }}"
             onerror="this.src='{{ env('IMAGE_SHOW_PATH').'/default/seal.png' }}'">
    </div>

    <!-- Footer -->
    <div class="letter-footer">
        <div class="footer-top">
            Copyright © 2026 Rukmani Developer | All rights reserved <span style="color:#ff4d4d;">&#10084;</span>
        </div>
        <div class="footer-bottom">
            Registered Address: {{$getSetting['address'] ?? ''}}
        </div>
    </div>

</div>

<script>
window.print();
</script>

</body>
</html>
