 
 <script type="text/javascript" src="{{URL::asset('public/assets/student_login/toastr_message/js/jquery_3.5.1_jquery.min.js')}}"></script>
  
 <link rel="stylesheet" href="{{ asset('public/assets/student_login/toastr_message/css/toastr.min.css') }}" />
  <script type="text/javascript" src="{{URL::asset('public/assets/student_login/toastr_message/js/toastr.min.js')}}"></script>
 <script src="{{URL::asset('public/assets/student_login/js/sweetAlert.js')}}"></script>
 <script>
   @if(Session::has('message'))
        Swal.fire({
            icon: 'success',
            title: 'Success!',
            text: "{{ session('message') }}",
            timer: 2000,
            showConfirmButton: false
        });
    @endif
 
   @if(Session::has('error'))
   toastr.options =
   {
       "closeButton" : true,
       "progressBar" : true
   }
           toastr.error("{{ session('error') }}");
   @endif
 
   @if(Session::has('info'))
   toastr.options =
   {
       "closeButton" : true,
       "progressBar" : true
   }
           toastr.info("{{ session('info') }}");
   @endif
 
   @if(Session::has('warning'))
   toastr.options =
   {
       "closeButton" : true,
       "progressBar" : true
   }
           toastr.warning("{{ session('warning') }}");
   @endif
 </script>