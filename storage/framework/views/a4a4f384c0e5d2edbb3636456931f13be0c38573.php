
<?php $__env->startSection('content'); ?>
<?php
$getSetting = Helper::getSetting();
$getUser = $data;
//dd($getUser);
$busAssign = Helper::busAssign();
?>
<style>
    .headers-line {
        margin: 0 0 14px;
        line-height: 27px;
        padding: 0;
        color: #ffbd2e;
        font-size: 18px;
        position: relative;
        overflow: hidden;
        text-align: left;
    }

    .headers-line:after {
        left: auto;
        width: 999em;
        margin: 0 0 0 12px;
    }

    .headers-line:before,
    .headers-line:after {
        content: " ";
        position: absolute;
        top: 50%;
        height: 2px;
        border-top: 2px solid #eee;
    }



    .icon-wrapper {
        display: inline-grid;
        /* Ensures transform works */
        background-color: #FFC107;
        padding: 8px;
        border-radius: 3px;
        transform: rotate(45deg)
    }

    .icon-wrapper .fa {
        display: inline-block;
        /* Ensures transform works */
        transform: rotate(-45deg)
    }

    .list-group-unbordered li {
        border: none;
        padding: 0;
        padding-bottom: 12px;
        font-size: 15px;
        background: none;

    }

    .list-group-unbordered li i {
        width: 15px;
        height: 15px;

    }

    .list-group-unbordered li a {
        color: black;
    }

    .tabData .headers-line {
        font-weight: bolder;
    }
</style>
<style>
    .profile-wrapper {
        display: flex;
        gap: 40px;
        background: #fff;
        padding: 30px;
        border-radius: 16px;
    }

    /* LEFT IMAGE */
    .profile-left {
        position: relative;
        min-width: 260px;
    }

    .profile-left img {
        width: 260px;
        height: 300px;
        object-fit: cover;
        border-radius: 16px;
    }

    .profile-check {
        position: absolute;
        bottom: 12px;
        right: 12px;
        background: #22c55e;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* RIGHT */
    .profile-right {
        flex: 1;
    }

    /* HEADER */
    .profile-header {
        display: flex;
        justify-content: space-between;
        align-items: start;
        gap: 20px;
    }

    .profile-header h1 {
        font-size: 40px;
        font-weight: 700;
        margin-bottom: 10px;
    }

    .profile-badges span {
        display: inline-block;
        padding: 6px 16px;
        border-radius: 999px;
        font-size: 14px;
        margin-right: 8px;
        background: #eef2ff;
        color: #2563eb;
    }

    /* BUTTONS */
    .profile-actions button {
        margin-left: 10px;
    }

    /* INFO GRID */
    .profile-info-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-top: 25px;
    }

    .info-box {
        display: flex;
        gap: 14px;
        padding: 18px;
        background: #f8fafc;
        border-radius: 14px;
        align-items: center;
    }

    .info-box i {
        font-size: 20px;
        color: #2563eb;
    }

    .info-box small {
        color: #6b7280;
        font-size: 13px;
    }

    .info-box strong {
        display: block;
        font-size: 15px;
    }

    /* RESPONSIVE */
    @media (max-width: 992px) {
        .profile-info-grid {
            grid-template-columns: repeat(2, 1fr);
        }
    }

    @media (max-width: 768px) {
        .profile-wrapper {
            flex-direction: column;
        }

        .profile-left {
            margin: auto;
        }

        .profile-header {
            flex-direction: column;
        }

        .profile-info-grid {
            grid-template-columns: 1fr;
        }
    }
