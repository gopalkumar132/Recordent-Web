@extends('voyager::master')

@section('page_title', __('voyager::generic.create').' Update Profile')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-edit"></i>Edit Email
    </h1>
   {{-- @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
             @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
             @endforeach
            </ul>
        </div>
    @endif --}}
@stop
@section('content')
<script src="{{asset('front/vendor/jquery/jquery.min.js')}}"></script>
<style>
    form label.error{color:red;bottom: -10px;top:auto;}
</style>
    <div class="page-content container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                    	<div class="alert alert-success hide" role="alert"></div>
                    	<div class="alert alert-danger hide" role="alert"></div>
                    	<form action="" name="get_otp_form" id="get-otp-form" method="POST">
  							@csrf
                              <div class="col-md-12">
  	                            <div class="form-group">
  									<label for="contact_phone">Email</label>
  									<input type="email" id="email" class="form-control email" maxlength="{{General::maxlength('email')}}" name="email" value="{{old('email',Auth::user()->email)}}" placeholder="Email" required maxlength="30" onkeypress="return onlyemail(this,event)">
  								<span id="error_msg"></span>
                  </div>
  							</div>
                              <div class="col-md-12">							
  								<div class="form-action">
  									<button type="button" class="btn btn-primary" id="get-otp-button">SUBMIT</button>
  								</div>
  							</div>		
  						</form>

						<form action="" name="submit_otp_form" id="submit-otp-form" method="POST" class="hide">
							@csrf
	                            <div class="col-md-12">
		                            <div class="form-group">
										<label for="contact_phone">Email</label>
										<input type="email" class="form-control email" name="email" placeholder="Email" required onkeypress="return onlyemail(this,event)">
									</div>
								</div>
								<div class="col-md-6">
		                            <div class="form-group">
										<label for="contact_phone">OTP</label>
										<input type="text" class="form-control" name="otp" value="" placeholder="OTP" required>
									</div>
								</div>
                            <div class="col-md-12">							
								<div class="form-action">
									<button type="button" class="btn btn-primary" id="submit-otp-button">SUBMIT</button>
								</div>	
								<br>
								<a href="Javascript:void" style="display: block;width: 100%;float: left;padding-top: 10px;" class="bright-link" id="resendOtp">Didn't get OTP? Send again</a>
							</div>	
						</form>
					</div>
				</div>
			</div>
		</div>
    </div>

<script src="{{asset('js/jquery.validate.min.js')}}"></script>    

<script>
    function onlyemail(myfield, e)
    {

        var key;
        var keychar;
        if (window.event)
            key = window.event.keyCode;
        else if (e)
            key = e.which;
        else
            return true;

        keychar = String.fromCharCode(key);

        // control keys
        if ((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) ){
            return true;
        }else if ((("`~!#$%^&*();:,\"\'?\/_+=|\/<>{}[]").indexOf(keychar) > -1)){
            return false;
        }else{
            return true;
        }
    }
