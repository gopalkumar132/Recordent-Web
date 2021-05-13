@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' Business Records')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-list"></i> {{-- $records->display_name_plural --}}Records of {{$businessName}}
    </h1>

     &nbsp&nbsp<a href="{{config('app.url')}}admin/users/{{$userId}}" class="btn btn-primary" title="View Profile"><i class="fa fa-eye" aria-hidden="true"></i> View Profile</a>
     
    <ul class="name_title">
        	
            <li>                
                <a href="#" class="btn btn-sm btn-primary view"><i class="voyager-eye"></i> = View</a> 
            </li>
        </ul>
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

                        <div id="dataTable_filter" class="dataTables_filter">
                            <form action="{{route('business-records-for-admin',['userId'=>$userId])}}" method="get">
                            <input type="hidden" value="{{$userId}}" name="userId">
                            <div class="row">
                            	<div class="col-md-12">
                                 <div class="row new_width"> 
                                    <div class="col-md-2">
                                        <label>{{General::getLabelName('unique_identification_number')}}: </label>
                                        <input type="text" name="unique_identification_number" class="form-control " aria-controls="dataTable" value="{{!empty(app('request')->input('unique_identification_number')) ? app('request')->input('unique_identification_number') : '' }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label> Concerned Person Name:</label>
                                        <input type="text" name="concerned_person_name" class="form-control " placeholder="" aria-controls="dataTable" value="{{!empty(app('request')->input('concerned_person_name')) ? app('request')->input('concerned_person_name') : '' }}">
                                    </div> 
                                     <div class="col-md-2">
                                        <label>Concerned Person Phone:</label>
                                        <input type="text" name="concerned_person_phone" class="form-control " placeholder="" aria-controls="dataTable" value="{{!empty(app('request')->input('concerned_person_phone')) ? app('request')->input('concerned_person_phone') : '' }}">
                                    </div>  
                                    <div class="col-md-2">
                                        <label>Company Name:</label>
                                        <input type="text" name="company_name"class="form-control " placeholder="" aria-controls="dataTable" value="{{!empty(app('request')->input('company_name')) ? app('request')->input('company_name') : '' }}">
                                    </div> 
                                    <div class="col-md-2">
                                        <label>Sector:</label>
                                        <select name="sector_id"class="form-control " placeholder="" aria-controls="dataTable">
                                            <option value="">All</option>    
                                            @foreach($sectors as $sector)
                                                <option value="{{$sector->id}}" {{!empty(app('request')->input('sector_id') && app('request')->input('sector_id')==$sector->id) ? 'selected' : '' }}>{{$sector->name}}</option>
                                            @endforeach
                                        </select>
                                    </div> 
                                    <div class="col-md-2">
                                        <label>Due Amount (in INR):</label>
                                        <select name="due_amount"class="form-control " placeholder="" aria-controls="dataTable">
                                            <option></option>    
                                            <option value="less than 1000" {{app('request')->input('due_amount')=='less than 1000' ? 'selected' : '' }}>less than 1000</option>
                                            <option value="1000 to 5000" {{app('request')->input('due_amount')=='1000 to 5000' ? 'selected' : '' }}>1000 to 5000</option>
                                            <option value="5001 to 10000" {{app('request')->input('due_amount')=='5001 to 10000' ? 'selected' : '' }}>5001 to 10000</option>
                                            <option value="10001 to 25000" {{app('request')->input('due_amount')=='10001 to 25000' ? 'selected' : '' }}>10001 to 25000</option>
                                            <option value="25001 to 50000" {{app('request')->input('due_amount')=='25001 to 50000' ? 'selected' : '' }}>25001 to 50000</option>
                                            <option value="more than 50000" {{app('request')->input('due_amount')=='more than 50000' ? 'selected' : '' }}>more than 50000</option>
                                        </select>
                                    </div>
                                    <div class="col-md-2">
                                        <label>Due Date Period:</label>
                                        <select name="due_date_period" class="form-control " placeholder="" aria-controls="dataTable">
                                            <option></option>    
                                            <option value="less than 30days" {{app('request')->input('due_date_period')=='less than 30days' ? 'selected' : '' }}>less than 30days</option>
                                            <option value="30days to 90days" {{app('request')->input('due_date_period')=='30days to 90days' ? 'selected' : '' }}>30days to 90days</option>
                                            <option value="91days to 180days" {{app('request')->input('due_date_period')=='91days to 180days' ? 'selected' : '' }}>91days to 180days</option>
                                            <option value="181days to 1year" {{app('request')->input('due_date_period')=='181days to 1year' ? 'selected' : '' }}>181days to 1year</option>
                                            <option value="more than 1year" {{app('request')->input('due_date_period')=='more than 1year' ? 'selected' : '' }}>more than 1year</option>
                                            
                                        </select>
                                    </div>  
                                    <div class="col-md-2">
                                        <label class="control-label">State:</label>
                                        <select class="form-control" name="state_id" id="state">
                                            <option value="">ALL</option>
                                             @if($states->count())  
                                            @foreach($states as $state)
                                                <option value="{{$state->id}}" {{app('request')->input('state_id')==$state->id ? 'selected' : '' }}>{{$state->name}}</option>
                                            @endforeach  
                                        @endif
                                        </select>
                                    </div>   
                                    <div class="col-md-2">
                                        <label>City:</label>
                                        <select class="form-control" name="city_id" id="city">
                                            <option value="">ALL</option>
                                        
                                        </select>
                                    </div> 
                                    <div class="col-md-8 text-right text-md-right mt_form">
                                        <button type="submit" class="btn btn-primary"  aria-controls="dataTable">Search</button>
                                        <a href="{{route('business-records-for-admin')}}" class="btn btn-primary"  aria-controls="dataTable">Reset</a>
                               </div>
                                 </div>
                                </div>
                                
                               
                            </div>
                           </form>
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

            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-hover fixed_headerss">
                                <thead>
                                <tr>
                                    <th>Company</th>
                                    <th>{{General::getLabelName('unique_identification_number')}}</th>
                                    <th>Business Type</th>
                                    <th>Concerned Person</th>
                                    <th>Concerned Person Phone</th>
                                    <th>Location</th>
                                    <th>Total Amount Due</th>
                                    <th class="actions">{{ __('voyager::generic.actions') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @forelse($records as $data)
                                        @php
                                            $sector = General::getSector($data->sector_id);
                                        @endphp
                                    <tr>
                                        <td>{{$data->company_name}}</td>
                                        <td>{{$data->unique_identification_number}}</td>
                                        <td>{{ $sector ? $sector->name : ''}}</td>
                                        <td>{{$data->concerned_person_name}}</td>
                                        <td>{{$data->concerned_person_phone}}</td>
                                        <td>{{General::getCityNameById($data->city_id)}},{{General::getStateNameById($data->state_id)}}</td>
                                        
                                         <td>{{General::ind_money_format(General::getTotalDueForBusinessByCustomId($data->id,$userId,$data->dueid,$data->external_business_id))}}</td>
                                        <td class="no-sort no-click bread-actions">
                                                <a href="{{ route('business-records-view-for-admin',[$data->id,$userId,$data->dueid]) }}" class="btn btn-sm btn-warning view" title="view Record">
                                                    <i class="voyager-eye"></i>
                                                </a>
                                        </td>
                                    </tr>
                                    @empty
                                        <tr><td colspan="10" align="center">No Record Found</td></tr>
                                       
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   

<script language="javascript" type="application/javascript">
    {{--$(document).ready(function(){
        var newOption = new Option('RAJKOT', 23, false, false);
        // Append it to the select
        $('#city_id').append(newOption).trigger('change');
        alert($('#city_id').val());
        $('#city_id').select2("destroy");
        $("#city_id").html('')
       $('#city_id').on('change',function(){
            alert($(this).val());
        });

        //clear selection
//        $('#city_id').val(null).trigger('change');
    });
  
 --}}

    $(document).ready(function(){
        if($("#state").val()!=''){ 
            @if(!empty(app('request')->input('state_id')))        
            var oldCity = "{{app('request')->input('city_id')}}";    
            var selected = '';
            $("#city").find('option').remove();
            $("#city").append('<option value="">ALL</option>');
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
            $("#city").append('<option value="">ALL</option>');

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