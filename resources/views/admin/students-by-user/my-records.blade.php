@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' All Records')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-list"></i> {{-- $records->display_name_plural --}}Records Of {{$businessName}}
    </h1>

    &nbsp&nbsp<a href="{{config('app.url')}}admin/users/{{$userId}}" class="btn btn-primary" title="View Profile"><i class="fa fa-eye" aria-hidden="true"></i> View Profile</a>

    <ul class="name_title">
        	<li>        		
        		<a href="#" class="btn btn-sm btn-primary view"><i class="voyager-eye"></i> = View</a> 
        		 
        		
        	</li> 
        </ul>
@stop
@section('content')
 
    <div class="page-content container-fluid">
        @include('voyager::alerts')
        
        <div class="row">

            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">

                        <div id="dataTable_filter" class="dataTables_filter">
                            <form action="{{route('user-records')}}" method="get">
                            <div class="row">
                            	<div class="col-md-12">
                                 <div class="row new_width"> 
                                   <input type="hidden" name="userId" value="{{$userId}}">
                                    <div class="col-md-2">
                                        <label>Aadhar Number: </label>
                                        <input type="text" name="aadhar_number"class="form-control input-sm" placeholder="1111-2222-3333" data-mask="9999-9999-9999" aria-controls="dataTable" value="{{!empty(app('request')->input('aadhar_number')) ? app('request')->input('aadhar_number') : '' }}">
                                    </div>
                                    <div class="col-md-2">
                                        <label> Person's Name:</label>
                                        <input type="text" name="student_first_name"class="form-control input-sm" placeholder="" aria-controls="dataTable" value="{{!empty(app('request')->input('student_first_name')) ? app('request')->input('student_first_name') : '' }}">
                                    </div> 
                                     <div class="col-md-2">
                                        <label>Contact Phone:</label>
                                        <input type="text" name="contact_phone"class="form-control input-sm" placeholder="" aria-controls="dataTable" value="{{!empty(app('request')->input('contact_phone')) ? app('request')->input('contact_phone') : '' }}">
                                    </div>  
                                    <div class="col-md-2">
                                        <label>Due Amount (in INR):</label>
                                        <select name="due_amount"class="form-control input-sm" placeholder="" aria-controls="dataTable">
                                            <option value="">All</option>    
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
                                        <select name="due_date_period" class="form-control input-sm" placeholder="" aria-controls="dataTable">
                                            <option value="">All</option>    
                                            <option value="less than 30days" {{app('request')->input('due_date_period')=='less than 30days' ? 'selected' : '' }}>less than 30days</option>
                                            <option value="30days to 90days" {{app('request')->input('due_date_period')=='30days to 90days' ? 'selected' : '' }}>30days to 90days</option>
                                            <option value="91days to 180days" {{app('request')->input('due_date_period')=='91days to 180days' ? 'selected' : '' }}>91days to 180days</option>
                                            <option value="181days to 1year" {{app('request')->input('due_date_period')=='181days to 1year' ? 'selected' : '' }}>181days to 1year</option>
                                            <option value="more than 1year" {{app('request')->input('due_date_period')=='more than 1year' ? 'selected' : '' }}>more than 1year</option>
                                            
                                        </select>
                                    </div>   
                                    {{--<div class="col-md-2">
                                        <label> Last Name:</label>
                                        <input type="text" name="student_last_name"class="form-control input-sm" placeholder="" aria-controls="dataTable" value="{{!empty(app('request')->input('student_last_name')) ? app('request')->input('student_last_name') : '' }}">
                                    </div> --}}

                                     <div class="col-md-2">
                                        <label> DOB:</label>
                                        <input type="date" name="student_dob" class="form-control input-sm" placeholder="" aria-controls="dataTable" value="{{!empty(app('request')->input('student_dob')) ? app('request')->input('student_dob') : '' }}">
                                    </div>
                                     <div class="col-md-2">
                                        <label>Father Name:</label>
                                        <input type="text" name="father_first_name"class="form-control input-sm" placeholder="" aria-controls="dataTable" value="{{!empty(app('request')->input('father_first_name')) ? app('request')->input('father_first_name') : '' }}">
                                    </div>
                                </div>
                                </div>
                                <div class="col-md-6 text-left text-md-right mt_form" style="vertical-align: bottom">
                                    <div class="row new_width">
                                        
                                        <div class="col-md-4">
                                            <label>Mother Name:</label>
                                            <input type="text" name="mother_first_name"class="form-control input-sm" placeholder="" aria-controls="dataTable" value="{{!empty(app('request')->input('mother_first_name')) ? app('request')->input('mother_first_name') : '' }}">
                                        </div>
                                    </div>
                                </div>
                               <div class="col-md-6 text-right text-md-right mt_form">
                                        <button type="submit" class="btn btn-primary"  aria-controls="dataTable">Search</button>
                                        <a href="{{route('user-records')}}?userId={{app('request')->input('userId')}}" class="btn btn-primary"  aria-controls="dataTable">Reset</a>
                               </div>

                                   
                            
                            </div>
                           </form>
                        </div>
                    </div>
                </div>    


            </div>

            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-hover fixed_headerss">
                            <thead>
                            <tr>
                                <th>Person's Name</th>
                                <th>Contact Phone</th>
                                <th>Total Amount Due</th>
                                <th class="actions">{{ __('voyager::generic.actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                                @forelse($records as $data)
                                <tr>
                                    <td>{{$data->person_name}}</td>
                                    <td>{{$data->contact_phone}}</td>
                                    <td>{{General::ind_money_format(General::getTotalDueForStudentByCustomId($data->id,$userId,$data->dueid,$data->external_student_id))}}</td>
                                    
                                    
                                    
                                    
                                   {{-- <td>{{General::ind_money_format(General::getNumberOfDues($data->id,$userId))}}</td>--}}
                                    <td class="no-sort no-click bread-actions">
                                       {{-- @can('delete', $data)
                                            <div class="btn btn-sm btn-danger pull-right delete" data-id="{{ $data->{$data->getKeyName()} }}">
                                                <i class="voyager-trash"></i> 
                                            </div>
                                        @endcan --}}
                                        
                                            <a href="{{ route('user-records-view', [$data->{$data->getKeyName()},$userId,$data->dueid]) }}" class="btn btn-sm btn-warning view">
                                                <i class="voyager-eye"></i> 
                                            </a>
                                        
                                    </td>
                                </tr>
                                @empty
                                    <tr><td colspan="10" align="center">No Record Found</td></tr>
                                    {{--@if(Auth::user()->hasRole('admin'))
                                    <tr><td colspan="10" align="center"><a href="{{route('voyager.users.index')}}"><button type="button" class="btn btn-primary" aria-controls="dataTable">Reporting Organization</button></a></td></tr>
                                    @endif --}}
                                @endforelse
                            </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('voyager::generic.close') }}">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">
                        <i class="voyager-trash"></i> {{ __('voyager::generic.delete_question') }} Student?
                    </h4>
                </div>
                <div class="modal-footer">
                    <form action="#" id="delete_form" method="POST">
                        {{ method_field("DELETE") }}
                        {{ csrf_field() }}
                        <input type="submit" class="btn btn-danger pull-right delete-confirm" value="{{ __('voyager::generic.delete_this_confirm') }} {Student">
                    </form>
                    <button type="button" class="btn btn-default pull-right" data-dismiss="modal">{{ __('voyager::generic.cancel') }}</button>
                </div>
            </div>
        </div>
    </div>

@endsection