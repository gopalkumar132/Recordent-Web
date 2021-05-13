@extends('voyager::master')


@section('page_header')
    <!-- <h1 class="page-title">
        <i class="voyager-upload"></i> Upload Master File
    </h1> -->
    
	<div class="pull-right" style="padding-top: 10px;padding-bottom: 10px;"><a href="{{route('import-business')}}" class="btn btn-info">Import Excel Data <i class="voyager-document"></i></a></div>
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
									<th>Invoice No</th>
									<th>Payment Date (DD/MM/YYYY)</th>
									<th>Payment Amount</th>
									<th>Payment Note</th>
									<th>Reason to skip</th>
								</tr>
							</thead>
							<tbody>
								@forelse($records as $data)
    								<tr>
    									<td>{{$data->invoice_no}}</td>
    									<td>{{$data->payment_date}}</td>
    									<td>{{$data->payment_amount}}</td>
    									<td><div class="wrap-table-text">{{$data->due_note}}</div></td>
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