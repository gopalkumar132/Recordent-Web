@extends('voyager::master')

@section('page_title', __('voyager::generic.create').' Update Profile')

@section('page_header')

	<style>
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
    	label.error{position:static;}
    	.update-profile-heading .page-title {
        display: block;
        margin: 3px 0px 15px 0px;
        padding: 12px 0px 15px 0px;
      }
      .update-profile-heading {
        text-align: center;
      }

      .update-profile-heading p {
         font-weight: 600;
       }
       input[type="text"],textarea{text-transform: uppercase};

      .modal {
        display: none;
        position: fixed;
        z-index: 1;
        padding-top: 100px;
        width: 100%;
        height: 100%;
      }

      .modal-content {
        width: 100%;
        margin-top: 25%;
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

      @media (max-width:580px) {
       .modal-content {
         width: 80%;
         height: 100%;
         margin-top: 30%;
         margin-left: 8%;
         word-break: break-all;
       }
      }
      label.success{color:green !important;}
	</style>
	<div class="update-profile-heading">
    <h1 class="page-title">
        Update Profile
    </h1>
	  <p>Please update the below details to complete your profile</p>
	</div>

	@if(session()->has('success'))
	<div class="alert alert-success">
		{{ session()->get('success') }}
	</div>
	@endif

    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
             @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
             @endforeach
            </ul>
        </div>
    @endif
@stop
@section('content')
<?php //var_dump(Auth::user()->email);
//dd(Auth::user()->type_of_business);
 ?>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<div class="page-content container-fluid">
 <div class="row">
  <div class="col-md-12">
   <div class="panel panel-bordered">
    <div class="submitdues-mainbody">
     <div class="panel-body">
      <form action="{{route('update-profile-store')}}" method="POST" id="edit_profile_form">
        @csrf
				<?php
				$emailReadonly = $passwordReadonly = $passwordValue = "";
				if(Auth::user()->email_verified_at!="" || Auth::user()->email_sent_at!="") {
						$emailReadonly = "readonly";
				}
				if(Auth::user()->password!="") {
						$passwordReadonly = "readonly";
						$passwordValue = "******";
				}

				?>

        <div class="form-group form-group-default col-md-12" id="emailGroup">
         <div class="col-md-2">Email*</div>
					 <!--<div class="controls col-md-8">
            <input type="text" name="email" id="email" value="{{ old('email',Auth::user()->email) }}" placeholder="E-mail" class="form-control email" {{$emailReadonly}} autocomplete="off" onkeyup='saveValue(this);' onBlur='sendVerifyEmail(this);' onkeypress='sendVerifyEmailPress(this);' maxlength="50">
								<label id="email-error-custom" class="error"></label>
           </div>-->
					 <div class="controls col-md-8">
             <!-- <input type="text" name="email" id="email" value="{{ old('email',Auth::user()->email) }}" placeholder="E-mail" class="form-control email" {{$emailReadonly}} autocomplete="off" onkeyup='saveValue(this);' onBlur='sendVerifyEmail(this);' onkeypress='sendVerifyEmailPress(this);' maxlength="{{General::maxlength('email')}}"> -->
						 <input type="text" name="email" id="email" value="{{ old('email',Auth::user()->email) }}" placeholder="E-mail" class="form-control email" {{$emailReadonly}} autocomplete="off"  maxlength="{{General::maxlength('email')}}">
						 <label id="email-error-custom" class="success"></label>
          </div>

          <div class="col-md-2">
					 <?php //if(Auth::user()->email_sent_at=="") { ?>
						<!--<b><u><a href="Javascript:void" id="verify_email" style="pointer-events:none;">Verify Email</a></u></b>
							<img src="{{asset('front_new/images/loader.gif')}}" class="verify-email-loader" height="34px" width="35px" style="display:none;">-->
					 <?php //} ?>

					 <?php if(Auth::user()->email!="" && Auth::user()->email_verified_at!="") { ?>
						<i class="fa fa-check fa-2x" style="color:green;" aria-hidden="true"></i>
					 <?php } ?>
					</div>
					<input type="hidden" id="emailexists" value="{{Auth::user()->email}}">
					<input type="hidden" id="emailverified" value="{{Auth::user()->email_verified_at}}">
        </div>

				<div class="form-group form-group-default col-md-12">
         <div class="col-md-2">Mobile</div>
				  <div class="controls col-md-8">
           <input type="text" name="mobile_number" id="mobile_number" value="{{ old('mobile_number',Auth::user()->mobile_number) }}" readonly placeholder="Mobile" class="form-control" maxlength="10">
          </div>

         <div class="col-md-2">
					 <?php if(Auth::user()->mobile_number!="" && Auth::user()->mobile_verified_at!="") { ?>
						<i class="fa fa-check fa-2x" style="color:green;" aria-hidden="true"></i>
					 <?php } else { ?>
					 <a href="" data-toggle="modal" data-target="#mobileverify"><button type="button" class="btn btn-primary btn-blue">Verify</button></a>
					<?php }?>
				 </div>
        </div>

        <div class="form-group form-group-default col-md-12">
         <div class="col-md-2">Password*</div>
				  <div class="controls col-md-10">
           <input type="password" name="password" id="password" autocomplete="new-password" placeholder="Password" class="form-control" style="text-transform: none !important;" value="{{ old('password',$passwordValue) }}" onkeyup='passwordValidator(this);' onfocus="showPassModal(this)" onfocusout="hidePassModal()">
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
				 <input type="hidden" id="passwordexists" value="{{Auth::user()->password}}">
        </div>

				<!--<div class="col-md-6">
								<div class="form-group">
									<label for="contact_phone">Person Name*</label>
									<input type="text" class="form-control" name="person_name" value="{{old('person_name')}}" placeholder="Person Name" required onblur="trimIt(this);">
								</div>
                            </div>-->
				<div class="form-group form-group-default col-md-12">
         <div class="col-md-2">Confirm Password*</div>
					<div class="controls col-md-10">
           <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" class="form-control" value="{{old('password_confirmation',$passwordValue)}}" style="text-transform: none !important;" onkeyup='saveValue(this);'>
            <i class="fa fa-eye field-icon" onclick="myFunctionConfirm()"></i>
          </div>
        </div>

        <div class="form-group form-group-default col-md-12">
         <div class="col-md-2">Business ID<span id="compid_mand">*</span></div>
				  <div class="controls col-md-10">
           <select class="form-control" id="company_id" name="company_id" required >
            <option value="">Select</option>
						 @if(count($company_types))
						  @foreach($company_types as $ckey=>$ctype)
							 <option value="{{$ckey}}" {{old('company_id',Auth::user()->company_type)==$ckey ? 'selected' : '' }}>{{$ctype}}</option>
							@endforeach
						 @endif
           </select>
					</div>
        </div>

        <div id="company_id_section"style="display:block;">
				 <div class="form-group form-group-default col-md-12">
          <div class="col-md-2" id="company_id_type">GSTIN/PAN</div>
					 <div class="controls company-type-class" id="display__companyid_err">
            <input type="text" name="gstin" id="gstin" value="{{ old('gstin',Auth::user()->gstin_udise) }}" placeholder="E-mail" maxlength="15" class="form-control company_type_dynamic" onkeyup='removeCustomError(this);'>
						<label id="gstin_error" class="error"></label>
						<input type="hidden" id="gstin_masters_error" value="">
           </div>

					 <div class="gstin-verify col-md-2" style="display:none;">
						<?php if(Auth::user()->gstin_verified_at!="") {
							$disableVerify = "disabled";
							$gstinVerifyReadonly = "readonly";
							$gstbtnverify = 1;
						} else {
							$disableVerify = "";
							$gstinVerifyReadonly = "";
							$gstbtnverify="";
						}?>
						<input type="hidden" id="verifiy_gstin" value="{{$gstbtnverify}}">
						<button type="button" {{$disableVerify}} class="btn btn-primary btn-sm btn-blue" id="verify-gstin-btn">Verify</button>
						<img src="{{asset('front_new/images/loader.gif')}}" class="gstin-loader" height="34px" width="35px" style="display:none;">
					 </div>
        </div>

        <div class="form-group form-group-default col-md-12">
         <div class="col-md-2">Business/Organisation Name</div>
					<div class="controls col-md-10">
           <input type="text" name="business_name" id="business_name" value=""  required placeholder="Business/Organisation Name" class="form-control" maxlength="{{General::maxlength('name')}}">
          </div>
        </div>
		<div class="form-group form-group-default col-md-12" >
         <div class="col-md-2">Legal Business Name</div>
					<div class="controls col-md-10">
           <input type="text" name="legal_business_name" id="legal_business_name" value="{{ old('business_name',Auth::user()->business_name) }}" {{$gstinVerifyReadonly}}  placeholder="Legal Business Name" class="form-control" maxlength="{{General::maxlength('name')}}">
          </div>
        </div>
        <div class="form-group form-group-default col-md-12" id="emailGroup">
          <div class="col-md-2">Business Short Name</div>
					<div class="controls col-md-10">
            <input type="text" name="business_short" id="business_short" value="{{ old('business_short',Auth::user()->business_short) }}" placeholder="Business Short Name" class="form-control" maxlength="50">
         </div>
        </div>

         <div class="form-group form-group-default col-md-12 others-type">
            <div class="col-md-2">Sectors*</div>
            <div class="controls col-md-10">
             <select name="sector_id" id="sector_id"  placeholder="Select Sector" class="form-control">
              <option value="">Select</option>
               @if($sectors->count())
                @foreach($sectors as $sector)
                 <option value="{{$sector->id}}" {{old('sector_id',Auth::user()->sector_id)==$sector->id ? 'selected' : '' }}>{{$sector->name}}</option>
                @endforeach
               @endif
             </select>
            </div>
         </div>

         <input type="hidden" id="sector_type_id_hidden" value="{{Auth::user()->sector_id}}">

         <div class="form-group form-group-default col-md-12" id="type_of_sector_div" style="display:none">
          <div class="col-md-2">Type of Sector</div>
           <div class="controls col-md-10">
             <input type="text" name="type_of_sector" id="type_of_sector" value="{{ old('type_of_sector',Auth::user()->type_of_sector) }}" placeholder="Please specify type of sector" class="form-control">
           </div>
         </div>

				<?php //echo "<pre>"; print_r(Auth::user()); echo "</pre>"; ?>
				<div class="form-group form-group-default col-md-12 others-type">
	       <div class="col-md-2">Business Type*</div>
				  <div class="controls col-md-10">
					 <select name="user_type" id="user_type"  placeholder="Select Sector" class="form-control">
					  <option value="">Select</option>
					   @if($userTypes->count())
						  @foreach($userTypes as $usertype)
							 <option value="{{$usertype->id}}" {{old('user_type',Auth::user()->user_type)==$usertype->id ? 'selected' : '' }}>{{$usertype->name}}</option>
							@endforeach
						 @endif
				   </select>
				  </div>
				 </div>

         <input type="hidden" id="business_type_id_hidden" value="{{Auth::user()->user_type}}">

         <div class="form-group form-group-default col-md-12" id="type_of_business_div" style="display:none">
          <div class="col-md-2">Type of Business</div>
           <div class="controls col-md-10">
             <input type="text" name="type_of_business" id="type_of_business" value="{{ old('type_of_business',Auth::user()->type_of_business) }}" placeholder="Please specify type of business" class="form-control">
           </div>
         </div>
		 <div class="form-group form-group-default col-md-12">
         <div class="col-md-2">Does your company fall under MSME* :</div>
				  <div class="controls col-md-10">

					<div class="form-check">
							<label class="radio-inline">
							<input type="radio" id="msme_yes" name="msme" value="Yes" >Yes
							</label>
							<label class="radio-inline">
							<input type="radio" id="msme_no" name="msme" value="No" checked >No
							</label>
						</div>

          </div>
        </div>
		 <div class="form-group form-group-default col-md-12" id="company_turnoverGroup" >
         <div class="col-md-2">Company Turnover</div>
          <div class="controls col-md-10">
           <select name="company_turnover" id="company_turnover"  class="form-control" >
		   				  <option value="" >Select</option>
						  <option value="less than 5 crores" >Less than 5 crores</option>
						  <option value="greater than 5 and less than 50 crores" >Greater than 5 and less than 50 crores</option>
						  <option value="greater than 50 and less than 250 crores" >Greater than 50 and less than 250 crores</option>
						  <option value="greater than 250 crores" >Greater than 250 crores</option>

           </select>
          </div>
        </div>


	<div class="form-group form-group-default col-md-12">
         <div class="col-md-2">Is your company engaged with exports / imports of Goods and Services* :</div>
				  <div class="controls col-md-10">

					<div class="form-check">
							<label class="radio-inline">
							<input type="radio" id="company_engaged_yes" name="company_engaged" value="Yes" >Yes
							</label>
							<label class="radio-inline">
							<input type="radio" id="company_engaged_no" name="company_engaged" value="No" checked >No
							</label>
						</div>

          </div>
        </div>


				 <div class="form-group form-group-default col-md-12">
          <div class="col-md-2">Address</div>
				   <div class="controls col-md-10">
            <input type="text" name="address" id="address" value="{{ old('address',Auth::user()->address) }}" {{$gstinVerifyReadonly}} placeholder="Address" class="form-control" maxlength="200">
           </div>
         </div>

        <div class="form-group form-group-default col-md-12" id="emailGroup" >
         <div class="col-md-2">State*</div>
          <div class="controls col-md-10">
  				 <?php $stateCheck = Auth::user()->state!=""? Auth::user()->state->id : "";?>
           <select name="state" id="state"  placeholder="Select State" class="form-control">
					  <option value="">Select</option>
						 @foreach($allStates as $kk=>$vv)
						  <option value="{{$kk}}" {{old('state',$stateCheck)==$kk ? 'selected' : '' }}>{{$vv}}</option>
						 @endforeach
           </select>
          </div>
        </div>

        <div class="form-group form-group-default col-md-12" id="emailGroup">
         <div class="col-md-2">City</div>
          <div class="controls col-md-10">
           <select name="city" id="city"  placeholder="Select City" class="form-control">
            <option value="">Select</option>
             {{-- @if($cities->count())
              @foreach($cities as $city)
               <option data-state-id="{{$city->state_id}}" style="display:none" value="{{$city->id}}" {{old('city')==$city->id ? 'selected' : '' }}>{{$city->name}}</option>
              @endforeach
             @endif
             --}}
           </select>
          </div>
        </div>

				<div class="form-group form-group-default col-md-12">
         <div class="col-md-2">Pincode</div>
				  <div class="controls col-md-10">
           <input type="text" name="pincode" id="pincode" value="{{ old('pincode',Auth::user()->pincode) }}" {{$gstinVerifyReadonly}} placeholder="Pincode" class="form-control" maxlength="6">
          </div>
        </div>
			 </div>

       <div class="form-action text-center">
        <button type="submit" class="btn btn-primary btn-blue" id="update-profile-check">Update Profile</button>
       </div>
       <input type="hidden" id="plan_id" name="plan_id" value={{$planId}}>
			 <input type="hidden" id="refferralCode" name="refferralCode" value={{$refferralCode}}>
			 <input type="hidden" id="sentverifymail" value="{{Auth::user()->email_sent_at}}">
      @if(isset($credit_report_type) && $credit_report_type != null)
          <input type="hidden" id="credit_report_type" name="credit_report_type" value={{$credit_report_type}}>
      @endif
     </form>
    </div>
</div></div></div></div></div>


<!--Mobile Verify Model POPUP---->
<div class="modal commap-team-popup" id="mobileverify" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title">Update Mobile</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
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
          <div class="modal-footer">
          </div>
        </div>
      </div>
    </div>

	<!--Mobile Verify Model POPUP ends here---->






	<!--Mobile Verify Model POPUP---->
<div class="modal commap-team-popup" id="mobileverify" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title">Update Mobile</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
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
          <div class="modal-footer">
          </div>
        </div>
      </div>
    </div>

	<!--Mobile Verify Model POPUP ends here---->





	<!--Email Verify Model POPUP---->
<div class="modal commap-team-popup center-screen" id="emailverify" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title email-otp-title">Update Email</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="row">
            <div class="col-md-12">
                <!--<div class="panel panel-bordered">-->
                    <!--<div class="panel-body">-->
                    	<div class="alert alert-success hide alert-email alert-success-email" role="alert"></div>
                    	<div class="alert alert-danger hide alert-email alert-danger-email" role="alert"></div>
						<form action="" name="submit_otp_form_email" id="submit-otp-form-email" method="POST">
							@csrf
	                            <!--<div class="col-md-12">
		                            <div class="form-group">
										<label for="contact_phone">Email</label>
										<input type="email" class="form-control email" name="email-verify" placeholder="Email" required onkeypress="return onlyemail(this,event)">
									</div>
								</div>-->
							<center>
								<div class="col-md-12">
									<div class="form-group">
										<label for="contact_phone">OTP</label>
										<input type="tel" class="form-control" style="width: 150px;" name="otpemail" value="" placeholder="Enter OTP" onkeyup='removeDisable(this);'>
									</div>

								</div>
								</center>
                            <div class="col-md-12">
                            <center>
								<div class="form-action">
									<button type="button" class="btn btn-primary rm-btn-disbld" id="submit-otp-button-email" disabled>SUBMIT</button>
								</div>
							</center>
								<br>
								<a href="Javascript:void" style="display: block;width: 100%;float: left;padding-top: 10px;" class="bright-link" id="resendOtpEmail">Didn't get OTP? Send again</a>
							</div>
						</form>
					<!--</div>-->
				</div>
			<!--</div>-->
		</div>
          </div>

        </div>
      </div>
    </div>

	<!--Email Verify Model POPUP ends here---->






	<select id="maincity" style="display: none">
	<option value="">Select City</option>
    @if($cities->count())
        @foreach($cities as $city)
            <option data-state-id="{{$city->state_id}}" value="{{$city->id}}">{{$city->name}}</option>
        @endforeach
    @endif
</select>
<?php if(Auth::user()->city!="") { $cityIdCheck = Auth::user()->city->id; }else{ $cityIdCheck = ""; } ?>
<input type="hidden" id="dbcity_id" value={{$cityIdCheck}}>
<input type="hidden" id="company_id" value={{Auth::user()->company_type}}>
<?php ?>
		<!--Edit profile popup ends here-->


<script src="{{asset('js/jquery.validate.min.js')}}"></script>

<script language="javascript" type="application/javascript">

// $(document).ready(function(){
// 	$("#company_turnoverGroup").hide();
// });
	$("#msme_yes").on("click",function(){
		$("#company_turnover").prop('required',true);

	});
	$("#msme_no").on("click",function(){
		$("#company_turnover").prop('required',false);
	});

function sendEmailVerify(val) {
	$('.verify-email-loader').css('display','block');
	$('#verify_email').css('display','none');
	$.ajax({
						method: 'get',
						url: "/emailverifymail",
						headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
						data: {
							email: val,
							_token: $('meta[name="csrf-token"]').attr('content')
						}
					}).done(function( data ) {
						//if($(#email-error).length < 1) {
						var errorMsgContent = $("#email-error").text();
						//console.log('errrcontent-------'+errorMsgContent);
						if(errorMsgContent == "") {
						if(data=="true") {
								$("#email-error-custom").css('display','block');
								$("#email-error-custom").html('Verification link has been sent to '+val);
								$('.verify-email-loader').css('display','none');
								$("#email").attr('readonly',true);
								$('#verify_email').hide();
								$('#sentverifymail').val('1');
						}
						}
						//}

					});
}

$(document).ready(function(){
	var businessTypeIdVal = $('#business_type_id_hidden').val();
	if(businessTypeIdVal == 11) {
	 $('#type_of_business_div').css('display','block');
	}

	var emailValidOnLoad = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i.test($('#email').val());
				if(emailValidOnLoad) {
					sendEmailVerify($('#email').val());
				}
});


$('#verify_email').on('click',function(){
	var val = $('#email').val();
	var emailvalid = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i.test(val);
				if(emailvalid) {
					sendEmailVerify(val);
				}

});

//$("#sentverifymail").val(localStorage.getItem('emailsent'));
	/*$(document).ready(function() {
			$(".company_type_dynamic").on('blur',function(){
				var companyTypeId = this.id;
			});
	});*/

	$.validator.addMethod("alphaspace", function(value, element) {
        return this.optional(element) || /^[a-z ]+$/i.test(value);
    }, "Only alphabet and space allowed.");

	$.validator.addMethod("mobile_number_india", function(value, element) {
        return this.optional(element) || /^[6789]\d{9}$/i.test(value);
    }, "Please enter a valid number.");

	$.validator.addMethod("alphanumdashspace", function(value, element) {
		if($("#verifiy_gstin").val()==1) { return true; }
        return this.optional(element) || /^[a-z0-9\- ]+$/i.test(value);
    }, "Only alphabet,number,dash and space allowed.");

  function passwordValidator(e) {
      var passVal = $("#password").val();
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

      if(passInvalidCount == 0) {
        saveValue(e);
      }
  }

  $.validator.addMethod("passwordValidator", function(value, element) {
      return value.match(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/);
    }, "Password Rules not matched");

	$.validator.addMethod("companyidcheck", function(value, element) {
		var elementId = element.id;
		var errMessage = 0;
		//$(this).parent('#display__companyid_err').find('label.error').remove();
		$("#gstin-error-custom").remove();

		if(elementId == "gstin") {
			var gstincheck = /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/i.test(value);
			if(!gstincheck) {
				errMessage++;
				$('#display__companyid_err').append('<label id="gstin-error-custom" class="error">Please enter valid GSTIN.</label>');
			} else {
				$('#gstin-error-custom').css('display','none');
				errMessage--;
			}
		}

		if(elementId == "cpan") {
			var valueToString = value.toString().toUpperCase();
			// var fourthChar = valueToString.charAt(3);
			// var allowedCharsAtFourthPosition = ["C","H","A","B","G","J","L","F","T"];
			//var cpancheck = /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/i.test(value);
			var cpancheck = /^[A-Z]{3}[A|B|C|F|G|H|L|J|P|T]{1}[A-Z]{1}[0-9]{4}[A-Z]{1}$/i.test(value);
			// if(allowedCharsAtFourthPosition.includes(fourthChar) || !cpancheck) {
			if(!cpancheck) {
				errMessage++;
				$('#display__companyid_err').append('<label id="gstin-error-custom" class="error">Please enter valid Business PAN.</label>');
			} else {
				$('#gstin-error-custom').css('display','none');
				errMessage--;
			}
		}

		if(elementId == "cin") {
			if(value.toString().length != 21) {
				errMessage++;
				$('#display__companyid_err').append('<label id="gstin-error-custom" class="error">Please enter valid Company Identification Number.</label>');
			} else {
				errMessage--;
				var cincheck = /^[a-zA-Z0-9]+$/i.test(value);
				if(!cincheck) {
				errMessage++;
				$('#display__companyid_err').append('<label id="gstin-error-custom" class="error">Please enter valid Company Identification Number.</label>');
				} else {
				$('#gstin-error-custom').css('display','none');
				errMessage--;
				}
			}
		}

		if(elementId == "tin") {
			if(value.toString().length != 11) {
				errMessage++;
				$('#display__companyid_err').append('<label id="gstin-error-custom" class="error">Please enter valid Tax Identification Number.</label>');
			} else {
				errMessage--;
				var cincheck = /^[a-zA-Z0-9]+$/i.test(value);
				if(!cincheck) {
				errMessage++;
				$('#display__companyid_err').append('<label id="gstin-error-custom" class="error">Please enter valid Tax Identification Number.</label>');
				} else {
				$('#gstin-error-custom').css('display','none');
				errMessage--;
				}
			}
		}

		if(elementId == "udise") {
			if(value.toString().length != 11) {
				errMessage++;
				$('#display__companyid_err').append('<label id="gstin-error-custom" class="error">Please enter valid UDISE Number.</label>');
			} else {
				errMessage--;
				var cincheck = /^[a-zA-Z0-9]+$/i.test(value);
				if(!cincheck) {
				errMessage++;
				$('#display__companyid_err').append('<label id="gstin-error-custom" class="error">Please enter valid UDISE Number.</label>');
				} else {
				$('#gstin-error-custom').css('display','none');
				errMessage--;
				}
			}
		}

		//console.log("error count----------"+errMessage);
		var error_count_flag = errMessage > 0 ? false:true;
		return error_count_flag;

    }, "");

	$.validator.addClassRules("company_type_dynamic", {
		required: function() {
			if($("#plan_id").val()==2 || $("#plan_id").val()==3 || $("#refferralCode").val()==1) {
				return true;
			} else {
				return false;
			}
		},
		companyidcheck: true,
	});
	var validator = $('#edit_profile_form').validate({
		ignore: '',
        rules: {
			email: {
				required: true,
				email:true,
				minlength : 3,
				maxlength : {{General::maxlength('email')}},
				onkeyup: false,
				remote: {
                    url: "/checkemailexists",
                    type: "GET",
					data: { planid:$("#plan_id").val(), emailverified:$("#emailverified").val(),sentverifymail:$("#sentverifymail").val()}
                 }
			},
			mobile_number:{
          maxlength:10,
          mobile_number_india:true
      },
      password: {
				required: true,
        passwordValidator: true,
        // onkeyup:true
        /*required: {
          depends: 
        }*/
      },
			password_confirmation : {
				minlength : 5,
				equalTo : "#password"
			},
			company_id : {
				required: {
                depends: function(element) {
					if($("#plan_id").val()==2 || $("#plan_id").val()==3 || $("#refferralCode").val()==1) {
                    return $("#company_id").val() == '';
					} else {
						return false;
					}
                }
            }
			},
			user_type : {
				required: {
                depends: function(element) {
                    return $("#user_type").val() == '';
                }
            }
			},
      sector_id : {
        required: {
                depends: function(element) {
                    return $("#sector_id").val() == '';
                }
            }
      },
			state : {
				required: {
                depends: function(element) {
                    return $("#state").val() == '';
                }
            }
			},
			business_name : {
				alphanumdashspace:true,
				required:true,
				maxlength:{{General::maxlength('name')}}
			},
			legal_business_name : {
				alphanumdashspace:true,
				required:true,
				maxlength:{{General::maxlength('name')}}
			}

        },
    messages: {
      company_id: 'Please select Company Id',
  		state: 'Please select State',
  		company_id: 'Please select business type',
  		email: {
  			remote:"Email already exists"
  		},
      password: "Invalid Password",
  		password_confirmation: {
  			equalTo:"Passwords do not match"
  		}
    }
});

	/*$(".company_type_dynamic").rules("add", {
			required:true
			});*/

</script>

<script type="text/javascript">
$(document).ready(function(){

	var emailInputTrim = $("#email,#gstin,#cpan");
emailInputTrim.on('keyup', function(event){
	$(this).val($.trim($(this).val()));
});

	var companyIdTypes = {"gstin":"GSTIN","cpan":"Business PAN","cin":"Company Identification Number","tin":"Tax Identification Number","seln":"Shop and Establishment License Number"};
	//var stateNames = <?php echo '["' . implode('", "', $stateIdNames) . '"]'; ?>
	console.log(stateNames);
	var companyIdVal = $("#company_id").val();
	companyTypeId(companyIdVal);

	$('#company_id').on('change',function(){
		companyTypeId($(this).val());
		$('#gstin_error').html('');
		$('#gstin,#cpan').val('');
	});

	if($("#plan_id").val()==1 && $("#refferralCode").val()==0) { $("#compid_mand").hide(); } else { $("#compid_mand").show(); }

	function companyTypeId(value) {
		//var selectedValue = $(this).val();
		var selectedValue = value;
		var companyTypeField = new String(selectedValue);
		//console.log("companytypefieldchangeeee----------------"+companyTypeField);
		//console.log("selectedvalue----"+companyIdTypes[selectedValue]);
		var isMand = "";
		if($("#plan_id").val()!=1) { var isMand = '*'; }

		$("#company_id_type").html(companyIdTypes[selectedValue]);
		$("#company_id_type").append(isMand);
		$(".company_type_dynamic").attr({'name':selectedValue,'id':selectedValue});
		if(selectedValue == "gstin") {
			$(".company-type-class").removeClass("col-md-10");
			$(".company-type-class").addClass("col-md-8");
			$(".gstin-verify").css("display","block");
		} else {
			$(".company-type-class").removeClass("col-md-8");
			$(".company-type-class").addClass("col-md-10");
			$(".gstin-verify").css("display","none");
		}
		selectedValue !="" ? $("#company_id_section").css("display","block") : $("#company_id_section").css("display","block");
	}


	$("#verify-gstin-btn").on("click",function(){
		var gstinValue = $("#gstin").val();
		$('#gstin-error').html('');
		var gstinPatternCheck =  /^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/i.test(gstinValue);
		if(!gstinPatternCheck) {
		//$("#gstin_error").html("Please enter valid GSTIN");
		} else {
		$("#gstin_error").html("");
		var _token = "<?=csrf_token(); ?>";
		$(".gstin-loader").css("display","block");
		$("#verify-gstin-btn").css("display","none");
		$.ajax({
               type:'GET',
               url:'/getgstinapidata',
               data:{_token:_token,gstin:gstinValue},
               success:function(gstinData) {
				   $(".gstin-loader").css("display","none");
				   $("#verify-gstin-btn").css("display","block");
				   var respoonseData = JSON.parse(gstinData);
				   if(!respoonseData.error) {
					$('#gstin_masters_error').val('');
				$("#gstin_error").html(respoonseData.data);
				   //console.log(respoonseData.data);
				   //return false;
				   var result = respoonseData.data
				   var addr = result.pradr.addr;
				   var completeAddr = addr.bnm+" "+addr.bno+" "+addr.flno+" "+addr.st+" "+addr.loc;
				   var gstinCharAt = gstinValue.charAt(5);
				   if(gstinCharAt == "P" || gstinCharAt == "p") {
					   $("#legal_business_name").val(result.tradeNam);
				   } else {
					   $("#legal_business_name").val(result.lgnm);
				   }
				   $("#address").val(completeAddr);
				   $("#pincode").val(addr.pncd);
				   //if(!respoonseData.error) {
					   window.location.reload();
					   $('#verify-gstin-btn').attr('disabled', 'disabled');
					   $('#legal_business_name').attr('readonly', true);
					   $('#address').attr('readonly', true);
					   $('#pincode').attr('readonly', true);
				   } else {
					   $('#gstin_masters_error').val(respoonseData.data);
					   $('#gstin_error').css('display','block');
					   $("#gstin_error").html(respoonseData.data);
				   }

               }
            });

		}
	});


	if($("#state").val()!=''){
        //var oldCity = $("#oldCity").html();
		var oldCity = $("#dbcity_id").val();
        var selected = '';
        $("#city").find('option').remove();
        //$("#city").append('<option value="">Select</option>');
        var stateId =  $("#state").val();
		$("#city").append('<option value="">Select City</option>');
        $("#maincity option").each(function(){
            if($(this).data('state-id')==stateId){
                var cityId = $(this).val();
                if(oldCity==cityId) { selected= 'selected';}else{selected= ''}

                $("#city").append('<option value="'+$(this).val()+'" '+selected+'>'+$(this).text()+'</option>');
            }
        });
    }
    $("#state").on('change',function(){
        $("#city").find('option').remove();
        $("#city").append('<option value="">Select</option>');

        if($("#state").val()!=''){
            var stateId =  $("#state").val();
            $("#maincity option").each(function(){
                if($(this).data('state-id')==stateId){
                    $("#city").append('<option value="'+$(this).val()+'">'+$(this).text()+'</option>');
                }
            });
        }
    });

	$("#user_type").on('change',function(){
        $("#type_of_business_div").find('input').val('');
        // if($(this).val()==10 || $(this).val()==11){
        if($('#user_type :selected').text()== "Others"){
            $("#type_of_business_div").show(1);
            $("#type_of_business_div").find('input').attr('required','required');

        }else{
            $("#type_of_business_div").hide(1);
            //$("#type_of_business_div").find('input').removeAttr('required');
        }
    });



$("#sector_id").on('change',function(){
        $("#type_of_sector_div").find('input').val('');
        // if($(this).val()==10 || $(this).val()==11){
        if($('#sector_id :selected').text()== "Others"){
            $("#type_of_sector_div").show(1);
            $("#type_of_sector_div").find('input').attr('required','required');
        }else{
            $("#type_of_sector_div").hide(1);
        }
    });


});



</script>


<!--------Mobile Verify popup------>
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
<!--Mobile Verify popup end here---->


<!--------Email Verify popup------>
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

	/*GSTIN Verify Error Handling*/
	$('#update-profile-check').on('click',function(e){
        e.preventDefault();
		var companyIdName = $('#company_id').find(":selected").val();
		//alert(companyIdName); return false;
        if($('#edit_profile_form').valid()){

			var gstinError = $('#gstin_masters_error').val();
            if(companyIdName == 'gstin') {
			if(gstinError != '') {
			$('#gstin_error').html(gstinError);
			$('#gstin_error').css('display','block');
			$('html, body').animate({
				scrollTop: $('#gstin_error').offset().top
			}, 2000);
            return false;
			} else if($('#verifiy_gstin').val() == "") {
			$('#gstin_error').html('Please verify GSTIN');
			$('#gstin_error').css('display','block');
			$('html, body').animate({
				scrollTop: $('#gstin_error').offset().top
			}, 2000);
            return false;
			} else {
				sendEmailVerify($('#email').val());
				$('#edit_profile_form').submit();
			} }
			sendEmailVerify($('#email').val());
			$('#edit_profile_form').submit();
        }

    });
	/*GSTIN verify Error Handling ends here*/


	/*commented because email otp is not using*/
  /*var validatorGetOtpForm = $("#get-otp-form-email").validate();
  var validatorSubmitOtpForm = $("#submit_otp_form_email").validate({
        rules: {
            otpemail:{
            	required:true,
            }
        },
		messages: {
			otpemail: {
				required:"Please enter otp"
			}
		}
    });

	$("#get-otp-button-email").on('click',function(e){
		if(!$("#get-otp-form-email").valid()){
            validatorGetOtpForm.focusInvalid();
            return false;
        }
        var thisButton = $(this);
        thisButton.attr('disabled','disabled');
        var form =$("#get-otp-form-email");
        var email = form.find('input[name=email-verify]').val();

        $('.alert-email').addClass("hide");
        $('.alert-email').html('');


      });*/

	/*$("#submit-otp-button-email").on('click',function(e){
		if(!$("#submit-otp-form-email").valid()){
            validatorSubmitOtpForm.focusInvalid();
            return false;
        }
        var thisButton = $(this);
        thisButton.attr('disabled','disabled');
        var form =$("#submit-otp-form-email");
        //var email = form.find('input[name=email-verify]').val();
		var email = $('#email').val();
        var otp = form.find('input[name=otpemail]').val();

        $('.alert-email').addClass("hide");
        $('.alert-email').html('');

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
          form.find('input[name=email-verify]').val('');
          form.find('input[name=otpemail]').val('');
          $('.alert-email.alert-success-email').html(response.message);
          $('.alert-email.alert-success-email').removeClass('hide');
          thisButton.removeAttr('disabled');
          form.addClass('hide');
          $("#get-otp-form-email").removeClass('hide');
          $("#get-otp-form-email").find('input[name=email-verify]').val('');
		  window.location.reload();
        }).fail(function (data) {
          $('.alert-email.alert-danger-email').html(data.responseJSON.message);
          $('.alert-email.alert-danger-email').removeClass('hide');
          thisButton.removeAttr('disabled');

        });
      });*/
	/*commented because email otp is not using ends here*/
	/*Commented because not used*/
	/*$("#submit-otp-form-email #resendOtpEmail").on('click',function(e){
        e.preventDefault();
        $('.alert-email').addClass("hide");
        $('.alert-email').html('');
        $("#get-otp-form-email").removeClass('hide');
        $("#submit-otp-form-email").addClass('hide');
        $("#submit-otp-form-email").find('input[name=email-verify]').val('');
        $("#submit-otp-form-email").find('input[name=otpemail]').val('');
      });*/
	  /*Commented because not used ends here*/


});


