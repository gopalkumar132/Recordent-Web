@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' Report')

@section('page_header')
<h1 class="page-title" style="display: none;">
    <i class="voyager-list"></i> Business report

</h1>
@stop
@section('content')
<link rel="stylesheet" type="text/css" href="{{asset('css/report.css')}}">

<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
<div class="main_rc_section recordent_active">
    
    <div class="main_rc_section">

        <!-- ------------------Dashboard------------------ -->
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
                            <span style="display: block; padding:45px 0 20px 0; font-size:40px">Coming soon !</span>
                        </h2>
                    </div>

                    <p class="last-update">Report Date: {{General::getFormatedDate($dateTime)}}</p>
                </div>
                <!-- ------------------end------------------ -->
               <!--  <div style="display: none;" class="recordentscreen">
                    <center class="no-records">No report found!</center>
                </div> -->

               

                <!-- ------------------mainBlock------------------ -->
                
                <!-- ------------------download_btn------------------ -->
                <div class="download_btn active_none">
                    <div class="togle_buttons">
                        
                        <a href="javascript:void(0)" class="recordent-active active">Recordent</a>
                    
                         </div>
                   
                    <a target="_blank" class="btn_d" href="{{route('admin.individual.view.pdf', ['cp_id' => $cp_id, 'c_id' => $c_id])}}">Download <i class="glyphicon glyphicon-save-file"></i></a>
                </div>



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
                                <li><span style="width: 52%;">Business Name:</span>{{$user['business_name_rec'] }}</li>
                                <li><span style="width: 51%;">GSTIN / Business PAN:</span> {{$user['unique_identification_number'] }}</li>
                                <li><span style="width: 52%;">Business Type:</span>{{$user['business_type_rec'] }}</li>
                                <li><span style="width: 52%;">Business Sector:</span>{{$user['business_sector_rec'] }}</li>
                                
                            </ul>
                        </div>
                        <div class="col-md-6 list-recoddent">
                            <ul>
                            <li><span style="width: 52%;">Concerned Person Name:</span>{{$user['business_concerned_name_rec'] }}</li>
                            <li><span style="width: 52%;">Concerned Person Mobile:</span>{{$user['number'] }}</li>
                            <li><span style="width: 52%;">Concerned Person Email:</span>{{$user['business_email_rec'] }}</li>
                            <li><span style="width: 52%;">Concerned Person Designation:</span>{{$user['business_designation_rec'] }}</li>
                           
                            
                            </ul>
                        </div>
                    </div>
                </div>

                <div id="back_to_dasborad_members_main_div" class="displayNone_section">
                    <a class="back_to_dasborad_members" href="javascript:void(0)">
                                    <i class="fa fa-angle-left"></i>
                                    Back to Report Summary</a>
                                <div class="dashboard_members">
                    <h2 class="rc_title_sub recordent-title">
                    <span class="m01">Members</span>    
                    <span class="m02">Invoices</span>    
                    <span class="m03">Total dues</span>    
                    
                    </h2>
                    </div>
                </div>
                <?php
                    $invoice_count = 0;
                ?>
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
                                               <h4 class="sub">Member Name: {{General::getMaskedCharacterAndNumber(substr($data->company_name, 0, 7))}}</h4>
                                                <p>Number of invoices reported: <span style="color:#202f7d; font-weight:bold">{{count($data->dues)+$paidCount}}</span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                        
                                        <div class="rc_bottom">
                                            <div class="left_bottom">
                                                <p class="ac_inline">Paid Invoices: <span> {{$paidCount}} </span></p>
                                                <p class="ac_inline ac_lm">Unpaid Invoices: <span> {{count($data->dues)}}</span></p>
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
                            <h2 class="rc_title_sub recordent-title fleft">&nbsp;&nbsp;Details for member:{{General::getMaskedCharacterAndNumber(substr($data->company_name, 0, 7))}}</h2>
                            @if($data->accountDetails->count())
                                          @foreach($data->accountDetails as $accountDetail)
                                             @php
                                            $custom_id = $accountDetail->external_business_id;
                                            
                                        @endphp
                            <div class="center"><h2 class="rc_title_sub_sub recordent-title fleft">&nbsp;&nbsp;Custom Id:{{$custom_id}}</h2></div>
                            @endforeach
                            @endif
                           
                          
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
                                        <li><span>Number of Invoices:</span>{{count($data->dues)}}</li>
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
                                         @if($data->accountDetails->count())
                                          @foreach($data->accountDetails as $accountDetail)
                                             @php
                                            $amountDue = $accountDetail->due_amount - General::getPaidForDueOfBusiness($accountDetail->id);
                                            $disputeDetail = $accountDetail->dispute->last();
                                            $disputeStatus = 'No';
                                            $disputeComment = 'N/A';
                                            if($disputeDetail){
                                                $disputeComment = $disputeDetail->comment ? $disputeDetail->comment : 'N/A';
                                                $disputeStatus = $disputeDetail->is_open == 1 ? 'Open' : 'Closed';
                                            }
                                        @endphp
                                        <li><span>Dispute:</span>{{$disputeStatus}}</li>
                                        @endforeach
                                        @endif
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

                                        <li><span>Number of Invoices:</span> {{$paidCount}}</li>
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


                <div class="recordentscreen">
                    <center class="no-records">No report found</center>
                </div>
                @endforelse


                <!-- ------------------end------------------ -->


            <!-- ------------------Profile  Section------------------ -->
            
       <style type="text/css">
           #recordent_member_profile_div, #recordent_member_profile_div .row{
            margin-left: 15px;
        }
        .back_to_dasborad_members{
            left: 15px;
        }
        .back_to_members_invoice{
            left: 15px;
            top: 20px;
        }
        .dashboard_members{
           margin-left: 15px;
        }
         .profile-sec1 .rc_bottom{
             padding-top: 77px;
        }
         .rc_bottom{
             padding:115px 0 0 0
        }
         .recrodent-section .row {
                   margin-top: 41px;
        }
        .main_section{
            margin: 30px auto 50px auto;
        }
        .rc_title_sub_sub {
            color: #000120;
            font-size: 18px;
            padding-bottom: 16px;
            margin: 10px;
            padding-left: 222px;
        }
        @media only screen and (max-width: 576px) {
        .main_section{
            width: 93%;
        }
        .left_bottom{
            float: none;
        }
        .back_to_dasborad_members, .back_to_dasborad_profile{
            top: 342px;
            left: 32px;
        }
        .back_to_members_invoice{
            top: 8px;
            left: 28px;
        }
        .rc_title_sub.recordent-title.fleft{
            padding: 15px;
            padding-left: 37px;
        }
        #recordent_member_profile_div .inner.mobile_profile{
            margin-left: -20px;
        }
        .rc_title_sub_sub {
            color: #000120;
            font-size: 15px;
            padding-bottom: 0px;
            margin: 4px;
            padding-left: 57px;
        }
        .list-recoddent li {
            font-size: 10px;
        }
        .rc_bottom .left_bottom p {
            font-size: 14px;
        }
      }
       @media screen and (min-device-width: 577px) and (max-device-width: 800px)  {
        .main_section{
            width: 93%;
        }
        .left_bottom{
            float: none;
        }
        .back_to_dasborad_members, .back_to_dasborad_profile{
            top: 342px;
            left: 32px;
        }
        .back_to_members_invoice{
            top: 8px;
            left: 28px;
        }
        .rc_title_sub.recordent-title.fleft{
            padding: 15px;
            padding-left: 37px;
        }
        #recordent_member_profile_div .inner.mobile_profile{
            margin-left: -20px;
        }
        .rc_title_sub_sub {
            color: #000120;
            font-size: 15px;
            padding-bottom: 0px;
            margin: 4px;
            padding-left: 57px;
        }
        .list-recoddent li {
            font-size: 10px;
        }
        .rc_bottom .left_bottom p {
            font-size: 14px;
        }
       }  
       </style>                   

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
         <script>
             $('.recordentscreen').css('display', 'block');
             $('.equifex_recordentscreen').css('display', 'none');
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

