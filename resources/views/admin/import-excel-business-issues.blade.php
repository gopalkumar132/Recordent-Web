@extends('voyager::master')
 

@section('page_header')
    <!-- <h1 class="page-title">
        <i class="voyager-upload"></i> Upload Master File
    </h1> -->
    <?php if($userId!=""){ ?> 
    
    <div class="pull-right" style="padding-top: 10px;padding-bottom: 10px;"><a href="{{route('super-excel',$userId)}}" class="btn btn-info">Import Excel Data <i class="voyager-document"></i></a></div> 

   <?php } else{ ?>
	<div class="pull-right" style="padding-top: 10px;padding-bottom: 10px;"><a href="{{route('import-excel-view-business')}}" class="btn btn-info">Import Excel Data <i class="voyager-document"></i></a></div>
  <?php }?>
@stop

@section('content')
<div class="page-content container-fluid">
	@include('voyager::alerts')

	<div class="row">
		@if($records->count())
			<div class="col-md-12" style="margin-bottom: 0px">
				<div class="alert alert-danger" role="alert">
					Following records are skipped due to errors. Please correct it and upload again.
				</div>
			</div>
		@endif

		<div class="col-md-12">
			<div class="panel panel-bordered">
				<div class="panel-body">
					<div class="table-responsive">
						<table id="dataTable" class="table table-hover fixed_headerss">
							<thead>
								<tr>
									<th>Business Name</th>
									<th>Sector Name</th>
									<th>Unique Identification Number</th>
									<th>Concerned Person Name</th>
									<th>Concerned Person Designation</th>
									<th>Concerned Person Phone</th>
									<th>Concerned Person Alternate Phone</th>
									<th>State</th>
									<th>City</th>
									<th>Pin Code</th>
									<th>Address</th>
									<th>DueDate (DD/MM/YYYY)</th>
									<th>DueAmount</th>	
									<th>Email</th>
									<th>Grace Period</th>
									<th>Business Type</th>
									<th>Reason to skip</th>
								</tr>
							</thead>
							<tbody>
								@forelse($records as $data)
    								<tr>
    									<td>{{$data->company_name}}</td>
    									<td>{{$data->sector_name}}</td>
    									<td>{{$data->unique_identification_number}}</td>
    									<td>{{$data->concerned_person_name}}</td>
    									<td>{{$data->concerned_person_designation}}</td>
    									<td>{{$data->concerned_person_phone}}</td>
    									<td>{{$data->concerned_person_alternate_phone}}</td>
    									<td>{{$data->state}}</td>
    									<td>{{$data->city}}</td>
    									<td>{{$data->pincode}}</td>
    									<td>{{$data->address}}</td>
    									<td>{{$data->due_date}}</td>
    									<td>{{$data->due_amount}}</td>
    									<td>{{$data->email}}</td>
    									<td>{{$data->grace_period}}</td>
    									<td>{{$data->business_type}}</td>
    									<td>{!! $data->issue !!} </td>
    								</tr>
    							@empty
	    							<tr><td colspan="20" align="center">No Issue Found</td></tr>
    							@endforelse
							</tbody>
						</table>
    				</div>
    			</div>
    		</div>
    	</div>
    </div>
</div>
@endsection