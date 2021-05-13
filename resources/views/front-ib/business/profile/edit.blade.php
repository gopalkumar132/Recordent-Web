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
		       @if (\Session::get('message'))
		           <div class="alert alert-success">
		                <span class="font-weight-semibold">{{ \Session::get('message') }}</span> 
		           </div>
		        @endif 
		        <div class="row">
		            <div class="col-md-12">
		                <div class="panel panel-bordered">
		                	<div class="panel-body">
		                		
			                    	<form action="{{route('front-business.profile-update')}}" name="add_store_record" id="add_store_record" method="POST" enctype="multipart/form-data">
										@csrf	
										<input type="hidden" name="id" value="{{$data->id}}">
										<input type="hidden" name="redirectQueryString" id="redirectQueryString" value="{{(Request::getQueryString() ? ('?' . Request::getQueryString()) : '')}}" />
										<div class="col-md-6">
											<div class="form-group">
												<label for="contact_phone">Company Name*</label>
												<input type="text" class="form-control" name="company_name" value="{{$data->company_name}}" placeholder="Company Name" {{!empty($data->company_name) ? 'readonly' : ''}}>
											</div>
										</div>
										
										<div class="col-md-6">
				                        	<div class="form-group">
				                        		<label for="contact_phone">Business Type*</label>
												<select name="sector_id" id="sector"  placeholder="Select Sector" class="form-control" {{!empty($data->sector_id) ? 'disabled' : ''}}>
										            <option value="">Select</option>
										            @if($sectors->count())  
											            @foreach($sectors as $sector)
											            	<option value="{{$sector->id}}" {{$data->sector_id==$sector->id ? 'selected' : '' }}>{{$sector->name}}</option>
											            @endforeach  
										            @endif
										        </select>
								        	</div>
								        </div>

										<div class="col-md-6">
											<div class="form-group">
												<label for="contact_phone">{{General::getLabelName('unique_identification_number')}}*</label>
												<input type="text" class="form-control" name="unique_identification_number" value="{{$data->unique_identification_number}}" placeholder="{{General::getLabelName('unique_identification_number')}}*" {{!empty($data->unique_identification_number) ? 'readonly' : ''}}>
											</div>
										</div>

										<div class="col-md-6">
											<div class="form-group">
												<label for="contact_phone">Concerned Person Name*</label>
												<input type="text" class="form-control" name="concerned_person_name" value="{{$data->concerned_person_name}}" placeholder="Concerned Person Name*" {{!empty($data->concerned_person_name) ? 'readonly' : ''}}>
											</div>
										</div>
			                           <div class="col-md-6">
											<div class="form-group">
												<label for="contact_phone">Concerned Person Designation*</label>
												<input type="text" class="form-control" name="concerned_person_designation" value="{{$data->concerned_person_designation}}" placeholder="Concerned Person Designation*" {{!empty($data->concerned_person_designation) ? 'readonly' : ''}}>
											</div>
										</div>
										<div class="col-md-6">
				                            <div class="form-group">
												<label for="contact_phone">Concerned Person Phone*</label>
												<input type="text" class="form-control" name="concerned_person_phone" value="{{$data->concerned_person_phone}}" placeholder="Concerned Person Phone" {{!empty($data->concerned_person_phone) ? 'readonly' : ''}}>
											</div>
										</div>
			                           <div class="col-md-6">
				                            <div class="form-group">
												<label for="contact_phone">Concerned Person Alternate Phone</label>
												<input type="text" class="form-control" name="concerned_person_alternate_phone" value="{{$data->concerned_person_alternate_phone}}" placeholder="Concerned Person Alternate Phone" {{!empty($data->concerned_person_alternate_phone) ? 'readonly' : ''}}>
											</div>
										</div>
										
			                            
										<div class="col-md-6">
				                        	<div class="form-group">
				                        		<label for="contact_phone">State*</label>
												<select name="state" id="state"  placeholder="Select State" class="form-control" {{!empty($data->state_id) ? 'disabled' : ''}}>
										            <option value="">Select</option>
										            @if($states->count())  
											            @foreach($states as $state)
											            	<option value="{{$state->id}}" {{$data->state_id==$state->id ? 'selected' : '' }}>{{$state->name}}</option>
											            @endforeach  
										            @endif
										        </select>
								        	</div>
								        </div>	

								        <div class="col-md-6">
				                        	<div class="form-group">
				                        		<label for="contact_phone">City*</label>
												<select name="city" id="city"  placeholder="Select city" class="form-control" {{!empty($data->city_id) ? 'disabled' : ''}}>
										            <option value="">Select</option>
										        </select>
								        	</div>
								        </div>

								        <div class="col-md-6">
				                            <div class="form-group">
												<label for="contact_phone">Pin Code</label>
												<input type="text" class="form-control" name="pin_code" value="{{$data->pincode}}" placeholder="Pin Code" {{!empty($data->pincode) ? 'readonly' : ''}}>
											</div>
			                            </div>

			                            <div class="col-md-6">
				                            <div class="form-group">
												<label for="contact_phone">Address</label>
												<input type="text" class="form-control" name="address" value="{{$data->address}}" placeholder="Address" {{!empty($data->address) ? 'readonly' : ''}}>
											</div>
			                            </div>
										
			                            
			                            
			                            <div class="col-md-12">							
											<div class="form-action ">
												<button type="submit" class="btn btn-primary">SUBMIT</button>
												<a href="{{route('front-business.dashboard')}}{{(Request::getQueryString() ? ('?' . Request::getQueryString()) : '')}}"><button type="button" class="btn btn-primary">Cancel</button></a>
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
<select id="maincity" style="display: none">
    @if($cities->count())  
	    @foreach($cities as $city)
	    	<option data-state-id="{{$city->state_id}}" value="{{$city->id}}">{{$city->name}}</option>
	    @endforeach  
    @endif
 </select>
 <script language="javascript" type="application/javascript">
	//$('#add_store_record').validate();

$(document).ready(function(){


	if($("#state").val()!=''){ 
		@if($data->city_id)		
	    var oldCity = "{{$data->city_id}}";	
		var selected = '';
    	$("#city").find('option').remove();
    	//$("#city").append('<option value="">Select</option>');
     	var stateId =  $("#state").val();
        $("#maincity option").each(function(){
        	if($(this).data('state-id')==stateId){
				var cityId = $(this).val();
				if(oldCity==cityId) { selected= 'selected';}else{selected= ''}
        		$("#city").append('<option value="'+$(this).val()+'" '+selected+'>'+$(this).text()+'</option>');	
        	}
        });
        @endif
      } 



    $("#state").on('change',function(){
    	$("#city").find('option').remove();
    	$("#city").append('<option value="">Select</option>');

     if($("#state").val()!=''){  
     	var stateId =  $("#state").val();
        $("#maincity option").each(function(){
        	if($(this).data('state-id')==stateId){
        		$("#city").append('<option value="'+$(this).val()+'">'+$(this).text()+'</option>');	
        	}
        });
      }  
    });

  });  
</script>
@endsection