
@extends('layouts_front.app_new')

@section('content')

 <?php $admin_favicon = Voyager::setting('admin.favicon', ''); ?>
    @if($admin_favicon == '')
        <link rel="shortcut icon" href="{{ voyager_asset('images/logo-icon.png') }}" type="image/png">
    @else
        <link rel="shortcut icon" href="{{ Voyager::image($admin_favicon) }}" type="image/png">
    @endif
<!-- <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&family=Rubik:wght@300;400;500;700;900&display=swap" rel="stylesheet"> -->
<style type="text/css">
.hidden-xs.col-sm-12.col-md-12{display:none;}
body.login .faded-bg{background:#fff;}
body {
/*background-image:url('{{ Voyager::image( Voyager::setting("admin.bg_image"), voyager_asset("images/bg.jpg") ) }}');*/
background-color: {{ Voyager::setting("admin.bg_color", "#FFFFFF" ) }};
}
body {
/*height: 100% !important; font-family: 'Rubik', sans-serif !important;*/
height: 100% !important; font-family: 'Open Sans', sans-serif !important;
}
body.login .login-container {  margin:25px auto 0;max-width: 700px; background: #fff; padding: 25px; right: 0px;left: 0px; top:0;border:1px solid #273581;   position: relative;}  
  

  body {
    height: 100% !important; font-family: 'Rubik', sans-serif !important;
}

body.login .form-group-default label{    color: #273581;
    font-weight: 500;
    font-size: 10px;
}
body.login .login-button {
	color: #fff !important;
	background: #273581 !important;
	opacity: 1 !important;
	
	border-radius: 10px;
	border: 1px solid #273581;
	font-weight: 700;
	font-size: 14px;
}
body.login .login-button:hover {
	background: #fff !important;
	color: #273581 !important;
}

      #abc ::-webkit-input-placeholder{ 
  color: #495057;
}

 #abc :-ms-input-placeholder{ 
  color: #495057;
}

#abc ::placeholder{
  color: #495057;
}

.butn-login{
  display: flex; margin-top:20px;
  justify-content: space-between;
  align-items: center;
}

.password-rule {
  margin-top: -15px;
  display: none;
  padding: 12px;
  z-index: 999;
  position: absolute;
  background: white;
  border: 1px solid #ccc;
}

.color-red {
  color: red;
}

.color-green {
  color: green;
}
label.error {
  position: unset !important;
}

</style>
<div class="col-xs-12 col-sm-12 col-md-12">

            <div class="login-container">

                <p style="color: #273581 !important;
font-weight: 500; font-family: 'Rubik', sans-serif !important; font-size:14px">{{ __('Reset Password') }}</p>
                @include('layouts_front.error')

                <form action="{{ route('password.update') }}" method="POST" id="form_reset_password">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">
                    <div class="form-group form-group-default" id="emailGroup">
                        <label>E-mail</label>
                        <div class="controls">
                            <input type="text" name="email" id="email" value="{{ $email ?? old('email') }}" placeholder="E-mail" class="form-control" required>
                         </div>
                    </div>
                     <div class="row">
                        <div class="col-md-6">   
                            <div class="form-group form-group-default" id="passwordGroup">
                                <label>Password</label>
                                <div class="controls">
                                    <input type="password" id="password" name="password" placeholder="Password" class="form-control" required onkeyup="">
                                </div>
                            </div>
                                    <div id="password_message" class="password-rule">
                                      <h4>Password Rule:</h4>
                                      <p id="pass_length"><i class="fa fa-times color-red" aria-hidden="true"></i> Min 8 <b> characters</b></p>
                                      <p id="pass_upper"><i class="fa fa-times color-red" aria-hidden="true"></i> Min 1 <b>uppercase</b> letter</p>
                                      <p id="pass_lower"><i class="fa fa-times color-red" aria-hidden="true"></i> Min 1 <b>lowercase</b> letter</p>
                                      <p id="pass_number"><i class="fa fa-times color-red" aria-hidden="true"></i> Min 1 <b>number</b></p>
                                      <p id="pass_special"><i class="fa fa-times color-red" aria-hidden="true"></i> Min 1 <b>special</b> character</p>
                                    </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-default" id="passwordGroup">
                                <label>Confirm Password</label>
                                <div class="controls">
                                    <input type="password" name="password_confirmation" placeholder="Password Confirmation" class="form-control" id="password_confirmation" required>
                                </div>
                            </div>
                        </div>
                    </div>
                     
                     <div class="butn-login">
                        <button type="submit" class="btn btn-block login-button">
                        <span class="signingin hidden"><span class="voyager-refresh"></span>...</span>
                        <span class="signin">{{ __('Reset Password') }}</span>
                    </button>
                    <span class="float-right" style="padding-top: 15px;">Or <a href="{{config('app.url')}}admin/login" class="bright-link"> Login</a></span>     
                     </div>
                    
              </form>
              <div style="clear:both"></div>
            </div> <!-- .login-container -->

        </div> <!-- .login-sidebar -->

