@extends('layouts_front.app_new')
@section('meta-title', config('seo_meta_tags.register_page.title'))
@section('meta-description', config('seo_meta_tags.register_page.description'))
@section('canonical-url')
  <link rel="canonical" href="{{config('app.url')}}register" />
@endsection
@section('content')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
 <?php $admin_favicon = Voyager::setting('admin.favicon', ''); ?>
    @if($admin_favicon == '')
        <link rel="shortcut icon" href="{{ voyager_asset('images/logo-icon.png') }}" type="image/png">
    @else
        <link rel="shortcut icon" href="{{ Voyager::image($admin_favicon) }}" type="image/png">
    @endif

	<div class="loading verify-loader" style="display:none;"></div>
@php General::utmContainerDetect(); @endphp



<style>


/* Absolute Center CSS Spinner */
.loading {
  position: fixed;
  z-index: 999;
  height: 2em;
  width: 2em;
  overflow: show;
  margin: auto;
  top: 0;
  left: 0;
  bottom: 0;
  right: 0;
}

/* Transparent Overlay */
.loading:before {
  content: '';
  display: block;
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: rgba(0,0,0,0.3);
}

/* :not(:required) hides these rules from IE9 and below */
.loading:not(:required) {
  /* hide "loading..." text */
  font: 0/0 a;
  color: transparent;
  text-shadow: none;
  background-color: transparent;
  border: 0;
}

.loading:not(:required):after {
  content: '';
  display: block;
  font-size: 10px;
  width: 1em;
  height: 1em;
  margin-top: -0.5em;
  -webkit-animation: spinner 1500ms infinite linear;
  -moz-animation: spinner 1500ms infinite linear;
  -ms-animation: spinner 1500ms infinite linear;
  -o-animation: spinner 1500ms infinite linear;
  animation: spinner 1500ms infinite linear;
  border-radius: 0.5em;
  -webkit-box-shadow: rgba(0, 0, 0, 0.75) 1.5em 0 0 0, rgba(0, 0, 0, 0.75) 1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) 0 1.5em 0 0, rgba(0, 0, 0, 0.75) -1.1em 1.1em 0 0, rgba(0, 0, 0, 0.5) -1.5em 0 0 0, rgba(0, 0, 0, 0.5) -1.1em -1.1em 0 0, rgba(0, 0, 0, 0.75) 0 -1.5em 0 0, rgba(0, 0, 0, 0.75) 1.1em -1.1em 0 0;
  box-shadow: rgba(0, 0, 0, 0.75) 1.5em 0 0 0, rgba(0, 0, 0, 0.75) 1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) 0 1.5em 0 0, rgba(0, 0, 0, 0.75) -1.1em 1.1em 0 0, rgba(0, 0, 0, 0.75) -1.5em 0 0 0, rgba(0, 0, 0, 0.75) -1.1em -1.1em 0 0, rgba(0, 0, 0, 0.75) 0 -1.5em 0 0, rgba(0, 0, 0, 0.75) 1.1em -1.1em 0 0;
}

/* Animation */

@-webkit-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@-moz-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@-o-keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}
@keyframes spinner {
  0% {
    -webkit-transform: rotate(0deg);
    -moz-transform: rotate(0deg);
    -ms-transform: rotate(0deg);
    -o-transform: rotate(0deg);
    transform: rotate(0deg);
  }
  100% {
    -webkit-transform: rotate(360deg);
    -moz-transform: rotate(360deg);
    -ms-transform: rotate(360deg);
    -o-transform: rotate(360deg);
    transform: rotate(360deg);
  }
}


