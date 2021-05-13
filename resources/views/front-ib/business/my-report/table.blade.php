<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" dir="{{ __('voyager::generic.is_rtl') == 'true' ? 'rtl' : 'ltr' }}">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&family=Rubik:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <!-- <link rel="stylesheet" href="http://localhost:8080/recordent/public/admin/voyager-assets?path=css%2Fapp.css">-->
    <link rel="stylesheet" href="{{asset('css/voyager-assets.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('front-ib/css/pdf.css')}}">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css">
    
</head>
<body class="voyager @if(isset($dataType) && isset($dataType->slug)){{ $dataType->slug }}@endif">
<div class="report-gen">
    @php
    $logo = file_get_contents(asset('storage/'.setting('admin.icon_image')));
    $base64 = 'data:image/png;base64,' . base64_encode($logo);
    @endphp
    @forelse($records as $data)
    
    <table  width="100%" class="table table-hover full-boder">
        <tr>

            <td colspan="8" class="w-100 boder-t-none logo-left"><img src="{{$base64}}}" alt="" width="180"></td>
        </tr>   
        <tr>
            <td colspan="8" align="center" class="main-title">Recordent Business Report</td>
        </tr>
        <tr class="">
            <td colspan="6" width="72%" style="width:72% !important"></td>
            <td class="f-w-6" >Report Number</td>
            <td class="" >
                {{$reportNumber}}
            </td>
        </tr>
         <tr class="">
            <td colspan="6" width="72%" style="width:72% !important"></td>
            <td class="f-w-6" >              
                Date &amp; Time Stamp
            </td>
            <td class="" >    
                {{$dateTime}}
            </td>   
        </tr>

         <tr class="bg-color">
            <td colspan="8" class="center-align w-100 sub-title" align="center">Business Details</td>
        </tr>
        <tr>
            <td class="f-w-6">Company Name</td>
            <td >{{$data->company_name}}</td>
            <td colspan="4" width="48%" style="width:48% !important"></td>
            <td class="f-w-6">GSTIN / UDISE Number</td>
             <td >
                @if(!empty($data->unique_identification_number))
                    {{$data->unique_identification_number}}
                @else
                    Not Reported
                @endif
            </td>    
        </tr>
        
        <tr class="bg-color">
            <td colspan="8" class="center-align w-100 sub-title"  align="center">Summary</td>
        </tr>
        <tr class="summary-de">
            <td colspan="8" class="p-0 b-0">
                <table width="100%">
                   <tr>
                        <td class="f-w-6">Total Members Submitted</td>
                        <td align="center">{{$data->summary_totalMemberReported}}</td>
                        <td class="f-w-6">Total Dues Submitted</td>
                        <td align="center">Rs {{General::ind_money_format($data->summary_totalDueReported)}}</td>
                        <td class="f-w-6">Total Dispute</td>
                        <td align="center">{{$data->totalDispute}}</td>
                   </tr>     
                </table>   
            </td>
        </tr>
        <tr><td colspan="8"></td></tr>

        <tr class="overdue-status">
            <td class="f-w-6">Overdue status</td>
            <td align="center">0-29 days</td>
            <td align="center">30-59 days</td>
            <td align="center">60-89 days</td>
            <td align="center">90-119 days</td>
            <td align="center">120-149 days</td>
            <td align="center">150-179 days</td>
            <td class="center-align" align="center">180+ days</td>
        </tr>
        <tr class="overdue-status">
                    <td class="f-w-6">Total Accounts</td>
                    <td align="center">{{$data->summary_overDueStatus0To29Days}}</td>
                    <td align="center">{{$data->summary_overDueStatus30To59Days}}</td>
                    <td align="center">{{$data->summary_overDueStatus60To89Days}}</td>
                    <td align="center">{{$data->summary_overDueStatus90To119Days}}</td>
                    <td align="center">{{$data->summary_overDueStatus120To149Days}}</td>
                    <td align="center">{{$data->summary_overDueStatus150To179Days}}</td>
                    <td class="center-align" align="center">{{$data->summary_overDueStatus180PlusDays}}</td>
                </tr>
         <tr class="bg-color">
                <td colspan="8" align="center" class="w-100 sub-title">Account Details</td>
          </tr>
        @if($data->accountDetails->count())
            @foreach($data->accountDetails as $accountDetail)
            @php
                $disputeDetail = $accountDetail->dispute->last();
                $disputeStatus = 'No';
                $disputeComment = 'N/A';
                if($disputeDetail){
                    $disputeComment = $disputeDetail->comment ? $disputeDetail->comment : 'N/A';
                    $disputeStatus = $disputeDetail->is_open == 1 ? 'Open' : 'Closed';
                }
            @endphp
            <tr class="account-de">
                <td colspan="8" class="p-0 b-0">
                    <table width="100%">
                        <tr class="account-de">
                            <th class="f-w-6">Member Name</th>
                            <th class="f-w-6">Amount Due</th>
                            <th class="f-w-6">Due Date</th>
                            <th class="f-w-6">Date Submitted</th>
                            <th class="f-w-6">Proof Submitted</th>
                            <th class="f-w-6">Status</th>
                            <th class="f-w-6">Dispute status</th>
                        </tr>
                        <tr class="account-de">
                             @php
                                $amountDue = $accountDetail->due_amount - General::getPaidForDueOfBusiness($accountDetail->id);
                            @endphp
                            <td class="f-w-6">
                                {{$accountDetail->addedBy->business_name}}  
                            </td>
                            <td class="f-w-6">Rs {{General::ind_money_format($amountDue)}}</td>
                            <td class="f-w-6">{{date('d-m-Y',strtotime($accountDetail->due_date))}}</td>
                            <td class="f-w-6">{{date('d-m-Y',strtotime($accountDetail->created_at))}}</td>
                            <td class="f-w-6">{{$accountDetail->proof_of_due ? 'Yes' : 'No'}}</td>
                            @php
                                $now = \Carbon\Carbon::now();

                                $diffDays = General::diffInDays($accountDetail->due_date);
                            @endphp
                            <td class="f-w-6"> 
                                @if($diffDays>=180 )
                                    180+ days overdue              
                                @else
                                    {{$diffDays}} days overdue
                                @endif   
                             </td>
                            <td>{{$disputeStatus}}</td>
                        </tr>
                        <tr class="">
                            <td colspan="2" class="f-w-6">
                                Dispute Comments
                            </td>
                            <td colspan="5" class="f-w-6">
                                {{$disputeComment}}
                            </td>
                        </tr>
                        
    					<tr><td colspan="8"></td></tr>
                    </table>
                </td>
            </tr>
            @endforeach
        @endif      
        <tr>
            <td align="center" class="main-title w-100" colspan="8">End of Report</td>
        </tr>   
    </table>
    @empty
    <div><center>No report found</center></div>
    @endforelse
    </div>
</body>
</html>
