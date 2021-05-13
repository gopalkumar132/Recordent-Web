@extends('voyager::master')

@section('page_title', 'Recordent - Individual Credit Report')

@section('page_header')
<h1 class="page-title">

    <img  class="india-flag"src="{{asset('front_new/images/team/individualreportflagicon.svg')}}" border="0" />&nbsp;&nbsp;&nbsp;Individual Credit Report

</h1>
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

<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<style type="text/css">
    input, textarea {
        text-transform: uppercase;
    }
    .credit-report-info-text{
      color:#66aed1;
      font-size: 20px;
      font-weight:bold;
    }
    #otp_verified-error{
      display: block;
    }
    @media only screen and (max-width:600px) {
    .page-title {
      padding: 100px 0 0 20px;
      margin-bottom: 60px;
      font-size: 16px;
    }
      .india-flag {
        width: 60px;
      }
    }

    }
</style>
<div class="page-content container-fluid">
    @include('voyager::alerts')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-bordered">
                <div class="panel-body">
                    <p class="credit-report-info-text">Complex Credit Information simplified only for you to make better credit decision and reduce risk</p>
                    <form enctype="multipart/form-data" action="{{route('store-individual-custom-creditreport')}}" name="add_store_record" id="add_store_record" method="POST">
                        @csrf

                        <div class="submitdues-mainbody">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_phone">Full Name*</label>
                                    <input type="text" class="form-control" minlength="3"  id="full_name" name="full_name" value="{{old('full_name')}}" maxlength="{{General::maxlength('name')}}" placeholder="Person Name" required onblur="trimIt(this);">

                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_phone">Customer PAN*</label>
                                    <input type="text" class="form-control" name="customer_pan" value="{{old('customer_pan')}}" maxlength="10">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_phone">Registered IP*</label>
                                    <input type="text" class="form-control" name="registered_ip" value="{{old('registered_ip')}}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_phone">Customer Registration Date (DD/MM/YYYY)<span class="mark" style="color:black;background-color:white;">*</span></label>
                                    <input type="text" name="registration_date" id="registration_date" class="form-control datepicker  inv_date" data-date-format="DD/MM/YYYY"   aria-controls="dataTable" value="">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_phone">Mobile Number*</label>
                                    <input type="tel" class="form-control number" name="contact_phone" value="{{old('contact_phone')}}" placeholder="Mobile Number" required onblur="trimIt(this);" maxlength="10" onkeypress="return numbersonly(this,event)">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_phone">OTP Code*</label>
                                    <input type="tel" maxlength="10" class="form-control number" name="otp_code" value="{{old('otp_code')}}" placeholder="OTP Code" required onblur="trimIt(this);" maxlength="10">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_phone">OTP Generated Date*</label>
                                    <input type="text" name="otp_generated_time" id="otp_generated_time" class="form-control datepicker  inv_date" data-date-format="DD/MM/YYYY"   aria-controls="dataTable" value="">
                                </div>
                            </div>
                            <div class="col-md-12">
                            <div class="col-md-6">
                                <div class="form-group" style="margin-left:-15px;"><br>
                                    <input type="checkbox" name="otp_verified" value="1">
                                    <label for="contact_phone">OTP Verified*</label>

                                </div>
                            </div></div>
                            </div>
                            <div class="col-md-12 text-center">
                            <!--<div class="col-md-6">-->
                                <div class="form-action">
                                    <button type="submit" class="btn btn-primary btn-blue">Generate Credit Report</button>
                                </div>
                            <!--</div>-->
                        </div>

<div class="col-md-12"><b>By Clicking on "Generate Credit Report", a record is generated for the user and can be viewed in "Records History" Page</b></div>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>

    @if(session()->has('successpopup'))
    <div class="modal commap-team-popup" id="paymentHistory" tabindex="-1" role="dialog">
     <div class="modal-dialog" role="document">
       <div class="modal-content">
         <div class="modal-header">
           <h3 class="modal-title text-success">{{session('successpopup')}}</h3>
           <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                     <span aria-hidden="true">&times;</span>
                   </button>
         </div>
         <!--<div class="modal-body text-center">
           <button type="button" class="btn btn-primary btn-blue" data-dismiss="modal">OK</button>
           <button type="button" class="btn btn-primary btn-blue" data-dismiss="modal">View Report History</button>
         </div>-->
         <div class="modal-footer">
               <div class="pull-right">
                  <button type="submit" class="btn btn-success" data-dismiss="modal"><i class="glyphicon glyphicon-ok"></i> OK</button>
                  <button type="button" id="view_report_history" class="btn btn-danger">View Report History</button>
               </div>
            </div>
       </div>
     </div>
    </div>
    @endif



<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
<script src="{{asset('js/jquery.validate.min.js')}}"></script>
<script src="{{asset('js/number-to-word.js')}}"></script>
@if(session()->has('successpopup'))
<script>
    $(function() {
      $('#paymentHistory').modal('show');
    });