.hidden-xs.col-sm-12.col-md-12{display:none;}
body.login #msform .form-group-default{margin-bottom:0 !important; overflow:inherit;}
body.login  #msform .form-group-default label.error{position:absolute;color:red; left:0; bottom:-20px;}
/*body.login  #msform .form-group-default label.error#mobile_number-error{ left:-100px;}*/
body.login .faded-bg{background:#fff;}
body {
/*background-image:url('{{ Voyager::image( Voyager::setting("admin.bg_image"), voyager_asset("images/bg.jpg") ) }}');*/
background-color: {{ Voyager::setting("admin.bg_color", "#FFFFFF" ) }};
}

    #msform fieldset:not(:first-of-type) {
        display: none
    }
    .country-code{padding-right:0; border-right:none;width:100px;}
    .form-check{padding-left:0;}
    .form-check .form-check-label{margin-left:18px; position: relative;top: -2px;}
    body.login .login-container {  margin:25px auto 0;max-width: 700px; background: #fff; padding: 25px; right: 0px;left: 0px; top:0;border:1px solid #273581;   position: relative;}
    body.login #msform .country-code .form-group-default{border-right:0; border-radius:3px 0 0 3px;}
    .mobile-number{padding-left:0;width:calc(100% - 100px)}
    body.login #msform .mobile-number .form-group-default{border-radius:0 3px 3px 0;}
    body.login .form-group-default label {
	color: #273581;
	font-weight: 500;
	font-size: 10px;
}
    body.login #msform .login-button{color: #fff !important;
    background: #273581 !important;
    opacity: 1 !important;
    border-radius: 10px;
    border: 1px solid #273581;
    font-weight: 700; padding:12px 20px;
    font-size: 14px;}



     #msform ::-webkit-input-placeholder,
      #loginWithEmailGetOtpToMobileForm ::-webkit-input-placeholder{
  color: #495057;
}

 #msform :-ms-input-placeholder ,
#loginWithEmailGetOtpToMobileForm :-ms-input-placeholder{
  color: #495057;
}

 #msform ::placeholder,
#loginWithEmailGetOtpToMobileForm ::placeholder{
  color: #495057;
}


    #msform .action-button {
           color: #fff !important;
    background: #273581 !important;
    opacity: 1 !important;
    border-radius: 10px;
    border: 1px solid #273581;
    font-weight: 700; padding:12px 20px;
    font-size: 14px;}

    #msform .action-button:hover,
    #msform .action-button:focus {
        background: #fff !important;
color: #273581 !important;
    }

    #msform .action-button-previous {
        width: 100px;
        background: #616161;
        display: block;
text-align: center;
text-decoration: none;
color: #eee;
font-family: Open Sans,sans-serif;
font-weight: 100;
padding: 12px 20px;

outline: none !important;
opacity: .8;
border: 0;
width: auto;

float: left;
font-size: 11px;
font-weight: 400;
text-transform: uppercase;
transition: width .3s ease;
    }
    #progressbar #business strong{margin-left:20px;}
     body.login #msform .login-button:hover{ background: #fff !important;
color: #273581 !important;}
    /*#msform .action-button-previous:hover,*/
    /*#msform .action-button-previous:focus{*/
    /*    box-shadow: 0 0 0 2px white, 0 0 0 3px #616161*/
    /*}*/
    #progressbar {
        margin-bottom:10px; padding:0;
        overflow: hidden;
        color: lightgrey
    }

    #progressbar .active {
        color: #000000
    }

    #progressbar li {
        list-style-type: none;
        font-size: 12px;
        width: 50%;
        float: left;
        position: relative
    }

    #progressbar #account:before {
        font-family: FontAwesome;
        content: "1";
        text-align:center;
    }

    #progressbar #business:before {
        font-family: FontAwesome;
        content: "2"; text-align:center;
    }

    #progressbar li:before {
        width: 50px;
        height: 50px;
        line-height: 45px;
        display: block;
        font-size: 18px;
        color: #ffffff;
        background: lightgray;
        border-radius: 50%;
        margin: 0 auto 10px auto;
        padding: 2px
    }

    #progressbar li:after {
        content: '';
        width: 100%;
        height: 2px;
        background: lightgray;
        position: absolute;
        left: 0;
        top: 25px;
        z-index: -1
    }

    #progressbar li.active:before,
    #progressbar li.active:after {
        background: #273581;
    }
    a{color:#273581}
    body.login #msform .login-button:disabled{ color: #fff !important;
    background: #273581 !important;
    opacity: 1 !important;
    border-radius: 10px;
    border: 1px solid #273581;
    font-weight: 700; padding:12px 20px;
    font-size: 14px;}
    body.login #msform .login-button:disabled:hover{ background: #fff !important;
color: #273581 !important;}
    #msform .previous {min-width: 70px; border-radius:10px;}

 .modal-header {
    position: relative;
	justify-content:center;
}

	@media (min-width:992px) {
.parent {
  padding-left: 65%;
  position:
}
}

.modal-content {

  width: 100%;
  margin-top: 35%;
}

