@extends('layouts_front_ib.master')
@section('content') 
<!-- BEGIN CONTENT -->
<div class="container-fluid" data-select2-id="13">
      <div class="side-body padding-top" data-select2-id="12">
        @if(empty($consentRequest))
          <h3 class="text-center">Invalid link</h3>
          
        @else
        <!-- <div class="container-fluid padding-20">
          <h1 class="page-title"> <i class="voyager-person"></i> My Consent </h1>
        </div> -->
        <div class="container-fluid padding-20 consent-request">
          <!-- <h1 class="page-title"> <i class="voyager-person"></i> My Consent </h1> -->
          <h4>Consent request for {{Ucfirst($consentRequest->addedBy->business_name) ?? ''}} <!-- ({{$consentRequest->addedBy->userType->name ?? ''}}) --></h4>
        </div>
        <div id="voyager-notifications"></div>
        <div class="page-content browse container-fluid" data-select2-id="11">
          <div class="alerts"> </div>
          <div class="row" data-select2-id="10">
            <div class="col-md-12" data-select2-id="9">
              <div class="panel panel-bordered" data-select2-id="8">
                <div class="panel-body" data-select2-id="7">
                  @if($consentRequest->report==2)
                  <h5 class="consentHeading">Please enter the below details and click on approve to provide your consent</h5>
                  @elseif($consentRequest->report==3)
                    <h5 class="consentHeading" style="font-weight: 500;font-size: 18px;color: black;">Business Credit Report Consent Form</h5>
                    <h5 class="consentHeading">Please enter / confirm the below details of your business and click on approve to provide your consent</h5>
                  @else
                    <h5 class="consentHeading">Please provide your consent to  {{Ucfirst($consentRequest->addedBy->business_name) ?? ''}} to view the dues reported on Recordent.</h5>
                  @endif
                  
                  <!-- <h5 class="otpHeading">OTP is sent to {{ $consentRequest->customer_type=='INDIVIDUAL' ? $consentRequest->contact_phone :  $consentRequest->concerned_person_phone }}</h5> -->
                  <div class="alert alert-success hide" role="alert"></div>
                  <div class="alert alert-danger hide" role="alert"></div>
                  <div class="form-group">
                    <!-- <label>{{Ucfirst($consentRequest->addedBy->business_name) ?? ''}} ({{$consentRequest->addedBy->userType->name ?? ''}}) has raised consent on {{date('d/m/Y', strtotime($consentRequest->created_at))}} at {{date('h:i a', strtotime($consentRequest->created_at))}} to view due reports of  
                    @if($consentRequest->customer_type=='BUSINESS')
                      {{$consentRequest->concerned_person_phone}}
                    @else
                      {{$consentRequest->contact_phone}}
                    @endif  
                    </label> -->
                    {{--<a class="requestResponseAnchor" href="" data-url="{{route('myconsent.accept',[$uniqueUrlCode])}}"><button type="button" class="btn btn-primary" aria-controls="dataTable">Accept</button></a>
                    <a class="requestResponseAnchor" href="" data-url="{{route('myconsent.deny',[$uniqueUrlCode])}}"><button type="button" class="btn btn-primary" aria-controls="dataTable">Deny</button></a>--}}
                    
                    </div>

                    <form action="" method="POST" id="chooseAction">
                        <!-- @csrf -->
                        <!-- <div class="form-group">
                            <input type="radio" class="form-radio-input" name="acceptDeny" value="accept" required>
                            <span>Accept</span>
                            <input type="radio" class="form-radio-input" name="acceptDeny" value="deny" required>
                            <span>Deny</span> 
                        </div> -->
                       @if($consentRequest->report==2 || $consentRequest->report==3)
                         @if($consentRequest->customer_type == "INDIVIDUAL")
                            <div class="form-group">
                             <label>Full Name</label>
                             <input type="text" name="fullname" class="form-control" placeholder="Please enter your full name" id="fullname">
                            </div>
                            <div class="form-group">
                               <label>ID Type</label>
                               <select class="form-control" name="id_type" id="id_type">
                                <option value="">Please select an ID</option>
                                <option value="1">PAN</option>
                                <option value="2">Voter ID</option>
                                <option value="3">Passport</option>
                                <option value="4">Driving License</option>
                              </select>
                            </div>
                            <div class="form-group">
                              <label>ID Value</label>
                              <input type="text" name="id_value" class="form-control" placeholder="Please enter ID value" id="id_value">
                            </div>
                            <div class="form-group">
                              <label>Mobile Number</label>
                              <input type="text" name="mobile" class="form-control" placeholder="Mobile Number" value="{{ $consentRequest->customer_type=='INDIVIDUAL' ? $consentRequest->contact_phone :  $consentRequest->concerned_person_phone }}" readonly>
                            </div>
                         @endif
                       @endif

                       @if($consentRequest->report==3)
                        @if($consentRequest->customer_type == "BUSINESS")
                          <div class="form-group">
                            <label>Business Name</label>
                            <input type="text" name="business_name" class="form-control" placeholder="Please enter the Business Legal Name" value="{{  $consentRequest->business_name }}" id="business_name">
                          </div>
                          <div class="form-group">
                            <label>Company / Business PAN</label>
                            <input type="text" class="form-control" name="company_id" id="company_id" value="<?php if(strlen($consentRequest->unique_identification_number)==15){ echo substr($consentRequest->unique_identification_number,2,10);}else { echo
                            $consentRequest->unique_identification_number;
                          } ?>" placeholder="Please enter Company Id" maxlength="15" required >
                          </div>
                          <div class="form-group">
                            <label>Address</label>
                            <input type="text" name="address" class="form-control" placeholder="Please enter registered office address" value="{{  $consentRequest->address }}" id="address">
                          </div>
                          <div class="form-group">
                           <label>State</label>
                           <select name="state" id="state" style="text-transform: uppercase;"  placeholder="Select State" class="form-control" required>
                             <option value="">Select</option>
                              @if($states->count())  
                               @foreach($states as $state)
                                <option value="{{$state->id}}" {{$consentRequest->state==$state->id ? 'selected' : '' }}>{{$state->name}}</option>
                               @endforeach  
                              @endif
                           </select>
                          </div>
                          <div class="form-group">
                            <label>City</label>
                            <input type="text" name="city" class="form-control" placeholder="Please enter your City name" value="{{  $consentRequest->city }}" id="city">
                          </div>
                          <div class="form-group">
                            <label>Pincode</label>
                            <input type="text" name="pincode" class="form-control" placeholder="Please enter pincode" value="{{  $consentRequest->pincode }}" id="pincode">
                          </div>
                          <div class="form-group">
                            <label>Authorized Signatory's Name</label>
                            <input type="text" name="authorized_name" class="form-control" placeholder="Please enter full name" value="{{  $consentRequest->authorized_signatory_name }}" id="authorized_name">
                          </div>
                          <div class="form-group">
                            <label>Authorized Signatory’s DOB</label>
                             <input type="text" name="authorized_dob" id="authorized_dob" class="form-control datepicker collectionsetevent" data-date-format="DD/MM/YYYY" required aria-controls="dataTable" value="" placeholder="Please enter DOB">
                          </div>
                          <div class="form-group">
                            <label>Authorized Signatory’s Mobile Number</label>
                            <input type="text" name="authorized_mobile" id="authorized_mobile" class="form-control" placeholder="Mobile Number" value="{{  $consentRequest->concerned_person_phone }}">
                          <p style="color: red;"><b>Note:</b> Please enter the mobile number registered with your Business loan account as per bank records which will be used for verification.</p>
                          </div>
                          <div class="form-group">
                            <label>Authorized Signatory’s Email</label>
                            <input type="text" class="form-control" maxlength="{{General::maxlength('email')}}" name="directors_email" value="{{  $consentRequest->directors_email }}" placeholder="Please Enter Email" required id="directors_email">
                          </div>
                          <div class="form-group">
                            <label>Authorized Signatory's Designation</label>
                            <select class="form-control" name="authorized_designation" id="authorized_designation">
                              <option value="">Select</option>
                              <option value="Director">Director</option>
                              <option value="Proprietor">Proprietor</option>
                              <option value="Promoter">Promoter</option>
                              <option value="Partner">Partner</option>
                              <option value="Others">Others</option>
                            </select>
                          </div>
                           <div  id="type_of_others_div" style="display:none">
                            <div class="form-group">
                              <label>Please Specify Designation</label>
                                <input type="text" name="type_of_others" id="type_of_others" value="" placeholder="Please specify type" class="form-control">
                            </div>
                          </div>
                          <div class="form-group">
                            <label>ID Type</label>
                            <select class="form-control" name="id_type" id="id_type">
                              <option value="">Please select an ID</option>
                              <option value="1">PAN</option>
                              <option value="2">Voter ID</option>
                              <option value="3">Passport</option>
                              <option value="4">Driving License</option>
                              <option value="5">Ration Card</option>
                              <option value="6">Aadhar Card</option>
                            </select>
                          </div>
                          <div class="form-group">
                            <label>ID Value</label>
                            <input type="text" name="id_value" class="form-control" placeholder="Please enter ID value" id="id_value">
                          </div>
                        @endif
                       @endif
                        <!-- <div class="form-check">
                            <input type="checkbox" class="form-check-input" name="agree_terms"> -->
                            <!-- <label class="form-check-label" for="agree_terms">Check here to indicate that you have read and agree to the terms of the <a target="_blank" href="{{route('end-user-license-agreement')}}" target="_blank">Recordent End User License Agreement</a>
                            </label> -->
                            <!-- <label class="form-check-label" for="agree_terms">I understand that by clicking on the "Accept" button, I agree to Recordent's  <a target="_blank" href="{{ url('terms-and-conditions') }}">Terms and Conditions</a> and <a target="_blank" href="{{route('end-user-license-agreement')}}" target="_blank">End User License Agreement</a>. {{ $consentRequest->report=="Recordent Comprehensive Report" ? 'I am also authorizing Recordent to obtain my credit profile from the bureau partner and provide it to '.Ucfirst($consentRequest->addedBy->business_name) ?? ''.' requesting consent' : '' }}
                            </label>
                        </div>  -->
                        <p>I understand that by clicking on ‘Accept’, I agree to Recordent’s  <a target="_blank" href="{{ url('terms-and-conditions') }}">Terms and Conditions</a> and <a target="_blank" href="{{route('end-user-license-agreement')}}" target="_blank">End User License Agreement</a>. {{ ($consentRequest->report==2 || $consentRequest->report==3) ? 'I am also authorizing Recordent Private Limited to obtain my credit profile from the bureau partner and provide it to '.Ucfirst($consentRequest->addedBy->business_name) ?? ''.' requesting consent' : '' }}</p>
                        <div class="form-action consent-buttons" align="center">
                            <!-- <button type="submit" disabled class="btn btn-primary">Submit</button> -->
                            <button type="submit" class="btn btn-success accept" name="accept">Accept</button>
                            <button type="submit" class="btn btn-danger deny" name="deny">Reject</button>
                        </div>                      
                    </form>

                    <form action="" method="POST" id="submitOtp" class="hide">
                        @csrf
                        <div class="form-group">
                            <label>OTP</label>
                            <!-- <input type="text" class="form-control" name="otp" value="" required> -->
                            <input type="tel" class="form-control" name="otp" id="otp" value="" maxlength="6" required>
                        </div>
                        <p style="display: block;float: left;padding-top: 10px;padding-right: 10px;font-weight: 400;">Didn't get OTP? <a href="Javascript:void" class="bright-link" id="resendOtp"> Send again</a></p>
                        <div class="form-action pull-left">
                            <button type="submit" disabled class="btn btn-primary">Submit</button>
                        </div>                      
                    </form>

                    {{-- @if($consentRequest->customer_type=='BUSINESS')
                      @include('front-ib.myconsent.business')
                    @else
                      @include('front-ib.myconsent.individual')
                    @endif
                    --}} 
                  
                </div>
              </div>
            </div>
          </div>
        </div>

        @endif
      </div>
