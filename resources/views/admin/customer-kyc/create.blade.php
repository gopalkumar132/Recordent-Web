@extends('voyager::master')

@section('page_title', __('voyager::generic.create').' customer kyc')

@section('page_header')
    <h1 class="page-title">
        
        <i class="voyager-plus"></i> {{-- $records->display_name_plural --}}Add Customer Kyc
        
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
                    <div class="panel-body">
						<form action="{{ route('customer-kyc-store') }}" method="POST" enctype="multipart/form-data">
							@csrf	
					       
							<div class="form-group">
								<label for="contact_phone">*Select Customer</label>
								<select class="form-control" name="customer_id" required>
									<option>Select</option>
									@foreach($customerList as $customer)
										<option value="{{$customer->id}}">{{$customer->firstname.' '.$customer->lastname}}</option>
									@endforeach
								</select>	
							</div>	

							<div class="form-group">
								<label for="contact_phone">*Present Address</label>
								<textarea class="form-control" name="present_address" required>{{old('present_address')}}</textarea>
							</div>

							<div class="form-group">
								<label for="contact_phone">*Previous Residence Address</label>
								<textarea class="form-control" name="previous_residence_address" required>{{old('previous_residence_address')}}</textarea>
							</div>

							<div class="form-group">
								<label for="contact_phone">*Previous Residence Telephone</label>
								<input type="text" class="form-control" name="previous_residence_telephone" value="{{old('previous_residence_telephone')}}">
							</div>

							
							<div class="form-group">
								<label for="contact_phone">*Permenent Address</label>
								<textarea class="form-control" name="permenent_address" required>{{old('permenent_address')}}</textarea>
							</div>

							<div class="form-group">
								<label for="contact_phone">*Permenent Telephone</label>
								<input type="text" class="form-control" name="permenent_telephone" value="{{old('permenent_telephone')}}" required>
							</div>

							<div class="form-group">
								<label for="contact_phone">*Select Id Proof Type</label>
								<select class="form-control" name="id_proof_type" required>
									<option>Select</option>
								
									@foreach($idProofType as $idProof)
									<option value="{{$idProof->id}}" {{old('id_proof_type')==$idProof->id ? 'selected' : '' }}>{{$idProof->name}}</option>
									@endforeach
								</select>	
							</div>	
							<div class="form-group">
								<label for="contact_phone">*ID Proof Number</label>
								<input type="text" class="form-control" name="id_proof_number" value="{{old('id_proof_number')}}" required>
							</div>

							<div class="form-group">
								<label for="contact_phone">*Upload ID Proof Image</label>
								<input type="file" class="form-control" name="id_proof_image" required>
							</div>	
							
							<div class="form-group">
								<label for="contact_phone">*Select Address Proof Type</label>
								<select class="form-control" name="address_proof_type" required>
									<option>Select</option>
									@foreach($addressProofType as $addressProof)
									<option value="{{$addressProof->id}}" {{old('address_proof_type')==$addressProof->id ? 'selected' : '' }}>{{$addressProof->name}}</option>
									@endforeach
								</select>	
							</div>	
							<div class="form-group">
								<label for="contact_phone">*Address Proof Number</label>
								<input type="text" class="form-control" name="address_proof_number" value="{{old('address_proof_number')}}" required>
							</div>

							<div class="form-group">
								<label for="contact_phone">*Upload Address Proof Image</label>
								<input type="file" class="form-control" name="address_proof_image" required>
							</div>	



							<div class="form-group">
								<label for="contact_phone">*Select Vehicle Type</label>
								<select class="form-control" name="vehicle_type" required>
									<option>Select</option>
									@foreach($vehicleType as $vehicle)
									<option value="{{$vehicle->id}}" {{old('vehicle_type')==$vehicle->id ? 'selected' : '' }}>{{$vehicle->name}}</option>
									@endforeach
								</select>	
							</div>	
							<div class="form-group">
								<label for="contact_phone">*Vehicle Name</label>
								<input type="text" class="form-control" name="vehicle_name" value="{{old('vehicle_name')}}" required>
							</div>
							<div class="form-group">
								<label for="contact_phone">*Vehicle Number</label>
								<input type="text" class="form-control" name="vehicle_number" value="{{old('vehicle_number')}}" required>
							</div>

														
							<div class="form-check">
								<input type="checkbox" class="form-check-input" name="agree_terms">
								<label class="form-check-label" for="agree_terms">Check here to indicate that you have read and agree to the terms of the <a target="_blank" href="{{route('end-user-license-agreement')}}" target="_blank">Recordent End User License Agreement</a></label>
							</div>							
							<div class="form-action ">
								<button type="submit" class="btn btn-primary">SUBMIT</button>
							</div>			
						</form>

					</div>
				</div>
			</div>
		</div>
    </div>
	
@endsection