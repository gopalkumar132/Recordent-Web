@extends('layouts_front_ib.master')
@section('content')
<!-- BEGIN CONTENT -->
<div class="container-fluid" data-select2-id="13">

    <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script> 
	
		<div class="side-body padding-top" data-select2-id="12">
			<div class="container-fluid padding-20">
		          <h1 class="page-title"> <i class="voyager-person"></i> Edit Profile</h1>
		    </div>
		    <div class="page-content container-fluid">
		        @include('layouts_front_ib.error')
		       
		        <div class="row">
		            <div class="col-md-12">
		                <div class="panel panel-bordered">
		                	<div class="panel-body">
		                    	<form action="{{route('front-individual.profile-update')}}" name="add_store_record" id="add_store_record" method="POST" enctype="multipart/form-data">
									@csrf	
									
									<input type="hidden" name="id" value="{{$data->id}}">
									<input type="hidden" name="redirectQueryString" id="redirectQueryString" value="{{(Request::getQueryString() ? ('?' . Request::getQueryString()) : '')}}" />
									
									<div class="col-md-6">
										<div class="form-group">
											<label for="contact_phone">Aadhaar Number</label>
											<input type="text" class="form-control" name="aadhar_number"  data-mask="9999-9999-9999" placeholder="1111-2222-3333" value="{{$data->aadhar_number}}" {{!empty($data->aadhar_number) ? 'readonly' : ''}}>
										</div>
									</div>
		                            <div class="col-md-6">
			                            <div class="form-group">
											<label for="contact_phone">Contact Phone Number</label>
											<input type="text" class="form-control" name="contact_phone" placeholder="Contact Phone Number" value="{{$data->contact_phone}}" {{!empty($data->contact_phone) ? 'readonly' : ''}}>
										</div>
									</div>
		                            <div class="col-md-6">
										<div class="form-group">
											<label for="contact_phone">Person Name</label>
											<input type="text" class="form-control" name="person_name"  placeholder="Person Name" value="{{$data->person_name}}" {{!empty($data->person_name) ? 'readonly' : ''}}>
										</div>
		                            </div>
		                            
		                            <div class="col-md-6">
			 							<div class="form-group">
											<label for="contact_phone">DOB (MM/DD/YYYY)</label>
			                                <input type="date" name="dob" class="form-control " placeholder="" aria-controls="dataTable" value="{{$data->dob}}" {{!empty($data->dob) ? 'readonly' : ''}}>
										</div>
									</div>
		                            <div class="col-md-6">
										<div class="form-group">
											<label for="contact_phone">Father Name</label>
											<input type="text" class="form-control" name="father_name"  placeholder="Father Name" value="{{$data->father_name}}" {{!empty($data->father_name) ? 'readonly' : ''}}>
										</div>
									</div>
		                            
		                            <div class="col-md-6">
										<div class="form-group">
											<label for="contact_phone">Mother Name</label>
											<input type="text" class="form-control" name="mother_name"  placeholder="Mother Name" value="{{$data->mother_name}}" {{!empty($data->mother_name) ? 'readonly' : ''}}>
										</div>
									</div>
		                            
		                            
		                            <div class="col-md-12">							
										<div class="form-action ">
											<button type="submit" class="btn btn-primary">SUBMIT</button>
											<a href="{{route('front-individual.profile')}}{{(Request::getQueryString() ? ('?' . Request::getQueryString()) : '')}}"><button type="button" class="btn btn-primary">Cancel</button></a>
										</div>	
									</div>		
								</form>
							</div>
							
						</div>
					</div>
				</div>
		    </div>
		</div>
	    



</div>    

@endsection