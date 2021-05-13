@extends('voyager::master')

@section('page_header')
@stop
@section('content')
<style>
.box {
  position: relative;
}
.ribbon {
position: absolute;
left: -5px; top: -5px;
z-index: 1;
overflow: hidden;
width: 75px; height: 75px;
text-align: right;
}
.ribbon span {
font-size: 10px;
font-weight: bold;
color: #FFF;
text-transform: uppercase;
text-align: center;
line-height: 20px;
transform: rotate(-45deg);
-webkit-transform: rotate(-45deg);
width: 100px;
display: block;
background: #79A70A;
background: linear-gradient(#F70505 0%, #8F0808 100%);
box-shadow: 0 3px 10px -5px rgba(0, 0, 0, 1);
position: absolute;
top: 19px; left: -21px;
}
.ribbon span::before {
content: "";
position: absolute; left: 0px; top: 100%;
z-index: -1;
border-left: 3px solid #8F0808;
border-right: 3px solid transparent;
border-bottom: 3px solid transparent;
border-top: 3px solid #8F0808;
}
.ribbon span::after {
content: "";
position: absolute; right: 0px; top: 100%;
z-index: -1;
border-left: 3px solid transparent;
border-right: 3px solid #8F0808;
border-bottom: 3px solid transparent;
border-top: 3px solid #8F0808;
}
</style>
@php
    $states = General::getStateList();

@endphp

        <section class="membership-plans plans-price bg-white">
            <div class="container">
                <div class="the-title text-center">
                    <h2 class="text-uppercase hidden">Membership Plans</h2>
                    <p>Please select a Membership plan that works for your business</p>
                </div>
                <div class="choose-palns">
                    <div class="d-flex justify-content-between cmd-flex-wrap accordion" id="accordion">
                        <div class="plan-box d-flex flex-wrap bg-yellow">
                            <div class="title-contains w-100">
                                <div class="title-part text-blue-type text-center">
                                    <h2 class="text-uppercase">{{ General::pricing_plan_data(2)->name }}</h2>
                                    <p>₹{{ General::pricing_plan_data(2)->membership_plan_price }} / year</p>
                                    <p class="applicable">Taxes Applicable </p>
                                </div>
                                <div class="btn-to-select w-100 align-self-end text-center">
                                    @if(!empty(Auth::user()->user_pricing_plan)&&Auth::user()->user_pricing_plan->pricing_plan_id==2 && Auth::user()->user_pricing_plan->paid_status==1 && Auth::user()->user_pricing_plan->paid_status==1 && strtotime(Auth::user()->user_pricing_plan->end_date) > strtotime(date('Y-m-d H:i:s')))
                                        <a href="#" class="btn-join" disabled="disabled">Subscribed</a>
                                    @else
                                        <a href="#" class="select-plan btn-join" id="pay_now_2" data-plan_id=2>Select</a>
                                    @endif
                                </div>
                                <br>
                                <div  class="csd-d-block nd-none">
                                    <div class="card mb-0">
                                        <div class="card-header collapsed" data-toggle="collapse" href="#collapseOne">
                                            <a class="card-title"></a>
                                        </div>
                                        <div id="collapseOne" class="card-body collapse" data-parent="#accordion" >
                                            @include('admin.membership_invoice.membership.partials.plan_section_data', [
                                                'plan_id' => 2,
                                                'part_1' => "text-center text-black blue-line some-points",
                                                'part_2' => "heighlight-part bg-blue text-white position-relative text-center",
                                                'part_3' => "blue-line text-black some-padding some-points point-wid-sign",
                                                'report_type' => 'Quarterly',
                                                'rupees_per_customer' => 3,
                                                'from_pricing_plan' => true]
                                            )
                                        </div>
                                    </div>
                                </div>
                                <div class="csd-d-none">
                                    @include('admin.membership_invoice.membership.partials.plan_section_data', [
                                        'plan_id' => 2,
                                        'part_1' => "text-center text-black blue-line some-points",
                                        'part_2' => "heighlight-part bg-blue text-white position-relative text-center",
                                        'part_3' => "blue-line text-black some-padding some-points point-wid-sign",
                                        'report_type' => 'Quarterly',
                                        'rupees_per_customer' => 3,
                                        'from_pricing_plan' => true]
                                    )
                                </div>
                            </div>
                        </div>
                        <div class="plan-box d-flex flex-wrap bg-blue csd-bg-yellow box">
                          <div class="ribbon"><span>20% Off</span></div>
                            <div class="title-contains w-100">
                                <div class="title-part text-white csd-text-black text-center">
                                    <h2 class="text-uppercase">{{ General::pricing_plan_data(5)->name }}</h2>
                                    <p><del>1499</del>(₹{{ General::pricing_plan_data(5)->membership_plan_price }}/year)</p>
                                    <p class="applicable">Taxes Applicable </p>
                                </div>
                                <div class="btn-to-select w-100 align-self-end text-center">
                                    @if(!empty(Auth::user()->user_pricing_plan)&&Auth::user()->user_pricing_plan->pricing_plan_id==5 && Auth::user()->user_pricing_plan->paid_status==1 && Auth::user()->user_pricing_plan->paid_status==1 && strtotime(Auth::user()->user_pricing_plan->end_date) > strtotime(date('Y-m-d H:i:s')))
                                        <a href="#" class="btn-join" disabled="disabled">Subscribed</a>
                                    @else
                                        <a href="#" class="select-plan btn-join" id="pay_now_5" data-plan_id=5>Select</a>
                                    @endif
                                </div>
                                <br>
                                <div class="csd-d-block nd-none">
                                    <div class="card mb-0">
                                        <div class="card-header collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo">
                                            <a class="card-title"></a>
                                        </div>
                                        <div id="collapseTwo" class="card-body collapse" data-parent="#accordion" >
                                            @include('admin.membership_invoice.membership.partials.plan_section_data', [
                                                'plan_id' => 5,
                                                'part_1' => "text-center text-white csd-text-black csd-blue-line white-line some-points",
                                                'part_2' => "heighlight-part bg-yellow csd-bg-blue text-black csd-text-white position-relative text-center",
                                                'part_3' => "white-line text-white some-padding csd-text-black csd-blue-line some-points point-wid-sign",
                                                'report_type' => 'Quarterly',
                                                'rupees_per_customer' => 3,
                                                'from_pricing_plan' => true]
                                            )
                                        </div>
                                    </div>
                                </div>
                                <div class="csd-d-none">
                                    @include('admin.membership_invoice.membership.partials.plan_section_data', [
                                        'plan_id' => 5,
                                        'part_1' => "text-center text-white csd-text-black csd-blue-line white-line some-points",
                                        'part_2' => "heighlight-part bg-yellow csd-bg-blue text-black csd-text-white position-relative text-center",
                                        'part_3' => "white-line text-white some-padding csd-text-black csd-blue-line some-points point-wid-sign",
                                        'report_type' => 'Quarterly',
                                        'rupees_per_customer' => 3,
                                        'from_pricing_plan' => true]
                                    )
                                </div>
                            </div>
                        </div>
                        <div class="plan-box d-flex flex-wrap bg-yellow">
                            <div class="title-contains w-100">
                                <div class="title-part text-blue-type text-center">
                                    <h2 class="text-uppercase">{{ General::pricing_plan_data(3)->name }}</h2>
                                    <p>₹{{ General::pricing_plan_data(3)->membership_plan_price }} / year</p>
                                    <p class="applicable">Taxes Applicable</p>
                                </div>
                                <div class="btn-to-select w-100 align-self-end text-center">
                                    @if(!empty(Auth::user()->user_pricing_plan) && Auth::user()->user_pricing_plan->pricing_plan_id==3 && Auth::user()->user_pricing_plan->paid_status==1 && Auth::user()->user_pricing_plan->paid_status==1 && strtotime(Auth::user()->user_pricing_plan->end_date) > strtotime(date('Y-m-d H:i:s')))
                                        <a href="#" class="btn-join" disabled="disabled">Subscribed</a>
                                    @else
                                    <a href="#" id="pay_now_3"  class="select-plan btn-join"  data-plan_id=3>Select</a>
                                    @endif
                                </div>
                                <br>
                                <div class="csd-d-block nd-none">
                                    <div class="card mb-0">
                                        <div class="card-header collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree">
                                            <a class="card-title"></a>
                                        </div>
                                        <div id="collapseThree" class="card-body collapse" data-parent="#accordion" >
                                            @include('admin.membership_invoice.membership.partials.plan_section_data', [
                                                'plan_id' => 3,
                                                'part_1' => "text-center text-black blue-line some-points",
                                                'part_2' => "heighlight-part bg-blue text-white position-relative text-center",
                                                'part_3' => "blue-line text-black some-padding some-points point-wid-sign",
                                                'report_type' => 'Monthly',
                                                'rupees_per_customer' => 2.5,
                                                'from_pricing_plan' => true]
                                            )
                                        </div>
                                    </div>
                                </div>
                                <div class="csd-d-none">
                                    @include('admin.membership_invoice.membership.partials.plan_section_data', [
                                        'plan_id' => 3,
                                        'part_1' => "text-center text-black blue-line some-points",
                                        'part_2' => "heighlight-part bg-blue text-white position-relative text-center",
                                        'part_3' => "blue-line text-black some-padding some-points point-wid-sign",
                                        'report_type' => 'Monthly',
                                        'rupees_per_customer' => 2.5,
                                        'from_pricing_plan' => true]
                                    )
                                </div>
                            </div>
                        </div>
                        <div class="plan-box d-flex flex-wrap bg-blue csd-bg-yellow">
                            <div class="title-contains w-100">
                                <div class="title-part text-white csd-text-black text-center">
                                    <h2 class="text-uppercase">Corporate</h2>
                                    <p>Contact for Pricing</p>
                                    <p class="applicable" style="opacity:0">a</p>
                                </div>
                                <div class="btn-to-select w-100 align-self-end text-center">
                                    @php
                                        $credit_report_type_query_param = '';

                                        if(isset($credit_report_type) && $credit_report_type != null){
                                            $credit_report_type_query_param = '?credit_report_type='.$credit_report_type;
                                        }
                                    @endphp
                                    <a href="{{route('corporate-plan')}}{{$credit_report_type_query_param}}" class="btn-join contact-us">Contact Us</a>
                                </div>
                                <br>

                                <div class="csd-d-block nd-none">
                                    <div class="card mb-0">
                                        <div class="card-header collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFour">
                                            <a class="card-title"></a>
                                        </div>
                                        <div id="collapseFour" class="card-body collapse" data-parent="#accordion" >
                                            @include('admin.membership_invoice.membership.partials.corporate_plan_section_data')
                                        </div>
                                    </div>
                                </div>
                                <div class="csd-d-none">
                                    @include('admin.membership_invoice.membership.partials.corporate_plan_section_data')
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
                @if(!empty(Auth::user()->user_pricing_plan) && Auth::user()->user_pricing_plan->pricing_plan_id == 1 && Auth::user()->user_pricing_plan->paid_status==1)
                    <?php $fre_plan_button_name = "Renew"; ?>
                @else
                    <?php $fre_plan_button_name = "Select"; ?>
                @endif
                <div class="row">
                    <div class="col-md-12 text-center">
                        <p>
                            @php
                                $credit_report_type_query_param = '';
                                if(isset($credit_report_type) && $credit_report_type != null){
                                    $credit_report_type_query_param = '&credit_report_type='.$credit_report_type;
                                }
                            @endphp
                            <a href="{{route('register-pricing-plan')}}?pricing_plan_id=1&refferral_status={{$checkOffer}}{{$credit_report_type_query_param}}" style="text-decoration: underline;" class=" btn-join" >Click here to {{$fre_plan_button_name}} Free Trail</a>
                        </p>
                    </div>
                </div>
        </section>
        <!-- Plan Checkout popup -->
        @include('admin.membership_invoice.membership.partials.plan_checkout_popup', ['states' => $states, 'show_discount_section' => true, 'checkOffer' => $checkOffer])

        <input type="hidden" id="plan_id_from_profile" value={{$planId}}>
        <input type="hidden" id="gstin_udise_hidden" value={{Auth::user()->gstin_udise}}>
        <input type="hidden" id="profile_verify_hidden" value={{Auth::user()->profile_verified_at}}>

        <div id="contact_form_popup" class="popup-wrap">
          <div class="popup-overlay"></div>
           <div class="extra-wrap">
            <div class="extra-inner">
                <div class="popup-outer">
                <div class="popup-box">
                <header class="">
                </header>
                <div class="">
                <div class="">
                    <section class="contact-us-se" id="contact-us">
                    <a class="popup-close" href="javascript:void(0);">×</a>
                        <div class="container">

                            <div class="row align-items-center">

                                <div class="col-12 col-md-6 col-lg-6 col-xl-6 text-white" data-aos="fade-right" data-aos-duration="1000">

                                    <p>

                                        <address class="mb-0">

                            <p>Recordent Private Limited,</p>

                            <p>Aditya Trade Center,Office No:-7-1-618/ACT/710,</p>

                            <p>Seventh Floor,Ameerpet,</p>

                            <p>Hyderabad, Telangana,</p>

                            <p>India Pincode - 500038</p>

                                        </address>

                                    </p>

                                </div>

                                <div class="col-12 col-md-6 col-lg-6 col-xl-6" data-aos="fade-left" data-aos-duration="1500">

                                    <div class="contact-from">

                                        <h3>Contact Us</h3>



                                        @include('front/aboutus/contactus')

                                    </div>

                                </div>

                            </div>

                        </div>

                    </section>
                </div>

                </div>

                </div>
                </div>
               </div>
              </div>
            </div>
@endsection
@section('css')
<link rel="stylesheet" href="{{asset('front_new/css/style.css')}}">
<style type="text/css">
    .app-footer{
        display: none;
    }
    /*.membership-plans .choose-palns .heighlight-part::before{
        width: 100%;
    }*/
    a[disabled="disabled"] {
        pointer-events: none;
    }

    .open-popup { font-family: 'Roboto', sans-serif; }
    .popup-wrap {
        font-family: 'Roboto', sans-serif;
        display: none;
        height: 100%;
        left: 0;
        line-height: 1.5;
        margin: 0;
        outline: 0 none;
        padding: 0;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 9;
    }
    .popup-outer {
        box-sizing: border-box;
        margin: 0 auto;
        max-width: 450px;
        padding: 30px 15px;
        position: relative;
        width: 100%;
        z-index: 2;
    }
    #contact_form_popup .popup-outer{
        max-width :900px;
    }
    .popup-box {
        background-color: #fff;
        border: 1px solid #ddd;
        border: 1px solid hsla(0, 0%, 0%, 0.1);
        border-radius: 4px;
        box-shadow: 0 3px 9px rgba(0, 0%, 0%, 0.5);
        background-clip: padding-box;
        width: 100%;
    }
    .popup-box .popup-header {
        border-bottom: 1px solid #ddd;
        padding: 15px 20px;
        position: relative;
    }
    .popup-box .popup-header h3 {
        padding-right: 15px;
    }
    .popup-close {
        color: #bbb;
        display: inline-block;
        font-size: 24px;
        position: absolute;
        right: 15px;
        text-decoration: none;
        top: 10px;
        transition: color 1s ease 0s;
    }
    .popup-close:hover {
        color: #222;
    }
    .popup-scroll {
        max-height: 400px;
        overflow: auto;
        position: relative;
    }
    .popup-box .popup-body {
        padding: 20px;
    }
    .popup-box .popup-footer {
        background-color: #f8f8f8;
        background-color: hsla(0, 0%, 0%, 0.02);
        border-top: 1px solid #ddd;
        padding: 1em;
    }
    .popup-overlay {
        background-color: #000;
        background-color: hsla(0, 0%, 0%, 0.3);
        filter:alpha(opacity=70);
        height: 100%;
        left: 0;
        opacity: 0.7;
        position: absolute;
        top: 0;
        width: 100%;
        z-index: 1;
    }
    .popup-box p {
        margin-bottom: 25px;
    }
    .popup-box p:last-child {
        margin-bottom: 0;
    }
    .extra-wrap {
        display: table;
        height: 100%;
        width: 100%;
    }
    .extra-inner {
        display: table-cell;
        vertical-align: middle;
    }
    b, strong {
            font-weight: 700;
    }
    .membership-plans .choose-palns .btn-to-select {
        margin: 40px auto 10px auto;
    }
    .d-flex {
        display: -ms-flexbox!important;
        display: flex!important;
    }
    .flex-wrap {
        -ms-flex-wrap: wrap!important;
        flex-wrap: wrap!important;
    }
    *, ::after, ::before {
        box-sizing: border-box;
    }
    .align-self-end {
        align-self: flex-end!important;
    }
    .w-100 {
        width: 100%!important;
    }
     .form-control {
        display: block;
        width: 100%;
        height: calc(1.5em + .75rem + 2px);
        padding: .375rem .75rem;
        font-size: 1rem;
        font-weight: 400;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        border: 1px solid #ced4da;
        border-radius: .25rem;
        transition: border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    }
    .contact-us-se address p {
        font-size: 20px;
    }
    .contact-us-se p{
            margin: 0;
            margin-bottom: 0;
    }
    .contact-us-se {
        color: #fff;
    }
    .contact-us-se .popup-close {
        top: 25px;
        right: 25px;
        color: #fff;
        font-size: 25px;
    }
    .row {
        display: -ms-flexbox;
        display: flex;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        margin-right: -15px;
        margin-left: -15px;
    }
    .position-relative {
        position: relative!important;
    }
    .card-header {
        padding: .75rem 1.25rem;
        margin-bottom: 0;
        background-color: rgba(0,0,0,.03);
        border-bottom: 1px solid rgba(0,0,0,.125);
    }
    .card-header:first-child {
        border-radius: calc(.25rem - 1px) calc(.25rem - 1px) 0 0;
    }
    .mb-0, .my-0 {
        margin-bottom: 0!important;
    }
    .card {
        position: relative;
        display: -ms-flexbox;
        display: flex;
        -ms-flex-direction: column;
        flex-direction: column;
        min-width: 0;
        word-wrap: break-word;
        background-color: #fff;
        background-clip: border-box;
        border: 1px solid rgba(0,0,0,.125);
        border-radius: .25rem;
    }
    html, body{
        font-weight:400;
    }
    #select_plan label.error {
        position: relative;
        bottom: 0;
    }
    .plan_price_igst,.plan_price_cgst,.plan_price_sgst,.plan_price,.conv_fee,.total_price,.amount,.discount_ten_price,.discount_subtotal_price{
        float: right;
        clear: both;
    }
    #select_plan .select2-container{
        background-clip: padding-box;
        border: 1px solid #ced4da;
        color: #495057;
        width: 100%;
        height: 45px;
        padding: .375rem .75rem;
    }
    #select_plan input#gstin_udise{
        height: 45px;
    }
    #select_plan  .select2-container--default .select2-selection--single .select2-selection__arrow{
        right: 10px;
    }

    #select_plan label.col-md-4 {
        line-height: 2.5;
    }
    .app-container .content-container .side-body.padding-top {
        padding-top: 0;
    }
    del {
        text-decoration-thickness: 3px;
        text-decoration-color: red;
    }
