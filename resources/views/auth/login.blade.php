@php
$setting = DB::table('settings')->first();
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>{{ $setting->name ?? 'School ERP' }} | Login</title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />

<style>
:root {
    --brand-1: #0d5ea8;
    --brand-2: #1f8ee0;
    --brand-3: #36b3ff;
    --ink: #112033;
    --muted: #6a7a8f;
    --surface: #ffffff;
    --line: #dce6f1;
    --danger: #d93025;
    --radius-xl: 28px;
    --radius-lg: 18px;
}

* { box-sizing: border-box; }
html, body { height: 100%; }
body {
    margin: 0;
    font-family: "Segoe UI", "Noto Sans", sans-serif;
    color: var(--ink);
    background:
        radial-gradient(900px 420px at 8% -8%, rgba(54, 179, 255, 0.28), transparent 55%),
        radial-gradient(760px 360px at 100% 0%, rgba(13, 94, 168, 0.2), transparent 60%),
        linear-gradient(180deg, #eef6ff, #f7fbff 40%, #ffffff);
}

.login-shell {
    min-height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
}

.login-card {
    width: 100%;
    max-width: 1120px;
    background: var(--surface);
    border-radius: var(--radius-xl);
    box-shadow: 0 24px 80px rgba(17, 32, 51, 0.16);
    overflow: hidden;
    display: grid;
    grid-template-columns: 1.02fr 0.98fr;
    border: 1px solid #e7eef7;
}

.brand-pane {
    position: relative;
    padding: 42px 42px 36px;
    color: #ffffff;
    background: linear-gradient(145deg, var(--brand-1), var(--brand-2) 55%, var(--brand-3));
}

.brand-pane::before,
.brand-pane::after {
    content: "";
    position: absolute;
    border-radius: 999px;
    pointer-events: none;
}

.brand-pane::before {
    width: 260px;
    height: 260px;
    background: rgba(255, 255, 255, 0.14);
    top: -90px;
    right: -90px;
}

.brand-pane::after {
    width: 220px;
    height: 220px;
    background: rgba(255, 255, 255, 0.1);
    bottom: -100px;
    left: -90px;
}

.brand-content {
    position: relative;
    z-index: 1;
}

.mobile-top-strip {
    display: none;
}

.brand-logo {
    width: 108px;
    height: 108px;
    border-radius: 24px;
    object-fit: contain;
    background: #ffffff;
    padding: 12px;
    box-shadow: 0 12px 28px rgba(0, 0, 0, 0.24);
    border: 2px solid rgba(255, 255, 255, 0.58);
}

.brand-title {
    margin-top: 20px;
    font-size: 28px;
    line-height: 1.15;
    font-weight: 700;
    letter-spacing: 0.4px;
}

.brand-subtitle {
    margin-top: 8px;
    font-size: 13px;
    letter-spacing: 1.6px;
    text-transform: uppercase;
    opacity: 0.92;
}

.brand-blurb {
    margin-top: 18px;
    font-size: 15px;
    line-height: 1.6;
    opacity: 0.94;
}

.feature-list {
    margin-top: 24px;
    display: grid;
    gap: 10px;
}

.feature-pill {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 10px 14px;
    border-radius: 12px;
    background: rgba(255, 255, 255, 0.17);
    border: 1px solid rgba(255, 255, 255, 0.25);
    font-size: 13px;
    font-weight: 600;
    line-height: 1.4;
}

.feature-pill i { font-size: 12px; }

.form-pane {
    padding: 34px 30px;
    background: #ffffff;
}

.form-wrap {
    width: 100%;
    max-width: 430px;
    margin: 0 auto;
}

.quick-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 8px;
}

.quick-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border-radius: 999px;
    background: #f1f7ff;
    border: 1px solid #d7e8fb;
    color: #365678;
    font-size: 11px;
    font-weight: 700;
    padding: 6px 10px;
    letter-spacing: 0.3px;
}

.welcome-title {
    margin: 0;
    font-size: 29px;
    font-weight: 700;
    line-height: 1.2;
    color: #10253d;
}

.welcome-sub {
    margin-top: 8px;
    color: var(--muted);
    font-size: 14px;
}

.form-label {
    margin-bottom: 7px;
    margin-top: 16px;
    font-size: 12px;
    letter-spacing: 0.7px;
    text-transform: uppercase;
    color: #4c6078;
    font-weight: 700;
}

.input-group .form-control {
    height: 48px;
    border-radius: 13px 0 0 13px;
    border: 1px solid var(--line);
    font-size: 15px;
    padding: 10px 13px;
    box-shadow: none;
}

.input-group .input-group-text {
    border-radius: 0 13px 13px 0;
    border: 1px solid var(--line);
    border-left: none;
    background: #f5f9ff;
    color: #5a6f86;
}

