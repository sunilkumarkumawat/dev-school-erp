<?php
$setting = DB::table('settings')->get()->first();
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo e($setting->name ?? 'School ERP'); ?> | Login</title>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" />


<style>
body {
    background:#e8f1ff;
    height:100vh;
    display:flex;
    align-items:center;
    justify-content:center;
    font-family:"Poppins",sans-serif;
}
.animated-bg{position:fixed;top:0;left:0;width:100%;height:100%;z-index:0;overflow:hidden;}
.wave{position:absolute;width:200%;height:200%;background:rgba(0,90,255,0.06);border-radius:40%;animation:wave 12s infinite linear;}
.wave:nth-child(2){background:rgba(0,162,255,0.05);animation-duration:15s;}
.wave:nth-child(3){background:rgba(0,200,255,0.04);animation-duration:18s;}
@keyframes  wave{0%{transform:rotate(0deg);}100%{transform:rotate(360deg);}}

.main-container{
    width:90%;
    max-width:1100px;
    background:#fff;
    border-radius:24px;
    display:flex;
    box-shadow:0 0 30px rgba(0,0,0,0.18);
    overflow:hidden;
    position:relative;
    z-index:5;
}
.left-section{
    width:50%;
    padding:40px;
    background:linear-gradient(135deg,#0052D4,#4364F7,#6FB1FC);
    color:#fff;
    text-align:center;
     z-index: 1;
}
.right-section{
    width:50%;
    padding:40px;
    background: #fff;
     z-index: 1;
}
.info-box{background:#ffffff2c;
padding:8px 12px;
margin-top:10px;
border-radius:6px;
font-size:13px;
    
}

.form-control{
    height:40px;
   font-size:14px;
    
}
label{font-size:13.5px;font-weight:600;}
.btn-login{
    width: 100%;
  padding: 17px;
  background: linear-gradient(135deg,#0052D4,#4364F7,#6FB1FC);
    background-size: auto;
  background-size: 200% 200%;
  color: white;
  border: none;
  border-radius: 10px;
  font-size: 12px;
  font-weight: 800;
  cursor: pointer;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  text-transform: uppercase;
  letter-spacing: 2px;
  margin-top: 12px;
  position: relative;
  overflow: hidden;
  box-shadow: 0 8px 25px rgba(14, 98, 171, 0.3);
    
}
.btn-login:hover::before {
  width: 300px;
  height: 300px;
}
.btn-login::before {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  width: 0;
  height: 0;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.3);
  transform: translate(-50%, -50%);
  transition: width 0.6s ease, height 0.6s ease;
}

.small.text-danger{font-size:12px;}

.mpin-box{display:flex;justify-content:center;gap:10px;margin-top:10px;}
.mpin-box input{
    width:45px;height:45px;text-align:center;font-size:22px;
    border:2px solid #0052D4;border-radius:6px;
}

@media(max-width:900px){
    .main-container{
        flex-direction:column;
        margin-top: 30pc;
        
    }
    .left-section,.right-section{
        width:100%;
         padding: 19px;
        
    }
    
}

.btn-login:hover {
  transform: translateY(-3px);
  box-shadow: 0 12px 35px rgba(14, 98, 171, 0.4);
  background-position: 100% 50%;
    color: white;

}
.mpin-boxes input {
    width: 55px;
    height: 55px;
    text-align: center;
    font-size: 22px;
    font-weight: bold;
    border-radius: 6px;
    border: 2px solid #0052D4;
}
.mpin-boxes input:focus {
    border-color: #0041a8;
    box-shadow: 0 0 5px rgba(0,82,212,0.5);
}

#expiryNotice {
    background: linear-gradient(90deg, #ff4b2b, #ff416c);
    color: #fff;
    font-size: 15px;
    font-weight: 600;
    padding: 10px 15px;
    border-radius: 8px;
    margin: 12px 0;
    display: flex;
    align-items: center;
    gap: 8px;
    box-shadow: 0 4px 12px rgba(255, 70, 90, 0.4);
    animation: shine 10s infinite linear;
    overflow: hidden;
    position: relative;
}

#expiryNotice i {
    font-size: 18px;
}

@keyframes  shine {
    0% { background-position: 0% }
    50% { background-position: 100% }
    100% { background-position: 0% }
}



