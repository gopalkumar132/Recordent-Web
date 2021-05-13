<!DOCTYPE html>
<html lang="{{ config('app.locale') }}" dir="{{ __('voyager::generic.is_rtl') == 'true' ? 'rtl' : 'ltr' }}">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&family=Rubik:wght@300;400;500;700;900&display=swap" rel="stylesheet">
    <!-- <link rel="stylesheet" href="http://localhost:8080/recordent/public/admin/voyager-assets?path=css%2Fapp.css">
 -->    <link rel="stylesheet" type="text/css" href="http://localhost:8080/recordent/public/css/custom.css">
     <link rel="stylesheet" type="text/css" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"> 
    <link rel="stylesheet" href="http://localhost:8080/recordent/public/css/voyager-assets.css">
</head>
<body class="voyager @if(isset($dataType) && isset($dataType->slug)){{ $dataType->slug }}@endif">
<style>
    /* font-family:'Open Sans', sans-serif */
    body{color:#000}
    p{margin:0;}
    .voyager .report-gen .table > tbody > tr:nth-child(2n) > td{background-color:#fff;}
    .voyager .report-gen .table > tbody > tr:hover{background-color:#fff;}
    .voyager .report-gen .main-title,
    .voyager .report-gen .table > tbody > tr > .main-title{font-family:'Rubik', sans-serif; color:#273581 !important; font-size:20px; font-weight:500;}
    .voyager .report-gen .table > tbody > tr > td.center-align{text-align:center !important;}
    .voyager .report-gen .table > tbody > tr > td.logo-left{text-align:left !important;}
    .w-100{width:100%;}
    .w-70{width:70%;}
    .w-60{width:60%;}
    .w-40{width:40%;}
    .w-30{width:30%;}
    .w-50{width:50%;}
    td,tr,p{color:#000; font-size:15px; font-weight:500;}
    .justify-content-between{justify-content:space-between;}
    .justify-content-center{justify-content:center;}
    .persona-det p{width:290px; color:#000; font-size:15px; font-weight:500;}
    .persona-det p ~ p{margin-left:10px;}
    .report-nu-date p{width:290px; color:#000; font-size:15px; font-weight:500;}
    .report-nu-date p ~ p{margin-left:10px;}
    .margin-h + .margin-h{margin-top:5px;}
    .report-gen .bg-color,
    .report-gen .bg-color:hover{background-color:#1e1f24 !important}
    .voyager .report-gen .sub-title,
    .voyager .report-gen .table > tbody > tr > .sub-title{font-family:'Rubik', sans-serif; color:#fff !important; font-size:16px; font-weight:500;}
    .bor-right{border-right:1px solid #c8c8c8;}
    .summary-de td{width:calc(100% / 6);}
    .summary-de td + td,
    .overdue-status td + td,
    .account-de td + td,
    .account-de th + th{border-left:1px solid #c8c8c8;}
    .full-boder{border:none; margin:0;}
    .boder-t-none{border-top:none !important;}
    .boder-t1{border-top:1px solid #c8c8c8 !important;}
    .b-td{height:30px; display:block;}
    .bor-left{border-left:1px solid #c8c8c8;}
    .overdue-status td{width:calc(100% / 8);}
    .account-de th{font-weight:600;}
    .account-de td,
    .account-de th{width:calc(100% / 7);}
    .f-w-6{font-weight:600 !important;}
    .w-tw-c{width:calc((100% / 7) * 2);}
    .w-fi-c{width:calc((100% / 7) * 5);}
    .dis-commen p ~ p{margin-left:10px;}
    .btn-to-action{background-color:#273581; border:1px solid #273581; color:#fff; border-radius:8px; padding:4px 35px; font-weight:700; display:inline-block; text-align:center; text-decoration:none !important;}
    .btn-to-action:hover,
    .btn-to-action:focus{background-color:#fff; color:#273581;}
    .two-btn-sath .btn-to-action + .btn-to-action{margin-left:25px;}
    .report-gen .table > tbody > tr > td,
    .report-gen .table > tbody > tr > th,
    .report-gen .table > tfoot > tr > td,
    .report-gen .table > tfoot > tr > th,
    .report-gen .table > thead > tr > td,
    .report-gen .table > thead > tr > th{border-top:1px solid #c8c8c8}
</style>
<div class="app-container">
    <div class="row content-container">
        <!-- Main Content -->
        <div class="container-fluid">
            <div class="side-body padding-top">
                <div id="voyager-notifications"></div>
                <div class="page-content container-fluid">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="panel panel-bordered">
                                <div class="panel-body">
                                    @forelse($records as $data)
                                    <div class="report-gen">
                                        <div class="table-responsive" id="my_table_{{$data->id}}">
                                            <table class="table full-boder">
                                                <tr>
                                                    @php
                                                    $logo = file_get_contents(asset('storage/'.setting('admin.icon_image')));
                                                    $base64 = 'data:image/png;base64,' . base64_encode($logo);
                                                    @endphp
                                                    <td class="w-100 boder-t-none logo-left">
                                                        <img src="{{$base64}}}" width="180" />
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td align="center" class="main-title w-100 center-align">Recordent Individual Report</td>
                                                </tr>
                                                <tr class=" justify-content-between boder-t1">
                                                    <td class="w-60 boder-t-none"></td>
                                                    <td class="w-40 boder-t-none">
                                                        <div class=" report-nu-date margin-h">
                                                            <p class="f-w-6">Report Number</p>
                                                            @php
                                                                $reportNumber = '';
                                                                $reportNumber = \Carbon\Carbon::now()->format('dmY');
                                                                $reportNumber.='MI';
                                                                $reportNumber.=rand(1000000,9999999);
                                                            @endphp
                                                            <p>{{$reportNumber}}</p>
                                                        </div>
                                                        <div class=" report-nu-date margin-h">
                                                            <p class="f-w-6">Date &amp; Time Stamp</p>
                                                            <p>{{$dateTime}}</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="bg-color">
                                                    <td class="w-100 sub-title bg-color center-align" align="center">Personal Details</td>
                                                </tr>
                                                <tr class=" justify-content-between">
                                                    <td class="w-60  boder-t-none">
                                                        <div class=" persona-det margin-h">
                                                            <p class="f-w-6">Consumer Name</p>
                                                            <p>{{$data->person_name}}</p>
                                                        </div>
                                                        <div class=" persona-det margin-h">
                                                            <p class="f-w-6">DOB</p>
                                                            <p>
                                                                @if(!empty($data->dob))
                                                                    {{date('d-m-Y',strtotime($data->dob))}}
                                                                @else
                                                                    Not Reported
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </td>
                                                    <td class="w-40 justify-content-between bor-left boder-t-none">
                                                        <div class=" report-nu-date margin-h">
                                                            <p class="f-w-6">Aadhar Number (last 6 digits)</p>
                                                            <p>
                                                                @if(!empty($data->aadhar_number))
                                                                    {{$data->aadhar_number}}
                                                                @else
                                                                    Not Reported
                                                                @endif
                                                            </p>
                                                                
                                                        </div>
                                                        <div class=" report-nu-date margin-h">
                                                            <p class="f-w-6">Mobile Number</p>
                                                            <p>{{$data->contact_phone}}</p>
                                                        </div>
                                                    </td>
                                                </tr>
                                                <tr class="bg-color">
                                                    <td class="w-100 sub-title bg-color center-align" align="center">Summary</td>
                                                </tr>
                                                <tr class=" summary-de">
                                                    <td class="f-w-6">Total Members Submitted</td>
                                                    <td align="center">{{$data->summary_totalMemberReported}}</td>
                                                    <td class="f-w-6">Total Dues Submitted</td>
                                                    <td align="center"><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{number_format($data->summary_totalDueReported)}}</td>
                                                    <td class="f-w-6">Total Dispute</td>
                                                    <td class="center-align" align="center">0</td>
                                                </tr>
                                                <tr>
                                                    <td class="w-100 b-td"></td>
                                                </tr>
                                                <tr class=" overdue-status">
                                                    <td class="f-w-6">Overdue status</td>
                                                    <td align="center">0-29 days</td>
                                                    <td align="center">30-59 days</td>
                                                    <td align="center">60-89 days</td>
                                                    <td align="center">90-119 days</td>
                                                    <td align="center">120-149 days</td>
                                                    <td align="center">150-179 days</td>
                                                    <td class="center-align" align="center">180+ days</td>
                                                </tr>
                                                <tr class=" overdue-status">
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
                                                    <td class="w-100 sub-title bg-color center-align" align="center">Account Details</td>
                                                </tr>
                                                @if($data->accountDetails->count())
                                                    @foreach($data->accountDetails as $accountDetail)
                                                    
                                                        <tr class="account-de ">
                                                            <th>Member Name</th>
                                                            <th>Amount Due</th>
                                                            <th>Due Date</th>
                                                            <th>Date Submitted</th>
                                                            <th>Proof Submitted</th>
                                                            <th>Status</th>
                                                            <th>Dispute status</th>
                                                        </tr>
                                                        <tr class="account-de ">
                                                            <td>
                                                                @if(Auth::id() == $accountDetail->addedBy->id)
                                                                {{$accountDetail->addedBy->business_name}}
                                                                @else
                                                                XXXXX
                                                                @endif
                                                            </td>
                                                            <td><span style="font-family: DejaVu Sans; sans-serif;">&#8377;</span> {{number_format($accountDetail->due_amount)}}</td>
                                                            <td>{{date('d-m-Y',strtotime($accountDetail->due_date))}}</td>
                                                            <td>{{date('d-m-Y',strtotime($accountDetail->created_at))}}</td>
                                                            <td>{{$accountDetail->proof_of_due ? 'Yes' : 'No'}}</td>
                                                            @php
                                                                $now = \Carbon\Carbon::now();

                                                                $diffDays = General::diffInDays($accountDetail->due_date);
                                                            @endphp
                                                            <td> 
                                                                @if($diffDays>=180 )
                                                                    180+ days overdue              
                                                                @else
                                                                    {{$diffDays}} days overdue
                                                                @endif   
                                                             </td>
                                                            <td>No</td>
                                                        </tr>
                                                        <tr class="">
                                                            <td class="w-tw-c  dis-commen">
                                                                <p class="f-w-6">Dispute Comments</p>
                                                                
                                                            </td>
                                                            <td class="w-fi-c bor-left dis-commen ">
                                                                <p class="f-w-6">N/A</p>
                                                                
                                                            </td>
                                                        </tr>
                                                        {{--
                                                        <tr class=" justify-content-center boder-t1">
                                                            <td class="boder-t-none two-btn-sath">
                                                                <a href="javascript:void(0)" class="btn-to-action">Make Payment</a>
                                                                <a href="javascript:void(0)" class="btn-to-action">Raise Dispute</a>
                                                            </td>
                                                        </tr>--}}
                                                        <tr>
                                                            <td class="w-100 b-td"></td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                                
                                                <tr>
                                                    <td align="center" class="main-title w-100 center-align">End of Report</td>
                                                </tr>
                                            </table>
                                        </div>
                                        
                                    </div>
                                    @empty
                                    <div><center>No report found</center></div>
                                    @endforelse

                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</body>
</html>