</style>
@endsection
@section('javascript')
<script type="text/javascript">
    var basic_plan = {name:'{{ General::pricing_plan_data(2)->name }}', price:'{{ General::pricing_plan_data(2)->membership_plan_price }}', tax:'{{ General::pricing_plan_data(2)->consent_recordent_report_gst }}', collection_fee:'{{ General::pricing_plan_data(2)->collection_fee }}'};

    var standard_plan = {name:'{{ General::pricing_plan_data(5)->name }}', price:'{{ General::pricing_plan_data(5)->membership_plan_price }}', tax:'{{ General::pricing_plan_data(5)->consent_recordent_report_gst }}', collection_fee:'{{ General::pricing_plan_data(5)->collection_fee }}'};

    var executive_plan = {name:'{{ General::pricing_plan_data(3)->name }}', price:'{{ General::pricing_plan_data(3)->membership_plan_price }}', tax:'{{ General::pricing_plan_data(3)->consent_recordent_report_gst }}', collection_fee:'{{ General::pricing_plan_data(3)->collection_fee }}'};

    var plan = {2:basic_plan, 5:standard_plan, 3:executive_plan};

    var state_id='{{isset(Auth::user()->state_id)?Auth::user()->state_id:0}}';
    var gstin_udise='{{isset(Auth::user()->gstin_udise)?Auth::user()->gstin_udise:""}}';
    var email='{{isset(Auth::user()->email)?Auth::user()->email:""}}';
    // console.log(state_id);

    function check_state(){
        var state_id_val=$('#state_id').val();
        if(state_id_val==36){
            $('.central_gst').hide();
            $('.state_gst').show();
        }else{
            $('.central_gst').show();
            $('.state_gst').hide();
        }
    }
    function check_required_fields(){
        var show_submit=0;
        if(state_id==0){
            show_submit++;
            $('.state_field').show();
        }else{
            $('.state_field').hide();
        }
        if(gstin_udise==0){
            show_submit++;
            $('.gstin_udise_field').show();
        }else{
            $('.gstin_udise_field').hide();
        }
        if(email==''){
            show_submit++;
            $('.email_field').show();
        }else{
            $('.email_field').hide();
        }
        if(show_submit==0){
            $('#submit_required').hide();
            $('.checkout_table').show();
            $('#checkout_button').show();
        }else{
            $('#submit_required').show();
            $('.checkout_table').hide();
            $('#checkout_button').hide();
        }
    }