</div>

   {{-- <div class="modal" id="requestResponse" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title">Sending OTP...</h3>
          </div>
          <div class="modal-body">
            <div class="alert alert-success hide" role="alert">ter</div>
            <div class="alert alert-danger hide" role="alert">tert</div>
            <form action="" method="POST">
                @csrf
                <input type="hidden" name="unique_url_code" value="{{$uniqueUrlCode}}">
                <div class="form-group">
                    <label for="due_amount">OTP</label>
                    <input type="text" class="form-control" name="otp" value="" required>
                </div>
                <a href="Javascript:void" style="display: block;float: left;padding-top: 10px;" class="bright-link" id="resendOtp">Didn't get OTP? Send again</a>                          
                <div class="form-action pull-right">
                    <button type="submit" class="btn btn-primary">SUBMIT</button>
                    <button type="reset" class="btn btn-secondary" data-dismiss="modal">CANCEL</button>
                </div>                      
            </form>
          </div>
          <div class="modal-footer">
          </div>
        </div>
      </div>
    </div>
    --}}
<!-- END CONTAINER --> 
<script src="{{asset('js/jquery.validate.min.js')}}"></script>
<script type="text/javascript">
  $("#authorized_designation").on('change',function(){
        $("#type_of_others_div").find('input').val('');
        // if($(this).val()==10 || $(this).val()==11){
        if($('#authorized_designation :selected').text()== "Others"){

            $("#type_of_others_div").show(1);
            // $("#type_of_others_div").find('input').attr('required','required');

        }else{
            $("#type_of_others_div").hide(1);
        }
    });
  $.validator.addMethod("alphaspace", function(value, element) {
      return this.optional(element) || /^[a-z ]+$/i.test(value);
  }, "Only alphabet and space allowed.");
  $.validator.addMethod("alphanumdashspace", function(value, element) {
        return this.optional(element) || /^[a-z0-9\-()./ ]+$/i.test(value);
    }, "Only alphabet,number,dash and space allowed.");
  $.validator.addMethod("mobile_number_india", function(value, element) {
        return this.optional(element) || /^[6789]\d{9}$/i.test(value);
  }, "Please enter a valid number.");
  $.validator.addMethod("idvalue", function(value, element) {
      var idtype = $("#id_type").val();
      var panno = /[A-Z]{5}[0-9]{4}[A-Z]{1}$/i;
      var passport = /[A-Z]{1}[0-9]{7}$/i;
      var voterid = /^([a-zA-Z]){3}([0-9]){7}?$/i;
      var driving = /^(([A-Z]{2}[0-9]{2})( )|([A-Z]{2}[0-9]{2}))((19|20)[0-9][0-9])[0-9]{7}$/i;
      var rationcard = /^([a-zA-Z0-9]){8,12}\s*$/i;
      var aadhar = /^[2-9]{1}[0-9]{3}\s{1}[0-9]{4}\s{1}[0-9]{4}$/i;
      if(idtype==1){
        return this.optional(element) || panno.test(value);
      }else if(idtype==3){
        return this.optional(element) || passport.test(value);
      }else if(idtype==2){
        return this.optional(element) || voterid.test(value);
      }else if(idtype==4){
        return this.optional(element) || driving.test(value);
      }else if(idtype==5){
        return this.optional(element) || rationcard.test(value);
      }else if(idtype==6){
        return this.optional(element) || aadhar.test(value);
      }
  }, function(params, element) {
      return 'Please enter a valid ' + $("#id_type option:selected").text() + ' number.';
  });

  $.validator.addMethod("check_gstin", function(value, element) {
      if(value.toString().length == 10) {
       var valueToString = value.toString().toUpperCase();
        // var fourthChar = valueToString.charAt(3);
        // var allowedCharsAtFourthPosition = ["C","H","A","B","G","J","L","F","T"];
        if(valueToString) {
          return this.optional(element) || /^[A-Z]{3}[A|B|C|F|G|H|L|J|P|T]{1}[A-Z]{1}[0-9]{4}[A-Z]{1}$/i.test(value);
        } else {
          return false;
        }
      } else {
          return this.optional(element) || /^[0-3|9]{1}[0-9]{1}[A-Z]{3}[A|B|C|F|G|H|L|J|P|T]{1}[A-Z]{1}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/i.test(value);
      }
    }, "Please enter a valid GSTIN/Business PAN.");
  
  $.validator.addMethod("dob_check", function(value, element) {
        var returnFlag = true;
        var currentDate = new Date();
        var dateString = value;
        var dateParts = dateString.split("/");
        var dateObject = new Date(+dateParts[2], dateParts[1] - 1, +dateParts[0]);
        if (dateObject.getTime() > currentDate.getTime()) {
            returnFlag = false;
        }
        return returnFlag;
    }, "DOB should not greater than current date");

   $.validator.addMethod("dob_valid", function(value, element) {
        var returnFlag = true;
        var d = new Date();
        var year = d.getFullYear();
        var month = d.getMonth();
        var day = d.getDate();
        var currentDate = new Date(year-21, month, day);
        var dateString = value;
        var dateParts = dateString.split("/");
        var dateObject = new Date(+dateParts[2], dateParts[1] - 1, +dateParts[0]);
        if (dateObject.getTime() > currentDate.getTime()) {
            returnFlag = false;
        }
        return returnFlag;
    }, "You should be atleast 21 years to proceed with this request");

  $('#state').change(function() {
    $('#city').val('')
  }); 
  $(".otpHeading").hide();
  
  var form = $('#chooseAction');
  form.validate({
    ignore: '',
        rules: {
            fullname: {
              required: true,
              alphanumdashspace:true,
              maxlength:100
            },
            id_type: {
              required: true,
            },
            id_value: {
              required: true,
              idvalue:true,
            },
            directors_email: {
              required: true,
              email: true
            },
             address: {
              required: true,
              maxlength:250
            },
            company_id:{
             required: true,
             maxlength: 15,
             check_gstin:true
            },
            authorized_mobile:{
                maxlength:10,
                required:true,
                mobile_number_india:true
            },
            authorized_name:{
                required:true,
                alphaspace:true,
                maxlength:28
            },
            authorized_dob:{
                required:true,
                dob_check:true,
                dob_valid:true
            },
            business_name:{
                required:true,
                alphanumdashspace:true,
                maxlength:100
            },
            authorized_designation:{
                required:true,
            },
            state:{
              required:true,
            },
            city:{
              required:true,
            },
            pincode:{
              required:true,
            },
            
        },
        messages : {
              fullname: {
                     required: "This is a required field"
                    },
              id_type: {
                     required: "This is a required field"
                    },
              id_value: {
                     required: "This is a required field"
                    },
              directors_email: {
                     required: "This is a required field"
                    },
              address: {
                     required: "This is a required field"
                    },
              company_id: {
                     required: "This is a required field"
                    },
              authorized_mobile: {
                     required: "This is a required field"
                    },
              authorized_name: {
                     required: "This is a required field"
                    },
              authorized_dob: {
                     required: "This is a required field"
                    },
              business_name: {
                     required: "This is a required field"
                    },
              authorized_designation: {
                     required: "This is a required field"
                    },
               state: {
                     required: "This is a required field"
                    },
               city: {
                     required: "This is a required field"
                    },
               pincode: {
                     required: "This is a required field"
                    },                                                                         

              }   
    });
  
  var form2 = $('#submitOtp');
  form2.validate({
    ignore: '',
        rules: {
            otp: {
              required: true,
              number: true,
              minlength:6,
              maxlength:6
            },
            
        }
    });
  $('body').on('focus','.datepicker',function(){
    $(this).datetimepicker();
});
    $("input[name=agree_terms]").on('change',function(){
        if($(this).is(':checked')){
            $(this).parents("form#chooseAction").find("button[type=submit]").attr('disabled',false);
        }else{
            $(this).parents("form#chooseAction").find("button[type=submit]").attr('disabled',true);
        }
    });
    
        var reportc = "{{ empty($consentRequest) ? '' : $consentRequest->report }}";
        if(reportc==2 || reportc==3){
          $('.accept').css('background', '#bbbbbb'); 
        }

        $("#fullname").add('#id_type').add('#id_value').add('#business_name').add('#address').add('#directors_email').add('#company_id').add('#authorized_name').add('#authorized_dob').add('#authorized_mobile').add('#authorized_designation').add('#state').add('#city').add('#pincode').on('keyup keypress blur change', function() {
            if(form.valid() == true ) {
                $('.accept').css('background', '#2ecc71');  
            } else {
                $('.accept').css('background', '#bbbbbb');
            }
        });
  $(document).ready(function(){
        var buttonpressed;
        $('.accept').click(function() {
              buttonpressed = $(this).attr('name')
        })
        $('.deny').click(function() {
              buttonpressed = $(this).attr('name')
        })
        $('#id_type').on('change', function() {
          var idtype = $(this).val();
          if(idtype==""){
            $("#id_value").attr("placeholder", "Please enter ID value");
          }else{
            $("#id_value").attr("placeholder", "Please Enter "+$("#id_type option:selected").text()+" number");
            if(idtype==1 || idtype==2){
              $("#id_value").attr('maxlength','10');
            }else if(idtype==3){
              $("#id_value").attr('maxlength','8');
            }else if(idtype==4){
              $("#id_value").attr('maxlength','15');
            }
          }
        });
        $("#otp").keypress(function (e) {
           //if the letter is not digit then display error and don't type anything
           if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
                 return false;
            }
         });

        $("form#chooseAction").on('submit',function(e){
            e.preventDefault();
            var chooseActionForm = $(this);
            var validationCheck;
            var reportCheck = "{{ empty($consentRequest) ? '' : $consentRequest->report }}";
            // if(reportCheck=="Recordent Comprehensive Report"){
            if((reportCheck==2 || reportCheck==3) && buttonpressed=="accept"){
              validationCheck = form.valid();
            }else{
              validationCheck = true;
            }
            
            if(validationCheck == true){
              var submitOtpForm = $('form#submitOtp');
              chooseActionForm.find('button[type=submit]').attr('disabled','disabled');
              $('.alert').addClass("hide");
              $('.alert').html('');
              // var acceptDenyRadio =  chooseActionForm.find('input[name=acceptDeny]:checked').val();
              // if(!acceptDenyRadio){
              //     alert('Please select accept or deny option');
              // }
              chooseActionForm.addClass('hide');
              if(buttonpressed=="accept"){
                submitOtpForm.removeClass('hide');
                $("#resendOtp").click();
              }else{
                $(".consentHeading").hide();

                var uniqueUrlCode = "{{$uniqueUrlCode}}";
                $.ajax({
                   method: 'post',
                   url: "{{route('myconsent.deny',[$uniqueUrlCode])}}",
                   headers: {
                       'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                   },
                   data: {
                       otp: "123456",
                       fullname: $('#fullname').val(),
                       // business_name:$('#business_name').val(),
                       // address:$('#address').val(),
                       // state:$('#state').val(),
                       // city:$('#city').val(),
                       // pincode:$('#pincode').val(),
                       idtype: $('#id_type').val(),
                       idvalue: $('#id_value').val(),
                       _token: $('meta[name="csrf-token"]').attr('content')
                   }
                }).then(function (response) {
                    $(".otpHeading").hide();
                    $('.alert.alert-success').html(response.message);
                    $('.alert.alert-success').removeClass('hide');
                    chooseActionForm.addClass('hide');
                    submitOtpForm.addClass('hide');

                })
              }
            }
           
        });
        
        
        $("#resendOtp").on('click',function(e){
            var resendOtp = $(this);
            e.preventDefault();
            if(resendOtp.attr('disabled')){
                return false;
            }

            $(".consentHeading").hide();
            $(".otpHeading").show();

            $("#submitOtp button").css('background', '#bbbbbb');

            var chooseActionForm = $("form#chooseAction");
            var submitOtpForm = $('form#submitOtp');

            resendOtp.attr('disabled','disabled');
            resendOtp.css('color', '#949494');
            submitOtpForm.find("button[type=submit]").attr('disabled','disabled');
            var uniqueUrlCode = "{{$uniqueUrlCode}}";
            //clear otp from otp form
            submitOtpForm.find('input[name=otp]').val('');
            $('.alert').addClass("hide");
            $('.alert').html('');

            $.ajax({
               method: 'post',
               url: "{{route('myconsent.sendOtp',[$uniqueUrlCode])}}",
               headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               },
               data: {
                   unique_url_code: uniqueUrlCode,
                   authorized_mobile:$('#authorized_mobile').val(),
                   _token: $('meta[name="csrf-token"]').attr('content')
               }
            }).then(function (response) {
                
                $('.alert.alert-success').html(response.message);
                $('.alert.alert-success').removeClass('hide');
                if(response.canRequestOtpAgain24Hour){
                    if(response.startCountDownTimer){
                        //start the timer
                        var now = new Date(response.currentTimeInMilli).getTime();
                        var countDownDate = new Date(response.next3MinForCounDown).getTime();
                        // Update the count down every 1 second
                        var x = setInterval(function() {
                              // Get today's date and time
                              var now = new Date().getTime();
                              // Find the distance between now and the count down date
                              var distance = countDownDate - now;
                              if(distance < 0){
                                clearInterval(x);
                              }
                              now = now + 1000;
                              // Time calculations for days, hours, minutes and seconds
                              if(distance>=0){
                                  var days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                  var seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                  // Display the result in the element with id="demo"
                                  if(seconds<10){
                                    seconds = "0" + seconds;
                                  }
                                  // document.getElementById("resendOtp").innerHTML = 'resend otp in 0'+minutes + ":" + seconds + " Min";
                                  document.getElementById("resendOtp").innerHTML = 'Resend OTP in '+ seconds + " Sec";
                                }
                                    
                                  // If the count down is finished, write some text
                                  if (distance < 0) {
                                    resendOtp.removeAttr('disabled');
                                    resendOtp.css('color', '#337ab7');
                                    clearInterval(x);
                                    // document.getElementById("resendOtp").innerHTML="Didn't get OTP? Send again";
                                    document.getElementById("resendOtp").innerHTML="Send again";
                                    
                                  }
                            }, 1000);

                    }
                }




                submitOtpForm.find("button[type=submit]").removeAttr('disabled');
                //submitOtpForm.find("button[type=reset]").removeAttr('disabled');

            }).fail(function (data) {
                resendOtp.removeAttr('disabled');
                resendOtp.css('color', '#337ab7');
                $('.alert.alert-danger').html(data.responseJSON.message);
                $('.alert.alert-danger').removeClass('hide');
            });
        });

        $('form#submitOtp').on('submit',function(e){
            e.preventDefault();
            var chooseActionForm =$("form#chooseAction"); 
            var submitOtpForm = $(this);
            var resendOtp = $('#resendOtp');
            // var acceptDenyRadio =  chooseActionForm.find('input[name=acceptDeny]:checked').val();
            // if(!acceptDenyRadio){
            //     alert('Please select accept or deny option');
            // }
            var url='';
            var acceptUrl="{{route('myconsent.accept',[$uniqueUrlCode])}}";
            var denyUrl="{{route('myconsent.deny',[$uniqueUrlCode])}}"
            if(buttonpressed=='accept'){
                url = acceptUrl;
            }else{
                url = denyUrl;
            }
            var otp = submitOtpForm.find('input[name=otp]').val();
            submitOtpForm.find("button[type=submit]").attr('disabled','disabled');
            submitOtpForm.find("button[type=reset]").attr('disabled','disabled');
            var uniqueUrlCode = "{{$uniqueUrlCode}}";
            $('.alert').addClass("hide");
            $('.alert').html('');

            $.ajax({
               method: 'post',
               url: url,
               headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               },
               data: {
                   otp: otp,
                   fullname: $('#fullname').val(),
                   business_name:$('#business_name').val(),
                   address:$('#address').val(),
                   directors_email:$('#directors_email').val(),
                   company_id:$('#company_id').val(),
                   authorized_name:$('#authorized_name').val(),
                   authorized_dob:$('#authorized_dob').val(),
                   authorized_mobile:$('#authorized_mobile').val(),
                   authorized_designation:$('#authorized_designation').val(),
                   type_of_others:$('#type_of_others').val(),
                   state:$('#state').val(),
                   city:$('#city').val(),
                   pincode:$('#pincode').val(),
                   idtype: $('#id_type').val(),
                   idvalue: $('#id_value').val(),
                   _token: $('meta[name="csrf-token"]').attr('content')
               }
            }).then(function (response) {
                $(".otpHeading").hide();
                $('.alert.alert-success').html(response.message);
                $('.alert.alert-success').removeClass('hide');
                chooseActionForm.addClass('hide');
                submitOtpForm.addClass('hide');

            }).fail(function (data) {
                resendOtp.removeAttr('disabled');
                resendOtp.css('color', '#337ab7');
                $('.alert.alert-danger').html(data.responseJSON.message);
                $('.alert.alert-danger').removeClass('hide');
                submitOtpForm.find("button[type=submit]").removeAttr('disabled');
                submitOtpForm.find("button[type=reset]").removeAttr('disabled');

            }); 
        });

  });
  $('#otp').on('input',function(e){
      checkOTP();
  });
  $('#id_value').keyup(function(){
      $(this).val($(this).val().toUpperCase());
  });
  $('#business_name').keyup(function(){
      $(this).val($(this).val().toUpperCase());
  });
  $('#company_id').keyup(function(){
      $(this).val($(this).val().toUpperCase());
  });
  $('#address').keyup(function(){
      $(this).val($(this).val().toUpperCase());
  });
  $('#city').keyup(function(){
      $(this).val($(this).val().toUpperCase());
  });
  $('#pincode').keyup(function(){
      $(this).val($(this).val().toUpperCase());
  });
  $('#authorized_name').keyup(function(){
      $(this).val($(this).val().toUpperCase());
  });
  $('#authorized_mobile').keyup(function(){
      $(this).val($(this).val().toUpperCase());
  });
  $('#directors_email').keyup(function(){
      $(this).val($(this).val().toUpperCase());
  });
  function checkOTP(){
    var otp = $('#otp').val();
    if (isNaN(otp)) {
        $("#submitOtp button").css('background', '#bbbbbb');
    } else {
        if(otp.toString().length == 6){
            $("#submitOtp button").css('background', '#22a7f0');
        }else{
            $("#submitOtp button").css('background', '#bbbbbb');
        }
    }
  }
</script>
@endsection