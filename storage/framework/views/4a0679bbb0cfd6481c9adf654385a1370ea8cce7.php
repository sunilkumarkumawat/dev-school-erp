<?php
$getUser = Helper::getUser();
$getSetting = Helper::getSetting();
?>

<?php $__env->startSection('title', 'Profile'); ?>
<?php $__env->startSection('page_title', 'PROFILE'); ?>
<?php $__env->startSection('page_sub', Session::get('first_name') . '-' . $getUser['ClassTypes']['name']); ?>
<?php $__env->startSection('content'); ?>
<section class="profile-page">

  <!-- ðŸ”¹ Header Section -->
  <div class="profile-header">
    <div class="profile-header-bg">
      <img src="<?php echo e(asset('public/assets/student_login/img/profile_bg.avif')); ?>" alt="Background">
      
    </div>

    <div class="profile-header-content">
      <div class="profile-header-text">
        <h5 class="fw-bold text-white mb-0" data-field="first_name"><?php echo e($getUser->first_name ?? ''); ?></h5>
        <p class="text-white mb-0 small"><?php echo e($getSetting->name ?? ''); ?></p>
        <small class="text-white-50"><?php echo e($getUser['ClassTypes']['name']); ?></small>
      </div>

      <!-- ðŸ”¹ Profile Photo -->
      <div class="profile-photo text-center mt-3">
        <div class="circle position-relative d-inline-block"> 
            <img id="profileImage" src="<?php echo e(env('IMAGE_SHOW_PATH') . '/profile/' . $getUser['image']); ?>" onerror="this.onerror=null; this.src='<?php echo e(asset('public/assets/student_login/img/user_icon.png')); ?>'">

          <i class="bi bi-camera position-absolute camera-icon" id="cameraIcon"></i>
        </div>
        <input type="file" id="photoInput" accept="image/*" hidden>
      </div>
    </div>
  </div>

  <!-- ðŸ”¹ Bottom Sheet -->
  <div class="photo-bottom-sheet" id="photoBottomSheet">
    <div class="photo-sheet-content" id="photoSheetContent">
      <div class="sheet-handle"></div>
      <ul class="list-unstyled mb-0 text-center">
        <li id="viewPhoto"><i class="bi bi-eye me-2"></i> View Photo</li>
        <li id="uploadPhoto"><i class="bi bi-upload me-2"></i> Upload New</li>
        <li id="deletePhoto"><i class="bi bi-trash me-2"></i> Remove Photo</li>
        <li class="cancel" id="cancelSheet"><strong>Cancel</strong></li>
      </ul>
    </div>
  </div>

  <!-- ðŸ”¹ Edit Button -->
  <div class="text-end mt-3 px-3">
    <button id="editProfileBtn" class="btn btn-sm btn-primary">
      <i class="bi bi-pencil"></i> Edit Profile
    </button>
    <button id="saveProfileBtn" class="btn btn-sm btn-success d-none">
      <i class="bi bi-check2-circle"></i> Save Changes
    </button>
  </div>


  <div class="profile-details">
    <div class="info-grid">
      <div class="info-row">
        <div class="info-col">
          <label>Name</label>
          <p data-field="first_name"><?php echo e($data->first_name ?? ''); ?></p>
        </div>
        <div class="info-col">
          <label>Mobile No.</label>
          <p data-field="mobile"><?php echo e($data->mobile ?? ''); ?></p>
        </div>
       
      </div>
        <div class="info-row single">
         <div class="info-col">
          <label>E-mail</label>
        <p data-field="email"><?php echo e($data->email ?? ''); ?></p>
        </div>
       
      </div>
        <div class="info-row single">
        <div class="info-col">
          <label>Address</label>
          <p data-field="address"><?php echo e($data->address ?? ''); ?></p>
        </div>
      </div>

      <div class="info-row">
        <div class="info-col">
          <label>Mother's Name</label>
          <p data-field="mother_name"><?php echo e($data->mother_name ?? ''); ?></p>
        </div>
         <div class="info-col">
          <label>Father's Name</label>
          <p data-field="father_name"><?php echo e($data->father_name ?? ''); ?></p>
        </div>
       
      </div>

      <div class="info-row">
        <div class="info-col">
          <label>Father's Mobile No.</label>
          <p data-field="father_mobile"><?php echo e($data->father_mobile ?? ''); ?></p>
        </div>
       <div class="info-col">
          <label>Mother's Mobile No.</label>
          <p data-field="mother_mobile"><?php echo e($data->mother_mob ?? ''); ?></p>
        </div>
      </div>

     
    </div>
  </div>
