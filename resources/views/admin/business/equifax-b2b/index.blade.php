@extends('voyager::master')
@section('page_title', __('voyager::generic.viewing').' Report')

@section('page_header')
<h1 class="page-title" style="display: none;">
    <i class="voyager-list"></i> US Credit Business Report

</h1>

<style>
	.download-btn i{color:#202f7d;display:inline-block;font-size:0;width:16px;height:20px;background:url(../save-icon.png) no-repeat 0 0;position:absolute;right:30px;top:12px;background-size:cover}
	.download-btn {
	    position: absolute;
	    right: 30px;
	    top: -20px;
	    border: solid 1px #202f7d;
	    color: #202f7d;
	    border-radius: 15px;
	    display: inline-block;
	    padding: 10px 60px 10px 30px;
	    text-decoration: none;
	    font-size: 20px;
	    line-height: 23px;
	    font-weight: 700;
	}
	.page-title2 {
	    display: inline-block;
	    height: auto;
	    font-size: 11px;
	    margin-top: -25px;
	    padding-top: 12px;
	    padding-left: 250px;
	    margin-bottom: 10px;
	    color: #555;
	    font-weight: 400;
	    line-height: 23px;
		align:right;
	}	
</style>

@stop
@section('content')
<!-- New Style added by ROOP -->

<?php 


//$response = json_decode($json_Data, true);

//dd($response);
//echo $response['EfxTransmit']['StandardRequest'][0]['Folder']['IdTrait'][0]['CompanyNameTrait'][0]['BusinessName'];
//echo "YESSSSS";

//echo '<pre>';
//print_r($response['EfxTransmit']['CommercialCreditReport'][0]['Folder']['FirmographicsTrait']);
//die;

	/*dd($result_resp);
	foreach($api_data as $key => $value){
		//echo General::decrypt($value->response);
		echo $value->response;
	}
	die('YESSSSS');
	*/
	
	//dd($response);
	
	
?>

<!-- 	Added by ROOP  -->
<div class="col-md-12 mobile-mr">
			
            <div class="panel panel-bordered">
                <div class="panel-body">
<!-- 							{ ?>
								
								</br>
                                <div class="row">
                                    <div class="col-md-3 col-xs-6 mb-0">
                                        <div class="pdf-logo"><img src="https://www.stage.recordent.com/main_logo.jpg" alt="Logo" data-default="placeholder" data-max-width="300" data-max-height="100"></div>
                                    </div>

                                    <div class="col-md-6">                             
                                        <div class="pdf-downloadbtn"> Sorry! <br> Report Not Created, <br>Insufficient response from Equifax</div>
                                    </div>

                                    <div class="col-md-3">                             
                                        <p class="pdf-date">
										<?php
										echo "Report Date:" ;?>
											
										</p>
                                    </div>
                                </div>
							
							//ELSE CASE WHEN WE HAVE DATA IN REPORT RESPONSE, this else brace will end at the end of all HTML report 
                            <!-- Header Starts  -->
                              <?php if(!isset($error_data)){?>
														 
                                <div class="row equifex_recordentscreen">
                                    <div class="col-md-3 ">
                                        <div class="pdf-logo"><img src="https://www.stage.recordent.com/main_logo.jpg" alt="Logo" data-default="placeholder" data-max-width="300" data-max-height="100"></div>
                                    </div>

                                    <div class="col-md-6">                             
                                        <div class="pdf-downloadbtn">
										<?php
										  echo !empty($business_details->BusinessName) ? $business_details->BusinessName : ' - ';
									    ?>
										
                                    <span style="color: #1e2c76; text-align:center !important;font-weight: 400; font-size: 18px;line-height: 28px;margin: 0px; padding-top:50px !important;"></span>
										
										<span style="color: #000000; text-align:center !important;font-weight: 400; font-size: 18px;line-height: 28px;margin: 0px; padding-top:50px !important;">
											
										</span>
										</div>										
                                    </div>


                                    <div class="col-md-3">
									           
                                        <p class="pdf-date">
										<?php
										echo "Date of Report : ".date('d/m/Y',strtotime($report_date));?>
									 </p>
									 <br class="media-break">
									 <p  class="pdf-date order-number">
										<?php
										echo "Report Number :	 ".  $report_no."&nbsp;&nbsp;&nbsp;";
										?></p>
                                        
                                    </div>
                                </div>
                          
                            <!-- Header Ends  -->
                            <!-- Donut Chart   -->
                                <div class="row equifex_recordentscreen">
									
									<?php
                                        $score_percentage = ($score_value/10)*100;
										if($score_percentage>=1 && $score_percentage<=20 ){
											$scale_remark = 'Excellent';
											$scale_color = '#82e360';
										} elseif($score_percentage>=30 && $score_percentage<=40 ){
											$scale_remark = 'Good';
											$scale_color = '#f5d13d'; 
										}elseif($score_percentage>=50 && $score_percentage<=70 ){
											$scale_remark = 'Fair';
											$scale_color = '#ffb36c'; 
										}elseif($score_percentage>=71 && $score_percentage<=100 ) {
											$scale_remark = 'Needs Improvement';
											$scale_color = '#ff6c6c'; 
										}else{
											$scale_remark = 'Not Available';
											$scale_color = '#ff6c6c';
										}
									?>										
                                    <div class="col-md-12 mt-mb">
										<div class="donutchart">
										<!-- <span style="color: #1e2c76; text-align:center !important;font-weight: 400; font-size: 18px;line-height: 28px;margin: 0px; padding-top:50px !important;">Business Name B2B</span> -->
											<canvas id="chDonut"></canvas>
											<div class="pie-value-txt" style="color: #000000;font-size: 20px;line-height: 28px; font-weight: 400 !important;padding-top: 20px;">
												<span class="pie-value"><?php if($score_value!=0){
												 echo $score_value;
												}else{
													echo "";
												}   ?></span><br/>
												{{$scale_remark}}
											</div>
										</div>								
                                    </div>
                                    <div class="col-md-2"></div>
                                    <div class="col-md-8 mt-mb">
									<div class="rc_mid">										 
										  <h5 class="title_imporve" style="color: #000000;font-weight: 400;font-size: 18px;line-height: 28px;">
											  <?php 
											 if(!empty($score_value)){
											     echo $scale_remark;
											 }else{
													
													echo '<span style"color: #000000;font-size: 16px; font-weight: 600;"><strong>Score</strong></span>';
												}
											  ?>
										  </h5>
									</div>									
										
									<div class="profress-scroll">
										<div class="progress rc_progress">
											<?php if($score_value!=0) { ?>
												<div id="progress-bar-active-score" class="progress-bar-act" role="progressbar" style="right:{{$score_percentage}}%; background-color:{{$scale_color}}"></div>
												<?php 
											}
											?>
											
											<div class="progress-bar progress-bar-danger" role="progressbar">
												<span class="lp ten" style="left: -5px;">10</span>
												<span class="rp nine" style="left: -84px;">9</span><span class="rp eight" style="right: 76px;" >8</span> 
											</div>
											
											<div class="progress-bar progress-bar-warning" role="progressbar">
												<span class="lp seven" style="left: -4px;">7</span><span class="lp six" style="left: 64px;">6</span><span class="rp five" style="left: 64px;">5</span></div>
											
											<div class="progress-bar progress-bar-info" role="progressbar">
												<span class="lp four" style="left: -11px;">4</span> <span class="rp three" style="left: -8px;" >3</span></div>	
											<div class="progress-bar progress-bar-success" role="progressbar">
												<span class="lp two" style="left: -4px;">2</span> <span class="rp one" style="left: -10px;" >1</span></div>
										</div>
										<p style="text-align:right;">
											<h2 class="page-title2"><img src="https://www.test.recordent.com/front_new/images/team/equifaxlogo.svg" border="0" height="50px" width="250px"></h2>
										</p>
									</div>
                                       
                                    </div>
                                </div>
                                 <?php } else {  ?>
                      
				                <div class="row equifex_recordentscreen">
				                    <center class="no-records">No report found!</center>
				                </div>
				            <?php }?>
				            <?php  if(isset($user['business_name_rec'])){?>

                            <!-- Donut Ends   -->
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
               
			                <?php } else {  ?>
                          <div style="display: none;" class="recordentscreen">
                    <center class="no-records">No report found!</center>
                </div>
               
            <?php }?>
                

                            <div class="download_btn active_none">
                   			  <div class="togle_buttons">
                        		@if((!isset($response) || empty($response)) && count($records) > 0)
		        	                    <a href="javascript:void(0)" class="equifax-active active">Equifax</a>
                       				 <a href="javascript:void(0)" class="recordent-active">Recordent</a>

			                        <script>
			                            $('.recordentscreen').css('display', 'block');
			                            $('.equifex_recordentscreen').css('display', 'none');
			                        </script>

		                        @else
		                         <a href="javascript:void(0)" class="equifax-active active">Equifax</a>
		                        <a href="javascript:void(0)" class="recordent-active">Recordent</a>
		                       
		                        @endif
                    		  </div>
			                    @if((!isset($response) || empty($response)) && count($records) == 0)
			                    	<a disabled class="btn_d" href="#">Download <i class="glyphicon glyphicon-save-file"></i></a>
			                    @else
			                    	<a target="_blank" class="btn_d" href="{{route('admin.india-b2b.business.report.download.pdf', ['c_id' => $c_id])}}">Download <i class="glyphicon glyphicon-save-file"></i></a>
			                    @endif
			                </div>

			                <h4 class="reportSum active_none"><span>Report Summary</span></h4>
			                <?php if(!isset($error_data)){?>
							
                            <!-- Enquiry Match & Head Quarter   -->
                                <div class="row equifex_recordentscreen">
                                   <div class="col-md-12 publicdeeds" style="margin-top: 0px !important;">
                                      <table id="publicdeeds">
                                          <tr>
                                              <th>Business Details</th>
                                          </tr>
                                      </table>
                                      <!-- </div> -->
                                   	  <div class="col-md-6 customers customers-data" style="padding-left: 0px !important;">
                                         <table id="publicdeeds">
                                           <!--  <tr>
                                            <th colspan="2">Enquiry Match
											</th>                                  
                                            </tr> -->
											<tr>
                                                <td style="width: 45%;">Business Name</td>
												<td style="width: 55%;">
												  <?php
												   echo !empty($business_details->BusinessName) ? $business_details->BusinessName : ' - ';
											  	  ?>
											    </td>
                                            </tr>
                                            <tr>
												<td>Business Short Name</td>
												<td>
												<?php  echo !empty($business_details->BusinessShortName) ? $business_details->BusinessShortName : ' - ';?>
												</td>
                                            </tr>
                                            <tr>
												<td>Business Category</td>
												<td>
												<?php 
												echo !empty($business_details->BusinessCategory) ? $business_details->BusinessCategory : ' - ';
												?>
												</td>
                                            </tr>
                                            <tr>
												<td>Business Industry Type</td>
												<td>
													<?php
													echo !empty($business_details->BusinessIndustryType) ? $business_details->BusinessIndustryType : ' - ';
													?>
												</td>
                                            </tr>
                                            
											<tr>
												<td>Date of Incorporation</td>
												<td> 
													<?php 
													echo !empty($business_details->DateIncorporation) ? 
                                                    date('d-m-Y',strtotime($business_details->DateIncorporation))  : ' - ';
													?>
												</td>
											</tr>
											<tr>
											  <td>Legal Constitution:</td>
											  <td> 
											   <?php 
											   echo !empty($business_details->BusinessLegalConstitution) ? $business_details->BusinessLegalConstitution : ' - ';
											   ?>
											  </td>
											</tr>
												
											<tr>												
											  <td>Sales Figure:</td>
											  <td><?php 
												echo !empty($business_details->SalesFigure) ? $business_details->SalesFigure : ' - ';
												?>
											  </td>
											</tr>
											
											<tr>
												<td>Class of Activity:</td>
												<td><?php
												echo !empty($business_details->ClassActivity) ? $business_details->ClassActivity : ' - ';?>
												</td>
											</tr>											
                                            <tr>
												<td>Employee count:</td>
												<td><?php echo !empty($business_details->EmployeeCount) ? $business_details->EmployeeCount : ' - '; ?> </td>
                                            </tr>
                                         </table>
                                      </div>
									
                                       <div class="col-md-6 customers customers-id" style="padding-right: 0px !important;">
	                                        <table id="publicdeeds">
	                                          <!-- <tr>
												<th colspan="2" >Headquarters Site</th>
											  </tr> -->
											      <td style="width: 45%;border-top:none;">CIN:</td>
											      <td style="width: 55%;border-top: none;"><?php echo !empty($cin_details->IdNumber) ? $cin_details->IdNumber : ' - '; ?></td>
											
		                                          <tr>
													<td>TIN: </td>
													<td><?php echo !empty($tin_details->IdNumber) ? $tin_details->IdNumber : ' - '; ?></td>
		                                          </tr>
		                                          <tr>
													<td>PAN:</td>
													<td> <?php echo !empty($pan_details->IdNumber) ? $pan_details->IdNumber : ' - '; ?>
													 </td>
		                                          </tr>
												  <tr>
													<td>Service Tax Number:</td>
													<td> <?php echo !empty($service_tax_details->IdNumber) ? $service_tax_details->IdNumber : ' - '; ?> </td>
		                                          </tr>
		                                          <tr>
													<td>Business Registration Date:</td>
													<td><?php echo !empty($business_details->DateIncorporation) ? 
                                                    	date('d-m-Y',strtotime($business_details->DateIncorporation))  : ' - ';
													?>
													</td>
		                                          </tr>
		                                          <tr>
													<td>Company Registration Number:</td>
													<td><?php
															echo !empty($business_registration_no->IdNumber) ? $business_registration_no->IdNumber : ' - '; 
														?>
													</td>
		                                          </tr>
		                                          <tr>
												   <td>Phone :</td>
												   <td><?php 
												   $checkCountContacts = [];
													foreach ($contact_details as $key => $value) {
														if($value['typeCode']=='L' || $value['typeCode']=='O'){
															$checkCountContacts[] = $value['typeCode'];	
														 }
														if($value['typeCode']=='L'){
															echo !empty($value['Number']) ? $value['Number'] : ' - ';
															break;
														}
														$addComma = count($checkCountContacts)>1 ? ",":""; 
														$addOthers = !empty($value['Number']) ? $value['Number'] : ' - ';
														if ($value['typeCode']=='O') {
															echo $addComma.$addOthers;
															break;
															
														}
													}
													if(count($checkCountContacts)==0) {
														echo "-";
													}
													?> </td>
	                                              </tr>
			                                      <tr>
													<td>Mobile :</td>
													<td><?php
													$checkCountContacts = []; 
													foreach ($contact_details as $key => $value) {

														if($value['typeCode']=='M'){
															$checkCountContacts[] = $value['typeCode'];
															echo !empty($value['Number']) ? $value['Number'] : ' - ';
															break;
														} 
													}
													if(count($checkCountContacts)==0) {
														echo "-";
													}
													?></td>
			                                      </tr>
			                                      <tr>
			                                        <td>Fax :</td>
			                                        <td><?php 
			                                        	$checkCountContacts = []; 
													foreach ($contact_details as $key => $value) {
														if($value['typeCode']=='F'){
															$checkCountContacts[] = $value['typeCode'];
															echo !empty($value['Number']) ? $value['Number'] : ' - ';
															break;
														} 
													}
													if(count($checkCountContacts)==0) {
														echo "-";
													}?> </td>
			                                      </tr>
	                                        </table>
                                       </div>
                                   </div>
                                </div>
                            

                            <!-- Enquiry Match & Head Quarter Ends  -->
                            <!-- Report High lights -->
                            <div class="row equifex_recordentscreen">
                                <div class="col-md-12 publicdeeds2" style="    margin-bottom: 1px !important;    margin-top: 0px !important;">
                                    <table id="publicdeeds2">
                                    <tr>
                                        <th colspan="7">Related Entities</th>                                  
                                    </tr>
                                    <tr>
                                        <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">Name <?php 
                                        // echo General::getFormatedDate($response_folder['ReportAttributes']['NonFinancialSummary']['SummaryAttributes']['CreditActiveSince']); ?> </td>
                                        
                                        <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">Address <?php 
                                        // echo General::getFormatedDate($response_folder['ReportAttributes']['NonFinancialSummary']['SummaryAttributes']['CreditActiveSince']); ?> </td>
                                        <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">Incorporation Date <?php 
                                        // echo General::getFormatedDate($response_folder['ReportAttributes']['NonFinancialSummary']['SummaryAttributes']['CreditActiveSince']); ?> </td>
                                        <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">CIN <?php 
                                        // echo General::getFormatedDate($response_folder['ReportAttributes']['NonFinancialSummary']['SummaryAttributes']['CreditActiveSince']); ?> </td>
                                        <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">TIN <?php 
                                        // echo General::getFormatedDate($response['EfxTransmit']['CommercialCreditReport'][0]['Header']['DateOfRequest']); ?></td>
                                        <td style="background-color:#f2c50c; text-align: left; font-weight: 600;">PAN <?php 
                                        // echo General::getFormatedDate($response['EfxTransmit']['CommercialCreditReport'][0]['Header']['DateOfRequest']); ?></td>
                                        <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">Relationship <?php 
                                        // echo General::getFormatedDate($response['EfxTransmit']['CommercialCreditReport'][0]['Header']['DateOfRequest']); ?></td>
                                    </tr>
									
									  <?php 
                                        if(isset($RelationshipDetails)){
                                        	foreach ($RelationshipDetails as $key => $value) {?>
                                    <tr>
                                        <td style="text-align: center;"><?php echo !empty($value['business_entity_name']) ? $value['business_entity_name']:'-';?></td>
									     <td style="text-align: center;"><?php echo !empty($value['CommercialAddressInfo'][0]['Address']) ? $value['CommercialAddressInfo'][0]['Address'] : '-';?></td>
										 <td style="text-align: center;"><?php echo !empty($value['date_of_incorporation']) ? date('d-m-Y',strtotime($value['date_of_incorporation'])):'-';?></td> 
										 <td style="text-align: center;"><?php echo !empty($value['IdentityInfo']['CIN'][0])? $value['IdentityInfo']['CIN'][0] :'-';?></td> 
										 <td style="text-align: center;"><?php  echo !empty($value['IdentityInfo']['TIN'][0])? $value['IdentityInfo']['CIN'][0] :'-';?></td> 
										 <td style="text-align: center;"><?php  echo !empty($value['IdentityInfo']['PANId'][0])? $value['IdentityInfo']['CIN'][0] :'-';?></td> 
										 <td style="text-align: center;"><?php echo "Proprietor"?></td>
									</tr>
									<?php }} else {?>
									
									 <tr>
                                        <td colspan="7" style="text-align: center;color: red;font-size: 17px;">
                                        	<?php echo "No Related Entities Reported to Equifax"?>
										</td>
									    <!-- <td style="text-align: center;"></td>
										<td style="text-align: center;"></td>
										<td style="text-align: center;"></td>
										<td style="text-align: center;"></td>
										<td style="text-align: center;"></td>
										<td style="text-align: center;"></td> -->
									</tr>
								<?php }?>
									
                                </table>
                                </div>
                            </div>

                            <div class="row equifex_recordentscreen">
                                <div class="col-md-12 publicdeeds2" style="margin-top: 0px;">
                                    <table id="publicdeeds2">
                                    <tr>
                                        <th colspan="5">Related Individuals</th>                                  
                                    </tr>
                                    <tr>
                                        <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">Name <?php 
                                        // echo General::getFormatedDate($response_folder['ReportAttributes']['NonFinancialSummary']['SummaryAttributes']['CreditActiveSince']); ?> </td>
                                        
                                        <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">Address <?php 
                                        // echo General::getFormatedDate($response_folder['ReportAttributes']['NonFinancialSummary']['SummaryAttributes']['CreditActiveSince']); ?> </td>
                                        <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">ID <?php 
                                        // echo General::getFormatedDate($response['EfxTransmit']['CommercialCreditReport'][0]['Header']['DateOfRequest']); ?></td>
                                        <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">Phone <?php 
                                        // echo General::getFormatedDate($response['EfxTransmit']['CommercialCreditReport'][0]['Header']['DateOfRequest']); ?></td>
                                        <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">Relationship <?php 
                                        // echo General::getFormatedDate($response['EfxTransmit']['CommercialCreditReport'][0]['Header']['DateOfRequest']); ?></td>
                                    </tr>
									
									 <tr>
                                        <td colspan="5" style="text-align: center;color: red;font-size: 17px;">
                                        <?php echo "No Related Individuals Reported to Equifax"?>
										</td>
									   <!--  <td style="text-align: center;"></td>
										<td style="text-align: center;"></td>
										<td style="text-align: center;"></td>
										<td style="text-align: center;"></td> -->
									</tr>
                                    </table>
                                </div>
                            </div>

                            <div class="row equifex_recordentscreen">
                                <div class="col-md-12 reportdata" style="margin-top: 0px;">
                                    <table id="reportdata">
	                                    <tr>
	                                       <th colspan="4">Report Highlights (Last 3 years)</th>
	                                    </tr>
	                                    <tr>
	                                       
	                                        <th colspan="4" style="width: 15%; background-color:#f2c50c; text-align: center; font-weight: 600;color: #222222">Availed by <?php echo !empty($business_details->BusinessName) ? $business_details->BusinessName : ' ';
									    ?></th>
	                                    </tr>
	                                     <!-- </table> -->
	                                        <!-- <div class="col-md-12 reportdata"> -->
	                                        <!-- <table id="reportdata"> -->
										
										<tr style=" border-bottom: 1px solid #a99f9f;">
                                            <td style="font-size: 17px !important;font-weight: 700;  border-right: solid 1px #a99f9f;text-align: center;">Details</td>
                                            <td style="font-size: 17px !important;font-weight: 700;border-right: solid 1px #a99f9f;text-align: center;"><!-- Most Recent Year Value -->
                                               <?php echo "FY ".!empty($overallcreditsummary_keys[0])? $overallcreditsummary_keys[0] : '-';?>
                                            </td>
                                            <td style="font-size: 17px !important;font-weight: 700;border-right: solid 1px #a99f9f;text-align: center;"><!-- Previous Year Value --><?php echo "FY ".!empty($overallcreditsummary_keys[1])? $overallcreditsummary_keys[1] : '-';?></td>
                                            <td style="font-size: 17px !important;font-weight: 700;text-align: center;"><!-- Most Latest Year Value --><?php echo "FY ".!empty($overallcreditsummary_keys[2])? $overallcreditsummary_keys[2] : '-';?></td>
                                        </tr>
                                        <tr>
                                             <td style="border-right: solid 1px #a99f9f;text-align: center;">Total Accounts :</td>
                                             <td style="border-right: solid 1px #a99f9f;text-align: center;"><?php 
                                             echo !empty($overallcreditsummary_borrower->a->CF_Count) ? $overallcreditsummary_borrower->a->CF_Count : ' - ';?></td>
                                             <td style="border-right: solid 1px #a99f9f;text-align:center;"><?php
                                             echo !empty($overallcreditsummary_borrower->b->CF_Count) ? $overallcreditsummary_borrower->b->CF_Count : ' - ';?></td>
                                             <td style="text-align:center;"><?php
                                             echo !empty($overallcreditsummary_borrower->c->CF_Count) ? $overallcreditsummary_borrower->c->CF_Count : '-';?></td>
                                        </tr>
                                        <tr>
                                             <td style="border-right: solid 1px #a99f9f;text-align: center;">New Accounts Opened : </td>
                                             <td style="border-right: solid 1px #a99f9f;text-align:center;"><?php 
                                             echo !empty($overallcreditsummary_borrower->a->OpenCF_Count) ? $overallcreditsummary_borrower->a->OpenCF_Count : ' - ';?></td>
                                             <td style="border-right: solid 1px #a99f9f;text-align:center;"><?php
                                             echo !empty($overallcreditsummary_borrower->b->OpenCF_Count) ? $overallcreditsummary_borrower->b->OpenCF_Count : ' - ';?></td>
                                             <td style="text-align:center;"><?php
                                             echo !empty($overallcreditsummary_borrower->c->OpenCF_Count) ? $overallcreditsummary_borrower->c->OpenCF_Count : ' - ';?></td>
                                        </tr>
                                        <tr>
                                             <td style="border-right: solid 1px #a99f9f;text-align: center;">Term Loans Closed :</td>
                                             <td style="border-right: solid 1px #a99f9f;text-align:center;"><?php echo '-';?></td>
                                             <td style="border-right: solid 1px #a99f9f;text-align:center;"><?php echo '-';?></td>
                                             <td style="text-align:center;"><?php echo '-';?></td>
                                        </tr>
                                        <tr>
                                             <td style="border-right: solid 1px #a99f9f;text-align: center;">Credit Utilization (Open Accounts)</td>
                                             <td style="border-right: solid 1px #a99f9f;text-align:center;"><?php
                                             if(!empty($overallcreditsummary_borrower->a->CurrentBalanceOpenCF_Sum)){
                                               if($overallcreditsummary_borrower->a->CurrentBalanceOpenCF_Sum != '0' and $overallcreditsummary_borrower->a->SanctionedAmtOpenCF_Sum != '0') {
                                                 echo round((($overallcreditsummary_borrower->a->CurrentBalanceOpenCF_Sum)/($overallcreditsummary_borrower->a->SanctionedAmtOpenCF_Sum)) * 100,0,PHP_ROUND_HALF_UP).'<span>'.'%'.'</span>';     
                                            } else {
                                             echo "-";
                                            }
                                          } else {
                                             echo "-";
                                          }
                                             ?></td>
                                             <td style="border-right: solid 1px #a99f9f;text-align:center;"><?php
                                             if(!empty($overallcreditsummary_borrower->b->CurrentBalanceOpenCF_Sum)){
                                                  // $overallcreditsummary_borrower->b->CurrentBalanceOpenCF_Sum = 0;
                                                  // $overallcreditsummary_borrower->b->SanctionedAmtOpenCF_Sum = 0;
                                                  if($overallcreditsummary_borrower->b->CurrentBalanceOpenCF_Sum != '0' and $overallcreditsummary_borrower->b->SanctionedAmtOpenCF_Sum != '0') {
                                                  echo round((($overallcreditsummary_borrower->b->CurrentBalanceOpenCF_Sum)/($overallcreditsummary_borrower->b->SanctionedAmtOpenCF_Sum)) * 100,0,PHP_ROUND_HALF_UP).'<span>'.'%'.'</span>';
                                               } else {
                                                  echo '-';
                                               }
                                             } else {
                                                  echo '-';
                                             }
                                            
                                             ?></td>
                                             <td style="text-align:center;"><?php
                                             if(!empty($overallcreditsummary_borrower->c->CurrentBalanceOpenCF_Sum)){
                                                  // $overallcreditsummary_borrower->b->CurrentBalanceOpenCF_Sum = 0;
                                                  // $overallcreditsummary_borrower->b->SanctionedAmtOpenCF_Sum = 0;
                                                  if($overallcreditsummary_borrower->c->CurrentBalanceOpenCF_Sum != '0' and $overallcreditsummary_borrower->c->SanctionedAmtOpenCF_Sum != '0') {
                                                  echo round((($overallcreditsummary_borrower->c->CurrentBalanceOpenCF_Sum)/($overallcreditsummary_borrower->c->SanctionedAmtOpenCF_Sum)) * 100,0,PHP_ROUND_HALF_UP).'<span>'.'%'.'</span>';
                                               } else {
                                                  echo '-';
                                               }
                                             } else {
                                                  echo '-';
                                             } ?></td>
                                        </tr>
                                        <tr>
                                             <td style="border-right: solid 1px #a99f9f;text-align: center;">Accounts Overdue</td>
                                             <td style="border-right: solid 1px #a99f9f;text-align:center;"><?php echo !empty($overallcreditsummary_borrower->a->OverdueCFInFY_Count) ? $overallcreditsummary_borrower->a->OverdueCFInFY_Count : ' - ';?></td>
                                             <td style="border-right: solid 1px #a99f9f;text-align:center;"><?php echo !empty($overallcreditsummary_borrower->b->OverdueCFInFY_Count) ? $overallcreditsummary_borrower->b->OverdueCFInFY_Count : ' - ';?></td>
                                             <td style="text-align:center;"><?php echo !empty($overallcreditsummary_borrower->c->OverdueCFInFY_Count) ? $overallcreditsummary_borrower->c->OverdueCFInFY_Count : ' - ';?></td>
                                        </tr>
                                        <tr>
                                             <td style="border-right: solid 1px #a99f9f;text-align: center;">Most Severe Status</td>
                                             <td style="border-right: solid 1px #a99f9f;text-align:center;"><?php echo '-';?></td>
                                             <td style="border-right: solid 1px #a99f9f;text-align:center;"><?php echo '-';?></td>
                                             <td style="text-align:center;"><?php echo '-';?></td>
                                        </tr>
                                        <tr>
                                             <td style="border-right: solid 1px #a99f9f;text-align: center;">Highest Overdue Amount</td>
                                             <td style="border-right: solid 1px #a99f9f;text-align:center;"><?php echo !empty($overallcreditsummary_borrower->a->HighestOverdueAmt) ? "₹".$overallcreditsummary_borrower->a->HighestOverdueAmt : ' - ';?></td>
                                             <td style="border-right: solid 1px #a99f9f;text-align:center;"><?php echo !empty($overallcreditsummary_borrower->b->HighestOverdueAmt) ? "₹".$overallcreditsummary_borrower->b->HighestOverdueAmt : ' - ';?></td>
                                             <td style="text-align:center;"><?php echo !empty($overallcreditsummary_borrower->c->HighestOverdueAmt) ? "₹".$overallcreditsummary_borrower->c->HighestOverdueAmt : ' - ';?></td>
                                        </tr>
									</table>
									<br>
									  <!-- </div> -->

								    <!-- <div class="col-md-12 reportdata"> -->
								    
								</div>
							</div>	
                           
							
																

                               
                            <div class="row equifex_recordentscreen">
                                <div class="col-md-12 publicdeeds2" style="margin-top: 0px;">
                                   <table id="publicdeeds2">
                                    <tr>
                                        <th colspan="8">Overall Report Summary</th>                                  
                                    </tr>
                                    <tr>
                                        <td style="width: 16%; background-color:#f2c50c; text-align: center; font-weight: 600; ">Credit Facilities availed by <?php echo !empty($business_details->BusinessName) ? $business_details->BusinessName : ' ';
									    ?></td>
                                      </tr>
                                    </table>
                                  </div>
                            </div>  
							<?php
									
									//array of account open dates.
									if(!empty($arrayAccOpenDate)){
										
									$date_arr =$arrayAccOpenDate;
									for ($i = 0; $i < count($date_arr); $i++)
									{
										if ($i == 0)
										{
											$max_date = date('Y-m-d H:i:s', strtotime($date_arr[$i]));
											$min_date = date('Y-m-d H:i:s', strtotime($date_arr[$i]));
										}
										else if ($i != 0)
										{
											$new_date = date('Y-m-d H:i:s', strtotime($date_arr[$i]));
											if ($new_date > $max_date)
											{
												$max_date = $new_date;
											}
											else if ($new_date < $min_date)
											{
												$min_date = $new_date;
											}
										}
									}
									//echo date('d-m-Y',strtotime($max_date));
									//echo date('d-m-Y',strtotime($min_date));
							}?>
								
                            <div class="row justify-content-lg-start justify-content-center equifex_recordentscreen">
                                
                                <div class="col-md-4">
                                    <div class="statistics_item">
                                    <h3 class="counter">
									
									<?php
										if(!empty($credit_age)){
											echo $credit_age;
											if($credit_age==1){
												echo ' yr';
											}
												else{
													echo " yrs";
												}
											// echo  '<span>'.$credit_age == 1 ? 'yr':' yrs'.'</span>';
										}else{
											echo "0";
										}
												
									?></h3>
                                    <p>Credit Age</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="statistics_item">
                                    <h3 class="counter">
										<?php
											
												if(!empty($credit_usage)){
												echo round($credit_usage,0,PHP_ROUND_HALF_UP).'<span>'.'%'.'</span>';	
												}
												else {
													echo '-';
												}
												
										?>
									</h3>
                                    <p>Credit Usage</p>
                                    </div>
                                </div>
								<div class="col-md-4">
                                    <div class="statistics_item">
                                    <h3 class="counter">
									<?php 
									 // echo $credit_usage; "%";
									echo !empty($total_enquiries) ? $total_enquiries : ' 0 ';
									?>
									</h3>
                                    <p>Enquires</p>
                                    </div>
                                </div>
                           <!--  </div>
							
                            <div class="row justify-content-lg-start justify-content-center"> -->
                                
                                <div class="col-md-4">
                                    <div class="statistics_item">
                                    <h3 class="counter">
										<?php
											
											$avgPayCnt = "";											
											if(!empty($payment_score)){
												echo round($payment_score,0,PHP_ROUND_HALF_UP).'<span>'.'%'.'</span>';	
												}
												else {
													echo '0';
												}
										?>
									</h3>
                                    <p>Payment Score</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="statistics_item">
                                    <h3 class="counter"><?php echo !empty($total_account) ? $total_account : ' 0 '; ?></h3>
                                    <p>Total Accounts</p>
                                    </div>
                                </div>
                            </div>

                             <?php
				                    $paymentStatusOnTimeArray = ['000', '*', 'STD', 'NEW', 'CLSD', 'OPEN', 'RES','NS','1000'];
				                    $paymentStatusLateArray = ['01+', '31+', '61+','SUB','SMA','SMA 0','SMA 1','SMA 2','1001','1002-1089','FPD'];
				                    $paymentStatusVeryLateTimeArray = ['91+','121+','181+', '360+', '540+', '720+','DBT','LOS','DBT 1','DBT 2','DBT 3','NPA','1090-1999', 'SET', 'WOF', 'POWS', 'INV', 'DEV', 'RNC','RGM','RNC','SF','WDF','SFR','SFWD','SFWO','SWDW','TP','DI','ED'];
				                    ?>

				                    <?php
				                    $openHistoryAccountFlag = false;
				                    $closedHistoryAccountFlag = false;
				                    ?>

	                       <?php foreach ($credit_facility as  $value) {?>    	
                            
                            <div class="row equifex_recordentscreen">
                               <div class="col-md-12 reportdata" style="margin-top: 0px;">
                                    <table id="reportdata">
	                                    <tr>
	                                        <th colspan="4">Details of Credit Facilities</th>  
	                                    </tr>
	                                    <tr>
	                                        <th colspan="4" style="width: 15%; background-color:#f2c50c; text-align: center; font-weight: 600;color: #222222">Availed by <?php echo !empty($business_details->BusinessName) ? $business_details->BusinessName : ' ';
									    ?></th>
	                                    </tr>
	                                     <!-- </table> -->
	                                        <!-- <div class="col-md-12 reportdata"> -->
	                                        <!-- <table id="reportdata"> -->
												<div class="col-md-4">
                                                                   <tr>
                                                            <td style="border-right: solid 1px #a99f9f;">Lender Name : <?php echo '****';?></td>
                                                            <td style="border-right: solid 1px #a99f9f;">Account Number : <?php 
                                                            $last_four_digits=4;
                                                                // echo !empty($value['account_number']) ? substr_replace($value['account_number'], str_repeat("X", strlen($value['account_number']) - $last_four_digits), 0, strlen($value['account_number']) - $last_four_digits) : ' - ';
                                                            // echo !empty($value['account_number']) ? $value['account_number'] : ' - ';
                                                            echo "****";
                                                                ?>
                                                            </td>
                                                            <td>Account Type : <?php 
                                                                echo !empty($value['credit_type']) ? $value['credit_type'] : ' - ';?></td>
                                                        </tr>
                                                  </div>
                                                   <div class="col-md-4">
                                                         <tr>
                                                            <td style="border-right: solid 1px #a99f9f;">Sanctioned Amount :  <?php 
                                                                echo !empty($value['sanctioned_amount_notional_amountofcontract']) ? "₹".number_format($value['sanctioned_amount_notional_amountofcontract']) : ' - ';?></td>
                                                            <td style="border-right: solid 1px #a99f9f;">Drawing Power : <?php 
                                                                echo !empty($value['drawing_power']) ? "₹".number_format($value['drawing_power']) : ' - ';?></td>
                                                            <td>Current Balance : <?php 
                                                                echo !empty($value['current_balance_limit_utilized_marktomarket']) ? "₹".number_format($value['current_balance_limit_utilized_marktomarket']) : ' - ';?></td>
                                                       </tr>
                                                   </div>
                                                  <div class="col-md-4">
                                                       <tr>
                                                          <td style="border-right: solid 1px #a99f9f;">High Credit : <?php 
                                                                echo !empty($value['high_credit']) ? "₹".number_format($value['high_credit']) : ' - ';?></td>
                                                          <td style="border-right: solid 1px #a99f9f;">Gurantee Coverage : <?php 
                                                                echo !empty($value['guarantee_coverage']) ? $value['guarantee_coverage'] : ' - ';?></td>
                                                          <td>Tenure : <?php 
                                                                echo !empty($value['tenure_weighted_avg_maturityperiod']) ? $value['tenure_weighted_avg_maturityperiod'].' months' : ' - ';?></td>
                                                       </tr>
                                                  </div>
                                                  <div class="col-md-4">
                                                        <tr>
                                                           <td style="border-right: solid 1px #a99f9f;">Date Opened : <?php 
                                                                echo !empty($value['sanctiondate_loanactivation']) ? 
                                                                date('d-m-Y',strtotime($value['sanctiondate_loanactivation'])) : ' - ';?></td>
                                                           <td style="border-right: solid 1px #a99f9f;">Loan Renewal Date : <?php 
                                                                echo !empty($value['loan_renewal_date']) ?
                                                                date('d-m-Y',strtotime($value['loan_renewal_date'])) : ' - ';?></td>
                                                           <td>Loan End Date : <?php 
                                                                echo !empty($value['loan_expiry_maturity_date']) ? date('d-m-Y',strtotime($value['loan_expiry_maturity_date'])): ' - ';?></td>
                                                        </tr>
                                                  </div>
                                                   <div class="col-md-4">
                                                         <tr>
                                                            <td style="border-right: solid 1px #a99f9f;">Last Payment Date : <?php echo !empty($value['dt_reported_lst']) ? 
                                                                date('d-m-Y',strtotime($value['dt_reported_lst'])): ' - ';?></td>
                                                            <td style="border-right: solid 1px #a99f9f;">Date Reported : <?php 
                                                                echo !empty($value['dt_reported_lst']) ? 
                                                                date('d-m-Y',strtotime($value['dt_reported_lst'])): ' - ';?></td>
                                                            <td>Dispute Code : <?php echo '-';?></td>
                                                        </tr>
                                                   </div>
                                                   <div class="col-md-4">
                                                         <tr>
                                                            <td style="border-right: solid 1px #a99f9f;">Account Status : <?php 
                                                                echo !empty($value['account_status']) ? $value['account_status'] : ' - ';?></td>
                                                            <td style="border-right: solid 1px #a99f9f;">Suit Filed Status :
                                                             <?php                                               echo !empty($value['suit_filed_status']) ? $value['suit_filed_status'] : ' - ';?></td>
                                                            <td>Wilful Default Status : <?php 
                                                                echo !empty($value['wilful_default_status']) ? $value['wilful_default_status'] : ' - ';?></td>
                                                        </tr>
                                                  </div>
                                                   <div class="col-md-4">
                                                         <tr>
                                                            <td style="border-right: solid 1px #a99f9f;">Status Date : <?php 
                                                                echo !empty($value['account_status_dt']) ? 
                                                                date('d-m-Y',strtotime($value['account_status_dt'])): ' - ';?></td>
                                                            <td style="border-right: solid 1px #a99f9f;">Suit Filed Date :
                                                             <?php 
                                                            echo !empty($value['date_of_suit']) ? date('d-m-Y',strtotime($value['date_of_suit'])): ' - ';
                                                         ?></td>
                                                            <td>Wilful Default Date : <?php echo '-';?></td>
                                                        </tr>
                                                   </div>
                                                   <div class="col-md-4">
                                                         <tr>
                                                            <td style="border-right: solid 1px #a99f9f;">Past Due Amount : <?php echo '-';?></td>
                                                            <td style="border-right: solid 1px #a99f9f;">Settlement Amount : <?php echo !empty($value['settled_amount']) ? "₹".number_format($value['settled_amount']) : '-';?></td>
                                                            <td>Written Off Amount : <?php echo !empty($value['written_off_amount']) ? "₹".number_format($value['written_off_amount']) : '-';?></td>
                                                        </tr>
                                                   </div>
                                                   <div class="col-md-4">
                                                         <tr>
                                                            <td style="border-right: solid 1px #a99f9f;">Monthly Payment Amount :
                                                         <?php 
                                                            echo !empty($value['installment_amount']) ? "₹".number_format($value['installment_amount']) : '-';
                                                         
                                                            ?>
                                                            </td>
                                                            <td style="border-right: solid 1px #a99f9f;">Repayment Frequency : 
                                                                 <?php  if(!empty($value['repayment_frequency'])){
                                                              if($value['repayment_frequency']==1){
                                                                 echo "Weekly";
                                                              } elseif ($value['repayment_frequency']==2) {
                                                                  echo "Fortnightly";
                                                              }
                                                              elseif ($value['repayment_frequency']==3) {
                                                                  echo "Monthly";
                                                              }
                                                              elseif ($value['repayment_frequency']==4) {
                                                                  echo "Quarterly";
                                                              } else {
                                                                 echo "-";
                                                              }
                                                                 } else {
                                                                      echo "-";
                                                                 }
                                                                  ?></td>
                                                            <td>Restructuring Reason :
                                                            <?php 
                                                             if(!empty($value['major_reasons_for_restructuring'])){
                                                              if($value['major_reasons_for_restructuring']==01){
                                                                 echo "Restructured due to Non- Performance";
                                                              } elseif ($value['major_reasons_for_restructuring']==02) {
                                                                  echo "Restructured due to Natural Calamity";
                                                              }
                                                              elseif ($value['major_reasons_for_restructuring']==99) {
                                                                  echo "Others";
                                                              } else {
                                                                 echo "-";
                                                              }
                                                                 } else {
                                                                      echo "-";
                                                                 }  ?></td>
                                                        </tr>
                                                   </div>
                                                   <div class="col-md-4">
                                                         <tr>
                                                            <td style="border-right: solid 1px #a99f9f;">Amount of NPA Contracts : <?php echo !empty($value['amount_of_contracts_classified_npa']) ? "₹".$value['amount_of_contracts_classified_npa'] : ' - ';?></td>
                                                            <td style="border-right: solid 1px #a99f9f;">NOARC : <?php echo !empty($value['notional_amount_outstanding_restructured_contracts']) ? $value['notional_amount_outstanding_restructured_contracts'] : ' - ';?></td>
                                                            <td>Asset Based Security Coverage : <?php 
                                                                echo !empty($value['asset_based_security_coverage']) ? $value['asset_based_security_coverage'] : ' - ';?></td>
                                                        </tr>
                                                   </div>
			                           
		                                    </table>
                                    <!-- </div>
                                    </div> --> 
                                        
							
                          
                   

                                   <!-- <div class="row"> -->
         					       
						
					                <!-- <div class="col-md-12 openacdetails"> -->
					                	
					                	<?php 
					                	$tempYears = array();
                                        $onTimePaymentCount = 0;
                                        

					                	   foreach ($value['History48Months'] as $k => $v) {
					                        $date = DateTime::createFromFormat("Y-m", $v['yyyymm']);
					                        
					                        $str = '';
					                        if (in_array($v['assetclassification_dayspastdue'], $paymentStatusOnTimeArray)) {
					                            $str = '<a class="anc_active oneitme" href="javascript:void(0)"></a>';
					                            $onTimePaymentCount++;
					                           
					                        } else if (in_array($v['assetclassification_dayspastdue'], $paymentStatusLateArray)) {
					                            $str = '<a class="anc_active miditme" href="javascript:void(0)"></a>';
					                           
					                        } else if (in_array($v['assetclassification_dayspastdue'], $paymentStatusVeryLateTimeArray)) {
					                            $str = '<a class="anc_active latetime" href="javascript:void(0)"></a>';
					                           
					                        } else {
					                           list($days_past_due) = explode(' ', trim($v['assetclassification_dayspastdue']));
					                        	if($days_past_due >=1 && $days_past_due<=89){
					                        	   $str = '<a class="anc_active miditme" href="javascript:void(0)"></a>';	
					                             } elseif ($days_past_due >=90) {
					                             	$str = '<a class="anc_active latetime" href="javascript:void(0)"></a>';
					                             } else {
					                        	   $str = '<a class="anc_active latetime" href="javascript:void(0)"></a>';
					                             }
                               				
					                        }

					                        if (isset($tempYears[$date->format("Y")])) {
					                            $tempYears[$date->format("Y")][$date->format("M")] = $str;
					                            
					                        } else {
					                            $tempYears[$date->format("Y")] = array();
					                            $tempYears[$date->format("Y")][$date->format("M")] = $str;
					                            
					                        }
                                          }
                                     $history_percentage = number_format(($onTimePaymentCount * 100) / count($value['History48Months']), 2);
                                     ?>

				                    <?php
				                        if($value['account_status'] == 'OPN'){
				                            $openHistoryAccountFlag = true;
				                        }
				                        else{
				                            $closedHistoryAccountFlag = true;
				                        }
				                    ?>      
				                    <?php $class = $value['account_status'] == 'OPN' ? 'open_account' : 'closed_account display_none';
				                    $fromToCount = count($value['History48Months']);
				                    
				                    if(isset($value['History48Months'][0]['yyyymm'])){
				                     $from_date = DateTime::createFromFormat("Y-m", $value['History48Months'][0]['yyyymm']);
				                    $from_date = $from_date->format('m/Y');

				                    } else {
				                    	$from_date ='';
				                    }
				                    $paymentHeadingFrom = isset($value['History48Months'][0]) ? " to ".$from_date : "";
				                    // dd($date1);exit;
				                    if(isset($value['History48Months'][$fromToCount-1]['yyyymm'])){
				                     $to_date = DateTime::createFromFormat("Y-m", $value['History48Months'][$fromToCount-1]['yyyymm']);
				                    $to_date = $to_date->format('m/Y');

				                    } else {
				                    	$to_date ='';
				                    }
				                    $paymentHeadingTo = isset($value['History48Months'][$fromToCount-1]) ? $to_date : "";
				                    if(count($value['History48Months'])==1){
				                        $paymentHeadingFrom = '';
				                    }
				                    $forOrFrom = $paymentHeadingFrom!="" ? 'from ':'for ';

				                     ?>
						                        <table id="openacdetails">
													<tr>
														<th colspan="7"><h class="payment-history"> Payment History  <!-- <?php echo $forOrFrom. $paymentHeadingTo.$paymentHeadingFrom; ?> --></h><br class="media-break"><h class="payment-time"> <div class="anc_active oneitme" style="width: 12px;height: 12px;margin-bottom: 2px;"></div>&nbsp;&nbsp;On-time&nbsp;&nbsp;<div class="anc_active miditme" style="width: 12px; height: 12px;margin-bottom: 2px;"></div>&nbsp;&nbsp;1-89 days late&nbsp;&nbsp;<div class="anc_active latetime" style="height: 12px;width: 12px;margin-bottom: 2px;"></div>&nbsp;&nbsp;90+ days late</h>
		                                                  </th>                                  
													</tr>
													<div style="margin-top: 10px">

																	<table id="paymenthistory">
																		
																		<tr>
																			<td></td>
																			<td>Year</td>
																			<td> Dec </td>
																			<td> Nov </td>
																			<td> Oct </td>
																			<td> Sep </td>
																			<td> Aug </td>
																			<td> Jul </td>
																			<td> Jun </td>
																			<td> May </td>
																			<td> Apr </td>
																			<td> Mar </td>
																			<td> Feb </td>
																			<td> Jan </td>
																		</tr>
																		@foreach($tempYears as $tempYears_key => $tempYears_value)
		                                    <tr> 
		                                    	<td style="font-size: 12px;"><div class="dpd-text"><span>Status</span></div>
		                                    		<hr style="margin-bottom: 0px;margin-top: 0px;border-bottom: 1px solid #bbbbbb; width: 60px;">
												  <!--  <div class="line" style="width: 82px;height: 1px;    border-bottom: 1px solid #bbbbbb;position: absolute;left: 16px; "></div> -->
												   <div class="over-due-text"><span>Overdue Amount</span></div>
											    </td>
											    
											    

		                                        <td>{{$tempYears_key}}</td>

		                                        <td>{!! isset($tempYears_value['Dec']) ? $tempYears_value['Dec'] : '' !!}<br>
		                                        <?php foreach ($value['History48Months'] as $k => $v){
		                                        $month =    date('m',strtotime('Dec')); 
		                                        if($tempYears_key.'-'.$month == $v['yyyymm']){
		                                          // echo "₹". $v['amount_overdue_limit_overdue'];
		                                          echo !empty($v['amount_overdue_limit_overdue']) ? "₹".$v['amount_overdue_limit_overdue'] : ' ₹0 '; 
		                                        }
		                                        }
		                                      
		                                        ?></td>
		                                        <td>{!! isset($tempYears_value['Nov']) ? $tempYears_value['Nov'] : '' !!}<br>
		                                        <?php foreach ($value['History48Months'] as $k => $v){
		                                          $month =    date('m',strtotime('Nov')); 
		                                        
		                                        if($tempYears_key.'-'.$month == $v['yyyymm']){
		                                         echo !empty($v['amount_overdue_limit_overdue']) ? "₹".$v['amount_overdue_limit_overdue'] : ' ₹0 ';
		                                        }
		                                         }
		                                      
		                                        ?></td>
		                                        <td>{!! isset($tempYears_value['Oct']) ? $tempYears_value['Oct'] : '' !!}<br>
		                                        <?php foreach ($value['History48Months'] as $k => $v) { 
		                                        $month =    date('m',strtotime('Oct')); 
		                                        if($tempYears_key.'-'.$month == $v['yyyymm']){
		                                         echo !empty($v['amount_overdue_limit_overdue']) ? "₹".$v['amount_overdue_limit_overdue'] : ' ₹0 ';
		                                        }
		                                        }
		                                        ?></td>
		                                        <td>{!! isset($tempYears_value['Sep']) ? $tempYears_value['Sep'] : '' !!}<br>
		                                        <?php foreach ($value['History48Months'] as $k => $v) {
		                                        $month =    date('m',strtotime('Sep')); 
		                                        if($tempYears_key.'-'.$month == $v['yyyymm']){
		                                         echo !empty($v['amount_overdue_limit_overdue']) ? "₹".$v['amount_overdue_limit_overdue'] : ' ₹0 ';
		                                        }
		                                        }
		                                      
		                                        ?></td>
		                                        <td>{!! isset($tempYears_value['Aug']) ? $tempYears_value['Aug'] : '' !!}<br>
		                                        <?php foreach ($value['History48Months'] as $k => $v) {
		                                        $month =    date('m',strtotime('Aug')); 
		                                        if($tempYears_key.'-'.$month == $v['yyyymm']){
		                                          echo !empty($v['amount_overdue_limit_overdue']) ? "₹".$v['amount_overdue_limit_overdue'] : ' ₹0 '; 
		                                        }
		                                        }
		                                      
		                                        ?></td>
		                                        <td>{!! isset($tempYears_value['Jul']) ? $tempYears_value['Jul'] : '' !!}<br>
		                                        <?php foreach ($value['History48Months'] as $k => $v) {
		                                        $month =    date('m',strtotime('Jul')); 
		                                        if($tempYears_key.'-'.$month == $v['yyyymm']){
		                                         echo !empty($v['amount_overdue_limit_overdue']) ? "₹".$v['amount_overdue_limit_overdue'] : ' ₹0 ';
		                                        }
		                                        }
		                                      
		                                        ?></td>
		                                        <td>{!! isset($tempYears_value['Jun']) ? $tempYears_value['Jun'] : '' !!}<br>
		                                        <?php foreach ($value['History48Months'] as $k => $v) { 
		                                        $month =    date('m',strtotime('Jun')); 
		                                        if($tempYears_key.'-'.$month == $v['yyyymm']){
		                                         echo !empty($v['amount_overdue_limit_overdue']) ? "₹".$v['amount_overdue_limit_overdue'] : ' ₹0 ';
		                                        }
		                                        }
		                                      
		                                        ?></td>
		                                        <td>{!! isset($tempYears_value['May']) ? $tempYears_value['May'] : '' !!}<br>
		                                        <?php foreach ($value['History48Months'] as $k => $v) { 
		                                        $month =    date('m',strtotime('May')); 
		                                        if($tempYears_key.'-'.$month == $v['yyyymm']){
		                                          echo !empty($v['amount_overdue_limit_overdue']) ? "₹".$v['amount_overdue_limit_overdue'] : ' ₹0';
		                                        } }?></td>
		                                        <td>{!! isset($tempYears_value['Apr']) ? $tempYears_value['Apr'] : '' !!}<br>
		                                        <?php foreach ($value['History48Months'] as $k => $v) {
		                                        $month =    date('m',strtotime('Apr')); 
		                                        if($tempYears_key.'-'.$month == $v['yyyymm']){
		                                         echo !empty($v['amount_overdue_limit_overdue']) ? "₹".$v['amount_overdue_limit_overdue'] : ' ₹0 ';
		                                        }
		                                        }
		                                      
		                                        ?></td>
		                                        <td>{!! isset($tempYears_value['Mar']) ? $tempYears_value['Mar'] : '' !!}<br>
		                                        <?php foreach ($value['History48Months'] as $k => $v) { 
		                                        $month =    date('m',strtotime('Mar')); 
		                                        if($tempYears_key.'-'.$month == $v['yyyymm']){
		                                         echo !empty($v['amount_overdue_limit_overdue']) ? "₹".$v['amount_overdue_limit_overdue'] : ' ₹0 ';
		                                        }
		                                        }
		                                      
		                                        ?></td>
		                                        <td>{!! isset($tempYears_value['Feb']) ? $tempYears_value['Feb'] : '' !!}<br>
		                                        <?php foreach ($value['History48Months'] as $k => $v) {
		                                        $month =    date('m',strtotime('Feb')); 
		                                        if($tempYears_key.'-'.$month == $v['yyyymm']){
		                                         echo !empty($v['amount_overdue_limit_overdue']) ? "₹".$v['amount_overdue_limit_overdue'] : ' ₹0 ';
		                                          }
		                                         }
		                                      
		                                        ?></td>
		                                        <td>{!! isset($tempYears_value['Jan']) ? $tempYears_value['Jan'] : '' !!}<br>
		                                        <?php foreach ($value['History48Months'] as $k => $v) {
		                                        $month =    date('m',strtotime('Jan')); 
		                                        if($tempYears_key.'-'.$month == $v['yyyymm']){
		                                         echo !empty($v['amount_overdue_limit_overdue']) ? "₹".$v['amount_overdue_limit_overdue'] : ' ₹0 ';
		                                        } 
		                                        }
		                                      
		                                        ?></td>
		                                    </tr>
		                                    @endforeach
																			
																		<!-- </tr> -->
																	</table>
																	<!-- <hr style="border-bottom: dotted;"> -->
																	
																<?php
																
														?>
														
														
													</div>
						                        </table>
						                  <?php };?>
		                               </div>
		                             <!--Details of credit facilities Guaranteed by your company ends  -->

			                           <div class="col-md-12 publicdeeds2 equifex_recordentscreen" style="margin-top: 0px;">
			                                <table id="publicdeeds2">
			                                <tr>
			                                    <th colspan="4">Details of Enquiries</th>                                  
			                                </tr>
			                                <tr>
			                                    <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">Lender</td>
			                                    
			                                    <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">Date</td>
			                                    <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">Purpose</td>
			                                    <td style="background-color:#f2c50c; text-align: center; font-weight: 600;">Amount</td>
			                                </tr>
											<?php if($recent_enquiries!=0){
											foreach ($recent_enquiries as $key => $value) {?>
											 <tr>
		 	                                    <td style="text-align: center;"><?php echo !empty($value['Institution']) ? 'XXXXXXXXXXXX' : ' - ';?></td>
											    <td style="text-align: center;"><?php echo !empty($value['Date']) ? $value['Date'] : ' - ';?></td>
												<td style="text-align: center;"><?php echo !empty($value['RequestPurpose']) ? $value['RequestPurpose'] : ' - ';?></td>
												<td style="text-align: center;"><?php echo !empty($value['Amount']) ? "₹".$value['Amount'] : ' - ';?></td>
											</tr>
										    <?php } } else { ?>
											<tr>
		 	                                    <td style="text-align: center;"><?php echo  ' - ';?></td>
											    <td style="text-align: center;"><?php echo  ' - ';?></td>
												<td style="text-align: center;"><?php echo '-';?></td>
												<td style="text-align: center;"><?php echo  ' - ';?></td>
											</tr>

										   <?php }?>
			                                </table>
			                           </div>
	                        <?php }?> 
	                        </div>


	                   

	                         
				
					
            <!-- B2B Recordent Report Starts -->
	          <?php  if(isset($user['business_name_rec'])){?>
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
              <?php }?>  
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
                                <li><span style="width: 52%;">Business Name:</span>
                                 
                                {{!empty($user['business_name_rec']) ?$user['business_name_rec']  : ' - '}}</li>
                                <li><span style="width: 51%;">GSTIN / Business PAN:</span> {{$user['unique_identification_number'] }}</li>
                                <li><span style="width: 52%;">Business Type:</span>
                                {{!empty($user['business_type_rec']) ?$user['business_type_rec']  : ' - '}}</li>
                                <li><span style="width: 52%;">Business Sector:</span>
                                 {{!empty($user['business_sector_rec']) ?$user['business_sector_rec']  : ' - '}}</li>
                                
                            </ul>
                        </div>
                        <div class="col-md-6 list-recoddent">
                            <ul>
                            <li><span style="width: 52%;">Concerned Person Name:</span>{{!empty($user['business_concerned_name_rec']) ?$user['business_concerned_name_rec']  : ' - '}}</li>
                            <li><span style="width: 52%;">Concerned Person Mobile:</span>{{$user['number'] }}</li>
                            <li><span style="width: 52%;">Concerned Person Email:</span>{{!empty($user['business_email_rec']) ?$user['business_email_rec']  : ' - '}}</li>
                            <li><span style="width: 52%;">Concerned Person Designation:</span>{{!empty($user['business_designation_rec']) ?$user['business_designation_rec']  : ' - '}}</li>
                           
                            
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
                    <!-- ------------------Recordent screen end ------------------ -->

                </div>
                </div>
               <!--  <div class="recordentscreen">
                    <center class="no-records">No report found</center>
                </div> -->
	     <!-- B2B Recordent Report Ends -->
</div>
            </div>
</div>

                
               
		
	<style type="text/css">
        .anc_active {
		    width: 20px;
		    height: 20px; 
        }
        .row {
            margin-right: 5px;
            margin-left: 5px;
        }
       .pdf-logo{
            width: 200px;
            height: 60px;
            align-items: center;
        }
        .pdf-logo img{
            width: 200px;
        }
        .pdf-downloadbtn{
            color: #202f7d;
            text-align: center;
            font-weight: 800;
            font-size: 25px;
            line-height: 28px;
            margin: 12px;
        }
        .pdf-date{
            text-align: right;
            font-size: 14px;
            /*font-style: italic;*/
            font-weight: 400;
            color: #fff;
            background-color: #1e2c76;
            width: max-content;
            padding: 5px 30px;
            float: right;
            margin-top: 5px;
            border-radius: 20px 10px;
            position: relative;
            top: 7px;
        }
         
        .pie-title-center {
        margin: auto;
        position: relative;
        text-align: center;
        }
        .pie-title-center p{
            display: block;
            position: absolute;
            height: 40px;
            top: 63%;
            left: 0;
            right: 0;
            margin-top: -20px;
            line-height: 22px;
            font-size: 18px;
            color: #333;
            font-weight: 600;
        }
        .pie-value-txt{
            display: block;
            position: absolute;
            font-size: 20px;
            height: 40px;
            top: 46px;
            margin: 0px auto;
            /*line-height: 25px;*/
            font-weight: 600;
            color: #000;
            width:200px;
            text-align:center;
            padding:10px;
        }
		
		.rc_progress {​​
			position: relative;
			overflow: visible;
			border-radius: 10px;
			margin: 0px auto 40px auto;
			max-width: 900px;
			}​​
			.rc_block .left_top p{
				font-size:15px;
				color:#727481
			}
            .clr-green,.right_top a.clr-green,.right_top h5.clr-green,.right_top h5.clr-green a,a.clr-green{
            	color:#4cb826;
            	font-weight:600!important;
            	font-size:16px;
            	text-transform:uppercase
            }
           .right_top a.clr-green:hover,.right_top h5.clr-green a:hover,a.clr-green:hover{
           	color:#273581
           }
		   .rc_bottom{
			 padding:115px 0 0 0
		   }
		   .recrodent-section .row {
 			       margin-top: 41px;
			}
		   .profile-sec1 .rc_bottom{
		   	 padding-top: 77px;
		   }
		 .rc_bottom .left_bottom p{
		 	font-size:18px;
		 	line-height:24px;
		 	color:#575c71;
		 	font-weight:600!important;
		 	margin-bottom:0
		 }
		 .rc_bottom .left_bottom p span{
		 	color:#202f7d;
		 	font-weight:700
		 }
		 .not_link i{
		 	font-size:20px;
		 	color:#f5d13d;
		 	font-weight:700
		 }
		 .not_link:hover i{
		 	color:#273581
		 }
		 .ac_inline{
		 	display:inline-block
		 }
		 .ac_lm{
		 	margin-left:20px
		 }
		 .address_span{
		 	max-width:50%
		 }
		 .rc_progress{
		 	position:relative;
		 	overflow:visible;
		 	border-radius:10px;
		 	margin:0px auto 70px auto;
		 	max-width:900px
		 }
		.rc_progress .progress-bar-danger{
			border-top-left-radius:10px;
			border-bottom-left-radius:10px;
			background:#ff6c6c!important;
			width:30%;
			position:relative
		}
		 .rc_progress .progress-bar-danger::before,.rc_progress .progress-bar-info::before,.rc_progress .progress-bar-warning::before{
		 	content:"";
		 	width:1px;
		 	height:100%;
		 	position:absolute;
		 	right:0;
		 	top:0;
		 	background:#fff
		 }
		 .rc_progress .progress-bar-warning{
		 	background:#ffb36c!important;
		 	width:30%;
		 	position:relative
		 }
		 .rc_progress .progress-bar-info{
		 	background:#f5d13d!important;
		 	width:20%;
		 	position:relative
		 }
		 .rc_progress .progress-bar-success{
		 	background:#82e360!important;
		 	border-top-right-radius:10px;
		 	border-bottom-right-radius:10px;
		 	width:20%;
		 	position:relative
		 }
		 .rc_progress .progress-bar.active{
		 	position:absolute;
		 	background:#82e360;
		 	width:12px;
		 	height:50px;
		 	left:<?php if(!isset($error_data)) {echo ($totalSuccessPayment/$totalPayments)*100;}?>%;
		 	top:-18px;
		 	border-radius:10px;
		 	border:solid 1px #fff;
		 	z-index:99
		 }
		 .progress-bar {
 		   line-height: 115px;
    	   box-shadow: none;
         }
        .pie-value {
            font-size: 40px;
            font-weight: 800;
        }
        .title_imporve {
            text-align: center;
            font-size: 28px;
            padding: 5px 0;
            font-weight: 500;
            color:#000;
        }
        .progress{
            border-radius: 5px;
            overflow: inherit;
        }
        .redpb{
            background-color: #ff6c6c !important;
            border-right: solid 2px #fff;
        }
        .orangepb{
            background-color: #ffb36c !important;
            border-right: solid 2px #fff;
        }
        .greenpb{
            background-color: #82e360 !important;
            border-right: solid 2px #fff;
        }
        .bluepb{
            background-color: #1483f2 !important;
        }
        .yellowpb{
            background-color: #f5d13d !important;
        }
        .donutchart{
            width: 200px;
            margin: 0px auto;
            height:200px;
        }
        .donutchart h3{
            font-size: 18px;
            font-weight: 600;
            padding: 0px 0px 20px;
            color: black;
        }
        .progress-bar span{
            color: #262626;
            top: -16px;
            position: relative;
            z-index: 4;
            font-weight: 600;
            font-size: 13px;
        }
		
		.rc_progress .progress-bar-act{​​​​​
		
			left: 0;
			position: absolute !important;
			/*background: #82e360;*/
			width: 12px;
			height: 50px;			
			top: -18px;
			border-radius: 10px;
			border: solid 1px #fff;
			z-index: 99;
		}​​​​​
        .progress-meter {
            min-height: 5px;
		}

		.progress-meter > .meter {
			position: relative;
			float: left;
			min-height: 5px;
		}

		.progress-meter > .meter-left {
			border-left-width: 2px;
		}

		.progress-meter > .meter-right {
			float: right;
			border-right-width: 2px;
		}

		.progress-meter > .meter-right:last-child {
			border-left-width: 2px;
		}

		.progress-meter > .meter > .meter-text {
			position: absolute;
		    display: inline-block;
		    bottom: -5px;
		    width: 100%;
		    font-weight: 700;
		    font-size: 0.85em;
		    color: rgb(0, 0, 0);
		    text-align: right;
		}

		.progress-meter > .meter.meter-right > .meter-text {
			text-align: right;
		}
        .mt-mb{
            margin: 25px auto;
        }
        .customerdata{
            margin: 45px auto;
        }
		
        #customers {
            border-collapse: separate;
            color: #424242;
            font-size: 15px;
            width: 100%;
            border: 1px solid #bbbbbb;
            border-radius: 10px 10px 0px 0px;
            overflow: hidden;
        }
        #customers td{
            border-bottom: 1px solid #bbbbbb;
            padding: 10px;
            color: #000000;
            font-weight: 500;
            border-right: 1px solid #bbbbbb;
        }
        #customers td:last-child{
            border-right: none;
        }
        #customers th {
            background-color: #f3b90f;
            color: #424242;
            font-size: 16px;
            font-weight: 600;
            height: 45px;
            text-align: center;
        }
        .reportdata{
            margin: 45px auto;
        }
        #reportdata {
            border-collapse: collapse;
            color: #222222;
            font-size: 15px;
            width: 100%;
        }
        #reportdata td{
            padding: 10px 50px;
            background-color: #e8e8e8;
            font-weight: 600;
        }
        #reportdata th {
            background-color: #273581;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            height: 45px;
            text-align: center;
        }
        .non-headtxt{
            position: relative;
            margin: 25px auto;
            text-align: center;
            display: block;
        }
        .non-headtxt h2{
            font-size: 18px;
            font-weight: 600;
            background-color: #f3b90f;
            width: max-content;
            padding: 10px 30px;
            border-radius: 20px;
            color: black;
            text-align: center;
            z-index: 99;
            position: relative;
            text-align: center;
            margin: 0px auto;
        }
        .non-headtxt span{
            border-bottom: solid 1px #424242;
            display: block;
            top: -19px;
            position: relative;
            z-index: 1;
        }
        .statistics_item {
            background-color: #273581;
            text-align: center;
            padding: 25px 15px;
            border-bottom: solid 4px #0b1130;
            border-radius: 20px;
            box-shadow: 3px 3px 15px #d3d3d3;
            -moz-box-shadow: 3px 3px 15px #d3d3d3;
            -webkit-box-shadow: 3px 3px 15px #d3d3d3;
            -o-box-shadow: 3px 3px 15px #d3d3d3;
            width: 300px;
            height:120px;
            margin: 0px auto;
        }
        .statistics_item .counter{
            font-size: 35px;
            font-weight: 700;
            margin-top:20px;
            height:30px;
            color: #fff;
        }
        .statistics_item p{
            color: #d1d1d1;
        }
		.statistics_item .counter span{
            font-size: 25px;
            font-weight: 700;
		}
			
        .pb-hr{
            border-bottom: solid 1px #424242;
            display: block;
            position: relative;
            margin: 25px auto;
        }
        .publicdeeds{
            margin: 45px auto;
        }
        .donutchart1{
            width: 150px;
            margin: 0px auto;
        }
        .donutchart1 h3{
            font-size: 18px;
            font-weight: 600;
            padding: 0px 0px 20px;
            color: black;
        }
        .pie-value-txt1{
            display: block;
            position: absolute;
            height: 40px;
            top: 56%;
            left: 0;
            right: 0;
            line-height: 20px;
            font-weight: 600;
            color: #000;
            text-align: center;
            width: 150px;
            margin: 0px auto;
        }
        .pie-value1 {
            font-size: 18px;
            font-weight: bold;
        }
        #publicdeeds {
            border-collapse: collapse;
            color: #222222;
            font-size: 15px;
            width: 100%;
        }
        #publicdeeds td{
            padding: 10px 5px;
            border: 1px solid #bbbbbb;
            font-weight: 600;
            text-align: center;
            border-radius: 15px 15px 0px 0px;
        }
        #publicdeeds th {
            background-color: #273581;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            height: 45px;
            text-align: center;
            border-radius: 15px 15px 0px 0px;
        }
        .publicdeeds2{
            margin: 45px auto;
        }
        #publicdeeds2 {
            border-collapse: collapse;
            color: #222222;
            font-size: 15px;
            width: 100%;
        }
        #publicdeeds2 td{
            padding: 10px 25px;
            font-weight: 600;
        }
        #publicdeeds2 th {
            background-color: #273581;
            color: #fff;
            font-size: 18px;
            font-weight: 600;
            height: 45px;
            padding: 10px 25px;
            border-radius: 15px 15px 0px 0px;
            text-align: center;
        }
        .creditage{
            margin: 45px auto;
        }
        #creditage {
            border-collapse: collapse;
            color: #222222;
            font-size: 15px;
            width: 100%;
        }
        #creditage td{
            padding: 10px 25px;
            font-weight: 600;
            text-align: center;
        }
        #creditage th {
            background-color: #273581;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            height: 45px;
            padding: 10px 25px;
            border-radius: 15px 15px 0px 0px;
            text-align: center;
        }
        .totalaccount{
            margin: 45px auto;
        }
        #totalaccount {
            border-collapse: collapse;
            color: #222222;
            font-size: 15px;
            width: 100%;
        }
        #totalaccount td{
            padding: 10px 5px;
            border: 1px solid #bbbbbb;
            font-weight: 600;
            text-align: center;
        }
        #totalaccount th {
            background-color: #273581;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            height: 45px;
            padding: 10px 25px;
            border-radius: 15px 15px 0px 0px;
            text-align: center;
        }
        .openacdetails{
            margin: 45px auto;
        }
        #openacdetails {
            border-collapse: collapse;
            color: #222222;
            font-size: 15px;
            width: 100%;
        }
        #openacdetails td{
            padding: 10px 25px;
            font-weight: 600;
            text-align: left;
        }
        #openacdetails th {
            background-color: #e8e8e8;
            /*color: #fff;*/
            font-size: 16px;
            font-weight: 600;
            height: 45px;
            padding: 10px 25px;
            border-radius: 15px 15px 0px 0px;
            text-align: center;
        }
        .paymenthistory{
            margin: 45px auto;
        }
        #paymenthistory {
            border-collapse: collapse;
            color: #222222;
            font-size: 15px;
            width: 100%;
            table-layout: fixed;
        }
        #paymenthistory td{
            padding: 0px;
            border: 1px solid #bbbbbb;
            font-weight: 600;
            text-align: center;
            width: calc(100%/13);
        }
        #paymenthistory th {
            background-color: #273581;
            color: #fff;
            font-size: 16px;
            font-weight: 600;
            height: 45px;
            text-align: center;
            border-radius: 15px 15px 0px 0px;
        }
        .red-roundbg{
            width: 35px;
            height: 35px;
            position: relative;
            background-color: #f22a2a;
            border-radius: 25px;
            line-height: 35px;
            margin: 6px auto;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            top: -3px;
        }
        .pur-roundbg{
            width: 35px;
            height: 35px;
            position: relative;
            background-color: #db22cd;
            border-radius: 25px;
            line-height: 35px;
            margin: 6px auto;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            top: -3px;
        }
        .lblue-roundbg{
            width: 35px;
            height: 35px;
            position: relative;
            background-color: #79d2de;
            border-radius: 25px;
            line-height: 35px;
            margin: 6px auto;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            top: -3px;
        }
        .green-roundbg{
            width: 35px;
            height: 35px;
            position: relative;
            background-color: #1da727;
            border-radius: 25px;
            line-height: 35px;
            margin: 6px auto;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            top: -3px;
        }
        .blue-roundbg{
            width: 35px;
            height: 35px;
            position: relative;
            background-color: #147ad6;
            border-radius: 25px;
            line-height: 35px;
            margin: 6px auto;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            top: -3px;
        }
        .bri-roundbg{
            width: 35px;
            height: 35px;
            position: relative;
            background-color: #7849c4;
            border-radius: 25px;
            line-height: 35px;
            margin: 6px auto;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            top: -3px;
        }
        .black-roundbg{
            width: 35px;
            height: 35px;
            position: relative;
            background-color: #000;
            border-radius: 25px;
            line-height: 35px;
            margin: 6px auto;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            top: -3px;
        }
        .orange-roundbg{
            width: 35px;
            height: 35px;
            position: relative;
            background-color: #ff9d00;
            border-radius: 25px;
            line-height: 35px;
            margin: 6px auto;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            top: -3px;
        }
        .tab-legends{
            display: flex;
            position: relative;
            flex-direction: row;
            margin: 10px auto;
            flex-wrap: wrap;
            justify-content:space-between;
        }
        .tab-legends li{
            list-style: none;
            margin-right: 10px;
            font-size: 13px;
            color: #000;
            font-weight: 300;
            line-height: 15px;
        }
        .tab-legends li > div{
            display: inline-flex;
            margin-right: 5px;
            top: 3px;
            position: relative;
        }
        .tab-blue{
            width: 15px;
            height: 15px;
            box-sizing: border-box;
            border-radius: 3px;
            background-color: #147ad6;
        }
        .tab-lblue{
            width: 15px;
            height: 15px;
            box-sizing: border-box;
            border-radius: 3px;
            background-color: #79d2de;
        }
        .tab-green{
            width: 15px;
            height: 15px;
            box-sizing: border-box;
            border-radius: 3px;
            background-color: #1da727;
        }
        .tab-red{
            width: 15px;
            height: 15px;
            box-sizing: border-box;
            border-radius: 3px;
            background-color: #f22a2a;
        }
        .tab-orange{
            width: 15px;
            height: 15px;
            box-sizing: border-box;
            border-radius: 3px;
            background-color: #ff9d00;
        }
        .tab-bri{
            width: 15px;
            height: 15px;
            box-sizing: border-box;
            border-radius: 3px;
            background-color: #7849c4;
        }
        .tab-pur{
            width: 15px;
            height: 15px;
            box-sizing: border-box;
            border-radius: 3px;
            background-color: #db22cd;
        }
        .tab-black{
            width: 15px;
            height: 15px;
            box-sizing: border-box;
            border-radius: 3px;
            background-color: #000;
        }
        #averagedaystb {
            border-collapse: collapse;
            color: #222222;
            font-size: 15px;
            width: 100%;
            table-layout: fixed;
            margin: 0px -15px;
        }
        #averagedaystb td{
            padding: 10px 5px;
            border: 1px solid #bbbbbb;
            font-weight: 600;
            text-align: center;
        }
        #averagedaystb th {
            color: #000;
            font-size: 16px;
            font-weight: 800;
            height: 45px;
            text-align: center;
            border: 1px solid #bbbbbb;
        }
        .media-break{
        	display: none;
        }
        .order-number{
          margin-right: 6px;
        }
        .payment-history{
        	padding-left: 278px;
        }
        .payment-time{
        	float: right;
        }
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
        
         @media only screen and (max-width: 576px) {
        
        .mobile-mr{
            margin: 70px -15px 0px -15px !important;
            padding: 0px !important;
        }
        .voyager .panel{
            padding: 20px 0px;
        }
        .panel-bordered > .panel-body {
            padding: 10px 0px 0px;
        }
        .profress-scroll {
            width: 100%;
            overflow: visible;
        }
        .rc_progress .progress-bar-danger{
            height:15px;
        }
        .rc_progress .progress-bar-warning{
            height:15px;
        }
        .rc_progress .progress-bar-info{
            height:15px;
        }
        .rc_progress .progress-bar-success{
            height:15px;
        }
        .pdf-logo{
            width:100%;
            height:50px;
        }
        .pdf-logo img {
            width: 125px;
        }
        .download-btn {
            border: solid 1px #202f7d;
            color: #202f7d;
            border-radius: 15px;
            display: inline-block;
            padding: 10px 60px 10px 30px;
            text-decoration: none;
            font-size: 20px;
            line-height: 20px;
            font-weight: 700;
            position:relative;
            right: 0px;
            top: 0px;
            width:100%;
        }
        .pdf-date {
            text-align: right;
            font-size: 9.5px;
            /*font-style: italic;*/
            font-weight: 400;
            color: #fff;
            background-color: #1e2c76;
            width: max-content;
            padding: 5px 10px;
            float: right;
            border-radius: 20px 10px;
            position: relative;
            top: -133px;
            left: 17px;
            margin-bottom:0px 0px 10px 0px;
        }
        .mt-mb{
        	margin-bottom: -104px;
        }
        .donutchart {
 		   width: 200px;
    		margin: -25px auto;
    		height: 200px;
		}

        .mb-0{
            margin-bottom:0px !important;
        }
        .page-title2 {
            display: block;
            height: auto;
            font-size: 11px;
            margin-top: -25px;
            padding-top: 0px;
            padding-left: 0px;
            margin-bottom: 10px;
            color: #555;
            font-weight: 400;
            line-height: 23px;
            text-align: center;
        }
        #customers{
            font-size:12px;
        }
        #reportdata {
            border-collapse: collapse;
            color: #222222;
            font-size: 12px;
            width: 100%;
            overflow-x: scroll;
            display: inline-block;
            overflow-y: scroll;
        }
        #reportdata td {
            padding: 10px 10px;
            background-color: #e8e8e8;
            font-weight: 600;
            white-space: nowrap;
            border: 1px solid #fff;
        }
        #publicdeeds {
            border-collapse: collapse;
            color: #222222;
            font-size: 12px;
            width: 100%;
            overflow-x: scroll;
            display: table;
            overflow-y: scroll;
        }
        #publicdeeds2 {
            border-collapse: collapse;
            color: #222222;
            font-size: 12px;
            width: 100%;
            overflow-x: scroll;
            display: inline-block;
            overflow-y: scroll;
            padding:2px;
        }
        #publicdeeds2 td{
            border: solid 1px #eee;
        }
        #totalaccount {
            border-collapse: collapse;
            color: #222222;
            font-size: 12px;
            width: 100%;
            overflow-x: scroll;
            display: inline-block;
            overflow-y: scroll;
            padding:2px;
        }
        #totalaccount td {
            white-space: nowrap;
        }
        #openacdetails {
            border-collapse: collapse;
            color: #222222;
            font-size: 15px;
            width: 100%;
            overflow-x: scroll;
            /*display: inline-block;*/
            overflow-y: scroll;
            padding:2px;
        }
        #openacdetails td {
            padding: 10px 10px;
            font-weight: 600;
            text-align: left;
            white-space: nowrap;
            border: solid 1px #eee;
        }
        #paymenthistory {
            border-collapse: collapse;
            color: #222222;
            font-size: 12px;
            width: 100%;
            table-layout: fixed;
            overflow-x: scroll;
            display: inline-block;
            overflow-y: scroll;
            padding:2px;
        }
        .ten{
         margin-left: 3px !important;
        }
        .nine{
           margin-right: -54px !important;
        }
        .eight{
          margin-right: -44px !important;
        }
        .seven{
          left: -8px !important;
        }
        .six{
          margin-left: -43px !important;
        }
        .five{
          margin-left: -41px !important;
        }
        .four{
           left: -7px !important;
        }
        .three{
           left: -7px !important;
        }
        .two{
            left: -7px !important;
        }
        .one{
          left: -5px !important;
        }
        .order-number{
             margin-right: -140px !important;
                 top: -121px !important;   
        }
        .media-break{
        	display: block;
        }
        
        /*.dpd-text span {
          display: none;
        }

        .dpd-text:after {
        	content: "DPD";
        }*/
        .over-due-text span {
          display: none;
        }
         .over-due-text:after {
        	content: "Overdue";
        }
        .payment-history{
        	font-size: 10.9px;
        	padding-left: 0px;
        }
        .payment-time{
        	padding-left: 8px;
           font-size: 10px;
        }
        #paymenthistory td{
            padding: 12px !important;
           
        }
        .customers-data{
        	padding-right: 0px !important;
        }
        .customers-id{
        	padding-left: 0px !important;
        	padding-top: 0px !important;
        }
        .main_section{
        	width: 93%;
        }
        .left_bottom{
        	float: none;
        }
        .back_to_dasborad_members, .back_to_dasborad_profile{
        	top: 307px;
        }
        .back_to_members_invoice{
        	top: 8px;
            left: 28px;
        }
        .rc_title_sub.recordent-title.fleft{
        	padding: 15px;
            padding-left: 37px;
        }
        }
        
        @media screen and (min-device-width: 577px) and (max-device-width: 800px)  {
        .mobile-mr{
            margin: 70px -15px 0px -15px !important;
            padding: 0px !important;
        }
        .voyager .panel{
            padding: 20px 0px;
        }
        .panel-bordered > .panel-body {
            padding: 10px 0px 0px;
        }
        .profress-scroll {
            width: 100%;
            overflow: visible;
        }
        .rc_progress .progress-bar-danger{
            height:15px;
        }
        .rc_progress .progress-bar-warning{
            height:15px;
        }
        .rc_progress .progress-bar-info{
            height:15px;
        }
        .rc_progress .progress-bar-success{
            height:15px;
        }
        .pdf-logo{
            width:100%;
            height:50px;
        }
        .pdf-logo img {
            width: 125px;
        }
        .download-btn {
            position: absolute;
            right: 30px;
            top: -20px;
            border: solid 1px #202f7d;
            color: #202f7d;
            border-radius: 15px;
            display: inline-block;
            padding: 10px 60px 10px 30px;
            text-decoration: none;
            font-size: 20px;
            line-height: 23px;
            font-weight: 700;
        }
        .pdf-date {
            text-align: right;
            font-size: 11px;
            /*font-style: italic;*/
            font-weight: 400;
            color: #fff;
            background-color: #1e2c76;
            width: max-content;
            padding: 5px 10px;
            float: right;
            border-radius: 20px 10px;
            position: relative;
            top: 0px;
            margin-bottom:0px 0px 10px 0px;
        }
        .mb-0{
            margin-bottom:0px !important;
        }
        .page-title2 {
            display: block;
            height: auto;
            font-size: 11px;
            margin-top: -25px;
            padding-top: 0px;
            padding-left: 0px;
            margin-bottom: 10px;
            color: #555;
            font-weight: 400;
            line-height: 23px;
            text-align: center;
        }
        #customers{
            font-size:12px;
        }
        #reportdata {
            border-collapse: collapse;
            color: #222222;
            font-size: 12px;
            width: 100%;
            overflow-x: scroll;
            display: inline-table;
            overflow-y: scroll;
        }
        #reportdata td {
            padding: 10px 10px;
            background-color: #e8e8e8;
            font-weight: 600;
            white-space: nowrap;
            border: 1px solid #fff;
        }
        #publicdeeds {
            border-collapse: collapse;
            color: #222222;
            font-size: 12px;
            width: 100%;
            overflow-x: scroll;
            display: table;
            overflow-y: scroll;
        }
        #publicdeeds2 {
            border-collapse: collapse;
            color: #222222;
            font-size: 12px;
            width: 100%;
            overflow-x: scroll;
            display: inline-block;
            overflow-y: scroll;
            padding:2px;
        }
        #publicdeeds2 td{
            border: solid 1px #eee;
        }
        #totalaccount {
            border-collapse: collapse;
            color: #222222;
            font-size: 12px;
            width: 100%;
            overflow-x: scroll;
            display: inline-table;
            overflow-y: scroll;
            padding:2px;
        }
        #totalaccount td {
            white-space: nowrap;
        }
        #openacdetails {
            border-collapse: collapse;
            color: #222222;
            font-size: 15px;
            width: 100%;
            overflow-x: scroll;
            display: inline-block;
            overflow-y: scroll;
            padding:2px;
        }
        #openacdetails td {
            padding: 10px 10px;
            font-weight: 600;
            text-align: left;
            white-space: nowrap;
            border: solid 1px #eee;
        }
        #paymenthistory {
            border-collapse: collapse;
            color: #222222;
            font-size: 12px;
            width: 100%;
            table-layout: fixed;
            overflow-x: scroll;
            display: inline-table;
            overflow-y: scroll;
            padding:2px;
        }
        .ten{
         margin-left: 3px !important;
        }
        .nine{
           margin-right: -54px !important;
        }
        .eight{
          margin-right: -44px !important;
        }
        .seven{
          left: -8px !important;
        }
        .six{
          margin-left: -43px !important;
        }
        .five{
          margin-left: -41px !important;
        }
        .four{
           left: -7px !important;
        }
        .three{
           left: -7px !important;
        }
        .two{
            left: -7px !important;
        }
        .one{
          left: -5px !important;
        }
        .order-number{
             margin-right: -140px !important;
                 top: -121px !important;   
        }
        .media-break{
        	display: block;
        }
        
        .dpd-text span {
          display: none;
        }

        .dpd-text:after {
        	content: "DPD";
        }
        .over-due-text span {
          display: none;
        }
         .over-due-text:after {
        	content: "Due";
        }
        .payment-history{
        	font-size: 10.9px;
        	padding-left: 0px;
        }
        .payment-time{
        	padding-left: 8px;
           font-size: 10px;
        }
        #paymenthistory td{
            padding: 12px !important;
           
        }
        .customers-data{
        	padding-right: 0px !important;
        }
        .customers-id{
        	padding-left: 0px !important;
        	padding-top: 0px !important;
        }
        .main_section{
        	width: 93%;
        }
        .left_bottom{
        	float: none;
        }
        .back_to_dasborad_members, .back_to_dasborad_profile{
        	top: 307px;
        }
        .back_to_members_invoice{
        	top: 8px;
            left: 28px;
        }
        .rc_title_sub.recordent-title.fleft{
        	padding: 15px;
            padding-left: 37px;
        }
        }
    </style>
    
    
	<!--<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
	<<script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>-->
	
	<!--<script src="{{asset('js/pie-chart.js')}}"></script>-->
    
	<!--<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>     
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.js"></script>-->
	
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.js"></script>
    <script type="text/javascript">

        
		//Anonymous sely-executing function