</style>
</head>

<body>
<div class="main-container">

<!-- BACKGROUND ANIMATION -->
<div class="animated-bg"><div class="wave"></div><div class="wave"></div><div class="wave"></div></div>

  <!-- LEFT SIDE -->
    <div class="left-section text-center">
        <img src="<?php echo e(env('IMAGE_SHOW_PATH').'setting/left_logo/'.$setting->left_logo ?? ''); ?>" onerror="this.src='<?php echo e(env('IMAGE_SHOW_PATH').'default/no_image.png'); ?>'"
             style="background:white; padding:10px; border-radius:15px; width:200px; box-shadow:0 2px 8px rgba(0,0,0,0.3);">

        <h3 class="mt-2" style="text-transform:uppercase; font-weight:700;">
            <?php echo e($setting->name ?? 'School Name'); ?>

        </h3>
        
        
        <h6 style="letter-spacing:3px;">SCHOOL MANAGEMENT SOFTWARE</h6>

        <p class="mt-2" style="line-height:22px; font-size:14px;">
            Digital transformation for modern schools.<br>
            Attendance • Fees • Report Cards • ID Cards • Transport • Hostel
        </p>

        <div class="info-box">✅ Fast & Easy To Use</div>
        <div class="info-box">✅ Cloud Based – Access Anywhere</div>
        <div class="info-box">✅ Secure & Backup Enabled</div>
    </div>
    
<!-- RIGHT -->
<div class="right-section">
    <h3 class="font-weight-bold">Welcome Back</h3>
    <p class="text-muted" style="font-size:14px;">Login to continue dashboard</p>

    <form id="loginForm" action="<?php echo e(url('login')); ?>" method="POST">
        <?php echo csrf_field(); ?>

        <label class="font-weight-bold mt-2">USERNAME</label>
        <div class="input-group">
            <input type="text" name="user_name" class="form-control" placeholder="Enter username">
            <div class="input-group-append"><span class="input-group-text"><i class="fa fa-user"></i></span></div>
        </div>
        <div class="text-danger" id="user_name_error"></div>

        <label class="font-weight-bold">PASSWORD</label>
        <div class="input-group">
            <input type="password" id="password" name="password" class="form-control" placeholder="Enter password">
            <div class="input-group-append toggle-password"><span class="input-group-text"><i class="fa fa-eye"></i></span></div>
        </div>
        <div class="text-danger" id="password_error"></div>

        <button type="submit" id="submitBtn" class="btn btn-login btn-block mt-2">
            <i class="fa fa-sign-in"></i> SIGN IN
        </button>
       

        
    </form>
 <!-- Two Buttons in One Line -->
            <div class="row mt-2">
                <div class="col-md-6 col-12 pr-1">
                    <button class="btn btn-login btn-block mt-1" data-toggle="modal" data-target="#mpinModal">
                       <i class="fa fa-lock"></i> Login mPIN
                    </button>
                </div>
                <div class="col-md-6 col-12 pr-1">
                    <button class="btn btn btn-mpinBtn1 btn-block mt-2" data-toggle="modal" data-target="#GenerateMpinModal">
                       <i class="fa fa-key"></i> Generate mPIN
                    </button>
                </div>
            </div>
  
        <div id="showNotice"></div>
        
     <p class="text-center mt-4 text-muted" style="font-size:12px;">
            © <?php echo e(date("Y")); ?> <?php echo e($setting->name ?? 'School'); ?> — All Rights Reserved.
        </p>
</div>

</div>

<!-- ==== mPIN MODAL ==== -->
<!-- ==== mPIN MODAL ==== -->
<div class="modal fade" id="mpinModal">
<div class="modal-dialog modal-dialog-centered">
<div class="modal-content p-4">

    <h4 class="text-center mb-3">Login with mPIN</h4>

    <form id="mpinForm" method="POST" action="<?php echo e(url('mpin-login')); ?>">
        <?php echo csrf_field(); ?>

        <div class="d-flex justify-content-center gap-3 mb-3 mpin-boxes">
            <input type="tel" maxlength="1" class="mpin-input form-control mr-1">
            <input type="tel" maxlength="1" class="mpin-input form-control mr-1">
            <input type="tel" maxlength="1" class="mpin-input form-control mr-1">
            <input type="tel" maxlength="1" class="mpin-input form-control">
        </div>

        <!-- Hidden field to send final 4 digit mpin -->
        <input type="hidden" name="mpin" id="final_mpin">

        <button type="submit" class="btn btn-primary btn-block">
            <i class="fa fa-lock"></i> Login
        </button>
    </form>

