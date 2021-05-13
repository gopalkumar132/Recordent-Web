@extends('voyager::master')
<style type="text/css">
	.same-width{
		padding-left: 280px;
	}
	.col-md-4.text-center{
	margin-left: 190px;
	}

	@media only screen and (max-width:800px){
	.same-width{
	margin-left: -248px;
	}
	.col-md-4.text-center{
	margin-left: -34px;
	}
	
}
</style>
@section('page_header')
    <h1 class="page-title">
        <i class="voyager-upload"></i> Upload Master File
    </h1>
    
	<!-- <div class="pull-right" style="padding-top: 10px;">
		 
		 <a href="{{route('export')}}" class="btn btn-info download-mem-data btn-blue">Download Members Data <i class="voyager-download"></i></a>
	</div> -->
@stop

@section('content')


        <div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered">
                    <!-- <div class="panel-body"> -->
                    	<!-- <div class="text-center"><p style="color:red;font-weight:bold">Bulk Upload Dues</p></div> -->

                    	<!-- <div class="nav nav-tabs" role="tablist"> -->
                    		<div class="same-width">
                          <h3 class="text-left">Select Customer Type</h3>
                    		</div>
                    		<hr>
                           <div class="col-md-4 text-center">
							<input id="optDaily" checked name="intervaltype" type="radio" data-target="#scheduleDaily">
							<label for="optDaily" style=""><b>Individual</b></label>&nbsp;&nbsp;&nbsp;
							<input id="optWeekly" name="intervaltype" type="radio" data-target="#scheduleWeekly">
							    <label for="optWeekly"><b>Business</b></label>
							</div>
							<!-- <div class="col-md-4 text-center">
							    <input id="optWeekly" name="intervaltype" type="radio" data-target="#scheduleWeekly">
							    <label for="optWeekly"><b>Business</b></label>
							</div> -->
<!-- </div> -->
                    	<div class="tab-content">
   					    <div id="scheduleDaily" class="tab-pane active">
						<div class="row">
						    <div class="col-md-8 text-left">
							   		<p style="color:red;font-weight:bold">Mobile Number, Person Name (Alphabets, Spaces), DueDate (DD/MM/YYYY) and Due Amount</p>
							   		<p style="color:green;font-weight:bold">Aadhar Number (Last six digits), Date Of Birth (DD/MM/YYYY), Father Name (Alphabets, Spaces), Mother name (Alphabets, Spaces), Due note</p>
							   		<p style="color:black;font-weight:bold">* Fields marked in Red are mandatory and in Green are optional</p>
							   	</div>
							 <div class="col-md-8 text-center"> 
						   		               	
								<div class="col-md-6 col-sm-8 col-md-offset-3 col-sm-offset-2">
									<form action="{{ route('super',$userId) }}" method="POST" enctype="multipart/form-data">
										@csrf
										<div class="form-group">
												<input type="file" name="file" accept=".xlsx" class="form-control fl-upload-height" required>
												<!--<small id="fileHelp" class="form-text text-muted">File to be uploaded is:</small>--> 
										</div>
										<!-- <input type="text" name="" value="{{$userId}}"> -->
										<div class="">
											
										</div>
										<div class="form-action">
											  <button class="btn btn-success btn-blue">Upload Data</button>
										</div>
									</form>
								</div>
								<!-- <div class="col-md-12 col-sm-12">
							   		<p>By clicking Upload you indicate that you have read and agree to the terms of the Recordent <a target="_blank" href="{{route('end-user-license-agreement')}}">End User License Agreement</a></p>
							   	</div> -->
							 </div>
							 <!-- <div class="col-md-4 text-md-right text-center">
							 	<div><a href="{{asset('MasterDataFile.xlsx')}}" download="MasterDataFile.xlsx" class="btn btn-warning download-mas-for btn-red">Download Master File Format</a></div>
							 </div> -->
						</div>
					</div>
    <div id="scheduleWeekly" class="tab-pane">

						<div class="row">
						    <div class="col-md-8 text-left">
							   		<p style="color:red;font-weight:bold">Company Name, Unique Identification Number, Concerned Person Name (Alphabets, Spaces), Concerned Person Designation (Alphabets, Spaces), Concerned Person Phone, Business Type, State (Alphabets, Spaces), City (Alphabets, Spaces), Due Date (DD/MM/YYYY) and Due Amount.</p>
							   		<p style="color:green;font-weight:bold">Sector Name (Alphabets, Spaces), Concerned Person Alternate Phone, Due Note, Pincode (Digits, Alphabets), Address</p>
