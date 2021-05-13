@extends('layouts_front_new.master')
@section('meta-title', config('seo_meta_tags.solutions_page.title'))
@section('meta-description', config('seo_meta_tags.solutions_page.description'))
@section('canonical-url')
    <link rel="canonical" href="{{config('app.url')}}solutions" />
@endsection
@section('content')
<section class="report-due-online" id="report-payments">
    <div class="container">
        <div class="the-title text-center" data-aos="zoom-in" data-aos-duration="2000">
            <h2>Submit Dues Online</h2>
        </div>  
        <div class="row text-center about-point-report">
            <div class="col-12 col-md-6 col-xl-3 col-lg-3 res-margin-cla" data-aos="fade-right" data-aos-duration="2500">
                <div class="img-part">
                    <img src="{{asset('front_new/images/flexible_data_entry.png')}}" alt="" class="img-fluid">
                </div>
                <div class="about-text-report">
                    <h3>Flexible Data Entry</h3>
                    <p>‘Submit one or many customer dues instantly</p>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3 col-lg-3 res-margin-cla" data-aos="fade-up" data-aos-duration="2500">
                <div class="img-part">
                    <img src="{{asset('front_new/images/data_security_a.png')}}" alt="" class="img-fluid">
                </div>
                <div class="about-text-report">
                    <h3>Data Security</h3>
                    <p>Highest data encryption standards</p>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3 col-lg-3 res-margin-cla" data-aos="fade-down" data-aos-duration="2500">
                <div class="img-part">
                    <img src="{{asset('front_new/images/automated_data_entry.png')}}" alt="" class="img-fluid">
                </div>
                <div class="about-text-report">
                    <h3>Automated Data Entry</h3>
                    <p>Integrate your accounting system</p>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3 col-lg-3 res-margin-cla" data-aos="fade-left" data-aos-duration="2500">
                <div class="join-now-part">
                    <p>Easy Sign Up. Become a Member</p>
                    <a href="{{config('app.url')}}register" class="text-uppercase btn-joinnow">Join Now</a>
                    <p>Already a member? <a href="{{config('app.url')}}admin/login"> Login</a> </p>
                </div>
            </div>
        </div>                             
    </div>
</section>

