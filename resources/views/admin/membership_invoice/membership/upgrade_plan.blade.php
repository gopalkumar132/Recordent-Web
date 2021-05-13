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
                    <!-- Basic Plan card -->
                    <div class="plan-box d-flex flex-wrap bg-yellow {{HomeHelper::getShowOrHidePlanSectionClass(2)}}">
                        <div class="title-contains w-100">
                            <div class="title-part text-blue-type text-center">
                                <h2 class="text-uppercase">{{ General::pricing_plan_data(2)->name }}</h2>
                                <p>₹{{ General::pricing_plan_data(2)->membership_plan_price }} / year</p>
                                <p class="applicable">Taxes Applicable </p>
                            </div>
                            <div class="btn-to-select w-100 align-self-end text-center">
                                @if(isset($_GET['plan_id']) && $_GET['plan_id'] == 2)
                                    <a href="#" class="select-plan btn-join" id="pay_now_2" data-plan_id=2>Renew</a>
                                @else
                                    @if(!empty(Auth::user()->user_pricing_plan) && Auth::user()->user_pricing_plan->pricing_plan_id == 2 && Auth::user()->user_pricing_plan->paid_status==1 && strtotime(Auth::user()->user_pricing_plan->end_date) > strtotime(date('Y-m-d H:i:s')))

                                        @if(HomeHelper::isPlanRenewable())
                                            <a href="#" class="select-plan btn-join" id="pay_now_2" data-plan_id=2>Renew</a>
                                        @else
                                            <a href="#" class="btn-join" disabled="disabled">Subscribed</a>
                                        @endif

                                    @elseif(HomeHelper::isPlanRenewable())
                                        <a href="#" class="select-plan btn-join" id="pay_now_2" data-plan_id=2>Subscribe</a>
                                    @else
                                        <a href="#" class="select-plan btn-join" id="pay_now_2" data-plan_id=2>Upgrade</a>
                                    @endif
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
                                            'rupees_per_customer' => 3]
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
                                    'rupees_per_customer' => 3]
                                )
                            </div>
                        </div>
                    </div>
                    <!-- Standard plan card -->
                    <div class="plan-box d-flex flex-wrap bg-blue csd-bg-yellow box {{HomeHelper::getShowOrHidePlanSectionClass(5)}}">
                        <div class="ribbon"><span>20% Off</span></div>
                        <div class="title-contains w-100">
                            <div class="title-part text-white csd-text-black text-center">
                                <h2 class="text-uppercase">{{ General::pricing_plan_data(5)->name }}</h2>
                                <p><del>1499</del>(₹{{ General::pricing_plan_data(5)->membership_plan_price }}/year)</p>
                                <p class="applicable">Taxes Applicable </p>
                            </div>
                            <div class="btn-to-select w-100 align-self-end text-center">
                                @if(isset($_GET['plan_id'])&&$_GET['plan_id']==5)
                                    <a href="#" class="select-plan btn-join" id="pay_now_5" data-plan_id=5>Renew</a>
                                @else
                                    @if(!empty(Auth::user()->user_pricing_plan) && Auth::user()->user_pricing_plan->pricing_plan_id == 5 && Auth::user()->user_pricing_plan->paid_status==1 && strtotime(Auth::user()->user_pricing_plan->end_date) > strtotime(date('Y-m-d H:i:s')))

                                        @if(HomeHelper::isPlanRenewable())
                                            <a href="#" class="select-plan btn-join" id="pay_now_5" data-plan_id=5>Renew</a>
                                        @else
                                            <a href="#" class="btn-join" disabled="disabled">Subscribed</a>
                                        @endif

                                    @elseif(HomeHelper::isPlanRenewable())
                                        <a href="#" class="select-plan btn-join" id="pay_now_5" data-plan_id=5>Subscribe</a>
                                    @else
                                        <a href="#" class="select-plan btn-join" id="pay_now_5" data-plan_id=5>Upgrade</a>
                                    @endif
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
                                            'rupees_per_customer' => 3]
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
                                    'rupees_per_customer' => 3]
                                )
                            </div>
                        </div>
                    </div>
                    <!-- Executive plan card -->
                    <div class="plan-box d-flex flex-wrap bg-yellow {{HomeHelper::getShowOrHidePlanSectionClass(3)}}">
                        <div class="title-contains w-100">
                            <div class="title-part text-blue-type text-center">
                                <h2 class="text-uppercase">{{ General::pricing_plan_data(3)->name }}</h2>
                                <p>₹{{ General::pricing_plan_data(3)->membership_plan_price }} / year</p>
                                <p class="applicable">Taxes Applicable</p>
                            </div>
                            <div class="btn-to-select w-100 align-self-end text-center">
                                @if(isset($_GET['plan_id'])&&$_GET['plan_id']==3)
                                    <a href="#" class="select-plan btn-join" id="pay_now_3" data-plan_id=3>Renew</a>
                                @else
                                    @if(!empty(Auth::user()->user_pricing_plan) && Auth::user()->user_pricing_plan->pricing_plan_id==3 && Auth::user()->user_pricing_plan->paid_status==1 && strtotime(Auth::user()->user_pricing_plan->end_date) > strtotime(date('Y-m-d H:i:s')))
                                        @if(HomeHelper::isPlanRenewable())
                                            <a href="#" class="select-plan btn-join" id="pay_now_3" data-plan_id=3>Renew</a>
                                        @else
                                            <a href="#" class="btn-join" disabled="disabled">Subscribed</a>
                                        @endif
                                    @elseif(!empty(Auth::user()->user_pricing_plan) && HomeHelper::isPlanRenewable())
                                        <a href="#" class="select-plan btn-join" id="pay_now_3" data-plan_id=3>Subscribe</a>
                                    @else
                                        <a href="#" id="pay_now_3"  class="select-plan btn-join"  data-plan_id=3>Upgrade</a>
                                    @endif
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
                                            'rupees_per_customer' => 2.5]
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
                                    'rupees_per_customer' => 2.5]
                                )
                            </div>
                        </div>
                    </div>
                    <!-- Corporate plan card -->
                    <div class="plan-box d-flex flex-wrap bg-blue csd-bg-yellow">
                        <div class="title-contains w-100">
                            <div class="title-part text-white csd-text-black text-center">
                                <h2 class="text-uppercase">{{ General::pricing_plan_data(4)->name }}</h2>
                                <p>Contact for Pricing</p>
                                <p class="applicable" style="opacity:0">a</p>
                            </div>
                            <div class="btn-to-select w-100 align-self-end text-center">
                                <a href="#" class="btn-join contact-us corporate-plan">Contact Us</a>
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

            @if(HomeHelper::isPlanRenewable())
                @if(!empty(Auth::user()->user_pricing_plan) && Auth::user()->user_pricing_plan->pricing_plan_id == 1 && Auth::user()->user_pricing_plan->paid_status==1)
                    <?php $fre_plan_button_name = "Renew"; ?>
                @else
                    <?php $fre_plan_button_name = "Subscribe"; ?>
                @endif
                <div class="row">
                    <div class="col-md-12 text-center">
                        <p>
                            <a href="{{route('upgrade-pricing-plan')}}?pricing_plan_id=1&&upgrade=1" style="text-decoration: underline;" >Click here to {{$fre_plan_button_name}} Free Trail</a>
                        </p>
                    </div>
                </div>
            @endif
    </section>
    <!-- Checkout popup -->
    @include('admin.membership_invoice.membership.partials.plan_checkout_popup', ['states' => $states])
    <!-- corporate plan -->
    <div id="corporate_plan_comments" class="popup-wrap">
        <div class="popup-overlay"></div>
        <div class="extra-wrap">
            <div class="extra-inner">
                <div class="popup-outer">
                    <div class="popup-box">
                        @if(HomeHelper::isPlanRenewable() && Auth::user()->user_pricing_plan->pricing_plan_id == 4)
                            <?php $upgrade = "0"; ?>
                        @else
                            <?php $upgrade = "1"; ?>
                        @endif
                        <form action="{{route('upgrade-corporate-plan')}}" method="GET">
                            <header class="popup-header">
                                <a class="popup-close" href="javascript:void(0);">×</a>
                                <h4 class="text-left">Corporate Plan</h4>
                            </header>
                            <div class="">
                                <div class="">
                                    <section id="contact-us">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <br>
                                                    <label for="comments" class="col-md-4">Comments</label>
                                                    <div class="col-md-8">
                                                        <textarea class="form-control" name="comments" required></textarea>
                                                        <label id="gstin_udise_error" class="error"></label>
                                                    </div>
                                                    <input type="hidden" name="upgrade" value="{{$upgrade}}">
                                                </div>
                                            </div>
                                        </div>
                                    </section>
                                </div>
                            </div>
                            <footer class="popup-footer">
                                <div class="pull-right">
                                    <button type="submit" name="submit" class="btn btn-info">Submit</button>
                                </div>
                                <div class="clearfix"></div>
                            </footer>
                        </form>
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

        a[disabled="disabled"] {
            pointer-events: none;
        }

        del {
            text-decoration-thickness: 3px;
            text-decoration-color: red;
        }
    </style>
    <link rel="stylesheet" href="{{asset('upgrade_membership/css/upgrade_plan.css')}}">
