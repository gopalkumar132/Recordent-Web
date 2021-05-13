@extends('layouts_front_ib.master')
@section('content')
@php
  if(session()->get('individual_client_udise_gstn')){
    $url = config('app.url').'business/';
  }else{
    $url = config('app.url').'individual/';
  }
@endphp
<!-- BEGIN CONTENT -->
<div class="container-fluid" data-select2-id="13">
      <div class="side-body padding-top" data-select2-id="12">
        <!--<div class="container-fluid padding-20">
          <h1 class="page-title"> <i class="voyager-person"></i> Dashboard </h1>
        </div>-->
        <div id="voyager-notifications"></div>
        <div class="page-content browse container-fluid" data-select2-id="11">
          <div class="alerts"> </div>
          <div class="col-xs-12 col-sm-12 col-md-12">
            <div class="" style="text-align:center">
              <h2><span class="main-title" style="font-weight:bold;padding:6px;padding-left:10px;padding-right:10px;color:#5f94c4; font-family:var(--font-rubik);"><strong>Welcome </strong>{{$individual[0]['person_name']}}</span></h2>
            
            </div>
          </div>
          
        </div>
        <div class="container-fluid custom-dimmers d-flex flex-wrap">
          <div class="dimmers-boxes">
            <div class="panel widget center bgimage" style="margin-bottom:0;overflow:hidden;background-image:url('{{config('app.url')}}payment.jpg');">
                <div class="dimmer"></div>
                <div class="panel-content">
                    <i class="voyager-dollar"></i>
                      <h4 style="font-size:17px;"> 
                        <div class="total-amount-due">
                          <span class="someeeee">Total Dues Submitted</span>
                        </div>  
                        <div class="total-amount-due">
                          <span class="someeeee">INR.{{$TotalDue}} </span>
                        </div>
                      </h4>
                      <p></p>
                </div>
            </div>
            <div>
              <p> &nbsp;</p>
            </div>
          </div>

          <div class="dimmers-boxes ">
            <div class="panel widget center bgimage" style="margin-bottom:0;overflow:hidden;background-image:url('{{config('app.url')}}ladpers_business.jpg');">
                <div class="dimmer"></div>
                <div class="panel-content">
                    <i class="voyager-file-text"></i>
                    <h4 style="font-size:17px;">
                      <div class="total-amount-due">
                        <span class="someeeee">No of Businesses Submitted Your Dues</span>
                      </div>
                      <div class="total-amount-due">
                        <span class="someeeee">{{$numberOfBusinessReported}}</span>
                      </div>
                    </h4>
                    <p></p>
                </div>
            </div>

            <div>
              <p> &nbsp;</p>
            </div>
          </div>
        </div>

  
        {{--My Records --}}
        <div class="page-content browse container-fluid" data-select2-id="11" style="display: none;">

          <!--<div class="alerts"> </div>-->
          <div class="row" data-select2-id="10">
            <div class="col-md-12" data-select2-id="9">
              <div class="panel panel-bordered" data-select2-id="8">
                <div class = "panel-heading">
                  <h3 class = "panel-title">
                     My Reports
                  </h3>
                </div>  
                <div class="panel-body" data-select2-id="7">

                  @if($message == "No Records")
                    <h3 class="text-center">No Records</h3>
                  @else
                    @php
                      $count =0;
                    @endphp
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
                                @php
                                if($count>=20){
                                  continue;
                                }
                                  
                                 $count++;
                                @endphp                 
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
                            @if($count>=20)
                              <tr><td colspan="10" align="center"><a href="{{$url.'records'}}"><button type="button" class="btn btn-primary" aria-controls="dataTable">View All Reports</button></a></td></tr>
                            @endif
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