<script src="{{asset('js/jquery.validate.min.js')}}"></script>
<script type="text/javascript">
  $.validator.addMethod("passwordValidator", function(value, element) {
    return value.match(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/);
  }, "Password Rules not matched");

  var validator = $('#form_reset_password').validate({
    ignore: '',
    rules: {
      email: {
        required: true,
        email:true,
        minlength : 3,
        maxlength : {{General::maxlength('email')}},
        onkeyup: false
      },
      password: {
        required: true,
        passwordValidator: true,
      },
      password_confirmation : {
        equalTo : "#password"
      }

    },
    messages: {
      password: "Invalid Password",
      password_confirmation: {
        equalTo:"Passwords do not match"
      }
    }
  });

  function passwordValidator(e) {
      var passVal = $("#password").val();
      // console.log(passVal);
      var passInvalidCount = 0;

      // minimum length
      if(passVal.length < 8) {
        $("#pass_length > i").removeClass('fa-check color-green');
        $("#pass_length > i").addClass('fa-times color-red');
        // $("#pass_length").css('color','red');
        ++passInvalidCount;
      } else {
        $("#pass_length > i").removeClass('fa-times color-red');
        $("#pass_length > i").addClass('fa-check color-green');
        // $("#pass_length").css('color','green');
      }

      // upperCase
      if(!passVal.match(/[A-Z]/g)) {
        $("#pass_upper > i").removeClass('fa-check color-green');
        $("#pass_upper > i").addClass('fa-times color-red');
        // $("#pass_upper").css('color','red');
        ++passInvalidCount;
      } else {
        $("#pass_upper > i").removeClass('fa-times color-red');
        $("#pass_upper > i").addClass('fa-check color-green');
        // $("#pass_upper").css('color','green');
      }

      // lowerCase
      if(!passVal.match(/[a-z]/g)) {
        $("#pass_lower > i").removeClass('fa-check color-green');
        $("#pass_lower > i").addClass('fa-times color-red');
        // $("#pass_lower").css('color','red');
        ++passInvalidCount;
      } else {
        $("#pass_lower > i").removeClass('fa-times color-red');
        $("#pass_lower > i").addClass('fa-check color-green');
        // $("#pass_lower").css('color','green');
      }

      // lowerCase
      if(!passVal.match(/[0-9]/g)) {
        $("#pass_number > i").removeClass('fa-check color-green');
        $("#pass_number > i").addClass('fa-times color-red');
        // $("#pass_number").css('color','red');
        ++passInvalidCount;
      } else {
        $("#pass_number > i").removeClass('fa-times color-red');
        $("#pass_number > i").addClass('fa-check color-green');
        // $("#pass_number").css('color','green');
      }

      // special character
      if(!passVal.match(/[\!\@\#\$\%\^\&\*\(\)\_\-\+\=]/g)) {
        $("#pass_special > i").removeClass('fa-check color-green');
        $("#pass_special > i").addClass('fa-times color-red');
        // $("#pass_special").css('color','red');
        ++passInvalidCount;
      } else {
        $("#pass_special > i").removeClass('fa-times color-red');
        $("#pass_special > i").addClass('fa-check color-green');
        // $("#pass_special").css('color','green');
      }

      if(passInvalidCount == 0) {
        return true;
      }
      return false;
  }
  
  $("#password").keyup(function() {
    passwordValidator();
  });
  $("#password").focusin(function() {
    $("#password_message").show();
    passwordValidator();
  });
  $("#password").focusout(function() {
    $("#password_message").hide();
  });
</script>
@endsection
