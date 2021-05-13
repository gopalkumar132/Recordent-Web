@extends('voyager::master')

@section('page_title', __('voyager::generic.create').' Update Profile')

<style type="text/css">
    .field-icon {
       float: right;
       margin-left: -25px;
       margin-top: -24px;
       margin-right: 10px;

       position: relative;
       z-index: 2;
      }

      .container{
       padding-top:50px;
       margin: auto;
      }
      div#dataTable_filter label.error, label.error, #add_store_record label.error {
    bottom: -46px !important;
    border: 0px !important;
}
.Error {
    background: white !important;
     border: 1px solid rgba(221,60,60,.5) !important; 
    border-radius: 4px;
    color: rgba(0,0,0,.5);
    margin: 2em 0;
    padding: 1em 1.5em;
}

.color-red {
  color: red;
}

.color-green {
  color: green;
}

.password-rule {
  display: none;
  padding: 12px;
  z-index: 999;
  position: absolute;
  background: white;
  border: 1px solid #ccc;
}

</style>
@section('page_header')
 @if(empty(Auth::user()->password))
<h1 class="page-title">
    <i class="voyager-edit"></i>Update password
</h1>
@endif
 @if(!empty(Auth::user()->password))
<h1 class="page-title">
    <i class="voyager-edit"></i>Change password
</h1>
@endif
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
                @if (count($errors) > 0)
                    <div class="alert alert-danger">
                        <ul>
                         @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                         @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{route('admin.profile.change-password')}}" name="change-password-form" id="change-password-form" method="POST">
					 @csrf
                     @if(!empty(Auth::user()->password))              
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="contact_phone">Old Password*</label>
                                <input type="password" class="form-control" name="old_password" id="old_password" placeholder="Old password" required>
                                <i class="fa fa-eye field-icon" onclick="myFunctionold()"></i>
                            </div>
                        </div>
                    @endif    
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="contact_phone">New Password*</label>
                                <input type="password" class="form-control" id="new_password" name="new_password" placeholder="New password" required onkeyup='passwordValidator();' onfocus="showPassModal()" onfocusout="hidePassModal()">
                                <i class="fa fa-eye field-icon" onclick="myFunction()"></i>
                                <div id="password_message" class="password-rule">
                                  <h4>Password Rule:</h4>
                                  <p id="pass_length"><i class="fa fa-times color-red" aria-hidden="true"></i> Min <b>8 characters</b></p>
                                  <p id="pass_upper"><i class="fa fa-times color-red" aria-hidden="true"></i> Min 1 <b>uppercase</b> letter</p>
                                  <p id="pass_lower"><i class="fa fa-times color-red" aria-hidden="true"></i> Min 1 <b>lowercase</b> letter</p>
                                  <p id="pass_number"><i class="fa fa-times color-red" aria-hidden="true"></i> Min 1 <b>number</b></p>
                                  <p id="pass_special"><i class="fa fa-times color-red" aria-hidden="true"></i> Min 1 <b>special</b> character</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="contact_phone">Confirm Password*</label>
                                <input type="password" class="form-control" name="confirm_password" id="password_confirmation" placeholder="Confirm password" required>
                                <i class="fa fa-eye field-icon" onclick="myFunctionConfirm()"></i>
                            </div>
                        </div>
                    <div class="col-md-12">							
						<div class="form-action">
							<button type="submit" class="btn btn-primary" id="get-otp-button">SUBMIT</button>
						</div>
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

$.validator.addMethod("passwordValidator", function(value, element) {
    return value.match(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/);
  }, "Password Rules not matched");

$("#change-password-form").validate({
    rules: {
       new_password: { 
         required: true,
         passwordValidator: true
       } , 
      confirm_password: { 
        equalTo: "#new_password",
        minlength: 6,
        maxlength: 15
      }
   },
   messages:{
    new_password: 'Invalid Password',
    confirm_password:{
        equalTo:"Please enter the same password again."
    }
   }
});

});	
</script>	
<script type="text/javascript">
  function myFunctionold() {
  var x = document.getElementById("old_password");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}
function myFunction() {
  var x = document.getElementById("new_password");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}
function myFunctionConfirm() {
  var x = document.getElementById("password_confirmation");
  if (x.type === "password") {
    x.type = "text";
  } else {
    x.type = "password";
  }
}

function showPassModal() {
  passwordValidator();
  $("#password_message").show();
}

function hidePassModal() {
  $("#password_message").hide();
}

function passwordValidator() {
  var passVal = $("#new_password").val();
  // console.log(passVal);
  var passInvalidCount = 0;
  // minimum length
  if(passVal.length < 8) {
    $("#pass_length > i").removeClass('fa-check color-green');
    $("#pass_length > i").addClass('fa-times color-red');
    ++passInvalidCount;
  } else {
    $("#pass_length > i").removeClass('fa-times color-red');
    $("#pass_length > i").addClass('fa-check color-green');
  }

  // upperCase
  if(!passVal.match(/[A-Z]/g)) {
    $("#pass_upper > i").removeClass('fa-check color-green');
    $("#pass_upper > i").addClass('fa-times color-red');
    ++passInvalidCount;
  } else {
    $("#pass_upper > i").removeClass('fa-times color-red');
    $("#pass_upper > i").addClass('fa-check color-green');
  }

  // lowerCase
  if(!passVal.match(/[a-z]/g)) {
    $("#pass_lower > i").removeClass('fa-check color-green');
    $("#pass_lower > i").addClass('fa-times color-red');
    ++passInvalidCount;
  } else {
    $("#pass_lower > i").removeClass('fa-times color-red');
    $("#pass_lower > i").addClass('fa-check color-green');
  }

  // lowerCase
  if(!passVal.match(/[0-9]/g)) {
    $("#pass_number > i").removeClass('fa-check color-green');
    $("#pass_number > i").addClass('fa-times color-red');
    ++passInvalidCount;
  } else {
    $("#pass_number > i").removeClass('fa-times color-red');
    $("#pass_number > i").addClass('fa-check color-green');
  }

  // special character
  if(!passVal.match(/[\!\@\#\$\%\^\&\*\(\)\_\-\+\=]/g)) {
    $("#pass_special > i").removeClass('fa-check color-green');
    $("#pass_special > i").addClass('fa-times color-red');
    ++passInvalidCount;
  } else {
    $("#pass_special > i").removeClass('fa-times color-red');
    $("#pass_special > i").addClass('fa-check color-green');
  }

  /*if(passInvalidCount == 0) {
    saveValue(e);
  }*/
}
</script>
@endsection