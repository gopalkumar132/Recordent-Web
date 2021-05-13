@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' Disputes')

@section('page_header') 
    <h1 class="page-title">
        <i class="voyager-list"></i> Disputes
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
                        <div id="dataTable_filter" class="dataTables_filter">
                            <form action="{{route('admin.dispute-list')}}" method="get">
                                <div class="row">
                                	<div class="col-md-12">
                                        <div class="col-md-2">
                                            <label> Dispute Status:</label>
                                            <select name="is_open" class="form-control">
                                                <option value="1">OPEN</option>
                                                <option value="2" {{app('request')->input('is_open')==2 ? 'selected' : ''}}>CLOSED</option>
                                                <option value="3" {{app('request')->input('is_open')==3 ? 'selected' : ''}}>ALL</option> 
                                            </select>
                                            
                                        </div>
                                        <div class="col-md-2">
                                            <label> From Date:</label>
                                            <input type="date" name="from_date" class="form-control input-sm" value="{{!empty(app('request')->input('from_date')) ? app('request')->input('from_date') : '' }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label> To Date:</label>
                                            <input type="date" name="to_date" class="form-control input-sm" value="{{!empty(app('request')->input('to_date')) ? app('request')->input('to_date') : '' }}">
                                        </div>
                                        <div class="col-md-6 text-right text-md-right mt_form">
                                            <button type="submit" class="btn btn-primary"  aria-controls="dataTable">Search</button>
                                            <a href="{{route('admin.dispute-list')}}" class="btn btn-primary"  aria-controls="dataTable">Reset</a>
                                        </div>
                                    </div>
                                </div>
                           </form>
                        </div>
                    </div>
                </div>  
            <!-- <div class="pull-right">
              <form action="{{route('export-dispute')}}">
                 <button class="btn btn-info download-mem-data btn-blue">Dispute Records <i class="voyager-download"></i>
                </button>
                </form> 
           </div> -->
			</div>
            <div class="col-md-12">
            <div class="disputeCls">
              <form action="{{route('export-dispute')}}">
                 <button class="btn btn-info download-mem-data btn-blue">Dispute Records <i class="voyager-download"></i>
                </button>
                </form> 
           </div>
           </div>
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-hover fixed_headerss">
                                <thead>
                                <tr>
                                    <th>Dispute Id</th>
                                    <th>Customer/Business Name</th>
                                    <th>Contact Number</th>
                                    @if(Auth::user()->role_id == 1)
                                    <th>Member Name</th>
                                    @endif
                                    <th>Dispute Date</th>
                                    <th>Dispute Status</th>
                                    <th>Overdue Status</th>
                                    <th class="actions">{{ __('voyager::generic.actions') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @forelse($records as $data)

                                        
                                        @php 
                                            if($data->customer_type=='INDIVIDUAL'){
                                                $dueType='individualDue';
                                            }else{
                                                $dueType='businessDue';
                                            }
                                        @endphp
                                        @if(isset($data->$dueType->due_date) && isset($data->dueAddedBy->id))
                                            <tr>
                                                <td>{{$data->id}}</td>
                                                @if($data->customer_type=='INDIVIDUAL')
                                                    <td>{{$data->individualProfile->person_name}}</td>
                                                    <td>{{$data->individualProfile->contact_phone}}</td>
                                                @else
                                                    <td>{{$data->businessProfile->company_name}}</td>
                                                     <td>{{$data->businessProfile->concerned_person_phone}}</td>
                                                @endif
                                                @if(Auth::user()->role_id == 1)
                                                <td>{{$data->dueAddedBy->business_name}}</td>
                                                @endif    
                                                <td>{{date('d/m/Y', strtotime($data->created_at))}}</td>
                                                <td>{{$data->is_open==1 ? 'Open' : 'Closed'}}</td>
                                                @php
                                                    $now = \Carbon\Carbon::now();
                                                    $diffDays = General::diffInDays($data->$dueType->due_date);
                                                @endphp
                                                <td> 
                                                    @if($diffDays>=180 )
                                                        180+ days overdue             
                                                    @else
                                                        {{$diffDays}} days overdue
                                                    @endif
                                                </td>
                                                 
                                                <td>
                                                    <a href="{{route('admin.dispute-view',$data->id)}}{{(Request::getQueryString() ? ('?' . Request::getQueryString()) : '')}}" class="btn btn-sm btn-warning view" title="Detail View">
                                                        <i class="voyager-eye"></i>
                                                    </a>
                                                    
                                                </td>
                                            </tr>
                                        @endif
                                    @empty
                                        <tr><td colspan="10" align="center">No Record Found</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="pull-right">
                            {{$records->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>

.disputeCls {
    float: right;
}

@media only screen and (max-width: 600px) {
    .disputeCls {
    margin-left: 44px !important;
    float: none;
    }
}
      
    </style>
@endsection