</script>
<!--Email Verify popup end here---->

<script type="text/javascript">
function sendVerifyEmailPress(e) {
	var keyCode = e.keyCode || e.which;
	if (keyCode == 9) {
    e.preventDefault();
	sendVerifyEmail(e);
  }
}



		function sendVerifyEmail(e) {
			var val = e.value;
			var emailvalid = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i.test(val);
			if(emailvalid) {
				//if($('#email-error').length < 1) {
					if($('#email-error').text()=="") {
					$('#verify_email').css('pointer-events','');
					sendEmailVerify(val);
				}
			}
		}



function removeCustomError(e) {
	var id = e.id;
	id=='gstin' ? $('#gstin_error').css('display','none') : '';
}
//var errorMsgContent = $("#email-error").text();

function sendVerifyEmail(e) {
	var val = e.value;
	var emailvalid = /^\b[A-Z0-9._%-]+@[A-Z0-9.-]+\.[A-Z]{2,4}\b$/i.test(val);
				if(emailvalid) {

					$.ajax({
						method: 'get',
						url: "/emailverifymail",
						headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
						data: {
							planid:$("#plan_id").val(),
							emailverified:$("#emailverified").val(),
							email: val,
							sentverifymail:$("#sentverifymail").val(),
							_token: $('meta[name="csrf-token"]').attr('content')
						}
					}).done(function( data ) {
						//if($(#email-error).length < 1) {
						var errorMsgContent = $("#email-error").text();
						console.log('errrcontent-------'+errorMsgContent);
						if(errorMsgContent == "") {
						if(data=="true") {
								localStorage.setItem('emailsent', 1);
								$("#sentverifymail").val(localStorage.getItem('emailsent'));
								$("#email-error-custom").css('display','block');
								$("#email-error-custom").html('Verification link has been sent to '+val);
								$("#email").attr('readonly',true);
						} else {
								$("#email-error-custom").css('display','block');
								$("#email-error-custom").html('Verification link has been sent to '+val);
						}
						}
						//}

					});
				}
}
	if($('#emailexists').val()=="") {
        document.getElementById("email").value = getSavedValue("email");    // set the value to this input
		}
		if($('#passwordexists').val()=="") {
        document.getElementById("password").value = getSavedValue("password");   // set the value to this input
		document.getElementById("password_confirmation").value = getSavedValue("password_confirmation");
		}

        /* Here you can add more inputs to set value. if it's saved */

        //Save the value function - save it to localStorage as (ID, VALUE)
        function saveValue(e){
            var id = e.id;  // get the sender's id to save it .
            var val = e.value; // get the value.
            localStorage.setItem(id, val);// Every time user writing something, the localStorage's value will override .



    			var errorMsgContent = $("#email-error").text();
    			if(errorMsgContent!="") {
    					$("#email-error-custom").html("");
    					$("#email-error-custom").css('display','none');
    			}
        }

        function showPassModal(e) {
          passwordValidator(e);
          $("#password_message").show();
        }

        function hidePassModal() {
          $("#password_message").hide();
        }

        //get the saved value function - return the value of "v" from localStorage.
        function getSavedValue  (v){
            if (!localStorage.getItem(v)) {
                return "";// You can change this to your defualt value.
            }
            return localStorage.getItem(v);
        }
