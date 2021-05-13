@extends('voyager::master')

@section('page_title', __('voyager::generic.create').' Edit Record')

@section('page_header')
    <h1 class="page-title">
        
        <i class="voyager-edit"></i>Update Profile
        
    </h1>
    @if (count($errors) > 0)
        <div class="alert alert-danger">
            <ul>
             @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
             @endforeach
            </ul>
        </div>
    @endif
@stop
@section('content')
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script> 
    <div class="page-content container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                	@if(!empty($data))
                    <div class="panel-body">
                    	<form action="{{route('business.update-business')}}" name="add_store_record" id="add_store_record" method="POST" enctype="multipart/form-data">
							@csrf	
							<input type="hidden" name="id" value="{{$data->id}}">
							<input type="hidden" name="redirectQueryString" id="redirectQueryString" value="{{(Request::getQueryString() ? ('?' . Request::getQueryString()) : '')}}" />
							<input type="hidden" name="all_values" id="all_values" value="{{$data}}">
							<div class="col-md-6">
								<div class="form-group">
									<label for="contact_phone">Company Name*</label>
									<input type="text" class="form-control" name="company_name" value="{{$data->company_name}}" placeholder="Company Name" required maxlength="{{General::maxlength('name')}}" onblur="trimIt(this);">
								</div>
							</div>

							<div class="col-md-6">
	                        	<div class="form-group">
	                        		<label for="contact_phone">Business Type*</label>
									<select name="user_type" id="user_type"  placeholder="Select Business Type" class="form-control" required>
							            <option value="">Select</option>
							            @if($userTypes->count())  
								            @foreach($userTypes as $userType)
								            	<option value="{{$userType->id}}" {{$data->user_type==$userType->id ? 'selected' : '' }}>{{$userType->name}}</option>
								            @endforeach  
							            @endif   
							        </select>
					        	</div>
					        </div>

					        <input type="hidden" id="business_type_id_hidden" value="{{Auth::user()->user_type}}">
							 
							 <div class="col-md-6" id="type_of_business_div" style="display:none">
	                        	<div class="form-group">
					         <label>Type of Business</label>
					             <input type="text" name="type_of_business" id="type_of_business" value="{{ old('type_of_business',Auth::user()->type_of_business) }}" placeholder="Please specify type of business" class="form-control">
					         </div>
					     </div>

							<input type="hidden" name="sector_id" value="1"> 
							<div class="col-md-6">
	                        	<div class="form-group">
	                        		<label for="contact_phone">Business Sector</label>
									<select name="sector_id" id="sector_id"  placeholder="Select Sector" class="form-control">
							            <option value="">Select</option>
							            @if($sectors->count())  
								            @foreach($sectors as $sector)
								            	<option value="{{$sector->id}}" {{$data->sector_id==$sector->id ? 'selected' : '' }}>{{$sector->name}}</option>
								            @endforeach  
							            @endif
							        </select>
					        	</div>
					        </div>

					        <input type="hidden" id="sector_type_id_hidden" value="{{Auth::user()->sector_id}}">

					        <div class="col-md-6" id="type_of_sector_div" style="display:none">
	                         <div class="form-group">	
					          <label>Type of Sector</label>
					             <input type="text" name="type_of_sector" id="type_of_sector" value="{{ old('type_of_sector',Auth::user()->type_of_sector) }}" placeholder="Please specify type of sector" class="form-control">
					         </div>
					        </div>

							<div class="col-md-6">
								<div class="form-group">
									<label for="contact_phone">{{General::getLabelName('unique_identification_number')}}*</label>
									<input type="text" class="form-control" name="unique_identification_number" style="text-transform:uppercase" value="{{$data->unique_identification_number}}" placeholder="{{General::getLabelName('unique_identification_number')}}*" maxlength="15" required onblur="trimIt(this);">
								</div>
							</div>
							
							<div class="col-md-6">
								<div class="form-group">
									<label for="contact_phone">Concerned Person Name*</label>
									<input type="text" class="form-control" name="concerned_person_name" value="{{$data->concerned_person_name}}" maxlength="{{General::maxlength('name')}}" placeholder="Concerned Person Name*" required>
								</div>
							</div>
                           <div class="col-md-6">
								<div class="form-group">
									<label for="contact_phone">Concerned Person Designation*</label>
									<input type="text" class="form-control" name="concerned_person_designation" value="{{$data->concerned_person_designation}}" placeholder="Concerned Person Designation*" required>
								</div>
							</div>
							<div class="col-md-6">
	                            <div class="form-group">
									<label for="contact_phone">Concerned Person Phone*</label>
									<input type="text" class="form-control" name="concerned_person_phone" value="{{$data->concerned_person_phone}}" placeholder="Concerned Person Phone" required onkeypress="return numbersonly(this,event)">
								</div>
							</div>
                           <div class="col-md-6">
	                            <div class="form-group">
									<label for="contact_phone">Concerned Person Alternate Phone</label>
									<input type="text" class="form-control" name="concerned_person_alternate_phone" value="{{$data->concerned_person_alternate_phone}}" placeholder="Concerned Person Alternate Phone">
								</div>
							</div>
							
                            
							<div class="col-md-6">
	                        	<div class="form-group">
	                        		<label for="contact_phone">State*</label>
									<select name="state" id="state"  placeholder="Select State" class="form-control" required>
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
									<select name="city" id="city"  placeholder="Select city" class="form-control" required>
							            <option value="">Select</option>
							        </select>
					        	</div>
					        </div>

					        <div class="col-md-6">
	                            <div class="form-group">
									<label for="contact_phone">Pin Code</label>
									<input type="text" class="form-control" name="pin_code" value="{{$data->pincode}}" placeholder="Pin Code">
								</div>
                            </div>

                            <div class="col-md-6">
	                            <div class="form-group">
									<label for="email">Email*</label>
									<input type="text" class="form-control" maxlength="{{General::maxlength('email')}}" name="email" value="{{$data->email}}" placeholder="Email" required>
								</div>
                            </div>

                            <div class="col-md-6">
	                            <div class="form-group">
									<label for="contact_phone">Address</label>
									<input type="text" class="form-control" name="address" value="{{$data->address}}" placeholder="Address">
								</div>
                            </div>
							
                            <div class="col-md-12">							
								<div class="form-action ">
									<button type="submit" class="btn btn-primary">SUBMIT</button>
									<a href="{{route('business.my-records')}}{{(Request::getQueryString() ? ('?' . Request::getQueryString()) : '')}}"><button type="button" class="btn btn-primary">Cancel</button></a>
								</div>	
							</div>

						</form>

					</div>
					@else
						<div class="panel-body">
							<h3>No Record Found</h3>
						</div>
					@endif
				</div>
			</div>
		</div>
    </div>