</style>
<div class="content-wrapper">

    <section class="content pt-3">
        
        <div class="container-fluid py-4">
            <div class="profile-main-card">
        
                <div class="profile-grid">
        
                    <!-- LEFT IMAGE -->
                    <div class="profile-photo-box">
                        <img src="<?php echo e(env('IMAGE_SHOW_PATH') . '/profile/' . $getUser['image']); ?>"
                             onerror="this.src='<?php echo e(env('IMAGE_SHOW_PATH') . '/default/user_image.jpg'); ?>">
                        <span class="verified"><i class="fa fa-check"></i></span>
                    </div>
        
                    <!-- RIGHT CONTENT -->
                    <div class="profile-content">
        
                        <div class="profile-top">
                            <div>
                                <h2><?php echo e($getUser->first_name); ?></h2>
                                <div class="badges">
                                    <span class="badge blue">STUDENT</span>
                                    <span class="badge gray"><?php echo e($getUser['category']); ?></span>
                                </div>
                            </div>
        
                            <!--<div class="action-btns">-->
                            <!--    <button class="btn-outline">-->
                            <!--        <i class="fa fa-pencil"></i> Edit Profile-->
                            <!--    </button>-->
                            <!--    <button class="btn-primary">-->
                            <!--        <i class="fa fa-download"></i> Export PDF-->
                            <!--    </button>-->
                            <!--</div>-->
                        </div>
        
                        <!-- INFO GRID -->
                        <div class="info-grid">
        
                            <div class="info-card">
                                <i class="fa fa-calendar"></i>
                                <div>
                                    <small>Date of Birth</small>
                                    <strong><?php echo e($getUser['dob']); ?></strong>
                                </div>
                            </div>
        
                            <div class="info-card">
                                <i class="fa fa-phone"></i>
                                <div>
                                    <small>Contact</small>
                                    <strong>+<?php echo e($getUser['mobile']); ?></strong>
                                </div>
                            </div>
        
                            <div class="info-card">
                                <i class="fa fa-envelope"></i>
                                <div>
                                    <small>Email</small>
                                    <strong><?php echo e($getUser['email']); ?></strong>
                                </div>
                            </div>
        
                        </div>
        
                    </div>
                </div>
            </div>
        </div>
        <style>
            .profile-main-card {
                background: #fff;
                border-radius: 22px;
                padding: 25px;
                box-shadow: 0 20px 60px rgba(0,0,0,.08);
            }
            
            .profile-grid {
                display: flex;
                gap: 30px;
                align-items: flex-start;
            }
            
            /* LEFT PHOTO */
            .profile-photo-box {
                width: 200px;
                height: 250px;
                background: #facc15;
                border-radius: 18px;
                position: relative;
                flex-shrink: 0;
            }
            
            .profile-photo-box img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                border-radius: 18px;
            }
            
            .verified {
                position: absolute;
                bottom: -12px;
                right: -12px;
                background: #22c55e;
                width: 36px;
                height: 36px;
                border-radius: 50%;
                color: #fff;
                display: flex;
                align-items: center;
                justify-content: center;
                border: 4px solid #fff;
            }
            
            /* RIGHT CONTENT */
            .profile-content {
                flex: 1;
            }
            
            .profile-top {
                display: flex;
                justify-content: space-between;
                align-items: center;
                flex-wrap: wrap;
            }
            
            .profile-top h2 {
                font-weight: 700;
                margin-bottom: 8px;
            }
            
            /* BADGES */
            .badges {
                display: flex;
                gap: 10px;
            }
            
            .badge {
                padding: 6px 14px;
                border-radius: 20px;
                font-size: 13px;
                font-weight: 600;
            }
            
            .badge.blue {
                background: #e0e7ff;
                color: #2563eb;
            }
            
            .badge.gray {
                background: #f1f5f9;
                color: #475569;
            }
            
            /* BUTTONS */
            .action-btns {
                display: flex;
                gap: 12px;
            }
            
            .btn-outline {
                border: 1px solid #cbd5f5;
                background: #fff;
                padding: 10px 16px;
                border-radius: 12px;
            }
            
            .btn-primary {
                background: #2563eb;
                color: #fff;
                padding: 10px 18px;
                border-radius: 12px;
                border: none;
            }
            
            /* INFO GRID */
            .info-grid {
                margin-top: 30px;
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
                gap: 20px;
            }
            
            /* CARD */
            .info-card {
                background: #ffffff;
                padding: 18px 20px;
                border-radius: 18px;
                display: flex;
                align-items: center;
                justify-content: space-between;
                border: 1px solid #eef2f7;
                box-shadow: 0 8px 20px rgba(0,0,0,.05);
            }
            
            /* TEXT SIDE */
            .info-card > div {
                display: flex;
                flex-direction: column;
                gap: 4px;
            }
            
            .info-card small {
                font-size: 12px;
                color: #94a3b8;
                font-weight: 600;
                letter-spacing: .4px;
                text-transform: uppercase;
            }
            
            .info-card strong {
                font-size: 15px;
                font-weight: 700;
                color: #0f172a;
                line-height: 1.4;
            }
            
            /* ICON RIGHT */
            .info-card i {
                font-size: 20px;
                width: 44px;
                height: 44px;
                border-radius: 14px;
                display: flex;
                align-items: center;
                justify-content: center;
            }
            
            /* ICON COLORS (auto variety like image) */
            .info-card:nth-child(1) i {
                background: #e0edff;
                color: #2563eb;
            }
            
            .info-card:nth-child(2) i {
                background: #fff4e6;
                color: #f97316;
            }
            
            .info-card:nth-child(3) i {
                background: #ede9fe;
                color: #7c3aed;
            }
            
            .info-card:nth-child(4) i {
                background: #ecfeff;
                color: #06b6d4;
            }
            
            .info-card:nth-child(5) i {
                background: #f0fdf4;
                color: #16a34a;
            }
            
            /* MOBILE */
            @media (max-width: 768px) {
                .info-card {
                    padding: 16px;
                }
            }

            
            /* MOBILE RESPONSIVE */
            @media (max-width: 768px) {
            
                .profile-grid {
                    flex-direction: column;
                    align-items: center;
                    text-align: center;
                }
            
                .profile-photo-box {
                    width: 160px;
                    height: 200px;
                }
            
                .profile-top {
                    flex-direction: column;
                    gap: 15px;
                }
            
                .action-btns {
                    flex-direction: column;
                    width: 100%;
                }
            
                .action-btns button {
                    width: 100%;
                }
            }

        </style>


        <div class="row">
            <div class="col-12 col-md-12 mt-3">
                <div class="card card-outline card-orange">
                    <section class="content">
                        <div class="container-fluid">
                            <form action="<?php echo e(url('studentDetail')); ?>" method="post">
                                <?php echo csrf_field(); ?>
                                <div class="row pt-2">
                                    <?php if(!empty($getStudentSession)): ?>
                                    <?php $__currentLoopData = $getStudentSession; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $stuSession): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-2 col-4">
                                        <button type="submit" name="session_id" value="<?php echo e($stuSession->id ?? ''); ?>"
                                            class="btn <?php if($stuSession->id == Session::get('session_id')): ?> btn-primary <?php else: ?> btn-light <?php endif; ?>"><?php echo e($stuSession->from_year ?? ''); ?>

                                            - <?php echo e($stuSession->to_year ?? ''); ?></button>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    <?php endif; ?>
                                </div>
                            </form>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="listing_tab tabs-custom">
                                        <ul class="nav-tabs">
                                            <li class="get_data active" data-title="profile_details"
                                                data-target="profile_details">
                                                <a href="#profile_details" class="get_data1"><i
                                                        class="fa fa-user-circle-o p-1" aria-hidden="true"></i>Profile
                                                    Details</a>
                                            </li>
                                            <li class="get_data" data-title="promotion_history"
                                                data-target="promotion_history">
                                                <a href="#promotion_history" class="get_data1"><i
                                                        class="fa fa-cart-arrow-down" aria-hidden="true"></i> Promotion
                                                    History</a>
                                            </li>
                                            <!-- <li class="get_data" data-title="exam_result" data-target="exam_result">
                                                    <a href="#exam_result" class="get_data1"><i class="fa fa-history p-1"
                                                            aria-hidden="true"></i>Exam Result</a>
                                                </li>-->
                                            <li class="get_data getFees" data-id="<?php echo e($getUser->id ?? ''); ?>"
                                                data-title="fees" data-target="fees">
                                                <a href="#fees" class="get_data1"><i class="fa fa-money"
                                                        aria-hidden="true"></i>
                                                    Fees</a>
                                            </li>


                                            <li class="get_data" data-title="parent_information"
                                                data-target="parent_information">
                                                <a href="#parent_information" class="get_data1"><i
                                                        class="fa fa-users p-1" aria-hidden="true"></i>Parent
                                                    Information</a>
                                            </li>
                                            <li class="get_data" data-title="sibling_information"
                                                data-target="sibling_information">
                                                <a href="#sibling_information" class="get_data1"><i
                                                        class="fa fa-sliders" aria-hidden="true"></i>
                                                    Sibling Information</a>
                                            </li>
                                            <li class="get_data" data-title="documents" data-target="documents">
                                                <a href="#documents" class="get_data1"><i class="fa fa-file-text p-1"
                                                        aria-hidden="true"></i>Documents</a>
                                            </li>
                                            
                                        </ul>
                                    </div>
                                    <style>
                                        /* ===== HORIZONTAL SCROLL TABS (ALL DEVICES) ===== */
                                        
                                        .tabs-custom {
                                            overflow-x: hidden; /* body scrollbar prevent */
                                        }
                                        
                                        .tabs-custom .nav-tabs {
                                            display: flex;
                                            flex-wrap: nowrap;          /* 🔥 no wrapping anywhere */
                                            gap: 12px;
                                            overflow-x: auto;           /* horizontal scroll */
                                            white-space: nowrap;
                                            border-bottom: none;
                                            padding-bottom: 6px;
                                        
                                            scrollbar-width: none;      /* Firefox */
                                        }
                                        
                                        .tabs-custom .nav-tabs::-webkit-scrollbar {
                                            display: none;              /* Chrome, Safari */
                                        }
                                        
                                        .tabs-custom .nav-tabs > li {
                                            flex: 0 0 auto;             /* prevent shrinking */
                                            list-style: none;
                                        }
                                        
                                        /* Tab link */
                                        .tabs-custom .nav-tabs > li a {
                                            display: flex;
                                            align-items: center;
                                            gap: 6px;
                                            padding: 8px 14px;
                                            font-size: 13px;
                                            border-radius: 10px;
                                            background: transparent;
                                            transition: all .25s ease;
                                            border: none;
                                        }
                                        
                                        /* Hover */
                                        .tabs-custom .nav-tabs > li a:hover {
                                            background: #f3f4f6;
                                        }
                                        
                                        /* Active tab */
                                        .tabs-custom .nav-tabs > li.active a {
                                            background: #fff7ed;
                                            color: #ea580c;
                                            font-weight: 600;
                                        }
                                        
                                        /* Desktop underline */
                                        @media (min-width: 992px) {
                                            .tabs-custom .nav-tabs > li.active {
                                                border-bottom: 2px solid #ffbd2e;
                                            }
                                        
                                            .tabs-custom .nav-tabs > li a {
                                                border-radius: 0;
                                                padding: 10px 18px;
                                            }
                                        }
                                        
                                        /* Mobile icon size tweak only */
                                        @media (max-width: 575px) {
                                            .tabs-custom .nav-tabs > li a i {
                                                font-size: 16px;
                                            }
                                        }

                                    </style>
                                    <div class="tabData w-100" id="profile_details"
                                        style="display:block;font-size: 14px;">
                                        <div class="profile-section">
                                            <h5 class="section-title">
                                                <i class="fa fa-graduation-cap"></i> Academic Details
                                            </h5>

                                            <div class="row g-3">
                                                <div class="col-md-3 col-sm-6 mb-3">
                                                    <div class="info-box-sm">
                                                        <span>Branch</span>
                                                        <strong><?php echo e($getSetting->name ?? ''); ?></strong>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-sm-6 mb-3">
                                                    <div class="info-box-sm">
                                                        <span>Academic Year</span>
                                                        <strong><?php echo e($getUser->from_year ?? ''); ?> - <?php echo e($getUser->to_year
                                                            ?? ''); ?></strong>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-sm-6 mb-3">
                                                    <div class="info-box-sm">
                                                        <span>Register No</span>
                                                        <strong><?php echo e($getUser->admissionNo ?? ''); ?></strong>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-sm-6 mb-3">
                                                    <div class="info-box-sm">
                                                        <span>Roll</span>
                                                        <strong>1</strong>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-sm-6 mb-3">
                                                    <div class="info-box-sm">
                                                        <span>Admission Date</span>
                                                        <strong><?php echo e($getUser->admission_date ?? ''); ?></strong>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-sm-6 mb-3">
                                                    <div class="info-box-sm">
                                                        <span>Class</span>
                                                        <strong><?php echo e($getUser->class_name ?? ''); ?></strong>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-sm-6 mb-3">
                                                    <div class="info-box-sm">
                                                        <span>Section</span>
                                                        <strong>A</strong>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-sm-6 mb-3">
                                                    <div class="info-box-sm">
                                                        <span>Category</span>
                                                        <strong><?php echo e($getUser->category ?? ''); ?></strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="profile-section mt-4">
                                            <h5 class="section-title">
                                                <i class="fa fa-user-circle-o"></i> Student Details
                                            </h5>

                                            <div class="row g-3">
                                                <div class="col-md-3 col-sm-6 mb-3">
                                                    <div class="info-box-sm">
                                                        <span>First Name</span>
                                                        <strong><?php echo e($getUser->first_name ?? ''); ?></strong>
                                                    </div>
                                                </div>

                                                

                                                <div class="col-md-3 col-sm-6 mb-3">
                                                    <div class="info-box-sm">
                                                        <span>Gender</span>
                                                        <strong><?php echo e($getUser->genderName ?? ''); ?></strong>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-sm-6 mb-3">
                                                    <div class="info-box-sm">
                                                        <span>Blood Group</span>
                                                        <strong><?php echo e($getUser->blood_group ?? ''); ?></strong>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-sm-6 mb-3">
                                                    <div class="info-box-sm">
                                                        <span>Date of Birth</span>
                                                        <strong><?php echo e($getUser->dob ?? ''); ?></strong>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-sm-6 mb-3">
                                                    <div class="info-box-sm">
                                                        <span>Mother Tongue</span>
                                                        <strong><?php echo e($getUser->mother_tongue ?? ''); ?></strong>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-sm-6 mb-3">
                                                    <div class="info-box-sm">
                                                        <span>Religion</span>
                                                        <strong><?php echo e($getUser->religion ?? ''); ?></strong>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-sm-6 mb-3">
                                                    <div class="info-box-sm">
                                                        <span>Caste</span>
                                                        <strong><?php echo e($getUser->caste_category ?? ''); ?></strong>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-sm-6 mb-3">
                                                    <div class="info-box-sm">
                                                        <span>Mobile</span>
                                                        <strong><?php echo e($getUser->mobile ?? ''); ?></strong>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-sm-6 mb-3">
                                                    <div class="info-box-sm">
                                                        <span>Email</span>
                                                        <strong><?php echo e($getUser->email ?? ''); ?></strong>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-sm-6 mb-3">
                                                    <div class="info-box-sm">
                                                        <span>City</span>
                                                        <strong><?php echo e($getUser->city_name ?? ''); ?></strong>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-sm-6 mb-3">
                                                    <div class="info-box-sm">
                                                        <span>State</span>
                                                        <strong><?php echo e($getUser->state_name ?? ''); ?></strong>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <div class="info-box-sm">
                                                        <span>Present Address</span>
                                                        <strong><?php echo e($getUser->address ?? ''); ?></strong>
                                                    </div>
                                                </div>

                                                <div class="col-md-6 mb-3">
                                                    <div class="info-box-sm">
                                                        <span>Permanent Address</span>
                                                        <strong><?php echo e($getUser->address ?? ''); ?></strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <style>
                                        /* =========================
                                           Profile Section Wrapper
                                           ========================= */
                                        .profile-section {
                                            background: #ffffff;
                                            padding: 26px;
                                            border-radius: 20px;
                                            margin-bottom: 32px;
                                            border: 1px solid #eaeef5;
                                        }
                                        
                                        /* Section Title */
                                        .section-title {
                                            font-size: 16px;
                                            font-weight: 700;
                                            color: #0f172a;
                                            margin-bottom: 24px;
                                            display: flex;
                                            align-items: center;
                                            gap: 12px;
                                        }
                                        
                                        .section-title i {
                                            width: 36px;
                                            height: 36px;
                                            border-radius: 12px;
                                            background: #eef2ff;
                                            color: #2563eb;
                                            display: flex;
                                            align-items: center;
                                            justify-content: center;
                                            font-size: 14px;
                                        }
                                        
                                        /* =========================
                                           Info Card (Icon Style)
                                           ========================= */
                                        .info-box-sm {
                                            background: #ffffff;
                                            border-radius: 18px;
                                            padding: 18px;
                                            display: flex;
                                            align-items: center;
                                            gap: 16px;
                                            height: 100%;
                                            border: 1px solid #eef2f7;
                                            transition: all .25s ease;
                                        }
                                        
                                        /* Icon Circle */
                                        /*.info-box-sm::before {*/
                                        /*    content: "\f02d";*/
                                        /*    font-family: FontAwesome;*/
                                        /*    width: 44px;*/
                                        /*    height: 44px;*/
                                        /*    border-radius: 14px;*/
                                        /*    background: #f1f5ff;*/
                                        /*    color: #2563eb;*/
                                        /*    display: flex;*/
                                        /*    align-items: center;*/
                                        /*    justify-content: center;*/
                                        /*    font-size: 16px;*/
                                        /*    flex-shrink: 0;*/
                                        /*}*/
                                        
                                        /* Text */
                                        .info-box-sm span {
                                            font-size: 11px;
                                            font-weight: 600;
                                            color: #94a3b8;
                                            letter-spacing: .4px;
                                            text-transform: uppercase;
                                            margin-bottom: 2px;
                                            display: block;
                                        }
                                        
                                        .info-box-sm strong {
                                            font-size: 14px;
                                            font-weight: 700;
                                            color: #0f172a;
                                            line-height: 1.4;
                                        }
                                        
                                        /* Hover */
                                        .info-box-sm:hover {
                                            background: #f8fafc;
                                            transform: translateY(-3px);
                                            box-shadow: 0 10px 22px rgba(0,0,0,.06);
                                        }
                                        
                                        /* Student Section Color */
                                        .profile-section:last-of-type .info-box-sm::before {
                                            background: #ecfdf5;
                                            color: #16a34a;
                                        }
                                        
                                        /* Address Wide Cards */
                                        .col-md-6 .info-box-sm {
                                            align-items: flex-start;
                                        }
                                        
                                        .col-md-6 .info-box-sm::before {
                                            margin-top: 2px;
                                        }
                                        
                                        /* =========================
                                           Responsive
                                           ========================= */
                                        @media (max-width: 768px) {
                                            .profile-section {
                                                padding: 18px;
                                            }
                                        
                                            .info-box-sm {
                                                padding: 16px;
                                                gap: 14px;
                                            }
                                        }


                                    </style>





                                    <div id="promotion_history" class="tabData w-100"
                                        style="display:none;font-size:14px;">

                                        <div class="profile-section">
                                            <!--<h5 class="section-title">-->
                                            <!--    <i class="fa fa-cart-arrow-down"></i> Promotion History-->
                                            <!--</h5>-->

                                            <div class="table-responsive">
                                                <table class="table promotion-table">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Student Name</th>
                                                            <th>Class / Section</th>
                                                            <th>Session</th>
                                                            <th>Father Name</th>
                                                            <th>Mobile</th>
                                                            <th>Admission Date</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php $__empty_1 = true; $__currentLoopData = $promotion_history; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $student): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                        <tr>
                                                            <td>
                                                                <span class="count-badge"><?php echo e($key + 1); ?></span>
                                                            </td>
                                                            <td>
                                                                <strong><?php echo e($student->first_name); ?> <?php echo e($student->last_name); ?></strong>
                                                            </td>
                                                            <td>
                                                                <span class="pill">
                                                                    <?php echo e($student->class_name); ?>

                                                                </span>
                                                            </td>
                                                            <td>
                                                                <?php echo e($student->from_year); ?> - <?php echo e($student->to_year); ?>

                                                            </td>
                                                            <td><?php echo e($student->father_name); ?></td>
                                                            <td><?php echo e($student->mobile); ?></td>
                                                            <td>
                                                                <?php echo e(\Carbon\Carbon::parse($student->admission_date)->format('d
                                                                M, Y')); ?>

                                                            </td>
                                                        </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                        <tr>
                                                            <td colspan="7" class="text-center text-muted">
                                                                No promotion history found
                                                            </td>
                                                        </tr>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </div>
                                    <style>
                                        /* =========================
                                           Promotion History Table
                                           ========================= */
                                        
                                        .promotion-table {
                                            width: 100%;
                                            border-collapse: separate;
                                            border-spacing: 0 14px;
                                            min-width: 950px; /* mobile scroll */
                                        }
                                        
                                        /* Table Head */
                                        .promotion-table thead th {
                                            font-size: 12px;
                                            font-weight: 700;
                                            color: #64748b;
                                            text-transform: uppercase;
                                            letter-spacing: .4px;
                                            border: none;
                                            padding: 12px 14px;
                                            background: #f8fafc;
                                        }
                                        
                                        /* Table Row */
                                        .promotion-table tbody tr {
                                            background: #ffffff;
                                            border-radius: 16px;
                                            box-shadow: 0 8px 22px rgba(0,0,0,.04);
                                            transition: all .25s ease;
                                        }
                                        
                                        .promotion-table tbody tr:hover {
                                            transform: translateY(-2px);
                                            box-shadow: 0 14px 30px rgba(0,0,0,.06);
                                        }
                                        
                                        /* Table Cells */
                                        .promotion-table tbody td {
                                            border: none;
                                            padding: 16px 14px;
                                            font-size: 14px;
                                            color: #0f172a;
                                            vertical-align: middle;
                                            white-space: nowrap;
                                        }
                                        
                                        .promotion-table tbody tr td:first-child {
                                            border-radius: 16px 0 0 16px;
                                        }
                                        
                                        .promotion-table tbody tr td:last-child {
                                            border-radius: 0 16px 16px 0;
                                        }
                                        
                                        /* =========================
                                           Elements
                                           ========================= */
                                        
                                        /* Count Badge */
                                        .count-badge {
                                            display: inline-flex;
                                            align-items: center;
                                            justify-content: center;
                                            width: 28px;
                                            height: 28px;
                                            border-radius: 50%;
                                            background: #2563eb;
                                            color: #fff;
                                            font-size: 13px;
                                            font-weight: 700;
                                        }
                                        
                                        /* Class Pill */
                                        .pill {
                                            background: #eef2ff;
                                            color: #2563eb;
                                            padding: 6px 14px;
                                            font-size: 13px;
                                            font-weight: 600;
                                            border-radius: 999px;
                                            display: inline-block;
                                        }
                                        
                                        /* =========================
                                           Mobile & Tablet (TABLE ONLY)
                                           ========================= */
                                        
                                        @media (max-width: 991px) {
                                        
                                            .table-responsive {
                                                overflow-x: auto;
                                                -webkit-overflow-scrolling: touch;
                                                border-radius: 16px;
                                            }
                                        
                                            .promotion-table {
                                                min-width: 950px; /* force horizontal scroll */
                                            }
                                        
                                        }

                                    </style>

                                    <div class="container p-3 tabData w-100" id="fees"
                                        style="display:none;font-size:14px;">

                                        <?php if(!empty($getFees)): ?>

                                        <?php
                                        $i = 1;
                                        $grand_total = 0;
                                        $Paids = 0;
                                        $Discount = 0;
                                        $Fine = 0;
                                        $balances = 0;
                                        ?>

                                        
                                        <?php $__currentLoopData = $getFees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <?php
                                        $pad = App\Models\FeesDetail::where('fees_type',0)
                                        ->where('status',0)
                                        ->where('admission_id',$getUser->id)
                                        ->where('fees_group_id',$item->fees_group_id)
                                        ->sum('total_amount');

                                        $discount = App\Models\FeesDetail::where('fees_type',0)
                                        ->where('admission_id',$getUser->id)
                                        ->where('fees_group_id',$item->fees_group_id)
                                        ->sum('discount');

                                        $balance = $item->fees_group_amount - $pad;

                                        $fine_amt = 0;
                                        if(!empty($item->installment_due_date) && $item->installment_due_date >
                                        date('Y-m-d')){
                                        $fine_amt = ($balance / 100) * $item->installment_fine;
                                        }

                                        $grand_total += $item->fees_group_amount;
                                        $Paids += $pad;
                                        $Discount += $discount;
                                        $Fine += $fine_amt;
                                        $balances += $balance;
                                        ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        
                                        <div class="profile-section mb-4">
                                            <h5 class="section-title">
                                                <i class="fa fa-money"></i> Fees Summary
                                            </h5>

                                            <div class="row g-3">
                                                <div class="col-md-3 col-sm-6">
                                                    <div class="fees-card total">
                                                        <span>Grand Total</span>
                                                        <strong><?php echo e($grand_total); ?></strong>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-sm-6">
                                                    <div class="fees-card paid">
                                                        <span>Paid</span>
                                                        <strong><?php echo e($Paids); ?></strong>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-sm-6">
                                                    <div class="fees-card discount">
                                                        <span>Discount</span>
                                                        <strong><?php echo e($Discount); ?></strong>
                                                    </div>
                                                </div>

                                                <div class="col-md-3 col-sm-6">
                                                    <div class="fees-card balance">
                                                        <span>Balance</span>
                                                        <strong><?php echo e($balances); ?></strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        
                                        <div class="profile-section">
                                            <h5 class="section-title">
                                                <i class="fa fa-list"></i> Fees Details
                                            </h5>

                                            <div class="table-responsive">
                                                <table class="table fees-table">
                                                    <thead>
                                                        <tr>
                                                            <th>#</th>
                                                            <th>Fees Type</th>
                                                            <th>Due Date</th>
                                                            <th>Status</th>
                                                            <th>Amount</th>
                                                            <th>Discount</th>
                                                            <th>Fine</th>
                                                            <th>Paid</th>
                                                            <th class="text-end">Balance</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        <?php $__currentLoopData = $getFees; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                        <?php
                                                        $pad = App\Models\FeesDetail::where('fees_type',0)
                                                        ->where('status',0)
                                                        ->where('admission_id',$getUser->id)
                                                        ->where('fees_group_id',$item->fees_group_id)
                                                        ->sum('total_amount');

                                                        $discount = App\Models\FeesDetail::where('fees_type',0)
                                                        ->where('admission_id',$getUser->id)
                                                        ->where('fees_group_id',$item->fees_group_id)
                                                        ->sum('discount');

                                                        $balance = $item->fees_group_amount - $pad;

                                                        $fine_amt = 0;
                                                        if(!empty($item->installment_due_date) &&
                                                        $item->installment_due_date > date('Y-m-d')){
                                                        $fine_amt = ($balance / 100) * $item->installment_fine;
                                                        }
                                                        ?>

                                                        <tr>
                                                            <td><span class="count-badge"><?php echo e($i++); ?></span></td>
                                                            <td><strong><?php echo e($item->group_name); ?></strong></td>
                                                            <td>
                                                                <?php echo e($item->installment_due_date
                                                                ? date('d M, Y', strtotime($item->installment_due_date))
                                                                : '—'); ?>

                                                            </td>
                                                            <td>
                                                                <?php if($item->fees_group_amount > $pad): ?>
                                                                <span class="status unpaid">Unpaid</span>
                                                                <?php else: ?>
                                                                <span class="status paid">Paid</span>
                                                                <?php endif; ?>
                                                            </td>
                                                            <td><?php echo e($item->fees_group_amount); ?></td>
                                                            <td><?php echo e($discount); ?></td>
                                                            <td><?php echo e($fine_amt); ?></td>
                                                            <td><?php echo e($pad); ?></td>
                                                            <td class="text-end fw-bold"><?php echo e($balance); ?></td>
                                                        </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        <?php else: ?>
                                        <div class="text-center text-muted p-4">
                                            <b>!! NO DATA FOUND !!</b>
                                        </div>
                                        <?php endif; ?>

                                    </div>

                                    <style>
                                        /* =========================
                                           Fees Table (Clean Table)
                                           ========================= */
                                        
                                        .fees-table {
                                            width: 100%;
                                            border-collapse: separate;
                                            border-spacing: 0 12px;
                                            min-width: 900px; /* mobile scroll ke liye */
                                        }
                                        
                                        .fees-table thead th {
                                            border: none;
                                            font-size: 12px;
                                            font-weight: 700;
                                            color: #64748b;
                                            text-transform: uppercase;
                                            letter-spacing: .4px;
                                            padding: 12px 14px;
                                            background: #f8fafc;
                                        }
                                        
                                        .fees-table tbody tr {
                                            background: #ffffff;
                                            border-radius: 14px;
                                            box-shadow: 0 6px 18px rgba(0,0,0,.04);
                                            transition: all .25s ease;
                                        }
                                        
                                        .fees-table tbody tr:hover {
                                            transform: translateY(-2px);
                                            box-shadow: 0 10px 26px rgba(0,0,0,.06);
                                        }
                                        
                                        .fees-table tbody td {
                                            border: none;
                                            padding: 14px;
                                            font-size: 14px;
                                            color: #0f172a;
                                            vertical-align: middle;
                                            white-space: nowrap;
                                        }
                                        
                                        .fees-table tbody tr td:first-child {
                                            border-radius: 14px 0 0 14px;
                                        }
                                        
                                        .fees-table tbody tr td:last-child {
                                            border-radius: 0 14px 14px 0;
                                        }
                                        
                                        /* Amount emphasis */
                                        .fees-table td.fw-bold {
                                            font-weight: 700;
                                            color: #0f172a;
                                        }
                                        
                                        /* =========================
                                           Status Pills
                                           ========================= */
                                        
                                        .status {
                                            padding: 6px 14px;
                                            border-radius: 999px;
                                            font-size: 12px;
                                            font-weight: 700;
                                            display: inline-block;
                                        }
                                        
                                        .status.unpaid {
                                            background: #fee2e2;
                                            color: #dc2626;
                                        }
                                        
                                        .status.paid {
                                            background: #dcfce7;
                                            color: #16a34a;
                                        }
                                        
                                        /* =========================
                                           Mobile & Tablet Handling
                                           ========================= */
                                        
                                        @media (max-width: 991px) {
                                        
                                            .table-responsive {
                                                overflow-x: auto;
                                                -webkit-overflow-scrolling: touch;
                                                border-radius: 14px;
                                            }
                                        
                                            .fees-table {
                                                min-width: 900px; /* horizontal scroll */
                                            }
                                        
                                        }

                                    </style>


                                    <div class="container p-3 tabData w-100" id="parent_information"
                                        style="display:none;font-size:14px;">

                                        
                                        <div class="profile-section mb-4">
                                            <!--<h5 class="section-title">-->
                                            <!--    <i class="fa fa-users"></i> Parent / Guardian Summary-->
                                            <!--</h5>-->

                                            <div class="row g-3">
                                                <div class="col-md-4 col-sm-6">
                                                    <div class="fees-card total">
                                                        <span>Guardian Name</span>
                                                        <strong><?php echo e($getUser->guardian_name ?? '—'); ?></strong>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-sm-6">
                                                    <div class="fees-card paid">
                                                        <span>Relation</span>
                                                        <strong><?php echo e($getUser->relation_student ?? '—'); ?></strong>
                                                    </div>
                                                </div>

                                                <div class="col-md-4 col-sm-6">
                                                    <div class="fees-card discount">
                                                        <span>Mobile No</span>
                                                        <strong><?php echo e($getUser->father_mobile ?? '—'); ?></strong>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        
                                        <div class="profile-section mb-4">
                                            <h5 class="section-title">
                                                <i class="fa fa-id-card"></i> Parent Details
                                            </h5>

                                            <div class="table-responsive">
                                                <table class="table fees-table">
                                                    <tbody>
                                                        <tr>
                                                            <td><strong>Father Name</strong></td>
                                                            <td><?php echo e($getUser->father_name ?? '—'); ?></td>

                                                            <td><strong>Mother Name</strong></td>
                                                            <td><?php echo e($getUser->mother_name ?? '—'); ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td><strong>Occupation</strong></td>
                                                            <td><?php echo e($getUser->father_occupation ?? '—'); ?></td>

                                                            <td><strong>Education</strong></td>
                                                            <td><?php echo e($getUser->father_education ?? '—'); ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td><strong>Annual Income</strong></td>
                                                            <td><?php echo e($getUser->family_annual_income ?? '—'); ?></td>

                                                            <td><strong>Email</strong></td>
                                                            <td><?php echo e($getUser->father_gmail ?? '—'); ?></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                        
                                        <div class="profile-section">
                                            <h5 class="section-title">
                                                <i class="fa fa-map-marker"></i> Address & Photo
                                            </h5>

                                            <div class="table-responsive">
                                                <table class="table fees-table">
                                                    <tbody>
                                                        <tr>
                                                            <td><strong>City</strong></td>
                                                            <td><?php echo e($getUser->city_name ?? '—'); ?></td>

                                                            <td><strong>State</strong></td>
                                                            <td><?php echo e($getUser->state_name ?? '—'); ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td><strong>Address</strong></td>
                                                            <td colspan="3"><?php echo e($getUser->address ?? '—'); ?></td>
                                                        </tr>

                                                        <tr>
                                                            <td><strong>Guardian Photo</strong></td>
                                                            <td colspan="3">
                                                                <img src="<?php echo e(env('IMAGE_SHOW_PATH') . '/father_image/' . $getUser['father_img']); ?>"
                                                                    onerror="this.src='<?php echo e(env('IMAGE_SHOW_PATH') . '/default/user_image.jpg'); ?>'"
                                                                    style="width:70px;height:70px;border-radius:12px;">
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>

                                    </div>

                                    <style>
                                        .sky_tr th {
                                            background: #f0f6ff;
                                            font-weight: 600;
                                        }

                                        .table th {
                                            white-space: nowrap;
                                        }
                                    </style>





                                    <div id="sibling_information" class="tabData w-100"
                                        style="display:none;font-size:14px;">

                                        <div class="profile-section">

                                            <div class="table-responsive">
                                                <table class="table promotion-table">
                                                    <thead>
                                                        <tr>
                                                            <th>Photo</th>
                                                            <th>Name</th>
                                                            <th>Register No</th>
                                                            <th>Gender</th>
                                                            <th>Class</th>
                                                            <th>Section</th>
                                                            <th>Roll</th>
                                                            <th>Mobile No</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>

                                                        <?php $__empty_1 = true; $__currentLoopData = $siblings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sibling): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                        <tr>


                                                            <td>
                                                                <div style="display:flex;align-items:center;gap:10px;">
                                                                    <img src="<?php echo e(env('IMAGE_SHOW_PATH') . '/profile/' . $sibling['image']); ?>"
                                                                        onerror="this.src='<?php echo e(env('IMAGE_SHOW_PATH') . '/default/user_image.jpg'); ?>'"
                                                                        style="width:42px;height:42px;border-radius:10px;object-fit:cover;">

                                                                </div>
                                                            </td>

                                                            <td><?php echo e($sibling->first_name ?? '—'); ?></td>

                                                            <td><?php echo e($sibling->admissionNo ?? '—'); ?></td>

                                                            <td><?php echo e($sibling->genderName ?? '—'); ?></td>

                                                            <td>

                                                                <?php echo e($sibling->class_name); ?>


                                                            </td>

                                                            <td><?php echo e($sibling->section ?? '—'); ?></td>

                                                            <td><?php echo e($sibling->roll_no ?? '—'); ?></td>

                                                            <td><?php echo e($sibling->mobile ?? '—'); ?></td>
                                                        </tr>
                                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                        <tr>
                                                            <td colspan="8" class="text-center text-muted">
                                                                No sibling information found
                                                            </td>
                                                        </tr>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>

                                        </div>
                                    </div>

                                    <div class="container mt-4 tabData w-100" id="documents"
                                        style="display:none;font-size: 14px;">
                                        <div style="text-align: right;">
                                            <a href="javascript:void(0);" onclick="openModal()"
                                                class="btn btn-circle btn-default mb-sm">
                                                <i class="fa fa-plus-circle" aria-hidden="true"></i> Add Document
                                            </a>
                                        </div>

                                        <!-- Modal Structure -->
                                        <div id="addStaffDocuments" class="modal"
                                            style="display: none; width: 50%;left:25%;">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h4 class="panel-title"><i class="fa fa-plus-circle"
                                                            aria-hidden="true"></i>
                                                        Add Document</h4>
                                                    <span class="close" onclick="closeModal()">&times;</span>
                                                </div>
                                                <form action="<?php echo e(url('document_upload')); ?>/<?php echo e($getUser['id']); ?>"
                                                    class="form-horizontal frm-submit-data"
                                                    enctype="multipart/form-data" method="post" accept-charset="utf-8">
                                                    <?php echo csrf_field(); ?>
                                                    <input type="hidden" name="school_csrf_name"
                                                        value="69f251fdf3fe85cf68c2e426bb6d6c37">
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Title </label>
                                                            <input type="text" class="form-control" name="title"
                                                                id="title">
                                                        </div>

                                                        <div class="form-group">
                                                            <label>Remarks</label>
                                                            <textarea class="form-control" rows="2"
                                                                name="remark"></textarea>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Document File</label>
                                                            <input type="file" name="file" class="form-control"
                                                                id="file" onchange="previewFile()">
                                                            <div id="filePreview" style="margin-top: 10px;"></div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="submit" id="" class="btn btn-primary">
                                                            <i class="fa fa-plus-circle" aria-hidden="true"></i> Save
                                                        </button>
                                                        <button type="button" class="btn btn-secondary"
                                                            onclick="closeModal()">Cancel</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>




                                        <table class="table promotion-table documents-table">
                                            <thead>
                                                <tr>
                                                    <th>Sl</th>
                                                    <th>Title</th>
                                                    <th>File</th>
                                                    <th>Remarks</th>
                                                    <th>Created At</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                                <?php $__empty_1 = true; $__currentLoopData = $getDocuments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $document): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                                <tr>
                                                    <td><?php echo e($key + 1); ?></td>

                                                    <td><?php echo e($document->title); ?></td>

                                                    <td>
                                                        <div style="display:flex;align-items:center;gap:10px;">
                                                            <img class="profile-user-img img-fluid img_frame"
                                                                src="<?php echo e(env('IMAGE_SHOW_PATH') . '/student_document/' . $document['file']); ?>"
                                                                style="width: 50px;height: 50px;"
                                                                onerror="this.src='<?php echo e(env('IMAGE_SHOW_PATH') . '/default/user_image.jpg'); ?>'">

                                                        </div>
                                                    </td>

                                                    <td><?php echo e($document->remark ?? 'N/A'); ?></td>

                                                    <td><?php echo e(\Carbon\Carbon::parse($document->created_at)->format('d.M.Y')); ?>

                                                    </td>

                                                    <td>
                                                        <a href="<?php echo e(env('IMAGE_SHOW_PATH') . '/student_document/' . $document['file']); ?>"
                                                            class="btn btn-sm btn-primary" download>
                                                            ⬇ Download
                                                        </a>
                                                    </td>
                                                </tr>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                                <tr>
                                                    <td colspan="6" class="text-center text-muted">
                                                        No documents found
                                                    </td>
                                                </tr>
                                                <?php endif; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                    <style>
                                        /* =========================
   Documents Table
   ========================= */