</script>

<script type="text/javascript">



$(".email-verify-popup").on("click", function(){
	var email = $('#email').val();
	$('.email-otp-title').html('OTP sent to '+email);
		$('#emailverify').modal('show');
		getEmailOtp(email);
});

$("#resendOtpEmail").on("click",function(){
			getEmailOtp($("#mobile_number").val());
			//setTimeout(function(){ alert("Hello"); }, 20000)
	});


function getEmailOtp(email) {
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
          //form.addClass('hide');
          $("#submit-otp-form-email").find('input[name=email]').val(response.email);
          //$("#submit-otp-form-email").removeClass('hide');
          //$('.alert-email.alert-success-email').html(response.message);
          //$('.alert-email.alert-success-email').removeClass('hide');
          //thisButton.removeAttr('disabled');

        }).fail(function (data) {
          $('.alert-email.alert-danger-email').html(data.responseJSON.message);
          $('.alert-email.alert-danger-email').removeClass('hide');
          thisButton.removeAttr('disabled');
        });
}
function removeDisable(e) {
		var otpValue = e.value;
		if(otpValue.toString().length ==6) {
			$(".rm-btn-disbld").removeAttr('disabled');
		}else {
			$(".rm-btn-disbld").attr('disabled','disabled');
		}
}
</script>
<script type="text/javascript">
  function myFunction() {
  var x = document.getElementById("password");
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
</script>
@endsection
