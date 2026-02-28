@php
$getSetting = Helper::getSetting();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Joining Letter</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<style>
body {
    font-family: Arial, Helvetica, sans-serif;
    background: #f2f2f2;
    padding: 20px;
    font-size: 15px;
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
    width: 40%;
    text-align: left;
    font-size: 14px;
    line-height: 1.6;
}

.company-info strong {
    font-size: 16px;
}

/* Title */
.letter-title {
    text-align: center;
    font-size: 26px;
    font-weight: 700;
    /*margin: 15px 0;*/
    /*border-top: 6px solid #d1052a;*/
    padding-top: 15px;
}

/* Body */
.letter-body {
    margin-top: -15px;
    font-size: 15px;
    line-height: 1.6;
    color: #222;
}

.detail-table {
    width: 100%;
    margin-bottom: 20px;
}

.detail-table td {
    padding: 6px 4px;
}

.detail-label {
    width: 120px;
    font-weight: 600;
}

.detail-value {
    border-bottom: 1px dotted #000;
}

/* Signature */
.signature-section {
    margin-top: 50px;
}

/* Seal */
.seal-section {
    position: absolute;
    right: 70px;
    bottom: 110px;
}

.seal-section img {
    height: 90px;
}

/* Footer */
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
            <b>Address:</b> {{$getSetting['address'] ?? ''}}<br>
            <b>Phone:</b> {{$getSetting['mobile'] ?? ''}}<br>
            <b>Email:</b> {{$getSetting['gmail'] ?? ''}}
        </div>
    </div>

    <!-- Title -->
    <div class="letter-title">
        JOINING LETTER
    </div>

    <!-- Body -->
    <div class="letter-body">

        <table class="detail-table">
            <tr>
                <td class="detail-label">To,</td>
                <td></td>
            </tr>
            <tr>
                <td class="detail-label">Name:</td>
                <td class="detail-value">{{$data['first_name'] ?? ''}} {{$data['last_name'] ?? ''}}</td>
            </tr>
            <tr>
                <td class="detail-label">Mobile:</td>
                <td class="detail-value">{{$data['mobile'] ?? ''}}</td>
            </tr>
            <tr>
                <td class="detail-label">Email:</td>
                <td class="detail-value">{{$data['email'] ?? ''}}</td>
            </tr>
            <tr>
                <td class="detail-label">DOB:</td>
                <td class="detail-value">{{date('d-m-Y', strtotime($data['dob'])) ?? ''}}</td>
            </tr>
            <tr>
                <td class="detail-label">Address:</td>
                <td class="detail-value">{{$data['address'] ?? ''}}</td>
            </tr>
        </table>

        <p><strong>Dear Sir/Ma'am,</strong></p>

        <p><strong>
            I am immensely pleased to inform you that I accept the offer for the position 
            and confirm my readiness to join on </strong>
            <strong><mark>{{date('d-m-Y', strtotime($data['joining_date'])) ?? ''}}</mark></strong>.
        </p>

        <p><strong>
            I sincerely thank you for believing in me and offering me this opportunity. 
            I assure you that I will work with sincerity and dedication.
            </strong>
        </p>

        <p>
            <strong>
            I will submit all the required documents on my joining date. 
            Should you require any further information, please feel free to contact me.
            </strong>
        </p>

        <p><strong>Yours faithfully,</strong></p>

        <div class="signature-section">
            <br><br>
            <strong>Signature</strong>
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
            Copyright © 2026 Rukmani Developer | All rights reserved 
            <span style="color:#ff4d4d;">&#10084;</span>
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
