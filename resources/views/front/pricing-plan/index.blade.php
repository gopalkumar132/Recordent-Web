@extends('layouts_front_new.master')
@section('meta-title', config('seo_meta_tags.pricing_plan_page.title'))
@section('meta-description', config('seo_meta_tags.pricing_plan_page.description'))
@section('canonical-url')
    <link rel="canonical" href="{{config('app.url')}}pricing-plan" />
@endsection
@section('content')
<style>
    .the-title-h1 h1{
        color: #5f94c4;
        font-family: var(--font-rubik);
        font-weight: 700;
        font-size: 44px;
    }
    del {
        text-decoration-thickness: 3px;
        text-decoration-color: red;
    }
    @media (max-width: 479px) {
        .the-title-h1 h1 {
            font-size: 26px;
        }
    }

    .credit-report-container {
        background-color: #4652b3;
    }

    .credit-report-section{
        padding: 0px !important;
    }


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

 <link rel="stylesheet" href="{{asset('front/css/pricing_plan_credit_report_section.css')}}">

<section class="membership-plans plans-price bg-white">
    <div class="container">
        <div class="the-title text-center">
            <h2 class="text-uppercase">Membership Plans</h2>
            <p>Take a plan that works with your business</p>
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
                    <div class="btn-to-select w-100 align-self-end text-center">
                        <a href="{{config('app.url')}}register?pricing_plan_id=2" class="btn-join">Join Now</a>
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
                    <div class="btn-to-select w-100 align-self-end text-center">
                        <a href="{{config('app.url')}}register?pricing_plan_id=5" class="btn-join">Join Now</a>
                    </div>
                </div>
                <div class="plan-box d-flex flex-wrap bg-yellow">
                    <div class="title-contains w-100">
                        <div class="title-part text-blue-type text-center">
                            <h2 class="text-uppercase">{{ General::pricing_plan_data(3)->name }}</h2>
                            <p>₹{{ General::pricing_plan_data(3)->membership_plan_price }} / year</p>
                            <p class="applicable">Taxes Applicable</p>
                        </div>
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
                    <div class="btn-to-select w-100 align-self-end text-center">
                        <a href="{{config('app.url')}}register?pricing_plan_id=3" class="btn-join">Join Now</a>
                    </div>
                </div>
                <div class="plan-box d-flex flex-wrap bg-blue csd-bg-yellow">
                    <div class="title-contains w-100">
                        <div class="title-part text-white csd-text-black text-center">
                            <h2 class="text-uppercase">Corporate</h2>
                            <p>Contact for Pricing</p>
                            <p class="applicable" style="opacity:0">a</p>
                        </div>
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
                    <div class="btn-to-select w-100 align-self-end text-center">
                        <a href="{{route('aboutus')}}#contact-us" class="btn-join">Contact Us</a>
                    </div>
                </div>
            </div>
            <br>
            <div class="row">
                <div class="col-md-12 text-center">
                    <p>
                        <a href="{{config('app.url')}}register?pricing_plan_id=1" style="text-decoration: underline; font-size:25px;" ><b>Click here to Start Your Free Trail</b></a>
                    </p>
                </div>
            </div>
        </div>
        <div class="in-details-tier">
            <div class="d-flex justify-content-between cmd-flex-wrap">
                <div class="single-w bg-yellow d-flex flex-wrap">
                    <div class="bg-yellow text-black cmd-w-100">
                        <div class="text-part upper-part">
                            <h3 class="text-uppercase text-center">Tier 1</h3>
                            <p class="font-weight-bold">Notifications / Reminders</p>
                            <ul>
                                <li>1 SMS, 1 IVR and 2 Emails</li>
                                <li>Customer due can be transferred to Tier 2 at any time</li>
                            </ul>
                        </div>
                    </div>
                    <div class="bg-blue text-white align-self-end cmd-w-100">
                        <div class="text-part bottom-part">
                            <p class="font-weight-bold">Subscribe to notifcation packs to send additional SMS, IVR and Emails</p>
                        </div>
                    </div>
                </div>
                <div class="double-w bg-yellow d-flex flex-wrap">
                    <div class="bg-yellow text-black w-100">
                        <div class="text-part upper-part">
                            <h3 class="text-uppercase text-center">Tier 2</h3>
                            <p class="font-weight-bold">Notifications / Reminders until the due is paid</p>
                            <ul>
                                <li>7 SMS, 1 IVR and 8 Emails per month for first 6 months</li>
                                <li>1 SMS and 1 Email per month after 6th month</li>
                                <li>Customer due can be transferred to 3rd party legal debt settlement at any time (Processing fees of ₹500 applicable)</li>
                            </ul>
                        </div>
                    </div>
                    <div class="bg-blue text-white w-100 align-self-end">
                        <div class="text-part bottom-part">
                            <p class="font-weight-bold">Subscribe to notification packs to send addtional SMS, IVR and Emails after 6th month</p>
                        </div>
                    </div>
                </div>
                <div class="single-w d-flex align-items-center justify-content-center bg-off-white condi-box">
                    <p>*Tier 2 Collection Fee is 1% of the customer due or ₹50, whichever is higher</p>
                </div>
            </div>
            </br>
            </br>
            <div class="the-title text-center">
                <h2 class="text-uppercase">No Hidden Charges</h2>
            </div>
        </div>
    </div>
</section>

<section class="credit-report-section bg-white plans-price">
    <div class="container">
        <div class="credit-report-text">
            <div class="ic-report">
                <img src="{{asset('front_new/images/ic_report_i.png')}}" alt="">
            </div>
            <div class="the-title text-center">
                <h2 class="text-uppercase">Get Your credit report</h2>
            </div>
        </div>
    </div>
    <div class="credit-report-container final-layer">
        <div class="container flag_div">
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="polaroid"  id="layer3">
                        <div class="container-fluid">
                            <br><br><br>
                            <img src="{{asset('front_new/images/team/india.png')}}" alt="Recordent" width="102px;" height="72px;">
                            <br><br><br><br>
                            <h4 class="india_credit_report_text"><b>INDIVIDUAL CUSTOMER <br>CREDIT REPORT</b></h4>
                            <br>
                            <a href="{{route('admin.credit-report')}}?credit_report_type=1" class="get-report">Get Report</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="polaroid"  id="layer3">
                        <div class="container-fluid">
                            <br><br><br>
                            <img src="{{asset('front_new/images/team/india_b.png')}}" alt="Recordent" width="102px;" height="72px;">
                            <br><br><br><br>
                            <h4 class="india_credit_report_text"><b>BUSINESS CUSTOMER <br>CREDIT REPORT</b></h4>
                            <br>
                            <a href="{{route('admin.credit-report')}}?credit_report_type=1" class="get-report">Get Report</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 col-xs-12">
                    <div class="polaroid" id="layer3" style="background: linear-gradient(to bottom, #9c7fdf 0%, #463864 100%)">
                        <div class="container-fluid">
                            <br><br><br>
                            <img src="{{asset('front_new/images/team/us.png')}}" alt="Recordent" width="106px;" height="75px;">
                            <br><br><br><br>
                            <h4 class="us_credit_report_text"><b>US BUSINESS CUSTOMER <br>CREDIT REPORT</b></h4>
                            <br>
                            <a href="{{route('admin.credit-report')}}?credit_report_type=1" class="get-report">Get Report</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
