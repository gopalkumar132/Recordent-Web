@extends('voyager::master')

@section('page_header')
@stop
@section('content')
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
                        <div class="plan-box d-flex flex-wrap bg-yellow {{(!empty(Auth::user()->user_pricing_plan)&&Auth::user()->user_pricing_plan->pricing_plan_id>1)?'hide-plan':''}}">
                            <div class="title-contains w-100">
                                <div class="title-part text-blue-type text-center">
                                    <h2 class="text-uppercase">{{ General::pricing_plan_data(1)->name }}</h2>
                                    <p>Free</p>
                                    <p class="applicable" style="opacity:0">a</p>
                                </div>
                                <div class="btn-to-select w-100 align-self-end text-center">
                                    @if(isset($_GET['plan_id'])&&$_GET['plan_id']==1)
                                        <a href="{{route('upgrade-pricing-plan')}}?pricing_plan_id=1&&upgrade=1" class=" btn-join" >Renew</a>
                                    @else
                                        @if(!empty(Auth::user()->user_pricing_plan)&&Auth::user()->user_pricing_plan->pricing_plan_id==1)
                                             @if(strtotime(Auth::user()->user_pricing_plan->end_date) < strtotime(date('Y-m-d H:i:s',strtotime('+10 days'))))
                                                <a href="{{route('upgrade-pricing-plan')}}?pricing_plan_id=1&&upgrade=1" class=" btn-join" >Renew</a>
                                            @else
                                                <a href="#" class="btn-join" disabled="disabled">Subscribed</a>
                                            @endif
                                        @elseif(!empty(Auth::user()->user_pricing_plan)&&Auth::user()->user_pricing_plan->pricing_plan_id>1)
                                        <a href="#" class="btn-join" disabled="disabled">Upgrade</a>
                                        @else
                                        <a href="{{route('upgrade-pricing-plan')}}?pricing_plan_id=1&&upgrade=1" class=" btn-join" >Upgrade</a>
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
                                            <ul class="text-center text-black blue-line some-points">
                                                <li>Upto <b>{{ General::pricing_plan_data(1)->free_customer_limit }} Customers</b></li>
                                                <li><b>₹25</b> per additional customer</li>
                                                <li><b>₹100</b> Individual credit &amp; payment history report</li>
                                                <li><b>₹1200</b> Business credit &amp; payment history report</li>
                                            </ul>
                                            <div class="heighlight-part bg-blue text-white position-relative text-center">
                                                <p><b>0%</b> Collection fee before due date (Tier-1)</p>
                                                <p><b>1%</b> Collection fee* on or after due date (Tier-2)</p>
                                                <p><b>1%</b> Collection fee* on tier-1 to tier-2 transfer</p>
                                            </div>
                                            <ul class="blue-line text-black some-padding some-points point-wid-sign">
                                                <li class="ic-sign ic-green-sign position-relative">Installment &amp; payment options to customers</li>
                                                <li class="ic-sign ic-red-sign position-relative">Standard reports</li>
                                            </ul>
                                        </div>                                       
                                    </div>
                                </div>
                                <div class="csd-d-none">
                                    <ul class="text-center text-black blue-line some-points">
                                        <li>Upto <b>{{ General::pricing_plan_data(1)->free_customer_limit }}  Customers</b></li>
                                        <li><b>₹25</b> per additional customer</li>
                                        <li><b>₹100</b> Individual credit &amp; payment history report</li>
                                        <li><b>₹1200</b> Business credit &amp; payment history report</li>
                                    </ul>
                                    <div class="heighlight-part bg-blue text-white position-relative text-center">
                                        <p><b>0%</b> Collection fee before due date (Tier-1)</p>
                                        <p><b>1%</b> Collection fee* on or after due date (Tier-2)</p>
                                        <p><b>1%</b> Collection fee* on tier-1 to tier-2 transfer</p>
                                    </div>
                                    <ul class="blue-line text-black some-padding some-points point-wid-sign">
                                        <li class="ic-sign ic-green-sign position-relative">Installment &amp; payment options to customers</li>
                                        <li class="ic-sign ic-red-sign position-relative">Standard reports</li>
                                    </ul>
                                </div>
                                
                            </div>
                        </div>
                        <div class="plan-box d-flex flex-wrap bg-blue csd-bg-yellow {{(!empty(Auth::user()->user_pricing_plan)&&Auth::user()->user_pricing_plan->pricing_plan_id>2)?'hide-plan':''}}">
                            <div class="title-contains w-100">
                                <div class="title-part text-white csd-text-black text-center">
                                    <h2 class="text-uppercase">{{ General::pricing_plan_data(2)->name }}</h2>
                                    <p>₹{{ General::pricing_plan_data(2)->membership_plan_price }} / year</p>
                                    <p class="applicable">Taxes Applicable </p>
                                </div>
                                <div class="btn-to-select w-100 align-self-end text-center">
                                    @if(isset($_GET['plan_id'])&&$_GET['plan_id']==2)
                                        <a href="#" class="select-plan btn-join" id="pay_now_2" data-plan_id=2>Renew</a>
                                    @else
                                        @if(!empty(Auth::user()->user_pricing_plan)&&Auth::user()->user_pricing_plan->pricing_plan_id==2&&Auth::user()->user_pricing_plan->paid_status==1&&strtotime(Auth::user()->user_pricing_plan->end_date)>strtotime(date('Y-m-d H:i:s')))
                                            

                                            @if(strtotime(Auth::user()->user_pricing_plan->end_date) < strtotime(date('Y-m-d H:i:s',strtotime('+10 days'))))
                                                <a href="#" class="select-plan btn-join" id="pay_now_2" data-plan_id=2>Renew</a>
                                            @else
                                                <a href="#" class="btn-join" disabled="disabled">Subscribed</a>
                                            @endif

                                        @elseif(!empty(Auth::user()->user_pricing_plan)&&Auth::user()->user_pricing_plan->pricing_plan_id>2)
                                            <a href="#" class="btn-join" disabled="disabled">Upgrade</a>
                                        @else
                                            <a href="#" class="select-plan btn-join" id="pay_now_2" data-plan_id=2>Upgrade</a>
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
                                            <ul class="text-center text-white csd-text-black csd-blue-line white-line some-points">
                                                <li>Upto <b>{{ General::pricing_plan_data(2)->free_customer_limit }} Customers</b></li>
                                                <li><b>₹20</b> per additional customer</li>
                                                <li><b>₹80</b> Individual credit &amp; payment history report</li>
                                                <li><b>₹950</b> Business credit &amp; payment history report</li>
                                            </ul>
                                            <div class="heighlight-part bg-yellow csd-bg-blue text-black csd-text-white position-relative text-center">
                                                <p><b>0%</b> Collection fee before due date (Tier-1)</p>
                                                <p><b>1%</b> Collection fee* on or after due date (Tier-2)</p>
                                                <p><b>0.8%</b> Collection fee* on tier-1 to tier-2 transfer</p>
                                            </div>
                                            <ul class="white-line text-white some-padding csd-text-black csd-blue-line some-points point-wid-sign">
                                                <li class="ic-sign ic-green-sign position-relative">Installment &amp; payment options to customers</li>
                                                <li class="ic-sign ic-green-sign position-relative">Quarterly Standard reports</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>                                

                                <div class="csd-d-none">
                                    <ul class="text-center text-white csd-text-black csd-blue-line white-line some-points">
                                        <li>Upto <b>{{ General::pricing_plan_data(2)->free_customer_limit }} Customers</b></li>
                                        <li><b>₹20</b> per additional customer</li>
                                        <li><b>₹80</b> Individual credit &amp; payment history report</li>
                                        <li><b>₹950</b> Business credit &amp; payment history report</li>
                                    </ul>
                                    <div class="heighlight-part bg-yellow csd-bg-blue text-black csd-text-white position-relative text-center">
                                        <p><b>0%</b> Collection fee before due date (Tier-1)</p>
                                        <p><b>1%</b> Collection fee* on or after due date (Tier-2)</p>
                                        <p><b>0.8%</b> Collection fee* on tier-1 to tier-2 transfer</p>
                                    </div>
                                    <ul class="white-line text-white some-padding csd-text-black csd-blue-line some-points point-wid-sign">
                                        <li class="ic-sign ic-green-sign position-relative">Installment &amp; payment options to customers</li>
                                        <li class="ic-sign ic-green-sign position-relative">Quarterly Standard reports</li>
                                    </ul>
                                </div>                                
                            </div>
                        </div>
                        <div class="plan-box d-flex flex-wrap bg-yellow {{(!empty(Auth::user()->user_pricing_plan)&&Auth::user()->user_pricing_plan->pricing_plan_id>3)?'hide-plan':''}}">
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
                                        @if(!empty(Auth::user()->user_pricing_plan)&&Auth::user()->user_pricing_plan->pricing_plan_id==3&&Auth::user()->user_pricing_plan->paid_status==1&&strtotime(Auth::user()->user_pricing_plan->end_date)>strtotime(date('Y-m-d H:i:s')))
                                             @if(strtotime(Auth::user()->user_pricing_plan->end_date) < strtotime(date('Y-m-d H:i:s',strtotime('+10 days'))))
                                                <a href="#" class="select-plan btn-join" id="pay_now_2" data-plan_id=2>Renew</a>
                                            @else
                                                <a href="#" class="btn-join" disabled="disabled">Subscribed</a>
                                            @endif
                                        @elseif(!empty(Auth::user()->user_pricing_plan)&&Auth::user()->user_pricing_plan->pricing_plan_id>3)
                                        <a href="#" class="btn-join" disabled="disabled">Upgrade</a>
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
                                            <ul class="text-center text-black blue-line some-points">
                                                <li>Upto <b>{{ General::pricing_plan_data(3)->free_customer_limit }} Customers</b></li>
                                                <li><b>₹15</b> per additional customer</li>
                                                <li><b>₹70</b> Individual credit &amp; payment history report</li>
                                                <li><b>₹800</b> Business credit &amp; payment history report</li>
                                            </ul>
                                            <div class="heighlight-part bg-blue text-white position-relative text-center">
                                                <p><b>0%</b> Collection fee before due date (Tier-1)</p>
                                                <p><b>1%</b> Collection fee* on or after due date (Tier-2)</p>
                                                <p><b>0.5%</b> Collection fee* on tier-1 to tier-2 transfer</p>
                                            </div>
                                            <ul class="blue-line text-black some-padding some-points point-wid-sign">
                                                <li class="ic-sign ic-green-sign position-relative">Installment &amp; payment options to customers</li>
                                                <li class="ic-sign ic-green-sign position-relative">Monthly Standard reports</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div> 

                                <div class="csd-d-none">
                                    <ul class="text-center text-black blue-line some-points">
                                        <li>Upto <b>{{ General::pricing_plan_data(3)->free_customer_limit }} Customers</b></li>
                                        <li><b>₹15</b> per additional customer</li>
                                        <li><b>₹70</b> Individual credit &amp; payment history report</li>
                                        <li><b>₹800</b> Business credit &amp; payment history report</li>
                                    </ul>
                                    <div class="heighlight-part bg-blue text-white position-relative text-center">
                                        <p><b>0%</b> Collection fee before due date (Tier-1)</p>
                                        <p><b>1%</b> Collection fee* on or after due date (Tier-2)</p>
                                        <p><b>0.5%</b> Collection fee* on tier-1 to tier-2 transfer</p>
                                    </div>
                                    <ul class="blue-line text-black some-padding some-points point-wid-sign">
                                        <li class="ic-sign ic-green-sign position-relative">Installment &amp; payment options to customers</li>
                                        <li class="ic-sign ic-green-sign position-relative">Monthly Standard reports</li>
                                    </ul>
                                </div>                                
                            </div>
                        </div>
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
                                            <ul class="white-line text-white some-padding some-points csd-text-black csd-blue-line point-wid-sign">
                                                <li class="ic-sign ic-green-sign position-relative">Unlimited Customers</li>
                                                <li class="ic-sign ic-green-sign position-relative">Installment &amp; other payment options to customers</li>
                                                <li class="ic-sign ic-green-sign position-relative">Monthly standard reports</li>
                                                <li class="ic-sign ic-green-sign position-relative">Customized Monthly reports</li>
                                                <li class="ic-sign ic-green-sign position-relative">Dedicated support</li>
                                                <li class="ic-sign ic-green-sign position-relative">API integration</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div> 

                                <div class="csd-d-none">
                                    <ul class="white-line text-white some-padding some-points csd-text-black csd-blue-line point-wid-sign">
                                        <li class="ic-sign ic-green-sign position-relative">Unlimited Customers</li>
                                        <li class="ic-sign ic-green-sign position-relative">Installment &amp; other payment options to customers</li>
                                        <li class="ic-sign ic-green-sign position-relative">Monthly standard reports</li>
                                        <li class="ic-sign ic-green-sign position-relative">Customized Monthly reports</li>
                                        <li class="ic-sign ic-green-sign position-relative">Dedicated support</li>
                                        <li class="ic-sign ic-green-sign position-relative">API integration</li>
                                    </ul>
                                </div>
                                
                            </div>
                        </div>
                    </div>
                </div>

                
        </section>
        <div id="select_plan" class="popup-wrap">
          <div class="popup-overlay"></div>
           <div class="extra-wrap">
            <div class="extra-inner">
                <div class="popup-outer">
                <div class="popup-box">
                <header class="popup-header"> <a class="popup-close" href="javascript:void(0);">×</a>
                <h4 class="text-left">Payment for <span class="plan_name">PREMIUM</span> Membership Plan</h4>
                </header>
                <div class="popup-scroll">
                <div class="popup-body text-left">
                    
                    <div class="form-group hidden">No Payment Method Required</div>
                    <input type="hidden" name="plan_id_val" id="plan_id_val" value=1>
                    <div class="form-group hidden">
                        <label>
                        <input type="radio" name="payment_method" class="payment_method" value="paytm" checked>
                        Paytm    
                        </label>
                        
                    </div>
                    <div class="">
                        
                    </div>
                    
                    
                        <div class="clearfix">
                            <div class="form-group state_field">
                                <label for="name" class="col-md-4">State</label>
                                <div class="col-md-8">
                                    <select class="form-control select2" id="state_id" name="state_id"  >
                                        <option value="">Select</option>
                                        @foreach($states as $state)
                                            
                                            <option value="{{$state->id}}" {{Auth::user()->state_id==$state->id ? 'selected' : ''}}>{{$state->name}}</option>
                                        @endforeach
                                    </select>   
                                    <label id="state_id_error" class="error"></label>  
                                </div>
                                
                            </div>
                            <div class="form-group gstin_udise_field">
                                <label for="name" class="col-md-4">GSTIN/UDISE</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="gstin_udise" name="gstin_udise" placeholder="GSTIN/UDISE" 
                                            value="{{ Auth::user()->gstin_udise ?? '' }}" maxlength="15"> 
                                    <label id="gstin_udise_error" class="error"></label>  
                                </div>
                                
                            </div>
                            <div class="form-group email_field">
                                <label for="name" class="col-md-4">Email</label>
                                <div class="col-md-8">
                                    <input type="text" class="form-control" id="email" name="email" placeholder="Email" 
                                            value="{{ Auth::user()->email ?? '' }}"> 
                                    <label id="email_error" class="error"></label>  
                                </div>
                                
                            </div>

                            <table class="col-md-12 col-sm-12 col-xs-12 checkout_table">
                                <tr>
                                    <th><b>Details</b></th>
                                    <th><b class="amount">Amount</b></th>
                                </tr>
                                <tr>
                                    <td><span class="plan_name">PREMIUM</span> Membership Plan</td>
                                    <td><span class="plan_price">₹599</span></td>
                                </tr>
                                <tr class="central_gst">
                                    <td>IGST </td>
                                    <td><span class="plan_price_igst">₹53.91</span></td>
                                </tr>
                                <tr class="state_gst">
                                    <td>CGST </td>
                                    <td><span class="plan_price_cgst">₹53.91</span></td>
                                </tr>
                                <tr class="state_gst">
                                    <td>SGST </td>
                                    <td><span class="plan_price_sgst">₹53.91</span></td>
                                </tr>
                                <tr class="conv_fee_tr">
                                    <td>Convenience Fee</td>
                                    <td><span class="conv_fee">₹0</span></td>
                                </tr>
                                <tr>
                                    <td>Total</td>
                                    <td><span class="total_price">₹720.95</span></td>
                                </tr>
                            </table>
                        </div>
                </div>

                </div>
                <footer class="popup-footer">
                    <div class="pull-right">
                        <button type="button" name="submit_required" id="submit_required" class="btn-checkout btn btn-info">Submit</button>
                        <button type="button" name="checkout" id="checkout_button" class="btn-checkout btn btn-info">Pay Now</button>
                    </div>
                    <div class="clearfix"></div>
                </footer>
                </div>
                </div>
               </div>
              </div>
            </div>
        <div id="corporate_plan_comments" class="popup-wrap">
          <div class="popup-overlay"></div>
           <div class="extra-wrap">
            <div class="extra-inner">
                <div class="popup-outer">
                <div class="popup-box">
                    <form action="{{route('upgrade-corporate-plan')}}" method="GET">
                <header class="popup-header"> <a class="popup-close" href="javascript:void(0);">×</a>
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
.plan_price_igst,.plan_price_cgst,.plan_price_sgst,.plan_price,.conv_fee,.total_price,.amount{
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
.membership-plans  .plan-box.hide-plan{
    display: none !important;
}
.membership-plans  .justify-content-between {
    -ms-flex-pack: justify!important;
    justify-content: space-around !important;
}
</style>
@endsection
@section('javascript')
<script type="text/javascript">
    var premium_plan={name:'{{ General::pricing_plan_data(2)->name }}',price:'{{ General::pricing_plan_data(2)->membership_plan_price }}' ,tax:'{{ General::pricing_plan_data(2)->consent_recordent_report_gst }}',collection_fee:'{{ General::pricing_plan_data(2)->collection_fee }}'};
    var executive_plan={name:'{{ General::pricing_plan_data(3)->name }}',price:'{{ General::pricing_plan_data(3)->membership_plan_price }}',tax:'{{ General::pricing_plan_data(3)->consent_recordent_report_gst }}',collection_fee:'{{ General::pricing_plan_data(3)->collection_fee }}'};
    var plan={2:premium_plan,3:executive_plan};
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
$('body').on('click','.corporate-plan', function(e) {
    e.preventDefault();
    $('#corporate_plan_comments').fadeIn();
});
 $('body').on('click','.select-plan', function(e) {
   e.preventDefault();
   check_state();
   check_required_fields();
   var plan_id=$(this).data('plan_id');
   var tax=parseFloat(plan[plan_id]['tax']);
   var price=parseFloat(plan[plan_id]['price']);
   $('#plan_id_val').val(plan_id);
   $('.plan_name').html(plan[plan_id]['name']);
   $('.plan_price').html(plan[plan_id]['price']);
   var cgst=price*tax/200;
   var sgst=parseFloat(cgst);
   cgst=sgst;
   var igst=cgst+sgst;

   // sgst=cgst;
   var conv_fee;
   conv_fee= 0.0;//parseFloat(plan[plan_id]['collection_fee']);
   var total_price;
   if(conv_fee<=0)
   {
    $('.conv_fee_tr').hide();
   }
   total_price=price+sgst+cgst+conv_fee;
   $('.plan_name').html(plan[plan_id]['name']);
   $('.plan_price').html(plan[plan_id]['price']);
   $('.plan_price_cgst').html(cgst);
   $('.plan_price_sgst').html(sgst);
   $('.plan_price_igst').html(igst);      
   $('.conv_fee').html(conv_fee);  
   $('.total_price').html(total_price.toFixed(2));
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
         console.log(data);
        // var response=JSON.parse(data);
        // console.log(response);
        if(data.status=='success')
            @if(Request::is('upgrade-plan'))
                window.location.href="{{route('upgrade-pricing-plan')}}?pricing_plan_id="+$('#plan_id_val').val()+'&&upgrade=1';
            @else
                window.location.href="{{route('renew-pricing-plan')}}?pricing_plan_id="+$('#plan_id_val').val()+'&&renew=1';
            @endif
           
        else{            
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