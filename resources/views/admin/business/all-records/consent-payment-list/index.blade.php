<table id="dataTable" class="table table-hover fixed_headerss">
    <thead>
        <tr>
            
            <th>{{General::getLabelName('unique_identification_number')}}</th>
            <th>Concerned Person Phone</th>
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
                
                <td>{{$consentList->unique_identification_number ? $consentList->unique_identification_number : '-'}}</td>
                <td>{{$consentList->concerned_person_phone}}</td>
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
                            Failed
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
                            $amount = $consentList->report == 3 ? HomeHelper::getConsentComprehensiveReportPrice() : HomeHelper::getConsentRecordentReportPrice();
                        }
                        else{
                            $amount = setting('admin.consent_payment_value') ? (int)setting('admin.consent_payment_value') : 100;
                        }
                    @endphp
                    @if($consentList->status!=4 && $consentList->status!=3)
                        @if($dateTimeForCheckStatus >= \Carbon\Carbon::now())
                            <a class="btn btn-primary consentCheckStatus" href="{{route('admin.business.check-consent-status',[$consentList->id])}}" onclick="consentCheckStatus(this,event)">Check Status</a>
                        @endif
                    @elseif($consentList->status==3) 
                        @if(empty($consentLastPayment))
                            @if(Auth::user()->reports_individual==1 || $amount==0)
                             <a href="{{route('admin.business.report',['c_id'=>$consentList->id])}}"class="btn btn-primary ">View Report</a>
                            @else
                            <a href="{{route('admin.business.consent.payment',[$consentList->id])}}" class="btn btn-primary ">Make Payment</a>
                            @endif
                        @else
                            @if($consentLastPayment->status==4)
                             <a href="{{route('admin.business.report',['cp_id'=>$consentLastPayment->id])}}"class="btn btn-primary ">View Report</a>
                            @elseif($consentLastPayment->status==3 || $consentLastPayment->status==5 )
                                <a href="{{route('admin.business.consent.payment',[$consentList->id])}}" class="btn btn-primary ">Make Payment</a>
                            @elseif($consentLastPayment->status==1)
                                 @php
                                    //if initiated and 15 minutes happned then make payment.
                                     $dateTimeForRepayment = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $consentLastPayment->created_at);
                                     $dateTimeForRepayment->addMinute(15);
                                 @endphp   
                                 @if($dateTimeForRepayment < \Carbon\Carbon::now())
                                    <a href="{{route('admin.business.consent.payment',[$consentList->id])}}" class="btn btn-primary ">Make Payment</a>
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