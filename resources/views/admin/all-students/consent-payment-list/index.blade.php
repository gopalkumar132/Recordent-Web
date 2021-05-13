@inject('provider', 'App\Http\Controllers\AllStudentController')
<table id="dataTable" class="table table-hover fixed_headerss all consent">
    <thead>
        <tr>
            <th>Full Name</th>
            <th>Business Name</th>
            <th>Gstin/Business PAN</th>
            <th>Contact Phone</th>
            <th>Customer Type</th>
            <th>Consent Raised Date</th>
            <th>Consent Status</th>
            <th>Consent Action Date</th>
            <th>Payment Date</th>
            <th>Payment Status</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($consentListing as $consentList)
            <tr id="checkConsentStatus{{$consentList->id}}">
                <td>{{$consentList->person_name ? $consentList->person_name : $provider::getName($consentList->contact_phone)}}</td>
                <td>{{$consentList->business_name ? $consentList->business_name : $provider::getName($consentList->business_name)}}</td>
                <td>{{$consentList->unique_identification_number ? strtoupper($consentList->unique_identification_number) : $provider::getName($consentList->unique_identification_number)}}</td>
                @if($consentList->customer_type=="INDIVIDUAL")
                  <td>{{$consentList->contact_phone}}</td>
                @else
                  <td>{{$consentList->concerned_person_phone}}</td>
                @endif
                <td>{{$consentList->customer_type}}</td>
                <td>{{date('d/m/Y H:i', strtotime($consentList->created_at))}}</td>
                <td>
                    @if($consentList->status==4)
                         Denied
                    @elseif($consentList->status==3)
                         Approved
                    @else
                        @php
                            $request_consent_block_for_hour = setting('admin.request_consent_block_for_hour') ? (int)setting('admin.request_consent_block_for_hour') : 0 ;
                            
                            $dateTimeForCheckStatus = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $consentList->created_at);
                            $dateTimeForCheckStatus->addHour($request_consent_block_for_hour);
                        @endphp

                        @if($dateTimeForCheckStatus >= \Carbon\Carbon::now())
                            Pending
                        @else
                             Expired 
                        @endif    
                    @endif
                </td>
                <td>
                    @if(!empty($consentList->response_at))
                        {{date('d/m/Y H:i', strtotime($consentList->response_at))}}
                    @else
                        -
                    @endif
                </td>

                @php $consentLastPayment = $consentList->payment->last(); @endphp
                <td>
                    @if(!empty($consentLastPayment))
                    {{date('d/m/Y H:i', strtotime($consentLastPayment->created_at))}}
                    @else
                    -
                    @endif
                </td>    
                <td>
                    @if(!empty($consentLastPayment))
                        @if($consentLastPayment->status==5)
                            @if(isset($consentList->consent_api_response->status) && $consentList->consent_api_response->status == 3)
                                Not invoiced
                            @else
                                Failed
                            @endif
                            
                        @elseif($consentLastPayment->status==4)
                            Success
                        @elseif($consentLastPayment->status==3)
                            Aborted
                        @elseif($consentLastPayment->status==2)
                            Open
                        @elseif($consentLastPayment->status==1)
                            @php
                                //if initiated and 15 minutes happned then make forcefully failed.
                                 $dateTimeForRepayment = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $consentLastPayment->created_at);
                                 $dateTimeForRepayment->addMinute(15);
                            @endphp
                            @if($dateTimeForRepayment < \Carbon\Carbon::now())
                                @php General::makeConsentPaymentFailForcefully($consentLastPayment->id); @endphp
                                Failed
                            @else
                                In Progress
                            @endif
                        @else
                            -    
                        @endif
                    @else
                    -
                    @endif
                </td>
                <td>
                    @php
                        if(Auth::user()->user_pricing_plan != NULL){
                            if($consentList->report == 2){
                                $amount = $consentList->report == 2 ? HomeHelper::getConsentComprehensiveReportPrice() : HomeHelper::getConsentRecordentReportPrice();
                            }

                            if($consentList->report == 3){
                               $amount = $consentList->report == 3 ? HomeHelper::getConsentComprehensiveReportPrice() : HomeHelper::getConsentRecordentReportPrice();
                            }

                            if($consentList->report == 1){
                              $amount = HomeHelper::getConsentRecordentReportPrice();
                            }
                        } else {
                            $amount = setting('admin.consent_payment_value') ? (int)setting('admin.consent_payment_value') : 100;
                        }
                    @endphp
                    @if($consentList->status!=4 && $consentList->status!=3)
                        @if($dateTimeForCheckStatus >= \Carbon\Carbon::now())
                            <a class="btn btn-primary consentCheckStatus" href="{{route('admin.check-consent-status',[$consentList->id])}}" onclick="consentCheckStatus(this,event)">Check Status</a>
                        @endif
                    @elseif($consentList->status == 3)

                        @if(!empty($consentLastPayment))
                            @php
                                //if initiated and 15 minutes happned then make payment.
                                $dateTimeForRepayment = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $consentLastPayment->created_at);
                                $dateTimeForRepayment->addMinute(15);
                            @endphp
                        @endif

                        @if($consentList->customer_type == "INDIVIDUAL")
                            @if(Auth::user()->reports_individual == 1 || $amount == 0 || !empty($consentLastPayment) && $consentLastPayment->status == 4 || !empty($consentList->consent_api_response->status) && $consentList->consent_api_response->status == 3)

                                @if(!empty($consentLastPayment) && $consentLastPayment->status == 4 && isset($consentList->consent_api_response->status) && $consentList->consent_api_response->status == 3)
                                    <a href="#" onclick="check_refund_status('{{$consentLastPayment->id}}', '{{$consentLastPayment->refund_status}}'); return false;" class="btn btn-primary ">Refund Status</a>
                                @else
                                    @if(!empty($consentList->consent_api_response->status) && $consentList->consent_api_response->status == 3 && !empty($consentLastPayment) && $consentLastPayment->status == 5 )
                                        <a href="javascript:void(0); return false;" class="btn btn-primary" disabled>No Report</a>
                                    @else

                                        @if($consentList->report == 3 && !empty($consentLastPayment->id) && $consentLastPayment->status == 4)
                                             <a href="{{route('admin.individual.report', ['cp_id' => $consentLastPayment->id])}}" class="btn btn-primary ">View Report</a>
                                        @else
                                            <a href="{{route('admin.individual.report',['c_id'=>$consentList->id])}}"class="btn btn-primary ">View Report</a>
                                        @endif
                                        
                                    @endif
                                @endif
                            @else
                                @if(empty($consentLastPayment) || $consentLastPayment->status == 3 || $consentLastPayment->status == 5 )
                                    <a href="{{route('admin.consent.payment',[$consentList->id])}}" class="btn btn-primary ">Make Payment</a>
                                @else
                                    @if(!empty($consentLastPayment) && $consentLastPayment->status == 1)
                                           
                                        @if($dateTimeForRepayment < \Carbon\Carbon::now())
                                            <a href="{{route('admin.consent.payment',[$consentList->id])}}" class="btn btn-primary ">Make Payment</a>
                                        @endif
                                    @endif
                                @endif
                            @endif
                        @endif


                        @if($consentList->customer_type == "BUSINESS")
                            @if(Auth::user()->reports_business==1 || $amount==0 || !empty($consentLastPayment) && $consentLastPayment->status == 4 || !empty($consentList->consent_api_response->status) && $consentList->consent_api_response->status == 3)

                                @if(!empty($consentLastPayment) && $consentLastPayment->status == 4 &&  !empty($consentList->consent_api_response->status) && $consentList->consent_api_response->status == 3)
                                    <a href="#" onclick="check_refund_status('{{$consentLastPayment->id}}', '{{$consentLastPayment->refund_status}}'); return false;" class="btn btn-primary ">Refund Status</a>
                                @else
                                    @if(!empty($consentList->consent_api_response->status) && $consentList->consent_api_response->status == 3 && !empty($consentLastPayment) && $consentLastPayment->status == 5 )
                                        <a href="javascript:void(0); return false;" class="btn btn-primary" disabled>No Report</a>
                                    @else
                                        @if($consentList->report == 3 && !empty($consentLastPayment->id) && $consentLastPayment->status == 4)
                                            <a href="{{route('admin.business.report', ['cp_id' => $consentLastPayment->id])}}"class="btn btn-primary ">View Report</a>
                                        @else
                                            <a href="{{route('admin.business.report',['c_id'=>$consentList->id])}}"class="btn btn-primary ">View Report</a> 
                                        @endif
                                    @endif
                                @endif
                                
                            @else
                                @if(empty($consentLastPayment) || $consentLastPayment->status==3 || $consentLastPayment->status==5 )
                                    <a href="{{route('admin.business.consent.payment',[$consentList->id])}}" class="btn btn-primary ">Make Payment</a>
                                @else
                                    @if(!empty($consentLastPayment) && $consentLastPayment->status == 1)  
                                        @if($dateTimeForRepayment < \Carbon\Carbon::now())
                                            <a href="{{route('admin.business.consent.payment',[$consentList->id])}}" class="btn btn-primary ">Make Payment</a>
                                        @endif
                                    @endif
                                @endif
                            @endif
                        @endif
                    @endif    
                </td>
            </tr>
        @empty
           <tr><td colspan="10" align="center">No Record Found</td></tr>
        @endforelse
    </tbody>