.documents-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 14px;
    table-layout: fixed;
    min-width: 1000px; /* mobile scroll */
}

/* Head */
.documents-table thead th {
    font-size: 12px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: .4px;
    border: none;
    padding: 12px 14px;
    background: #f8fafc;
    white-space: nowrap;
}

/* Rows */
.documents-table tbody tr {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 8px 22px rgba(0,0,0,.04);
    transition: .25s ease;
}

.documents-table tbody tr:hover {
    transform: translateY(-2px);
    box-shadow: 0 14px 30px rgba(0,0,0,.06);
}

/* Cells */
.documents-table tbody td {
    border: none;
    padding: 16px 14px;
    font-size: 14px;
    color: #0f172a;
    vertical-align: middle;
    word-wrap: break-word;
    white-space: nowrap;
}

.documents-table tbody tr td:first-child {
    border-radius: 16px 0 0 16px;
    text-align: center;
}

.documents-table tbody tr td:last-child {
    border-radius: 0 16px 16px 0;
    text-align: center;
}

/* Column widths */
.documents-table th:nth-child(1),
.documents-table td:nth-child(1) {
    width: 60px;
}

.documents-table th:nth-child(3),
.documents-table td:nth-child(3) {
    width: 90px;
    text-align: center;
}

.documents-table th:nth-child(6),
.documents-table td:nth-child(6) {
    width: 140px;
    text-align: center;
}

