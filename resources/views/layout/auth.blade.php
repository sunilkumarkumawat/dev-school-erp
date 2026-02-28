<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>School | Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="{{ asset('public/assets/school/css/all.min.css') }}">
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
    <link rel="stylesheet" href="{{ asset('public/assets/school/css/tempusdominus-bootstrap-4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/school/css/icheck-bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/school/css/jqvmap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/school/css/adminlte.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/school/css/OverlayScrollbars.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/school/css/daterangepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/school/css/summernote-bs4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/school/css/dataTables.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/school/css/responsive.bootstrap4.css') }}">
    <link rel="stylesheet" href="{{ asset('public/assets/school/css/buttons.bootstrap4.css') }}">
    <link
        rel="stylesheet"
        href="https://use.fontawesome.com/releases/v5.13.0/css/all.css"
        integrity="sha384-Bfad6CLCknfcloXFOyFnlgtENryhrpZCe29RTifKEixXQZ38WheV+i/6YWSzkz3V"
        crossorigin="anonymous"/>

</head>
<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
       
        @yield('content')
   
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
                var URL  = "{{ url('/') }}";
            </script>
        <script src="{{URL::asset('public/assets/school/js/jquery.min.js')}}"></script>
        <script src="{{URL::asset('public/assets/school/js/jquery-ui.min.js')}}"></script>
        <script>
            $.widget.bridge('uibutton', $.ui.button)
        </script>
        <script src="{{URL::asset('public/assets/school/js/jquery.dataTables.js')}}"></script>
        <script src="{{URL::asset('public/assets/school/js/dataTables.bootstrap4.js')}}"></script>
        <!--<script src="{{URL::asset('public/assets/school/js/dataTables.responsive.js')}}"></script>-->
        <script src="{{URL::asset('public/assets/school/js/bootstrap.bundle.min.js')}}"></script>
        <script src="{{URL::asset('public/assets/school/js/Chart.min.js')}}"></script>
        <script src="{{URL::asset('public/assets/school/js/sparkline.js')}}"></script>
        <script src="{{URL::asset('public/assets/school/js/jquery.vmap.min.js')}}"></script>
        <script src="{{URL::asset('public/assets/school/js/jquery.vmap.usa.js')}}"></script>
        <script src="{{URL::asset('public/assets/school/js/jquery.knob.min.js')}}"></script>
        <script src="{{URL::asset('public/assets/school/js/moment.min.js')}}"></script>
        <script src="{{URL::asset('public/assets/school/js/daterangepicker.js')}}"></script>
        <script src="{{URL::asset('public/assets/school/js/tempusdominus-bootstrap-4.min.js')}}"></script>
        <script src="{{URL::asset('public/assets/school/js/summernote-bs4.min.js')}}"></script>
        <script src="{{URL::asset('public/assets/school/js/jquery.overlayScrollbars.min.js')}}"></script>
        <script src="{{URL::asset('public/assets/school/js/pdfmake.js')}}"></script>
        <script src="{{URL::asset('public/assets/school/js/demo.js')}}"></script>
        <script src="{{URL::asset('public/assets/school/js/dashboard.js')}}"></script>
        <script src="{{URL::asset('public/assets/school/js/bootstrap5.js')}}"></script>   
        
       
        <script src="{{URL::asset('public/assets/school/js/responsive.bootstrap4.js')}}"></script>
        <script src="{{URL::asset('public/assets/school/js/dataTables.buttons.js')}}"></script>
        <script src="{{URL::asset('public/assets/school/js/buttons.bootstrap4.js')}}"></script>
        <script src="{{URL::asset('public/assets/school/js/buttons.html5.js')}}"></script>
        <script src="{{URL::asset('public/assets/school/js/buttons.print.js')}}"></script>
        <script src="{{URL::asset('public/assets/school/js/buttons.colVis.js')}}"></script>
        <script src="{{URL::asset('public/assets/school/js/jquery.validate.js')}}"></script>
<script src="{{URL::asset('public/assets/school/js/additional-methods.js')}}"></script>
       <script src="{{URL::asset('public/assets/school/js/adminlte.js')}}"></script>

<script>
  $(function () {
    $("#example1").DataTable({
      "responsive": true, "lengthChange": false, "autoWidth": false,
      "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
    }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
    $('#example2').DataTable({
      "paging": true,
      "lengthChange": false,
      "searching": false,
      "ordering": true,
      "info": true,
      "autoWidth": false,
      "responsive": true,
    });
  });


$('#country_id').on('change', function(e){
    
	var country_id = $(this).val();
    $.ajax({
         headers: {'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')},
	  url: '/countryData/'+country_id,
	  success: function(data){
			$("#state_id").html(data);
	  }
	});
	
});

$('#state_id').on('change', function(e){
    
	var state_id = $(this).val();
    $.ajax({
         headers: {'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')},
	  url: '/stateData/'+state_id,
	  success: function(data){
			$("#city_id").html(data);
	  }
	});
	
});
   
</script>
<script type="text/javascript">
    function isNumber(evt)
      {
         var charCode = (evt.which) ? evt.which : event.keyCode
         if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
 
         return true;
      }
</script>

</body>
</html>
