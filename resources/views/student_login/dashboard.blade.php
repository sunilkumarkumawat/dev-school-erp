@extends('student_login.layout.app') 
@section('title', 'Dashboard')
@section('page_title', 'Dashboard')
@section('page_sub', 'Welcome')
@section('content')

@php
  $getSetting = Helper::getSetting();
@endphp
  
<div class="card text-center school-info-card" >
    <div class="d-flex justify-content-center  p-2">
        <img src="{{ asset('public/assets/student_login/img/logo.png') }}" alt="Logo" width="55px" class="me-2" style="border-radius:50%;">
        <div class="ml-2">
            <h6 class="m-0 fw-bold p-0"> {{ \Illuminate\Support\Str::limit($getSetting->name ?? 'Rukmani Software', 27, '...') }}</h6>
            <p class="m-0 " style="text-align: center;"> <b>Email :</b> {{ \Illuminate\Support\Str::limit($getSetting->gmail ?? '', 30, '...') }}</p>
            <p class="m-0 " style="text-align: center;"><b>Address :</b>  {{ \Illuminate\Support\Str::limit($getSetting->address ?? '', 30, '...') }}</p>
        </div>
        
    </div>
    <div class="ml-2 quoto">
          “Learning Today, Leading Tomorrow”
         </div>
</div>
<div class="dashboard-grid mt-3">
  @php
    $modules = [
     
      ['icon' => 'attendence.png', 'label' => 'ATTENDANCE', 'url' => 'AttendanceView_student'],
      ['icon' => 'homework.png', 'label' => 'HOMEWORK', 'url' => 'student_homework'],
      ['icon' => 'fees.png', 'label' => 'FEES', 'url' => 'fees_history'],
      ['icon' => 'exam_timetable.jpeg', 'label' => 'EXAM TIME TABLE', 'url' => 'student/result-card'],
      ['icon' => 'result.png', 'label' => 'EXAM RESULT', 'url' => 'student/result-card'],
      ['icon' => 'leave.png', 'label' => 'APPLY LEAVE', 'url' => 'applyLeaveStudent'],
      ['icon' => 'feedback.png', 'label' => 'COMPLAINT', 'url' => 'complaintAddStudent'],
      ['icon' => 'timetable.jpg', 'label' => 'DAILY CLASS TIMETABLE', 'url' => 'timetable'],
      ['icon' => 'circular.jpeg', 'label' => 'NOTICE  BOARD', 'url' => 'notice_board_student/0'],
      ['icon' => 'notes.png', 'label' => 'ACADEMIC NOTES', 'url' => 'student_study_material'],
      ['icon' => 'syllabus.png', 'label' => 'SYLLABUS', 'url' => 'student_syllabus'],
      ['icon' => 'download.jpeg', 'label' => 'DOWNLOAD CENTER', 'url' => 'download_center_student'],
    ];
  @endphp

  @foreach ($modules as $item)
    <a href="{{ url($item['url']) }}" class="dash-card text-center animate">
      <img src="{{ asset('public/assets/student_login/img/icons/'.$item['icon']) }}" alt="{{ $item['label'] }}">
      <p>{{ $item['label'] }}</p>
    </a>
  @endforeach
</div>
@endsection