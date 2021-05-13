@php 
    $removeAllRecordListSection = false;
    if(!empty($lastConsent) && $lastConsent->id==$consentList->id){
        $removeAllRecordListSection = true;
    }
@endphp
<td>{{$consentList->unique_identification_number ? $consentList->unique_identification_number : '-'}}</td>
<td>{{$consentList->concerned_person_phone}}</td><td>{{date('d/m/Y H:i', strtotime($consentList->created_at))}}</td>
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
            @php $removeAllRecordListSection = false; @endphp 
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
    {{date('d/m/Y H:i', strtotime($consentLastPayment->created_at))}}</td>
    @else
    -
    @endif
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
    @if($consentList->status!=4 && $consentList->status!=3)
        @if($dateTimeForCheckStatus >= \Carbon\Carbon::now())
            <a class="btn btn-primary consentCheckStatus" href="{{route('admin.business.check-consent-status',[$consentList->id])}}" onclick="consentCheckStatus(this,event)">Check Status</a>
        @endif
    @elseif($consentList->status==3) 
        @if(empty($consentLastPayment))
            <a href="{{route('admin.business.consent.payment',[$consentList->id])}}" class="btn btn-primary ">Make Payment</a>
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
@if($removeAllRecordListSection)
    <script>
    $('.all-record-listing-section').addClass('hide');
    </script>
@endif