@extends('voyager::master')


@section('page_header')
    <h1 class="page-title">
        <i class="voyager-upload"></i> Upload Master File
    </h1>

	<!-- <div class="pull-right" style="padding-top: 10px;">
		 <a href="{{route('export')}}" class="btn btn-info download-mem-data btn-blue">Download Individual Customers <i class="voyager-download"></i></a>
	</div> -->
@stop

@section('content')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
						<div class="row">
						    <div class="col-md-8 text-left">
							   		<p style="color:red;font-weight:bold">Mobile Number, Person Name (Alphabets, Spaces), DueDate (DD/MM/YYYY) and Due Amount</p>
							   		<p style="color:green;font-weight:bold">Aadhar Number (Last six digits), Date Of Birth (DD/MM/YYYY), Father Name (Alphabets, Spaces), Mother name (Alphabets, Spaces), Due note</p>
							   		<p style="color:black;font-weight:bold">* Fields marked in Red are mandatory and in Green are optional</p>
							   	</div>
							 <div class="col-md-8 text-center">

								<div class="col-md-6 col-sm-8 col-md-offset-3 col-sm-offset-2">
									<form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
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
							 	<div><a href="{{asset('MasterDataFile.xlsx')}}" download="Individual-MasterDataFile.xlsx" class="btn btn-warning download-mas-for btn-red">Download Master File Format</a></div>
							 </div>
						</div>
					</div>
				</div>
			</div>
		</div>
		@if($isShow)
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
							 <div class="col-md-4 text-md-right text-center">
							 	<div><a href="{{asset('PaymentMasterDataFile.xlsx')}}" download="PaymentMasterDataFile.xlsx" class="btn btn-warning download-mas-for btn-red">Download Master File Format</a></div>
							 </div>
						</div>
					</div>
				</div>
			</div>
		</div>
		@endif
<!-- <h1 class="page-title">
	        	<i class="voyager-upload"></i> Upload Profile Master File
	    	</h1>

			<div class="row">
	            <div class="col-md-12">
	                <div class="panel panel-bordered">
	                    <div class="panel-body">
							<div class="row">
							    <div class="col-md-8 text-left">
							   		<p style="color:red;font-weight:bold">Customer Id</p>
							   		<p style="color:green;font-weight:bold">State, City, Email, Custom Id</p>
							   		<p style="color:black;font-weight:bold">* Fields marked in Red are mandatory and in Green are optional</p>
							   	</div>
								<div class="col-md-8 text-center">
									<div class="col-md-6 col-sm-8 col-md-offset-3 col-sm-offset-2">
										<form action="{{ route('import-update-profile',Auth::id()) }}" method="POST" enctype="multipart/form-data">
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
								 		<a href="{{asset('ProfileMasterDataFile.xlsx')}}" download="ProfileMasterDataFile.xlsx" class="btn btn-warning download-mas-for btn-red">Download Master File Format</a>
								 	</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div> -->

@endsection