@media (max-width:580px) {
.modal-content {

  width: 80%;
   height: 100%;
  margin-top: 35%;
  margin-left: 8%;

}
}
.commap-team-popup .modal-header .close {
    position: absolute;
    padding: 0;
    left: auto;
    right: 0;
    top: 0px;
    height: 30px;
    width: 30px;
    text-shadow: none;
    color: #fff;
    z-index: 9;
    opacity: 1;
}
.modal-header .close {
    margin-top: 1px;
}
.modal-header .close {
    margin-top: -2px;
}
.modal-header .close {
    padding: 1rem 1rem;
    margin: -1rem -1rem -1rem auto;
}
.commap-team-popup .modal-header .close::after{height:30px; position:absolute;  width:30px; border-radius:100%; background-color:#273581; top:0; left:0; content:""; z-index:-1;}
</style>

<div class="col-xs-12 col-sm-12 col-md-12">
    <div class="login-container">

        <h5 style="color:#273581 !important">Fill in your business details</h5>
		<div class="alert alert-red mobile-exisits-msg" style="display:none;">
			<ul class="list-unstyled">
				<li class="mobile-err-msg"></li>
			</ul>
		</div>
        @include('layouts_front.error')
        <form action="{{ route('register') }}" method="POST" id="msform">
            <input type="hidden" name="pricing_plan_id" value="{{request()->get('pricing_plan_id')}}">
            <input type="hidden" name="credit_report_type" value="{{request()->get('credit_report_type')}}">
            @csrf
            <fieldset>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group form-group-default" id="emailGroup">
                            <label>Name*</label>
                            <div class="controls">
                                <input type="text" name="name" id="name" value="{{ old('name') }}" placeholder="Person Name" class="form-control" minlength="3" required maxlength="{{General::maxlength('name')}}" onblur="trimIt(this);">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <!--<div class="country-code">
                            <div class="form-group form-group-default" id="emailGroup">
                                <label class="lab-country">Country code*</label>
                                <div class="controls">
                                    <select name="country_code" id="country_code"  placeholder="Country code" class="form-control" required>
                                        @if($countriePhonecodes->count())
                                        @foreach($countriePhonecodes as $countriePhonecode)
                                        <option value="{{$countriePhonecode->phonecode}}" {{old('country_code','91')==$countriePhonecode->phonecode ? 'selected' : '' }} {{ $countriePhonecode->phonecode!=91 ? 'disabled' : '' }}>+{{$countriePhonecode->phonecode}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                        </div>-->
                        <div class="mobile-number111">
                            <div class="form-group form-group-default" id="emailGroup">
                                <label>Mobile Number*</label>
                                <div class="controls">
                                    <input type="tel" name="mobile_number" id="mobile_number" value="{{ old('mobile_number') }}" placeholder="Mobile Number" class="form-control number" required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-group-default" id="emailGroup">
                            <label>Email*</label>
                            <div class="controls">
                                <input type="email" name="email" value="{{ old('email') }}" placeholder="E-mail" id="email" class="form-control Email_Validation" required maxlength="{{General::maxlength('email')}}" autocomplete="email" autofocus>
                            </div>
                            
                        </div>
                        <label id="error_msg" style="color:red;"></label>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-group-default" id="emailGroup">
                            <label>Organization/Business Name*</label>
                            <div class="controls">
                                <input type="text" name="business_name" id="business_name" value="{{ old('business_name') }}" placeholder="Organization/Business Name" class="form-control" required maxlength="{{General::maxlength('name')}}" >
                            </div>
                        </div><br>

                    </div>
                    <div class="col-md-6">
                        <div class="form-group form-group-default" id="emailGroup">
                            <label>Business Type*</label>
                            <div class="controls">
                                <select name="user_type" id="user_type"  placeholder="Business Type" class="form-control" required>
                                    <option value="">Select</option>
                                    @foreach($userTypes as $userType)
                                    <option value="{{$userType->id}}" {{old('user_type')==$userType->id ? 'selected' : '' }}>{{$userType->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <!--<div class="col-md-6">
                        <div class="form-group form-group-default" id="type_of_business_div" style="display:none">
                            <label>Type of Business</label>
                            <div class="controls">
                                <input type="text" name="type_of_business" id="type_of_business" value="{{ old('type_of_business') }}" placeholder="Please specify type of business" class="form-control">
                            </div>

                        </div>
                    </div>-->

					<!--<div class="col-md-6 col-md-offset-3">-->
					<div class="col-md-6 append-others-business-type">
					<!--<b><u><a href="Javascript:void" id="verify_offer_code" style="font-size:12px;">Verify Code</a></u></b>-->
                            <div class="form-group form-group-default" id="emailGroup">
                            <label>Referral code</label>
                            <div class="controls">

                                <input type="text" name="offer_code" id="offer_code" value="{{ old('offer_code') }}" placeholder="Referral code (optional) " class="form-control"   maxlength="20" onkeyup='verifyOfferCode(this);' onBlur='verifyOfferCode(this);'>
								<label id="offer_code-error" class="error" for="offer_code"></label>
                            </div>
                        </div>

                       <img src="{{asset('front_new/images/loader.gif')}}" class="verifycode-loader" height="34px" width="35px" style="display:none;">
					   <span id="vc_res_msg"></span>
                    </div>

					<div class="col-md-12">

                            <!--<input type="checkbox" class="form-check-input" name="agree_terms" id="ihavreadage">-->
                            <label class="form-check-label" for="ihavreadage"><p style="font-size: 14px;font-family: 'Rubik', sans-serif !important;text-transform: none;font-weight: 500;color: #000">I understand that by clicking on Register, I agree to Recordent's
                                <a href="{{route('end-user-license-agreement')}}" target="_blank">End User License Agreement</a>
                            </p></label>

                    </div>
                </div>



            	<!--<input type="button" name="next" class="next action-button float-right" style="margin: 0; " value="Next" />-->
				<div style="display: flex; justify-content: center;">
				<button type="button"  class="btn btn-block login-button float-right" value="Register">
                    <span class="signingin hidden"><span class="voyager-refresh"></span>...</span>
                    <span class="signin">Register</span>
                </button>
				</div>
				<span class="" style="font-size: 14px;font-family: 'Rubik', sans-serif !important;text-transform: none;font-weight: 500;color: #000;">Already a member ? <a href="{{config('app.url')}}admin/login" class="bright-link " style="font-size:15px; color: #273581 !important;font-weight: 500;"> Login</a>

				</span>
            </fieldset>
			<!--Hidden fields to fetch name in controller-->
			<input type="hidden" name="country" value="101"/>
			<input type="hidden" name="password" value=""/>
			<input type="hidden" name="address" value=""/>
			<input type="hidden" name="city" value=""/>
			<input type="hidden" name="state" value=""/>
			<input type="hidden" name="branch_name" value=""/>
			<input type="hidden" name="gstin_udise" value=""/>
			<input type="hidden" name="country_code" value="91"/>
			<input type="hidden" name="offer_code_status" id="offer_code_status" value="0"/>
      <input type="hidden" name="campaign_id" value="{{$campaignsIdValue}}"/>
			<!--Hidden fields to fetch name in controller ends here---->
        </form>
        <div style="clear:both"></div>
    </div>
        <!-- .login-container -->
</div>
<select id="maincity" style="display: none">
    @if($cities->count())
        @foreach($cities as $city)
            <option data-state-id="{{$city->state_id}}" value="{{$city->id}}">{{$city->name}}</option>
        @endforeach
    @endif
</select>

	<!--Verify OTP POPUP---->
<div class="modal commap-team-popup center-screen" id="emailverify" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title" id="offerCodeApiResponse" style="text-align:center;font-size:22px;"></h3>
            <button type="button" id="closeOtpVerifyBtn" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body111">
            <div class="row111">
            <div class="col-md-12">
                <div class="panel panel-bordered111">
                    <div class="panel-body">
                    	<div class="alert alert-success hide alert-email alert-success-email" role="alert"></div>
                    	<div class="alert alert-danger hide alert-email alert-danger-email" role="alert"></div>
                      <br>
                    	<form action="" name="submit_otp_form_register" id="submit-otp-form-register" method="POST">
							@csrf

								<div class="col-md-6">
                                    <center>
		                            <div class="form-group parent">
										<!--<label for="contact_phone">OTP</label>-->
										<input type="tel" class="form-control" style="border: 1px solid #ccc;width:150px;margin-top:-20px;" name="otpregister" value="" placeholder="Please enter OTP" onkeyup='removeDisable(this);'>
									</div>
                                </center>
								</div>
                            <div class="col-md-12">
                            <div class="modal-header" style="margin-bottom:25px;padding-top:0px;">
								<h3 class="modal-title otp-txt" style="text-align:center;font-size:14px;">Verify OTP</h3>
								</div>
							<center>
								<div class="form-action">
									<button type="button" class="btn btn-primary rm-btn-disbld" id="submit-otp-button-register" disabled>Verify</button>
								</div>
                            </center>

								<a href="Javascript:void" style="display: block;width: 100%;float: left;padding-top: 10px;" class="bright-link resendLinkTimer" id="resendOtpEmail">Didn't get OTP? Send again</a>
                <center>
								<span id="timerBlock"></span>
                </center>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
          </div>
        </div>
      </div>
    </div>

	<!--Verify OTP POPUP ends here---->




    <!-- .login-sidebar -->
<script src="{{asset('js/jquery.validate.min.js')}}"></script>
<script>
var timerId = null;
function resendOtpTimer() {
var timeLeft = 30;
		var elem = document.getElementById('timerBlock');
		timerId = setInterval(function(){
			if (timeLeft == -1) {
				clearInterval(timerId);
				$("#resendOtpEmail").show();
				elem.innerHTML = "";
			} else {
				if(timeLeft<10){
					timeLeft = "0" + timeLeft;
				}
				elem.innerHTML = 'Resend OTP in '+ timeLeft + " Sec"
				timeLeft--;
			}
		}, 1000);
		}

		$('.commap-team-popup input').on('keypress', function(e) {
				if(e.keyCode == 13) {
				var form = $("#submit-otp-form-register");
				var otp = form.find('input[name=otpregister]').val();
				if(otp.toString().length ==6) {
				$("#submit-otp-button-register").trigger('click');
				return false;
				}
				return false;
			}
		});

		$("#resendOtpEmail").on("click",function(){
			getOtpResend($('#mobile_number').val());
			$("#resendOtpEmail").hide();
	});

	$("#closeOtpVerifyBtn").on('click',function(){
		var elem = document.getElementById('timerBlock');
		elem.innerHTML = '';
		clearInterval(timerId);
		//location.reload();
	});


function getOtpResend(mobilenumber) {
		resendOtpTimer();
		$.ajax({
	         method: 'post',
	         url: "{{route('register.getotp')}}",
	         headers: {
	           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	         },
	         data: {
	           mobile_number: mobilenumber,
	           _token: $('meta[name="csrf-token"]').attr('content')
	         }
        });
	}



function trimIt(currentElement){
    $(currentElement).val(currentElement.value.trim());
}
function removeDisable(e) {
		var otpValue = e.value;
		if(otpValue.toString().length ==6) {
			$(".rm-btn-disbld").removeAttr('disabled');
		}else {
			$(".rm-btn-disbld").attr('disabled','disabled');
		}
	}

$(document).ready(function(){

    $.validator.addMethod("alphaspace", function(value, element) {
        return this.optional(element) || /^([a-zA-Z]+\s?)*$/i.test(value);
    }, "Only alphabet and space allowed.");

    $.validator.addMethod("alphanumdashspace", function(value, element) {
        return this.optional(element) || /^[a-z0-9\- ]+$/i.test(value);
    }, "Only alphabet,number,dash and space allowed.");

    $.validator.addMethod("mobile_number_india", function(value, element) {
        return this.optional(element) || /^[6789]\d{9}$/i.test(value);
    }, "Please enter a valid number.");
    $.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
 });
    var validator = $("#msform").validate(

      {
        rules: {
           /* name: {
              maxlength: {{General::maxlength('name')}},
              alphaspace:true
            },*/
            name: {
              alphanumdashspace:true,
              maxlength: {{General::maxlength('name')}},
              remote: {
                          url: "/businessname_validate",
                          type: "post",
                data: { business_name:

                  function () { return $("#name").val();
                              }

                 }
                      }
            },
			mobile_number:{
                maxlength:10,
                mobile_number_india:true
            },
            business_name: {
              required: true,
              alphanumdashspace:true,
              remote: {
                          url: "/businessname_validate",
                          type: "post",
                data: { business_name:

                  function () { return $("#business_name").val();
                              }

                 }
                      }
            },
            branch_name: {
              maxlength: 50,
              alphanumdashspace:true
            }

        },
        messages: {
          business_name: {
                    remote:"Business name is not valid"
                  },
                  name: {
                    remote:"Person name is not valid"
                  }
                }
    });

    $("#user_type").on('change',function(){
        $("#type_of_business_div").find('input').val('');
        if($(this).val()==10 || $(this).val()==11){
			$(".append-others-business-type").after('<div class="col-md-6 other-bussiness-type-switch"><div class="form-group form-group-default" id="type_of_business_div"><label>&nbsp;</label><div class="controls"><input type="text" name="type_of_business" id="type_of_business" value="{{ old("type_of_business") }}" placeholder="Please specify Business Type" class="form-control"></div></div></div>');
            //$("#type_of_business_div").show(1);
            $("#type_of_business_div").find('input').attr('required','required');
			$(".hidden_type_business").remove();

        }else{
            //$("#type_of_business_div").hide(1);
			$(".other-bussiness-type-switch").remove();
            $("#type_of_business_div").find('input').removeAttr('required');
			$(".append-others-business-type").after('<input type="hidden" name="type_of_business" id="type_of_business" class="hidden_type_business"/>');
        }
    });


    $("button[type=button].login-button").on('click',function(e){
        e.preventDefault();
        if(!$("#msform").valid()){
            validator.focusInvalid();
            return false;
        }
		$(".verify-loader").css("display","block");
		$.ajax({
	         method: 'post',
	         url: "{{route('register.checkmobile')}}",
	         headers: {
	           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	         },
	         data: {
	           mobile_number: $("#mobile_number").val(),
             email: $("#email").val(),
	           _token: $('meta[name="csrf-token"]').attr('content')
	         }
        }).then(function (response) {
			//$(".verify-loader,.mobile-exisits-msg").css("display","none");
			getOtpVerify($("#mobile_number").val());
        }).fail(function (data) {
			verifyMobileOtpLoader(data.responseJSON.message);
        });


		/*Offer code verify from api*/

		var offerCodeValue = $("#offer_code").val();
		  var _token = "<?=csrf_token(); ?>";
		  if(/^ONE@/i.test(offerCodeValue)) {
			  $('#offer_code-error').html('');
		  $.ajax({
               type:'GET',
               url:'/verifyoffercode',
               data:{_token:_token,offercode:offerCodeValue},
               success:function(data) {
				   $('.verifycode-loader').css('display','none');

				   if(data!="" && data!=0) {
				   var respoonseData = JSON.parse(data);
				   var offerStatus = respoonseData.status ? 1:0;
				   $('#offer_code_status').val(offerStatus);
				   if(offerStatus) {
					   //$('#offerCodeApiResponse').addClass('alert alert-success alert-email alert-success-email');
					   $('#offerCodeApiResponse').css('color','green');
					   $('#offerCodeApiResponse').html("Congratulations, you've earned a referral reward!");
				   } else {
						//$('#offerCodeApiResponse').addClass('alert alert-danger alert-email alert-danger-email');
						$('#offerCodeApiResponse').css('color','red');
					   $('#offerCodeApiResponse').html('Referral code is invalid');

				   }

			   } else {
					   $('#offerCodeApiResponse').css('color','red');
					   $('#offerCodeApiResponse').html('Something wrong with referral code, please try again.');
				}

               }
            });
		  } else { $('#offerCodeApiResponse').html('');
			  $('#offer_code-error').html('Please enter a valid Offer Code.');
		  }

		/*Offer code verify from api ends here*/

    });


	function getOtpVerify(mobilenumber) {
		$.ajax({
	         method: 'post',
	         url: "{{route('register.getotp')}}",
	         headers: {
	           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	         },
	         data: {
	           mobile_number: mobilenumber,
	           _token: $('meta[name="csrf-token"]').attr('content')
	         }
        }).then(function (response) {
		  $("#resendOtpEmail").hide();
		  $(".otp-txt").html("OTP sent to "+$("#mobile_number").val());
		  $('#emailverify').modal('show');
		  resendOtpTimer();
		  $(".verify-loader,.mobile-exisits-msg").css("display","none");
        }).fail(function (data) {
          //$('.alert.alert-danger').html(data.responseJSON.message);
          //$('.alert.alert-danger').removeClass('hide');
		  verifyMobileOtpLoader(data.responseJSON.message);
        });
	}

	function verifyMobileOtpLoader(msg) {
		$(".mobile-exisits-msg").css("display","block");
			$(".verify-loader").css("display","none");
			$('.mobile-err-msg').html(msg);
			$('html, body').animate({
				scrollTop: $(".verify-loader").offset().top
			}, 2000);

	}



  // var validator = $('#msform').validate({
	// 	ignore: '',
  //       rules: {
  //   business_name: {
	// 			required: true,
	// 			alphanumdashspace:true,
	// 			maxlength : {{General::maxlength('name')}},
	// 			onkeyup: false,
	// 			remote: {
  //                   url: "/businessname_validate",
  //                   type: "GET",
	// 				data: { business_name:$("#business_name").val()}
  //                }
	// 		}
  //   },
  //     messages: {
  //       business_name: {
	// 		remote:"Business name is not valid"
	// 	}

  //   }

  //   });

	/*var validatorSubmitOtpForm = $("#submit-otp-form-register").validate({
        rules: {
            otpregister:{
            	required:true,
            }
        },
		messages: {
			otpregister: {
				required:"Please enter otp"
			}
		}
    });*/



	$("#submit-otp-button-register").on('click',function(e){
		if(!$("#submit-otp-form-register").valid()){
            validatorSubmitOtpForm.focusInvalid();
            return false;
        }
        var thisButton = $(this);
        thisButton.attr('disabled','disabled');
        var form =$("#submit-otp-form-register");
        var mobile_number = $('#mobile_number').val();
        var otp = form.find('input[name=otpregister]').val();

        $.ajax({
	         method: 'post',
	         url: "{{route('register.verifyotp')}}",
	         headers: {
	           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	         },
	         data: {
	           mobile_number: mobile_number,
	           otp: otp,
	           _token: $('meta[name="csrf-token"]').attr('content')
	         }
        }).then(function (response) {
          $('.alert.alert-success').html(response.message);
          $('.alert.alert-success').removeClass('hide');
		  $('.alert.alert-danger').hide();
          thisButton.removeAttr('disabled');
          form.addClass('hide');
          //$("#get-otp-form").removeClass('hide');
		  $("#msform").submit();
        }).fail(function (data) {
          $('.alert.alert-danger').html(data.responseJSON.message);
          $('.alert.alert-danger').removeClass('hide');
          thisButton.removeAttr('disabled');

        });
      });



	  $('#verify_offer_code').on('click', function(){
		  var offerCodeValue = $("#offer_code").val();
		  var _token = "<?=csrf_token(); ?>";
		  if(/^ONE@/i.test(offerCodeValue)) {
			  $('.verifycode-loader').css('display','block');
			  $('#offer_code-error').html('');
			//$('#verify_offer_code').css('display','block');
		  $.ajax({
               type:'GET',
               url:'/verifyoffercode',
               data:{_token:_token,offercode:offerCodeValue},
               success:function(data) {
				   $('.verifycode-loader').css('display','none');
				   var respoonseData = JSON.parse(data);
				   var offerStatus = respoonseData.status ? 1:0;
				   $('#offer_code_status').val(offerStatus);
				   if(offerStatus) {
						$('#vc_res_msg').html('Code is valid');
						$('#vc_res_msg').css('color','green');
				   } else {
					   $('#vc_res_msg').html('Code is invalid');
						$('#vc_res_msg').css('color','red');
				   }

               }
            });
		  } else {
			  $('#offer_code-error').html('Please enter a valid Referral Code.');
		  }
	  });
});


function verifyOfferCode(e) {
	if(e.value!="") {
		if(/^ONE@/i.test(e.value)) {
			$('#offer_code-error').css('display','none');
			$('#offer_code-error').html('');
		} else {
		$('#offer_code-error').css('display','block');
			$('#offer_code-error').html('Please enter a valid Referral Code.');
		}
	} else {
		$('#offer_code-error').css('display','none');
		$('#offer_code-error').html('');
	}
}

$(".Email_Validation").on("change",function(){  
      var emailid=$("#email").val();
      check_mail_api_validation(emailid);
})



function  check_mail_api_validation(emailid)
{
  var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
          if(regex.test(emailid))
          {
        $.ajax({
	         method: 'post',
	         url: "{{route('register.verifyemaiid')}}",
	         headers: {
	           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	         },
	         data: {
            emailid: emailid,
	           _token: $('meta[name="csrf-token"]').attr('content')
	         }
        }).then(function (response) {

         if(response.status)
         {
          $("#error_msg").html('');
         $("#error_msg").css("display:'none';");  
         }else{
          
          $("#error_msg").css("display:'';");
          $("#error_msg").html(response.message);
         }
        }).fail(function (data) {
         
        });
      }
}

localStorage.removeItem("email");
localStorage.removeItem("password_confirmation");
localStorage.removeItem("password");
localStorage.removeItem("emailsent");

</script>
@endsection
