@extends('voyager::master')
@section('page_title', __('voyager::generic.viewing').' Report')

@section('page_header')
<h1 class="page-title" style="display: none;">
    <i class="voyager-list"></i> US Credit Business Report

</h1>

<style>
	html {
		overflow-x: hidden;
	}
	.download-btn i{
		color:#202f7d;
		display:inline-block;
		font-size:0;
		width:16px;
		height:20px;
		background:url(../save-icon.png) no-repeat 0 0;
		position:absolute;right:30px;
		top:12px;
		background-size:cover
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
		align:right;}
</style>

@stop
@section('content')

<?php
	$request_folder = array();
	if(!empty($response['EfxTransmit']['StandardRequest'][0]['Folder'])){
		$request_folder = $response['EfxTransmit']['StandardRequest'][0]['Folder'];
	}
	
	if(empty($response['EfxTransmit'])){
		$response_folder = array();
	} else if(empty($response['EfxTransmit']['CommercialCreditReport'][0]['Folder'])){
		$response_folder = array();
	} else {
		$response_folder = $response['EfxTransmit']['CommercialCreditReport'][0]['Folder'];
	}
?>

<!-- 	Added by ROOP  -->
<div class="col-md-12 mobile-mr">
    <div class="panel panel-bordered">
        <div class="panel-body">
			<?php if(empty($response['EfxTransmit']['CommercialCreditReport']) || empty($response_folder['EfxId'])) { ?>
				</br>
                <div class="row">
                    <div class="col-md-3">
                        <div class="pdf-logo"><img src="https://www.stage.recordent.com/main_logo.jpg" alt="Logo" data-default="placeholder" data-max-width="300" data-max-height="100"></div>
                    </div>

                    <div class="col-md-6">
                        <div class="pdf-downloadbtn"> Sorry! <br> Report Not Created, <br>Insufficient response from Equifax</div>
                    </div>

                    <div class="col-md-3">
                        <p class="pdf-date">
						<?php echo "Report Date:"; ?>
						<!--January 21, 2021 - 14:06 p.m. CST --></p>
                    </div>
                </div>
			<?php
			} else {
			//ELSE CASE WHEN WE HAVE DATA IN REPORT RESPONSE, this else brace will end at the end of all HTML report
			?>
            <!-- Header Starts  -->
            <div class="row">
                <div class="col-md-3 col-xs-6 mb-0">
                    <div class="pdf-logo"><img src="https://www.stage.recordent.com/main_logo.jpg" alt="Logo" data-default="placeholder" data-max-width="300" data-max-height="100"></div>
                </div>

                <div class="col-xs-6 visible-xs mb-0">
					<p class="pdf-date"> <?php echo "Report Date: ".date('j F, Y', strtotime($response['EfxTransmit']['CommercialCreditReport'][0]['Header']['DateOfRequest'])); ?>
					<!--January 21, 2021 - 14:06 p.m. CST --></p>
                </div>
                <div class="col-md-6 col-xs-12">
                    <div class="pdf-downloadbtn">
						<?php echo $response['EfxTransmit']['StandardRequest'][0]['Folder']['IdTrait'][0]['CompanyNameTrait'][0]['BusinessName']; ?>
						<span style="color: #000000; text-align:center !important;font-weight: 400; font-size: 18px;line-height: 28px;margin: 0px; padding-top:50px !important;">
							<?php echo !empty($response_folder['ReportAttributes']['NonFinancialSummary']['SummaryAttributes']['CreditActiveSince']) ? '<br>'."Credit Active Since - " . General::getFormatedDate($response_folder['ReportAttributes']['NonFinancialSummary']['SummaryAttributes']['CreditActiveSince']) : '-'; ?>
						</span>
					</div>
                </div>
                <div class="col-md-3 hidden-xs">
					<a class="download-btn" style="display: block;" target="_blank" href="{{route('admin.us.business.download.pdf', ['cp_id' => $cp_id])}}"><i class="glyphicon glyphicon-save-file"></i>Download</a>
					<br>
                    <p class="pdf-date">
					<?php
					echo "Report Date: ".date('j F, Y',strtotime($response['EfxTransmit']['CommercialCreditReport'][0]['Header']['DateOfRequest']));?>
					<!--January 21, 2021 - 14:06 p.m. CST --></p>
                </div>
            </div>
            <!-- Header Ends  -->
	        <!-- Donut Chart   -->
	            <div class="row">
					<?php
						$score = "";
	                    if(isset($response_folder['DecisionTools']['ScoreData'][0]['score'])){
	                        $score = is_numeric($response_folder['DecisionTools']['ScoreData'][0]['score']) ? $response_folder['DecisionTools']['ScoreData'][0]['score'] : '' ;
	                    } else {
	                        $score = "";
	                    }

	                    if($score != ''){
							$scoreText = 'Needs improvement';
							$needle_color = '#ff6c6c';

							//Check is Score lie on range.
							if($score > 400){
								$scoreText = 'Excellent';
								$needle_color = '#82e360';
							} else if($score >= 301 && $score <= 400){
								$scoreText='Good' ;
								$needle_color = '#f5d13d';
							} else if($score>= 201 && $score < 300){
								$scoreText = 'Fair' ;
								$needle_color = '#f1b26b';
							}
							//end of code for testing code range.
					    } else {
							$scoreText = 'Not Available';
							$score     = "";
							$needle_color = '#147ad6';
						}
					?>
	                <div class="col-md-12 mt-mb">
						<div class="donutchart">
							<canvas id="chDonut"></canvas>
							<div class="pie-value-txt" style="color: #000000;font-size: 16px;line-height: 28px; font-weight: 400 !important;">
								<span class="pie-value"><?php 
									if($score <= 0) {
										$scoreText = "Not Available";
									} else {
										echo $score;
									}
									?>
								</span><br/>
								{{$scoreText}}
							</div>
						</div>
	                </div>
	                <div class="col-md-2"></div>
	                <div class="col-md-8 mt-mb">
					<div class="rc_mid">
						<h5 class="title_imporve" style="color: #000000;font-weight: 400;font-size: 18px;line-height: 28px;">
							<?php
								if(!empty($score)){
									echo '<span style"color: #000000;font-size: 16px; font-weight: 600;"><strong>Score</strong></span>';
								} else {
									echo '<span style"color: #000000;font-size: 16px; font-weight: 600;"><strong>Score</strong></span>';
								}
							?>
						</h5>
					</div>
					<div class="profress-scroll">
						<div class="progress rc_progress">
							<?php 
								if(!empty($score)){
									if ($score <= 200) {
										$score_indicator_pos = ($score/200)*17;
									} else if ($score > 200 && $score <= 300) {
										$score_indicator_pos = ($score/300)*34;
									} else if ($score > 300 && $score <= 400) {
										$score_indicator_pos = ($score/400)*51;
									} else {
										$score_width_diff = 687 - $score;
										$score_indicator_pos = 100 - ($score_width_diff/287)*49;
									}
								}
							?>
							<?php if(!empty($score)){ ?>
								<div id="progress-bar-active-score" class="progress-bar-act" role="progressbar" style="left:{{ number_format($score_indicator_pos, 2) }}%; background-color:{{$needle_color}}"></div>
							<?php } ?>
							<div class="progress-bar progress-bar-danger" role="progressbar">
								<span class="lp">101</span><span class="rp">200</span></div>
							<div class="progress-bar progress-bar-warning" role="progressbar">
								<span class="rp">300</span></div>
							<div class="progress-bar progress-bar-info" role="progressbar">
								<span class="rp">400</span></div>
							<div class="progress-bar progress-bar-success" role="progressbar">
								<span class="rp">687</span></div>
						</div>
						<p>
							<h2 style="text-align:center;"><img src="{{config('app.url')}}front_new/images/team/equifaxlogo.svg" border="0" height="50px" width="250px"></h2>
						</p>
					</div>
	                    <!--<div class="progress">
	                        <div class="progress-bar progress-bar-striped redpb active" role="progressbar" style="width:25%">
	                            <span>Needs improvement</span>
	                        </div>
	                        <div class="progress-bar progress-bar-striped orangepb active" role="progressbar" style="width:25%">
	                        <span>Fair</span>
	                        </div>
	                        <div class="progress-bar progress-bar-striped greenpb active" role="progressbar" style="width:25%">
	                        <span>Good</span>
	                        </div>
	                        <div class="progress-bar progress-bar-striped bluepb active" role="progressbar" style="width:25%">
	                        <span>Excellent</span>
	                        </div>
	                    </div> -->
	                </div>
	                <!--<div class="col-md-2"></div> -->
	            </div>
	        <!-- Donut Ends   -->

            <!-- Enquiry Match & Head Quarter   -->
            <div class="row">
                <div class="col-md-6 customerdata">
                    <table id="customers">
                        <tr>
                            <th colspan="2">Enquiry Match
							</th>
                        </tr>
						<tr>
                        	<td style="width: 35%;">Customer Ref</td>
							<td style="width: 65%;">
							<?php
								echo !empty($response['EfxTransmit']['customerReference']) ? $response['EfxTransmit']['customerReference'] : ' - ';
							?>
							</td>
                        </tr>
                        <tr>
							<td style="width: 35%;">EFX ID</td>
							<td style="width: 65%;">
							<?php  echo !empty($response_folder['EfxId']) ? $response_folder['EfxId'] : '-';?>
							</td>
                        </tr>
                        <tr>
							<td>Company Profile</td>
							<td>
							<?php
							echo $request_folder['IdTrait'][0]['AddressTrait'][0]['AddressLine1'].'<br>';
							echo !empty($request_folder['IdTrait'][0]['AddressTrait'][0]['City']['value']) ?
							$request_folder['IdTrait'][0]['AddressTrait'][0]['City']['value']. ", " : '' ;
							echo !empty($request_folder['IdTrait'][0]['AddressTrait'][0]['State']) ? $request_folder['IdTrait'][0]['AddressTrait'][0]['State'].', ' : '';
							echo !empty($request_folder['IdTrait'][0]['AddressTrait'][0]['PostalCode']) ? $request_folder['IdTrait'][0]['AddressTrait'][0]['PostalCode'] : '' ;
							?>
							</td>
                        </tr>
                        <tr>
							<td>Telephone </td>
							<td>
								<?php
									echo !empty($response_folder['Site'][0]['IdTrait']['AddressTrait'][0]['TelephoneTrait'][0]['TelephoneNumber']) ? $response_folder['Site'][0]['IdTrait']['AddressTrait'][0]['TelephoneTrait'][0]['TelephoneNumber'] : '-';
								?>
							</td>
                        </tr>

						<?php

							$taxId = "";
							$ssnId = "";
							if(!empty($response_folder['IdTrait'][0]['IdNumberTrait'])){
								foreach($response_folder['IdTrait'][0]['IdNumberTrait'] as $key => $value){

									if(!empty($value['IdNumberIndicator']) && $value['IdNumberIndicator']=="Tax ID"){

										$taxId = $value['IdNumber'];
									}
									if(!empty($value['IdNumberIndicator']) && $value['IdNumberIndicator']=="SSN"){

										$ssnId = $value['IdNumber'];
									}
								}
							} ?>
							<tr>
								<td>TAX-ID</td>
								<td>
									<?php echo !empty($taxId) ? $taxId : ' - ';?>
								</td>
							</tr>
							<tr>
								<td>SSN</td>
								<td>
									<?php echo !empty($ssnId) ? $ssnId : ' - ';?>
								</td>
							</tr>

							<tr>
								<td>Established</td>
								<td><?php
								if(!empty($response_folder['SOSTrait'][0]['CurrentSOSDataTrait']['IncorporationDate'])){
									echo !empty($response_folder['FirmographicsTrait'][0]['CurrentFirm']['YearStarted']) ? $response_folder['FirmographicsTrait'][0]['CurrentFirm']['YearStarted'] : '-';
								}else{
									echo '-';
								}
								?></td>
							</tr>

						<tr>
							<td>Liability Type</td>
							<td><?php
									echo !empty($response_folder['FirmographicsTrait'][0]['CurrentFirm']['LiabilityIndicator']['value']) ? $response_folder['FirmographicsTrait'][0]['CurrentFirm']['LiabilityIndicator']['value'] : ' - ';
								?>
							</td>
						</tr>
                        <tr>
							<td>Ownership</td>
							<td><?php
								if(!empty($response_folder['FirmographicsTrait'][0]['CurrentFirm']['CompanyOwnership']['value'])){
									echo $response_folder['FirmographicsTrait'][0]['CurrentFirm']['CompanyOwnership']['value'];
								}else{
									echo '-';
								}
								 ?> </td>
                        </tr>
                        <tr>
							<td>Employees</td>
							<td>
							<?php
								echo  !empty($response_folder['FirmographicsTrait'][0]['CurrentFirm']['NumberOfEmployees']['value']) ? $response_folder['FirmographicsTrait'][0]['CurrentFirm']['NumberOfEmployees']['value'] : '';
							?>
							</td>
                        </tr>
                        <tr>
							<td>Location Type</td>
							<td>
							<?php
								if(!empty($response_folder['Site'])) {

									foreach($response_folder['Site'] as $key => $BrValue){
										if($BrValue['EfxId']==$response_folder['EfxId'] && !empty($BrValue['HierarchyProperties'])){
											echo "Branch";
											//echo $BrValue['HierarchyProperties']['BusinessLevel'];
										}
									}
								}else{
									echo " - ";
								}
							?>
							</td>
                        </tr>
                        <tr>
                        <td>Annual Sales</td>
                        <td><?php echo !empty($response_folder['FirmographicsTrait'][0]['CurrentFirm']['AnnualSalesRange']['value']) ? $response_folder['FirmographicsTrait'][0]['CurrentFirm']['AnnualSalesRange']['value'] : '-'; ?></td>
                        </tr>
                        <tr>
                            <td>SIC </td>
                            <td>
							<?php echo !empty($response_folder['FirmographicsTrait'][0]['CurrentFirm']['SICCode']) ? $response_folder['FirmographicsTrait'][0]['CurrentFirm']['SICCode'].", " : '-';
							if(!empty($response_folder['FirmographicsTrait'][0]['CurrentFirm']['SICDescription'])){
								echo $response_folder['FirmographicsTrait'][0]['CurrentFirm']['SICDescription'];
							}
							?> </td>
                        </tr>
                        <tr>
                            <td style="height:65px;">NAICS </td>
                            <td><?php
							echo !empty($response_folder['FirmographicsTrait'][0]['CurrentFirm']['NAICSCode']) ? $response_folder['FirmographicsTrait'][0]['CurrentFirm']['NAICSCode'].", " : '-';

							if(!empty($response_folder['FirmographicsTrait'][0]['CurrentFirm']['NAICSDescription'])){
								echo $response_folder['FirmographicsTrait'][0]['CurrentFirm']['NAICSDescription'];
							}
							?></td>
                        </tr>
                    </table>
            	</div>

				<?php

					//bind data for HeadQuarter
					//Site[n]->HierarchyProperties->BusinessLevel == 'Headquarters'.

					$isHeadquarters =  true;
					$HQEfxId 		=  "";
					$HQEfxIdMain	=  "";
					$HQCurrentFirmKey  = "";
					$HQTelephone 	   = "";
					$LegalBusinessName = "";
					$BusinessName 		= "";
					$HqCompanyAddress     = "";
					$HqCompCity			  = "";
					$HqCompState		  = "";
					$HqCompZip   		  = "";
					$LiabilityIndicator = "";
					$CompanyOwnership   = "";
					$NumberOfEmployees  = "";
					$StartedYear  		= "";
					$BusinessName = "";
					$SICCode = "";
					$SICDesc = "";
					$NAICSCode = "";
					$NAICSDescription = "";
					$AnnualSalesRange = "";

					if(!empty($response_folder['Site'])) {

						//die('DONE');
						foreach($response_folder['Site'] as $key => $HQValue){

							$HQEfxId= $HQValue['EfxId'];
							//echo '<pre>';
							if(!empty($HQValue['HierarchyProperties']) && $HQValue['HierarchyProperties']['BusinessLevel']=="Headquarters"){

								$HQEfxIdMain= $HQValue['EfxId'];
								$HqCompanyAddress = !empty($HQValue['IdTrait']['AddressTrait'][0]['AddressLine1']) ? $HQValue['IdTrait']['AddressTrait'][0]['AddressLine1'] : '';
								$HqCompCity = !empty($HQValue['IdTrait']['AddressTrait'][0]['City']['value']) ? ', '.$HQValue['IdTrait']['AddressTrait'][0]['City']['value'] : '';
								$HqCompState = !empty($HQValue['IdTrait']['AddressTrait'][0]['State']) ? ', '.$HQValue['IdTrait']['AddressTrait'][0]['State'] : '';
								$HqCompZip = !empty($request_folder['IdTrait']['AddressTrait'][0]['PostalCode']) ? $request_folder['IdTrait']['AddressTrait'][0]['PostalCode'] : '' ;
							}
						}

						//echo $HQEfxIdMain."=EFX-ID";

						//get key value on the basis of Headquarters ID.
						if(!empty($HQEfxIdMain)){

								foreach($response_folder['Site'] as $key => $HQValue){

								//$HQEfxId= $HQValue['EfxId'];
								//echo '<pre>';
								if(!empty($HQValue['FirmographicsTrait']['CurrentFirm']['NumberOfEmployees']) && $HQValue['EfxId']==$HQEfxIdMain){


									$HQCurrentFirmKey = $HQValue['FirmographicsTrait']['CurrentFirm'];
									$LiabilityIndicator = !empty($HQCurrentFirmKey['LiabilityIndicator']['value']) ? $HQCurrentFirmKey['LiabilityIndicator']['value'] : '';
									$CompanyOwnership   = !empty($HQCurrentFirmKey['CompanyOwnership']['value']) ? $HQCurrentFirmKey['CompanyOwnership']['value'] : '';
									$NumberOfEmployees  = $HQCurrentFirmKey['NumberOfEmployees']['value'];
									$StartedYear  		= !empty($HQCurrentFirmKey['YearStarted']) ? $HQCurrentFirmKey['YearStarted'] : '';
									$AnnualSalesRange   = !empty($HQCurrentFirmKey['AnnualSalesRange']['value']) ? $HQCurrentFirmKey['AnnualSalesRange']['value'] : '';
								}
								if(!empty($HQValue['IdTrait']['CompanyNameTrait'][0]['BusinessName']) &&  !empty($HQValue['IdTrait']['CompanyNameTrait'][0]['LegalBusinessName']) && $HQValue['EfxId']==$HQEfxIdMain){

									$BusinessName = $HQValue['IdTrait']['CompanyNameTrait'][0]['BusinessName'];

									$LegalBusinessName = $HQValue['IdTrait']['CompanyNameTrait'][0]['LegalBusinessName'];

									$SICCode = !empty($HQValue['IdTrait']['CompanyNameTrait'][0]['SICCode'][0]) ? $HQValue['IdTrait']['CompanyNameTrait'][0]['SICCode'][0] : '';

									$SICDesc = !empty($HQValue['IdTrait']['CompanyNameTrait'][0]['SICDescription']) ? $HQValue['IdTrait']['CompanyNameTrait'][0]['SICDescription'] : '';

									$NAICSCode = !empty($HQValue['IdTrait']['CompanyNameTrait'][0]['NAICSCode'][0]) ? $HQValue['IdTrait']['CompanyNameTrait'][0]['NAICSCode'][0] : '';

									$NAICSDescription = !empty($HQValue['IdTrait']['CompanyNameTrait'][0]['NAICSDescription']) ? $HQValue['IdTrait']['CompanyNameTrait'][0]['NAICSDescription'] : '';
								}

								if(!empty($HQValue['IdTrait']['AddressTrait'][0]['TelephoneTrait']) && $HQValue['EfxId']==$HQEfxIdMain){

									$HQTelephone = $HQValue['IdTrait']['AddressTrait'][0]['TelephoneTrait'][0]['TelephoneNumber'];
								}
							}
						}
					}
				?>
                <div class="col-md-6 customerdata">
                    <table id="customers">
                        <tr>
							<th colspan="2" >Headquarters Site</th>
						</tr>
							<td>EFX ID</td>
							<td style="width: 65%;"><?php echo $HQEfxIdMain; ?></td>
                        <tr>
							<td style="height:110px;">Company Profile</td>
							<td>
								<?php
									echo !empty($BusinessName) ? $BusinessName : '-';
									if (!empty($LegalBusinessName)) {
										echo '<br>'."Legal Business Name : ";
										echo $LegalBusinessName;
									}
									
									
									echo '<br>'.' '.$HqCompanyAddress;
									echo $HqCompCity.$HqCompState.$HqCompZip;
								?>
							</td>
                        </tr>

                        <tr>
							<td>Telephone </td>
							<td><?php echo !empty($HQTelephone) ? $HQTelephone : ' - '; ?></td>
                        </tr>
                        <tr>
							<td>Tax ID</td>
							<td> - </td>
                        </tr>
						<tr>
							<td>SSN</td>
							<td> - </td>
                        </tr>
                        <tr>
							<td>Established</td>
							<td>
								<?php echo !empty($StartedYear) ? $StartedYear : ' - '; ?>
							</td>
                        </tr>
                        <tr>
							<td>Liability Type</td>
							<td><?php
									echo !empty($LiabilityIndicator) ? $LiabilityIndicator : ' - ';
								?>
							</td>
                        </tr>
                        <tr>
							<td>Ownership</td>
							<td><?php
									echo !empty($CompanyOwnership) ? $CompanyOwnership : ' - ';
								?>
							</td>
                        </tr>
                        <tr>
							<td>Employees</td>
							<td><?php
									echo !empty($NumberOfEmployees) ? $NumberOfEmployees : ' - ';
								?>
							</td>
                        </tr>
                        <tr>
                        <td>Location Type</td>
                        <td><?php echo (!empty($HQEfxIdMain)) ? "Headquarters" : ' - '; ?> </td>
                        </tr>
                        <tr>
                        <td>Annual Sales</td>
                        <td><?php echo (!empty($AnnualSalesRange)) ? $AnnualSalesRange : ' - '; ?></td>
                        </tr>
                        <tr>
                            <td>SIC </td>
                            <td><?php echo (!empty($SICCode)) ? $SICCode.', '.$SICDesc : ' - '; ?> </td>
                        </tr>
                        <tr>
                           <td style="height:65px;">NAICS </td>
                           <td><?php echo (!empty($NAICSCode)) ? $NAICSCode.', '.$NAICSDescription : ' - '; ?></td>
                        </tr>
                    </table>
                </div>
            </div>

            <!-- Enquiry Match & Head Quarter Ends  -->
            <!-- Report High lights -->
            <div class="row">
                <div class="col-md-12 reportdata">
                    <table id="reportdata">
                    <tr>
                        <th colspan="2">Report Highlights</th>
                    </tr>
                    <tr>
                        <td style="width: 50%; background-color:#f2c50c; text-align: left; font-weight: 600; padding-left: 50px;">Since <?php $RecentSinceDate = isset($response_folder['ReportAttributes']['RecentSinceDate']) ? General::getFormatedDate($response_folder['ReportAttributes']['RecentSinceDate']) : "NA"; echo $RecentSinceDate;?> </td>
                        <td style="width: 50%; background-color:#f2c50c; text-align: left; font-weight: 600; padding-left: 50px;">As of <?php echo General::getFormatedDate($response['EfxTransmit']['CommercialCreditReport'][0]['Header']['DateOfRequest']); ?></td>
                    </tr>

					 <tr>
                        <td>Accounts Updated : <?php echo !empty($response_folder['ReportAttributes']['NonFinancialSummary']['SummaryAttributes']['NewUpdates']) ? $response_folder['ReportAttributes']['NonFinancialSummary']['SummaryAttributes']['NewUpdates'] : '-' ;?>
						</td>
					    <td>Number Of Public Records :
							<?php
								$noOfbankrup   = 0;
								$noOfJudg      = 0;
								$noOfLiens     = 0;

								if(!empty($response_folder['ReportAttributes']['PublicRecordsSummary']['NumberOfJudgments'])){
									$noOfJudg = $response_folder['ReportAttributes']['PublicRecordsSummary']['NumberOfJudgments'];
								}
								if(!empty($response_folder['ReportAttributes']['PublicRecordsSummary']['NumberOfLiens'])){
									$noOfLiens = $response_folder['ReportAttributes']['PublicRecordsSummary']['NumberOfLiens'];
								}
								if(!empty($response_folder['ReportAttributes']['PublicRecordsSummary']['NumberOfBankruptcies'])){
									$noOfbankrup = $response_folder['ReportAttributes']['PublicRecordsSummary']['NumberOfBankruptcies'];
								}
								echo $noOfJudg + $noOfLiens +$noOfbankrup;
							?>
						</td>
					</tr>

                    <tr>
                        <td>Accounts Opened : <?php
							if(!empty($response_folder['ReportAttributes']['OpenNonFiByIndGroup']['OpenSummaryAttributes'][0]['NumOpenAccts'])){
								echo $response_folder['ReportAttributes']['OpenNonFiByIndGroup']['OpenSummaryAttributes'][0]['NumOpenAccts'];
							}else{
								echo '0';
							}
							?>
						</td>
                        <td>Open Accounts : <?php
							if(!empty($response_folder['ReportAttributes']['OpenNonFiByIndGroup']['OpenSummaryAttributesTotals']['NumOpenAccts'])){
								echo $response_folder['ReportAttributes']['OpenNonFiByIndGroup']['OpenSummaryAttributesTotals']['NumOpenAccts'];
							}
							?>
						</td>
                    </tr>
					<tr>
                        <td>Accounts Closed :
							<?php
							$closeAcc = 0;
							if(!empty($response_folder['ReportAttributes']['NonFinancialSummary']['SummaryAttributes']['NumClosed'])){
								echo $response_folder['ReportAttributes']['NonFinancialSummary']['SummaryAttributes']['NumClosed'];
							}else{
								echo $closeAcc = 0;
							}
							?>
						</td>

                        <td>Closed Accounts :
							<?php
							if(!empty($response_folder['ReportAttributes']['ClosedNonFiByIndGroup']['ClosedSummaryAttributesTotals']['NumCurrentAccts'])){
								echo $response_folder['ReportAttributes']['ClosedNonFiByIndGroup']['ClosedSummaryAttributesTotals']['NumCurrentAccts'];
							}else{
								echo '0';
							}
							?>
						</td>

                    </tr>

                    <tr>
                        <td>Accounts Delinquent : <?php
							if(!empty($response_folder['ReportAttributes']['NonFinancialSummary']['SummaryAttributes']['NewDelinquencies'])){
								echo $response_folder['ReportAttributes']['NonFinancialSummary']['SummaryAttributes']['NewDelinquencies'];
							}else{
								echo '-';
							}
							?>
						</td>
						<td>Total Past Due Amount :
							<?php echo !empty($response_folder['ReportAttributes']['NonFinancialSummary']['SummaryAttributes']['OpenTotalPastDue']['value']) ? "$" .number_format($response_folder['ReportAttributes']['NonFinancialSummary']['SummaryAttributes']['OpenTotalPastDue']['value']) : '-' ;
							?>
						</td>
                    </tr>
                    <tr>
                        <td>Inquiries : <?php
							$non_banking_count = array();
							$non_banking_array = array();
							$banking_count	   = array();
							$banking_array     = array();
							if(!empty($response['EfxTransmit']['CommercialCreditReport'][0]['Folder']['Inquiries'])){
									foreach($response['EfxTransmit']['CommercialCreditReport'][0]['Folder']['Inquiries'] as $key => $inquery_val){
										if($inquery_val['Industry']['value']=='Non-Financial'){
											$non_banking_count[] = $inquery_val['Industry']['value'];
											$non_banking_array[] = $inquery_val['InquiryDate'];
										}else{
											$banking_count[] = $inquery_val['Industry']['value'];
											$banking_array[] = $inquery_val['InquiryDate'];
										}


									}
								echo count($non_banking_count)+count($banking_count);
							} ?>
						</td>
                        <td>Most Severe Status : <?php
						if(!empty($response_folder['ReportAttributes']['NonFinancialSummary']['SummaryAttributes']['MostSevereStatus24Months'])){
							echo $response_folder['ReportAttributes']['NonFinancialSummary']['SummaryAttributes']['MostSevereStatus24Months'];
						}else{
							echo '-';
						}
						?></td>
                    </tr>

                    <tr>
                        <td>Most Severe Status :  <?php
							if(!empty($response_folder['ReportAttributes']['NonFinancialSummary']['SummaryAttributes']['NewMostSevStatus'])){
							echo $response_folder['ReportAttributes']['NonFinancialSummary']['SummaryAttributes']['NewMostSevStatus']; }else{
								echo '-';
							}
							?>
						</td>
						<?php
						//looping data at here...

							$singleHiCreditExtOrBalOwed = array();
							$singleHighestTotalPastDue  = array();

							if(!empty($response_folder['ReportAttributes']['OpenNonFiByIndGroup']['OpenSummaryAttributes'])){

								foreach($response_folder['ReportAttributes']['OpenNonFiByIndGroup']['OpenSummaryAttributes'] as $key => $grp_dtl){

									$singleHiCreditExtOrBalOwed[] = $grp_dtl['SingleHiCreditExtOrBalOwed'];
									$singleHighestTotalPastDue[]  = $grp_dtl['SingleHighestTotalPastDue'];
								}
							}
						//ends of data looping.

						$all_payment_history = array();
						$i = 0;

						$var2 =10;
						$creditLimitAmtSum        = array();
						$creditLimitBalanceAmtSum = array();
						$arrayAccOpenDate 		  = array();
						$sumOfYrsAllOpenAcc       = 0;
						$sumCreditUsageAmt        = array();
						$arrNonFinAccPaymentHistryParams = array();
						$arrFinAccPaymentHistryParams = array();
						$arrNonFinClosedAccPaymentHistryParams = array();
						$arrAccPaymentHistryAllAcc = array();
						//AcctOpenedDate


					if(!empty($response['EfxTransmit']['CommercialCreditReport'][0]['Folder']['TradeInfo'])){
						foreach($response['EfxTransmit']['CommercialCreditReport'][0]['Folder']['TradeInfo'] as $key => $trade_val){

							$HiCreditOrOrigLoanAmountValue = 0;
							$HiCreditOrOrigLoanAmountValue = !empty($trade_val['CurrentTrade']['HiCreditOrOrigLoanAmount']['value']) ? $trade_val['CurrentTrade']['HiCreditOrOrigLoanAmount']['value'] : 0;

							$BalanceAmountValue = 0;
							$BalanceAmountValue = !empty($trade_val['CurrentTrade']['BalanceAmount']['value']) ? $trade_val['CurrentTrade']['BalanceAmount']['value'] : 0;

							$reportedDate = !empty($trade_val['CurrentTrade']['TraitActivity']['ReportedDate']) ? $trade_val['CurrentTrade']['TraitActivity']['ReportedDate'] : '';

							$IndustryGroup = !empty($trade_val['CurrentTrade']['TraitActivity']['InformationSource']['IndustryGroup']) ? $trade_val['CurrentTrade']['TraitActivity']['InformationSource']['IndustryGroup'] : '';
							$AccountReferenceNo = !empty($trade_val['CurrentTrade']['AccountReference']['value']) ? $trade_val['CurrentTrade']['AccountReference']['value'] : '';

							// Error handling and assigning values
							$AgingStatus = !empty($trade_val['CurrentTrade']['AgingStatus']) ? $trade_val['CurrentTrade']['AgingStatus'] : '';
							$PaymentHistoryProfile = !empty($trade_val['CurrentTrade']['PaymentHistoryProfile'])?$trade_val['CurrentTrade']['PaymentHistoryProfile'] : '';

							$OpenInd = !empty($trade_val['CurrentTrade']['OpenInd'])? $trade_val['CurrentTrade']['OpenInd'] : '';
							$ClosedInd = !empty($trade_val['CurrentTrade']['ClosedInd']) ? $trade_val['CurrentTrade']['ClosedInd'] : '';
							$NonFiAcctInd = !empty($trade_val['CurrentTrade']['NonFiAcctInd'])? $trade_val['CurrentTrade']['NonFiAcctInd'] : '';

							//Creating array only for NonFinancial Account.
							if(!empty($trade_val['CurrentTrade']['NonFiAcctInd']) && $trade_val['CurrentTrade']['NonFiAcctInd']=="Y" && $trade_val['CurrentTrade']['FiAcctInd']=="N"){

								$creditLimitAmtSum[]  = $HiCreditOrOrigLoanAmountValue;
								$creditLimitBalanceAmtSum[] = $BalanceAmountValue;

								$sumCreditUsageAmt[] = $HiCreditOrOrigLoanAmountValue - $BalanceAmountValue;

								$AcctOpenedDate = !empty($trade_val['CurrentTrade']['AcctOpenedDate']) ? $trade_val['CurrentTrade']['AcctOpenedDate'] : '';

								$arrayAccOpenDate[] = $AcctOpenedDate;
								$sumOfYrsAllOpenAcc+= General::getNumberOfYearsFromDate(date("m/d/Y"), $AcctOpenedDate);
							}
							
							// re-usable payment history params array
							$arrPaymentHistoryParams = array(
									"IndustryGroup" => $IndustryGroup,
									"ReportedDate" => $reportedDate,
							   		"AccountReferenceNo" => $AccountReferenceNo,
							   		"HiCreditOrOrigLoanAmount" => $HiCreditOrOrigLoanAmountValue,
							   		"BalanceAmount" => $BalanceAmountValue,
							   		"AgingStatus" => $AgingStatus,
							   		"PaymentHistoryProfile" => $PaymentHistoryProfile,
							   		"OpenInd" => $OpenInd,
							   		"ClosedInd" => $ClosedInd,
							   		"NonFiAcctInd" => $NonFiAcctInd
							   	);

							//Creating array only for NonFinancial Account.
							if(!empty($trade_val['CurrentTrade']['NonFiAcctInd']) && $trade_val['CurrentTrade']['NonFiAcctInd']=="Y"  && $trade_val['CurrentTrade']['ClosedInd'] == "N"){

								$arrNonFinAccPaymentHistryParams[] = $arrPaymentHistoryParams;

							}

							//get all accounts payment History.
							if(!empty($trade_val['CurrentTrade']['NonFiAcctInd'])){
								$arrAccPaymentHistryAllAcc[] = $trade_val['CurrentTrade']['PaymentHistoryProfile'];
							}

							//Creating array only for NonFinancial Account.
							if(!empty($trade_val['CurrentTrade']['FiAcctInd']) && $trade_val['CurrentTrade']['FiAcctInd']=="Y"){

								$arrFinAccPaymentHistryParams[] = $arrPaymentHistoryParams;

							}

							//Creating array only for NonFinancial Account.
							if(!empty($trade_val['CurrentTrade']['NonFiAcctInd']) && $trade_val['CurrentTrade']['NonFiAcctInd']=="Y" && $trade_val['CurrentTrade']['ClosedInd'] == "Y"){

								$arrNonFinClosedAccPaymentHistryParams[] = $arrPaymentHistoryParams;

							}

							/*$arrFinAccPaymentHistryParams[] = array("IndustryGroup"=> , "ReportedDate"=> , "AccountReferenceNo"=> , "HiCreditOrOrigLoanAmount"=> , "BalanceAmount"=> , "AgingStatus"=> , "PaymentHistoryProfile"=> ,"OpenInd"=> , "ClosedInd"=> , "NonFiAcctInd"=>"" );*/

							$org_name =
							 $all_payment_history[] ="Group-".$i.':'.$trade_val['CurrentTrade']['PaymentHistoryProfile'] ;
							$i = $i+1;
						}

					}


						//dd($arrayAccOpenDate);

						//calculataion of values.
						//$arrAllZeros = array();
						//$arrAllBs	 = array();
						$arrOnTimePayment	 = array();
						$arrDelayPayment	 = array();
						$arrOfTotalMonths	 = array();

						foreach($arrAccPaymentHistryAllAcc as $key =>$payHis_val)
						{

							$data = $payHis_val;
							$formatted = implode(',',str_split($data));
							$array = explode(",",$formatted);
							foreach($array as $val)
							{
								if($val==0 || $val=="B" || $val=="C" || $val==7){
									$arrOnTimePayment [] = $val;
									$arrOfTotalMonths [] = $val;
								}else{
									$arrDelayPayment[]   = $val;
									$arrOfTotalMonths [] = $val;
								}
							}
						}

						//echo '<pre>';
						//print_r($arrAllZeros);
						//echo "On-TIME=SUM=".count($arrOnTimePayment).'<br>';
						//echo "DELAY=SUM=".count($arrDelayPayment).'<br>';
						//echo '<pre>';
						//print_r($arrAllBs);
						//dd($arrAccPaymentHistryAllAcc);
						//dd($arrNonFinClosedAccPaymentHistryParams);
						//echo "SUM_CRET_USAGE_AMT".array_sum($sumCreditUsageAmt);
						//echo "SUM_CRET_BALANCE_LIMIT".array_sum($creditLimitBalanceAmtSum);
						//dd($sumOfYrsAllOpenAcc)/count($arrayAccOpenDate);
						//dd(array_slice($all_payment_history, -2 ));
						?>

                        <td>Single Highest Balance Due : <?php
						if(!empty($response_folder['ReportAttributes']['NonFinancialSummary']['SummaryAttributes']['OpenTotalPastDue']['value'])){

							echo !empty($singleHighestTotalPastDue) ? "$" .number_format(max($singleHighestTotalPastDue)) : '-';
							//echo "$" .number_format($response_folder['ReportAttributes']['NonFinancialSummary']['SummaryAttributes']['OpenTotalPastDue']['value']);
						}
						?></td>
                    </tr>

                    <tr>
						<td>Charge Off Amount :
							<?php
								if(!empty($response_folder['ReportAttributes']['NonFinancialSummary']['SummaryAttributes']['ChargeOffAmt']['value'])){
									echo "$" .number_format($response_folder['ReportAttributes']['NonFinancialSummary']['SummaryAttributes']['ChargeOffAmt']['value']);
								}else {
									echo '$0';
								}
							?>
						</td>

                        <td>Total Current Credit Exposure : <?php
						if(!empty($response_folder['ReportAttributes']['NonFinancialSummary']['SummaryAttributes']['TotalExposure']['value'])){
							echo "$" .number_format($response_folder['ReportAttributes']['NonFinancialSummary']['SummaryAttributes']['TotalExposure']['value']);
						}else{
							echo "-";
						}
						?></td>
                    </tr>

                    <tr>
                        <td>Highest Credit Extended : <?php
							if(!empty($response_folder['ReportAttributes']['NonFinancialSummary']['SummaryAttributes']['NewHiCreditExt']['value'])){
								echo "$" .number_format($response_folder['ReportAttributes']['NonFinancialSummary']['SummaryAttributes']['NewHiCreditExt']['value']);
							}else{
								echo '-';
							}
							?>
						</td>
                        <td>Highest Credit Extended :
							<?php echo !empty($response_folder['ReportAttributes']['NonFinancialSummary']['SummaryAttributes']['HighestCredit']['value']) ? "$" .number_format($response_folder['ReportAttributes']['NonFinancialSummary']['SummaryAttributes']['HighestCredit']['value']) : '-';
							?>
						</td>
                    </tr>

					<tr>
                        <td></td>
						<td> </td>
                        <!--<td>Number Of Public Records:
							<?php
								$noOfbankrup   = 0;
								$noOfJudg      = 0;
								$noOfLiens     = 0;

								if(!empty($response_folder['ReportAttributes']['PublicRecordsSummary']['NumberOfJudgments'])){
									$noOfJudg = $response_folder['ReportAttributes']['PublicRecordsSummary']['NumberOfJudgments'];
								}
								if(!empty($response_folder['ReportAttributes']['PublicRecordsSummary']['NumberOfLiens'])){
									$noOfLiens = $response_folder['ReportAttributes']['PublicRecordsSummary']['NumberOfLiens'];
								}
								if(!empty($response_folder['ReportAttributes']['PublicRecordsSummary']['NumberOfBankruptcies'])){
									$noOfbankrup = $response_folder['ReportAttributes']['PublicRecordsSummary']['NumberOfBankruptcies'];
								}
								echo $noOfJudg + $noOfLiens +$noOfbankrup;
							?>
						</td> -->
                    </tr>

                    </table>
                </div>
            </div>


			<!-- Public Deed Summary -->
            <div class="row">
				<div class="col-md-2">
                    <!--<div class="donutchart1">
                        <h3>Public Records</h3>
                        <canvas id="chDonut7"></canvas>
                        <div class="pie-value-txt1">
                        <span class="pie-value1"><strong>
						<?php
						/*if(!empty($response_folder['ReportAttributes']['PublicRecordsSummary']['NumberOfJudgments']) && !empty($response_folder['ReportAttributes']['PublicRecordsSummary']['NumberOfLiens'])){
						echo $response_folder['ReportAttributes']['PublicRecordsSummary']['NumberOfJudgments'] + $response_folder['ReportAttributes']['PublicRecordsSummary']['NumberOfLiens'];

						} */ ?></strong><br/>Bad</span>
                        </div>
                    </div>
					-->
                </div>

                <div class="col-md-8 publicdeeds">
                    <table id="publicdeeds">
                        <tr>
                        <th colspan="4">Public Records Summary</th>
                        </tr>
                        <tr>
                        <td><?php
						if(!empty($response_folder['ReportAttributes']['PublicRecordsSummary']['NumberOfJudgments'])){
							//echo ngettext('Judgement', 'Judgements', $response_folder['ReportAttributes']['PublicRecordsSummary']['NumberOfJudgments']);
							echo "Judgements";
						}else{
							echo "Judgements";
						}
						 ?> </td>
                        <td>
							<?php
							if(!empty($response_folder['ReportAttributes']['PublicRecordsSummary']['NumberOfLiens'])){
								//echo ngettext('Lien', 'Liens', $response_folder['ReportAttributes']['PublicRecordsSummary']['NumberOfLiens']);
								echo "Liens";
							}else{

								
								echo "Liens";
							}	
							?>
						</td>
                        <td>
							<?php
							if(!empty($response_folder['ReportAttributes']['PublicRecordsSummary']['NumberOfBankruptcies'])){
								//echo ngettext('Bankruptcy', 'Bankruptcies', $response_folder['ReportAttributes']['PublicRecordsSummary']['NumberOfBankruptcies']);
								echo 'Bankruptcies';
							}else{
								echo 'Bankruptcies';
							}	

							?>
						</td>
                        </tr>
                        <tr>
							<td>
								<?php if(!empty($response_folder['ReportAttributes']['PublicRecordsSummary']['NumberOfJudgments'])){
								echo $response_folder['ReportAttributes']['PublicRecordsSummary']['NumberOfJudgments']; }else{ echo '0' ;} ?>
							</td>
							<td>
								<?php if(!empty($response_folder['ReportAttributes']['PublicRecordsSummary']['NumberOfLiens'])){
									echo $response_folder['ReportAttributes']['PublicRecordsSummary']['NumberOfLiens'];
								}else{ echo '0' ;}
								?>
							</td>

							<td>
								<?php if(!empty($response_folder['BankruptcyTrait'])){
									echo count($response_folder['BankruptcyTrait']);
								}else{ echo '0'; }
								?>
							</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12 publicdeeds2">
                    <table id="publicdeeds2">
                    <tr>
                        <th colspan="8">Public Record Details</th>
                    </tr>
                    <tr>
                        <td style="width: 16%; background-color:#f2c50c; text-align: left; font-weight: 600; ">Type</td>
                        <td style="width: 15%; background-color:#f2c50c; text-align: left; font-weight: 600; ">Plaintiff</td>
						<td style="width: 15%; background-color:#f2c50c; text-align: left; font-weight: 600; ">Defendant</td>
                        <td style="width: 16%; background-color:#f2c50c; text-align: left; font-weight: 600; ">Ref Number</td>
                        <td style="width: 16%; background-color:#f2c50c; text-align: left; font-weight: 600; ">Date</td>
                        <td style="width: 16%; background-color:#f2c50c; text-align: left; font-weight: 600; ">Amount</td>
						<td style="width: 16%; background-color:#f2c50c; text-align: left; font-weight: 600; ">Status</td>
                    </tr>

					<?php
					if(!empty($response_folder['JudgmentTrait'])){ ?>

                    <tr>
						<?php

							if(!empty($response_folder['JudgmentTrait'])){
									$isJudgments = false;
									$Jud_value   = "";
									foreach($response_folder['JudgmentTrait'] as $key => $Jud_value){ ?>

										<td style="font-size:16px; font-weight:strong; color:#000000;">
											<?php
												if($isJudgments==false){
													echo "Judgments";
													$isJudgments =true;
												}
											?>
										</td>
										<td>
											<?php
												if(!empty($Jud_value['PublicRecordID'][1]) && $Jud_value['PublicRecordID'][1]['DebtorCode']=="Plaintiff"){
													echo $Jud_value['PublicRecordID'][1]['Folder']['IdTrait'][0]['CompanyNameTrait'][0]['BusinessName'];
												}
											?>
										</td>
										<td>
											<?php
												if(!empty($Jud_value['PublicRecordID'][0]) && $Jud_value['PublicRecordID'][0]['DebtorCode']=="Defendant"){
													echo $Jud_value['PublicRecordID'][0]['Folder']['IdTrait'][0]['CompanyNameTrait'][0]['BusinessName'];
												}
											?>
										</td>
										<td>
											<?php echo !empty($Jud_value['CurrentPublicRecord']['CaseNumber']) ? $Jud_value['CurrentPublicRecord']['CaseNumber'] : ''; ?>
										</td>
										<td>
											<?php echo !empty($Jud_value['CurrentPublicRecord']['FilingDate']) ? General::getFormatedDate($Jud_value['CurrentPublicRecord']['FilingDate']) : ' - '; ?>
										</td>
										<td>
											<?php echo !empty($Jud_value['CurrentPublicRecord']['TotalLiabilities']['value']) ? "$" .number_format($Jud_value['CurrentPublicRecord']['TotalLiabilities']['value']) : '$0'; ?>
										</td>
										<td>
											<?php echo !empty($Jud_value['CurrentPublicRecord']['DispositionStatus']) ? $Jud_value['CurrentPublicRecord']['DispositionStatus'] : ''; ?>
										</td>

									</tr>
									<?php
								}
							}

						}
						?>
                    <tr>
						<?php
							//Code For Lien.
							if(!empty($response_folder['LienTrait'])){

									$isLiens = false;
									foreach($response_folder['LienTrait'] as $key => $Lien_value){ ?>
										<td style="font-size:16px; font-weight:strong; color:#000000;">
											<?php
												if($isLiens==false){

													//echo ngettext('Lien', 'Liens', $response_folder['ReportAttributes']['PublicRecordsSummary']['NumberOfLiens']);
													echo "Liens";
													$isLiens =true;
												}
											?>
										</td>
										<td>
											<?php
												if(!empty($Lien_value['PublicRecordID'][1]) && $Lien_value['PublicRecordID'][1]['DebtorCode']=="Plaintiff"){
													echo $Lien_value['PublicRecordID'][1]['Folder']['IdTrait'][0]['CompanyNameTrait'][0]['BusinessName'];
												}
											?>
										</td>
										<td>
											<?php
												if(!empty($Lien_value['PublicRecordID'][0]) && $Lien_value['PublicRecordID'][0]['DebtorCode']=="Defendant"){
													echo $Lien_value['PublicRecordID'][0]['Folder']['IdTrait'][0]['CompanyNameTrait'][0]['BusinessName'];
												}
											?>
										</td>
										<td>
											<?php echo !empty($Lien_value['CurrentPublicRecord']['CaseNumber']) ? $Lien_value['CurrentPublicRecord']['CaseNumber'] : ''; ?>
										</td>

										<td>
											<?php echo !empty($Lien_value['CurrentPublicRecord']['FilingDate']) ? General::getFormatedDate($Lien_value['CurrentPublicRecord']['FilingDate']) : ' - ';
											?>


										</td>
										<td>
											<?php echo !empty($Lien_value['CurrentPublicRecord']['TotalLiabilities']['value']) ? "$" .number_format($Lien_value['CurrentPublicRecord']['TotalLiabilities']['value']) : '$0'; ?>
										</td>
										<td>
											<?php echo !empty($Lien_value['CurrentPublicRecord']['DispositionStatus']) ? $Lien_value['CurrentPublicRecord']['DispositionStatus'] : ''; ?>
										</td>

									</tr>
									<?php
								}
							}

						//Code For Bankruptcy
						if(!empty($response_folder['BankruptcyTrait'])){ ?>

						<tr>
							<?php
							if(!empty($response_folder['BankruptcyTrait'])){

									$isBankruptcy=false;

									foreach($response_folder['BankruptcyTrait'] as $key => $Bky_value){ ?>

										<td style="font-size:16px; font-weight:strong; color:#000000;">
											<?php
												if($isBankruptcy==false){
													echo "Bankruptcy";
													$isBankruptcy =true;
												}
											?>
										</td>

										<td>
											<?php
												if(!empty($Bky_value['PublicRecordID'][2]['DebtorCode']) && $Bky_value['PublicRecordID'][2]['DebtorCode']=="Trustee"){

													echo $Bky_value['PublicRecordID'][2]['Folder']['IdTrait'][0]['CompanyNameTrait'][0]['BusinessName'];
												}
											?>
										</td>
										<td>
											<?php
												if(!empty($Bky_value['PublicRecordID'][0]['DebtorCode']) && $Bky_value['PublicRecordID'][0]['DebtorCode']=="Defendant"){
													echo $Bky_value['PublicRecordID'][0]['Folder']['IdTrait'][0]['CompanyNameTrait'][0]['BusinessName'];
												}
											?>
										</td>
										<td>
											<?php echo !empty($Bky_value['CurrentPublicRecord']['CaseNumber']) ? 'XXXXXXX' : ''; ?>
										</td>

										<td>
											<?php
												if(!empty($Bky_value['CurrentPublicRecord']['FilingDate'])){
													echo General::getFormatedDate($Bky_value['CurrentPublicRecord']['FilingDate']);
												}else{
													echo ' - ';
												}
											?>
										</td>
										<td>
											<?php echo !empty($Bky_value['CurrentPublicRecord']['TotalLiabilities']['value']) ? "$" .number_format($Bky_value['CurrentPublicRecord']['TotalLiabilities']['value']) : '$0'; ?>
										</td>

										<td>
											<?php
											if(!empty($Bky_value['CurrentPublicRecord']['DispositionStatus'])){
												echo $Bky_value['CurrentPublicRecord']['DispositionStatus'];
											} ?>
										</td>

									</tr>
									<?php
								}
							}
						}
						?>

                    </table>
                </div>
            </div>
            <!--Public Deeds Summary Ends-->
			<!-- PUBLIC DEED ENDS HERE  -->
            <!-- Report HighLights Ends-->

            <!-- Non Banking Starts-->
            <div class="row">
                <div class="col-md-12">
                    <div class="non-headtxt"><h2>Non-Banking</h2>
                    <span></span>
                    </div>
                </div>
            </div>
            <!-- Heading Ends-->
            <!-- Non Banking Blocks-->
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
				}
			?>

            <div class="row justify-content-lg-start justify-content-center">

                <div class="col-md-4">
                    <div class="statistics_item">
                    <h3 class="counter">

					<?php
						if(!empty($arrayAccOpenDate)){

							$maxDate = date('d-m-Y',strtotime($min_date));
							echo $maxYrs  = General::getNumberOfYearsFromDate(date("m/d/Y"), $maxDate);
							echo  '<span>'.$maxYrs == 1 ? 'yr':' yrs'.'</span>';
						}else{
							echo "NA";
						}

					?></h3>
                    <p>Credit Age</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="statistics_item">
                    <h3 class="counter">
						<?php
							$sumVal = "";
							if(!empty($creditLimitAmtSum) && !empty($creditLimitBalanceAmtSum)){

								if (array_sum($creditLimitAmtSum) > 0) {
									$sumVal = array_sum($creditLimitBalanceAmtSum)/array_sum($creditLimitAmtSum)*100;
								}
								echo round($sumVal,0,PHP_ROUND_HALF_UP).'<span>'.'%'.'</span>';
							}else{
								echo "NA";
							}
						?>
					</h3>
                    <p>Credit Usage</p>
                    </div>
                </div>
				<div class="col-md-4">
                    <div class="statistics_item">
                    <h3 class="counter">
					<?php echo count($non_banking_count); ?>
					</h3>
                    <p>Enquires</p>
                    </div>
                </div>
            </div>

            <div class="row justify-content-lg-start justify-content-center">

                <div class="col-md-4">
                    <div class="statistics_item">
                    <h3 class="counter">
						<?php

							$avgPayCnt = "";
							if(!empty($arrOnTimePayment) && !empty($arrOfTotalMonths)){

								$avgPayCnt =  count($arrOnTimePayment)/count($arrOfTotalMonths)*100;
								echo round($avgPayCnt,0,PHP_ROUND_HALF_UP).'%';
							}else{
								echo "NA";
							}
						?>
					</h3>
                    <p>Payment Score</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="statistics_item">
                    <h3 class="counter"><?php if(!empty($response_folder['ReportAttributes']['NonFinancialSummary']['SummaryAttributes']['NumberOfAccounts'])){ echo $response_folder['ReportAttributes']['NonFinancialSummary']['SummaryAttributes']['NumberOfAccounts']; }else{ echo "NA"; } ?></h3>
                    <p>Total Accounts</p>
                    </div>
                </div>
            </div>

            <!-- Non Banking blocks Ends-->

            <!-- Credit Age Summary -->
            <div class="row">
                <div class="col-md-2">
                    <!--<div class="donutchart1">
                        <h3>Credit Age</h3>
                        <canvas id="chDonut8"></canvas>
                        <div class="pie-value-txt1">
                            <span class="pie-value1"><strong>16yrs</strong><br/> Good</span>
                        </div>
                    </div>
					-->
                </div>

                <div class="col-md-8 publicdeeds">
                    <table id="publicdeeds">
                        <tr>
                        <th colspan="2">Credit Age Summary</th>
                        </tr>
                        <tr>
                        <td style="width: 50%;">Oldest Account</td>
                        <td style="width: 50%;">
							<?php
								if(!empty($arrayAccOpenDate)){

									$maxDate = date('d-m-Y',strtotime($min_date));
									echo $maxYrs  = General::getNumberOfYearsFromDate(date("m/d/Y"), $maxDate);
									echo $yearText = $maxYrs == 1 ? 'yr':' yrs';
								}
							?>
						</td>
                        </tr>
                        <tr>
                        <td>Newest Account</td>
                        <td><?php

								/*echo $minDate = date('d-m-Y',strtotime($max_date));
								//use Carbon\Carbon;
								echo $date1 = "2020-02-29";
								//$date2 = "2009-06-26";
								echo $date2 = date('Y-m-d');
								echo $diff  = abs(strtotime($date2) - strtotime($date1));
								echo $years = floor($diff / (365*60*60*24)) . "=No. Of Yrs";

								$d1 = new DateTime('2021-02-18');
								$d2 = new DateTime('2020-03-29');
								$diff = $d2->diff($d1);
								echo "YRS=" . $diff->y . "=YRS";
								*/
								//$startDate = Carbon::parse($date1);
								//$endDate   = Carbon::parse($date2);
								//echo $diff      = $startDate->diffInYears($endDate);

								 //$datetime1 = new DateTime("3-28-2020");
								 //$datetime2 = new DateTime("2-18-2021");
								 //$difference = $datetime1->diff($datetime2);
								 //echo "<br>";
								 //echo 'Difference: '.$difference->y.'=NEXT';

								if(!empty($arrayAccOpenDate)){

									$minDate = date('d-m-Y',strtotime($max_date));
									$minYrs  = General::getNumberOfYearsFromDate(date("m/d/Y"), $minDate);
									if($minYrs==0){
										echo $minYrs = "1";
									}else{
										echo $minYrs;
									}
									echo $yearText = $minYrs == 1 ? ' yr':' yrs';
								}
							?></td>
                        </tr>
                        <tr>
                        <td>Average Account</td>
                        <td>
						<?php
							$avgCnt = "";
							if(!empty($sumOfYrsAllOpenAcc) && !empty($arrayAccOpenDate)){
								$avgCnt =  $sumOfYrsAllOpenAcc/count($arrayAccOpenDate);
								echo round($avgCnt,0,PHP_ROUND_HALF_UP);
								echo $avgCnt == 1 ? ' yr':' yrs';
							}

						?>
						</td>
                        </tr>
                    </table>
                </div>
            </div>

            <div class="row">
                <!--<div class="col-md-12 creditage">
                    <table id="creditage">
                    <tr>
                        <th colspan="2">Credit Age Details</th>
                    </tr>
                    <tr>
                        <td style="width: 50%; background-color:#f2c50c; text-align: center; font-weight: 600; ">Account</td>
                        <td style="width: 50%; background-color:#f2c50c; text-align: center; font-weight: 600; ">Credit Age</td>
                    </tr>
                    <tr>
                        <td>Communications</td>
                        <td>1 year </td>
                    </tr>
                    <tr>
                        <td>Wholesale Trade-Durable Goods</td>
                        <td>2 years </td>
                    </tr>
                    <tr>
                        <td>Business Services and All Other</td>
                        <td>31 years </td>
                    </tr>
                    </table>
                </div>-->
            </div>
            <!--Credit Age Summary Ends-->
            <!-- Credit usage Summary -->
            <div class="row">
                <div class="col-md-2">
                    <!--<div class="donutchart1">
                        <h3>Credit Usage</h3>
                        <canvas id="chDonut9"></canvas>
                        <div class="pie-value-txt1">
                            <span class="pie-value1"><strong></strong><br/>Excellent</span>
                        </div>
                    </div>-->
                </div>

                <div class="col-md-8 publicdeeds">
                    <table id="publicdeeds">
                        <tr>
                        <th colspan="2">Credit Usage Summary</th>
                        </tr>
                        <tr>
                        <td style="width: 50%;">Total usage
							<?php
								$sumVal = "";
								if(!empty($creditLimitAmtSum) && !empty($creditLimitBalanceAmtSum)){
									if (array_sum($creditLimitAmtSum) > 0) {
										$sumVal = array_sum($creditLimitBalanceAmtSum)/array_sum($creditLimitAmtSum)*100;
									}
									echo '('. round($sumVal,0,PHP_ROUND_HALF_UP).'<span>'.'%'.'</span>'.')';
								}
							?>
						</td>
                        <td style="width: 50%;">
						<?php echo !empty($creditLimitBalanceAmtSum) ? "$" .number_format(array_sum($creditLimitBalanceAmtSum)) : '' ;?>
						</td>
                        </tr>
                        <tr>
                        <td>Total limit <?php echo !empty($creditLimitAmtSum) ? '' : '' ?></td>
                        <td>
						<?php
							if(!empty($creditLimitAmtSum)){
								echo "$" . number_format(array_sum($creditLimitAmtSum));
							}
						?>
						</td>
                        </tr>

                    </table>
                </div>
            </div>
            <div class="row">
                <!--<div class="col-md-12 creditage">
                    <table id="creditage">
                    <tr>
                        <th colspan="4">Credit Usage Details</th>
                    </tr>
                    <tr>
                        <td style="width: 25%; background-color:#f2c50c; text-align: center; font-weight: 600; ">Account</td>
                        <td style="width: 25%; background-color:#f2c50c; text-align: center; font-weight: 600; ">Credit Age</td>
                        <td style="width: 25%; background-color:#f2c50c; text-align: center; font-weight: 600; ">Limit</td>
                        <td style="width: 25%; background-color:#f2c50c; text-align: center; font-weight: 600; ">Usage</td>
                    </tr>
                    <tr>
                        <td>-</td>
                        <td>- </td>
                        <td>- </td>
                        <td>-</td>
                    </tr>

                    </table>
                </div>-->
            </div>
        	<!--Credit usage Summary Ends-->

            <!-- Enquires Summary  -->
            <div class="row">
                <div class="col-md-2">
                    <!--<div class="donutchart1">
                        <h3>Enquires</h3>
                        <canvas id="chDonut10"></canvas>
                        <div class="pie-value-txt1">
                            <span class="pie-value1"><strong><?php echo count($non_banking_array); ?></strong><br/>Fair</span>
                        </div>
                    </div>-->
                </div>

                <div class="col-md-8 publicdeeds">
                    <table id="publicdeeds">
                        <tr>
                        <th colspan="4">Enquires Summary</th>
                        </tr>
                        <tr>
                        <td style="width: 25%;">Last 3 months</td>
                        <td style="width: 25%;">Last 6 months</td>
                        <td style="width: 25%;">Last 12 months</td>
                        <td style="width: 25%;"> > Last 12 months</td>
                        </tr>
                        <tr>
                        <td>
						<?php
							$Months3 =  General::getCountOfValueInDateRange(90, $non_banking_array);
							echo !empty($Months3) ? count($Months3) : '-';
						?>
						</td>
                        <td><?php
								$Months6 =  General::getCountOfValueInDateRange(180, $non_banking_array);
								echo !empty($Months6) ? count($Months6) : '-';
							?></td>
                        <td><?php
								$Months12 =  General::getCountOfValueInDateRange(365, $non_banking_array);
								echo !empty($Months12) ? count($Months12) : '-';
							?></td>
                        <td>
						<?php

						//echo "1";
					$MonthsGreaterThan12 =  General::getElemenstGreaterthanMonthsCount(365, $non_banking_array);
					echo !empty($MonthsGreaterThan12) ? $MonthsGreaterThan12 : '-';
						?>
						</td>
                        </tr>
                    </table>
                </div>
            </div>
            <!--Enquires Summary Ends-->

                <!-- Total Accounts Summary  -->
                <div class="row">
                    <div class="col-md-2">
                        <!--<div class="donutchart1">
                            <h3>Total Accounts</h3>
                            <canvas id="chDonut11"></canvas>
                            <div class="pie-value-txt1">
                                <span class="pie-value1"><strong><?php
								/*if(!empty($response_folder['ReportAttributes']['OpenNonFiByIndGroup']['OpenSummaryAttributesTotals']['NumOpenAccts']) && !empty($response_folder['ReportAttributes']['ClosedNonFiByIndGroup']['ClosedSummaryAttributesTotals']['NumCurrentAccts']) && !empty($response_folder['ReportAttributes']['ClosedNonFiByIndGroup']['ClosedSummaryAttributesTotals']['NumDelinquentAccts']))
								{
								echo $response_folder['ReportAttributes']['OpenNonFiByIndGroup']['OpenSummaryAttributesTotals']['NumOpenAccts']+$response_folder['ReportAttributes']['ClosedNonFiByIndGroup']['ClosedSummaryAttributesTotals']['NumCurrentAccts']+$response_folder['ReportAttributes']['ClosedNonFiByIndGroup']['ClosedSummaryAttributesTotals']['NumDelinquentAccts'];
								}*/
								?></strong><br/>Fair</span>
                            </div>
                        </div>-->
                    </div>

                    <div class="col-md-8 totalaccount">
                        <table id="totalaccount">
                            <tr>
								<th colspan="5">Total Accounts Summary</th>
                            </tr>

							<?php
							    $totalAccVal = 0;

							    $NumOpenAccts = !empty($response_folder['ReportAttributes']['OpenNonFiByIndGroup']['OpenSummaryAttributesTotals']['NumOpenAccts']) ? $response_folder['ReportAttributes']['OpenNonFiByIndGroup']['OpenSummaryAttributesTotals']['NumOpenAccts']: 0;

							    $NumCurrentAccts = !empty($response_folder['ReportAttributes']['ClosedNonFiByIndGroup']['ClosedSummaryAttributesTotals']['NumCurrentAccts']) ? $response_folder['ReportAttributes']['ClosedNonFiByIndGroup']['ClosedSummaryAttributesTotals']['NumCurrentAccts'] : 0;

							    $NumDelinquentAccts = !empty($response_folder['ReportAttributes']['ClosedNonFiByIndGroup']['ClosedSummaryAttributesTotals']['NumDelinquentAccts']) ? $response_folder['ReportAttributes']['ClosedNonFiByIndGroup']['ClosedSummaryAttributesTotals']['NumDelinquentAccts'] : 0;

							    $totalAccVal = $NumOpenAccts + $NumCurrentAccts + $NumDelinquentAccts;
							?>

                            <tr>
                            <td colspan="5" style="background-color: #e8e8e8;">Business Entity - Total
								<?php //echo ngettext('Account', 'Accounts', $totalAccVal).'  - '. $totalAccVal;
								echo "Accounts"; ?></td></tr>
                            <tr>
								<?php
									$openAccVal = 0;
									if(!empty($response_folder['ReportAttributes']['OpenNonFiByIndGroup']['OpenSummaryAttributesTotals']['NumOpenAccts'])){
										$openAccVal = $response_folder['ReportAttributes']['OpenNonFiByIndGroup']['OpenSummaryAttributesTotals']['NumOpenAccts'];
									}
								?>
                                <td colspan="2" style="width: 50%;">Open
									<?php //echo ngettext('Account', 'Accounts', $openAccVal).' - '. $openAccVal;
									echo "Accounts"; ?>
								</td>

								<?php
									$closeAccVal = 0;
									if(!empty($response_folder['ReportAttributes']['ClosedNonFiByIndGroup']['ClosedSummaryAttributesTotals']['NumCurrentAccts']) && !empty($response_folder['ReportAttributes']['ClosedNonFiByIndGroup']['ClosedSummaryAttributesTotals']['NumDelinquentAccts']))
									{
										$closeAccVal =  $response_folder['ReportAttributes']['ClosedNonFiByIndGroup']['ClosedSummaryAttributesTotals']['NumCurrentAccts']+$response_folder['ReportAttributes']['ClosedNonFiByIndGroup']['ClosedSummaryAttributesTotals']['NumDelinquentAccts'];
									}
								?>

                                <td colspan="3" style="width: 50%;">Closed
									<?php //echo ngettext('Account', 'Accounts', $closeAccVal).' - '. $closeAccVal;
									echo "Accounts"; ?>
								</td>
                            </tr>
                            <tr>
                            <td>Current</td>
                            <td>Delinquency</td>
                            <td>Current</td>
                            <td>Delinquency</td>
                            <td>Charge-Off</td>
                            </tr>
                            <tr>
                            <td><?php if(!empty($response_folder['ReportAttributes']['OpenNonFiByIndGroup']['OpenSummaryAttributesTotals']['NumOpenAccts'])){
							echo $response_folder['ReportAttributes']['OpenNonFiByIndGroup']['OpenSummaryAttributesTotals']['NumOpenAccts']; }else{ echo "0"; } ?></td>
                            <td>0</td>
                            <td><?php
							if(!empty($response_folder['ReportAttributes']['ClosedNonFiByIndGroup']['ClosedSummaryAttributesTotals']['NumCurrentAccts'])){
							echo $response_folder['ReportAttributes']['ClosedNonFiByIndGroup']['ClosedSummaryAttributesTotals']['NumCurrentAccts']; }else{ echo "0"; } ?></td>
                            <td><?php if(!empty($response_folder['ReportAttributes']['ClosedNonFiByIndGroup']['ClosedSummaryAttributesTotals']['NumDelinquentAccts'])){
							echo $response_folder['ReportAttributes']['ClosedNonFiByIndGroup']['ClosedSummaryAttributesTotals']['NumDelinquentAccts'];
							}else{ echo "0"; } ?></td>
                            <td><?php if(!empty($response_folder['ReportAttributes']['ClosedNonFiByIndGroup']['ClosedSummaryAttributesTotals']['NumChargeOffAccts'])){ echo $response_folder['ReportAttributes']['ClosedNonFiByIndGroup']['ClosedSummaryAttributesTotals']['NumChargeOffAccts']; }else{ echo "0"; } ?></td>
                            </tr>

                        </table>
                    </div>
                </div>

                <div class="row">
					<!-- check Condition For Non-Financial Trade-Groups Open Account data -->
					<?php
						if(!empty($response_folder['ReportAttributes']['OpenNonFiByIndGroup']['OpenSummaryAttributes']) && !empty($response['EfxTransmit']['CommercialCreditReport'][0]['Folder']['TradeInfo'])){

						?>

						<div class="col-md-12 openacdetails">
                        <table id="openacdetails">
							<tr>
								<th colspan="7">Business Entity - Open Account Details</th>
							</tr>


									<?php
									//$singleHiCreditExtOrBalOwed = array();
									//$singleHighestTotalPastDue = array();
									//sort array for Group of values.
									$arrNonFinAccPaymentHistryParams = General::getGroupedArray($arrNonFinAccPaymentHistryParams, array('IndustryGroup'));
									//plotting MONTH GRAPH at here.
									$data  			 = "";
									$year   		 =  "";
									$month  		 =  "";
									$flag_heading 	 =false;


								foreach($arrNonFinAccPaymentHistryParams as $key => $payHis_val_outer){

										if(!empty($response_folder['ReportAttributes']['OpenNonFiByIndGroup']['OpenSummaryAttributes'])){

										echo '<table id="openacdetails">';
										if($flag_heading==false){ ?>
											<tr>
												<td class="text-center" style="background-color:#f2c50c; font-weight: 600; ">Industry</td>
												<td class="text-center" style="background-color:#f2c50c; font-weight: 600; ">No of  Accounts</td>
												<!--<td style="background-color:#f2c50c; font-weight: 600; ">Open Date</td>
												<td style="background-color:#f2c50c; font-weight: 600; ">Account  Type</td>-->
												<td class="text-center" style="background-color:#f2c50c; font-weight: 600; ">Account Status With Slow %</td>
												<td class="text-center" style="background-color:#f2c50c; font-weight: 600; ">Highest Balance Amount ($)</td>
												<td class="text-center" style="background-color:#f2c50c; font-weight: 600; ">Highest Past Due Amount ($) </td>
											</tr>
											<?php

											$flag_heading=false;
										}
										?>
										<tr>
										<?php
											$percent_slow = "";

											foreach($response_folder['ReportAttributes']['OpenNonFiByIndGroup']['OpenSummaryAttributes'] as $key_inner => $grp_dtl){

												if($key == $grp_dtl['IndustryGroup']){
												?>
												<td class="text-center"><?php echo $grp_dtl['IndustryGroup']?></td>
												<td class="text-center"><?php echo $grp_dtl['NumOpenAccts']?> </td>
												<td class="text-center"><?php

												 $percent_slow = General::getPercentSlowValue($grp_dtl['SingleMostSevereStatus']);

												if($percent_slow!=""){
													echo $grp_dtl[$percent_slow]. '%  ';
												}
												echo $grp_dtl['SingleMostSevereStatus'];
												?></td>
												<td class="text-center"><?php echo $grp_dtl['SumOfBalances']['value']>0 ? "$" .number_format($grp_dtl['SumOfBalances']['value']) : '-';?></td>
												<td class="text-center"><?php echo $grp_dtl['SumOfPastDue']['value']>0 ? "$" .number_format($grp_dtl['SumOfPastDue']['value']) : '-';?>
												</td>
												</tr>
												<?php
												}
											}
											echo '</table>';

										}


										foreach($payHis_val_outer as $key =>$payHis_val)
										{

											$data   = $payHis_val['PaymentHistoryProfile'];
											$year   = date('Y', strtotime($payHis_val['ReportedDate']));
											$month  = date('m', strtotime($payHis_val['ReportedDate']));

											//validate correct year when month i dec (12)

											$formatted = implode(',',str_split($data));
											$array = explode(",",$formatted);
											?>
											<table id="paymenthistory">
												<tr>
													<td colspan="13" style="background-color:#f2c50c; font-weight: 600; text-align: center; ">Ref. Account No :
													<?php echo $payHis_val['AccountReferenceNo'] . '<br>';
													?>
													</td>
												</tr>
												<tr>
													<td> Year </td>
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

												<tr>
													<?php
													$k = 0;
													$i = 0;
													if($month == 01)
													{
														$year = $year - 1;

														echo "<td>". $year ."</td>";
														foreach($array as $val)
														{
															$i = $i + 1;

															  echo "<td><div class=".General::getPayHisClass($val)."-roundbg>".General::getPayHisText($val)."</div></td>";
															  if($i == 12 || $i == 24)
															  {
																  $year = $year - 1;
																 echo "</tr> <tr> <td>". $year ."</td>";
															  }
															  else  if($i == 36 || $i == 48)
															  {
																  $year = $year - 1;
																 echo "</tr> <tr> <td>". $year ."</td>";
															  }
														}
													}
													else
													{

														echo "<td>". $year ."</td>";
														for($j = 12; $j>= 0; $j--)
														{
															$i = $i + 1;
															if($k == 0)
															{
																if($j < $month)
																{
																	foreach($array as $val)
																	{
																		$i = $i + 1;

																		echo "<td><div class=".General::getPayHisClass($val)."-roundbg>".General::getPayHisText($val)."</div></td>";

																	 // $j = $j + 1;
																	  if($i == 13 || $i == 25)
																	  {
																		  $year = $year - 1;
																		 echo "</tr> <tr> <td>". $year ."</td>";
																	  }
																	  else  if($i == 37 || $i == 49)
																	  {
																		  $year = $year - 1;
																		 echo "</tr> <tr> <td>". $year ."</td>";
																	  }
																	  else if($i == 61)
																	  {
																		  $year = $year - 1;
																		 echo "</tr> <tr> <td>". $year ."</td>";
																	  }
																	}
																	$k = 1;
																}
																else
																{
																	echo "<td>  </td>";
																}
															}

														}
												}
												?>

												</tr>
											</table>
											<br/><br/>
										<?php
										//End of Code plotting for PaymentHistory.

											//}
									}
								}
								?>

								<div class="col-md-12">
									<div class="tab-legends">
                                        <li><div class="tab-green"></div>On-time</li>
                                        <li><div class="tab-red"></div>Slow</li>
                                        <li><div class="tab-lblue"></div>Closed</li>
                                        <li><div class="tab-orange"></div>Non-accrual account</li>
										<li><div class="tab-blue"></div>Not-Reported</li>
                                        <li><div class="tab-pur"></div>Collection</li>
                                        <li><div class="tab-black"></div>Charge- Off</li>
										<li><div class="tab-bri"></div>Repossession</li>

									</div>
								</div>

							</div>

                        </table>
                    </div>
                </div>
						<?php
					}
					?>
				    <!-- END ['CODE -PICKED'] -->


                <!-- Business Account Details-->

                <!-- Business Account Closed Details-->
				<div class="row">
					<!-- check Condition For Non-Financial Trade-Groups Closed Account data -->
					<?php
					if(!empty($response_folder['ReportAttributes']['ClosedNonFiByIndGroup']['ClosedSummaryAttributes']) && !empty($response['EfxTransmit']['CommercialCreditReport'][0]['Folder']['TradeInfo'])){

					?>
                    <div class="col-md-12 openacdetails">
                        <table id="openacdetails">
							<tr>
								<th colspan="6">Business Entity - Closed Account Details</th>
							</tr>

								<!--  CODE WILL COME  IN THIS AREA  For CLOSED ACC DETAILS 						    -->

										<div class="row">
												<?php
												//$singleHiCreditExtOrBalOwed = array();
												//$singleHighestTotalPastDue = array();
												//sort array for Group of values.
												$arrNonFinClosedAccPaymentHistryParams = General::getGroupedArray($arrNonFinClosedAccPaymentHistryParams, array('IndustryGroup'));
												//plotting MONTH GRAPH at here.
												$data  			 = "";
												$year   		 =  "";
												$month  		 =  "";
												$flag_heading 	 =false;


												foreach($arrNonFinClosedAccPaymentHistryParams as $key => $payHis_val_outer){

														if(!empty($response_folder['ReportAttributes']['ClosedNonFiByIndGroup']['ClosedSummaryAttributes'])){

														echo '<table id="openacdetails">';
														if($flag_heading==false){ ?>
															<tr>
																<td class="text-center" style="background-color:#f2c50c; font-weight: 600; ">Industry</td>
																<td class="text-center" style="background-color:#f2c50c; font-weight: 600; ">No of  Accounts</td>
																<td class="text-center" style="background-color:#f2c50c; font-weight: 600; ">Closed Date</td>
																<td class="text-center" style="background-color:#f2c50c; font-weight: 600; ">Highest Balance Amount ($)</td>
															</tr>
															<?php

															$flag_heading=false;
														}
														?>
														<tr>
														<?php
															$percent_slow = "";

															foreach($response_folder['ReportAttributes']['ClosedNonFiByIndGroup']['ClosedSummaryAttributes'] as $key_inner => $grp_dtl){

																if($key == $grp_dtl['IndustryGroup']){
																?>
																	<td class="text-center"><?php echo $grp_dtl['IndustryGroup']?></td>
																	<td class="text-center"><?php echo $grp_dtl['NumCurrentAccts']?> </td>
																	<td class="text-center">
																		<?php
																			echo !empty($grp_dtl['MostRecentDateCurrent']) ? $grp_dtl['MostRecentDateCurrent'] : ' - ';
																		?></td>
																	<td class="text-center"><?php echo $grp_dtl['SingleHiCreditExtOrBalOwed']>0 ? "$" .number_format($grp_dtl['SingleHiCreditExtOrBalOwed']) : '-';?>
																	</td>

																</tr>
																<?php
																}
															}
															echo '</table>';

														}

														/* Display all Accounts in Given Group Name, from below loop */

														foreach($payHis_val_outer as $key =>$payHis_val)
														{

															$data   = $payHis_val['PaymentHistoryProfile'];
															$year   = date('Y', strtotime($payHis_val['ReportedDate']));
															$month  = date('m', strtotime($payHis_val['ReportedDate']));
															$formatted = implode(',',str_split($data));
															$array = explode(",",$formatted);
															?>
															<table id="paymenthistory">
																<tr>
																	<td colspan="13" style="background-color:#f2c50c; font-weight: 600; text-align: center; ">Ref. Account No :
																	<?php echo $payHis_val['AccountReferenceNo'] ; ?>
																	</td>
																</tr>
																<tr>
																	<td> Year </td>
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
																<tr>
																	<?php

																	$k = 0;
																	$i = 0;
																	if($month == 01)
																	{
																		$year = $year - 1;

																		echo "<td>". $year ."</td>";
																		foreach($array as $val)
																		{
																			$i = $i + 1;

																			  echo "<td><div class=".General::getPayHisClass($val)."-roundbg>".General::getPayHisText($val)."</div></td>";
																			  if($i == 12 || $i == 24)
																			  {
																				  $year = $year - 1;
																				 echo "</tr> <tr> <td>". $year ."</td>";
																			  }
																			  else  if($i == 36 || $i == 48)
																			  {
																				  $year = $year - 1;
																				 echo "</tr> <tr> <td>". $year ."</td>";
																			  }
																		}
																	}
																	else
																	{
																		echo "<td>". $year ."</td>";

																		for($j = 12; $j>= 0; $j--)
																		{
																			$i = $i + 1;
																			if($k == 0)
																			{
																				if($j < $month)
																				{
																					foreach($array as $val)
																					{
																						$i = $i + 1;

																						echo "<td><div class=".General::getPayHisClass($val)."-roundbg>".General::getPayHisText($val)."</div></td>";
																					  if($i == 13 || $i == 25)
																					  {
																						  $year = $year - 1;
																						 echo "</tr> <tr> <td>". $year ."</td>";
																					  }
																					  else  if($i == 37 || $i == 49)
																					  {
																						  $year = $year - 1;
																						 echo "</tr> <tr> <td>". $year ."</td>";
																					  }
																					  else if($i == 61)
																					  {
																						  $year = $year - 1;
																						 echo "</tr> <tr> <td>". $year ."</td>";
																					  }
																					}
																					$k = 1;
																				}
																				else
																				{
																					echo "<td>  </td>";
																				}
																			}

																		}
																	}
																	?>
																</tr>
															</table>
															<br/><br/>
														<?php
														//End of Code plotting for PaymentHistory.

															//}
													}
												}
												?>

												<div class="col-md-12">
													<div class="tab-legends">
                                                    <li><div class="tab-green"></div>On-time</li>
                                                    <li><div class="tab-red"></div>Slow</li>
                                                    <li><div class="tab-lblue"></div>Closed</li>
                                                    <li><div class="tab-orange"></div>Non-accrual account</li>
                                                    <li><div class="tab-blue"></div>Not-Reported</li>
                                                    <li><div class="tab-pur"></div>Collection</li>
                                                    <li><div class="tab-black"></div>Charge- Off</li>
                                                    <li><div class="tab-bri"></div>Repossession</li>

													</div>
												</div>

										</div>

										</table>
									</div>
								</div>
							<?php
						}
					?>
					<!--     CODE ENDS AT HERE    For Closed acc details.       					    -->
                    </div>
                </div>

                <!-- Business Accont Closed ENDS-->

                <!-- Credit Guarantor - Open Account Details-->
                <!--
				<div class="row">
                    <div class="col-md-12 openacdetails">
                        <table id="openacdetails">
                        <tr>
                            <th colspan="7">Credit Guarantor - Open Account Details</th>
                        </tr>
                        <tr>
                            <td style="width: 20%; background-color:#f2c50c; font-weight: 600; ">Credit  Guarantor</td>
                            <td style="background-color:#f2c50c; font-weight: 600; ">No of  Accounts</td>
                            <td style="background-color:#f2c50c; font-weight: 600; ">Open Date</td>
                            <td style="background-color:#f2c50c; font-weight: 600; ">Account  Type</td>
                            <td style="background-color:#f2c50c; font-weight: 600; ">Account Status With Slow %</td>
                            <td style="background-color:#f2c50c; font-weight: 600; ">Current Balance  Amount</td>
                            <td style="background-color:#f2c50c; font-weight: 600; ">Amount  Past Due</td>
                        </tr>

                        <tr>
                            <td>-</td>
                            <td>- </td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>


                        <tr>
                            <td colspan="7" style="background-color:#e8e8e8; font-weight: 600; ">Open Delinquency Account</td>
                        </tr>
                        <tr>
                            <td>-</td>
                            <td>- </td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>


                        </table>
                    </div>
                </div> -->
                <!-- Credit Guarantor - Open Account Details-->

                <!-- Credit Guarantor - Closed Account Details-->

                <!--<div class="row">
                    <div class="col-md-12 openacdetails">
                        <table id="openacdetails">
                        <tr>
                            <th colspan="6">Credit Guarantor - Closed Account Details</th>
                        </tr>
                        <tr>
                            <td style="width: 20%; background-color:#f2c50c; font-weight: 600; ">Credit Guarantor</td>
                            <td style="background-color:#f2c50c; font-weight: 600; ">No of  Accounts</td>
                            <td style="background-color:#f2c50c; font-weight: 600; ">Closed Date</td>
                            <td style="background-color:#f2c50c; font-weight: 600; ">Account  Type</td>
                            <td style="background-color:#f2c50c; font-weight: 600; ">Closed Status </td>
                            <td style="background-color:#f2c50c; font-weight: 600; ">Balance Amount</td>
                        </tr>

                        <tr>
                            <td>-</td>
                            <td>- </td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>


                        <tr>
                            <td colspan="6" style="background-color:#e8e8e8; font-weight: 600; ">Closed Delinquency Account </td>
                        </tr>
                        <tr>
                            <td>-</td>
                            <td>- </td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>

                        <tr>
                            <td colspan="6" style="background-color:#e8e8e8; font-weight: 600; ">Closed Charge-Off Account</td>
                        </tr>
                        <tr>
                            <td>-</td>
                            <td>- </td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                            <td>-</td>
                        </tr>

                        </table>
                    </div>
                </div> -->
                <!-- Credit Guarantor - Closed Account Details-->

                <!-- 60 Month Payment History Ends -->

                <!-- 60 Month Payment History Client2 -->


                        <!--<tr>
                            <th colspan="13">60 Month Payment History</th>
                        </tr>
                        <tr>
                            <td colspan="13" style="background-color:#f2c50c; font-weight: 600; text-align: center; ">Jones Manufactures</td>
                        </tr>
                        <tr>
                            <td> &nbsp; </td>
                            <td>Jan</td>
                            <td>Feb</td>
                            <td>Mar</td>
                            <td>Apr</td>
                            <td>May</td>
                            <td>Jun</td>
                            <td>Jul</td>
                            <td>Aug</td>
                            <td>Sep</td>
                            <td>Oct</td>
                            <td>Nov</td>
                            <td>Dec</td>
                        </tr>
                        <tr>
                            <td> 2020 </td>
                            <td><div class="red-roundbg">90</div></td>
                            <td><div class="red-roundbg">30</div></td>
                            <td><div class="red-roundbg">60</div></td>
                            <td><div class="pur-roundbg"></div></td>
                            <td><div class="pur-roundbg"></div></td>
                            <td><div class="lblue-roundbg"></div></td>
                            <td><div class="lblue-roundbg"></div></td>
                            <td><div class="lblue-roundbg"></div></td>
                            <td><div class="lblue-roundbg"></div></td>
                            <td><div class="lblue-roundbg"></div></td>
                            <td><div class="lblue-roundbg"></div></td>
                            <td><div class="lblue-roundbg"></div></td>
                        </tr>


                </div> -->

                <!-- 60 Month Payment History Client2 Ends -->

                <!-- 60 Month Payment History Client3 -->



                <!-- 60 Month Payment History Client3 Ends -->
				<!--Total Accounts Summary Ends-->
				</table>

				<!-- Banking Section appears at here -->
				<!--
					<div class="row">
						<div class="col-md-12">
							<div class="non-headtxt"><h2>Banking</h2>
							<span></span>
							</div>
						</div>
					</div>
				-->

							<!-- Public Deed Summary -->

                            <!-- Heading Ends-->

                            <!--<div class="row justify-content-lg-start justify-content-center">

                                <div class="col-md-4">
                                    <div class="statistics_item">
                                    <h3 class="counter"> </h3>
                                    <p>Credit Age</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="statistics_item">
                                    <h3 class="counter"> </h3>
                                    <p>Credit Usage</p>
                                    </div>
                                </div>
								<div class="col-md-4">
                                    <div class="statistics_item">
                                    <h3 class="counter"><?php // echo count($banking_count) ;?></h3>
                                    <p>Enquires</p>
                                    </div>
                                </div>

                            </div>
                            <div class="row justify-content-lg-start justify-content-center">

                                <div class="col-md-4">
                                    <div class="statistics_item">
                                    <h3 class="counter"></h3>
                                    <p>Payment Score</p>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="statistics_item">
                                    <h3 class="counter"></h3>
                                    <p>Total Accounts</p>
                                    </div>
                                </div>
                            </div> -->

                            <!-- Non Banking blocks Ends-->
                            <!--<div class="row">
                                <div class="col-md-12 pb-hr"></div>
                            </div> -->

                            <!-- Public Deed Summary -->
							<!--Public Deeds Summary Ends-->

                            <!-- Credit Age Summary -->
                            <!--<div class="row">
                                <div class="col-md-2">
                                    <div class="donutchart1">
                                        <h3>Credit Age</h3>
                                        <canvas id="chDonut8"></canvas>
                                        <div class="pie-value-txt1">
                                            <span class="pie-value1"><strong>16yrs</strong><br/> Good</span>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-md-8 publicdeeds">
                                    <table id="publicdeeds">
                                        <tr>
                                        <th colspan="2">Credit Age Summary</th>
                                        </tr>
                                        <tr>
                                        <td style="width: 50%;">Oldest Account</td>
                                        <td style="width: 50%;">-</td>
                                        </tr>
                                        <tr>
                                        <td>Newest Account</td>
                                        <td>-</td>
                                        </tr>
                                        <tr>
                                        <td>Average Account</td>
                                        <td>-</td>
                                        </tr>
                                    </table>
                                </div>
                            </div> -->

                            <!--<div class="row">
                                <div class="col-md-12 creditage">
                                    <table id="creditage">
                                    <tr>
                                        <th colspan="2">Credit Age Details</th>
                                    </tr>
                                    <tr>
                                        <td style="width: 50%; background-color:#f2c50c; text-align: center; font-weight: 600; ">Account</td>
                                        <td style="width: 50%; background-color:#f2c50c; text-align: center; font-weight: 600; ">Credit Age</td>
                                    </tr>

                                    </table>
                                </div>
                            </div> -->
                        <!--Credit Age Summary Ends-->

                        <!-- Credit usage Summary -->
                        <!--<div class="row">
                            <div class="col-md-2">
                                <div class="donutchart1">
                                    <h3>Credit Usage</h3>
                                    <canvas id="chDonut9"></canvas>
                                    <div class="pie-value-txt1">
                                        <span class="pie-value1"><strong></strong><br/></span>
                                    </div>
                                </div>
                            </div>

							<div class="col-md-8 publicdeeds">
                                <table id="publicdeeds">
                                    <tr>
                                    <th colspan="2">Credit Usage Summary</th>
                                    </tr>
                                    <tr>
                                    <td style="width: 50%;">Total usage </td>
                                    <td style="width: 50%;">-</td>
                                    </tr>
                                    <tr>
                                    <td>Total limit</td>
                                    <td>-</td>
                                    </tr>

                                </table>
                            </div>
                            </div>
                        </div> -->

                        <!--<div class="row">
                            <div class="col-md-12 creditage">
                                <table id="creditage">
                                <tr>
                                    <th colspan="4">Credit Usage Details</th>
                                </tr>
                                <tr>
                                    <td style="width: 25%; background-color:#f2c50c; text-align: center; font-weight: 600; ">Account</td>
                                    <td style="width: 25%; background-color:#f2c50c; text-align: center; font-weight: 600; ">Credit Age</td>
                                    <td style="width: 25%; background-color:#f2c50c; text-align: center; font-weight: 600; ">Limit</td>
                                    <td style="width: 25%; background-color:#f2c50c; text-align: center; font-weight: 600; ">Usage</td>
                                </tr>

                                </table>
                            </div>
                        </div> -->
                    <!--Credit usage Summary Ends-->

                    <!--<div class="row">
                        <div class="col-md-12 pb-hr"></div>
                    </div> -->

                    <!-- Enquires Summary  -->
                    <!--<div class="row">
                        <div class="col-md-2">
                           <div class="donutchart1">
                                <h3>Enquires</h3>
                                <canvas id="chDonut13"></canvas>
									<div class="pie-value-txt1">
                                    <span class="pie-value1"><strong><?php //echo count($banking_array); ?></strong><br/>Fair</span>
									</div>
								</div>

                        </div>

                        <div class="col-md-8 publicdeeds">
                            <table id="publicdeeds">
                                <tr>
                                <th colspan="4">Enquires Summary</th>
                                </tr>
                                <tr>
                                <td style="width: 25%;">Last 3 months</td>
                                <td style="width: 25%;">Last 6 months</td>
                                <td style="width: 25%;">Last 12 months</td>
                                <td style="width: 25%;"> > Last 12 months</td>
                                </tr>
                                <tr>
									<td>
									 -->

									<?php
										/*$Months3 =  General::getCountOfValueInDateRange(90, $banking_array);
										echo !empty($Months3) ? $Months3 : '-'; */
									?>

									<!--</td>
									<td><?php
											//$Months6 =  General::getCountOfValueInDateRange(180, $banking_array);
											//echo !empty($Months6) ? $Months6 : '-';
										?></td>
									<td><?php
											//$Months12 =  General::getCountOfValueInDateRange(365, $banking_array);
											//echo !empty($Months12) ? $Months12 : '-';
										?></td>
									<td> -->
									<?php

									//$MonthsGreaterThan12 =  General::getElemenstGreaterthanMonthsCount(365, $banking_array);
									//echo !empty($MonthsGreaterThan12) ? $MonthsGreaterThan12 : '-';
									?>
									<!--</td>
                                </tr>
                            </table>
                        </div>
                    </div>



                    <div class="row">

						<div class="col-md-12 creditage">
                            <table id="creditage">
                            <tr>
                                <th colspan="4">Enquiry Details</th>
                            </tr>
                            <tr>
								<td style="width: 25%; background-color:#f2c50c; text-align: center; font-weight: 600; ">Number</td>
                                <td style="width: 25%; background-color:#f2c50c; text-align: center; font-weight: 600; ">Type</td>
                                <td style="width: 25%; background-color:#f2c50c; text-align: center; font-weight: 600; ">Date</td>
                                <td style="width: 25%; background-color:#f2c50c; text-align: center; font-weight: 600; ">Amount</td>
                            </tr>
                            <tr> -->
								<?php
									/*$i = 0  ;
									foreach($banking_array as $key => $inquery_date){

										//$inquery_date
										$i = $i +1  ;
										?>
										<td><?php echo $i; ?></td>
										<td><?php echo "Banking"; ?> </td>
										<td><?php echo date("m/d/Y", strtotime($inquery_date)); ?></td>
										<td><?php echo "Not Available"; ?></td> </tr>
										<?php
									}*/
								?>
                            <!--</table>
                        </div>
                    </div>-->
                <!--Enquires Summary Ends-->

                <!--<div class="row">
                    <div class="col-md-12 pb-hr"></div>
                </div>-->

                <!-- Total Accounts Summary  -->
                <!--<div class="row">
                    <div class="col-md-2">
                        <div class="donutchart1">
                            <h3>Total Accounts</h3>
                            <canvas id="chDonut11"></canvas>
                            <div class="pie-value-txt1">
                                <span class="pie-value1"><strong></strong><br/></span>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-8 totalaccount">
                        <table id="totalaccount">
                            <tr>
                            <th colspan="5">Total Accounts Summary</th>
                            </tr>
                            <tr>
                            <td colspan="5" style="background-color: #e8e8e8;">Business Entity - </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="width: 50%;">Open Account - </td>
                                <td colspan="3" style="width: 50%;">Closed Account - </td>
                            </tr>
                            <tr>
                            <td>Current</td>
                            <td>Delinquency</td>
                            <td>Current</td>
                            <td>Delinquency</td>
                            <td>Charge-Off</td>
                            </tr>
                            <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>--</td>
                            <td></td>
                            </tr>
                            <tr>
                            <td colspan="5" style="background-color: #e8e8e8;">Business Entity - </td>
                            </tr>
                            <tr>
                                <td colspan="2" style="width: 50%;">Open Account - </td>
                                <td colspan="3" style="width: 50%;">Closed Account - </td>
                            </tr>
                            <tr>
                            <td>Current</td>
                            <td>Delinquency</td>
                            <td>Current</td>
                            <td>Delinquency</td>
                            <td>Charge-Off</td>
                            </tr>
                            <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td>--</td>
                            <td></td>
                            </tr>
                        </table>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 openacdetails">
                        <table id="openacdetails">
                        <tr>
                            <th colspan="5">Business Entity - Open Account Details</th>
                        </tr>
                        <tr>
                            <td style="width: 30%;background-color:#f2c50c; font-weight: 600; ">Account</td>
                            <td style="background-color:#f2c50c; font-weight: 600; ">No. of  Accounts</td>
                            <td style="background-color:#f2c50c; font-weight: 600; ">Account Status With Slow %</td>
                            <td style="background-color:#f2c50c; font-weight: 600; ">Highest Balance Amount($)</td>
                            <td style="background-color:#f2c50c; font-weight: 600; ">Highest Past Due Amount($)</td>

                        </table>
                    </div>
                </div> -->

                <!-- Business Account Details-->

                <!-- Business Account Closed Details-->

                <!--<div class="row">
                    <div class="col-md-12 openacdetails">
                        <table id="openacdetails">
                        <tr>
                            <th colspan="6">Business Entity - Closed Account Details</th>
                        </tr>
                        <tr>
                            <td style="width: 30%; background-color:#f2c50c; font-weight: 600; ">Account</td>
                            <td style="background-color:#f2c50c; font-weight: 600; ">No. of  Accounts</td>
                            <td style="background-color:#f2c50c; font-weight: 600; ">Closed Date</td>
                            <td style="background-color:#f2c50c; font-weight: 600; ">Highest Balance Amount($)</td>
                        </tr>

                        </table>
                    </div>
                </div> -->

                <!-- Business Accont Closed ENDS-->
                <!--Total Accounts Summary Ends-->
				<!--
				<div class="row">
					<div class="col-md-12 pb-hr"></div>
				</div> -->

                    <!-- Payment Score Summary  -->

					<!--ends at here -->
               <!--</div>
            </div>
        </div> -->
			<?php
		}
		?>

	<style type="text/css">
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
            margin: 0px;
        }
        .pdf-date{
            text-align: right;
            font-size: 14px;
            font-weight: 400;
            color: #fff;
            background-color: #1e2c76;
            width: max-content;
            padding: 5px 30px;
            float: right;
            margin-top: 5px;
            border-radius: 12px 10px;
            position: relative;
            /*top: 24px;*/
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
            top: 65px;
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

        .pie-value {
            font-size: 40px;
            font-weight: 800;
        }
        .title_imporve {
            text-align: center;
            font-size: 28px;
            padding: 15px 0;
            font-weight: 500;
            color:#000;
            margin-bottom: 25px;
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
            top: -34px;
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
            margin: 10px auto;
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
            margin: 15px auto;
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
            margin: 30px auto;
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
            font-size: 16px;
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
            margin: 30px auto;
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
            background-color: #273581;
            color: #fff;
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
            padding: 10px 5px;
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
            width: 20px;
            height: 20px;
            position: relative;
            background-color: #f22a2a;
            border-radius: 25px;
            line-height: 26px;
            margin: 5px auto;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
        }
        .pur-roundbg{
            width: 20px;
            height: 20px;
            position: relative;
            background-color: #db22cd;
            border-radius: 25px;
            line-height: 26px;
            margin: 5px auto;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
        }
        .lblue-roundbg{
            width: 20px;
            height: 20px;
            position: relative;
            background-color: #79d2de;
            border-radius: 25px;
            line-height: 26px;
            margin: 5px auto;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
        }
        .green-roundbg{
            width: 20px;
            height: 20px;
            position: relative;
            background-color: #1da727;
            border-radius: 25px;
            line-height: 26px;
            margin: 5px auto;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
        }
        .blue-roundbg{
            width: 20px;
            height: 20px;
            position: relative;
            background-color: #147ad6;
            border-radius: 25px;
            line-height: 26px;
            margin: 5px auto;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
        }
        .bri-roundbg{
            width: 20px;
            height: 20px;
            position: relative;
            background-color: #7849c4;
            border-radius: 25px;
            line-height: 26px;
            margin: 5px auto;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
        }
        .black-roundbg{
            width: 20px;
            height: 20px;
            position: relative;
            background-color: #000;
            border-radius: 25px;
            line-height: 26px;
            margin: 5px auto;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
        }
        .orange-roundbg{
            width: 20px;
            height: 20px;
            position: relative;
            background-color: #ff9d00;
            border-radius: 25px;
            line-height: 26px;
            margin: 5px auto;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
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
	            font-size: 11px;
	            font-weight: 400;
	            color: #fff;
	            background-color: #1e2c76;
	            width: max-content;
	            padding: 5px 10px;
	            float: right;
	            border-radius: 12px 10px;
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
	            display: inline-block;
	            overflow-y: scroll;
	            padding:2px;
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
	            font-weight: 400;
	            color: #fff;
	            background-color: #1e2c76;
	            width: max-content;
	            padding: 5px 10px;
	            float: right;
	            border-radius: 12px 10px;
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
        var colors = ['#ff6c6c','#ffb36c','#82e360','#1483f2','#f5d13d','#333333', '#f1b26b'];

        /* 3 donut charts */
        var donutOptions = {
        	cutoutPercentage: 85,
        	legend: {
  				display: false
			},
			tooltips: {
		    	mode: null
		    },
        };

        //set color as per rage of score.
		var score_val = '<?php echo $score?? ""; ?>';
		var scoreText = '<?php echo $scoreText?? ""; ?>';
		var donutBackground = '#ff6c6c';
		if(score_val==""){
			var donutBackground = '#147ad6';
		} else {
			if (score_val > 400) {
				donutBackground = '#82e360';
			} else if (score_val >= 301 && score_val <= 400) {
				donutBackground = '#f5d13d';
			} else if (score_val >= 201 && score_val < 300) {
				donutBackground = '#f1b26b';
			}
		}

		var chDonutData = {
			labels: [scoreText],
			datasets: [
				{
					backgroundColor: donutBackground,
					borderWidth: 0,
					data: [score_val],
					opacity:10,
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
    </script>
<!--	ends at here added by ROOP   -->
@endsection
