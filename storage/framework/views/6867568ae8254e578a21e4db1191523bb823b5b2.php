<!DOCTYPE html>
<html lang="en">
<?php
    $host = request()->getHost();              // example: school.domain.com
    $hostParts = explode('.', $host);
    $subdomain = $hostParts[0] ?? '';
    $domain    = $hostParts[1] ?? $hostParts[0];
?>

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>New School Registration</title>

<!-- ✅ CSRF -->
<meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

<!-- Fonts & Icons -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

<!-- jQuery -->
<script src="<?php echo e(asset('public/assets/school/js/jquery.min.js')); ?>"></script>

<!-- Toastr -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<style>
*{box-sizing:border-box;font-family:'Poppins',sans-serif}
body{
    background:url('<?php echo e(env('IMAGE_SHOW_PATH').'default/Icon_images/rm347-porpla-01.jpg'); ?>') no-repeat center/cover;
    min-height:100vh;
    display:flex;
    justify-content:center;
    align-items:center;
    padding:20px;
}
.card{
    width:100%;
    max-width:850px;
    background:#ffffffee;
    border-radius:18px;
    padding:30px 40px;
    box-shadow:0 15px 35px rgba(0,0,0,.25);
}
h2{
    text-align:center;
    margin-bottom:25px;
    font-weight:600;
}
.form-group{margin-bottom:15px}
label{font-size:13px;font-weight:500}
input{
    width:100%;
    padding:11px 12px;
    border-radius:6px;
    border:1px solid #ccc;
    margin-top:5px;
    font-size:14px;
}
input:focus{
    outline:none;
    border-color:#6639b5;
    box-shadow:0 0 0 2px rgba(102,57,181,.15);
}
input::placeholder{
    color:#999;
    font-size:13px;
}
.is-invalid{border-color:red}
.error{color:red;font-size:13px;margin-top:4px}
.submit-btn{
    margin-top:25px;
    padding:13px 40px;
    background:#6639b5;
    border:none;
    color:white;
    font-size:15px;
    border-radius:6px;
    cursor:pointer;
}
.submit-btn:disabled{opacity:.6}
</style>
</head>

<body>

<div class="card">

    <h2>School Environment Setup</h2>

    <form action="<?php echo e(url('newRegistration')); ?>" method="POST" id="form-submit">
        <?php echo csrf_field(); ?>

        <div class="form-group">
            <label>Software Token *</label>
            <input type="text" name="softwareTokenNo"
                   placeholder="Enter software activation token (e.g. RUK-XXXX-XXXX)" value="<?php echo e(env('SOFTWARE_TOKEN_NO')); ?>">
        </div>

        <div class="form-group">
            <label>Database Name *</label>
            <input type="text" name="dbDatabase"
                   placeholder="Enter database name (e.g. school_management)" value="<?php echo e(env('DB_DATABASE')); ?>">
        </div>

        <div class="form-group">
            <label>Database Username *</label>
            <input type="text" name="dbUsername"
                   placeholder="Enter database username (e.g. root)" value="<?php echo e(env('DB_USERNAME')); ?>">
        </div>

        <div class="form-group">
            <label>Database Password *</label>
            <input type="password" name="dbPassword"
                   placeholder="Enter database password" value="<?php echo e(env('DB_PASSWORD')); ?>">
        </div>

        <div class="form-group">
            <label>Image Show Path *</label>
            <input type="text" name="imageShowPath"
                   value="https://<?php echo e($host); ?>/schoolimage/"
                   placeholder="Public image URL (e.g. https://yoursite.com/schoolimage/)">
        </div>

        <div class="form-group">
            <label>Image Upload Path *</label>
            <input type="text" name="imageUploadPath"
                   value="/home/<?php echo e($domain); ?>/public_html/<?php echo e($subdomain); ?>/schoolimage/"
                   placeholder="Server upload path (e.g. /home/domain/public_html/schoolimage/)">
        </div>

        <center>
            <button type="submit" class="submit-btn btn-submit">
                Submit
            </button>
        </center>

    </form>
</div>

<script>
$(function () {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $('#form-submit').on('submit', function (e) {
        e.preventDefault();

        let $form = $(this);
        let btn = $('.btn-submit');
        let originalText = btn.text();

        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,

            beforeSend: function () {
                btn.prop('disabled', true)
                   .html('<i class="fa fa-spinner fa-spin"></i> Installing...');
                $('.error').remove();
                $('.is-invalid').removeClass('is-invalid');
            },

            success: function (res) {
                toastr.success(res.message);
                btn.html('Installed ✔');
                 window.location.href = 'allowSidebar';
               // $form.trigger('reset');
            },

            error: function (xhr) {
                btn.prop('disabled', false).text(originalText);

                if (xhr.status === 422) {
                    $.each(xhr.responseJSON.errors, function (key, val) {
                        let input = $form.find('[name="'+key+'"]');
                        input.addClass('is-invalid')
                             .after('<div class="error">'+val[0]+'</div>');
                    });
                } else {
                    toastr.error(xhr.responseJSON?.message || 'Something went wrong');
                }
            }
        });
    });

});
</script>

</body>
</html>
<?php /**PATH /home/rusofterp/public_html/dev/resources/views/auth/newRegistration.blade.php ENDPATH**/ ?>