.input-group .form-control:focus {
    border-color: #69b6ff;
    box-shadow: none;
}

.toggle-password { cursor: pointer; }

.field-error {
    min-height: 18px;
    color: var(--danger);
    font-size: 12px;
    margin-top: 5px;
}

.btn-main {
    width: 100%;
    margin-top: 12px;
    border: none;
    border-radius: 13px;
    height: 50px;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: 1.2px;
    text-transform: uppercase;
    color: #fff;
    background: linear-gradient(135deg, var(--brand-1), var(--brand-2) 55%, var(--brand-3));
    box-shadow: 0 12px 24px rgba(13, 94, 168, 0.28);
    transition: transform 0.16s ease, box-shadow 0.16s ease;
}

.btn-main:hover {
    color: #fff;
    transform: translateY(-1px);
    box-shadow: 0 14px 28px rgba(13, 94, 168, 0.33);
}

.btn-alt {
    width: 100%;
    margin-top: 10px;
    border: 1px solid #bad7ef;
    border-radius: 12px;
    height: 44px;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.8px;
    text-transform: uppercase;
    color: #21405f;
    background: #f7fbff;
}

.btn-alt:hover {
    color: #173552;
    background: #eef7ff;
}

.action-grid {
    margin-top: 4px;
}

.action-grid .col-6 {
    padding-left: 6px;
    padding-right: 6px;
}

.footer-note {
    margin-top: 16px;
    text-align: center;
    color: #8092a8;
    font-size: 12px;
}

.modal-content {
    border-radius: 16px;
    border: none;
}

.modal-title {
    font-size: 20px;
    font-weight: 700;
}

.mpin-boxes {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 10px;
}

.mpin-boxes input {
    height: 54px;
    border-radius: 12px;
    border: 1px solid #c9dbef;
    text-align: center;
    font-size: 24px;
    font-weight: 700;
}

.mpin-boxes input:focus {
    border-color: #5aa7f2;
    box-shadow: 0 0 0 0.16rem rgba(48, 140, 230, 0.15);
}

@media (max-width: 991.98px) {
    .login-shell { padding: 10px; align-items: stretch; }
    .login-card {
        border-radius: 24px;
        grid-template-columns: 1fr;
        min-height: calc(100vh - 20px);
        background: transparent;
        box-shadow: none;
        border: none;
    }
    .brand-pane {
        border-radius: 22px 22px 0 0;
        padding: 18px 16px 44px;
        min-height: 210px;
    }
    .brand-logo { width: 66px; height: 66px; border-radius: 16px; padding: 8px; }
    .brand-title { margin-top: 10px; font-size: 20px; }
    .brand-subtitle { font-size: 10px; letter-spacing: 1px; margin-top: 5px; }
    .brand-blurb { margin-top: 8px; font-size: 12px; opacity: 0.9; max-width: 90%; }
    .feature-list { display: none; }
    .form-pane {
        position: relative;
        margin-top: -26px;
        border-radius: 22px 22px 0 0;
        background: #fff;
        padding: 18px 14px 14px;
        box-shadow: 0 -8px 28px rgba(17, 32, 51, 0.12);
    }
    .mobile-top-strip {
        display: block;
        width: 48px;
        height: 5px;
        border-radius: 12px;
        background: #d6e6f7;
        margin: 0 auto 12px;
    }
    .form-wrap { max-width: 100%; }
    .quick-meta { margin-top: 4px; margin-bottom: 10px; }
    .welcome-title { font-size: 24px; line-height: 1.15; }
    .welcome-sub { font-size: 13px; margin-top: 4px; margin-bottom: 8px; }
    .form-label { margin-top: 10px; margin-bottom: 6px; }
    .input-group .form-control { height: 50px; font-size: 16px; }
    .input-group .input-group-text { min-width: 48px; justify-content: center; }
    .btn-main { height: 52px; border-radius: 14px; font-size: 13px; }
    .btn-alt { height: 46px; border-radius: 12px; font-size: 11px; margin-top: 8px; }
    .footer-note { margin-top: 12px; padding-bottom: calc(env(safe-area-inset-bottom) + 2px); }
}