jQuery(document).ready(function($) {
    var planIdFromProfile = $("#plan_id_from_profile").val();

    setTimeout(function(){
        $("#pay_now_"+planIdFromProfile).trigger('click');
    }, 1000);


    $('#submit_required').on('click',function(){
        var gstin_udise_val=$('#gstin_udise').val();
        var email_val=$('#email').val();
        var state_id_val=$('#state_id').val(),error=0;
        console.log(/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/i.test(gstin_udise_val));
        if(gstin_udise_val.toString().length == 11) {
            if(Number.isInteger(parseInt(gstin_udise_val))) {
              $('#gstin_udise_error').html("");
            } else {
              error++;
              $('#gstin_udise_error').html("Please enter a valid GSTIN/UDISE.");
            }
          } else {
            if(/^[0-9]{2}[A-Z]{5}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/i.test(gstin_udise_val)){
                $('#gstin_udise_error').html("");
            }else{
                error++;
                $('#gstin_udise_error').html("Please enter a valid GSTIN/UDISE.");
            }
          }

          if (/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(email_val))
          {
            $('#email_error').html("");
          }else{
            error++;
            $('#email_error').html("Please enter a valid email address");
          }



        if(state_id_val==0||state_id_val==''){
            error++;
            $('#state_id_error').html("Please Select State.");
        }else{
            $('#state_id_error').html("");
        }
        console.log(error);
        if(error==0){
            $('#submit_required').hide();
            $('.state_field').hide();
            $('.gstin_udise_field').hide();
            $('.email_field').hide();
            $('.checkout_table').show();
            $('#checkout_button').show();
            check_state();
        }
    });
 $('body').on('click','.select-plan', function(e) {
     var plan_id=$(this).data('plan_id');
     var gstinUdiseHidden = $("#gstin_udise_hidden").val();
     var profileVerifyHidden = $("#profile_verify_hidden").val();
     if(gstinUdiseHidden=="" ||  profileVerifyHidden=="") { window.location.href = "/update-profile/"+plan_id;
     return false;
     }
    e.preventDefault();
    check_state();
    check_required_fields();
    $('#plan_id_val').val(plan_id);
    $('.plan_name').html(plan[plan_id]['name']);
    $('.plan_price').html(plan[plan_id]['price']);
    var check_discount = true;
    $('#is_discount').val(1);
   //if(!$('#code_status').val() || !$('#code_used_status').val()) {
    if($('#code_status').val() ==0) {
       check_discount = false;
        $('#is_discount').val(0);
        $('.discount_ten').hide();
        $('.discount_subtotal').hide();
    }

    if(!check_discount) {
        var cgst=plan[plan_id]['price']*9/100;
        var sgst=parseFloat(cgst);
        cgst=sgst;
        var igst=cgst+sgst;
    } else {
        var discountPercnt = 100 - <?php echo setting('admin.one_code_discount')?>;
        var pricingPlan = plan[plan_id]['price']*discountPercnt/100;
        var cgst=pricingPlan*9/100;
        var sgst=parseFloat(cgst);
        cgst=sgst;
        var igst=cgst+sgst;
    }

    var conv_fee;
    conv_fee=0;
    var total_price;

    if(conv_fee<=0) {
        $('.conv_fee_tr').hide();
    }

    $('.amount_adjusted').hide();

    if(!check_discount) {
       total_price = parseFloat(plan[plan_id]['price'])+ sgst + cgst + conv_fee;
       $('.plan_name').html(plan[plan_id]['name']);
       $('.plan_price').html('₹ '+plan[plan_id]['price']);
       $('.plan_price_cgst').html('₹ '+cgst);
       $('.plan_price_sgst').html('₹ '+sgst);
       $('.plan_price_igst').html('₹ '+igst);
       $('.conv_fee').html('₹ '+conv_fee);
       console.log('total_price = '+total_price);
       $('.total_price').html('₹ '+total_price.toFixed(2));
       $('#select_plan').fadeIn();
    } else {
        var discountPercnt = 100 - <?php echo setting('admin.one_code_discount')?>;
        var plan_price_discount = plan[plan_id]['price']*discountPercnt/100;

        total_price = plan_price_discount;
        total_price+=sgst+cgst+conv_fee;
        var discount_price = plan[plan_id]['price']-plan_price_discount;

        $('.discount_ten_price').html(discount_price.toFixed(2));
        $('.discount_subtotal_price').html(plan_price_discount.toFixed(2));
        $('.plan_name').html(plan[plan_id]['name']);
        $('.plan_price').html(plan[plan_id]['price']);
        $('.plan_price_cgst').html(cgst.toFixed(2));
        $('.plan_price_sgst').html(sgst.toFixed(2));
        $('.plan_price_igst').html(igst.toFixed(2));
        $('.conv_fee').html(conv_fee);
        $('.total_price').html(total_price.toFixed(2));
        $('#select_plan').fadeIn();
    }
 });

 $('.popup-close').on('click', function() {
  $(this).closest('.popup-wrap').fadeOut();
  $(".payment_method").prop("checked", false);
 });
 $('#checkout_button').on('click',function(){
    var gstin_udise_val=$('#gstin_udise').val();
    var email_val=$('#email').val();
    var state_id_val=$('#state_id').val(),error=0;
    $.ajax({
       type: "GET",
       url: '{{route("user-update")}}',
       data: {state_id:state_id_val,user_id:'{{Auth::user()->id}}',gstin_udise:gstin_udise_val,email:email_val}, // serializes the form's elements.
       success: function(data)
       {
         console.log(data);
        // var response=JSON.parse(data);
        // console.log(response);
        if(data.status=='success'){

            var credit_report_type = '';
            @if(isset($credit_report_type) && $credit_report_type != null)
                credit_report_type = "&credit_report_type={{$credit_report_type}}";
            @endif

            window.location.href="{{route('register-pricing-plan')}}?pricing_plan_id="+$('#plan_id_val').val()+"&is_discount="+$('#is_discount').val()+credit_report_type;
        } else {
            check_required_fields();
            console.log(data.status);
            console.log(data.message);
            $('#email_error').html(data.message);
        }

       }
    });

 });
 $('.name').attr('placeholder','Name');
 $('.email').attr('placeholder','Email');
 $('.mobile').attr('placeholder','Mobile');
 $('.message').attr('placeholder','Message');

});
@if(!empty(Auth::user()->user_pricing_plan)&&Auth::user()->user_pricing_plan->pricing_plan_id!=1&&Auth::user()->user_pricing_plan->paid_status!=1)
    setTimeout(function(){

        $('#pay_now_{{Auth::user()->user_pricing_plan->pricing_plan_id}}').trigger('click');
        $('.btn-checkout').text('Pay Now');


}, 1000);




@endif
</script>

@endsection
@section('contact_us_scripts')
<script type="text/javascript">

</script>
@endsection
