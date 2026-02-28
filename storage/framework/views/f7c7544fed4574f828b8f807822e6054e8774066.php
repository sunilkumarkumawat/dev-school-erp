<?php $__env->startSection('content'); ?>

<?php echo $__env->make('attendance.theme', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php
    $attendanceType = old('attendance_type', $setting->attendance_type ?? 2);
    $allowBackDate = (int) old('allow_back_date_attendance', $setting->allow_back_date_attendance ?? 0);
    $manualAttendanceMessagingEnabled = (int) old('manual_attendance_messaging_enabled', $setting->manual_attendance_messaging_enabled ?? 0);
    $autoAbsentMarkEnabled = (int) old('auto_absent_mark_enabled', $setting->auto_absent_mark_enabled ?? 0);
    $messagingServicesRaw = old('messaging_services', !empty($setting->messaging_services) ? explode(',', $setting->messaging_services) : ['whatsapp', 'firebase', 'sms']);
    $messagingServices = is_array($messagingServicesRaw) ? array_map('strtolower', $messagingServicesRaw) : ['whatsapp', 'firebase', 'sms'];
    $timeVal = function (string $key) use ($setting) {
        $v = old($key, $setting->{$key} ?? '');
        if (!$v) return '';
        // DB time columns are usually `HH:MM:SS`; HTML time inputs expect `HH:MM`.
        return strlen($v) >= 5 ? substr($v, 0, 5) : $v;
    };
?>

<div class="content-wrapper attendance-page">
    <section class="content pt-3">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card card-outline card-orange">
                        <div class="card-header bg-primary">
                            <h3 class="card-title"><i class="fa fa-cogs"></i> &nbsp;Attendance Configuration</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-7">
                                    <form action="<?php echo e(url('attendance/settings')); ?>" method="post">
                                        <?php echo csrf_field(); ?>

                                        <?php if($errors->any()): ?>
                                            <div class="alert alert-danger">
                                                <strong>Validation Error:</strong>
                                                <ul class="mb-0 pl-3">
                                                    <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <li><?php echo e($error); ?></li>
                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                </ul>
                                            </div>
                                        <?php endif; ?>

                                        <div class="attendance-card mb-3">
                                            <h5>Attendance Type</h5>
                                            <div class="attendance-inline">
                                                <div class="custom-control custom-radio">
                                                    <input class="custom-control-input" type="radio" id="type_biometric" name="attendance_type" value="1" <?php echo e($attendanceType == 1 ? 'checked' : ''); ?>>
                                                    <label for="type_biometric" class="custom-control-label">Biometric</label>
                                                </div>
                                                <div class="custom-control custom-radio">
                                                    <input class="custom-control-input" type="radio" id="type_normal" name="attendance_type" value="2" <?php echo e($attendanceType == 2 ? 'checked' : ''); ?>>
                                                    <label for="type_normal" class="custom-control-label">Normal</label>
                                                </div>
                                            </div>
                                            <small class="text-muted d-block mt-2">Choose how attendance is captured for students and staff.</small>
                                        </div>

                                        <div class="attendance-card mb-3">
                                            <h5>Messaging Service</h5>
                                            <div class="attendance-inline">
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="msg_whatsapp" name="messaging_services[]" value="whatsapp" <?php echo e(in_array('whatsapp', $messagingServices, true) ? 'checked' : ''); ?>>
                                                    <label for="msg_whatsapp" class="custom-control-label">WhatsApp</label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="msg_firebase" name="messaging_services[]" value="firebase" <?php echo e(in_array('firebase', $messagingServices, true) ? 'checked' : ''); ?>>
                                                    <label for="msg_firebase" class="custom-control-label">Firebase</label>
                                                </div>
                                                <div class="custom-control custom-checkbox">
                                                    <input class="custom-control-input" type="checkbox" id="msg_sms" name="messaging_services[]" value="sms" <?php echo e(in_array('sms', $messagingServices, true) ? 'checked' : ''); ?>>
                                                    <label for="msg_sms" class="custom-control-label">SMS</label>
                                                </div>
                                            </div>
                                            <small class="text-muted d-block mt-2">Attendance messages will be queued only for selected services.</small>
                                        </div>

                                        <div class="attendance-card mb-3">
                                            <h5>Manual/Bulk Attendance Messaging</h5>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="manual_attendance_messaging_enabled" name="manual_attendance_messaging_enabled" value="1" <?php echo e($manualAttendanceMessagingEnabled === 1 ? 'checked' : ''); ?>>
                                                <label class="custom-control-label" for="manual_attendance_messaging_enabled">Enable messages on Manual/Bulk Attendance Save</label>
                                            </div>
                                            <small class="text-muted d-block mt-2">When enabled, manual/bulk attendance updates will queue and dispatch messages using selected messaging services.</small>
                                        </div>

                                        <div class="attendance-card mb-3">
                                            <h5>Auto Absent Mark</h5>
                                            <div class="custom-control custom-switch mb-2">
                                                <input type="checkbox" class="custom-control-input" id="auto_absent_mark_enabled" name="auto_absent_mark_enabled" value="1" <?php echo e($autoAbsentMarkEnabled === 1 ? 'checked' : ''); ?>>
                                                <label class="custom-control-label" for="auto_absent_mark_enabled">Enable Auto Absent Marking</label>
                                            </div>
                                            <div class="form-group mb-0">
                                                <label>Auto Absent Time</label>
                                                <input type="time" name="auto_absent_mark_time" class="form-control" value="<?php echo e($timeVal('auto_absent_mark_time')); ?>">
                                                <small class="text-muted d-block mt-2">Example: `11:00` (11 AM). Logic will be implemented later.</small>
                                            </div>
                                        </div>


                                        <div class="attendance-card mb-3">
                                            <h5>Back Date Attendance</h5>
                                            <div class="custom-control custom-switch">
                                                <input type="checkbox" class="custom-control-input" id="allow_back_date_attendance" name="allow_back_date_attendance" value="1" <?php echo e($allowBackDate === 1 ? 'checked' : ''); ?>>
                                                <label class="custom-control-label" for="allow_back_date_attendance">Allow Back Date Attendance</label>
                                            </div>
                                            <small class="text-muted d-block mt-2">Default is not allowed. Admin can still mark back date attendance.</small>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Late Grace (Minutes)</label>
                                                    <input type="number" name="late_grace_minutes" class="form-control" value="<?php echo e(old('late_grace_minutes', $setting->late_grace_minutes ?? 15)); ?>" min="0">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>QR Validity (Minutes)</label>
                                                    <input type="number" name="qr_validity_minutes" class="form-control" value="<?php echo e(old('qr_validity_minutes', $setting->qr_validity_minutes ?? 5)); ?>" min="0">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Early Out Grace (Minutes)</label>
                                                    <input type="number" name="early_out_grace_minutes" class="form-control" value="<?php echo e(old('early_out_grace_minutes', $setting->early_out_grace_minutes ?? 15)); ?>" min="0">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Half Day If Working Minutes Less Than</label>
                                                    <input type="number" name="half_day_min_minutes" class="form-control" value="<?php echo e(old('half_day_min_minutes', $setting->half_day_min_minutes ?? 240)); ?>" min="0">
                                                </div>
                                            </div>
                                        </div>

                                        <div class="attendance-card mb-3">
                                            <h5>Season Timings</h5>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Summer Start Time</label>
                                                        <input type="time" name="summer_start_time" class="form-control" value="<?php echo e($timeVal('summer_start_time')); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Summer End Time</label>
                                                        <input type="time" name="summer_end_time" class="form-control" value="<?php echo e($timeVal('summer_end_time')); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Winter Start Time</label>
                                                        <input type="time" name="winter_start_time" class="form-control" value="<?php echo e($timeVal('winter_start_time')); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Winter End Time</label>
                                                        <input type="time" name="winter_end_time" class="form-control" value="<?php echo e($timeVal('winter_end_time')); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="attendance-card mb-3">
                                            <h5>Summer Lunch Time</h5>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Summer Lunch From</label>
                                                        <input type="time" name="summer_lunch_from_time" class="form-control" value="<?php echo e($timeVal('summer_lunch_from_time')); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Summer Lunch To</label>
                                                        <input type="time" name="summer_lunch_to_time" class="form-control" value="<?php echo e($timeVal('summer_lunch_to_time')); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="attendance-card mb-3">
                                            <h5>Winter Lunch Time</h5>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Winter Lunch From</label>
                                                        <input type="time" name="winter_lunch_from_time" class="form-control" value="<?php echo e($timeVal('winter_lunch_from_time')); ?>">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label>Winter Lunch To</label>
                                                        <input type="time" name="winter_lunch_to_time" class="form-control" value="<?php echo e($timeVal('winter_lunch_to_time')); ?>">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label>Notes</label>
                                            <textarea name="notes" rows="3" class="form-control" placeholder="Any special instructions..."><?php echo e(old('notes', $setting->notes ?? '')); ?></textarea>
                                        </div>

                                        <button type="submit" class="btn btn-primary">Save Settings</button>
                                    </form>
                                </div>

                                <div class="col-lg-5 mt-4 mt-lg-0">
                                    <div class="attendance-card mb-3">
                                        <h5>How to Configure</h5>
                                        <div class="attendance-pill mb-3">
                                            <strong>Attendance Type</strong>
                                            <p>Biometric uses check-in/out time, Normal is status-only, and QR uses short token validity.</p>
                                        </div>
                                        <div class="attendance-pill mb-3">
                                            <strong>Late Grace</strong>
                                            <p>If check-in is later than shift start + grace minutes, status auto-marks as Late.</p>
                                        </div>
                                        <div class="attendance-pill mb-3">
                                            <strong>Early Out Grace</strong>
                                            <p>If check-out is earlier than shift end - grace minutes, status auto-marks as Half Day.</p>
                                        </div>
                                        <div class="attendance-pill">
                                            <strong>Half Day Minimum</strong>
                                            <p>If total working minutes are less than this value, status auto-marks as Half Day.</p>
                                        </div>
                                    </div>
                                    <div class="attendance-card">
                                        <h5>Tips</h5>
                                        <p class="text-muted mb-0">Set QR validity low for security. Keep grace minutes aligned with your shift policies.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/attendance/settings.blade.php ENDPATH**/ ?>