</section>
<script>
    document.addEventListener("DOMContentLoaded", function () {

  const editBtn = document.getElementById("editProfileBtn");
  const saveBtn = document.getElementById("saveProfileBtn");

  editBtn.addEventListener("click", function () {

    const fields = document.querySelectorAll(".info-col p, .info-row.single p");

    fields.forEach(p => {
      const value = p.textContent.trim();
      const name = p.getAttribute("data-field");

      const input = document.createElement("input");
      input.type = "text";
      input.value = value;
      input.name = name;
      input.classList.add("form-control", "form-control-sm", "editable-field");

      p.replaceWith(input);
    });

    editBtn.classList.add("d-none");
    saveBtn.classList.remove("d-none");
  });

  saveBtn.addEventListener("click", function () {

    const inputs = document.querySelectorAll(".editable-field");

    let formData = {};
    inputs.forEach(input => {
      formData[input.name] = input.value;
    });

    fetch("<?php echo e(url('profileStudent')); ?>", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
      },
      body: JSON.stringify(formData)
    })
    .then(res => res.json())
    .then(data => {

      if (data.status === "success") {

 
          inputs.forEach(input => {
            const newP = document.createElement("p");
            newP.textContent = input.value;
            newP.setAttribute("data-field", input.name);
            input.replaceWith(newP);
          });
        
          saveBtn.classList.add("d-none");
          editBtn.classList.remove("d-none");
        
          Swal.fire({
            icon: 'success',
            title: 'Profile Updated',
            text: 'Your profile details have been successfully saved!',
            confirmButtonColor: '#3085d6'
          });
        
        } else {
        
        
          Swal.fire({
            icon: 'error',
            title: 'Update Failed',
            text: 'Something went wrong while updating the profile.',
            confirmButtonColor: '#d33'
          });
        
        }
    });

  });

});
</script>


