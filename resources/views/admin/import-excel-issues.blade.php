@extends('voyager::master')
 

@section('page_header')
    <!-- <h1 class="page-title">
        <i class="voyager-upload"></i> Upload Master File
    </h1> -->
    <?php if($userId!=""){ ?>  
    
    <div class="pull-right" style="padding-top: 10px;padding-bottom: 10px;"><a href="{{route('super-excel',$userId)}}" class="btn btn-info">Import Excel Data <i class="voyager-document"></i></a></div>

   <?php } else{ ?>
	<div class="pull-right" style="padding-top: 10px;padding-bottom: 10px;"><a href="{{route('import')}}" class="btn btn-info">Import Excel Data <i class="voyager-document"></i></a></div>
   <?php } ?>
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
									<th>Aadhar Number</th>
									<th>Contact Phone</th>
									<th>Person Name</th>
									<th>DOB (DD/MM/YYYY)</th>
									<th>Father Name</th>
									<th>Mother Name</th>
									<th>DueDate (DD/MM/YYYY)</th>
									<th>DueAmount</th>
									<th>DueNote</th>
									<th>Email</th>
									<th>Grace Period</th>
									<th>Reason to skip</th>
								</tr>
							</thead>
							<tbody>
								@forelse($records as $data)
    								<tr>
    									<td>{{$data->aadhar_number}}</td>
    									<td>{{$data->contact_phone}}</td>
    									<td>{{$data->person_name}}</td>
    									<td>{{$data->dob}}</td>
    									<td>{{$data->father_name}}</td>
    									<td>{{$data->mother_name}}</td>
    									<td>{{$data->due_date}}</td>
    									<td>{{$data->due_amount}}</td>
    									<td><div class="wrap-table-text">{{$data->due_note}}</div></td>
    									<td>{{$data->email}}</td>
    									<td>{{$data->grace_period}}</td>
    									<td>{!! $data->issue !!} </td>
    								</tr>
    							@empty
	    							<tr><td colspan="10" align="center">No Issue Found</td></tr>
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