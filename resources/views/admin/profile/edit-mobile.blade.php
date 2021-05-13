@extends('voyager::master')

@section('page_title', __('voyager::generic.create').' Update Profile')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-edit"></i>Edit Mobile Number
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
    form label.error{color:red;}
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
									<label for="contact_phone">Mobile Number</label>
									<input type="tel" class="form-control" name="mobile_number" value="" placeholder="Mobile Number" required>
                  <br>
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
										<label for="contact_phone">Mobile Number</label>
										<input type="tel" class="form-control" name="mobile_number" value="{{old('mobile_number')}}" placeholder="Mobile Number" required>
									</div>
								</div>
								<div class="col-md-6">
		                            <div class="form-group">
										<label for="contact_phone">OTP</label>
										<input type="text" class="form-control" name="otp" value="" placeholder="OTP" required>
                    <br>
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
$(document).ready(function($){
	$.validator.addMethod("mobile_number_india", function(value, element) {
        return this.optional(element) || /^[6789]\d{9}$/i.test(value);
    }, "Please enter a valid number.");
	var validatorGetOtpForm = $("#get-otp-form").validate({
        rules: {
            mobile_number:{
                maxlength:10,
                mobile_number_india:true,
            }
        }
    });
    var validatorSubmitOtpForm = $("#submit-otp-form").validate({
        rules: {
            mobile_number:{
                maxlength:10,
                mobile_number_india:true,
            },
            otp:{
            	required:true,
            }
        }
    });

	$("#get-otp-button").on('click',function(e){
		if(!$("#get-otp-form").valid()){
            validatorGetOtpForm.focusInvalid();
            return false;
        }
        var thisButton = $(this);
        thisButton.attr('disabled','disabled');
        var form =$("#get-otp-form");
        var mobile_number = form.find('input[name=mobile_number]').val();

        $('.alert').addClass("hide");
        $('.alert').html('');

        $.ajax({
	         method: 'post',
	         url: "{{route('admin.profile.edit-mobile-get-otp')}}",
	         headers: {
	           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	         },
	         data: {
	           mobile_number: mobile_number,
	           _token: $('meta[name="csrf-token"]').attr('content')
	         }
        }).then(function (response) {
          form.addClass('hide');
          $("#submit-otp-form").find('input[name=mobile_number]').val(response.mobile_number);
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

	$("#submit-otp-button").on('click',function(e){
		if(!$("#submit-otp-form").valid()){
            validatorSubmitOtpForm.focusInvalid();
            return false;
        }
        var thisButton = $(this);
        thisButton.attr('disabled','disabled');
        var form =$("#submit-otp-form");
        var mobile_number = form.find('input[name=mobile_number]').val();
        var otp = form.find('input[name=otp]').val();

        $('.alert').addClass("hide");
        $('.alert').html('');

        $.ajax({
	         method: 'post',
	         url: "{{route('admin.profile.update-mobile')}}",
	         headers: {
	           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	         },
	         data: {
	           mobile_number: mobile_number,
	           otp: otp,
	           _token: $('meta[name="csrf-token"]').attr('content')
	         }
        }).then(function (response) {
          form.find('input[name=mobile_number]').val('');
          form.find('input[name=otp]').val('');
          $('.alert.alert-success').html(response.message);
          $('.alert.alert-success').removeClass('hide');
          thisButton.removeAttr('disabled');
          form.addClass('hide');
          $("#get-otp-form").removeClass('hide');
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
        $("#submit-otp-form").find('input[name=mobile_number]').val('');
        $("#submit-otp-form").find('input[name=otp]').val('');
      });

});	
</script>	
@endsection