<p style="color:black;font-weight:bold">* Fields marked in Red are mandatory and in Green are optional</p>
							   	</div>
							 <div class="col-md-8 text-center"> 
						   		               	
								<div class="col-md-6 col-sm-8 col-md-offset-3 col-sm-offset-2">
									<form action="{{ route('import-business-super',$userId) }}" method="POST" enctype="multipart/form-data">
										@csrf
										<!-- <input type="text" name="" value="{{$userId}}"> -->

										<div class="form-group">
												<input type="file" name="file" accept=".xlsx" class="form-control fl-upload-height" required>
												<!--<small id="fileHelp" class="form-text text-muted">File to be uploaded is:</small>-->
										</div>
										<div class="form-action">
											  <button class="btn btn-success btn-blue">Upload Data</button>
										</div>
									</form>
								</div>
								<!-- <div class="col-md-12 col-sm-12">
							   		<p>By clicking Upload you indicate that you have read and agree to the terms of the Recordent <a target="_blank" href="{{route('end-user-license-agreement')}}">End User License Agreement</a></p>
							   	</div> -->
							 </div>
						</div>
					</div>
				</div>
					</div>
				<!-- </div> -->
			</div>
		</div>

		<div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered">
                    
                    		<div class="same-width">
                          <h3 class="text-left">Bulk Update Profile</h3>
                    		</div>
                    		<hr>
                           <div class="col-md-4 text-center">
							<input id="optDaily1" checked name="profiletype" type="radio" data-target="#IndividualProfile">
							<label for="optDaily1" style=""><b>Individual</b></label>&nbsp;&nbsp;&nbsp;
							<input id="optWeekly1" name="profiletype" type="radio" data-target="#BusinessProfile">
							    <label for="optWeekly1"><b>Business</b></label>
							</div>
					
                    	<div class="tab-content">
   					    <div id="IndividualProfile" class="tab-pane active">
	                
			<div class="row">
	                    <div class="panel-body">
							<div class="row">
							    <div class="col-md-8 text-left">
							   		<p style="color:red;font-weight:bold">Customer Id</p>
							   		<p style="color:green;font-weight:bold">Email, Aadhar Number</p>
							   		<p style="color:black;font-weight:bold">* Fields marked in Red are mandatory and in Green are optional</p>
							   	</div>
								<div class="col-md-8 text-center">             	
									<div class="col-md-6 col-sm-8 col-md-offset-3 col-sm-offset-2">
										<form action="{{ route('import-update-profile',$userId) }}" method="POST" enctype="multipart/form-data">
											@csrf
				                                
											<div class="form-group">
													<input type="file" name="file" accept=".xlsx" class="form-control fl-upload-height" required>
													<!--<small id="fileHelp" class="form-text text-muted">File to be uploaded is:</small>-->
											</div>
											<div class="">
												
											</div>
											<div class="form-action">
												  <button class="btn btn-success btn-blue">Upload Data</button>
											</div>
										</form>
									</div>
									<div class="col-md-12 col-sm-12">
								   		<p>By clicking Upload you indicate that you have read and agree to the terms of the Recordent <a target="_blank" href="{{route('end-user-license-agreement')}}">End User License Agreement</a></p>
								   	</div>
								</div>
								<div class="col-md-4 text-md-right text-center">
								 	<div>
								 		<a href="{{asset('ProfileMasterDataFile.xlsx')}}" download="ProfileMasterDataFile.xlsx" class="btn btn-warning download-mas-for btn-red">Download Master File Format</a>
								 	</div>
								</div>
							</div>
						</div>
					</div>
			</div>
    <div id="BusinessProfile" class="tab-pane">

				<div class="row">
	                    <div class="panel-body">
							<div class="row">
							    <div class="col-md-8 text-left">
							   		<p style="color:red;font-weight:bold">Customer Id</p>
							   		<p style="color:green;font-weight:bold">State, City, Email, Custom Id</p>
							   		<p style="color:black;font-weight:bold">* Fields marked in Red are mandatory and in Green are optional</p>
							   	</div>
								<div class="col-md-8 text-center">             	
									<div class="col-md-6 col-sm-8 col-md-offset-3 col-sm-offset-2">
										<form action="{{ route('import-business-update-profile',$userId) }}" method="POST" enctype="multipart/form-data">
											@csrf
				                                
											<div class="form-group">
													<input type="file" name="file" accept=".xlsx" class="form-control fl-upload-height" required>
											</div>
											<div class="">
												
											</div>
											<div class="form-action">
												  <button class="btn btn-success btn-blue">Upload Data</button>
											</div>
										</form>
									</div>
									<div class="col-md-12 col-sm-12">
								   		<p>By clicking Upload you indicate that you have read and agree to the terms of the Recordent <a target="_blank" href="{{route('end-user-license-agreement')}}">End User License Agreement</a></p>
								   	</div>
								</div>
								<div class="col-md-4 text-md-right text-center">
								 	<div>
								 		<a href="{{asset('BusinessProfileMasterDataFile.xlsx')}}" download="BusinessProfileMasterDataFile.xlsx" class="btn btn-warning download-mas-for btn-red">Download Master File Format</a>
								 	</div>
								</div>
							</div>
						</div>
					</div>
					</div>
				</div>
					</div>
				<!-- </div> -->


				<div class="row">
            <div class="col-md-12">

                <div class="panel panel-bordered">
                    		<div class="same-width">
                          <h3 class="text-left">Upload Reports</h3>
                    		</div>
                    		<hr>
                           <div class="col-md-4 text-center">
							<input id="optDaily_report" checked name="intervaltype_report" type="radio" data-target="#scheduleDaily_report">
							<label for="optDaily_report" style=""><b>Individual</b></label>&nbsp;&nbsp;&nbsp;
							<input id="optWeekly_report" name="intervaltype_report" type="radio" data-target="#scheduleWeekly_report">
							    <label for="optWeekly_report"><b>Business</b></label>
							</div>
                    	<div class="tab-content">
   					    <div id="scheduleDaily_report" class="tab-pane active">
						<div class="row">
							 <div class="col-md-8 text-center"> 
						   		               	
								<div class="col-md-6 col-sm-8 col-md-offset-3 col-sm-offset-2">
									<form action="{{ route('super-admin-reports',$userId) }}" method="POST" enctype="multipart/form-data">
										@csrf
										<div class="form-group">
												<input type="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.bmp,.csv" class="form-control fl-upload-height" required>
												<!--<small id="fileHelp" class="form-text text-muted">File to be uploaded is:</small>--> 
										</div>
										<div class="">
											
										</div>
										<div class="form-action">
											  <button class="btn btn-success btn-blue">Upload Data</button>
										</div>
									</form>
								</div>
							 </div>
						</div>
					</div>
    <div id="scheduleWeekly_report" class="tab-pane">
						<div class="row">
						    <div class="col-md-8 text-left">
							   	</div>
							 <div class="col-md-8 text-center"> 
						   		               	
								<div class="col-md-6 col-sm-8 col-md-offset-3 col-sm-offset-2">
									<form action="{{ route('import-business-report-super',$userId) }}" method="POST" enctype="multipart/form-data">
										@csrf
										<div class="form-group">
												<input type="file" name="file" accept=".pdf,.doc,.docx,.xls,.xlsx,.bmp,.csv" class="form-control fl-upload-height" required>
										</div>
										<div class="form-action">
											  <button class="btn btn-success btn-blue">Upload Data</button>
										</div>
									</form>
								</div>
							 </div>
						</div>
					</div>
				</div>
					</div>
				<!-- </div> -->
			</div>
		</div>
		
			</div>
		</div>


		<!-- @if($isShow) -->
		<h1 class="page-title">
        	<i class="voyager-upload"></i> Upload Payment Master File
    	</h1>
		<div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
						<div class="row">
						    <div class="col-md-8 text-left">
							   		<p style="color:red;font-weight:bold">Invoice Number, Payment Date(DD/MM/YYYY) and Payment Amount</p>
							   		<p style="color:green;font-weight:bold">Payment Note</p>
							   		<p style="color:black;font-weight:bold">* Fields marked in Red are mandatory and in Green are optional</p>
							   	</div>
							 <div class="col-md-8 text-center"> 
						   		               	
								<div class="col-md-6 col-sm-8 col-md-offset-3 col-sm-offset-2">
									<form action="{{ route('import-due-payment') }}" method="POST" enctype="multipart/form-data">
										@csrf
			                                
										<div class="form-group">
												<input type="file" name="file" accept=".xlsx" class="form-control fl-upload-height" required>
												<!--<small id="fileHelp" class="form-text text-muted">File to be uploaded is:</small>-->
										</div>
										<div class="">
											
										</div>
										<div class="form-action">
											  <button class="btn btn-success btn-blue">Upload Data</button>
										</div>
									</form>
								</div>
								<div class="col-md-12 col-sm-12">
							   		<p>By clicking Upload you indicate that you have read and agree to the terms of the Recordent <a target="_blank" href="{{route('end-user-license-agreement')}}">End User License Agreement</a></p>
							   	</div>
							 </div>
						<!-- 	 <div class="col-md-4 text-md-right text-center">
							 	<div><a href="{{asset('PaymentMasterDataFile.xlsx')}}" download="PaymentMasterDataFile.xlsx" class="btn btn-warning download-mas-for btn-red">Download Master File Format</a></div>
							 </div> -->
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- @endif -->

		
		<!-- @if($isShow) -->
		<h1 class="page-title">
        	<i class="voyager-upload"></i> Upload Payment Master File
    	</h1>

		<div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
						<div class="row">
						    <div class="col-md-8 text-left">
							   		<p style="color:red;font-weight:bold">Invoice Number, Payment Date(DD/MM/YYYY) and Payment Amount</p>
							   		<p style="color:green;font-weight:bold">Payment Note</p>
							   		<p style="color:black;font-weight:bold">* Fields marked in Red are mandatory and in Green are optional</p>
							   	</div>
							 <div class="col-md-8 text-center"> 
						   		               	
								<div class="col-md-6 col-sm-8 col-md-offset-3 col-sm-offset-2">
									<form action="{{ route('import-business-due-payment') }}" method="POST" enctype="multipart/form-data">
										@csrf
			                                
										<div class="form-group">
												<input type="file" name="file" accept=".xlsx" class="form-control fl-upload-height" required>
												<!--<small id="fileHelp" class="form-text text-muted">File to be uploaded is:</small>-->
										</div>
										<div class="">
											
										</div>
										<div class="form-action">
											  <button class="btn btn-success btn-blue">Upload Data</button>
										</div>
									</form>
								</div>
								<div class="col-md-12 col-sm-12">
							   		<p>By clicking Upload you indicate that you have read and agree to the terms of the Recordent <a target="_blank" href="{{route('end-user-license-agreement')}}">End User License Agreement</a></p>
							   	</div>
							 </div>
							 
						</div>
					</div>
				</div>
			</div>
		</div>

		
		<!-- @endif -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script> 
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script> 
<script type="text/javascript">
	$(document).ready(function () {
  $('input[name="intervaltype"]').click(function () {
      $(this).tab('show');
      $(this).removeClass('active');
  });
  $('input[name="profiletype"]').click(function () {
      $(this).tab('show');
      $(this).removeClass('active');
  });
  $('input[name="intervaltype_report"]').click(function () {
      $(this).tab('show');
      $(this).removeClass('active');
  });
})
</script>
@endsection
