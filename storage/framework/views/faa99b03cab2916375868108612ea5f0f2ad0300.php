<?php
$student_count = Helper::getCount('admissions','id','count');
$teacher_count = Helper::getCount('teachers','id','count');
$user_count = Helper::getCount('users','id','count');
$noticeBoard = Helper::noticeBoard();
?>
 
<?php $__env->startSection('content'); ?>

<div class="content-wrapper" id="contentWrapper">

   <section class="content pt-3">
      <div class="container-fluid">
        <div class="row">
          <div class="col-12 col-md-12">
            <div class="card card-outline card-orange">
                <div class="card-header bg-primary">
                    <h3 class="card-title"><i class="fa fa-home"></i> &nbsp; Teacher Dashboard</h3>
                    <div class="card-tools">
                        <!--<a href="<?php echo e(url('add_user')); ?>" class="btn btn-primary  btn-sm" title="Add User"><i class="fa fa-plus"></i> Add User</a>-->
                    </div>
            
                </div>               
            </div>
            </div>
        </div>
        
        <div class="row">
            
            <div class="col-12 col-sm-6 col-md-3">
                <a href="<?php echo e(url('admissionView')); ?>">
                <div class="info-box mb-3 text-dark">
                    <span class="info-box-icon bg-success elevation-1"><i class="fa fa-graduation-cap"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">TOTAL STUDENTS</span>
                    <span class="info-box-number"><?php echo e(\App\Models\Admission::countActiveAdmission()->count()); ?></span>
                    </div>
                </div>
                </a>
            </div>  
            <div class="col-12 col-sm-6 col-md-3">
                <a href="<?php echo e(url('studentsAttendanceView')); ?>">
                <div class="info-box mb-3 text-dark">
                    <span class="info-box-icon bg-danger elevation-1"><i class="ion ion-stats-bars"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">PRESENT STUDENTS</span>
                    <span class="info-box-number"><?php echo e(\App\Models\StudentAttendance::countPresentStudents()->count()); ?></span>
                    </div>
                </div>
                </a>
            </div>

            <div class="col-12 col-sm-6 col-md-3">
                <a href="<?php echo e(url('homework/index')); ?>">
                <div class="info-box mb-3 text-dark">
                    <span class="info-box-icon bg-primary elevation-1"><i class="fa fa-money"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">TODAY HOMEWORK</span>
                    <span class="info-box-number"><?php echo e(\App\Models\Master\Homework::todayHomework()); ?></span>
                    </div>
                </div>
                </a>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <a href="<?php echo e(url('homework/index')); ?>">
                <div class="info-box mb-3 text-dark">
                    <span class="info-box-icon bg-warning elevation-1"><i class="fa fa-money"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">TODAY ASSIGNMENT</span>
                    <span class="info-box-number"><?php echo e(\App\Models\Master\UploadHomework::countTodayAssignment()); ?></span>
                    </div>
                </div>
                </a>
            </div>
           <!-- <div class="col-12 col-sm-6 col-md-3">
                <a href="<?php echo e(url('expense_view')); ?>">
                <div class="info-box mb-3 text-dark">
                    <span class="info-box-icon bg-danger elevation-1"><i class="fa fa-credit-card"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">MONTHLY EXPENSES</span>
                    <span class="info-box-number"><i class="fa fa-rupee"></i></span>
                    </div>
                </div>
                </a>
            </div>
            <div class="col-12 col-sm-6 col-md-3">
                <a href="<?php echo e(url('view_user')); ?>">
                <div class="info-box mb-3 text-dark">
                    <span class="info-box-icon bg-primary elevation-1"><i class="fa fa-user-secret"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">TOTAL USERS</span>
                    <span class="info-box-number"></span>
                    </div>
                </div>
                </a>
            </div>          -->  
        </div>        
        
        <div class="row">
            <div class="col-md-6">
                    <div class="card" style="height: 283px;">
                        <div class="card-header ui-sortable-handle">
                            <h3 class="card-title">
                                <i class="ion ion-clipboard mr-1"></i>
                                <?php echo e(__('dashboard.To Do List')); ?>

                            </h3>
                            <div class="card-tools" style="width:70%;">
                                <div class="row">
                                    <div class="col-md-8">
                                        <input type="text" class="form-control form-control-border" id="task"
                                            name="task" placeholder="Enter Task..">
                                    </div>
                                    <div class="col-md-4">
                                        <button type="button" class="add_task btn btn-primary float-right btn-xs"><i
                                                class="fa fa-plus"></i> <?php echo e(__('messages.Add')); ?></button>
                                    </div>
                                </div>

                            </div>
                        </div>
                         
                        <div class="card-body">
                           
                            <ul class="todo-list ui-sortable todoList" data-widget="todo-list">
                              
                            </ul>
                        </div>
                        
                    </div>
                </div>
            <div class="col-md-6">
                <div class="card">
                <div class="card-header ui-sortable-handle">
                <h3 class="card-title">
                <i class="fa fa-bell-o mr-1"></i>
                Notifications
                </h3>

                </div>
                
                <div class="card-body">
                    <marquee direction="up" scrollamount="4" id="test" onMouseOver="document.all.test.stop()" onMouseOut="document.all.test.start()">
                        <ul class="todo-list ui-sortable" data-widget="todo-list">
                            <?php if(!empty($noticeBoard)): ?>
                            <?php $__currentLoopData = $noticeBoard; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <li class="">
                                    <a href="<?php echo e(url('notice_board/view')); ?>">
                                    <span class="text text-dark"><?php echo e($item->title ?? ''); ?></span>
                                    <small class="badge badge-danger"><i class="fa fa-envelope-o"></i> New</small>
                                    </a>
                                </li>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>
                        </ul>
                    </marquee>
                </div>

                </div>        
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6" id="calendarElement">

            </div>
        </div>
	
        </div>
    </section>