@endsection
@section('javascript')
    <script type="text/javascript">

        var basic_plan = {name:'{{ General::pricing_plan_data(2)->name }}', price:'{{ General::pricing_plan_data(2)->membership_plan_price}}' ,tax:'{{ General::pricing_plan_data(2)->consent_recordent_report_gst }}',collection_fee:'{{ General::pricing_plan_data(2)->collection_fee }}', amount_adjusted: '{{HomeHelper::getMembershipUpgradeAdjustedAmount(Auth::id(), 2)}}'};

        var standard_plan = {name:'{{ General::pricing_plan_data(5)->name }}', price:'{{ General::pricing_plan_data(5)->membership_plan_price }}' ,tax:'{{ General::pricing_plan_data(5)->consent_recordent_report_gst }}',collection_fee:'{{ General::pricing_plan_data(5)->collection_fee }}', amount_adjusted: '{{HomeHelper::getMembershipUpgradeAdjustedAmount(Auth::id(), 5)}}'};

        var executive_plan = {name:'{{ General::pricing_plan_data(3)->name }}', price:'{{ General::pricing_plan_data(3)->membership_plan_price }}', tax:'{{ General::pricing_plan_data(3)->consent_recordent_report_gst }}', collection_fee:'{{ General::pricing_plan_data(3)->collection_fee }}', amount_adjusted: '{{HomeHelper::getMembershipUpgradeAdjustedAmount(Auth::id(), 3)}}'};

        var plan = {2:basic_plan, 5:standard_plan, 3:executive_plan};
        var state_id = '{{isset(Auth::user()->state_id)?Auth::user()->state_id:0}}';
        var gstin_udise = '{{isset(Auth::user()->gstin_udise)?Auth::user()->gstin_udise:""}}';
        var email = '{{isset(Auth::user()->email)?Auth::user()->email:""}}';
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

                if (/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(email_val)){
                    $('#email_error').html("");
                } else{
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

            $('body').on('click','.corporate-plan', function(e) {
                e.preventDefault();
                $('#corporate_plan_comments').fadeIn();
            });

            $('body').on('click','.select-plan', function(e) {
                e.preventDefault();

                check_state();
                check_required_fields();
                var plan_id = $(this).data('plan_id');
                var tax = parseFloat(plan[plan_id]['tax']);
                var price = parseFloat(plan[plan_id]['price']);

                if (plan[plan_id]['amount_adjusted'] <= 0) {
                    $('.amount_adjusted').hide();
                } else {
                    price = price - plan[plan_id]['amount_adjusted'];
                    $('.amount_adjusted_value').html('₹ -'+plan[plan_id]['amount_adjusted']);
                    $('.plan_sub_total').show();
                    $('.plan_subtotal_price').html('₹ '+price);
                }

                $('#plan_id_val').val(plan_id);
                $('.plan_name').html(plan[plan_id]['name']);
                $('.plan_price').html(plan[plan_id]['price']);

                var cgst = price*tax/200;
                var sgst = parseFloat(cgst);
                cgst = sgst;
                var igst = cgst+sgst;

                var conv_fee;
                conv_fee = 0.0;//parseFloat(plan[plan_id]['collection_fee']);
                var total_price;

                if(conv_fee<=0){
                    $('.conv_fee_tr').hide();
                }

                total_price = price+sgst+cgst+conv_fee;

                $('.plan_name').html(plan[plan_id]['name']);
                $('.plan_price').html('₹ '+plan[plan_id]['price']);
                $('.plan_price_cgst').html('₹ '+cgst);
                $('.plan_price_sgst').html('₹ '+sgst);
                $('.plan_price_igst').html('₹ '+igst);
                $('.conv_fee').html('₹ '+conv_fee);
                $('.total_price').html('₹ '+total_price.toFixed(2));
                $('#select_plan').fadeIn();
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
                        // var response=JSON.parse(data);
                        // console.log(response);
                        if(data.status=='success') {
                            @if(Request::is('upgrade-plan') || Request::segment(1) == 'upgrade-plan-due' || Request::segment(1) == 'upgrade-plan-business')
                                window.location.href="{{route('upgrade-pricing-plan')}}?pricing_plan_id="+$('#plan_id_val').val()+'&&upgrade=1&&id={{isset($id) ? $id : "0"}}&&type={{isset($type) ? $type : "insert"}}&&due_type={{Request::segment(1)}}';
                            @else
                                window.location.href="{{route('renew-pricing-plan')}}?pricing_plan_id="+$('#plan_id_val').val()+'&&renew=1';
                            @endif

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

        @if(!empty(Auth::user()->user_pricing_plan) && Auth::user()->user_pricing_plan->pricing_plan_id !=1 && Auth::user()->user_pricing_plan->paid_status !=1 )
            setTimeout(function(){

                $('#pay_now_{{Auth::user()->user_pricing_plan->pricing_plan_id}}').trigger('click');
                $('.btn-checkout').text('Pay Now');
            }, 1000);
        @endif
    </script>
@endsection
@section('contact_us_scripts')
@endsection