$(document).ready(function($){
  var validatorGetOtpForm = $("#get-otp-form").validate();
  var validatorSubmitOtpForm = $("#submit-otp-form").validate();

/*	$("#get-otp-button").on('click',function(e){
		if(!$("#get-otp-form").valid()){
            validatorGetOtpForm.focusInvalid();
            return false;
        }
        var thisButton = $(this);
        thisButton.attr('disabled','disabled');
        var form =$("#get-otp-form");
        var email = form.find('input[name=email]').val();

        $('.alert').addClass("hide");
        $('.alert').html('');

        $.ajax({
	         method: 'post',
	         url: "{{route('admin.profile.edit-email-get-otp')}}",
	         headers: {
	           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	         },
	         data: {
	           email: email,
	           _token: $('meta[name="csrf-token"]').attr('content')
	         }
        }).then(function (response) {
          form.addClass('hide');
          $("#submit-otp-form").find('input[name=email]').val(response.email);
          $("#submit-otp-form").removeClass('hide');
          $('.alert.alert-success').html(response.message);
          $('.alert.alert-success').removeClass('hide');
          thisButton.removeAttr('disabled');

        }).fail(function (data) {
          $('.alert.alert-danger').html(data.responseJSON.message);
          $('.alert.alert-danger').removeClass('hide');
          thisButton.removeAttr('disabled');
        });
      });
*/
	$("#submit-otp-button").on('click',function(e){
		if(!$("#submit-otp-form").valid()){
            validatorSubmitOtpForm.focusInvalid();
            return false;
        }
        var thisButton = $(this);
        thisButton.attr('disabled','disabled');
        var form =$("#submit-otp-form");
        var email = form.find('input[name=email]').val();
        var otp = form.find('input[name=otp]').val();

        $('.alert').addClass("hide");
        $('.alert').html('');

        $.ajax({
	         method: 'post',
	         url: "{{route('admin.profile.update-email')}}",
	         headers: {
	           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	         },
	         data: {
	           email: email,
	           otp: otp,
	           _token: $('meta[name="csrf-token"]').attr('content')
	         }
        }).then(function (response) {
          form.find('input[name=email]').val('');
          form.find('input[name=otp]').val('');
          $('.alert.alert-success').html(response.message);
          $('.alert.alert-success').removeClass('hide');
          thisButton.removeAttr('disabled');
          form.addClass('hide');
          $("#get-otp-form").removeClass('hide');
          $("#get-otp-form").find('input[name=email]').val('');
        }).fail(function (data) {
          $('.alert.alert-danger').html(data.responseJSON.message);
          $('.alert.alert-danger').removeClass('hide');
          thisButton.removeAttr('disabled');

        });
      });

	$("#submit-otp-form #resendOtp").on('click',function(e){
        e.preventDefault();
        $('.alert').addClass("hide");
        $('.alert').html('');
        $("#get-otp-form").removeClass('hide');
        $("#submit-otp-form").addClass('hide');
        $("#submit-otp-form").find('input[name=email]').val('');
        $("#submit-otp-form").find('input[name=otp]').val('');
      });

      $("#get-otp-button").on("click",function(e){
     
     if(!$("#get-otp-form").valid()){
               validatorGetOtpForm.focusInvalid();
               return false;
           }
   
     var emailid=$("#email").val();
     var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if(regex.test(emailid))
            {
          $.ajax({
             method: 'post',
             url: "{{route('verifyemaiid')}}",
             headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
             },
             data: {
              emailid: emailid,
               _token: $('meta[name="csrf-token"]').attr('content')
             }
          }).then(function (response) {
   
           $('.alert.alert-danger').html("");
           if(response.status)
           {
            Get_mobile_otp_function();
           }else{
             $('.alert.alert-success').html("");
             $('.alert.alert-danger').html(response.message);
             $('.alert.alert-danger').removeClass('hide');
           }
          }).fail(function (data) {
           
          });
        }
   })
   
   function Get_mobile_otp_function(){
     
           var thisButton = $("#get-otp-button");
           thisButton.attr('disabled','disabled');
           var form =$("#get-otp-form");
           var email = form.find('input[name=email]').val();
   
           $('.alert').addClass("hide");
           $('.alert').html('');
   
           $.ajax({
              method: 'post',
              url: "{{route('admin.profile.edit-email-get-otp')}}",
              headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
              },
              data: {
                email: email,
                _token: $('meta[name="csrf-token"]').attr('content')
              }
           }).then(function (response) {
             form.addClass('hide');
             $("#submit-otp-form").find('input[name=email]').val(response.email);
             $("#submit-otp-form").removeClass('hide');
             $('.alert.alert-success').html(response.message);
             $('.alert.alert-success').removeClass('hide');
             thisButton.removeAttr('disabled');
   
           }).fail(function (data) {
             $('.alert.alert-danger').html(data.responseJSON.message);
             $('.alert.alert-danger').removeClass('hide');
             thisButton.removeAttr('disabled');
           });
   }
});	
</script>	
@endsection