</div>


<script>

   $(window).on("load", function(){
        tableviewajax()
			
			  });
      function tableviewajax() {
     $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });
		            $.ajax({
                     	url:'/task_list',
					type:'post',
				  data: {
                status: "1",
               
            },
               
                success: function(result) {
                   // var result = JSON.parse(result);
                
                    if (result) {

                      //  toastr.success(result.msg);
                        	$('.todoList').html(result)
                        //	alert("done");
                      
                    } else {
                       	$('.todoList').html("<p class='text-center'><img style='width:184px' src='https://thumbs.dreamstime.com/b/task-task-icon-155379995.jpg'></p>")
                    }
                }
            })
      }
      
    $(document).on('click', ".add_task", function () {
        var task = $('#task').val();
        var data = { 'task': task }
        if(task=="")
        {
             setTimeout(function() {
          $("#task").css("border-bottom","1px solid red")
          $("#task").css("margin-left","0px")
          }, 20);
             setTimeout(function() {
          $("#task").css("border-bottom","1px solid black")
           $("#task").css("margin-left","3px")
          }, 40);
             setTimeout(function() {
          $("#task").css("border-bottom","1px solid red")
           $("#task").css("margin-left","0px")
          }, 60);
             setTimeout(function() {
          $("#task").css("border-bottom","1px solid black")
           $("#task").css("margin-left","3px")
          }, 80);
             setTimeout(function() {
          $("#task").css("border-bottom","1px solid red")
           $("#task").css("margin-left","0px")
          }, 100);
             setTimeout(function() {
          $("#task").css("border-bottom","1px solid black")
           $("#task").css("margin-left","3px")
          }, 120);
             setTimeout(function() {
          $("#task").css("border-bottom","1px solid red")
           $("#task").css("margin-left","0px")
          }, 140);
             
        }
        else{
        
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });
        $.ajax({
            type: "POST",
            url: "/add/task",
            data: data,
            dataType: "html",
            success: function (response) {
                toastr.success('Task Added Successfully.');
                  tableviewajax()
                  $("#task").val("");
                     $("#task").css("border-bottom","1px solid black");
            },
        });
        }
    });

    $(document).on('click', ".task_status", function () {
        var id = $(this).data('id');
        var status = $(this).data('status');
        $.ajax({
            url: '/status/task',
            type: 'post',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
                status: status,
                id: id
            },
            success: function () {
                toastr.success('Record Saved Successfully.');
               
            },
        });
    });

    $(document).on('click', ".task_delete", function () {
        var task_id = $(this).data('id');
        var data = { 'task_id': task_id }
        $.ajaxSetup({
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
        });
        $.ajax({
            type: "POST",
            url: "/delete/task",
            data: data,
            dataType: "html",
            success: function (response) {
                $("#task_li").remove();
                toastr.error('Task Deleted Successfully.');
              tableviewajax()
            },
        });
    });
</script>

<?php $__env->stopSection(); ?>
  

<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/dashboard/teacher_dashboard.blade.php ENDPATH**/ ?>