@media (max-width: 575.98px) {
    .brand-pane { padding-bottom: 42px; }
    .brand-title { font-size: 18px; max-width: 92%; }
    .brand-blurb { font-size: 11.5px; margin-bottom: 0; }
    .action-grid .col-6 { padding-left: 4px; padding-right: 4px; }
    .btn-alt { font-size: 10px; letter-spacing: 0.4px; }
    .welcome-title { font-size: 22px; }
    .quick-pill { font-size: 10px; padding: 5px 8px; }
}
</style>
</head>
<body>
<div class="login-shell">
    <div class="login-card">
        <section class="brand-pane">
            <div class="brand-content">
                <img
                    class="brand-logo"
                    src="{{ env('IMAGE_SHOW_PATH').'setting/left_logo/'.$setting->left_logo ?? '' }}"
                    onerror="this.src='{{ env('IMAGE_SHOW_PATH').'default/no_image.png' }}'"
                    alt="School Logo"
                >

                <h1 class="brand-title">{{ $setting->name ?? 'School ERP' }}</h1>
                <div class="brand-subtitle">School Management Platform</div>

                <p class="brand-blurb">
                    One place for attendance, communication, academics and administration.
                </p>

                <div class="feature-list">
                    <div class="feature-pill"><i class="fa fa-check"></i> Fast and easy daily operations</div>
                    <div class="feature-pill"><i class="fa fa-check"></i> Mobile ready and cloud accessible</div>
                    <div class="feature-pill"><i class="fa fa-check"></i> Secure login with mPIN option</div>
                </div>
            </div>
        </section>

        <section class="form-pane">
            <div class="form-wrap">
                <div class="mobile-top-strip"></div>
                <h2 class="welcome-title">Welcome Back</h2>
                <p class="welcome-sub">Sign in to continue to your dashboard.</p>
                <div class="quick-meta">
                    <span class="quick-pill"><i class="fa fa-shield-alt"></i> Secure</span>
                    <span class="quick-pill"><i class="fa fa-bolt"></i> Quick Access</span>
                </div>

                <form id="loginForm" action="{{ url('login') }}" method="POST">
                    @csrf

                    <label class="form-label">Username</label>
                    <div class="input-group">
                        <input type="text" name="user_name" class="form-control" placeholder="Enter username" autocomplete="username">
                        <div class="input-group-append"><span class="input-group-text"><i class="fa fa-user"></i></span></div>
                    </div>
                    <div class="field-error" id="user_name_error"></div>

                    <label class="form-label">Password</label>
                    <div class="input-group">
                        <input type="password" id="password" name="password" class="form-control" placeholder="Enter password" autocomplete="current-password">
                        <div class="input-group-append toggle-password"><span class="input-group-text"><i class="fa fa-eye"></i></span></div>
                    </div>
                    <div class="field-error" id="password_error"></div>

                    <button type="submit" id="submitBtn" class="btn-main">
                        <i class="fa fa-sign-in-alt"></i> Sign In
                    </button>
                </form>

                <div class="row action-grid">
                    <div class="col-6">
                        <button class="btn-alt" data-toggle="modal" data-target="#mpinModal">
                            <i class="fa fa-lock"></i> Login mPIN
                        </button>
                    </div>
                    <div class="col-6">
                        <button class="btn-alt" data-toggle="modal" data-target="#GenerateMpinModal">
                            <i class="fa fa-key"></i> Generate mPIN
                        </button>
                    </div>
                </div>

                <div id="showNotice"></div>

                <p class="footer-note">
                    Copyright {{ date('Y') }} {{ $setting->name ?? 'School ERP' }}
                </p>
            </div>
        </section>
    </div>
</div>

<div class="modal fade" id="mpinModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-4">
            <h4 class="modal-title text-center mb-2">Login with mPIN</h4>
            <p class="text-muted text-center mb-3" style="font-size:13px;">Enter your 4-digit secure mPIN</p>

            <form id="mpinForm" method="POST" action="{{ url('mpin-login') }}">
                @csrf
                <div class="mpin-boxes mb-3">
                    <input type="tel" maxlength="1" class="mpin-input form-control">
                    <input type="tel" maxlength="1" class="mpin-input form-control">
                    <input type="tel" maxlength="1" class="mpin-input form-control">
                    <input type="tel" maxlength="1" class="mpin-input form-control">
                </div>

                <input type="hidden" name="mpin" id="final_mpin">

                <button type="submit" class="btn btn-primary btn-block" style="border-radius:12px;height:44px;">
                    <i class="fa fa-lock"></i> Login
                </button>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="GenerateMpinModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content p-3 p-md-4">
            <h4 class="modal-title text-center mb-1">Generate mPIN</h4>
            <p class="text-muted text-center mb-3" style="font-size:13px;">Create your 4-digit quick login mPIN</p>

            <form id="GenerateMpinForm">
                @csrf
                <div class="form-group mb-2">
                    <label class="mb-1">Username</label>
                    <input type="text" name="user_name" id="mpin_user" class="form-control" placeholder="Enter username">
                </div>

                <div class="form-group mb-2">
                    <label class="mb-1">Password</label>
                    <input type="password" name="password" id="mpin_pass" class="form-control" placeholder="Enter password">
                </div>

                <div class="form-group mb-3">
                    <label class="mb-1">Create 4-digit mPIN</label>
                    <input
                        type="number"
                        name="mpin"
                        id="mpin_code"
                        class="form-control text-center font-weight-bold"
                        placeholder="0000"
                        oninput="if(this.value.length>4)this.value=this.value.slice(0,4)"
                    >
                </div>

                <button type="submit" id="GenerateMpinBtn" class="btn btn-dark btn-block" style="border-radius:12px;height:44px;">
                    Save mPIN
                </button>
            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
