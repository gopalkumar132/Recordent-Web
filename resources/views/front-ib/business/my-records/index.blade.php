@extends('layouts_front_ib.master')
@section('content') 
<!-- BEGIN CONTENT -->
<div class="container-fluid" data-select2-id="13">
      <div class="side-body padding-top" data-select2-id="12">
        <div id="voyager-notifications"></div>
        <div class="page-content browse container-fluid" data-select2-id="11" style="display: none;">
          <div class="alerts"> </div>
          <div class="row" data-select2-id="10">
            <div class="col-md-12" data-select2-id="9">
              <div class="panel panel-bordered" data-select2-id="8">
                <div class="panel-body" data-select2-id="7">
                  @if($message == "No Records")
                  	<h3 class="text-center">No Records</h3>
                  @else
                  <div class="table-responsive">
                    <table id="dataTable" class="table table-hover fixed_headerss">
                      	<thead>
                        	<tr>
								<th>Company Name</th>                               
								<th>Business Type</th>
								<th>Person's Name</th> 
								<th>Designation</th>
								<th>Contact Phone</th>
								<th>Address</th>
								<th>Total Amount Due</th>
								<th>Number Of Times Payment Was Late</th>
								<th>Reported By</th> 
								<th class="actions">Actions</th>
                        	</tr>
                      	</thead>
                      	<tbody>
							@forelse($sRecords as $records)
								@foreach($records as $data)									
								<tr>
									<td>{{$data->company_name}}</td>
									@php $Sector = General::getSector($data->sector_id); @endphp
									<td>{{$Sector->name}} 
									@php $uidt = General::getUniqueIdentificationTypeofSector($Sector->unique_identification_type); @endphp
									({{$uidt}})</td>
									<td>{{$data->concerned_person_name}}</td>
									<td>{{$data->concerned_person_designation}}</td>
									<td>{{$data->concerned_person_phone}}</td>
									<td>{{$data->address}}<br>
										{{General::getCityNameById($data->city_id)}}, {{General::getStateNameById($data->state_id)}}
									</td>
									<td>{{General::ind_money_format(General::getTotalDueForBusiness($data->id) - General::getTotalPaidForBusiness($data->id)) }}</td>
									<td>{{General::ind_money_format(General::getNumberOfDuesOfBusiness($data->id))}}</td>
									<td>{{General::getUserBusinessName($data->addedBy)}}</td>
									<td class="no-sort no-click bread-actions">
										<a href="{{ route('front-business.my-records-view', $data->{$data->getKeyName()}) }}" class="btn btn-sm btn-warning view"><i class="voyager-eye"></i></a>
									</td>
								</tr>
								@endforeach
							@empty
							<tr><td colspan="10" align="center">No Record Found</td></tr>
							@endforelse
                        
                      </tbody>
                    </table>
                  </div>
                  @endif 
                  
                </div>
              </div>
            </div>
          </div>
        </div>
        
        @if($message != "No Records")
            {!! $htmlReport !!}
        @endif

      </div>
    </div>
<!-- END CONTAINER --> 
 

@endsection