</div>
</div>
</div>



<style>
/* ====== MPIN MODAL DESIGN ====== */
#GenerateMpinModal .modal-content {
    border-radius: 16px;
    border: none;
    overflow: hidden;
}
#GenerateMpinModal .modal-header {
    background: linear-gradient(135deg,#479eea,#05355e);
    color: #fff;
    border-bottom: none;
    padding: 16px 20px;
}
#GenerateMpinModal .modal-header .close {
    color: #fff;
    opacity: 1;
}
#GenerateMpinModal label {
    font-size: 13px;
    font-weight: 600;
    margin-bottom: 4px;
}
#GenerateMpinModal input {
    height: 44px;
    font-size: 16px;
    border-radius: 8px;
}
.btn-mpinBtn1 {
    background: linear-gradient(135deg,#479eea,#05355e);
     width: 100%;
  padding: 17px;
    background-size: auto;
  background-size: 200% 200%;
  color: white;
  border: none;
  border-radius: 10px;
  font-size: 12px;
  font-weight: 800;
  cursor: pointer;
  transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
  text-transform: uppercase;
  letter-spacing: 2px;
  margin-top: 12px;
  position: relative;
  overflow: hidden;
  box-shadow: 0 8px 25px rgba(14, 98, 171, 0.3);
}
.btn-mpinBtn1:hover::before {
  width: 300px;
  height: 300px;
  color: white;
}
.btn-mpinBtn1::before {
  content: '';
  position: absolute;
  top: 50%;
  left: 50%;
  width: 0;
  height: 0;
  border-radius: 50%;
  background: rgba(255, 255, 255, 0.3);
  transform: translate(-50%, -50%);
  transition: width 0.6s ease, height 0.6s ease;
  color: white;
}

.mpin-title {
    font-size: 20px;
    font-weight: 700;
}
.mpin-sub {
    font-size: 13px;
    color: #777;
    margin-top: -6px;
}
@media (max-width: 480px){
    #GenerateMpinModal .modal-dialog {
        margin: 10px;
    }
    
    .right-section {
   padding: 19px;
}
}



</style>

<!-- MODAL -->
<div class="modal fade" id="GenerateMpinModal" tabindex="-1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="mpin-title mb-0">🔐 Generate mPIN</h5>
        <button type="button" class="close" data-dismiss="modal">
          <span>&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <p class="text-center mpin-sub">Enter Username & Password to create 4-digit mPIN</p>

        <form id="GenerateMpinForm">
          <?php echo csrf_field(); ?>

          <div class="form-group">
            <label>Username</label>
            <input type="text" name="user_name" id="mpin_user" class="form-control" placeholder="Enter username" >
          </div>

          <div class="form-group">
            <label>Password</label>
            <input type="text" name="password" id="mpin_pass" class="form-control" placeholder="Enter password" >
          </div>

          <div class="form-group">
            <label>Create 4-Digit mPIN</label>
            <input type="number" name="mpin" id="mpin_code" class="form-control text-center font-weight-bold" placeholder="••••"
            oninput="if(this.value.length>4)this.value=this.value.slice(0,4)" >
          </div>

          <button type="submit" id="GenerateMpinBtn" class="btn btn-dark w-100">
            ✅ Save mPIN
          </button>
        </form>
      </div>

    </div>
  </div>
</div>



<!-- JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>


$.ajaxSetup({
    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
});


$('#GenerateMpinModal').on('shown.bs.modal', function () {
    $('#mpin_user').focus();
});

$('#GenerateMpinForm').submit(function(e){
    e.preventDefault();
    $('#GenerateMpinBtn').text('Saving...').prop('disabled', true);

    $.ajax({
        url: "<?php echo e(url('save-mpin')); ?>",
        type: "POST",
        data: $(this).serialize(),
        success: function(res){
            $('#GenerateMpinBtn').text('Save mPIN').prop('disabled', false);
            if(res.status === 'success'){
                toastr.success(res.message);
                $('#GenerateMpinModal').modal('hide');
                $('#GenerateMpinForm')[0].reset();
            } else {
                toastr.error(res.message);
            }
        },
        error: function(){
            $('#GenerateMpinBtn').text('Save mPIN').prop('disabled', false);
            toastr.error("Server Error");
        }
    });
});