@if(!empty($data))    
<select id="maincity" style="display: none">
    @if($cities->count())  
	    @foreach($cities as $city)
	    	<option data-state-id="{{$city->state_id}}" value="{{$city->id}}">{{$city->name}}</option>
	    @endforeach  
    @endif
 </select>
<script src="{{asset('js/jquery.validate.min.js')}}"></script>
<script src="{{asset('js/number-to-word.js')}}"></script>
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

    $("#user_type").on('change',function(){
        $("#type_of_business_div").find('input').val('');
        // if($(this).val()==10 || $(this).val()==11){
        if($('#user_type :selected').text()== "Others"){

            $("#type_of_business_div").show(1);
            $("#type_of_business_div").find('input').attr('required','required');

        }else{
            $("#type_of_business_div").hide(1);
            //$("#type_of_business_div").find('input').removeAttr('required');
        }
    });

   $("#sector_id").on('change',function(){
        $("#type_of_sector_div").find('input').val('');
        // if($(this).val()==10 || $(this).val()==11){
        	if($('#sector_id :selected').text()== "Others"){

            $("#type_of_sector_div").show(1);
            $("#type_of_sector_div").find('input').attr('required','required');
        }else{
            $("#type_of_sector_div").hide(1);
        }
    });

  });  

$.validator.addMethod("check_gstin", function(value, element) {
        if(value.toString().length == 10) {
			var valueToString = value.toString().toUpperCase();
			// var fourthChar = valueToString.charAt(3);
			// var allowedCharsAtFourthPosition = ["C","H","A","B","G","J","L","F","T"];
			if(valueToString) {
				return this.optional(element) || /^[A-Z]{3}[A|B|C|F|G|H|L|J|P|T]{1}[A-Z]{1}[0-9]{4}[A-Z]{1}$/i.test(value);
			} else {
				return false;
			}
		} else {
          return this.optional(element) || /^[0-3|9]{1}[0-9]{1}[A-Z]{3}[A|B|C|F|G|H|L|J|P|T]{1}[A-Z]{1}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/i.test(value);
      }
    }, "Please enter a valid GSTIN/Business PAN.");

$.validator.addMethod("alphaspace", function(value, element) {
        return this.optional(element) || /^[a-z ]+$/i.test(value);
    }, "Only alphabet and space allowed.");

$.validator.addMethod("alphaspacename", function(value, element) {
        return this.optional(element) || /^[a-z0-9&. -]+$/i.test(value);
    }, "Please enter valid business name.");	

$.validator.addMethod("mobile_number_india", function(value, element) {
        return this.optional(element) || /^[6789]\d{9}$/i.test(value);
    }, "Please enter a valid number.");

$('#add_store_record').validate({
        rules: {
            unique_identification_number: {
              maxlength: 15,
              minlength:2,
              required:true,
			  check_gstin:true
            },
             email: {
             	required: true,
                email: true
            },
            company_name: {
	            alphaspacename:true,
	            maxlength:{{General::maxlength('name')}},
             },
            concerned_person_name:{
                maxlength:{{General::maxlength('name')}},
                alphaspace:true
            },
            concerned_person_designation: {
	            alphaspace:true,
	            maxlength:28
             }, 
             concerned_person_phone:{
                maxlength:10,
                mobile_number_india:true
            },
           concerned_person_alternate_phone:{
                maxlength:10,
                mobile_number_india:true
            }

                   }
    });

	function numbersonly(myfield, e)
    {
        var key;
        var keychar;
        if (window.event)
            key = window.event.keyCode;
        else if (e)
            key = e.which;
        else
            return true;

//        alert(1);
        keychar = String.fromCharCode(key);
        // control keys
        if ((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) )
            return true;
        // numbers
        else if ((("0123456789").indexOf(keychar) > -1)){
        	return true;
        }
        else{
        	return false;
        }
    }


	function trimIt(currentElement){
    	$(currentElement).val(currentElement.value.trim());
	}
	
</script>
@endif	
@endsection