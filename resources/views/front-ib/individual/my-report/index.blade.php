
@section('page_title', __('voyager::generic.viewing').' Report')

@section('page_header')
<h1 class="page-title" style="display: none;">
    <i class="voyager-list"></i> Business report

</h1>
@stop
@section('content')
<link rel="stylesheet" type="text/css" href="{{asset('front-ib/css/report.css')}}">

<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

<div class="page-content container-fluid">
        <div class="col-md-12">
            
   <div class="panel panel-bordered">
     @include('layouts_front_ib.error')
             @if (\Session::get('message'))
               <div class="alert alert-success">
                    <span class="font-weight-semibold">{{ \Session::get('message') }}</span> 
               </div>
             @endif 

    <div class="panel-body">
        <div class="welcome_message">
    <span class="font-weight-bold" id="hello_message"></span> 
</div>
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
        <div class="download_btn active_none recordent_main">
                    <div class="togle_buttons">
                       
                        <a href="javascript:void(0)" class="recordent-active active">Recordent</a>

                        <script>
                            $('.recordentscreen').css('display', 'block');
                        </script>

                       
                    </div>
                   <!--  <a target="_blank" class="btn_d" href="#">Download <i class="glyphicon glyphicon-save-file"></i></a> -->
                     <a disabled  readonly class="btn_d">Download <i class="glyphicon glyphicon-save-file"></i></a><br><p style="color: red;">Coming Soon!&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
                </div> 
              <h4 class="reportSum active_none"><span>Report Summary</span></h4>  
     @if(count($records)>1)
     <style type="text/css">
         .display_retail_data{
            display: block !important;
         }
     </style>
     @else
     <style type="text/css">
         .display_retail_data{
            display: none !important;
         }
     </style>
     @endif
        <div class="recordent row recordentscreen recordent_main display_retail_data">
            <b style="font-weight: 1000;color: #202f7d;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Retail Customers</b>
        <select name="per1" id="SelectOptions" class="SelectOptions">
          <!-- <option selected="selected">Select the Customer</option> -->
          <?php
            foreach($records as $i=> $r) { ?>
              <option value="<?= $r['id'] ?>" @if($i==0) selected @endif><?= $r['person_name'] ?></option>
          <?php
            } ?>
       </select> 
    </div>
    <br>
        <div class="main_rc_section recordent_active">
            
            <div class="main_rc_section">


                      <div  id="display_rc_data">
                            
                        <div class="recordent row recordentscreen recordent_main">
                            <div id="recordent_member_profile" class="recordent_member_profile rc_section col-md-6 active_none top_rc_section">
                                <div class="rc_block">
                                    <div class="rc_top">
                                        <div class="left_top">
                                            <h4 class="sub">Profile information</h4>
                                        </div>
                                        <div class="clear"></div>
                                    </div>


                                    <div class="rc_bottom">
                                        <div class="left_bottom">
                                            <p class="ac_inline">Phone: <span id="mobile_number"></span></p>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </div>
                            </div>

                            <div id="recordent_members" class="recordent_members rc_section col-md-6 active_none top_rc_section">
                                <div class="rc_block">
                                    <div class="rc_top">
                                        <div class="left_top">
                                            <h4 class="sub">Members</h4>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="rc_bottom">
                                        <div class="left_bottom">
                                            <p>Total Members Reporting Dues: <span id="total_dues"></span></p>
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
                                             <p>No.of records: </p>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="rc_bottom rc_summary">
                                        <div class="left_bottom">
                                           <h4>Overdue status</h4>
                                            <p>1-89 days :<span id="summary_overDueStatus0To89Days"></span></p>
                                            <p>90-180 days :<span id="summary_overDueStatus90To179Days"></span></p>
                                            <p>180+ days :<span id="summary_overDueStatus180PlusDays"></span></p>
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
                                            <p>Total Due Amount:<span id="total_amount"></span></p>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                    <div class="rc_bottom">
                                        <div class="left_bottom">
                                            <p class="ac_inline">Paid: <span id="paid_amount">₹ </span></p>
                                            <p class="ac_inline ac_lm">Unpaid : <span id="unpaid_amount">₹ </span></p>
                                        </div>
                                        <div class="clear"></div>
                                    </div>

                                </div>
                            </div>
                            <div class="clear"></div>
                        </div>
                        <div id="recordent_member_profile_div" class="recordent_member_profile_div row recordentscreen displayNone_section">
                            <a class="back_to_dasborad_profile " href="javascript:void(0)">
                                <i class="fa fa-angle-left"></i>                        
                               Back to Report Summary</a>
                            <div class="inner row subclick_display mobile_profile">   

                            <h2 class="rc_title_sub recordent-title profile_information">
                            Profile information
                            </h2>             
                                <div class="col-md-6 list-recoddent">
                                    <ul>
                                       <li>Consumer's name:<span id="person_name"></span></li>
                                <li>DOB:<span id="dob"></span></li>
                                    </ul>
                                </div>
                                 <div class="col-md-6 list-recoddent">
                            <ul>
                            <li>Mobile number:<span id="mobile_number"></span></li>
                            <li>UID:<span id="aadhar"></span>
                            </li>
                            
                            </ul>
                        </div>
                            </div>
                        </div>


                        <div id="back_to_dasborad_members_main_div" class="back_to_dasborad_members_main_div displayNone_section">
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
                        <div class="recordentscreen col-md-6 full-width-section-01">
                            <!-- ------------------Recordent screen -01 ------------------ -->
                            <a class="backtodasborad2" href="javascript:void(0)" onclick="window.location.reload()">
                                <i class="fa fa-angle-left"></i>
                                Back to Dashbord</a>

                            <!-- ------------------Recordent screen end ------------------ -->



                            <!-- ------------------Recordent screen -02 ------------------ -->
                             <div class="recrodent-section recordent_members">

                        <!-- <div class="displayNone_section recordent_members_div recordent_02" id="recordent_02"> -->
                            <!-- <a class="back_to_dasborad_members" href="javascript:void(0)">
                                <i class="fa fa-angle-left"></i>
                                </a>
                            <div class="clear"></div>
                            <h2 class="rc_title_sub recordent-title"></h2> -->
                            <div class="displayNone_section recordent_members_div" id="recordent_02">
                            <!-- <div class="recordent_cards_screen" id="recordent_cards_screen">  -->
                            <div class="row recordent_cards" id="rc_section">



                                <div class="rc_section  active_none" >
                                    <div class="rc_block">
                                    
                                        <div class="rc_top">
                                            <div class="left_top">
                                                <h4 class="sub" id="person_name">Member Name: ********* </h4>
                                                <p>Number of invoices reported: <span id="no_of_invoices" style="color:#202f7d; font-weight:bold"></span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                        
                                        <div class="rc_bottom">
                                            <div class="left_bottom">
                                                <p class="ac_inline" >Paid Invoices: <span id="paid_invoices"> </span></p>
                                                <p class="ac_inline ac_lm">Unpaid Invoices: <span id="unpaid_invoices">  </span></p>
                                            </div>
                                            <div class="clear"></div>
                                        </div>
                                    </div>
                                    <a id="click_invoices" class="rc_link subclick" href="#">&nbsp;</a>
                                </div>

                            </div>
                        </div>
                        <!-- </div> -->
                        <div class="clear"></div>

                        <div class="displayNone_section" id="subclick_022">
                            <a id="invoices_data" class="back_to_members_invoice" href="javascript:void(0)">
                                <i class="fa fa-angle-left"></i>
                                Back to Previous Screen</a>
                            <div class="clear"></div>
                            <h2 id="memebr_name_masked" class="rc_title_sub recordent-title fleft">Invoices for member: *********</h2>
                            <div class="toggle_right paid_togle">
                                <label class="rc_switch">
                                    <input id="loan_switch" onchange="loanSwitch(this.checked)" type="checkbox" checked="">
                                    <span class="slider round">
                                        <i class="acc-text  open-ac">Paid invoices</i>
                                        <i class="acc-text close-ac">Unpaid invoices</i>
                                    </span>
                                </label>
                            </div>
                            <div class="clear"></div>

                           

                           <div id="unpaid_listing">
                            <!-- <div class="closed_account display_none inner row subclick_display boderleftsection">                                -->
                               <!--  <div class="col-md-6 list-recoddent">
                                    <ul>
                                        <li><span>Invoice no:</span></li>
                                        <li><span>Status:</span>Unpaid</li>
                                        <li><span>Overdue status:</span></li>
                                    </ul>
                                </div>
                                <div class="col-md-6 list-recoddent">
                                    <ul>
                                        <li><span>Due date:</span></li>
                                        <li><span>Date reported</span></li>
                                        <li><span>Last payment date:</span></li>
                                    </ul>
                                </div>
                                <div class="col-md-6 list-recoddent">
                                    <ul>
                                    <li><span>Opening balance:</span>₹ </li>
                                        <li><span>Closing balance:</span>₹ 
                                           
                                               |
                                                </li>

                                        
                                        <li><span>Last payment:</span></li> 
                                        
                                    </ul>
                                </div>
                                <div class="col-md-6 list-recoddent">
                                    <ul>
                                        <li><span>Proof of dues:</span><a target="_blank" href="#">Yes</a></li>
                                    </ul>
                                </div> -->
                            </div>

                            <div id="paid_listing" class="open_account inner row subclick_display boderleftsection">
                                <!-- <div class="col-md-6 list-recoddent">
                                    <ul>
                                        <li><span>Invoice no:</span> anam</li>
                                        <li><span>Status:</span>Paid</li>
                                        <li><span>Paid amount:</span>₹199999 </li>
                                        <li><span>Due amount:</span>₹100000 </li>                                       
                                    </ul>
                                </div>
                                <div class="col-md-6 list-recoddent">
                                    <ul>
                                        <li><span>Due date:  dsdsdsdsadsa</span></li>
                                        <li><span>Paid date:dsadsasaddsa</span></li>
                                        
                                        <li><span>Date reported:asdsadsa</span>
                                    </ul>
                                </div>
                            </div> -->
                        </div>
                            <div id="unpaid_listing_payment" class="closed_account display_none inner row subclick_display">
                            <!--  <a href="javascript:void(0)" data-due-id="" data-due-amount="" class="btn-to-action makePayment" data-toggle="modal" data-target="#pay">Make Payment</a>
                             <br class="invoice_screen"><br class="invoice_screen">
                                  <a href="" class="btn-to-action">Raise Dispute</a>
                               <br><small id="make_payment_help" class="form-text text-muted">(Payment will be credited in the member's <br class="invoice_screen"> bank account within 24 hours)</small> -->
                          </div>
                        </div>
                        <div class="clear"></div>
                    </div>
                           
                            <!-- ------------------Recordent screen end ------------------ -->

                        </div>


                       <!--  <div class="recordentscreen">
                            <center class="no-records">No report found</center>
                        </div> -->
                    </div>

            </div>
        </div>
      </div>
     </div>
    </div>
   </div>
   <!-- Start Model Pay Outstanding Amount -->  
    <div class="modal" id="pay" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title">Make Payment</h3>
          </div>
          <div class="modal-body">
            <form action="{{ route('front-individual.my-records-make-payment') }}" method="POST">
                @csrf   
                <input type="hidden" name="due_id" value="">
                <input type="hidden" name="due_amount" value="" id="due_amount">
                                    
                <div class="form-group">
                    <label for="due_amount">*Amount (Minimum: Rs. 1)</label>
                    <input type="text" class="form-control" name="pay_amount" min="1" oninput="chargesApplicable(this)" value="" required onkeypress="return numbersonly(this,event)">
                    <span id="dueAmountExceedError" style="color:red;"></span>
                </div>                          
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="agree_terms">
                    <label class="form-check-label" for="agree_terms">Check here to indicate that you have read and agree to the terms of the <a target="_blank" href="{{route('end-user-license-agreement')}}" target="_blank">Recordent End User License Agreement</a></label>
                </div>                          
                <div class="form-action pull-right">
                    <button type="submit" disabled class="btn btn-primary">SUBMIT</button>
                    <button type="reset" class="btn btn-secondary" data-dismiss="modal">CANCEL</button>
                </div>
            </form>
          </div>
          <div class="modal-footer">
          </div>
        </div>
      </div>
    </div>



                <!-- ------------------end------------------ -->


            <!-- ------------------Profile  Section------------------ -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
            
<style type="text/css">
        #recordent_member_profile_div, #recordent_member_profile_div .row{
            margin-left: 15px;
        }
        .full-block-width .full-width-section-01 {
            width: 100%;
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
             padding-top: 44px;
        }
         .rc_bottom{
             padding:115px 0 0 0
        }
         .recrodent-section .row {
                   margin-top: 41px;
        }
        .main_section {
            margin: 0 auto 50px auto;
            max-width: 970px;
        }
        .rc_mid {
            padding: 10px 0;
         }      
         .last-update, .rc_age .rc_block p.last-update {
            font-size: 16px;
            color: #000;
            font-weight: 600;
        }
        .main_section .left_top h4 {
            font-size: 28px;
            color: #0d1332;
            font-weight: 400!important;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        button, input, select, textarea{
                color: #202f7d;
        }
        .SelectOptions{
            width: 30%;
        }
        .rc_mid h2 {
            font-family: Open Sans,sans-serif!important;
            font-weight: 700!important;
            text-align: center;
            font-size: 100px;
            line-height: 102px;
            color: #0d1332;
        }
        .rc_title_sub_sub {
            color: #000120;
            font-size: 18px;
            padding-bottom: 16px;
            margin: 10px;
            padding-left: 222px;
        }
        .rc_title_sub_sub {
            color: #000120;
            font-size: 18px;
            padding-bottom: 16px;
            margin: 10px;
            padding-left: 222px;
        }
        .recordent_screen .recordentscreen {
        display: block;
            }
        .recordentscreen {
            display: none;
        }
        .row {
            margin-right: -15px;
            margin-left: -15px;
        }
        .recordentscreen .rc_block {
            border-left: solid 10px #202f7d;
            min-height: 180px;
        }
        .btn.disabled, [disabled], .btn[disabled], fieldset[disabled] {
                cursor: not-allowed;
                filter: alpha(opacity=65);
                opacity: .65;
                box-shadow: none;
                pointer-events: none;
                background-color: #b8bcbe !important;
                border-color: #b8bcbe !important;
            }
        .rc_screens {
            padding: 30px 40px;
            font-family: Open Sans,sans-serif;
            color: #555;
        }
        .rc_bottom {
            padding: 71px 0 0 0;
        }
        .recordentscreen .rc_block {
            border-left: solid 10px #202f7d;
            min-height: 180px;
        }
        .rc_block, .webscreens .row.enquires .rc_block {
            border: solid 1px #e9e9e9;
            padding: 15px 20px;
            background: #fff;
            box-shadow: 3px 3px 15px #eee;
            -moz-box-shadow: 3px 3px 15px #eee;
            -webkit-box-shadow: 3px 3px 15px #eee;
            -o-box-shadow: 3px 3px 15px #eee;
            border-radius: 10px;
            -webkit-transition: all .3s ease-in-out;
            -moz-transition: all .3s ease-in-out;
            -ms-transition: all .3s ease-in-out;
            -o-transition: all .3s ease-in-out;
            transition: all .3s ease-in-out;
            min-height: 205px;
        }
        .row>[class*=col-] {
            margin-bottom: 25px;
        }
        #recordent_member_profile_div, #recordent_member_profile_div .row {
            margin: 0;
        }
        .displayNone_section {
            display: none !important;
        }
        .row {
            margin-right: -15px;
            margin-left: -15px;
        }
        .rc_screens {
            padding: 30px 40px;
            font-family: Open Sans,sans-serif;
            color: #555;
        }
        .left_bottom, .left_top {
            float: left;
        }
        .rc_bottom .left_bottom p {
            font-size: 18px;
            line-height: 24px;
            color: #575c71;
            font-weight: 600!important;
            margin-bottom: 0;
        }
        .ac_inline {
            display: inline-block;
        }
        .rc_bottom .left_bottom p span {
            color: #202f7d;
            font-weight: 700;
        }
        * {
            outline: none;
        }
        *, :after, :before {
            box-sizing: border-box;
        }
        .rc_bottom .left_bottom p {
            font-size: 18px;
            line-height: 24px;
            color: #575c71;
            font-weight: 600!important;
            margin-bottom: 0;
        }
        td, tr, p {
            color: #000;
            font-size: 15px;
            font-weight: 500;
        }
        .rc_screens {
            padding: 30px 40px;
            font-family: Open Sans,sans-serif;
            color: #555;
        }
        .rc_screens .clear {
            clear: both;
            width: 100%;
            height: 1px;
            display: block;
        }
        .rc_bottom {
            padding: 50px 0 0 0;
        }
        #recordent_member_profile_div, #recordent_member_profile_div .row {
            margin: 0;
        }
        .clear {
            clear: both;
        }
        #recordent_member_profile_div, #recordent_member_profile_div .row {
            margin: 0;
        }
        .displayNone_section {
            display: none !important;
        }
        .back_to_dasborad_profile, .back_to_dasborad_members, .back_to_members_invoice {
            display: inline-block;
            margin: 0 0 30px 0;
        }
        .backtodasborad, .backtodasborad1, .backtodasborad2, .backtodasborad3, .back_to_dasborad_profile, .back_to_dasborad_members, .back_to_members_invoice {
            border: solid 1px #202f7d;
            color: #fff;
            padding: 10px 15px 10px 30px;
            font-weight: 700;
            font-size: 12px;
            line-height: 16px;
            color: #202f7d!important;
            display: none;
            z-index: 99;
            margin-left: 45px;
            text-decoration: none!important;
            position: relative;
        }
        #recordent_member_profile_div, #recordent_member_profile_div .row {
            margin: 0;
        }
        .rc_title_sub {
            font-family: var(--font-rubik);
            color: #000120;
            font-size: 30px;
            font-weight: 600;
            padding-bottom: 30px;
            margin: 0;
        }
        .list-recoddent {
            border-left: solid 1px #202f7d;
            /*padding: 0 20px 20px;*/
        }
        .voyager .panel {
            margin-top: 75px;
        }
        .rc_section {
            margin-bottom: 30px;
        }
        .rc_screens a {
            color: #202f7d;
            text-decoration: none;
        }
        .rc_link {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            z-index: 9;
        }
        .fleft {
            float: left;
            max-width: 80%;
        }
        h2 {
            display: block;
            /*font-size: 1.5em;*/
            margin-block-start: 0.83em;
            margin-block-end: 0.83em;
            margin-inline-start: 0px;
            margin-inline-end: 0px;
            /*font-weight: bold;*/
        }
         


            .paid_togle .rc_switch .acc-text{    left: -160px}
            .toggle_right.paid_togle.toggle_none, .toggle_right.paid_togle {    margin-left: 184px !important;}
            .paid_togle /*.rc_switch*/ .acc-text{left: -142px; font-size: 16px;}

            .recrodent-section .row {
                margin: 0;
            }
            .boderleftsection {
                border-left: solid 1px #202f7d;
                padding-left: 20px;
                margin-bottom: 30px !important;
                padding-top: 22px;
            }
            .row>[class*=col-] {
                margin-bottom: 25px;
            }
            .list-recoddent ul {
                margin: 0;
                padding: 0;
                list-style: none;
            }
            .list-recoddent li {
                color: #202f7d;
            }
            .rc_loans .enquires .rc_block .item p, .list-recoddent li {
                font-size: 18px;
                line-height: 20px;
                margin: 0 0 15px 0;
                padding: 0;
                font-weight: 700!important;
            }
            .rc_loans .enquires .rc_block .item span, .row01 .item p span, .list-recoddent li span {
                display: inline-block;
                width: 220px;
                font-weight: 400 !important;
                color: #000 !important;
            }
            .recordentscreen.col-md-6.full-width-section-01{margin-bottom: 0;}

            #subclick_02{display: none;}
            #subclick_02.active{display:block !important;}
            #subclick_02.subclick_active, #recordent_02.recordent_active,

            #subclick_03.subclick_active, #recordent_03.recordent_active,

            #subclick_04.subclick_active, #recordent_04.recordent_active
            {display: block;}

            .m01{display: none;}
            .m02{ display: none;}
            .m03{ display: none;}
            .m01.active{display: block;}
            .m02.active{ display: block;}
            .m03.active{ display: block;}
            .rc_screens {
                padding: 30px 40px;
                font-family: Open Sans,sans-serif;
                color: #555;
            }
            .main_section .left_top h4.fulname{    font-size: 20px;     text-transform: uppercase;}
             .main_section .left_top h4.fulname .s-rc{ display: inline-block;}

             .backtodasborad, .backtodasborad1, .backtodasborad2, .backtodasborad3,
             .back_to_dasborad_profile, .back_to_dasborad_members, .back_to_members_invoice

             {border:solid 1px #202f7d;color:#fff;padding:10px 15px 10px 30px;font-weight:700;font-size:12px;line-height:16px;color:#202f7d!important;display:none;z-index:99;margin-left:45px;text-decoration:none!important;position:relative}
             .backtodasborad i, .backtodasborad1 i, .backtodasborad2 i, .backtodasborad3 i, .back_to_members_invoice i,
             .back_to_dasborad_profile i, .back_to_dasborad_members i
             {font-size:30px;position:absolute;left:10px;top:2px}
             .backtodasborad:hover, .backtodasborad1:hover, .backtodasborad2:hover, .backtodasborad3:hover,
             .back_to_dasborad_profile:hover, .back_to_dasborad_members:hover, .back_to_members_invoice:hover
             {background:#202f7d;color:#fff!important}
             .mobileScreen .backtodasborad, .mobileScreen .backtodasborad1{display:inline-block}


             .back_to_dasborad_profile, .back_to_dasborad_members, .back_to_members_invoice{    display: inline-block;
              margin: 0 0 30px 0;}


             .backtodasborad1{display: none !important;}
            .backtodasborad2, .backtodasborad3{margin-left: 15px;}

             .acccounts_dashboard .backtodasborad1{display: inline-block !important; }
             .acccounts_dashboard .backtodasborad{display: none !important;}

             .recordentscreen_active .backtodasborad2{display: inline-block;}
             .recordentscreen_active1 .backtodasborad3{display: inline-block;     margin: 0 0 15px 0;}
             .rc_summary p {
                display: inline-block;
                margin-right: 7px;
            }
            .display_none{display:none}
            .invoices_div{
                width: 234%;
            }

            .rc_switch{position:relative;display:inline-block;width:60px;height:34px}
            .rc_switch input{opacity:0;width:0;height:0}
            .rc_switch .slider{position:absolute;cursor:pointer;top:0;left:0;right:0;bottom:0;background-color:#ccc;-webkit-transition:.4s;transition:.4s}
            .rc_switch .slider:before{position:absolute;content:"";height:26px;width:26px;left:30px;bottom:4px;background-color:#fff;-webkit-transition:.4s;transition:.4s}
            .rc_switch input:checked+.slider{background-color:#202f7d}
            .rc_switch .rc_switch input:focus+.slider{box-shadow:0 0 1px #202f7d}
            .rc_switch input:checked+.slider:before{-webkit-transform:translateX(-26px);-ms-transform:translateX(-26px);transform:translateX(-26px)}
            .rc_switch .slider.round{border-radius:34px}
            .rc_switch .slider.round:before{border-radius:50%}
            .rc_switch .acc-text{position:absolute;font-size:18px;display:inline-block;line-height:24px;color:#000;font-weight:600;text-transform:uppercase;top:20%;left:-75px;font-style:normal;font-family:Open Sans,sans-serif!important}
            .paid_togle .rc_switch .acc-text{    left: -165px}


            .rc_switch .open-ac{display:none}
            .rc_switch input:checked+span .open-ac{display:block}
            .rc_switch input:checked+span .close-ac{display:none}
            .display_rc_data {
                display: none;
            }
            .download_btn {
                text-align: right;
                position: relative;
                margin: 60px 0 30px 0;
            }
            .togle_buttons {
                position: absolute;
                border-radius: 30px;
                z-index: 2;
                font-size: 22px;
                line-height: 24px;
                color: #0d1332;
                font-weight: 600;
                border: solid 1px #202f7d;
                background: #f9f9f9;
                top: 0;
                left: 50%;
                overflow: hidden;
                margin-left: -150px;
            }
            .download_btn{text-align:right;position:relative;margin:60px 0 30px 0}
            .download_btn:before{content:"";position:absolute;left:0;top:50%;width:100%;height:1px;background:#202f7d;z-index:1}
            .download_btn a.btn_d{display:inline-block;border:solid 1px #202f7d;background:#f9f9f9;position:relative;z-index:2;font-size:22px;line-height:24px;color:#0d1332;font-weight:600;padding:15px 70px 15px 30px;border-radius:30px;position:relative}
            .download_btn a.btn_d i{color:#202f7d;display:inline-block;font-size:0;width:20px;height:25px;background:url(../save-icon.png) no-repeat 0 0;position:absolute;right:30px;top:14px}
            .download_btn.active_none a.btn_d{opacity: 1 !important;}
            .togle_buttons{position:absolute;border-radius:30px;z-index:2;font-size:22px;line-height:24px;color:#0d1332;font-weight:600;border:solid 1px #202f7d;background:#f9f9f9;top:0;left:50%;overflow:hidden;margin-left:-150px}
            .togle_buttons a{padding:15px 30px;display:inline-block;color:#000}
            .togle_buttons a:last-child{margin-left:-15px}
            .togle_buttons a:first-child{margin-left:0}
            .togle_buttons a.active{background:#202f7d;color:#fff}
            .download_btn.active_none a.btn_d{opacity: 1 !important;}
            .reportSum {
                text-align: center;
                padding: 0;
                margin: -1px 0 30px 0;
                font-size: 28px;
                line-height: 30px;
                color: #fff;
                font-weight: bold;
            }
            /*h4 {
                display: block;
                margin-block-start: 1.33em;
                margin-block-end: 1.33em;
                margin-inline-start: 0px;
                margin-inline-end: 0px;
                font-weight: bold;
            }*/
            .reportSum span {
                border: solid 1px #ccc;
                padding: 15px 20px;
                background: #202f7d;
                box-shadow: 3px 3px 15px #eee;
                -moz-box-shadow: 3px 3px 15px #eee;
                -webkit-box-shadow: 3px 3px 15px #eee;
                -o-box-shadow: 3px 3px 15px #eee;
                border-radius: 10px;
                display: block;
            }
            .welcome_message{
                text-align: center;
                font-size: 30px;
            }
            .font-weight-bold{
                font-weight: 520px !important;
            }
            .invoice_screen{
                display: none;
            }
            @media screen and (max-width: 767px){
                .rc_title {
                font-size: 20px;
                line-height: 30px;
            }
                .full-block-width .full-width-section-01 {
                 width: 214%;
                }
            .SelectOptions{
            width: 80%;
           }
            .welcome_message {
                text-align: center;
                font-size: 18px;
            }
            .download_btn a.btn_d i {
                width: 17px;
                height: 25px;
                background: url(../save-icon-hover.png) no-repeat 0 0;
                right: 15px;
                top: 14px;
                background-size: 100%;
            }
                .main_section .left_top h4 {
                font-size: 12px;
            }
            .last-update, .rc_age .rc_block p.last-update{
                font-size: 12px;
            }
            .rc_dashbord .main_section {
                background: url(../credit-bg.png) right top no-repeat;
                background-size: 51% 61%;
            }
            .rc_block {
                border: solid 1px #ccc;
            }
            .main_section {
                max-width: 100%;
                margin-bottom: 25px;
            }
            .rc_block {
                padding: 15px 15px;
                min-height: 50px;
                position: relative;
                z-index: 1;
            }
            .main_section .left_top {
                width: 100%;
            }

            .rc_mid {
                padding: 0;
            }
            .rc_mid h2 {
                font-size: 60px;
                line-height: 62px;
                margin-top: 0;
                margin-bottom: 0;
            }
            .rc_mid h2 span {
                padding: 10px 0 !important;
                font-size: 26px !important;
            }
            .reportSum {
                margin: -10px 0 45px 0;
                font-size: 20px;
                font-weight: normal;
            }
            .reportSum span {
                padding: 13px 15px;
            }
            .mobileScreen .rc_dashbord .main_section, .mobileScreen .rc_title{display:none}
            .main_rc_section.mobileScreen{    margin: -18px 0 50px 0;}
            .main_rc_section.recordent_active{    margin:65px 0 50px 0;}
            #recordent_member_profile_div .inner.mobile_profile{    margin-top: 80px;}
            .profile_information{    position: absolute;
              top: 100px;
              left: 28px;}

            .recordent_active .main_section.equifax{display:none}
            .recordent_active .rc_screens:before{display:none}
            .personal_block ul li span{font-size:12px;text-transform:initial!important; }
            .mobileScreen .backtodasborad, .mobileScreen .backtodasborad1,
            .backtodasborad2, .backtodasborad3, .back_to_dasborad_profile, .back_to_dasborad_members, .back_to_members_invoice
            {bottom:auto;top:110px;font-size:0;background:url(../back-button.png) no-repeat 0 0 !important;width:30px;height:30px;right:auto;position:absolute;left:-25px;background-size:100% 100% !important;z-index:9;padding:0;border:none}
            .mobileScreen .backtodasborad i, .mobileScreen .backtodasborad1 i, .back_to_dasborad_members i, .back_to_members_invoice i
            .backtodasborad2 i, .backtodasborad3 i, .back_to_dasborad_profile i, .back_to_members_invoice i
            {display:none}

            .toggle_right.paid_togle.toggle_none, .toggle_right.paid_togle {margin: 0 0 10px 0;}
            .back_to_dasborad_profile{left: 18px;}
            .back_to_dasborad_members{      left: 18px;   top: 90px;}
            .back_to_members_invoice {    left: 14px;
              top: 36px;}
            .rc_title_sub.recordent-title.fleft{font-size: 18px;
              padding: 0px 0 10px 0;
              font-weight: 400 !important;    max-width: 100%;
            }
            .paid_togle .rc_switch .acc-text{left: -142px; font-size: 16px;}

             .list-recoddent li span{width:130px;}

             .rc_dashbord .main_section.recordentscreen{display: none;}

            .eqfuifax_title img{    width: 84px;}

            .title_imporve{    text-align: center;   font-size: 24px;   padding: 0;   font-weight: 600;}

            .main_section .left_top{width: 60%;}
            .main_section .left_top h4.fulname{    font-size: 16px;
              text-transform: uppercase;
              }
              .rc_bottom{padding-top:15px}
              .rc_title_sub{font-size:23px;padding-left:30px;display:block;top: 50px; /*padding:15px 0 18px 34px*/}
              .recrodent-section .row.inner {
                border-left: solid 10px #202f7d !important;
                border: solid 1px #ccc;
                padding: 15px 15px;
                background: #fff;
                box-shadow: 3px 3px 15px #eee;
                -moz-box-shadow: 3px 3px 15px #eee;
                -webkit-box-shadow: 3px 3px 15px #eee;
                -o-box-shadow: 3px 3px 15px #eee;
                border-radius: 10px;
                margin-left: -28px;
                    width: 55%;
            }
            .invoice_screen{
                display: block;
            }
            }
            @media screen and (min-width: 800px) {
            .toggle_right {
                float: right;
            }
            .list-recoddent {
                border-left: solid 1px #202f7d;
                padding: 0 20px 20px;
            }
            }
            @media (min-width: 992px){
            .col-md-6 {
                width: 48%;
            }
            .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12 {
                float: left;
            }
            .col-lg-1, .col-lg-2, .col-lg-3, .col-lg-4, .col-lg-5, .col-lg-6, .col-lg-7, .col-lg-8, .col-lg-9, .col-lg-10, .col-lg-11, .col-lg-12, .col-md-1, .col-md-2, .col-md-3, .col-md-4, .col-md-5, .col-md-6, .col-md-7, .col-md-8, .col-md-9, .col-md-10, .col-md-11, .col-md-12, .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12, .col-xs-1, .col-xs-2, .col-xs-3, .col-xs-4, .col-xs-5, .col-xs-6, .col-xs-7, .col-xs-8, .col-xs-9, .col-xs-10, .col-xs-11, .col-xs-12 {
                position: relative;
                min-height: 1px;
                   padding-right: 59px;
                    padding-left: 88px;
            }
            }
                    @media only screen and (max-width: 576px) {
                    .main_section{
                        width: 93%;
                    }
                    .left_bottom{
                        float: none;
                    }
                    .back_to_dasborad_members, .back_to_dasborad_profile{
                        top: 50px;
                        left: 0px;
                    }
                    .back_to_members_invoice{
                        top: 5px;
                        left: -6px;
                    }
                    .rc_title_sub.recordent-title.fleft{
                        padding: 15px;
                        padding-left: 37px;
                    }
                    #recordent_member_profile_div .inner.mobile_profile{
                            margin-top: 0px;
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
                    #recordent_member_profile_div .inner {
                        border-left: solid 10px #202f7d !important;
                        border: solid 1px #ccc;
                        /*padding: 15px 15px;*/
                        background: #fff;
                        box-shadow: 3px 3px 15px #eee;
                        -moz-box-shadow: 3px 3px 15px #eee;
                        -webkit-box-shadow: 3px 3px 15px #eee;
                        -o-box-shadow: 3px 3px 15px #eee;
                        border-radius: 10px;
                        margin-bottom: 30px;
                        margin-top: 55px;
                    }
                    .recrodent-section .recordent_cards {
                        margin-left: -40px;
                        margin-right: -34px;
                    }
                    .dashboard_members {
                        margin-left: 11px;
                        margin-top: -64px;
                    }
                    .rc_bottom {
                       padding-top: 51px;
                    }
                    .rc_title_sub.recordent-title.fleft {
                            margin-top: -73px;
                            margin-left: -60px;
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
                        top: 4px;
                        left: 0px;
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
                   @media screen and (max-width: 992px){
                    .togle_buttons a {
                        padding: 10px 20px;
                    }
                    .download_btn {
                         margin: 50px 0 100px 0;
                    }  
                    .togle_buttons {
                        font-size: 15px;
                        top: -25px;
                        left: 0;
                        margin-left: 0;
                    } 
                    .download_btn a.btn_d {
                        font-size: 0;
                        right: 0;
                        top: -27px;
                        padding: 0;
                        width: 50px;
                        height: 50px;
                        border-radius: 100%;
                        background: #273581;
                        position: absolute;
                   }    
                   .download_btn {
                margin: 50px 0 100px 0;
            } 
</style>
       <script type="text/javascript">
        $(document).ready(function(){
            var data_id = document.getElementById("SelectOptions").value;
            // alert(data_id);
            $("#click_invoices").attr("data","invoices_div_"+data_id);
               $("#click_invoices").attr("member-id",data_id);
               $("#invoices_data").attr("data","invoices_div_"+data_id);
                $("#paid_listing").html('');
                $("#unpaid_listing").html('');
                 $("#unpaid_listing_payment").html('');  
                   document.getElementById("subclick_022").className += " invoices_div_"+data_id;
            onselctduesajax(data_id);
        });
           $('#SelectOptions').change(function(){
            var selected_options = $("#SelectOptions option:selected")[0].id;

            // document.getElementByClass("display_rc_data").style.display = "none";
            $('.display_rc_data').hide();
             $('.recordent_cards_screen').hide();
             var data_id = document.getElementById("SelectOptions").value;
             $("#click_invoices").attr("data","invoices_div_"+data_id);
               $("#click_invoices").attr("member-id",data_id);
               $("#invoices_data").attr("data","invoices_div_"+data_id);
                $("#paid_listing").html('');
                $("#unpaid_listing").html('');
                 $("#unpaid_listing_payment").html('');
               // invoices_data
               // $('#subclick_022').classList.add("invoices_div_"+data_id);
               document.getElementById("subclick_022").className += " invoices_div_"+data_id;

                 onselctduesajax(data_id);
             // alert(data_id);
               
              // console.log('#display_rc_data_'+data_id);
              // $('.display_rc_data').not('#display_rc_data_'+data_id).hide();
              // document.getElementById("recordent_cards_screen_"+data_id).style.display = "block";
             // document.getElementById("display_rc_data_"+data_id).style.display = "block";
            // if(selected_options== data_id){
            //     document.getElementById("display_rc_data").style.display = "block";

            // } else {
            //     document.getElementById("display_rc_data").style.display = "none";
            //  alert($("#SelectOptions option:selected")[0].id)

            // }

            // var a= $(':selected', $(this)).data('id');
            // alert(a);
            });
       </script>                   

        <script>

            function onselctduesajax(data_id){
                // alert(data_id);
                $.ajax({
                    method: 'GET',
                    url: "{{route('front-individual.get-individual-report')}}",
                    data: {
                        data_id: data_id,
                    },



                    success:function(res){
                        // console.log(res);
                        // alert(res);
                        var student = res.student;
                        var records = res.records;
                        var summary_totalMemberReported = res.summary_totalMemberReported;
                         var summary_totalDueReported = res.summary_totalDueReported;
                          var summary_totalDuePaid = res.summary_totalDuePaid;
                          var paidAmount = JSON.stringify(res.paidAmount);
                          paidAmount=paidAmount.replace(/['"]+/g, '');
                          var settled_records = res.settled_records;

                          var unpaid_invoices = res.due;
                            var paid_data = res.paid_data;      
                          var paid_invoices = res.paid_cnt;
                          var dueamount = res.dueamount;
                          // console.log(paidAmount);
                          // records= JSON.stringify(records);

                           var summary_overDueStatus0To89Days = res.summary_overDueStatus0To89Days;

                             var summary_overDueStatus90To179Days = res.summary_overDueStatus90To179Days;
                              var summary_overDueStatus180PlusDays = res.summary_overDueStatus180PlusDays;
                               var summary_totalMemberReported = res.summary_totalMemberReported;
                              contact_phone= JSON.stringify(student['contact_phone']);
                              contact_phone=contact_phone.replace(/['"]+/g, '');
                              person_name= JSON.stringify(student['person_name']);
                              person_name=person_name.replace(/['"]+/g, '');
                              if(student['dob']==null){
                                dob = "&nbsp;&nbsp;&nbsp;&nbsp;-";
                            } else {
                                 dob= JSON.stringify(student['dob']);
                                 dob=dob.replace(/['"]+/g, '');
                                 dob = moment(dob).format('DD-MM-YYYY');
                            }
                             if(student['aadhar_number']==null){
                                aadhar = "&nbsp;&nbsp;&nbsp;&nbsp;-";
                            } else {
                                  aadhar= JSON.stringify(student['aadhar_number']);
                              aadhar=aadhar.replace(/['"]+/g, '');
                            }
                              
                             
                              summary_totalDuePaid= JSON.stringify(summary_totalDuePaid);
                              summary_totalDuePaid=summary_totalDuePaid.replace(/['"]+/g, '');
                             
                              summary_totalMemberReported= JSON.stringify(summary_totalMemberReported);
                              summary_totalMemberReported=summary_totalMemberReported.replace(/['"]+/g, '');
                              summary_totalDueReported= JSON.stringify(summary_totalDueReported);
                              summary_totalDueReported=summary_totalDueReported.replace(/['"]+/g, '');

                              $('span#mobile_number').html( contact_phone);
                              $('span#hello_message').html("Hello  "+person_name);
                          $('span#person_name').html( person_name);
                          
                           $('span#dob').html(dob);
                             $('span#aadhar').html( aadhar );
                           $('span#total_dues').html( summary_totalMemberReported);
                            $('span#total_amount').html("₹ "+ summary_totalDueReported);
                             $('span#paid_amount').html("₹ "+ summary_totalDuePaid);
                             $('span#unpaid_amount').html("₹ "+ JSON.stringify(summary_totalDueReported-summary_totalDuePaid) );
                              $('span#summary_overDueStatus0To89Days').html( JSON.stringify(summary_overDueStatus0To89Days) );
                               $('span#summary_overDueStatus90To179Days').html( JSON.stringify(summary_overDueStatus90To179Days) );
                                $('span#summary_overDueStatus180PlusDays').html( JSON.stringify(summary_overDueStatus180PlusDays) );
                                 $('span#no_of_invoices').html( JSON.stringify(paid_invoices+unpaid_invoices) );
                                 $('span#paid_invoices').html( JSON.stringify(paid_invoices) );
                                 $('span#unpaid_invoices').html( JSON.stringify(unpaid_invoices));

                                 // alert(3);
                             
                                    for(i=0; i<dueamount.length; i++){
                                                // alert(JSON.stringify(dueamount));
                                            var dispute_url = '{{ route("front-individual.raise-dispute", ":id") }}';
                                            dispute_url = dispute_url.replace(':id', dueamount[i].due_id);
                                            var elements = "<div class='closed_account display_none inner row subclick_display boderleftsection'><div class='col-md-6 list-recoddent'><ul><li><span>Invoice no:</span>"+dueamount[i].due_id+"</li><li><span>Status:</span>Unpaid</li><li><span>Overdue status:</span>"+dueamount[i].overDueStatus+"</li></ul></div><div class='col-md-6 list-recoddent'><ul><li><span>Due date:</span>"+moment(dueamount[i].due_date).format('DD-MM-YYYY')+"</li><li><span>Date reported:</span>"+moment(dueamount[i].date_reported).format('DD-MM-YYYY')+"</li><li><span>Last payment date:</span>"+dueamount[i].last_paid_date+"</li></ul></div><div class='col-md-6 list-recoddent'<ul><li><span>Opening balance:</span>₹ "+dueamount[i].due_amount+"</li><li><span>Closing balance:</span>₹ "+dueamount[i].unpaid+"</li><li><span>Last payment:</span>₹"+dueamount[i].last_paid_amount+"</li></ul></div><div class='col-md-6 list-recoddent'><ul>";
                                            // </ul></div></div>";

                                               
                                                if("dueamount[i].proof_of_due"==null){
                                                   elements+= "<li><span>Proof of dues:</span><a target=_blank href=#>Yes</a></li></ul></div></div>";
                                                } else {
                                                  elements+= "<li><span>Proof of dues:</span>No</li></ul></div></div>"; 
                                                }
                                            


                                           if(settled_records==0){
                                            if (dueamount[i].unpaid>0) {
                                              elements += "<div class='closed_account display_none inner row subclick_display'><a href=javascript:void(0) data-due-id="+dueamount[i].due_id+" data-due-amount="+dueamount[i].unpaid+" class='btn-to-action makePayment' data-toggle=modal data-target=#pay>Make Payment</a><br class='invoice_screen'><br class='invoice_screen'><a href="+dispute_url+" class='btn-to-action'>Raise Dispute</a><br><small id=make_payment_help class='form-text text-muted'>(Payment will be credited in the member's <br class='invoice_screen'> bank account within 24 hours)</small></div>";
                                            }
                                        }

                                           $("#unpaid_listing").append(elements);

                                         
                                          

                                           // $("#unpaid_listing_payment").append("<a href=javascript:void(0) data-due-id="+dueamount[i].due_id+" data-due-amount="+dueamount[i].unpaid+" class='btn-to-action makePayment' data-toggle=modal data-target=#pay>Make Payment</a><br class='invoice_screen'><br class='invoice_screen'><a href="+dispute_url+" class='btn-to-action'>Raise Dispute</a><br><small id=make_payment_help class='form-text text-muted'>(Payment will be credited in the member's <br class='invoice_screen'> bank account within 24 hours)</small>");
                                            }

                                           // console.log(JSON.stringify(paid_data));
                                            // alert(JSON.stringify(paid_data));
                                            for(i=0; i<paid_data.length; i++){

                                                 // alert(paid_data[i].due_id);
                                            $("#paid_listing").append("<div class='col-md-6 list-recoddent'><ul><li><span>Invoice no:</span> "+paid_data[i].due_id+"</li><li><span>Status:</span>Paid</li><li><span>Paid amount:</span>₹ "+paid_data[i].paid_amount+" </li><li><span>Due amount:</span>₹ "+paid_data[i].due_amount+" </li></ul></div><div class='col-md-6 list-recoddent'><ul><li><span>Due date: </span>"+moment(paid_data[i].due_date).format('DD-MM-YYYY')+"</li><li><span>Paid date:</span>"+moment(paid_data[i].paid_date).format('DD-MM-YYYY')+"</li><li><span>Date reported:</span>"+moment(paid_data[i].created_at).format('DD-MM-YYYY')+"</ul></div></div>");

                                        }



                                          
                        
                         
                               

                    },
                    error:function(error){
                        // alert(JSON.stringify(error));
                        // console.log(res);

                    }
                });

            }
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
                    $('.recordent_02').addClass('recordent_active')
                    $('.top_rc_section').addClass('displayNone_section')
                    $('.download_btn.active_none').addClass('displayNone_section')
                    $('.recordentscreen').addClass('recordentscreen_active')
                });


                $('.recordent_click_03').click(function() {
                    $('.recordent_03').addClass('recordent_active')
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
                    $('.recordent_02').removeClass('displayNone_section')
                    $('#subclick_02').removeClass('subclick_active')
                    $('.recordentscreen').addClass('recordentscreen_active')
                    $('.recordentscreen1').removeClass('recordentscreen_active')

                });


                $('#subclick_03 .backtodasborad3').click(function() {
                    $('.recordent_03').removeClass('displayNone_section')
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







                $('.recordent_member_profile').click(function() {
                    // alert(1);
                    // return false;
                    $('.recordent_main').toggleClass('displayNone_section');
                    $('.recordent_member_profile_div').toggleClass('displayNone_section');
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
        <script type="text/javascript">
            function chargesApplicable(myfield){
            $('#dueAmountExceedError').html('');   
            if($(myfield).val()>0){
                // if(parseInt($(myfield).val()) > parseInt($('#amountduevalidate').val())) {
                    if(parseInt($(myfield).val()) > parseInt($('#due_amount').val())) {
                    $(myfield).val('');
                    $('#dueAmountExceedError').html('Payment amount should not greater than Due amount');
                }
            }
}

function numbersonly(myfield, e,maxlength=null)
        {
            var key;
            var keychar;
            if (window.event)
                key = window.event.keyCode;
            else if (e)
                key = e.which;
            else
                return true;

            keychar = String.fromCharCode(key);
            // control keys
            if ((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) ){
                return true;
            }
            // numbers
            else if ((("0123456789").indexOf(keychar) > -1)){
                return true;
            }
            else{
                return false;
            }
        }
         $("input[name=agree_terms]").on('change',function(){
        if($(this).is(':checked')){
            $(this).parents().parents().parents().parents().find("button[type=submit]").attr('disabled',false);
        }else{
            $(this).parents().parents().parents().parents().find("button[type=submit]").attr('disabled',true);
        }
    });
             $('.makePayment').on('click', function () { 
        var element = $(this);
        var dueId = $(this).data('due-id');
        var dueamount = $(this).data('due-amount');
        $("#pay").find(".modal-body").find('input[name=due_id]').val(dueId);   
        $("#pay").find(".modal-body").find('input[name=due_amount]').val(dueamount);     
               
    });
    $('#pay').find("button[type=reset]").on('click',function(){
      $("#pay").find(".modal-body").find('input[name=due_id]').val('');
      $("#pay").find(".modal-body").find('input[name=pay_amount]').val('');
      $("#pay").find(".modal-body").find("input[name=agree_terms]").prop('checked',false);
      $("#pay").find(".modal-body").find("button[type=submit]").attr('disabled',true);
    });
    
        </script>

        @endsection


