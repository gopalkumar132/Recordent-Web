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
                    	<p style="color:red;font-weight:bold">NOTE : (Aadhaar Number or (Contact Phone Number and Person Name)) and Due Date and Due Amount are required.</p>
						<form action="{{route('update-student')}}" name="add_store_record" id="add_store_record" method="POST" enctype="multipart/form-data">
							@csrf	
							
							<input type="hidden" name="id" value="{{$data->id}}">
							<input type="hidden" name="redirectQueryString" id="redirectQueryString" value="{{(Request::getQueryString() ? ('?' . Request::getQueryString()) : '')}}" />
                            <input type="hidden" name="all_values" id="all_values" value="{{$data}}">
							
							<div class="col-md-6">
								<div class="form-group">
									<label for="contact_phone">Aadhaar Number</label>
									<input type="text" class="form-control" name="aadhar_number"  data-mask="9999-9999-9999" placeholder="1111-2222-3333" value="{{$data->aadhar_number}}">
								</div>
							</div>
                            <div class="col-md-6">
	                            <div class="form-group">
									<label for="contact_phone">Contact Phone Number*</label>
									<input type="text" class="form-control" name="contact_phone" placeholder="Contact Phone Number" value="{{$data->contact_phone}}" required onkeypress="return numbersonly(this,event)">
								</div>
							</div>
                            <div class="col-md-6">
								<div class="form-group">
									<label for="contact_phone">Person Name*</label>
									<input type="text" class="form-control" name="person_name" maxlength="{{General::maxlength('name')}}" placeholder="Person Name" value="{{$data->person_name}}" required>
								</div>
                            </div>
                            
                            <!-- <div class="col-md-6">
	 							<div class="form-group">
									<label for="contact_phone">DOB (MM/DD/YYYY)</label>
	                                <input type="date" name="dob" class="form-control " placeholder="" aria-controls="dataTable" value="{{$data->dob}}">
								</div>
							</div> -->

							<div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_phone">DOB (DD/MM/YYYY)</label>
                                    <input type="text" name="dob" class="form-control datepicker" data-date-format="DD/MM/YYYY" aria-controls="dataTable" value="{{$data->dob}}">
                                </div>
                            </div>
                            <div class="col-md-6">
								<div class="form-group">
									<label for="contact_phone">Father Name</label>
									<input type="text" class="form-control" name="father_name" maxlength="{{General::maxlength('name')}}"  placeholder="Father Name" value="{{$data->father_name}}">
								</div>
							</div>
                            
                            <div class="col-md-6">
								<div class="form-group">
									<label for="contact_phone">Mother Name</label>
									<input type="text" class="form-control" name="mother_name" maxlength="{{General::maxlength('name')}}" placeholder="Mother Name" value="{{$data->mother_name}}">
								</div>
							</div>

							  <div class="col-md-6">
								<div class="form-group">
									<label for="email">Email</label>
									<input type="text" class="form-control" name="email" maxlength="{{General::maxlength('email')}}"  placeholder="Email" value="{{$data->email}}">
								</div>
							</div>
                            
                            
                            <div class="col-md-12">							
								<div class="form-action ">
									<button type="submit" class="btn btn-primary">SUBMIT</button>
									<a href="{{route('my-records')}}{{(Request::getQueryString() ? ('?' . Request::getQueryString()) : '')}}"><button type="button" class="btn btn-primary">Cancel</button></a>
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

<script src="{{asset('js/jquery.validate.min.js')}}"></script>
<script src="{{asset('js/number-to-word.js')}}"></script>    
<script language="javascript" type="application/javascript">

	 $('body').on('focus', '.datepicker', function() {
            $(this).datetimepicker();
        });
	
	$.validator.addMethod("alphaspace", function(value, element) {
        return this.optional(element) || /^[a-z ]+$/i.test(value);
    }, "Only alphabet and space allowed.");

	$.validator.addMethod("mobile_number_india", function(value, element) {
        return this.optional(element) || /^[6789]\d{9}$/i.test(value);
    }, "Please enter a valid number.");

     $.validator.addMethod("dob_check", function(value, element) {
        var returnFlag = true;
        var currentDate = new Date();
        var dateString = value;
        var dateParts = dateString.split("/");
        var dateObject = new Date(+dateParts[2], dateParts[1] - 1, +dateParts[0]);
        console.log(dateObject);
        if (dateObject.getTime() > currentDate.getTime()) {
            returnFlag = false;
        }
        return returnFlag;
    }, "DOB should not greater than current date");

	$('#add_store_record').validate({
		rules: {
			contact_phone: {
                maxlength: 10,
                mobile_number_india: true
            },
            person_name: {
                alphaspace: true,
                maxlength: {{General::maxlength('name')}}
            },
            father_name: {
                alphaspace: true,
                maxlength: {{General::maxlength('name')}}
            },
            mother_name: {
                alphaspace: true,
                maxlength: {{General::maxlength('name')}}
            },    
            dob: {
                dob_check: true
            },
            email: {
                email: true
            }
		}
	});

	 function numbersonly(myfield, e, maxlength = null) {
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
        if ((key == null) || (key == 0) || (key == 8) || (key == 9) || (key == 13) || (key == 27))
            return true;
        // numbers
        else if ((("0123456789").indexOf(keychar) > -1)) {
            return true;
        } else {
            return false;
        }
    }
</script>	
@endsection