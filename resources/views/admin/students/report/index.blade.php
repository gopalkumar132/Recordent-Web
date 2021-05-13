@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' Report')

@section('page_header')
<h1 class="page-title" style="display: none;">
    <i class="voyager-list"></i> Individual report

</h1>
@stop
@section('content')
<link rel="stylesheet" type="text/css" href="{{asset('css/report.css')}}">

<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

<!-- <div class="page-content container-fluid mob-web-off">

    @if(Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
    @endif

    @include('voyager::alerts')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-bordered">
                <div class="panel-body">
                    @forelse($records as $data)
                    <div class="report-gen">
                        <div class="table-responsive" id="my_table_{{$data->id}}">
                            <table class="table full-boder">
                                <tr>
                                    <td class="w-100 boder-t-none logo-left"><img src="{{asset('storage/'.setting('admin.icon_image'))}}" alt="" width="180"></td>
                                </tr>
                                <tr>
                                    <td align="center" class="main-title w-100 center-align">Recordent Individual Report </td>
                                </tr>
                                <tr class="d-flex justify-content-between boder-t1">
                                    <td class="w-60 boder-t-none"></td>
                                    <td class="w-40 boder-t-none">
                                        <div class="d-flex report-nu-date margin-h">
                                            <p class="f-w-6">Report Number</p>
                                            @php
                                            $reportNumber = '';
                                            $reportNumber = \Carbon\Carbon::now()->format('dmY');
                                            $reportNumber.='MI';
                                            $reportNumber.=rand(1000000,9999999);
                                            @endphp
                                            <p>{{$reportNumber}}</p>
                                        </div>
                                        <div class="d-flex report-nu-date margin-h">
                                            <p class="f-w-6">Date &amp; Time Stamp</p>
                                            <p>{{$dateTime}}</p>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="bg-color">
                                    <td class="w-100 sub-title bg-color center-align" align="center">Personal Details</td>
                                </tr>
                                <tr class="d-flex justify-content-between">
                                    <td class="w-60  boder-t-none">
                                        <div class="d-flex persona-det margin-h">
                                            <p class="f-w-6">Consumer Name</p>
                                            <p>{{$data->person_name}}</p>
                                        </div>
                                        <div class="d-flex persona-det margin-h">
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
                                        <div class="d-flex report-nu-date margin-h">
                                            <p class="f-w-6">Aadhar Number (last 6 digits)</p>
                                            <p>
                                                @if(!empty($data->aadhar_number))
                                                {{$data->aadhar_number}}
                                                @else
                                                Not Reported
                                                @endif
                                            </p>

                                        </div>
                                        <div class="d-flex report-nu-date margin-h">
                                            <p class="f-w-6">Mobile Number</p>
                                            <p>{{$data->contact_phone}}</p>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="bg-color">
                                    <td class="w-100 sub-title bg-color center-align" align="center">Summary</td>
                                </tr>
                                <tr class="d-flex summary-de">
                                    <td class="f-w-6">Total Members Submitted</td>
                                    <td align="center">{{$data->summary_totalMemberReported}}</td>
                                    <td class="f-w-6">Total Dues Submitted</td>
                                    <td align="center">Rs {{General::ind_money_format($data->summary_totalDueReported)}}</td>
                                    <td class="f-w-6">Total Dispute</td>
                                    <td class="center-align" align="center">{{$data->totalDispute}}</td>
                                </tr>
                                <tr>
                                    <td class="w-100 b-td"></td>
                                </tr>
                                <tr class="d-flex overdue-status">
                                    <td class="f-w-6">Overdue status</td>
                                    <td align="center">0-29 days</td>
                                    <td align="center">30-59 days</td>
                                    <td align="center">60-89 days</td>
                                    <td align="center">90-119 days</td>
                                    <td align="center">120-149 days</td>
                                    <td align="center">150-179 days</td>
                                    <td class="center-align" align="center">180+ days</td>
                                </tr>
                                <tr class="d-flex overdue-status">
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
                                @php
                                $disputeDetail = $accountDetail->dispute->last();
                                $disputeStatus = 'No';
                                $disputeComment = 'N/A';
                                if($disputeDetail){
                                $disputeComment = $disputeDetail->comment ? $disputeDetail->comment : 'N/A';
                                $disputeStatus = $disputeDetail->is_open == 1 ? 'Open' : 'Closed';
                                }
                                $amountDue = $accountDetail->due_amount - General::getPaidForDue($accountDetail->id);
                                @endphp
                                <tr class="account-de d-flex">
                                    <th>Member Name</th>
                                    <th>Amount Due</th>
                                    <th>Due Date</th>
                                    <th>Date Submitted</th>
                                    <th>Proof Submitted</th>
                                    <th>Status</th>
                                    <th>Dispute status</th>
                                </tr>
                                <tr class="account-de d-flex">
                                    <td>
                                        @if(Auth::id() == $accountDetail->addedBy->id)
                                        {{$accountDetail->addedBy->business_name}}
                                        @else
                                        XXXXX
                                        @endif
                                    </td>
                                    <td>Rs {{General::ind_money_format($amountDue)}}</td>
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
                                    <td>{{$disputeStatus}}</td>
                                </tr>
                                <tr class="d-flex">
                                    <td class="w-tw-c d-flex dis-commen">
                                        <p class="f-w-6">Dispute Comments</p>

                                    </td>
                                    <td class="w-fi-c bor-left dis-commen d-flex">
                                        <p class="f-w-6" style="word-break: break-all;">{{$disputeComment}}</p>

                                    </td>
                                </tr>
                                {{--
                                        <tr class="d-flex justify-content-center boder-t1">
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
                        <a href="{{route('admin.individual.report.download',['cp_id'=>$cp_id,'c_id'=>$data->id,'r_n'=>$reportNumber])}}" class="btn btn-primary pull-right downloadAsPdf">Download!</a>
                    </div>
                    @empty
                    <div>
                        <center class="no-records">No report found</center>
                    </div>
                    @endforelse

                </div>
            </div>
        </div>
    </div>
</div> -->


@if(!isset($response) || empty($response))
<div class="main_rc_section recordent_active">
    @else
    <div class="main_rc_section">
        @endif
        <!-- ------------------Dashboard------------------ -->
        @if(!isset($response) || empty($response))
        <div class="rc_screens  rc_dashbord recordent_screen">
            @else
            <div class="rc_screens  rc_dashbord">
                @endif
                <h2 class="rc_title">Hello {{Auth::user()->name}},

                    <span> {{Auth::user()->business_name}} </span></h2>



                @if(count($records) > 0)
                <!-- ------------------Recordent------------------ -->
                <div class="rc_section rc_block main_section recordentscreen">
                    <div class="rc_top">
                        <div class="left_top">
                            <h4> Recordent score is:</h4>
                        </div>
                        <div class="right_top"></div>
                        <div class="clear"></div>
                    </div>
                    <div class="rc_mid">
                        <h2>
                            <span style="display: block; padding:0 0 20px 0; font-size:40px">Coming soon !</span>
                        </h2>
                    </div>

                    <p class="last-update">Report Date: {{General::getFormatedDate($dateTime)}}</p>
                </div>
                <!-- ------------------end------------------ -->
                @else
                <div style="display: none;" class="recordentscreen">
                    <center class="no-records">No report found!</center>
                </div>
                @endif

                @if(!isset($response) || empty($response))
                <div class="equifex_recordentscreen">
                    <center class="no-records">No report found!</center>
                </div>
                @endif

                <!-- ------------------mainBlock------------------ -->
                @if(isset($response) && !empty($response && isset($response['CCRResponse'])))
                <div class="rc_section rc_block main_section equifax">
                    <div class="rc_top">
                        <div class="left_top">
                            <h4 class="fulname">
                                Current Score of

                                {{ isset($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['PersonalInfo']['Name']['FullName']) ? $response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['PersonalInfo']['Name']['FullName'] : $response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['PersonalInfo']['Name']['FullName']}} is:</h4>
                        </div>
                        <div class="right_top">

                            <p>Powered by </P>
                            <div class="ma_title eqfuifax_title">
                                <img src="/640px-Equifax_Logo.svg.png" alt="" />
                            </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="rc_mid">
                        <h2>{{ $response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['ScoreDetails'][0]['Value'] }} </h2>
                        @php
                        $score = $response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['ScoreDetails'][0]['Value'];
                        $scoreText = 'Needs improvement';
                        if($score > 750){
                        $scoreText = 'Excellent'; }
                        else if($score >= 700 && $score <= 749){ $scoreText='Good' ; } else if($score>= 650 && $score < 700){ $scoreText='Fair' ; } @endphp <h5 class="title_imporve ">{{$scoreText}}</h5>
                    </div>
                    <div class="profress-scroll">
                        <div class="progress rc_progress">
                            <div id="progress-bar-active-score" class="progress-bar active" role="progressbar" style="left:{{ number_format(($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['ScoreDetails'][0]['Value']*100)/900, 2) }}%;"></div>
                            <div class="progress-bar progress-bar-danger" role="progressbar">
                                <span class="lp">300</span> <span class="rp">650</span></div>
                            <div class="progress-bar progress-bar-warning" role="progressbar">
                                <span class="rp">700</span></div>
                            <div class="progress-bar progress-bar-info" role="progressbar">
                                <span class="rp">750</span></div>
                            <div class="progress-bar progress-bar-success" role="progressbar">
                                <span class="rp">900</span></div>
                        </div>
                    </div>
                    <p class="last-update">Report Date: {{General::getFormatedDate($response['InquiryResponseHeader']['Date'])}}</p>
                </div>
                @endif
                <!-- ------------------download_btn------------------ -->
                <div class="download_btn active_none">
                    <div class="togle_buttons">
                        @if((!isset($response) || empty($response)) && count($records) > 0)
                       
                            @if($consentRequest[0]['report'] == 2)
                            <a href="javascript:void(0)" class="equifax-active">Equifax</a>
                            @endif
                        <a href="javascript:void(0)" class="recordent-active active">Recordent</a>

                        <script>
                            $('.recordentscreen').css('display', 'block');
                            $('.equifex_recordentscreen').css('display', 'none');
                        </script>

                        @else
                            @if($consentRequest[0]['report'] == 2)
                            <a href="javascript:void(0)" class="equifax-active active">Equifax</a>
                            @endif
                        <a href="javascript:void(0)" class="recordent-active">Recordent</a>
                        @endif
                    </div>
                    @if((!isset($response) || empty($response)) && count($records) == 0)
                    <a disabled class="btn_d" href="#">Download <i class="glyphicon glyphicon-save-file"></i></a>
                    @else
                    <a target="_blank" class="btn_d" href="{{route('admin.individual.view.pdf', ['cp_id' => $cp_id, 'c_id' => $c_id])}}">Download <i class="glyphicon glyphicon-save-file"></i></a>
                    <!-- <a target="_blank" class="btn_d" href="{{route('admin.individual.download.pdf', ['cp_id' => $cp_id])}}">Download <i class="glyphicon glyphicon-save-file"></i></a> -->
                    @endif
                </div>


                @if(count($records) > 0)            

                <h4 class="reportSum active_none"><span>Report Summary</span></h4>

                <!-- ------------------download_btn end------------------ -->

                <!-- ------------------Recordent------------------ -->
                <div class="recordent row recordentscreen recordent_main">
                    <div id="recordent_member_profile" class="rc_section col-md-6 active_none top_rc_section">
                        <div class="rc_block">
                            <div class="rc_top">
                                <div class="left_top">
                                    <h4 class="sub">Profile information</h4>
                                </div>
                                <div class="clear"></div>
                            </div>


                            <div class="rc_bottom">
                                <div class="left_bottom">
                                    <p class="ac_inline">Phone: <span>{{$user['number']}}</span></p>
                                </div>
                                <div class="clear"></div>
                            </div>
                        </div>
                    </div>

                    <div id="recordent_members" class="rc_section col-md-6 active_none top_rc_section">
                        <div class="rc_block">
                            <div class="rc_top">
                                <div class="left_top">
                                    <h4 class="sub">Members</h4>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="rc_bottom">
                                <div class="left_bottom">
                                    <p>Total Members Reporting Dues: <span>{{$user['recordent']['total_members']}}</span></p>
                                </div>
                                <div class="clear"></div>
                            </div>



                        </div>
                    </div>

                    <div id="recordent_invoices" class="rc_section col-md-6 profile-sec1 active_none top_rc_section">
                        <div class="rc_block">
                            <div class="rc_top">
                                <div class="left_top">
                                    <h4 class="sub">Invoices</h4>
                                    <p>No.of records: {{$user['recordent']['summary_overDueStatus0To89Days']+$user['recordent']['summary_overDueStatus90To179Days']+$user['recordent']['summary_overDueStatus180PlusDays']}}</p>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="rc_bottom rc_summary">
                                <div class="left_bottom">
                                    <h4>Overdue status</h4>
                                    <p>1-89 days :<span> {{$user['recordent']['summary_overDueStatus0To89Days']}}</span></p>
                                    <p>90-180 days :<span> {{$user['recordent']['summary_overDueStatus90To179Days']}}</span></p>
                                    <p>180+ days :<span> {{$user['recordent']['summary_overDueStatus180PlusDays']}}</span></p>
                                </div>
                                <div class="clear"></div>
                            </div>

                        </div>
                    </div>

                    <div id="recordent_dues" class="rc_section col-md-6 active_none top_rc_section">
                        <div class="rc_block">
                            <div class="rc_top">
                                <div class="left_top">
                                    <h4 class="sub">Total dues</h4>
                                    <p>Total Due Amount: ₹ {{number_format($user['recordent']['total_dues_paid']+$user['recordent']['total_dues_unpaid'])}}</p>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="rc_bottom">
                                <div class="left_bottom">
                                    <p class="ac_inline">Paid: <span>₹ {{number_format($user['recordent']['total_dues_paid'])}}</span></p>
                                    <p class="ac_inline ac_lm">Unpaid : <span>₹ {{number_format($user['recordent']['total_dues_unpaid'])}}</span></p>
                                </div>
                                <div class="clear"></div>
                            </div>

                        </div>
                    </div>
                    <div class="clear"></div>
                </div>
                @endif

                <div id="recordent_member_profile_div" class="row recordentscreen displayNone_section">
                    <a class="back_to_dasborad_profile " href="javascript:void(0)">
                        <i class="fa fa-angle-left"></i>                        
                Back to Report Summary</a>
                    <div class="inner row subclick_display mobile_profile">   

                    <h2 class="rc_title_sub recordent-title profile_information">
                    Profile information
                    </h2>                     
                        <div class="col-md-6 list-recoddent">
                            <ul>
                                <li><span>Consumer's name:</span>{{$user['name'] }}</li>
                                <li><span>DOB:</span> {{$user['dob']}} </li>
                                
                            </ul>
                        </div>
                        <div class="col-md-6 list-recoddent">
                            <ul>
                            <li><span>Mobile number:</span>{{$user['number']}}</li>
                            <li><span>UID:</span>
                            @if(isset($consentRequest) && isset($consentRequest[0]) && isset($consentRequest[0]['idtype']) && $consentRequest[0]['idtype'] == 'AADHAR')
                            ****** {{substr($user['id_value'], -6)}}
                            @else
                            -
                            @endif
                            </li>
                            
                            </ul>
                        </div>
                    </div>
                </div>

                <div id="back_to_dasborad_members_main_div" class="displayNone_section">
                    <a class="back_to_dasborad_members" href="javascript:void(0)">
                                    <i class="fa fa-angle-left"></i>
                                    Back to Report Summary</a>
                                <div class="clear"></div>
                    <h2 class="rc_title_sub recordent-title">
                    <span class="m01">Members</span>    
                    <span class="m02">Invoices</span>    
                    <span class="m03">Total dues</span>    
                    
                    </h2>
                </div>

                <?php
                    $invoice_count = 0;
                ?>
                <div class="row">
                @forelse($records as $data)
                <div class="recordentscreen col-md-6 full-width-section-01">
                    <!-- ------------------Recordent screen -01 ------------------ -->
                    <a class="backtodasborad2" href="javascript:void(0)" onclick="window.location.reload()">
                        <i class="fa fa-angle-left"></i>
                        Back to Dashbord</a>

                    <!-- ------------------Recordent screen end ------------------ -->



                    <!-- ------------------Recordent screen -02 ------------------ -->
                    <div class="recrodent-section recordent_members">

                        <div class="displayNone_section recordent_members_div" id="recordent_02">
                            <!-- <a class="back_to_dasborad_members" href="javascript:void(0)">
                                <i class="fa fa-angle-left"></i>
                                </a>
                            <div class="clear"></div>
                            <h2 class="rc_title_sub recordent-title"></h2> -->
                            <div class="row recordent_cards">



                                <div class="rc_section  active_none">
                                    <div class="rc_block">
                                        <?php
                                            $paidCount = 0;
                                            $paid_amount = 0;
                                            $unpaid_amount = 0;
                                            foreach ($data->dues as $due_key => $due_value) {
                                                $paidCount = $paidCount + count($due_value->paid);
                                                $unpaid_amount += $due_value->due_amount;
                                                foreach ($due_value->paid as $r_due_paid_key => $r_due_paid_value) {
                                                    $paid_amount += $r_due_paid_value->paid_amount;
                                                }
                                            }
                                            $unpaid_amount = $unpaid_amount - $paid_amount;
                                        ?>

                                        <div class="rc_top">
                                            <div class="left_top">
                                                <h4 class="sub">Member Name: {{General::getMaskedCharacterAndNumber(substr($data->person_name, 0, 7))}}</h4>
                                                <p>Number of invoices reported: <span style="color:#202f7d; font-weight:bold">{{count($data->dues)+$paidCount}}</span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                        
                                        <div class="rc_bottom">
                                            <div class="left_bottom">
                                                <p class="ac_inline">Paid Invoices: <span> {{$paidCount}} </span></p>
                                                <p class="ac_inline ac_lm">Unpaid Invoices: <span> {{count($data->dues)}} </span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                    </div>
                                    <a member-id="{{$data->id}}" data="invoices_div_{{$data->id}}" class="rc_link subclick" href="#">&nbsp;</a>
                                </div>




                            </div>
                        </div>
                        <div class="clear"></div>

                        <div class="displayNone_section invoices_div_{{$data->id}}" id="subclick_022">
                            <a data="invoices_div_{{$data->id}}" class="back_to_members_invoice" href="javascript:void(0)">
                                <i class="fa fa-angle-left"></i>
                                Back to Previous Screen</a>
                            <div class="clear"></div>
                            <h2 class="rc_title_sub recordent-title fleft">Invoices for member: {{General::getMaskedCharacterAndNumber($data->person_name)}}</h2>
                            <div class="toggle_right paid_togle">
                                <label class="rc_switch">
                                    <input id="loan_switch_{{$data->id}}" onchange="loanSwitch(this.checked)" type="checkbox" checked="">
                                    <span class="slider round">
                                        <i class="acc-text  open-ac">Paid invoices</i>
                                        <i class="acc-text close-ac">Unpaid invoices</i>
                                    </span>
                                </label>
                            </div>
                            <div class="clear"></div>

                            @foreach($data->dues as $due_k => $due_v)
                            <?php
                            $invoice_count++;
                            $now = time(); // or your date as well
                            $your_date = strtotime($due_v->due_date);
                            $datediff = $now - $your_date;

                            $days = round($datediff / (60 * 60 * 24));

                            $temp_paid_amount = 0;
                            foreach ($due_v->paid as $temp_r_due_paid_key => $temp_r_due_paid_value) {
                                $temp_paid_amount += $temp_r_due_paid_value->paid_amount;
                            }                

                            ?>

                            <div class="closed_account display_none inner row subclick_display boderleftsection">                               
                                <div class="col-md-6 list-recoddent">
                                    <ul>
                                        <li><span>Invoice no:</span> {{$due_v->id}}</h4>
                                        <li><span>Status:</span>Unpaid</li>
                                        <li><span>Overdue status:</span>{{$days > 0 ? $days. ' days' : '-'}}</li>
                                    </ul>
                                </div>
                                <div class="col-md-6 list-recoddent">
                                    <ul>
                                        <li><span>Due date:</span>{{General::getFormatedDate($due_v->due_date)}}</li>
                                        <li><span>Date reported</span>{{General::getFormatedDate($due_v->created_at)}}</li>
                                        <li><span>Last payment date:</span>{{count($due_v->paid) > 0 ? General::getFormatedDate($due_v->paid[count($due_v->paid) - 1]->paid_date) : '-'}}</li>
                                    </ul>
                                </div>
                                <div class="col-md-6 list-recoddent">
                                    <ul>
                                    <li><span>Opening balance:</span>₹ {{number_format($due_v->due_amount)}}</li>
                                        <li><span>Closing balance:</span>₹ {{number_format($due_v->due_amount - $temp_paid_amount)}}</li>

                                        
                                        <li><span>Last payment:</span>{{count($due_v->paid) > 0 ? '₹ '.$due_v->paid[count($due_v->paid) - 1]->paid_amount : '-'}}</li> 
                                        
                                    </ul>
                                </div>
                                <div class="col-md-6 list-recoddent">
                                    <ul>
                                        @if(!empty($due_v->proof_of_due))
                                        <li><span>Proof of dues:</span><a target="_blank" href="{{url('/')}}/{{$due_v->proof_of_due}}">Yes</a></li>
                                        @else
                                        <li><span>Proof of dues:</span>No</li>
                                        @endif
                                        <!--<li><span>Dispute:</span></li>-->
                                    </ul>
                                </div>
                            </div>

                            @foreach($due_v->paid as $r_due_paid_key => $r_due_paid_value)
                            <?php
                            $invoice_count++;
                            ?>
                            <div class="open_account inner row subclick_display boderleftsection">
                                <div class="col-md-6 list-recoddent">
                                    <ul>
                                        <li><span>Invoice no:</span> {{$r_due_paid_value->due_id}}</h4>
                                        <li><span>Status:</span>Paid</li>
                                        <li><span>Paid amount:</span>₹ {{number_format($r_due_paid_value->paid_amount)}}</li>
                                        <li><span>Due amount:</span>₹ {{number_format($due_v->due_amount)}}</li>                                       
                                    </ul>
                                </div>
                                <div class="col-md-6 list-recoddent">
                                    <ul>
                                        <li><span>Due date:</span>{{General::getFormatedDate($due_v->due_date)}}</li>
                                        <li><span>Paid date:</span>{{General::getFormatedDate($r_due_paid_value->paid_date)}}</li>
                                        
                                        <li><span>Date reported:</span>{{General::getFormatedDate($due_v->created_at)}}</li>
                                    </ul>
                                </div>
                            </div>
                            @endforeach

                            @endforeach

                        </div>
                        <div class="clear"></div>
                    </div>
                    <!-- ------------------Recordent screen end ------------------ -->

                </div>
                @empty
                </div>
                <div class="recordentscreen">
                    <center class="no-records">No report found</center>
                </div>
                @endforelse


                <!-- ------------------end------------------ -->

                @if(isset($response) && !empty($response))
                <div class="row equifax">
                    <!-- ------------------Profile------------------ -->
                    <div class="rc_section col-md-4 profile-sec active_none">
                        <div class="rc_block">
                            <div class="rc_top">
                                <div class="left_top">
                                    <h4 class="sub">Profile</h4>
                                    <p>Personal details</p>
                                </div>
                                <div class="right_top hide_div">
                                    <h5 class="clr-green"><a class="link" href="#">
                                            <div class="pic">2</div>
                                        </a></h5>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="rc_bottom">
                                @php
                                $idnumber = isset($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['IdentityInfo']['PANId']) ? $response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['IdentityInfo']['PANId'][0] : 0;

                                $ndnumber = isset($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['IdentityInfo']['NationalIDCard']) ? $response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['IdentityInfo']['NationalIDCard'][0] : 0;



                                @endphp



                                <div class="left_bottom">
                                    @if(!empty($number['mobile']))
                                    <p><i>Phone:</i><span> {{ $number['mobile'] }}</span></p>
                                    @endif

                                    <p><i>PAN:</i><span>
                                            {{General::getMaskedPAN($idnumber['IdNumber'])}}


                                        </span></p>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <a class="rc_link rc_link_01" href="#"></a>
                        </div>
                    </div>
                    <!-- ------------------Age------------------ -->
                    <div class="rc_section col-md-4 active_none">
                        <div class="rc_block">
                            <div class="rc_top">
                                <div class="left_top">
                                    <h4 class="sub">Credit Age</h4>
                                    <p>Age of credit accounts</p>
                                </div>
                                <div class="right_top  hide_div">
                                    <h5 class="clr-green">Excellent</h5>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="rc_bottom">
                                <div class="left_bottom">
                                    <p>Since 1st account: <span>
                                            <?php
                                            $years = floor($diff / (365 * 60 * 60 * 24));
                                            $months = floor(($diff - $years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
                                            ?>
                                            {{$years}} years {{$months}} months
                                        </span></p>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <a class="rc_link rc_link_02" href="#"></a>
                        </div>
                    </div>
                    <!-- ------------------Payment history------------------ -->
                    <div class="rc_section col-md-4 active_none">
                        <div class="rc_block">
                            <div class="rc_top">
                                <div class="left_top">
                                    <h4 class="sub">Payment History</h4>
                                    <p>On-time & delayed payments</p>
                                </div>
                                <div class="right_top hide_div">
                                    <h5 class="clr-green">Excellent</h5>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="rc_bottom">
                                <div class="left_bottom">
                                    <p>Payments on time: <span>{{$totalSuccessPayment}}/{{$totalPayments}}</span></p>
                                </div>
                                <div class="right_top hide_div"><a class="not_link" href="#"><i class="glyphicon glyphicon-bell"></i></a></div>
                                <div class="clear"></div>
                            </div>
                            <a class="rc_link rc_link_03" href="#"></a>
                        </div>
                    </div>
                    <!-- ------------------Accounts------------------ -->
                    <div class="rc_section col-md-4 active_none">
                        @php
                        $open = 0;
                        $close = 0;
                        @endphp
                        @foreach ($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['RetailAccountDetails'] as $key => $value)
                        @if(isset($value['DateClosed']))
                        @php($close++)
                        @else
                        @php($open++)
                        @endif
                        @endforeach
                        <div class="rc_block">
                            <div class="rc_top">
                                <div class="left_top">
                                    <h4 class="sub">Accounts</h4>
                                    <p>Type & status of credit accounts</p>
                                </div>
                                <div class="right_top hide_div">
                                    <h5 class="clr-green">Excellent</h5>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="rc_bottom">
                                <div class="left_bottom">
                                    <p class="ac_inline">Open: <span>
                                            {{ $open }}</span></p>
                                    <p class="ac_inline ac_lm">Closed: <span>{{ $close }}</span></p>
                                </div>
                                <div class="right_top hide_div"><a class="not_link" href="#"><i class="glyphicon glyphicon-bell"></i></a></div>
                                <div class="clear"></div>
                            </div>
                            <a class="rc_link rc_link_04" href="#"></a>
                        </div>
                    </div>
                    <!-- ------------------Limits------------------ -->
                    <div class="rc_section col-md-4 active_none">
                        <div class="rc_block">
                            <div class="rc_top">
                                <div class="left_top">
                                    <h4 class="sub">Limits</h4>
                                    <p>Remaining limit on open credit cards</p>
                                </div>
                                <div class="right_top hide_div">
                                    <h5 class="clr-green">Excellent</h5>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="rc_bottom">
                                <div class="left_bottom">
                                    <p>Credit available: <span>{{$limit}}%</span></p>
                                </div>
                                <div class="right_top hide_div"><a class="not_link" href="#"><i class="glyphicon glyphicon-bell"></i></a></div>
                                <div class="clear"></div>
                            </div>
                            <a class="rc_link rc_link_05" href="#"></a>
                        </div>
                    </div>
                    <!-- ------------------Enquiries------------------ -->
                    <div class="rc_section col-md-4 active_none">
                        <div class="rc_block">
                            <div class="rc_top">
                                <div class="left_top">
                                    <h4 class="sub">Enquiries</h4>
                                    <p>Loan / Credit Card applications</p>
                                </div>
                                <div class="right_top hide_div">
                                    <h5 class="clr-green">Excellent</h5>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="rc_bottom">
                                <div class="left_bottom">
                                    <p>Last 30 days: <span>
                                            {{ isset($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['EnquirySummary']) && isset($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['EnquirySummary']['Past30Days']) ? $response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['EnquirySummary']['Past30Days'] : 0 }}
                                        </span></p>
                                </div>
                                <div class="right_top hide_div"><a class="not_link" href="#"><i class="glyphicon glyphicon-bell"></i></a></div>
                                <div class="clear"></div>
                            </div>
                            <a class="rc_link rc_link_06" href="#"></a>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            <a class="backtodasborad" href="javascript:void(0)" onClick="window.location.reload()">
                <i class="fa fa-angle-left"></i>
                Back to Report Summary</a>


            <a class="backtodasborad1" href="javascript:void(0)">
                <i class="fa fa-angle-left"></i>
                Back to Accounts</a>

            <!-- ------------------Profile  Section------------------ -->
            <div class="rc_screens webscreens  rc_personal_informaion" id="rc_01">
                <h2 class="rc_title_sub">Profile Information</span></h2>
                <div class="row personal_block">
                    <div class="rc_section col-md-4 ">
                        <div class="rc_block pa-rc">
                            <h4 class="sub">Personal & Account information</h4>




                            <ul>
                                <li><span>Consumer's first name:</span> <span>{{ isset($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['PersonalInfo']['Name']['FirstName']) ? $response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['PersonalInfo']['Name']['FirstName'] : $response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['PersonalInfo']['Name']['FullName']  }} </span></li>
                                <li><span>Consumer's last name:</span> <span>
                                        {{ isset($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['PersonalInfo']['Name']['FirstName']) ? $response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['PersonalInfo']['Name']['LastName'] : $response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['PersonalInfo']['Name']['FullName']  }}
                                    </span></li>
                                <li><span>DOB:</span> <span>

                                        {{ isset($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['PersonalInfo']['DateOfBirth']) ? General::getMaskedDOB(General::getFormatedDate($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['PersonalInfo']['DateOfBirth'])) : ''}}

                                    </span></li>
                                <li><span>Gender:</span> <span>
                                        {{ isset($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['PersonalInfo']['Gender']) ? $response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['PersonalInfo']['Gender'] : ''}}
                                    </span></li>


                                @if(!empty($sallerys['sallery']))
                                <li><span>Sallary:</span> <span> {{$sallerys['sallery']}} </span></li>
                                @endif




                            </ul>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="rc_section col-md-4 ">
                        <div class="rc_block id-rc">
                            <h4 class="sub">ID & Phone Number</h4>
                            <ul>

                                @if(isset($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['IdentityInfo']['PANId']) && isset($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['IdentityInfo']['PANId'][0]))
                                <li><span>PAN:</span> <span>{{General::getMaskedPAN($idnumber['IdNumber'])}} </span></li>



                    @endif

                    @if(isset($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['IdentityInfo']['VOTERId']) && isset($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['IdentityInfo']['VOTERId'][0]))
                    <li><span>Voter ID: </span> <span>

                    {{ General::getMaskedCharacters($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['IdentityInfo']['VOTERId'][0]['IdNumber']) }}
                    

                        </span>
                    </li>
                    @endif

                    @if(isset($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['IdentityInfo']['Passport']) && isset($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['IdentityInfo']['Passport'][0]))
                    <li><span>Passport:</span>
                        <span>{{ General::getMaskedPAN($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['IdentityInfo']['Passport'][0]['IdNumber']) }}
                        </span>
                    </li>
                    @endif


                    @if(isset($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['IdentityInfo']['NationalIDCard']) && isset($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['IdentityInfo']['NationalIDCard'][0]))
                    <li><span>UID:</span> <span> ******* {{substr($ndnumber['IdNumber'], 6)}} </span> </li>
                    @endif

                    @if(isset($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['IdentityInfo']['DriverLicence']) && isset($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['IdentityInfo']['DriverLicence'][0]))
                    <li><span>Driver's License:</span><span>
                    {{ General::getMaskedCharacters($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['IDAndContactInfo']['IdentityInfo']['DriverLicence'][0]['IdNumber']) }}        
                
                </span> </li>
                    @endif






                                @if(!empty($number['mobile']))
                                <li><span>Mobile phone:</span><span>{{ $number['mobile'] }}</span> </li>
                                @endif



                                @if(!empty($number['home']))
                                <li><span>Home phone:</span><span>{{General::getMaskedPhone($number['home'])}} </span></li>
                                @endif



                                @if(!empty($number['workphone']))
                                <li><span>Office phone:</span><span>{{ General::getMaskedPhone($number['workphone']) }}</span> </li>
                                @endif


                            </ul>
                            <div class="clear"></div>
                        </div>
                    </div>
                    <div class="rc_section col-md-4 ">
                        <div class="rc_block cd-rc">
                            <h4 class="sub">Contact Details</h4>
                            <ul>

                                <li class="addres-rc"><span>Address: </span> <span class="address_span">

                                        **** **** **** </br> **** **** **** {{substr($AddressInfo[0]['Address'], 200)}}



                                    </span></li>
                                <li><span>State:</span> <span> {{isset($AddressInfo[0]) ? $AddressInfo[0]['State'] : '-'}} </span></li>
                                <li><span>Pin code:</span> <span> {{isset($AddressInfo[0]) ? $AddressInfo[0]['Postal'] : '-'}} </span></li>
                                <li><span>Reported date:</span> <span> {{isset($AddressInfo[0]) ? General::getFormatedDate($AddressInfo[0]['ReportedDate']) : '-'}} </span></li>
                            </ul>
                            <div class="clear"></div>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>

            <!-- ------------------Age of Accounts Section------------------ -->
            <div class="rc_screens webscreens  rc_age" id="rc_02">
                <h2 class="rc_title_sub toggle_left">Age of Accounts</span></h2>
                <div class="toggle_right">
                    <label class="rc_switch">
                        <input id="loan_switch" onchange="loanSwitch(this.checked)" type="checkbox" checked>
                        <span class="slider round">
                            <i class="acc-text  open-ac">Open</i>
                            <i class="acc-text close-ac">Closed</i>
                        </span>
                    </label>
                </div>
                <div class="clear"></div>
                <div class="row ">
                    <?php
                    $openAccountFlag = false;
                    $closedAccountFlag = false;
                    ?>        

                    @foreach ($RetailAccountDetails as $key => $value)
                    <?php $class = $value['Open'] == 'Yes' ? 'open_account' : 'closed_account display_none';    ?>
                    <?php
                    if($value['Open'] == 'Yes'){
                        $openAccountFlag = true;
                    }
                    else{
                        $closedAccountFlag = true;
                    }
                    ?>
                    <div class="rc_section {{$class}} col-md-4">
                        <div class="rc_block pa-rc">
                            <div class="left">
                                <p class="spanp"> <span class="spanName">Lender name:</span> **** **** {{substr($value['Institution'], 140)}}</p>
                                <p class="spanp"><span class="spanName">A/C number:</span> **** {{substr($value['AccountNumber'], -4)}}</p>
                            </div>
                            <div class="right_open">
                                <a class="acvite-link" href="#">{{General::getAccountStatus1($value['Open'])}}</a>
                                <p>As of: {{General::getFormatedDate($value['DateReported'])}}</p>
                            </div>
                            <div class="clear clear_top"></div>
                            <p class="fleft">{{$value['AccountType']}}</p>
                            <p class="fright">
                                <?php
                                $card_age_years = '';
                                $card_age_months = '';
                                if (isset($value['DateOpened'])) {

                                    $date1 = strtotime($value['DateOpened']);
                                    $date2 = strtotime(date('Y-m-d'));
                                    $diff = abs($date2 - $date1);
                                    $card_age_years = floor($diff / (365 * 60 * 60 * 24));
                                    $card_age_months = floor(($diff - $card_age_years * 365 * 60 * 60 * 24) / (30 * 60 * 60 * 24));
                                }
                                ?>
                                @if($card_age_years != '')
                                {{$card_age_years}} years {{$card_age_months}} months
                                @else
                                Data Not Available
                                @endif
                            </p>
                            <div class="clear"></div>
                        </div>
                    </div>
                    @endforeach

                    @if(!$openAccountFlag)
                        <div class="rc_section open_account col-md-4">
                        No Open Accounts Available!
                        </div>
                    @endif

                    @if(!$closedAccountFlag)
                        <div class="rc_section closed_account display_none col-md-4">
                        No Closed Accounts Available!
                        </div>
                    @endif
                </div>
                <div class="clear"></div>
            </div>

            <!-- ------------------Payment History Section------------------ -->
            <div class="rc_screens webscreens  rc_payment_history" id="rc_03">
                <h2 class="rc_title_sub toggle_left">Payment History</span></h2>
                <div class="toggle_right"> <label class="rc_switch">
                        <input id="loan_switch" onchange="loanSwitch(this.checked)" type="checkbox" checked>
                        <span class="slider round">
                            <i class="acc-text  open-ac">Open</i>
                            <i class="acc-text close-ac">Closed</i>
                        </span>
                    </label>
                </div>
                <div class="clear"></div>
                <div class="row payment_history p_h_div">

                    <?php
                    $paymentStatusOnTimeArray = ['000', '*', 'STD', 'NEW', 'CLSD', 'OPEN', 'RES'];
                    $paymentStatusLateArray = ['01+', '30+', '60+'];
                    $paymentStatusVeryLateTimeArray = ['180+', '360+', '540+', '720+', 'SET', 'WOF', 'POWS', 'INV', 'DEV', 'RNC'];
                    ?>

                    <?php
                    $openHistoryAccountFlag = false;
                    $closedHistoryAccountFlag = false;
                    ?> 

                    @foreach ($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['RetailAccountDetails'] as $key => $value)

                    <?php
                    $tempYears = array();
                    $onTimePaymentCount = 0;
                    foreach ($value['History48Months'] as $k => $v) {
                        $date = DateTime::createFromFormat("m-y", $v['key']);

                        $str = '';
                        if (in_array($v['PaymentStatus'], $paymentStatusOnTimeArray)) {
                            $str = '<a class="anc_active oneitme" href="javascript:void(0)">';
                            $onTimePaymentCount++;
                        } else if (in_array($v['PaymentStatus'], $paymentStatusLateArray)) {
                            $str = '<a class="anc_active miditme" href="javascript:void(0)">';
                        } else if (in_array($v['PaymentStatus'], $paymentStatusVeryLateTimeArray)) {
                            $str = '<a class="anc_active latetime" href="javascript:void(0)">';
                        } else {
                            $str = 'X';
                        }

                        if (isset($tempYears[$date->format("Y")])) {
                            $tempYears[$date->format("Y")][$date->format("M")] = $str;
                        } else {
                            $tempYears[$date->format("Y")] = array();
                            $tempYears[$date->format("Y")][$date->format("M")] = $str;
                        }
                    }
                    // dd($tempYears);
                    $history_percentage = number_format(($onTimePaymentCount * 100) / count($value['History48Months']), 2);
                    ?>

                    <?php
                        if($value['Open'] == 'Yes'){
                            $openHistoryAccountFlag = true;
                        }
                        else{
                            $closedHistoryAccountFlag = true;
                        }
                    ?>

                    <?php $class = $value['Open'] == 'Yes' ? 'open_account' : 'closed_account display_none';    ?>
                    <div id="{{$history_percentage}}" class="history_percentage rc_section col-md-12 {{$class}}">
                        <div class="rc_block pa-rc">
                            <div class="item">
                                <h4 class="sub"><i>Lender name: </i>**** **** {{substr($value['Institution'], 140)}}<span>
                                        <i>A/C number: </i>
                                        **** {{substr($value['AccountNumber'], -4)}}</span></h4>
                                <p>
                                    <em>{{$value['AccountType']}}</em>
                                    {{$onTimePaymentCount}} / {{count($value['History48Months'])}} <span>On Time</span></p>
                                <div class="clear"></div>
                            </div>
                            <a href="javascript:void(0)" class="view-btn"><span class="view_s">View History</span> <span class="close_s">Close History</span></a>
                        </div>
                        <div class="grid_table">


                            <div class="grid-block">
                                <p class="sus">On Time</p>
                                <p class="prog">1-89 days late</p>
                                <p class="dang">90+ days late</p>
                            </div>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col">&nbsp;</th>
                                        <th scope="col">Dec</th>
                                        <th scope="col">Nov</th>
                                        <th scope="col">Oct</th>
                                        <th scope="col">Sep</th>
                                        <th scope="col">Aug</th>
                                        <th scope="col">Jul</th>
                                        <th scope="col">Jun</th>
                                        <th scope="col">May</th>
                                        <th scope="col">Apr</th>
                                        <th scope="col">Mar</th>
                                        <th scope="col">Feb</th>
                                        <th scope="col">Jan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($tempYears as $tempYears_key => $tempYears_value)
                                    <tr>
                                        <td>{{$tempYears_key}}</td>

                                        <td>{!! isset($tempYears_value['Dec']) ? $tempYears_value['Dec'] : '' !!}</td>
                                        <td>{!! isset($tempYears_value['Nov']) ? $tempYears_value['Nov'] : '' !!}</td>
                                        <td>{!! isset($tempYears_value['Oct']) ? $tempYears_value['Oct'] : '' !!}</td>
                                        <td>{!! isset($tempYears_value['Sep']) ? $tempYears_value['Sep'] : '' !!}</td>
                                        <td>{!! isset($tempYears_value['Aug']) ? $tempYears_value['Aug'] : '' !!}</td>
                                        <td>{!! isset($tempYears_value['Jul']) ? $tempYears_value['Jul'] : '' !!}</td>
                                        <td>{!! isset($tempYears_value['Jun']) ? $tempYears_value['Jun'] : '' !!}</td>
                                        <td>{!! isset($tempYears_value['May']) ? $tempYears_value['May'] : '' !!}</td>
                                        <td>{!! isset($tempYears_value['Apr']) ? $tempYears_value['Apr'] : '' !!}</td>
                                        <td>{!! isset($tempYears_value['Mar']) ? $tempYears_value['Mar'] : '' !!}</td>
                                        <td>{!! isset($tempYears_value['Feb']) ? $tempYears_value['Feb'] : '' !!}</td>
                                        <td>{!! isset($tempYears_value['Jan']) ? $tempYears_value['Jan'] : '' !!}</td>



















                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach

                    @if(!$openHistoryAccountFlag)
                        <div class="rc_section open_account col-md-4">
                        No Open Accounts Available!
                        </div>
                    @endif

                    @if(!$closedHistoryAccountFlag)
                        <div class="rc_section closed_account display_none col-md-4">
                        No Closed Accounts Available!
                        </div>
                    @endif
                </div>
                <div class="clear"></div>
            </div>


            <div class="rc_screens webscreens  rc_loans" id="rc_04">

                <h2 class="rc_title_sub">Accounts</span></h2>

                <div class="row enquires">

                    <div class="rc_section col-md-6">
                        <div class="rc_block">
                            <div class="rc_top">
                                <div class="left_top">
                                    <h4 class="sub">Loans </h4>
                                    <p>Type & status of your loans</p>
                                </div>
                                <div class="right_top hide_div">
                                    <h5 class="clr-green"><a class="link" href="#">Excellent</a></h5>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="rc_bottom">
                                <div class="left_bottom">
                                    <p class="ac_inline">Active: <span> {{$openClosedAccountsArr['loan_accounts']['open']}} </span></p>
                                    <p class="ac_inline ac_lm">Closed: <span> {{$openClosedAccountsArr['loan_accounts']['closed']}} </span></p>
                                </div>
                                <div class="right_top hide_div"><a class="not_link" href="#"><i class="glyphicon glyphicon-bell"></i></a></div>
                                <div class="clear"></div>
                            </div>
                            <a class="rc_link rc_link_04a" href="#"></a>
                        </div>
                    </div>

                    @if($openClosedAccountsArr['credit_card_accounts']['open'] > 0 || $openClosedAccountsArr['credit_card_accounts']['closed'] > 0)
                    <div class="rc_section col-md-6">
                        <div class="rc_block">
                            <div class="rc_top">
                                <div class="left_top">
                                    @if(($openClosedAccountsArr['credit_card_accounts']['open'] + $openClosedAccountsArr['credit_card_accounts']['closed']) > 1)
                                    <h4 class="sub">Credit Cards</h4>
                                    @else
                                    <h4 class="sub">Credit Card</h4>
                                    @endif
                                    <p>Type & status of your credit cards</p>
                                </div>
                                <div class="right_top hide_div">
                                    <h5 class="clr-green"><a class="link" href="#">Excellent</a></h5>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <div class="rc_bottom">
                                <div class="left_bottom">
                                    <p class="ac_inline">Active: <span> {{$openClosedAccountsArr['credit_card_accounts']['open']}} </span></p>
                                    <p class="ac_inline ac_lm">Closed: <span> {{$openClosedAccountsArr['credit_card_accounts']['closed']}} </span></p>
                                </div>
                                <div class="right_top hide_div"><a class="not_link" href="#"><i class="glyphicon glyphicon-bell"></i></a></div>
                                <div class="clear"></div>
                            </div>
                            <a class="rc_link rc_link_04b" href="#"></a>
                        </div>
                    </div>
                    @endif
                </div>

            </div>


            <!-- ------------------Loans Section------------------ -->
            <div class="rc_screens webscreens  rc_loans" id="rc_04a">
                <h2 class="rc_title_sub toggle_left">Loans</span></h2>
                <div class="toggle_right">
                    <label class="rc_switch">
                        <input id="loan_switch_check" onchange="loanSwitch(this.checked)" type="checkbox" checked>
                        <span class="slider round">
                            <i class="acc-text  open-ac">Open</i>
                            <i class="acc-text close-ac">Closed</i>
                        </span>
                    </label>
                </div>
                <div class="clear"></div>
                <div class="row enquires enq_loans">
                    <div class="rc_section col-md-12">
                        <div class="rc_block">
                            <div class="open_account_div display_none">No Open Account for this customer</div>
                            <div class="closed_account_div display_none">No Closed Account for this customer</div>
                            <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="tab_01_tab" data-toggle="pill" href="#tab_01" role="tab" aria-controls="tab_01" aria-selected="true"><i class="per"></i> Personal Loans</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="tab_02_tab" data-toggle="pill" href="#tab_02" role="tab" aria-controls="tab_02" aria-selected="false"><i class="home"></i> Home Loans</a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="tab_03_tab" data-toggle="pill" href="#tab_03" role="tab" aria-controls="tab_03" aria-selected="false"><i class="car"></i> Auto Loans</a>
                                </li>

                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="tab_06_tab" data-toggle="pill" href="#tab_06" role="tab" aria-controls="tab_06" aria-selected="false"><i class="pro"></i> Business Loans</a>
                                </li>

                                <li class="nav-item" role="presentation">
                                    <a class="nav-link" id="tab_05_tab" data-toggle="pill" href="#tab_05" role="tab" aria-controls="tab_05" aria-selected="false"><i class="gen"></i> Other Loans</a>
                                </li>

                                <!-- <li class="nav-item" role="presentation">
              <a class="nav-link" id="tab_07_tab" data-toggle="pill" href="#tab_07" role="tab" aria-controls="tab_07" aria-selected="false"><i class="cre"></i> Credit cards</a>
            </li> -->
                            </ul>
                            <?php
                            $firstTabActive = false;
                            ?>
                            <div class="tab-content" id="pills-tabContent">


                                <!-- ------------------Tab 01------------------ -->
                                <div class="tab-pane fade" id="tab_01" role="tabpanel" aria-labelledby="tab_01_tab">
                                    <?php
                                    $existACC = false;
                                    $openExist = false;
                                    $closedExist = false;
                                    ?>

                                    @foreach($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['RetailAccountDetails'] as $key => $value)
                                    @if($value['AccountType'] == 'Personal Loan')

                                    @if(!$firstTabActive)
                                    <script>
                                        $('#tab_01_tab').addClass('active_tab_new');
                                    </script>
                                    @endif

                                    <?php
                                    $existACC = true;
                                    $firstTabActive = true;
                                    $class = $value['Open'] == 'Yes' ? 'open_account' : 'closed_account display_none';
                                    ?>

                                    <?php
                                    if ($value['Open'] == 'Yes') {
                                        $openExist = true;
                                    } else {
                                        $closedExist = true;
                                    }
									$ownershipType = array_key_exists('OwnershipType',$value) ? $value['OwnershipType'] : 'NA';
                                    ?>

                                    <div class="rc_block {{$class}}">
                                        <div class="row moblie-tab">
                                            <div class="col-md-6 loans-rc">
                                                <div class="item">
                                                    <p><span>Lender name</span>**** **** {{substr($value['Institution'], 140)}}</p>
                                                    <p><span>A/C number </span>**** {{substr($value['AccountNumber'], -4)}}</p>
                                                    <p><span>Ownership type</span>{{$ownershipType}}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6 loans-rc">
                                                <div class="item">
                                                    <p><span>Account type</span>{{$value['AccountType']}}</p>
                                                    <p><span>Account status </span>{{$value['AccountStatus']}}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6 loans-rc">
                                                <div class="item">
                                                    <p><span>Date opened</span>{{isset($value['DateOpened']) ? General::getFormatedDate($value['DateOpened']) : '-'}}</p>
                                                    @if($value['Open'] != 'Yes')
                                                    <p><span>Date closed </span>{{isset($value['DateClosed']) ? General::getFormatedDate($value['DateClosed']) : '-'}}</p>
                                                    @endif
                                                    <p><span>Last updated </span>{{isset($value['DateReported']) ? General::getFormatedDate($value['DateReported']) : '-'}}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6 loans-rc">
                                                <div class="item">
                                                    <p><span>Loan amount </span>₹ {{isset($value['SanctionAmount']) ? number_format($value['SanctionAmount'],2) : '-'}}</p>
                                                    <p><span>Current balance</span>₹ {{isset($value['Balance']) ? number_format($value['Balance']) : '-'}}</p>
                                                    @if(isset($value['PastDueAmount']) && $value['PastDueAmount'] > 0)
                                                    <p><span>Overdue amount</span>{{isset($value['PastDueAmount']) ? number_format($value['PastDueAmount'],2) : '-'}}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @endif
                                    @endforeach


                                    @if(!$closedExist)
                                    <div class="rc_block closed_account_no_data display_none">
                                        There are no open loans for this customer
                                    </div>
                                    @endif

                                    @if(!$openExist)
                                    <div class="rc_block open_account_no_data">
                                        There are no open loans for this customer
                                    </div>
                                    @endif

                                    @if(!$existACC)
                                    No Account Exists!!!!!!
                                    <script>
                                        $('#tab_01_tab').css('display', 'none');
                                    </script>
                                    @else



                                    <script>
                                        $('#tab_01').addClass('active in');
                                    </script>
                                    @endif

                                </div>

                                <!-- ------------------Tab 02------------------ -->
                                <div class="tab-pane fade {{!$firstTabActive ? 'active in' : ''}}" id="tab_02" role="tabpanel" aria-labelledby="tab_02_tab">
                                    <?php $existACC = false;
                                    $openExist = false;
                                    $closedExist = false; ?>

                                    @foreach($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['RetailAccountDetails'] as $key => $value)
                                    @if($value['AccountType'] == 'Property Loan' || $value['AccountType'] == 'Housing Loan')

                                    @if(!$firstTabActive)
                                    <script>
                                        $('#tab_02_tab').addClass('active_tab_new');
                                    </script>
                                    @endif

                                    <?php
                                    $existACC = true;
                                    $firstTabActive = true;
                                    $class = $value['Open'] == 'Yes' ? 'open_account' : 'closed_account display_none';
                                    ?>

                                    <?php
                                    if ($value['Open'] == 'Yes') {
                                        $openExist = true;
                                    } else {
                                        $closedExist = true;
                                    }
									$ownershipType = array_key_exists('OwnershipType',$value) ? $value['OwnershipType'] : 'NA';
                                    ?>

                                    <div class="rc_block {{$class}}">
                                        <div class="row moblie-tab">
                                            <div class="col-md-6 loans-rc">
                                                <div class="item">
                                                    <p><span>Lender name </span>**** **** {{substr($value['Institution'], 140)}}</p>
                                                    <p><span>A/C number </span>**** {{substr($value['AccountNumber'], -4)}}</p>
                                                    <p><span>Ownership type</span>{{$ownershipType}}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6 loans-rc">
                                                <div class="item">
                                                    <p><span>Account type</span>{{$value['AccountType']}}</p>
                                                    <p><span>Account status </span>{{$value['AccountStatus']}}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6 loans-rc">
                                                <div class="item">
                                                    <p><span>Date opened</span>{{isset($value['DateOpened']) ? General::getFormatedDate($value['DateOpened']) : '-'}}</p>
                                                    @if($value['Open'] != 'Yes')
                                                    <p><span>Date closed </span>{{isset($value['DateClosed']) ? General::getFormatedDate($value['DateClosed']) : '-'}}</p>
                                                    @endif
                                                    <p><span>Last updated </span>{{isset($value['DateReported']) ? General::getFormatedDate($value['DateReported']) : '-'}}</p>
                                                </div>
                                            </div>
                                            <div class="col-md-6 loans-rc">
                                                <div class="item">
                                                    <p><span>Loan amount </span>₹ {{isset($value['SanctionAmount']) ? number_format($value['SanctionAmount'],2) : '-'}}</p>
                                                    <p><span>Current balance</span>₹ {{isset($value['Balance']) ? number_format($value['Balance'],2) : '-'}}</p>
                                                    @if(isset($value['PastDueAmount']) && $value['PastDueAmount'] > 0)
                                                    <p><span>Overdue amount</span>{{isset($value['PastDueAmount']) ? number_format($value['PastDueAmount'],2) : '-'}}</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                    @endforeach

                                    @if(!$closedExist)
                                    <div class="rc_block closed_account_no_data display_none">
                                        There are no open loans for this customer
                                    </div>
                                    @endif

                                    @if(!$openExist)
                                    <div class="rc_block open_account_no_data">
                                        There are no open loans for this customer
                                    </div>
                                    @endif

                                    @if(!$existACC)
                                    No Account Exists1
                                    <script>
                                        $('#tab_02_tab').css('display', 'none');
                                    </script>
                                    @endif
                                </div>

                                <!-- ------------------Tab 03------------------ -->
                                <div class="tab-pane fade {{!$firstTabActive ? 'active in' : ''}}" id="tab_03" role="tabpanel" aria-labelledby="tab_03_tab">
                                    <?php $existACC = false;
                                    $openExist = false;
                                    $closedExist = false; ?>

                                    <div class="row moblie-tab">
                                        <div class="col-md-12 loans-rc">
                                            @foreach($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['RetailAccountDetails'] as $key => $value)
                                            @if($value['AccountType'] == 'Auto Loan' || $value['AccountType'] == 'Auto Lease')

                                            @if(!$firstTabActive)
                                            <script>
                                                $('#tab_03_tab').addClass('active_tab_new');
                                            </script>
                                            @endif

                                            <?php
                                            $existACC = true;
                                            $firstTabActive = true;
                                            $class = $value['Open'] == 'Yes' ? 'open_account' : 'closed_account display_none';
                                            ?>

                                            <?php
                                            if ($value['Open'] == 'Yes') {
                                                $openExist = true;
                                            } else {
                                                $closedExist = true;
                                            }
											$ownershipType = array_key_exists('OwnershipType',$value) ? $value['OwnershipType'] : 'NA';
                                            ?>

                                            <div class="rc_block {{$class}}">

                                                <div class="row moblie-tab">
                                                    <div class="col-md-6 loans-rc">
                                                        <div class="item">

                                                            <p><span>Lender name </span>**** **** {{substr($value['Institution'], 140)}}</p>

                                                            <p><span>A/C number </span>**** {{substr($value['AccountNumber'], -4)}}</p>
                                                            <p><span>Ownership type</span>{{$ownershipType}}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 loans-rc">
                                                        <div class="item">
                                                            <p><span>Account type</span>{{$value['AccountType']}}</p>
                                                            <p><span>Account status </span>{{$value['AccountStatus']}}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 loans-rc">
                                                        <div class="item">
                                                            <p><span>Date opened</span>{{isset($value['DateOpened']) ? General::getFormatedDate($value['DateOpened']) : '-'}}</p>
                                                            @if($value['Open'] != 'Yes')
                                                            <p><span>Date closed </span>{{isset($value['DateClosed']) ? General::getFormatedDate($value['DateClosed']) : '-'}}</p>
                                                            @endif
                                                            <p><span>Last updated </span>{{isset($value['DateReported']) ? General::getFormatedDate($value['DateReported']) : '-'}}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 loans-rc">
                                                        <div class="item">
                                                            <p><span>Loan amount </span>₹ {{isset($value['SanctionAmount']) ? number_format($value['SanctionAmount'],2) : '-'}}</p>
                                                            <p><span>Current balance</span>₹ {{isset($value['Balance']) ? number_format($value['Balance'],2) : '-'}}</p>
                                                            @if(isset($value['PastDueAmount']) && $value['PastDueAmount'] > 0)
                                                            <p><span>Overdue amount</span>{{isset($value['PastDueAmount']) ? number_format($value['PastDueAmount'],2) : '-'}}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            @endforeach

                                            @if(!$closedExist)
                                            <div class="rc_block closed_account_no_data display_none">
                                                There are no open loans for this customer
                                            </div>
                                            @endif

                                            @if(!$openExist)
                                            <div class="rc_block open_account_no_data">
                                                There are no open loans for this customer
                                            </div>
                                            @endif

                                            @if(!$existACC)
                                            No Account Exists.
                                            <script>
                                                $('#tab_03_tab').css('display', 'none');
                                            </script>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- ------------------Tab 06------------------ -->
                                <div class="tab-pane fade {{!$firstTabActive ? 'active in' : ''}}" id="tab_06" role="tabpanel" aria-labelledby="tab_06_tab">
                                    <?php $existACC = false;
                                    $openExist = false;
                                    $closedExist = false; ?>

                                    <div class="row moblie-tab">
                                        <div class="col-md-12 loans-rc">
                                            @foreach($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['RetailAccountDetails'] as $key => $value)
                                            @if(
                                            $value['AccountType'] == 'Business Loan'
                                            || $value['AccountType'] == 'Business Loan-Priority Sector-Small Business'
                                            || $value['AccountType'] == 'Business Loan - Priority Sector- Agriculture'
                                            || $value['AccountType'] == 'Business Loan - Priority Sector- Others'
                                            || $value['AccountType'] == 'Business Non-Funded Credit Facility'
                                            || $value['AccountType'] == 'Business Non-Funded Credit Facility - Priority Sector - Small Business'
                                            || $value['AccountType'] == 'Business Non-Funded Credit Facility - Priority Sector - Agriculture'
                                            || $value['AccountType'] == 'Business Non-Funded Credit Facility - Priority Sector - Other'
                                            || $value['AccountType'] == 'Business Loan Against Bank Deposits'
                                            )


                                            @if(!$firstTabActive)
                                            <script>
                                                $('#tab_06_tab').addClass('active_tab_new');
                                            </script>
                                            @endif

                                            <?php
                                            $existACC = true;
                                            $firstTabActive = true;
                                            $class = $value['Open'] == 'Yes' ? 'open_account' : 'closed_account display_none';
                                            ?>


                                            <?php
                                            if ($value['Open'] == 'Yes') {
                                                $openExist = true;
                                            } else {
                                                $closedExist = true;
                                            }
											$ownershipType = array_key_exists('OwnershipType',$value) ? $value['OwnershipType'] : 'NA';
                                            ?>

                                            <div class="rc_block {{$class}}">
                                                <div class="row moblie-tab">
                                                    <div class="col-md-6 loans-rc">
                                                        <div class="item">
                                                            <p><span>Lender name </span>**** **** {{substr($value['Institution'], 140)}}</p>
                                                            <p><span>A/C number </span>**** {{substr($value['AccountNumber'], -4)}}</p>
                                                            <p><span>Ownership type</span>{{$ownershipType}}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 loans-rc">
                                                        <div class="item">
                                                            <p><span>Account type</span>{{$value['AccountType']}}</p>
                                                            <p><span>Account status </span>{{$value['AccountStatus']}}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 loans-rc">
                                                        <div class="item">
                                                            <p><span>Date opened</span>{{isset($value['DateOpened']) ? General::getFormatedDate($value['DateOpened']) : '-'}}</p>
                                                            @if($value['Open'] != 'Yes')
                                                            <p><span>Date closed </span>{{isset($value['DateClosed']) ? General::getFormatedDate($value['DateClosed']) : '-'}}</p>
                                                            @endif
                                                            <p><span>Last updated </span>{{isset($value['DateReported']) ? General::getFormatedDate($value['DateReported']) : '-'}}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 loans-rc">
                                                        <div class="item">
                                                            <p><span>Loan amount </span>₹ {{isset($value['SanctionAmount']) ? number_format($value['SanctionAmount'],2) : '-'}}</p>
                                                            <p><span>Current balance</span>₹ {{isset($value['Balance']) ? number_format($value['Balance'],2) : '-'}}</p>
                                                            @if(isset($value['PastDueAmount']) && $value['PastDueAmount'] > 0)
                                                            <p><span>Overdue amount</span>{{isset($value['PastDueAmount']) ? number_format($value['PastDueAmount'],2) : '-'}}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            @endforeach

                                            @if(!$closedExist)
                                            <div class="rc_block closed_account_no_data display_none">
                                                There are no open loans for this customer
                                            </div>
                                            @endif

                                            @if(!$openExist)
                                            <div class="rc_block open_account_no_data">
                                                There are no open loans for this customer
                                            </div>
                                            @endif

                                            @if(!$existACC)
                                            No Account Exists..
                                            <script>
                                                $('#tab_06_tab').css('display', 'none');
                                            </script>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- ------------------Tab 05------------------ -->
                                <div class="tab-pane fade {{!$firstTabActive ? 'active in' : ''}}" id="tab_05" role="tabpanel" aria-labelledby="tab_05_tab">
                                    <?php $existACC = false;
                                    $openExist = false;
                                    $closedExist = false; ?>
                                    <div class="row moblie-tab">
                                        <div class="col-md-12 loans-rc">
                                            @foreach($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['RetailAccountDetails'] as $key => $value)
                                            @if(
                                            $value['AccountType'] != 'Personal Loan'
                                            && $value['AccountType'] != 'Property Loan'
                                            && $value['AccountType'] != 'Housing Loan'
                                            && $value['AccountType'] != 'Auto Loan'
                                            && $value['AccountType'] != 'Auto Lease'
                                            && $value['AccountType'] != 'Business Loan'
                                            && $value['AccountType'] != 'Business Loan-Priority Sector-Small Business'
                                            && $value['AccountType'] != 'Business Loan - Priority Sector- Agriculture'
                                            && $value['AccountType'] != 'Business Loan - Priority Sector- Others'
                                            && $value['AccountType'] != 'Business Non-Funded Credit Facility'
                                            && $value['AccountType'] != 'Business Non-Funded Credit Facility - Priority Sector - Small Business'
                                            && $value['AccountType'] != 'Business Non-Funded Credit Facility - Priority Sector - Agriculture'
                                            && $value['AccountType'] != 'Business Non-Funded Credit Facility - Priority Sector - Other'
                                            && $value['AccountType'] != 'Business Loan Against Bank Deposits'
                                            && $value['AccountType'] != 'Credit Card'
                                            && $value['AccountType'] != 'Fleet Card'
                                            && $value['AccountType'] != 'Secured Credit Card'
                                            && $value['AccountType'] != 'Corporate Credit Card'
                                            )

                                            @if(!$firstTabActive)
                                            <script>
                                                $('#tab_05_tab').addClass('active_tab_new');
                                            </script>
                                            @endif

                                            <?php
                                            $existACC = true;
                                            $firstTabActive = true;
                                            $class = $value['Open'] == 'Yes' ? 'open_account' : 'closed_account display_none';
                                            ?>


                                            <?php
                                            if ($value['Open'] == 'Yes') {
                                                $openExist = true;
                                            } else {
                                                $closedExist = true;
                                            }
											$ownershipType = array_key_exists('OwnershipType',$value) ? $value['OwnershipType'] : 'NA';
                                            ?>

                                            <div class="rc_block {{$class}}">
                                                <div class="row moblie-tab">
                                                    <div class="col-md-6 loans-rc">
                                                        <div class="item">
                                                            <p><span>Lender name </span>**** **** {{substr($value['Institution'], 140)}}</p>
                                                            <p><span>A/C number </span>**** {{substr($value['AccountNumber'], -4)}}</p>
                                                            <p><span>Ownership type</span>{{$ownershipType}}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 loans-rc">
                                                        <div class="item">
                                                            <p><span>Account type</span>{{$value['AccountType']}}</p>
                                                            <p><span>Account status </span>{{$value['AccountStatus']}}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 loans-rc">
                                                        <div class="item">
                                                            <p><span>Date opened</span>{{isset($value['DateOpened']) ? General::getFormatedDate($value['DateOpened']) : '-'}}</p>
                                                            @if($value['Open'] != 'Yes')
                                                            <p><span>Date closed </span>{{isset($value['DateClosed']) ? General::getFormatedDate($value['DateClosed']) : '-'}}</p>
                                                            @endif
                                                            <p><span>Last updated </span>{{isset($value['DateReported']) ? General::getFormatedDate($value['DateReported']) : '-'}}</p>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6 loans-rc">
                                                        <div class="item">
                                                            <p><span>Loan amount </span>₹ {{isset($value['SanctionAmount']) ? number_format($value['SanctionAmount'],2) : '-'}}</p>
                                                            <p><span>Current balance</span>₹ {{isset($value['Balance']) ? number_format($value['Balance'],2) : '-'}}</p>
                                                            @if(isset($value['PastDueAmount']) && $value['PastDueAmount'] > 0)
                                                            <p><span>Overdue amount</span>{{isset($value['PastDueAmount']) ? number_format($value['PastDueAmount'],2) : '-'}}</p>
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endif
                                            @endforeach

                                            @if(!$closedExist)
                                            <div class="rc_block closed_account_no_data display_none">
                                                There are no open loans for this customer
                                            </div>
                                            @endif

                                            @if(!$openExist)
                                            <div class="rc_block open_account_no_data">
                                                There are no open loans for this customer
                                            </div>
                                            @endif

                                            @if(!$existACC)
                                            No Account Exists-
                                            <script>
                                                $('#tab_05_tab').css('display', 'none');
                                            </script>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                <!-- ------------------Tab 07------------------ -->
                                <!-- <div class="tab-pane fade" id="tab_07" role="tabpanel" aria-labelledby="tab_07_tab">
             <?php $existACC = false;
                $openExist = false;
                $closedExist = false; ?>
              <div class="row moblie-tab">
                <div class="col-md-12 loans-rc">
                  @foreach($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['RetailAccountDetails'] as $key => $value)
                  @if(
                  $value['AccountType'] == 'Credit Card'
                  || $value['AccountType'] == 'Fleet Card'
                  || $value['AccountType'] == 'Secured Credit Card'
                  || $value['AccountType'] == 'Corporate Credit Card'
                  )
                  <?php
                    $existACC = true;
                    $class = $value['Open'] == 'Yes' ? 'open_account' : 'closed_account display_none';
                    ?>


                  <?php
                    if ($value['Open'] == 'Yes') {
                        $openExist = true;
                    } else {
                        $closedExist = true;
                    }
					$ownershipType = array_key_exists('OwnershipType',$value) ? $value['OwnershipType'] : 'NA';
                    ?>

                  <div class="rc_block {{$class}}">
                    <h4 class="sub">{{General::getMaskedCharacterAndNumber($value['Institution'])}}</h4>
                    <div class="row moblie-tab">
                      <div class="col-md-6 loans-rc">
                        <div class="item">
                          <p><span>A/C number </span>**** **** {{substr($value['AccountNumber'], -4)}}</p>
                          <p><span>Ownership type</span>{{$ownershipType}}</p>
                        </div>
                      </div>
                      <div class="col-md-6 loans-rc">
                        <div class="item">
                          <p><span>Account type</span>{{$value['AccountType']}}</p>
                          <p><span>Current balance</span>{{isset($value['Balance']) ? number_format($value['Balance'],2) : '-'}}</p>
                          @if(!empty($value['CreditLimit']))
                          <p><span>Credit limit </span>{{isset($value['CreditLimit']) ? 'Rs '.number_format($value['CreditLimit'], 2) : '-'}}</p>
                          @endif
                          @if(!empty($value['HighCredit']))
                          <p><span>High credit </span>{{isset($value['HighCredit']) ? 'Rs '.number_format($value['HighCredit'], 2) : '-'}}</p>
                          @endif
                        </div>
                      </div>
                      <div class="col-md-6 loans-rc">
                        <div class="item">
                          <p><span>Overdue amount</span>{{isset($value['PastDueAmount']) ? number_format($value['PastDueAmount'],2) : '-'}}</p>
                        </div>
                      </div>
                      <div class="col-md-6 loans-rc">
                        <div class="item">
                          <p><span>Date opened</span>{{isset($value['DateOpened']) ? General::getFormatedDate($value['DateOpened']) : '-'}}</p>
                          <p><span>Date closed </span>{{isset($value['DateClosed']) ? General::getFormatedDate($value['DateClosed']) : '-'}}</p>
                          <p><span>Last updated </span>{{isset($value['DateReported']) ? General::getFormatedDate($value['DateReported']) : '-'}}</p>
                        </div>
                      </div>
                    </div>
                  </div>
                  @endif
                  @endforeach

                  @if(!$existACC)
                  No Account Exists!
                  <script>
                    $('#tab_07_tab').css('display', 'none');
                  </script>
                  @endif
                </div>
              </div>
            </div> -->
                                <!-- ------------------Tab 07 end------------------ -->
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clear"></div>
            </div>
            <!-- ------------------Credit Cards Section------------------ -->
            <div class="rc_screens webscreens  rc_credit_cards" id="rc_04b">
                <h2 class="rc_title_sub toggle_left">Credit Cards</span></h2>
                <div class="toggle_right"> <label class="rc_switch">
                        <input id="loan_switch" onchange="loanSwitch(this.checked)" type="checkbox" checked>
                        <span class="slider round">
                            <i class="acc-text  open-ac">Open</i>
                            <i class="acc-text close-ac">Closed</i>
                        </span>
                    </label>
                </div>
                <div class="clear"></div>
                <div class="row01 ">










                    @foreach ($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['RetailAccountDetails'] as $key => $value)
                    @if($value['AccountType'] == 'Credit Card')
                    <?php $class = $value['Open'] == 'Yes' ? 'open_account' : 'closed_account display_none';
						  $ownershipType = array_key_exists('OwnershipType',$value) ? $value['OwnershipType'] : 'NA';
				?>







                    <div class="rc_block {{$class}}">

                        <div class="row moblie-tab">
                            <div class="col-md-6 loans-rc">
                                <div class="item">
                                    <p><span>Lender name </span>**** **** {{substr($value['Institution'], 140)}}</p>
                                    <p><span>A/C number </span>**** **** {{substr($value['AccountNumber'], -4)}}</p>
                                    <p><span>Ownership type</span>{{$ownershipType}}</p>
                                </div>
                            </div>
                            <div class="col-md-6 loans-rc">
                                <div class="item">
                                    <p><span>Account type</span>{{$value['AccountType']}}</p>
                                    <p><span>Current balance</span>{{isset($value['Balance']) ? '₹ '.number_format($value['Balance']) : '-'}}</p>

                                    <p><span>Credit limit </span>{{isset($value['CreditLimit']) ? '₹ '.number_format($value['CreditLimit']) : '-'}}</p>


                                    <p><span>High credit </span>{{isset($value['HighCredit']) ? '₹ '.number_format($value['HighCredit']) : '-'}}</p>

                                </div>
                            </div>
                            <div class="col-md-6 loans-rc">
                                <div class="item">
                                    <p><span>Account status</span>{{$value['AccountStatus']}} </p>
                                    <p><span>Amount overdue</span>{{isset($value['PastDueAmount']) ? '₹ '. number_format($value['PastDueAmount']) : '-'}}</p>
                                </div>
                            </div>
                            <div class="col-md-6 loans-rc">
                                <div class="item">
                                    <p><span>Date opened</span>{{isset($value['DateOpened']) ? General::getFormatedDate($value['DateOpened']) : '-'}}</p>
                                    @if($value['Open'] != 'Yes')
                                    <p><span>Date closed </span>{{isset($value['DateClosed']) ? General::getFormatedDate($value['DateClosed']) : '-'}}</p>
                                    @endif
                                    <p><span>Last updated </span>{{isset($value['DateReported']) ? General::getFormatedDate($value['DateReported']) : '-'}}</p>
                                </div>
                            </div>
                        </div>
                    </div>

















                    @endif
                    @endforeach
                </div>
                <div class="clear"></div>
            </div>


            <!-- ------------------Credit limit Section------------------ -->
            <div class="rc_screens webscreens  rc_age rc_limits" id="rc_05">
                <h2 class="rc_title_sub toggle_left">Credit limit </span></h2>
                <div class="clear"></div>
                <div class="row credit_cards limit_credit_cards">

                    <?php $credit_limit_avail = false; ?>

                    @if(isset($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['RetailAccountDetails']))
                    @foreach ($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['RetailAccountDetails'] as $key => $value)
                    @if($value['AccountType'] == 'Credit Card')
                    <?php $credit_limit_avail = true;
                    $class = $value['Open'] == 'Yes' ? 'open_account' : 'closed_account display_none';    ?>
                    <div class="rc_section col-md-4 {{$class}}">
                        <div class="rc_block pa-rc">
                            <div class="credit-top">
                                <p><span>Lender name: </span><i class="lendername">**** **** {{substr($value['Institution'], 140)}}</i></p>
                                <p><span>A/C number: </span>**** {{substr($value['AccountNumber'], -4)}}</p>
                            </div>
                            <div class="credit-center">
                                <?php
                                $availableLimitTemp = isset($value['CreditLimit']) ? $value['CreditLimit'] : (isset($value['HighCredit']) ? $value['HighCredit'] : 1);
                                if ($availableLimitTemp > 0) {
                                    $availableLimitTemp = (($availableLimitTemp - $value['Balance']) * 100) / $availableLimitTemp;
                                }

                                $availableLimitTemp = ($availableLimitTemp < 0) ? 0.00 : $availableLimitTemp;

                                ?>
                                <span>Available credit {{round(number_format($availableLimitTemp, 2))}}%</span>
                            </div>
                            <div class="credit-bottom">
                                <div class="cbottom_left">
                                    <p><span>Credit limit: </span>{{isset($value['CreditLimit']) ? '₹ '.number_format($value['CreditLimit']) : '-'}}</p>
                                    <p><span>High credit: </span>{{isset($value['HighCredit']) ? '₹ '.number_format($value['HighCredit']) : '-'}}</p>
                                </div>
                                <div class="cbottom_right">
                                    <p><span>Current balance: </span>
                                        {{isset($value['Balance']) ? '₹ '.number_format($value['Balance']) : '-'}}

                                    </p>
                                    <p><span>Last updated: </span>{{General::getFormatedDate($value['DateReported'])}}</p>
                                </div>
                                <div class="clear"></div>
                            </div>
                            <h5 style="display: none;">limit {{isset($value['CreditLimit']) ? number_format($value['CreditLimit']) : (isset($value['HighCredit']) ? number_format($value['HighCredit']) : '') }}
                            </h5>
                        </div>
                    </div>
                    @endif
                    @endforeach

                    @if(!$credit_limit_avail)
                    <div>
                        <p style="color:black">No Loan or Credit Card applications reported to Equifax</p>
                    </div>
                    @endif

                    @else
                    <div>
                        <p style="color:red">No Loan or Credit Card applications reported to Equifax</p>
                    </div>
                    @endif


                </div>
                <div class="clear"></div>
            </div>





            <!-- ------------------Enquiries Section------------------ -->
            <div class="rc_screens webscreens  rc_enquires" id="rc_06">
                <h2 class="rc_title_sub toggle_left">Enquires</span></h2>
                <div class="clear"></div>
                <div class="row enquires">
                    @if(isset($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['Enquiries']))
                    <select class="short-enq" id="enquiry_sorting">
                        <option value="desc">Newest to Oldest</option>
                        <option value="asc">Oldest to Newest</option>
                    </select>
                    @endif

                    <div class="clear"></div>
                    <div id="enquiry_main_div" class="enquiry_main_div_1">



                        @if(isset($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['Enquiries']))
                        @foreach ($response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['Enquiries'] as $key => $value)
                        <div data-sort="{{$key}}" class="rc_section enquiry_main_div col-md-12 ediv_{{$key}}">
                            <h4 class="sub-head">Enquiry #{{ $value['seq']+1 }}
                            </h4>
                            <div class="rc_block enq-rc">
                                <div class="item">
                                    <div class="left-block">
                                        <p><span>Lender name:</span>**** **** {{substr($value['Institution'], 140)}}</p>
                                        <p><span>Date:</span>{{ General::getFormatedDate($value['Date']) }}</p>

                                    </div>
                                    <div class="right-block">
                                        <p><span>Purpose:</span>{{ General::getInquiryPurpose($value['RequestPurpose']) }}</p>
                                        <p><span>Amount:</span> {{ isset($value['Amount']) ? '₹ '.round($value['Amount']) : '-' }}</p>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
                        @endforeach

                        @else
                        <div>
                            <p>No Loan or Credit Card applications reported to Equifax</p>
                        </div>
                        @endif
                    </div>
                </div>
                <div class="clear"></div>
            </div>

            <!-- ------------------Enquiries end------------------ -->
            @endif
        </div>






        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>

        @if(isset($response) && !empty($response))
        <script>
            var score = "{{ $response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['ScoreDetails'][0]['Value'] }}";
            var scoreBackgroundvalue = '#ff6c6c !important';

            if (score > 750) {
                scoreBackgroundvalue = '#82e360 !important';
            } else if (score > 700 && score <= 750) {
                scoreBackgroundvalue = 'progress_bar_yellow';
            } else if (score > 650 && score <= 700) {
                scoreBackgroundvalue = 'progress_bar_orange';
            } else if (score <= 650) {
                scoreBackgroundvalue = 'progress_bar_red';
            }
            console.log(scoreBackgroundvalue);
            // $('#progress-bar-active-score').css('background', scoreBackgroundvalue);
            $('#progress-bar-active-score').addClass(scoreBackgroundvalue);
        </script>




        <script>
            var score = "{{ $response['CCRResponse']['CIRReportDataLst'][0]['CIRReportData']['ScoreDetails'][0]['Value'] }}";
            var scoreBackgroundvalue1 = '#ff6c6c !important';

            if (score > 750) {
                scoreBackgroundvalue1 = 'progress_bar_green';
            } else if (score > 700 && score <= 750) {
                scoreBackgroundvalue1 = 'progress_bar_yellow';
            } else if (score > 650 && score <= 700) {
                scoreBackgroundvalue1 = 'progress_bar_orange';
            } else if (score <= 650) {
                scoreBackgroundvalue1 = 'progress_bar_red';
            }
            console.log(scoreBackgroundvalue1);
            $('.title_imporve').addClass(scoreBackgroundvalue1);
        </script>






        @endif

        <script>
            // var $divs = $(".history_percentage");
            // var numericallyOrderedDivs = $divs.sort(function (a, b) {
            //   console.log($(a).attr("id"));
            //         return $(a).attr("id") < $(b).attr("id");
            // });
            // console.log(numericallyOrderedDivs);
            // $(".p_h_div").html(numericallyOrderedDivs);

            var result = $('.history_percentage').sort(function(a, b) {
                var contentA = parseInt($(a).attr('id'));
                var contentB = parseInt($(b).attr('id'));
                return (contentA < contentB) ? -1 : (contentA > contentB) ? 1 : 0;
            });
            $('.p_h_div').html(result);

            function loanSwitch(val) {
                if (val) {
                    var open = false;
                    jQuery('.nav-link').each(function() {
                        if($('#'+$(this).attr('aria-controls')).find('.open_account').length > 0){
                            open = true;
                            $('#'+$(this).attr('aria-controls')+'_tab').css('display', 'block');
                        }
                        else{
                            $('#'+$(this).attr('aria-controls')+'_tab').css('display', 'none');
                        }
                    });

                    if(!open){
                        $('.closed_account_div').addClass('display_none');
                        $('.open_account_div').removeClass('display_none');
                       
                    }
                    else{
                        $('.closed_account_div').addClass('display_none');
                        $('.open_account_div').addClass('display_none');
                         
                    }

                    $('.closed_account').addClass('display_none');
                    $('.open_account').removeClass('display_none');
                  
                } else {
                    var closed = false;
                    jQuery('.nav-link').each(function() {
                        if($('#'+$(this).attr('aria-controls')).find('.closed_account').length > 0){
                            closed = true;
                            $('#'+$(this).attr('aria-controls')+'_tab').css('display', 'block');
                        }
                        else{
                            $('#'+$(this).attr('aria-controls')+'_tab').css('display', 'none');
                        }
                    });

                    if(!closed){
                        $('.closed_account_div').removeClass('display_none');
                        $('.open_account_div').addClass('display_none');
                    }
                    else{
                        $('.closed_account_div').addClass('display_none');
                        $('.open_account_div').addClass('display_none');
                    }

                    $('.open_account').addClass('display_none');
                    $('.closed_account').removeClass('display_none');
                }
            }

            $('#enquiry_sorting').change(function() {

                var result = $('.enquiry_main_div').sort(function(a, b) {

                    var contentA = parseInt($(a).data('sort'));
                    var contentB = parseInt($(b).data('sort'));

                    // var mult = 1;
                    // var temp;
                    // if ($(this).val() == 'desc') {
                    //   mult = -1
                    // }

                    // if (contentA > contentB) {
                    //   temp = 1 * mult;
                    // }
                    // else if (contentA < contentB) {
                    //   temp = -1 * mult;
                    // } else {
                    //   temp = 0;
                    // }

                    var temp = -1;
                    // var temp = (contentA < contentB) ? -1 : (contentA > contentB) ? 0 : 1;
                    // console.log(contentA);
                    // console.log(contentB);
                    // console.log(temp);
                    return temp;
                });
                $('#enquiry_main_div').html(result);
            });
        </script>

        <script type="text/javascript">
            $(".mobile_head").click(function() {

                $(this).parents(".tab-pane").toggleClass("rc_open");
            });



            $(".rc_open").click(function() {
                alert("test");
                $(this).parents(".tab-sspane").toggleClass("rc_opsssen");
            });
        </script>


        <script type="text/javascript">
            $("body").on("click", ".downloadAsPdf", function(e) {
                var alertType = "info";
                var alertMessage = "Your download will start soon";
                var alerter = toastr[alertType];
                alerter(alertMessage);
                /*var htmlToDownload = $(this).parents('.report-gen').find('.table-responsive');
                html2canvas(htmlToDownload, {
                    onrendered: function (canvas) {
                     //   alert(htmlToDownload.outerWidth());
                        var data = canvas.toDataURL();
                        var docDefinition = {
                            content: [{
                                image: data,
                                width:500,
                                //pageSize: 'A1',
                            }]
                        };
                        pdfMake.createPdf(docDefinition).download("Recordent-report.pdf");
                    }
                });*/
            });
        </script>

        <script type="text/javascript">
            $(document).ready(function() {
                $('.view-btn').click(function() {
                    $(this).parents(".rc_section").siblings().removeClass('rc_section_open');
                    $(this).parents(".rc_section").toggleClass('rc_section_open');
                });
            });








            $(document).ready(function() {

                setTimeout(function() {
                    // alert();
                    console.log($('.nav-item'));
                    // $('.nav .nav-item:first-child').click();
                    // $('.nav .nav-item:first-child').trigger('click');
                    $('.active_tab_new').first().trigger('click');
                    // $('.nav-link').filter(function(){
                    //     $bg = $(this).css('background-color');
                    //     if ($bg === 'rgba(255, 171, 0, 0.15)') {
                    //         return true;
                    //     }
                    // }).eq(0).click();

                }, 3000);

                $('.nav-link').click(function() {
                    // if($('#'+$(this).attr('aria-controls')).find('.open_account').length == 0){
                    //   console.log('11');
                    //   if($('#loan_switch_check').prop('checked')){
                    //     console.log('1');
                    //     $('#loan_switch_check').first().trigger('click');
                    //   }
                    // }
                    // else{
                    //   console.log('22');
                    //   if(!$('#loan_switch_check').prop('checked')){
                    //     console.log('2');
                    //     $('#loan_switch_check').first().trigger('click');
                    //   }
                    // }
                });

                // $('.nav-link').click(function() {
                //   console.log($(this).attr('aria-controls'));
                //   if($('#'+$(this).attr('aria-controls')).find('.open_account').length == 0){
                //     //$('#loan_switch').trigger('click');
                //     console.log('inin');
                //     $('#loan_switch').prop('checked', false); // Unchecks it
                //     loanSwitch(false);
                //     $('.rc_switch .open-ac1').css('display', 'none');
                //     $('.rc_switch .close-ac1').css('display', 'block');

                //     $('.rc_switch .slider1').css('background-color', '#ccc');
                //   }
                //   else{
                //     $('.rc_switch .open-ac1').css('display', 'block');
                //     $('.rc_switch .close-ac1').css('display', 'none');
                //     $('#loan_switch').prop('checked', true); // Unchecks it
                //     loanSwitch(true);
                //     $('.rc_switch .slider1').css('background-color', '#202f7d');
                //   }
                //
                
                      //   $('.equifex_recordentscreen').css('display', 'block');
                      //    $('.recordentscreen').css('display', 'none');
                      //    $('.back_to_dasborad_members').removeAttr( "style" );
                      //    $('.rc_title_sub.recordent-title').removeAttr( "style" );
                
                
                
                
                
                
                    //   });

                $('.togle_buttons a').click(function() {
                    $(this).siblings().removeClass('active');
                    $(this).addClass('active');
                });

                $('a.equifax-active').click(function() {
                    $(this).parents('.rc_dashbord').removeClass('recordent_screen')
                    $(this).parents('.main_rc_section').removeClass('recordent_active')
                    $('.equifex_recordentscreen').css('display', 'block');
                    $('.recordentscreen').css('display', 'none');

                    $('.back_to_dasborad_members').addClass('displayNone_section');
                    $('.rc_title_sub.recordent-title').addClass('displayNone_section');
                    $('.recrodent-section.recordent_members').addClass('displayNone_section');
                    $('#subclick_02').addClass('displayNone_section');

                    $('body').removeClass('full-block-width');
                    $('#subclick_02').addClass('displayNone_section');
                    $('#subclick_02').removeClass('active'); 

                    

                });

                $('a.recordent-active').click(function() {
                    $(this).parents('.rc_dashbord').addClass('recordent_screen')
                    $(this).parents('.main_rc_section').addClass('recordent_active')
                    $('.recordentscreen').css('display', 'block');
                    $('.equifex_recordentscreen').css('display', 'none');
                    $('.recordent_main').removeClass('displayNone_section');
                    $('#recordent_member_profile_div').addClass('displayNone_section');
                    $('.recordent_members_div').addClass('displayNone_section');
                    $('#subclick_02').addClass('displayNone_section');
                    $('.reportSum').removeClass('displayNone_section');
                    $('body').removeClass('full-block-width');
                    $('.recrodent-section.recordent_members').addClass('displayNone_section');
                    $('#subclick_02').addClass('displayNone_section');
                    $('#subclick_02').removeClass('active'); 
                     
                    

                    
                    

                    


                });






                


                $('.rc_link_01').click(function() {
                    $('.closed_account').addClass('display_none');
                    $('.open_account').removeClass('display_none');
                    $('#rc_02').removeClass('section_open')
                    $('#rc_03').removeClass('section_open')
                    $('#rc_04').removeClass('section_open')
                    $('#rc_04a').removeClass('section_open')
                    $('#rc_04b').removeClass('section_open')
                    $('#rc_05').removeClass('section_open')
                    $('#rc_06').removeClass('section_open')
                    $('#rc_01').toggleClass('section_open')
                    $('.main_rc_section').addClass('mobileScreen')
                    $('.active_none').addClass('desk_none')


                });

                $('.rc_link_02').click(function() {
                    $('.closed_account').addClass('display_none');
                    $('.open_account').removeClass('display_none');
                    $('#rc_01').removeClass('section_open')
                    $('#rc_03').removeClass('section_open')
                    $('#rc_04').removeClass('section_open')
                    $('#rc_05').removeClass('section_open')
                    $('#rc_06').removeClass('section_open')
                    $('#rc_04a').removeClass('section_open')
                    $('#rc_04b').removeClass('section_open')
                    $('#rc_02').toggleClass('section_open')
                    $('.main_rc_section').addClass('mobileScreen')
                    $('.active_none').addClass('desk_none')
                });

                $('.rc_link_03').click(function() {
                    $('.closed_account').addClass('display_none');
                    $('.open_account').removeClass('display_none');
                    $('#rc_02').removeClass('section_open')
                    $('#rc_01').removeClass('section_open')
                    $('#rc_04').removeClass('section_open')
                    $('#rc_05').removeClass('section_open')
                    $('#rc_06').removeClass('section_open')
                    $('#rc_04a').removeClass('section_open')
                    $('#rc_04b').removeClass('section_open')
                    $('#rc_03').toggleClass('section_open')
                    $('.main_rc_section').addClass('mobileScreen')
                    $('.active_none').addClass('desk_none')
                });


                $('.backtodasborad1').click(function() {
                    // $('.closed_account').addClass('display_none');
                    // $('.open_account').removeClass('display_none');
                    // $('.active_tab_new').first().trigger('click');
                    $('#rc_02').removeClass('section_open')
                    $('#rc_03').removeClass('section_open')
                    $('#rc_01').removeClass('section_open')
                    $('#rc_05').removeClass('section_open')
                    $('#rc_06').removeClass('section_open')
                    $('#rc_04a').removeClass('section_open')
                    $('#rc_04b').removeClass('section_open')
                    $('body').removeClass('acccounts_dashboard')
                    $('#rc_04').addClass('section_open')
                    $('.main_rc_section').addClass('mobileScreen')
                    $('.active_none').addClass('desk_none')
                });



                $('.recordent_click_02').click(function() {
                    $('h2.rc_title').addClass('displayNone_section')
                    $('.main_section ').addClass('displayNone_section')
                });
                $('.recordent_click_03').click(function() {
                    $('h2.rc_title').addClass('displayNone_section')
                    $('.main_section ').addClass('displayNone_section')
                });

                $('.recordent_click_04').click(function() {
                    $('h2.rc_title').addClass('displayNone_section')
                    $('.main_section ').addClass('displayNone_section')
                });



                $('.rc_link_04').click(function() {
                    $('#rc_02').removeClass('section_open')
                    $('#rc_03').removeClass('section_open')
                    $('#rc_01').removeClass('section_open')
                    $('#rc_05').removeClass('section_open')
                    $('#rc_06').removeClass('section_open')
                    $('#rc_04a').removeClass('section_open')
                    $('#rc_04b').removeClass('section_open')
                    $('#rc_04').toggleClass('section_open')
                    $('.main_rc_section').addClass('mobileScreen')
                    $('.active_none').addClass('desk_none')
                });

                $('.rc_link_05').click(function() {
                    $('.closed_account').addClass('display_none');
                    $('.open_account').removeClass('display_none');
                    $('#rc_02').removeClass('section_open')
                    $('#rc_03').removeClass('section_open')
                    $('#rc_04').removeClass('section_open')
                    $('#rc_01').removeClass('section_open')
                    $('#rc_06').removeClass('section_open')
                    $('#rc_04a').removeClass('section_open')
                    $('#rc_04b').removeClass('section_open')
                    $('#rc_05').toggleClass('section_open')
                    $('.main_rc_section').addClass('mobileScreen')
                    $('.active_none').addClass('desk_none')
                });

                $('.rc_link_06').click(function() {
                    $('.closed_account').addClass('display_none');
                    $('.open_account').removeClass('display_none');
                    $('#rc_02').removeClass('section_open')
                    $('#rc_03').removeClass('section_open')
                    $('#rc_04').removeClass('section_open')
                    $('#rc_05').removeClass('section_open')
                    $('#rc_01').removeClass('section_open')
                    $('#rc_04a').removeClass('section_open')
                    $('#rc_04b').removeClass('section_open')
                    $('#rc_06').toggleClass('section_open')
                    $('.main_rc_section').addClass('mobileScreen')
                    $('.active_none').addClass('desk_none')
                });


                $('.recordent_click_02').click(function() {
                    $('#recordent_02').addClass('recordent_active')
                    $('.top_rc_section').addClass('displayNone_section')
                    $('.download_btn.active_none').addClass('displayNone_section')
                    $('.recordentscreen').addClass('recordentscreen_active')
                });


                $('.recordent_click_03').click(function() {
                    $('#recordent_03').addClass('recordent_active')
                    $('.top_rc_section').addClass('displayNone_section')
                    $('.download_btn.active_none').addClass('displayNone_section')
                    $('.recordentscreen').addClass('recordentscreen_active')
                });



                $('.recordent_click_04').click(function() {
                    $('#recordent_04').addClass('recordent_active')
                    $('.top_rc_section').addClass('displayNone_section')
                    $('.download_btn.active_none').addClass('displayNone_section')
                    $('.recordentscreen').addClass('recordentscreen_active')
                });

                $('#recordent_03 .subclick').click(function() {
                    $('#subclick_03').addClass('subclick_active')
                    $('#recordent_03').addClass('displayNone_section')
                    $('.top_rc_section').addClass('displayNone_section')
                    $('.recordentscreen').removeClass('recordentscreen_active')
                    $('.recordentscreen').addClass('recordentscreen_active1')
                });


                $('#recordent_04 .subclick').click(function() {
                    $('#subclick_04').addClass('subclick_active')
                    $('#recordent_04').addClass('displayNone_section')
                    $('.top_rc_section').addClass('displayNone_section')
                    $('.recordentscreen').removeClass('recordentscreen_active')
                    $('.recordentscreen').addClass('recordentscreen_active1')
                });

                $('#subclick_02 .backtodasborad3').click(function() {
                    $('#recordent_02').removeClass('displayNone_section')
                    $('#subclick_02').removeClass('subclick_active')
                    $('.recordentscreen').addClass('recordentscreen_active')
                    $('.recordentscreen1').removeClass('recordentscreen_active')

                });


                $('#subclick_03 .backtodasborad3').click(function() {
                    $('#recordent_03').removeClass('displayNone_section')
                    $('#subclick_03').removeClass('subclick_active')
                    $('.recordentscreen').addClass('recordentscreen_active')
                    $('.recordentscreen1').removeClass('recordentscreen_active')

                });

                $('#subclick_04 .backtodasborad3').click(function() {
                    $('#recordent_04').removeClass('displayNone_section')
                    $('#subclick_04').removeClass('subclick_active')
                    $('.recordentscreen').addClass('recordentscreen_active')
                    $('.recordentscreen1').removeClass('recordentscreen_active')

                });




/*$('.back_to_members_invoice').click(function() {
$('.closed_account').addClass('display_none');
$('.open_account').removeClass('display_none');

});*/



//$('.subclick').click(function() {
//$('body').addClass('unchecked_box');
//});






//$('.rc_switch .slider.round').click(function() {
//$('body').removeClass('unchecked_box');
//});
                


//$('input:checkbox').change(function(){
  //  if($(this).is(":checked")) {
  //      $('body').addClass("unchecked_box");
  //  } else {
  //      $('body').removeClass("unchecked_box");
  //  }
//});





                $('.rc_link_04a').click(function() {
                    $('.closed_account').addClass('display_none');
                    $('.open_account').removeClass('display_none');

                    if (!$('#loan_switch_check').prop('checked')) {
                        console.log($('#loan_switch_check').prop('checked'));
                        $('#loan_switch_check').first().trigger('click');
                    }
                    else{
                        loanSwitch(true);
                    }

                    $('#rc_01').removeClass('section_open')
                    $('#rc_02').removeClass('section_open')
                    $('#rc_03').removeClass('section_open')
                    $('#rc_04').removeClass('section_open')
                    $('#rc_05').removeClass('section_open')
                    $('#rc_06').removeClass('section_open')
                    $('#rc_04b').removeClass('section_open')
                    $('#rc_04a').toggleClass('section_open')
                    $('.main_rc_section').addClass('mobileScreen')
                    $('.active_none').addClass('desk_none')
                    $('body').addClass('acccounts_dashboard')
                    $('.active_tab_new').first().trigger('click');
                });




                $('.rc_link_04b').click(function() {
                    // alert($('#loan_switch_check').prop('checked')); 
                    $('.closed_account').addClass('display_none');
                    $('.open_account').removeClass('display_none');

                    if (!$('#loan_switch_check').prop('checked')) {
                        console.log($('#loan_switch_check').prop('checked'));
                        $('#loan_switch_check').first().trigger('click');
                    }

                    $('#rc_01').removeClass('section_open')
                    $('#rc_02').removeClass('section_open')
                    $('#rc_03').removeClass('section_open')
                    $('#rc_04').removeClass('section_open')
                    $('#rc_05').removeClass('section_open')
                    $('#rc_06').removeClass('section_open')
                    $('#rc_04a').removeClass('section_open')
                    $('#rc_04b').toggleClass('section_open')
                    $('.main_rc_section').addClass('mobileScreen')
                    $('.active_none').addClass('desk_none')
                    $('body').addClass('acccounts_dashboard')
                });

                $('.toggle_right .slider').click(function() {
                    $('.slider').parents(".toggle_right").addClass('toggle_none')
                });







                $('#recordent_member_profile').click(function() {
                    $('.recordent_main').toggleClass('displayNone_section');
                    $('#recordent_member_profile_div').toggleClass('displayNone_section');
                    $('.reportSum').addClass('displayNone_section');   

                     $('.recordent_screen').addClass('mobile_acitive');                 

                });

                $('#recordent_members').click(function() {
                    $('.recordent_main').toggleClass('displayNone_section');
                    $('.recordent_members_div').toggleClass('displayNone_section');
                    $('.reportSum').addClass('displayNone_section');  

                    $('.recordent_screen').addClass('mobile_acitive'); 
                    $('#back_to_dasborad_members_main_div').removeClass('displayNone_section');
                    $('.back_to_dasborad_members').removeClass('displayNone_section');
                    $('.rc_title_sub.recordent-title').removeClass('displayNone_section');
                    $('.recrodent-section.recordent_members').removeClass('displayNone_section');
                    $('#subclick_02').addClass('displayNone_section');

                    $('.m01').addClass('active');
                    $('.m02').removeClass('active');
                    $('.m03').removeClass('active');

                    
                    
                    

                   

                });

                $('#recordent_invoices').click(function() {
                    $('.recordent_main').toggleClass('displayNone_section');
                    $('.recordent_members_div').toggleClass('displayNone_section');
                    $('.reportSum').addClass('displayNone_section');  

                    $('.recordent_screen').addClass('mobile_acitive'); 
                    $('#back_to_dasborad_members_main_div').removeClass('displayNone_section');
                    $('.back_to_dasborad_members').removeClass('displayNone_section');
                    $('.rc_title_sub.recordent-title').removeClass('displayNone_section');
                    $('.recrodent-section.recordent_members').removeClass('displayNone_section');
                    $('#subclick_02').addClass('displayNone_section');
                    $('.m01').removeClass('active');
                    $('.m02').addClass('active');
                    $('.m03').removeClass('active');

                });

                $('#recordent_dues').click(function() {
                    $('.recordent_main').toggleClass('displayNone_section');
                    $('.recordent_members_div').toggleClass('displayNone_section');
                    $('.reportSum').addClass('displayNone_section');  

                    $('.recordent_screen').addClass('mobile_acitive'); 
                    $('#back_to_dasborad_members_main_div').removeClass('displayNone_section');
                    $('.back_to_dasborad_members').removeClass('displayNone_section');
                    $('.rc_title_sub.recordent-title').removeClass('displayNone_section');
                    $('.recrodent-section.recordent_members').removeClass('displayNone_section');
                    $('#subclick_02').addClass('displayNone_section');
                    $('.m01').removeClass('active');
                    $('.m02').removeClass('active');
                    $('.m03').addClass('active');

                });

                $('.back_to_dasborad_members').click(function() {
                    $('#back_to_dasborad_members_main_div').toggleClass('displayNone_section');

                    $('.recordent_main').toggleClass('displayNone_section');
                    $('.recordent_members_div').toggleClass('displayNone_section');
                    $('.reportSum').removeClass('displayNone_section');  


                    $('.recordent_screen').removeClass('mobile_acitive'); 
                });

                $('.back_to_members_invoice').click(function() {
                    $('.recordent_members_div').toggleClass('displayNone_section');
                    // $('#subclick_02').toggleClass('displayNone_section');
                    $('.'+$(this).attr('data')).toggleClass('displayNone_section');
                    $('#back_to_dasborad_members_main_div').toggleClass('displayNone_section');

                    $('body').removeClass('full-block-width');
                    $('#subclick_02').removeClass('active');  




                });

                $('.back_to_dasborad_profile').click(function() {
                    $('.recordent_main').toggleClass('displayNone_section');
                    $('#recordent_member_profile_div').toggleClass('displayNone_section');
                    $('.reportSum').removeClass('displayNone_section');  

                    $('.recordent_screen').removeClass('mobile_acitive'); 
                });

                $('#recordent_02 .subclick').click(function() {
                    $('.recordent_members_div').toggleClass('displayNone_section');
                    $('#back_to_dasborad_members_main_div').toggleClass('displayNone_section');
                    // $('#subclick_02').toggleClass('displayNone_section');
                    $('.'+$(this).attr('data')).toggleClass('displayNone_section');
                    $('.reportSum').removeClass('displayNone_section');  
                    $('.reportSum.active_none').addClass('displayNone_section');

                    $('body').addClass('full-block-width');
                    $('#subclick_02').addClass('active');




                    if($('.'+$(this).attr('data')).find('.open_account').length == 0){
                      if($('#loan_switch_'+$(this).attr('member-id')).prop('checked')){
                        $('#loan_switch_'+$(this).attr('member-id')).first().trigger('click');
                      }
                    }
                    else{
                        if($('#loan_switch_'+$(this).attr('member-id')).prop('checked')){
                            $('.closed_account').addClass('display_none');
                            $('.open_account').removeClass('display_none');
                        }
                    }

                });

            });
        </script>

        @endsection

        