// Password Toggle
$(".toggle-password").click(function() {
    let input = $("#password");
    let icon = $(this).find("i");
    if (input.attr("type") === "password") {
        input.attr("type", "text");
        icon.removeClass("fa-eye").addClass("fa-eye-slash");
    } else {
        input.attr("type", "password");
        icon.removeClass("fa-eye-slash").addClass("fa-eye");
    }
});

// AJAX Login
$('#loginForm').on('submit', function(e) {
    e.preventDefault();

    let form = $(this);
    let submitButton = $('#submitBtn');

    submitButton.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Signing In...');

    $('#user_name_error').text('');
    $('#password_error').text('');

    $.ajax({
        url: form.attr('action'),
        type: "POST",
        data: form.serialize(),
        success: function(res) {
            submitButton.prop('disabled', false).html('<i class="fa fa-sign-in"></i> SIGN IN');

            if (res.status === 'success') {
                //toastr.success(res.message);
                setTimeout(() => { window.location.href = res.redirect_url; }, 800);
            } else {
                $('#user_name_error').text(res.user_name_error);
                $('#password_error').text(res.password_error);
                if (res.message) toastr.error(res.message);
            }
        },
        error: function(xhr) {
            submitButton.prop('disabled', false).html('<i class="fa fa-sign-in"></i> SIGN IN');
            let errors = xhr.responseJSON.errors;
            $('#user_name_error').text(errors?.user_name?.[0] || '');
            $('#password_error').text(errors?.password?.[0] || '');
        }
    });
});

// ✅ Only number allowed + auto next focus
$(".mpin-input").on("input", function () {
    this.value = this.value.replace(/[^0-9]/g, '');
    if (this.value.length === 1) {
        $(this).next('.mpin-input').focus();
    }

    let mpin = "";
    $(".mpin-input").each(function () {
        mpin += $(this).val();
    });
    $("#final_mpin").val(mpin);
});

// ✅ Backspace support
$(".mpin-input").on("keydown", function (e) {
    if (e.key === "Backspace" && this.value === "") {
        $(this).prev('.mpin-input').focus();
    }
});

// ✅ AJAX login submit
$("#mpinForm").on("submit", function (e) {
    e.preventDefault();

    let mpin = $("#final_mpin").val();
    if (mpin.length < 4) {
        toastr.error("Please enter 4-digit mPIN");
        return;
    }

    $.ajax({
        url: "<?php echo e(url('mpin-login')); ?>",
        type: "POST",
        data: {
            _token: "<?php echo e(csrf_token()); ?>",
            mpin: mpin
        },
        success: function (response) {
            if (response.status === "success") {
                toastr.success(response.message);
                setTimeout(() => {
                    window.location.href = response.redirect_url;
                }, 800);
            } else {
                toastr.error(response.message);
            }
        },
        error: function () {
            toastr.error("Server error, please try again!");
        }
    });
});

</script>
<!--Default Login Page Content Start-->

<style>
* {
  box-sizing: border-box;
  margin: 0;
  padding: 0;
}
body {
  font-family: Arial, sans-serif;
  background-color: white;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
}
.refresh-button {
  display: inline-block;
  padding: 12px 24px;
  background-color: #6639b5;
  color: #fff;
  border: 1px solid transparent;
  border-color: #6639b5;
  border-radius: 4px;
  font-size: 16px;
  cursor: pointer;
  transition: background-color 0.3s ease;
  text-decoration:none;
}
.refresh-button:hover {
    color: #ff5722;
    background-color: #6639b500;
    border-color: #ff5722;
}
.connectionimg{max-width: 400px;}
section{
    position: absolute;
    top: 0;
    width: 100%;
    display: flex;
    height: 100%;
    align-items: center;
    text-align: center;
    justify-content: center;
}
</style>





</body>
</html>
<?php /**PATH C:\xampp\htdocs\dev\resources\views/auth/login.blade.php ENDPATH**/ ?>