<script>
document.addEventListener('DOMContentLoaded', () => {
  const cameraIcon   = document.getElementById('cameraIcon');
  const bottomSheet  = document.getElementById('photoBottomSheet');
  const sheet        = document.getElementById('photoSheetContent');
  const cancelBtn    = document.getElementById('cancelSheet');
  const photoInput   = document.getElementById('photoInput');
  const profileImage = document.getElementById('profileImage');

  // Create loading overlay
  const loadingOverlay = document.createElement('div');
  loadingOverlay.style.position = 'absolute';
  loadingOverlay.style.top = '0';
  loadingOverlay.style.left = '0';
  loadingOverlay.style.width = '100%';
  loadingOverlay.style.height = '100%';
  loadingOverlay.style.background = 'rgba(255,255,255,0.7)';
  loadingOverlay.style.display = 'flex';
  loadingOverlay.style.alignItems = 'center';
  loadingOverlay.style.justifyContent = 'center';
  loadingOverlay.style.fontSize = '16px';
  loadingOverlay.style.fontWeight = 'bold';
  loadingOverlay.style.color = '#333';
  loadingOverlay.style.zIndex = '1000';
  loadingOverlay.style.borderRadius = '50%';
  loadingOverlay.innerText = 'Uploading...';
  loadingOverlay.style.display = 'none';
  profileImage.parentElement.style.position = 'relative';
  profileImage.parentElement.appendChild(loadingOverlay);

  // ---------------- Bottom sheet UI ----------------
  let startY = 0, currentY = 0, dragging = false;

  cameraIcon.addEventListener('click', e => {
    e.stopPropagation();
    bottomSheet.classList.add('active');
    sheet.style.transform = 'translateY(0)';
  });

  function closeSheet() {
    sheet.style.transition = 'transform .25s ease';
    sheet.style.transform  = 'translateY(100%)';
    setTimeout(() => {
      bottomSheet.classList.remove('active');
      sheet.style.transition = 'none';
      sheet.style.transform  = 'translateY(0)';
    }, 250);
  }

  cancelBtn.addEventListener('click', closeSheet);
  bottomSheet.addEventListener('click', e => {
    if(e.target === bottomSheet) closeSheet();
  });

  const startDrag = y => { startY = y; dragging = true; sheet.style.transition='none'; };
  const moveDrag = y => { if(!dragging) return; currentY=y; const diff=currentY-startY; if(diff>0) sheet.style.transform=`translateY(${diff}px)`; };
  const endDrag = () => { if(!dragging) return; dragging=false; const diff=currentY-startY; sheet.style.transition='transform .25s ease'; if(diff>100) closeSheet(); else sheet.style.transform='translateY(0)'; };

  sheet.addEventListener('touchstart', e=>startDrag(e.touches[0].clientY), {passive:true});
  sheet.addEventListener('touchmove', e=>{moveDrag(e.touches[0].clientY); if(dragging) e.preventDefault();}, {passive:false});
  sheet.addEventListener('touchend', endDrag);
  sheet.addEventListener('mousedown', e=>startDrag(e.clientY));
  window.addEventListener('mousemove', e=>moveDrag(e.clientY));
  window.addEventListener('mouseup', endDrag);

  // ---------------- Image Options ----------------
  document.getElementById('viewPhoto').onclick = () => {
    window.open(profileImage.src, '_blank');
    closeSheet();
  };

  document.getElementById('uploadPhoto').onclick = () => {
    closeSheet();
    photoInput.click();
  };

  
  photoInput.addEventListener('change', function(e){
    const file = this.files[0];
    if(!file) return;

  
    const reader = new FileReader();
    reader.onload = ev => profileImage.src = ev.target.result;
    reader.readAsDataURL(file);

    loadingOverlay.style.display = 'flex';

    const formData = new FormData();
    formData.append('photo', file);

    fetch("<?php echo e(url('profileStudent')); ?>", {
      method: "POST",
      headers: { "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
      body: formData
    })
    .then(res => res.json())
    .then(data => {
  loadingOverlay.style.display = 'none';

  if (data.status === 'success') {
    profileImage.src = data.image_url;

          
            Swal.fire({
              icon: 'success',
              title: 'Upload Successful!',
              text: 'Your profile photo has been updated!',
              confirmButtonColor: '#3085d6'
            });
        
          } else {
           
            Swal.fire({
              icon: 'error',
              title: 'Upload Failed',
              text: 'Something went wrong. Please try again!',
              confirmButtonColor: '#d33'
            });
          }
        })
        .catch(() => {
          loadingOverlay.style.display = 'none';
        
         
          Swal.fire({
            icon: 'error',
            title: 'Upload Failed',
            text: 'Network error! Please try again.',
            confirmButtonColor: '#d33'
          });
        });
  });

  
  document.getElementById('deletePhoto').onclick = () => {
  fetch("<?php echo e(url('profileStudent')); ?>", {
    method: "POST",
    headers: { 
      "Content-Type": "application/json",
      "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify({ delete_photo:true })
  })
  .then(res => res.json())
  .then(data => {
    if(data.status === "success"){
      profileImage.src = "<?php echo e(asset('public/assets/student_login/img/user_icon.png')); ?>";

    
      Swal.fire({
        icon: 'success',
        title: 'Photo Removed',
        text: 'Your profile photo has been successfully deleted!',
        confirmButtonColor: '#3085d6'
      });
    }
  });

  closeSheet();
};

});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('student_login.layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/student_login/profile.blade.php ENDPATH**/ ?>