/* Image */
.documents-table img {
    display: block;
    margin: auto;
    width: 50px;
    height: 50px;
    border-radius: 10px;
    object-fit: cover;
}

/* Action button */
.documents-table .btn {
    padding: 6px 12px;
    font-size: 13px;
    white-space: nowrap;
}

/* =========================
   Mobile / Tablet
   ========================= */

@media (max-width: 991px) {

    .table-responsive,
    .documents-table {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .documents-table {
        min-width: 1000px; /* force horizontal scroll */
    }
}
/* =========================
   Add Document Button
   ========================= */

.btn-circle.btn-default {
    background: linear-gradient(135deg, #2563eb, #4f46e5);
    color: #fff;
    padding: 10px 18px;
    font-size: 14px;
    font-weight: 600;
    border-radius: 999px;
    border: none;
    box-shadow: 0 8px 18px rgba(37,99,235,.35);
    transition: all .3s ease;
}

.btn-circle.btn-default i {
    margin-right: 6px;
}

.btn-circle.btn-default:hover {
    background: linear-gradient(135deg, #1d4ed8, #4338ca);
    transform: translateY(-2px);
    box-shadow: 0 14px 28px rgba(37,99,235,.45);
    color: #fff;
}

                                    </style>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </section>

</div>


<!-- The Modal -->
<div class="modal" id="myModal">
    <div class="modal-dialog">
        <div class="modal-content" style="background: #555b5beb;">

            <div class="modal-header">
                <h4 class="modal-title text-white"><?php echo e(__('common.Status Change Confirmation')); ?></h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal"><i class="fa fa-times"
                        aria-hidden="true"></i></button>
            </div>

            <form action="#" method="post">
                <div class="modal-body">
                    <h5 class="text-white"><?php echo e(__('common.Are you sure you want to Change Status ?')); ?></h5>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary waves-effect waves-light change_status1"
                        data-bs-dismiss="modal"><?php echo e(__('common.Yes')); ?></button>
                    <button type="button" class="btn btn-default waves-effect remove-data-from-delete-form"
                        data-bs-dismiss="modal"><?php echo e(__('common.No')); ?></button>

                </div>
            </form>
        </div>
    </div>
</div>


<script>


    $(document).on('click', ".change_status", function () {
        $('#myModal').modal('toggle');
        id = $(this).data("id");
        status = $(this).data("status");
    });
    $(document).on('click', ".change_status1", function () {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            type: 'post',
            url: '/stu_status',
            data: {
                status: status,
                id: id
            },
            success: function (response) {
                location.reload();
                toastr.success('Status Changed Successfully !');
            },
        });
    });




    $(document).ready(function () {
        $(".tabData").hide(); // Hide all tabs
        $(".tabData:first").show(); // Show the first tab by default

        $(".get_data").click(function (e) {
            e.preventDefault();

            $(".tabData").hide();
            $(".get_data").removeClass("active");
            $(this).addClass("active");

            const targetId = $(this).data("target");
            $("#" + targetId).fadeIn(); // Smooth transition
        });
    });



    document.querySelectorAll('.download-btn').forEach(button => {
        button.addEventListener('click', function () {
            const fileName = this.getAttribute('data-file'); // Get file name from data attribute
            const link = document.createElement('a');
            link.href = fileName; // Set href to file name or file path
            link.download = fileName; // Set download attribute
            link.click(); // Trigger click to download
        });
    });

    // Open Modal
    function openModal() {
        document.getElementById('addStaffDocuments').style.display = 'block';
    }

    // Close Modal
    function closeModal() {
        document.getElementById('addStaffDocuments').style.display = 'none';
    }

    // Form Validation
    document.querySelector('#docsavebtn').addEventListener('click', (e) => {
        const title = document.getElementById('adocument_title').value.trim();
        const category = document.getElementById('adocument_category').value.trim();
        const file = document.getElementById('adocument_file').files[0];

        if (!title || !category || !file) {
            e.preventDefault();
            alert('All required fields must be filled out!');
        }
    });


</script>

<style>
    .modal-header {
        display: flex;
        justify-content: space-between;
        padding: 10px 20px;
        border-bottom: 1px solid #ddd;
    }


    .modal-footer {
        display: flex;
        justify-content: flex-end;
        padding: 10px 20px;
        border-top: 1px solid #ddd;
    }

    .close {
        cursor: pointer;
        font-size: 1.5rem;
        line-height: 1;
    }

    .table th,
    .table td {
        padding: 4px 8px;
    }

    .btn-default:hover {
        color: #333;
        background-color: #e6e6e6;
        border-color: #adadad;
    }

    .btn:hover,
    .btn:focus,
    .btn.focus {
        color: #333;
        text-decoration: none;
    }

    /* Ensure tab bar is fixed and doesn't move */
    .listing_tab {
        position: sticky;
        top: 0;
        background: white;
        /* Adjust based on design */
        z-index: 1000;
        margin: 10px;
        display: flex;
        align-items: center;
        border-bottom: 1px solid #c6c6c6;
    }

    /* Remove default list styles and align tabs */
    .listing_tab ul {
        padding-left: 0;
        margin-bottom: 0;
        list-style: none;
        display: flex;
        align-items: center;
    }

    /* Fix incorrect CSS selector */
    .listing_tab ul li:first-child {
        margin-left: 0;
    }

    /* Custom tab styles */
    .tabs-custom .nav-tabs {
        position: relative;
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
        border-bottom: none;
    }

    .tabs-custom .nav-tabs>li {
        position: relative;
        margin-right: 20px;
    }

    .tabs-custom .nav-tabs>li a {
        text-decoration: none;
        display: inline-block;
        color: #333;
        font-weight: bold;
        transition: all 250ms ease;
        font-size: 13px;
    }

    /* Active tab styling */
    .tabs-custom .nav-tabs>li.active {
        border-bottom: 2px solid #ffbd2e;
    }

    .tabs-custom .nav-tabs>li.active:before {
        content: '';
        height: 4px;
        width: 8px;
        display: block;
        position: absolute;
        bottom: -5px;
        left: 50%;
        border-radius: 0 0 8px 8px;
        transform: translateX(-50%);
        background: #ffbd2e;
    }

    /* Fix height jump when changing tabs */
    .tabData {
        min-height: 400px;
        /* Adjust as needed */
        overflow: auto;
        display: none;
        /* Hide all initially */
    }

    /* Show first tab by default */
    .tabData:first-child {
        display: block;
    }

    /* Table padding */
    .padding_table td,
    .padding_table th {
        padding: 10px;
    }

    /* Labels */
    .label-success-custom {
        border: #47a447 1px solid;
        color: #47a447;
    }

    .label-danger-custom {
        border: #d2322d 1px solid;
        color: #d2322d;
    }

    .label1 {
        display: inline;
        padding: .2em .6em .3em;
        font-size: 75%;
        font-weight: bold;
        line-height: 1;
        text-align: center;
        white-space: nowrap;
        vertical-align: baseline;
        border-radius: .25em;
    }

    /* Background color for alternate rows */
    .sky_tr {
        background: #e6e6e659;
        color: black;
    }
</style>
<script>
    function previewFile() {
        const fileInput = document.getElementById('file');
        const filePreview = document.getElementById('filePreview');
        filePreview.innerHTML = ''; // Clear previous preview

        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            const fileType = file.type;

            const previewDiv = document.createElement('div');
            previewDiv.style.display = "flex";
            previewDiv.style.alignItems = "center";
            previewDiv.style.gap = "10px";

            const removeButton = document.createElement('button');
            removeButton.textContent = '❌ Remove';
            removeButton.className = 'btn btn-danger btn-sm';
            removeButton.type = 'button';
            removeButton.onclick = () => {
                fileInput.value = '';
                filePreview.innerHTML = '';
            };

            if (fileType.startsWith("image/")) {
                const imgPreview = document.createElement("img");
                imgPreview.style.width = "80px";
                imgPreview.style.height = "80px";
                imgPreview.style.objectFit = "cover";
                imgPreview.style.border = "1px solid #ccc";
                imgPreview.style.borderRadius = "5px";

                const reader = new FileReader();
                reader.onload = (e) => imgPreview.src = e.target.result;
                reader.readAsDataURL(file);

                previewDiv.appendChild(imgPreview);
            } else {
                previewDiv.textContent = file.name;
            }

            previewDiv.appendChild(removeButton);
            filePreview.appendChild(previewDiv);
        }
    }
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH /home/rusofterp/public_html/dev/resources/views/students/admission/studentDetail.blade.php ENDPATH**/ ?>