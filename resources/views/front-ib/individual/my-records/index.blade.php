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
            								<th>Person's Name</th>                                
            								<th>DOB</th>
            								<th>Father Name</th>
            								<th>Mother Name</th>
            								<th>Aadhar Number</th>
            								<th>Contact Phone</th>
            								<th>Total Amount Due</th>
            								<th>Number Of Times Payment Was Late</th>
            								<th>Added By</th> 
            								<th class="actions">Actions</th>
                        	</tr>
                      	</thead>
                      	<tbody>
							@forelse($sRecords as $records)
								@foreach($records as $data)									
								<tr>
									<td>{{$data->person_name}}</td>
									@if(empty($data->dob) || $data->dob == '0000-00-00')
									<td>N/A</td>
									@else
									<td>{{date('d/m/Y',strtotime($data->dob))}}</td>
									@endif
									<td>{{$data->father_name}}</td>
									<td>{{$data->mother_name}}</td>
									<td>{{$data->aadhar_number}}</td>
									<td>{{$data->contact_phone}}</td>
									<td>{{General::ind_money_format(General::getTotalDueForStudent($data->id) - General::getTotalPaidForStudent($data->id)) }}</td>
									<td>{{General::ind_money_format(General::getNumberOfDues($data->id))}}</td>
									<td>{{General::getUserBusinessName($data->addedBy)}}</td>
									<td class="no-sort no-click bread-actions">
										<a href="{{ route('front-individual.my-records-view', $data->{$data->getKeyName()}) }}" class="btn btn-sm btn-warning view"><i class="voyager-eye"></i></a>
									</td>
								</tr>
								@endforeach
							@empty
							<tr><td colspan="10" align="center">No Record Found</td></tr>
							@endforelse
                        <!--<tr>
                          <td><div>V. Nagasubbareddy</div></td>
                          <td><div>Sribhashyamforyou@gmail.com</div></td>
                          <td> 2020-01-02 18:00:44 </td>
                          <td><p>School Manager</p></td>
                          <td><div>SRI BHASHYAM PUBLIC SCHOOL</div></td>
                          <td> ACTIVE </td>
                          <td class="no-sort no-click" id="">
                            <a href="javascript:;" title="Delete" class="btn btn-sm btn-danger pull-right delete" data-id="" id="">
                              <i class="voyager-trash"></i>
                              <span class="hidden-xs hidden-sm">Delete</span>
                            </a>
                            <a href="javascript:void(0)" title="Edit" class="btn btn-sm btn-primary pull-right edit">
                              <i class="voyager-edit"></i>
                              <span class="hidden-xs hidden-sm">Edit</span>
                            </a>
                            <a href="javascript:void(0)" title="View" class="btn btn-sm btn-warning view">
                              <i class="voyager-eye"></i>
                              <span class="hidden-xs hidden-sm">View</span>
                            </a>
                            <br>
                            <a href="javascript:void(0)" class="btn btn-sm btn-primary pull-right margin-right-5" title="View Records">
                              <i class="voyager-eye"></i>
                              <span class="hidden-xs hidden-sm">Records 0</span>
                            </a>
                          </td>
                        </tr>-->
                      </tbody>
                    </table>
                  </div>
                  @endif 
                  <!--<div class="pull-left">
                    <div role="status" class="show-res" aria-live="polite">Showing 1 to 15 of 60
                      entries</div>
                  </div>
                  <div class="pull-right">
                    <ul class="pagination" role="navigation">
                      <li class="page-item disabled" aria-disabled="true" aria-label="« Previous"> <span class="page-link" aria-hidden="true">‹</span> </li>
                      <li class="page-item active" aria-current="page"><span class="page-link">1</span></li>
                      <li class="page-item"><a class="page-link" href="javascript:void(0)">2</a> </li>
                      <li class="page-item"><a class="page-link" href="javascript:void(0)">3</a> </li>
                      <li class="page-item"><a class="page-link" href="javascript:void(0)">4</a> </li>
                      <li class="page-item"> <a class="page-link" href="javascript:void(0)" rel="next" aria-label="Next »">›</a> </li>
                    </ul>
                  </div>-->
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