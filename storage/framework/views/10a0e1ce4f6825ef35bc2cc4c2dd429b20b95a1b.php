

<div class="timetable-wrapper">
    <div class="table-responsive">
  <div class="row">
        <?php $__currentLoopData = $classType; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $class): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

        <?php
            $getsubject = \App\Models\Subject::where('class_type_id',$class->id)
                        ->orderBy('id','ASC')
                        ->get();
        ?>
          <div class="col-md-6">

        <div class="card shadow-sm mb-1 class-wrapper"
     data-class="<?php echo e($class->id); ?>">

            <!-- Header -->
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
              <div class="form-check m-0">
                    <input 
                        type="checkbox"
                        class="form-check-input select-all"
                        data-class="<?php echo e($class->id); ?>"
                        id="select_all_<?php echo e($class->id); ?>"
                    >
                    <label class="form-check-label text-white" for="select_all_<?php echo e($class->id); ?>">
                        Select All
                    </label>
                </div>
                <strong><?php echo e($class->name); ?></strong>

                <!-- Select All -->
                
            </div>

            <!-- Subjects -->
            <div class="card-body">
                <div class="row">

                    <?php $__currentLoopData = $getsubject; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>

                    <div class="col-md-3 col-sm-3 col-4 mb-1">
                        <div class="form-check custom-checkbox-box">
                            <input 
    type="checkbox"
    class="form-check-input subject-checkbox"
    value="<?php echo e($subject->id); ?>"
    <?php echo e(in_array($subject->id, $selectedSubjects ?? []) ? 'checked' : ''); ?> 
>
                            <label class="form-check-label" for="subject_<?php echo e($subject->id); ?>">
                                <?php echo e($subject->name); ?>

                            </label>
                        </div>
                    </div>

                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                </div>
            </div>

        </div>
</div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>
    </div>
</div>

<script>
document.addEventListener("change", function(e){

    // =========================
    // SELECT ALL
    // =========================
    if(e.target.classList.contains("select-all")){

        let classWrapper = e.target.closest(".class-wrapper");
        let classId = classWrapper.dataset.class;

        classWrapper.querySelectorAll(".subject-checkbox")
        .forEach(function(cb){
            cb.checked = e.target.checked;

            saveSingleSubject(cb, classId);
        });
    }
    
    document.querySelectorAll(".class-wrapper").forEach(function(wrapper){

        let allSubjects = wrapper.querySelectorAll(".subject-checkbox");
        let checkedSubjects = wrapper.querySelectorAll(".subject-checkbox:checked");
        let selectAll = wrapper.querySelector(".select-all");

        if(selectAll){
            selectAll.checked = (allSubjects.length === checkedSubjects.length);
        }

    });

    // =========================
    // SINGLE SUBJECT
    // =========================
    if(e.target.classList.contains("subject-checkbox")){

        let classWrapper = e.target.closest(".class-wrapper");
        let classId = classWrapper.dataset.class;

        saveSingleSubject(e.target, classId);

        // Update Select All checkbox
        let all = classWrapper.querySelectorAll(".subject-checkbox");
        let checked = classWrapper.querySelectorAll(".subject-checkbox:checked");
        let selectAll = classWrapper.querySelector(".select-all");

        if(selectAll){
            selectAll.checked = (all.length === checked.length);
        }
    }

});


function saveSingleSubject(checkbox, classId){

    let userId = document.getElementById("modal_user_id").value;

    fetch("<?php echo e(url('user/timetable/save')); ?>", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>"
        },
        body: JSON.stringify({
            class_id: classId,
            user_id: userId,
            subject_id: checkbox.value,
            status: checkbox.checked
        })
    });

}</script>



<style>
    .card-header .form-check {
    display: flex;
    align-items: center;
    gap: 6px;
}

.card-header .form-check-input {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.custom-checkbox-box {
    background: linear-gradient(135deg, #ffffff, #f1f5f9);
    padding: 10px 12px;
    border-radius: 14px;
    border: 1px solid #e2e8f0;
    transition: all 0.25s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}

.custom-checkbox-box:hover {
    background: linear-gradient(135deg, #eef2ff, #e0e7ff);
    border-color: #6366f1;
    transform: translateY(-2px);
    box-shadow: 0 8px 18px rgba(99, 102, 241, 0.15);
}

.subject-checkbox {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.form-check-label {
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
}

.subject-checkbox:checked {
    box-shadow: 0 0 0 3px rgba(99,102,241,.25);
}

</style>



<?php /**PATH /home/rusofterp/public_html/dev/resources/views/user/users/user_time_table.blade.php ENDPATH**/ ?>