$.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
});

const APP_BASE_URL = "{{ url('/') }}";
function withBaseUrl(redirectUrl) {
    const target = (redirectUrl || '/').toString().trim();
    if (target === '') return APP_BASE_URL;
    if (/^https?:\/\//i.test(target)) return target;
    if (target.startsWith('/')) return APP_BASE_URL + target;
    return APP_BASE_URL + '/' + target;
}

$('#GenerateMpinModal').on('shown.bs.modal', function () {
    $('#mpin_user').trigger('focus');
});

$('#GenerateMpinForm').on('submit', function(e){
    e.preventDefault();
    $('#GenerateMpinBtn').text('Saving...').prop('disabled', true);

    $.ajax({
        url: "{{ url('save-mpin') }}",
        type: "POST",
        data: $(this).serialize(),
        success: function(res){
            $('#GenerateMpinBtn').text('Save mPIN').prop('disabled', false);
            if(res.status === 'success'){
                toastr.success(res.message);
                $('#GenerateMpinModal').modal('hide');
                $('#GenerateMpinForm')[0].reset();
            } else {
                toastr.error(res.message || 'Unable to save mPIN');
            }
        },
        error: function(){
            $('#GenerateMpinBtn').text('Save mPIN').prop('disabled', false);
            toastr.error('Server Error');
        }
    });
});

$('.toggle-password').on('click', function() {
    const input = $('#password');
    const icon = $(this).find('i');
    if (input.attr('type') === 'password') {
        input.attr('type', 'text');
        icon.removeClass('fa-eye').addClass('fa-eye-slash');
    } else {
        input.attr('type', 'password');
        icon.removeClass('fa-eye-slash').addClass('fa-eye');
    }
});

$('#loginForm').on('submit', function(e) {
    e.preventDefault();

    const form = $(this);
    const submitButton = $('#submitBtn');

    submitButton.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Signing In...');
    $('#user_name_error').text('');
    $('#password_error').text('');

    $.ajax({
        url: form.attr('action'),
        type: 'POST',
        data: form.serialize(),
        success: function(res) {
            submitButton.prop('disabled', false).html('<i class="fa fa-sign-in-alt"></i> Sign In');

            if (res.status === 'success') {
                setTimeout(() => { window.location.href = withBaseUrl(res.redirect_url); }, 600);
            } else {
                $('#user_name_error').text(res.user_name_error || '');
                $('#password_error').text(res.password_error || '');
                if (res.message) toastr.error(res.message);
            }
        },
        error: function(xhr) {
            submitButton.prop('disabled', false).html('<i class="fa fa-sign-in-alt"></i> Sign In');
            const errors = xhr.responseJSON && xhr.responseJSON.errors ? xhr.responseJSON.errors : {};
            $('#user_name_error').text(errors.user_name ? errors.user_name[0] : '');
            $('#password_error').text(errors.password ? errors.password[0] : '');
        }
    });
});

$('.mpin-input').on('input', function () {
    this.value = this.value.replace(/[^0-9]/g, '');
    if (this.value.length === 1) {
        $(this).next('.mpin-input').focus();
    }

    let mpin = '';
    $('.mpin-input').each(function () {
        mpin += $(this).val();
    });
    $('#final_mpin').val(mpin);
});

$('.mpin-input').on('keydown', function (e) {
    if (e.key === 'Backspace' && this.value === '') {
        $(this).prev('.mpin-input').focus();
    }
});

$('#mpinForm').on('submit', function (e) {
    e.preventDefault();

    const mpin = $('#final_mpin').val();
    if ((mpin || '').length < 4) {
        toastr.error('Please enter 4-digit mPIN');
        return;
    }

    $.ajax({
        url: "{{ url('mpin-login') }}",
        type: 'POST',
        data: {
            _token: "{{ csrf_token() }}",
            mpin: mpin
        },
        success: function (response) {
            if (response.status === 'success') {
                toastr.success(response.message);
                setTimeout(function () {
                    window.location.href = withBaseUrl(response.redirect_url);
                }, 600);
            } else {
                toastr.error(response.message || 'Login failed');
            }
        },
        error: function () {
            toastr.error('Server error, please try again');
        }
    });
});
</script>

<script>
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll("img").forEach(function (img) {
        img.onerror = function () {
            this.onerror = null;
            this.src = "{{ asset('storage/images/default.png') }}";
        };
    });
});
</script>
</body>
</html>
