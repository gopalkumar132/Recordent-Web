@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' Sent SMS')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-list"></i> {{-- $records->display_name_plural --}}Sent SMS
        <div id="sendSms" class="pull-right">
            <a href="{{route('business.my-records-for-sms')}}" class="btn btn-success">
                <i class="voyager-plus"></i> Send New SMS
            </a>
        </div>
    </h1>
   
    
@stop

@section('content')
 
    <div class="page-content container-fluid">
        @include('voyager::alerts')
        
        <div class="row">

            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="table-responsive">
                         <table id="dataTable" class="table table-hover fixed_headerss">
                            <thead>
                            <tr>
                                <th>Company Name</th>
                                <th>Message</th>
                                <th>Status</th>
                                <th>Sent at</th>
                                
                            </tr>
                            </thead>
                            <tbody>
                                @forelse($records as $data)
                                    
                                <tr>
                                    <td>{{$data->business->company_name}}</td>
                                    <td><div class="wrap-table-text">{{$data->message}}</div></td>
                                    <td>
                                        @if($data->approve_reject_status == 0) 
                                            Pending For Approval
                                        @elseif($data->approve_reject_status==1)
                                            Approved 
                                        @else
                                            Rejected 
                                        @endif
                                    </td>
                                    <td>{{date('F d, Y H:i',strtotime($data->created_at))}}</td>
                                   
                                </tr>
                                @empty
                                    <tr><td colspan="10" align="center">No Record Found</td></tr>
                                    
                                @endforelse
                            </tbody>
                        </table>
                        </div>
                    @if($records->count())
                        <div class="pull-left">
                            <div role="status" class="show-res" aria-live="polite">Showing
                                {{($records->perPage() * $records->currentPage()) - ($records->perPage() - 1)}} to
        
                                {{(($records->perPage() * $records->currentPage()) - ($records->perPage() - 1)) + ($records->count() - 1)}}</strong> of <strong>{{$records->total()}} </strong> entries
                                
                            </div>
                        </div>
                        <div class="pull-right">
                            {{$records->links()}}
                        </div> 
                    @endif

                    </div>
                    
                </div>
            </div>
                
          

        </div>
    </div>


@endsection