</table>

    <div class="modal commap-team-popup" id="myModal" tabindex="-1" role="dialog">
      <div class="modal-dialog modal-sm">
        <div class="modal-content">
          <div class="modal-header">
              <h3 class="modal-title">Error</h3>
          </div>
          <div class="modal-body">
              <?php echo Session::get('msg'); ?>
          </div>
          <div class="modal-footer">
             <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
             </div>
          </div>
        </div>
      </div>
    </div>

    

    <div id="freelimitforreport" class="popup-wrap">
    <div class="popup-overlay"></div>
    <div class="extra-wrap">
        <div class="extra-inner">
            <div class="popup-outer">
                <div class="popup-box">
                    <header class="popup-header">
                        <h4 style="text-align: center;margin-top: 10px;">Payment for Credit Report</h4>
                    </header>
                    <div class="popup-scroll">
                        <div class="popup-body text-left">
                            
                            <div class="clearfix">
                                
                                <table class="col-md-12 col-sm-12 col-xs-12 checkout_table">
                                    <tr>
                                        <th><b style="font-weight: 900;    font-size: 15px;">Details</b></th>
                                        <th><b class="amount" style="font-weight: 900;    font-size: 15px;">Amount</b></th>
                                    </tr>
                                    <tr>
                                        <td><span class="plan_name">Price</span></td>
                                        <td><span class="plan_price">₹ <?php echo Session::get('consent_payment_value');?></span></td>
                                    </tr>
                                   <?php if(Auth::user()->state_id!=36){?>
                                   <tr class="central_gst">
                                        <td>IGST </td>
                                        <td><span class="plan_price_igst">₹ <?php echo Session::get('consent_payment_value')*0.18;?></span></td>
                                    </tr>
                                    <?php } else {?>
                                    <tr class="state_gst">
                                        <td>CGST </td>
                                        <td><span class="plan_price_cgst">₹ <?php echo Session::get('consent_payment_value')*0.09;?></span></td>
                                    </tr>
                                    <tr class="state_gst">
                                        <td>SGST </td>
                                        <td><span class="plan_price_sgst">₹ <?php echo Session::get('consent_payment_value')*0.09;?></span></td>
                                    </tr>
                                  <?php }?>
                                  <tr class="conv_fee_tr">
                                        <td>Discount</td>
                                        <td><span class="conv_fee">₹ -<?php echo Session::get('consent_payment_value_final');?></span></td>
                                    </tr>
                                    <tr>
                                        <td>Total</td>
                                        <td><span class="total_price">₹ 0</span></td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                    <footer class="popup-footer">
                        <div class="pull-right">
                           
                            <button type="button" class="btn btn-primary view_free_report" >Continue</button>
                        </div>
                        <div class="clearfix"></div>
                    </footer>
                </div>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .modal-dialog {
       width: 357px;
       margin: 168px auto;
       right: -30px;
    }
    .modal-body {
       padding: 20px;
       text-align: center;
    }
    .modal-dd{
      text-align: left !important;
    }
    .modal-footer {
       padding: 0px;
       border-top: none;
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
        top: 77px;
        width: 100%;
        z-index: 9;
    }
    .popup-outer {
        box-sizing: border-box;
        margin: 65px auto;
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
        padding: 5px 20px;
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
    .modal-backdrop{
      position: initial;
    }
    .plan_price_igst, .plan_price_cgst, .plan_price_sgst, .plan_price, .conv_fee, .total_price, .amount, .amount_adjusted_value, .plan_subtotal_price{
          float: right;
    clear: both;
    }
@media only screen and (max-width: 600px) {
 .modal-dialog {
    width: 300px;
        margin: 218px auto;
    left: 1px;   
}
</style>    

@if(!empty(Session::get('msg')))
  <script>
    $(function() {
      $('#myModal').modal('show');
    });
</script>
@endif

@if(!empty(Session::get('consent_payment_value')))
  <script>
    $(function() {
      $('#freelimitforreport').modal('show');
    });
</script>
@endif

<script>
$(document).ready(function(){

    consentCheckStatus = function(element,e){
        $this = $(element);
        if($this.attr('disabled')){
            e.preventDefault();
            return false;
        }
        e.preventDefault();
        var url =$this.attr('href');
        $this.attr('disabled','disabled');
        $.ajax({
           method: 'get',
           url: url,
           headers: {
               'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
           }               
        }).then(function (response) {
            var alertType = "info";
            var alertMessage = response.message;
            var alerter = toastr[alertType];
            alerter(alertMessage);
            $this.removeAttr('disabled');

            $this.parents('tr').fadeOut(1000,function(){
               $(this).html(response.newStatus);
               $(this).fadeIn(500);
            });

        }).fail(function (data) {
            var alertType = "error";
            var alertMessage = data.responseJSON.message;
            var alerter = toastr[alertType];
            alerter(alertMessage);
            $this.removeAttr('disabled');    

        });
    }
});        
</script>  
<script type="text/javascript">
  $(".view_free_report").on('click',function(){
  window.location.reload();
});
</script>      