</script>
@endif
<script type="text/javascript">


    function blockSpecialChar(myfield, e) {
        var key;
        var keychar;
        if (window.event)
            key = window.event.keyCode;
        else if (e)
            key = e.which;
        else
            return true;

        keychar = String.fromCharCode(key);
        console.log(key);
        // control keys
        if ((key == null) || (key == 0) || (key == 8) || (key == 9) || (key == 13) || (key == 27)) {
            return true;
        }
        // numbers
        else if ((key == 192) || (key == 49) || (key == 50) || (key == 51) || (key == 52) || (key == 54) || (key == 55) || (key == 56) || (key == 189) || (key == 187) || (key == 220) || (key == 191) || (key == 219) || key == 221) {
            //return false;
        } else if ((("~!@#$^&*_+|\/<>{}[]").indexOf(keychar) > -1)) {
            return false;
        } else {
            return true;
        }
    }
</script>
<script language="javascript" type="application/javascript">

    $.validator.addMethod("alphaspace", function(value, element) {
        return this.optional(element) || /^[a-z ]+$/i.test(value);
    }, "Only alphabet and space allowed.");

    $.validator.addMethod("alphanum", function(value, element) {
        return this.optional(element) || /^[a-z0-9]+$/i.test(value);
    }, "Only alphabet and numbers allowed.");


    $.validator.addMethod("mobile_number_india", function(value, element) {
        return this.optional(element) || /^[6789]\d{9}$/i.test(value);
    }, "Please enter a valid number.");


    $.validator.addMethod("check_registration", function(value, element) {
        var returnFlag = true;
        var currentDate = new Date();
        var dateString = value;
        var dateParts = dateString.split("/");
        var dateObject = new Date(+dateParts[2], dateParts[1] - 1, +dateParts[0]);
        if (dateObject.getTime() > currentDate.getTime()) {
            returnFlag = false;
        }
        return returnFlag;
    }, "Registration date should not greater than current date");

    $.validator.addMethod("check_otp_generated", function(value, element) {
        var returnFlag = true;
        var currentDate = new Date();
        var dateString = value;
        var dateParts = dateString.split("/");
        var dateObject = new Date(+dateParts[2], dateParts[1] - 1, +dateParts[0]);
        if (dateObject.getTime() > currentDate.getTime()) {
            returnFlag = false;
        }
        return returnFlag;
    }, "OTP generated date should not greater than current date");


    $.validator.addMethod('check_pan', function (value) {
      return /^[A-Z]{5}[0-9]{4}[A-Z]{1}$/i.test(value);
    }, 'Please enter a valid PAN');

$.validator.addMethod('check_ip', function(value) {
           /*var ip = "^(?:(?:25[0-5]2[0-4][0-9][01]?[0-9][0-9]?)\.){3}" +
               "(?:25[0-5]2[0-4][0-9][01]?[0-9][0-9]?)$";*/
               var ip = "^(([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])\.){3}([0-9]|[1-9][0-9]|1[0-9]{2}|2[0-4][0-9]|25[0-5])$";
               return value.match(ip);
           }, 'Invalid IP address');


    $.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
 });

    $('#add_store_record ').validate({
        ignore: '',
        rules: {
            full_name: {
              required: true,
              alphaspace:true,
              maxlength: {{General::maxlength('name')}},
              minlength:3
            },
            customer_pan: {
              maxlength: 10,
              minlength:2,
              required:true,
			        check_pan:true
            },
            registered_ip: {
              required: true,
              check_ip: true
            },
            registration_date: {
                required: true,
                check_registration: true
            },
            contact_phone: {
                maxlength: 10,
                mobile_number_india: true
            },
            otp_code: {
                required:true,
                minlength:2
            },
            otp_generated_time: {
              required: true,
              check_otp_generated: true
            },
            otp_verified:{
              required:true
            }

        }
    });



    function trimIt(currentElement) {
        $(currentElement).val(currentElement.value.trim());
    }

    function numbersonly(myfield, e, maxlength = null) {
        var key;
        var keychar;
        if (window.event)
            key = window.event.keyCode;
        else if (e)
            key = e.which;
        else
            return true;

        //        alert(1);
        keychar = String.fromCharCode(key);
        // control keys
        if ((key == null) || (key == 0) || (key == 8) || (key == 9) || (key == 13) || (key == 27))
            return true;
        // numbers
        else if ((("0123456789").indexOf(keychar) > -1)) {
            return true;
        } else {
            return false;
        }
    }

    $(document).ready(function() {
      $("#view_report_history").on("click", function(){
        location.href = 'all-records';
      });

        $("body").on('keyup', '.invoice_due_amount', function() {
            convertToINRFormat($(this).val(), $(this));

        });

    });
</script>

@endsection