<section class="customer-engagement" id="messaging">
    <div class="container">
        <div class="the-title text-center" data-aos="zoom-in" data-aos-duration="2000">
            <h2>Customer Engagement Platform</h2>
        </div>
        <div class="row text-center">
            <div class="col-12 col-md-6 col-xl-3 col-lg-3 res-margin-cla" data-aos="zoom-out-left" data-aos-duration="1000">
                <div class="eng-img">
                    <img src="{{asset('front_new/images/send_payment.png')}}" alt="" class="img-fluid">
                </div>
                <div class="font-size-com">
                    <p>Send payment reminders through multiple channels</p>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3 col-lg-3 res-margin-cla" data-aos="zoom-out-right" data-aos-duration="1500">
                <div class="eng-img">
                    <img src="{{asset('front_new/images/select_from.png')}}" alt="" class="img-fluid">
                </div>
                <div class="font-size-com">
                    <p>Select effective messaging templates or customize your own</p>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3 col-lg-3 res-margin-cla" data-aos="zoom-in-left" data-aos-duration="2000">
                <div class="eng-img">
                    <img src="{{asset('front_new/images/set_schedules.png')}}" alt="" class="img-fluid">
                </div>
                <div class="font-size-com">
                    <p>Schedule automated payment reminders</p>
                </div>
            </div>
            <div class="col-12 col-md-6 col-xl-3 col-lg-3 res-margin-cla" data-aos="zoom-in-right" data-aos-duration="1500">
                <div class="eng-img">
                    <img src="{{asset('front_new/images/send_effective.png')}}" alt="" class="img-fluid">
                </div>
                <div class="font-size-com">
                    <p>Right message at the Right time through our smart messaging technology</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="options-about-money" id="payment-options" >
    <div class="indi-options" id="payment-options">
        <div class="container">
            <div class="d-flex align-items-center csd-flex-wrap">
                <div class="payment-img" data-aos="fade-up" data-aos-easing="linear" data-aos-duration="1000">
                    <img src="{{asset('front_new/images/digital_payment.png')}}" alt="" class="img-fluid">
                </div>
                <div class="payment-text font-size-com" data-aos="fade-down" data-aos-easing="linear" data-aos-duration="1000">
                    <div>
                        <h3>Digital Payment Options</h3>
                        <!--<span class="b-s">Coming soon </span>-->
                        <p>Offer your customers choice of payment options to payoff their past dues through Recordent.  Payments can be made instantly and at anytime by your customers.</p>
                    </div>   
                    <div class="b-s-20"></div>                         
                    <div class="logo-slider">
                        <div class="owl-carousel owl-theme partner-slider">

                            <?php
                            $payment_options=["amex.png","master_logo.png","2_UPI.png","2_PhonePe.png"
                                                ,"2_GPay.png","2_Netbanking.png","2_Credit.png",
                                                "2_Debit.png","2_Wallet.png","visa.png"];

                        foreach($payment_options as $payment_names)
                        {?>
                            <div class="item position-relative">
                            <div class="logo-img">
                                    <img src="{{asset('front_new/images')}}/<?php echo $payment_names?>" alt="" class="img-fluid">
                                </div>                                        
                                <a href="javascript:void(0)" class="position-absolute full-box-link"></a>
                            </div>
                           <?php }?>
                        </div>
                    </div>
                </div>                        
            </div>
        </div>                
    </div>
    <div class="indi-options" id="payment-plans">
        <div class="container">
            <div class="d-flex align-items-center csd-flex-wrap">
                <div class="payment-img" data-aos="fade-right" data-aos-easing="linear" data-aos-duration="1000">
                    <img src="{{asset('front_new/images/loan_options.png')}}" alt="" class="img-fluid">
                </div>
                <div class="payment-text font-size-com" data-aos="fade-left" data-aos-easing="linear" data-aos-duration="1000">
                    <div>
                        <h3>Loan Options </h3>
                        <span class="b-s">Coming soon </span>
                        <p>Through Recordent provide loan options to your customers from ﬁnancing companies to pay off their past dues. Your customers may have to qualify for the loan.</p>
                    </div>
                    <!--<div class="b-s-20"></div>                         -->
                    <!--<div class="logo-slider">-->
                    <!--    <div class="owl-carousel owl-theme partner-slider">-->
                    <!--        <div class="item position-relative">-->
                    <!--            <div class="logo-img">-->
                    <!--                <img src="{{asset('front_new/images/logo/circle.png')}}" alt="" class="img-fluid">-->
                    <!--            </div>                                        -->
                    <!--            <a href="javascript:void(0)" class="position-absolute full-box-link"></a>-->
                    <!--        </div>-->
                    <!--        <div class="item position-relative">-->
                    <!--            <div class="logo-img">-->
                    <!--                <img src="{{asset('front_new/images/logo/hexa.png')}}" alt="" class="img-fluid">-->
                    <!--            </div>                                        -->
                    <!--            <a href="javascript:void(0)" class="position-absolute full-box-link"></a>-->
                    <!--        </div>-->
                    <!--        <div class="item position-relative">-->
                    <!--            <div class="logo-img">-->
                    <!--                <img src="{{asset('front_new/images/logo/monero.png')}}" alt="" class="img-fluid">-->
                    <!--            </div>                                        -->
                    <!--            <a href="javascript:void(0)" class="position-absolute full-box-link"></a>-->
                    <!--        </div>-->
                    <!--        <div class="item position-relative">-->
                    <!--            <div class="logo-img">-->
                    <!--                <img src="{{asset('front_new/images/logo/treva.png')}}" alt="" class="img-fluid">-->
                    <!--            </div>                                        -->
                    <!--            <a href="javascript:void(0)" class="position-absolute full-box-link"></a>-->
                    <!--        </div>-->
                    <!--    </div>-->
                    <!--</div>                -->
                </div>
            </div>
        </div>
    </div>
    <div class="indi-options" id="finance-options">
        <div class="container">
            <div class="d-flex align-items-center csd-flex-wrap">
                <div class="payment-img" data-aos="fade-up" data-aos-easing="linear" data-aos-duration="1000">
                    <img src="{{asset('front_new/images/installment_options.png')}}" alt="" class="img-fluid">
                </div>
                <div class="payment-text font-size-com" data-aos="fade-down" data-aos-easing="linear" data-aos-duration="1000">
                    <h3>Installment Options</h3>
                    <p>Offer your customers ﬂexibility to settle dues in 3, 6, or 12 months through Recordent.</p>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="view-customer-report" id="customer-reports">
    <div class="container">
        <div class="the-title text-center" data-aos="zoom-in" data-aos-duration="2000">
        </div>                
        <div class="row align-items-center">
            <div class="col-12 col-md-12 col-lg-6 col-xl-6" data-aos="fade-right" data-aos-easing="linear" data-aos-duration="1000">
                <div class="customer-rep-img">
                    <img src="{{asset('front_new/images/Infographic.png')}}" alt="">
                </div>
            </div>
            <div class="col-12 col-md-12 col-lg-6 col-xl-6" data-aos="fade-left" data-aos-easing="linear" data-aos-duration="1000">
                <div class="font-size-com customer-rep-text" style="font-size: 18px !important;">
                    <p>The best option to reduce credit risk from your <br>business customers is verifying their payment history.</p>
                    <p>And that is only possible on our PLATFORM</p>
                </div>
                <div class="join-now-part text-center" style="padding-top:20px;">
                    <p>Easy Sign Up. Become a Member</p>
                    <a href="{{config('app.url')}}register" class="text-uppercase btn-joinnow">check now</a>
                    <p>Already a member? <a href="{{config('app.url')}}admin/login"> Login</a> </p>
                </div>
                <div class="text-center" style="padding-top:71px;">
                <p style="color:#616160 !important;">Data Powered by   &nbsp;&nbsp;&nbsp;<img src="{{asset('front_new/images/equifax_logo.png')}}" alt="" style="width: 167px;"></p>
            </div>
            </div>
            
        </div>                
    </div>
</section>
<!-- 
<section class="bg-white equifax-section">
    <div class="container">
        <div class="d-flex flex-wrap align-items-center">
            <div class="left-logo">
                <img src="{{asset('front_new/images/equifax_logo.png')}}" alt="">
            </div>
            <div class="right-contain">
                <h3>Comprehensive Customer Report</h3>
                <p>Recordent report also comprises payment history data from one of the leading cedit bureau to provide complete risk analysis.</p>
            </div>
        </div>
    </div>
</section> -->

@endsection