(function (root, factory) {
  factory(root.jQuery);
}(this, function ($) {

  var CanvasRenderer = function (element, options) {
    var cachedBackground;
    var canvas = document.createElement('canvas');

    element.appendChild(canvas);

    var ctx = canvas.getContext('2d');

    canvas.width = canvas.height = options.size;

    // move 0,0 coordinates to the center
    ctx.translate(options.size / 2, options.size / 2);

    // rotate canvas -90deg
    ctx.rotate((-1 / 2 + options.rotate / 180) * Math.PI);

    var radius = (options.size - options.lineWidth) / 2;

    Date.now = Date.now || function () {

          //convert to milliseconds
          return +(new Date());
        };

    var drawCircle = function (color, lineWidth, percent) {
      percent = Math.min(Math.max(-1, percent || 0), 1);
      var isNegative = percent <= 0 ? true : false;

      ctx.beginPath();
      ctx.arc(0, 0, radius, 0, Math.PI * 2 * percent, isNegative);

      ctx.strokeStyle = color;
      ctx.lineWidth = lineWidth;

      ctx.stroke();
    };

    /**
     * Return function request animation frame method or timeout fallback
     */
    var reqAnimationFrame = (function () {
      return window.requestAnimationFrame ||
          window.webkitRequestAnimationFrame ||
          window.mozRequestAnimationFrame ||
          function (callback) {
            window.setTimeout(callback, 1000 / 60);
          };
    }());

    /**
     * Draw the background of the plugin track
     */
    var drawBackground = function () {
      if (options.trackColor) drawCircle(options.trackColor, options.lineWidth, 1);
    };

    /**
     * Clear the complete canvas
     */
    this.clear = function () {
      ctx.clearRect(options.size / -2, options.size / -2, options.size, options.size);
    };

    /**
     * Draw the complete chart
     * param percent Percent shown by the chart between -100 and 100
     */
    this.draw = function (percent) {
      if (!!options.trackColor) {
        // getImageData and putImageData are supported
        if (ctx.getImageData && ctx.putImageData) {
          if (!cachedBackground) {
            drawBackground();
            cachedBackground = ctx.getImageData(0, 0, options.size, options.size);
          } else {
            ctx.putImageData(cachedBackground, 0, 0);
          }
        } else {
          this.clear();
          drawBackground();
        }
      } else {
        this.clear();
      }

      ctx.lineCap = options.lineCap;

      // draw bar
      drawCircle(options.barColor, options.lineWidth, percent / 687);
    }.bind(this);

    this.animate = function (from, to) {
      var startTime = Date.now();

      var animation = function () {
        var process = Math.min(Date.now() - startTime, options.animate.duration);
        var currentValue = options.easing(this, process, from, to - from, options.animate.duration);
        this.draw(currentValue);

        //Show the number at the center of the circle
        options.onStep(from, to, currentValue);

        reqAnimationFrame(animation);

      }.bind(this);

      reqAnimationFrame(animation);
    }.bind(this);
  };

  var pieChart = function (element, userOptions) {
    var defaultOptions = {
      barColor: '#ef1e25',
      trackColor: '#f9f9f9',
      lineCap: 'round',
      lineWidth: 4,
      size: 180,
      rotate: 0,
      animate: {
        duration: 1000,
        enabled: true
      },
      easing: function (x, t, b, c, d) {//copy from jQuery easing animate
        t = t / (d / 2);
        if (t < 1) {
          return c / 2 * t * t + b;
        }
        return -c / 2 * ((--t) * (t - 2) - 1) + b;
      },
      onStep: function (from, to, currentValue) {
        return;
      },
      renderer: CanvasRenderer//Maybe SVGRenderer more later
    };

    var options = {};
    var currentValue = 0;

    var init = function () {
      this.element = element;
      this.options = options;

      // merge user options into default options
      for (var i in defaultOptions) {
        if (defaultOptions.hasOwnProperty(i)) {
          options[i] = userOptions && typeof(userOptions[i]) !== 'undefined' ? userOptions[i] : defaultOptions[i];
          if (typeof(options[i]) === 'function') {
            options[i] = options[i].bind(this);
          }
        }
      }

      // check for jQuery easing, use jQuery easing first
      if (typeof(options.easing) === 'string' && typeof(jQuery) !== 'undefined' && jQuery.isFunction(jQuery.easing[options.easing])) {
        options.easing = jQuery.easing[options.easing];
      } else {
        options.easing = defaultOptions.easing;
      }

      // create renderer
      this.renderer = new options.renderer(element, options);

      // initial draw
      this.renderer.draw(currentValue);

      if (element.getAttribute && element.getAttribute('data-percent')) {
        var newValue = parseFloat(element.getAttribute('data-percent'));

        if (options.animate.enabled) {
          this.renderer.animate(currentValue, newValue);
        } else {
          this.renderer.draw(newValue);
        }

        currentValue = newValue;
      }
    }.bind(this)();
  };

  $.fn.pieChart = function (options) {

    //Iterate all the dom to draw the pie-charts
    return this.each(function () {
      if (!$.data(this, 'pieChart')) {
        var userOptions = $.extend({}, options, $(this).data());
        $.data(this, 'pieChart', new pieChart(this, userOptions));
      }
    });
  };

}));


    </script>
    <script type="text/javascript">

            // chart colors
            var colors = ['#ff6c6c','#ffb36c','#82e360','#1483f2','#f5d13d','#333333'];

            /* 3 donut charts */
            var donutOptions = {
            cutoutPercentage: 85, 
            legend: {
      display: false
    }
            };
              
                //set color as per rage of score.
				<?php if(!isset($error_data)){?>
				var score_value = <?php echo $score_value; ?>;
				var donutBackground = '#ff6c6c';				
				if(score_value==""){
					var donutBackground = '#147ad6';
				}else{
					if (score_value > 0 && score_value <=2) {
						donutBackground = '#82e360';
					} else if (score_value >= 3 && score_value <= 4) {
						donutBackground = '#f5d13d';
					} else if (score_value >= 5 && score_value <= 7) {
						donutBackground = '#ffb36c';
					}
					else  {
						donutBackground = '#ff6c6c';
					}
				}
			<?php }?>
				
				var chDonutData = {
					//labels: ['Public Deeds'],
					datasets: [
						{
							//var cur_color = '';
							backgroundColor: donutBackground,
							borderWidth: 0,
							data: [100],
							opacity:10
						}
					]
				};

            var chDonut = document.getElementById("chDonut");
            if (chDonut) {
            new Chart(chDonut, {
                type: 'pie',
                data: chDonutData,
                options: donutOptions
                
            });
        }



            // donut 1
            var chDonutData1 = {
                labels: ['Public Deeds'],
                datasets: [
                {
                    backgroundColor: '#ff0505',
                    borderWidth: 0,
                    data: [100]
                }
                ]
            };

            var chDonut1 = document.getElementById("chDonut1");
            if (chDonut1) {
            new Chart(chDonut1, {
                type: 'pie',
                data: chDonutData1,
                options: donutOptions
                
            });
        }
            

            // donut 2
            var chDonutData2 = {
                labels: ['Oldest Account', 'Newest Account', 'Average Account'],
                datasets: [
                {
                    backgroundColor: colors.slice(0,3),
                    borderWidth: 0,
                    data: [40, 45, 30]
                }
                ]
            };
            var chDonut2 = document.getElementById("chDonut2");
            if (chDonut2) {
            new Chart(chDonut2, {
                type: 'pie',
                data: chDonutData2,
                options: donutOptions
            });

            }

            // donut 3
            var chDonutData3 = {
                labels: ['Credit Usage'],
                datasets: [
                {
                    backgroundColor: '#1483f2',
                    borderWidth: 0,
                    data: [100]
                }
                ]
            };
            var chDonut3 = document.getElementById("chDonut3");
            if (chDonut3) {
            new Chart(chDonut3, {
                type: 'pie',
                data: chDonutData3,
                options: donutOptions
            });

            }

            // donut 4
            var chDonutData4 = {
                labels: ['Enquires'],
                datasets: [
                {
                    backgroundColor: '#f88a1c',
                    borderWidth: 0,
                    data: [100]
                }
                ]
            };
            var chDonut4 = document.getElementById("chDonut4");
            if (chDonut4) {
            new Chart(chDonut4, {
                type: 'pie',
                data: chDonutData4,
                options: donutOptions
            });

            }

            // donut 5
            var chDonutData5 = {
                labels: ['Total Accounts'],
                datasets: [
                {
                    backgroundColor: '#f88a1c',
                    borderWidth: 0,
                    data: [100]
                }
                ]
            };
            var chDonut5 = document.getElementById("chDonut5");
            if (chDonut5) {
            new Chart(chDonut5, {
                type: 'pie',
                data: chDonutData5,
                options: donutOptions
            });

            }

            // donut 6
            var chDonutData6 = {
                labels: ['Payment Score'],
                datasets: [
                {
                    backgroundColor: '#f88a1c',
                    borderWidth: 0,
                    data: [100]
                }
                ]
            };
            var chDonut6 = document.getElementById("chDonut6");
            if (chDonut6) {
            new Chart(chDonut6, {
                type: 'pie',
                data: chDonutData6,
                options: donutOptions
            });

            }

            // donut 7 Banking charts
            var chDonutData7 = {
                labels: ['Public Deeds'],
                datasets: [
                {
                    backgroundColor: '#ff0505',
                    borderWidth: 0,
                    data: [100]
                }
                ]
            };

            var chDonut7 = document.getElementById("chDonut7");
            if (chDonut7) {
            new Chart(chDonut7, {
                type: 'pie',
                data: chDonutData7,
                options: donutOptions
                
            });
        }
            

            // donut 8
            var chDonutData8 = {
                labels: ['Oldest Account', 'Newest Account', 'Average Account'],
                datasets: [
                {
                    backgroundColor: colors.slice(0,3),
                    borderWidth: 0,
                    data: [40, 45, 30]
                }
                ]
            };
            var chDonut8 = document.getElementById("chDonut8");
            if (chDonut8) {
            new Chart(chDonut8, {
                type: 'pie',
                data: chDonutData8,
                options: donutOptions
            });

            }

            // donut 9
            var chDonutData9 = {
                labels: ['Credit Usage'],
                datasets: [
                {
                    backgroundColor: '#1483f2',
                    borderWidth: 0,
                    data: [100]
                }
                ]
            };
            var chDonut9 = document.getElementById("chDonut9");
            if (chDonut9) {
            new Chart(chDonut9, {
                type: 'pie',
                data: chDonutData9,
                options: donutOptions
            });

            }

            // donut 10
            var chDonutData10 = {
                labels: ['Enquires'],
                datasets: [
                {
                    backgroundColor: '#f88a1c',
                    borderWidth: 0,
                    data: [100]
                }
                ]
            };
			
            var chDonut10 = document.getElementById("chDonut10");
            if (chDonut10) {
            new Chart(chDonut10, {
                type: 'pie',
                data: chDonutData10,
                options: donutOptions
            });

            }

            // donut 11
            var chDonutData11 = {
                labels: ['Total Accounts'],
                datasets: [
                {
                    backgroundColor: '#f88a1c',
                    borderWidth: 0,
                    data: [100]
                }
                ]
            };
            var chDonut11 = document.getElementById("chDonut11");
            if (chDonut11) {
            new Chart(chDonut11, {
                type: 'pie',
                data: chDonutData11,
                options: donutOptions
            });

            }

            // donut 12
            var chDonutData12 = {
                labels: ['Payment Score'],
                datasets: [
                {
                    backgroundColor: '#f88a1c',
                    borderWidth: 0,
                    data: [100]
                }
                ]
            };
            var chDonut12 = document.getElementById("chDonut12");
            if (chDonut12) {
            new Chart(chDonut12, {
                type: 'pie',
                data: chDonutData12,
                options: donutOptions
            });
            }
			
			//donut13
			var chDonutData13 = {
                labels: ['Total Accounts'],
                datasets: [
                {
                    backgroundColor: '#f88a1c',
                    borderWidth: 0,
                    data: [100]
                }
                ]
            };
            var chDonut13 = document.getElementById("chDonut13");
            if (chDonut13) {
            new Chart(chDonut13, {
                type: 'pie',
                data: chDonutData13,
                options: donutOptions
            });

            }

    </script>
    <script>
    	<?php if(!isset($error_data)){?>
            var score = "{{ ($totalSuccessPayment/$totalPayments)*100}}";
            var scoreBackgroundvalue = '#ff6c6c !important';

            if (score > 80) {
                scoreBackgroundvalue = '#82e360 !important';
            } else if (score > 60 && score <= 79) {
                scoreBackgroundvalue = 'progress_bar_yellow';
            } else if (score > 30 && score <= 59) {
                scoreBackgroundvalue = 'progress_bar_orange';
            } else if (score <= 29) {
                scoreBackgroundvalue = 'progress_bar_red';
            }
            console.log(scoreBackgroundvalue);
            // $('#progress-bar-active-score').css('background', scoreBackgroundvalue);
            $('#progress-bar-active-score').addClass(scoreBackgroundvalue);
   <?php }?>
   </script>
   <script type="text/javascript">
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
                    // $(this).parents('.rc_dashbord').addClass('recordent_screen')
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
   	 $('#recordent_member_profile').click(function() {
                    $('.recordent_main').toggleClass('displayNone_section');
                    $('#recordent_member_profile_div').toggleClass('displayNone_section');
                    $('.reportSum').addClass('displayNone_section');   

                     $('.recordent_screen').addClass('mobile_acitive');                 

                });
   	 $('.back_to_dasborad_profile').click(function() {
                    $('.recordent_main').toggleClass('displayNone_section');
                    $('#recordent_member_profile_div').toggleClass('displayNone_section');
                    $('.reportSum').removeClass('displayNone_section');  

                    $('.recordent_screen').removeClass('mobile_acitive'); 
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

   </script>
<!--	ends at here added by ROOP   -->
@endsection

        

