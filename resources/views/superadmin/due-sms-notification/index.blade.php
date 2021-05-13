@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' SMS Notification')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-list"></i>SMS Notification
    </h1>
@stop

@section('content')
 
    <div class="page-content container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div id="dataTable_filter" class="dataTables_filter">
                            <form action="{{route('superadmin.due-sms-list')}}" method="get">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-2">
                                            <label> From Date</label>
                                            <input type="date" name="from_date" class="form-control input-sm" value="{{!empty(app('request')->input('from_date')) ? app('request')->input('from_date') : '' }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label> To Date</label>
                                            <input type="date" name="to_date" class="form-control input-sm" value="{{!empty(app('request')->input('to_date')) ? app('request')->input('to_date') : '' }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label> Customer Type</label>
                                            <select name="customer_type" class="form-control input-sm">
                                                <option value="">ALL</option>
                                                <option value="INDIVIDUAL" {{app('request')->input('customer_type')=='INDIVIDUAL' ? 'selected' : ''}}>Individual</option>
                                                <option value="BUSINESS" {{app('request')->input('customer_type')=='BUSINESS' ? 'selected' : ''}}>Business</option>
                                            </select>
                                        </div>
                                        <div class="col-md-2">
                                            <label> SMS Status</label>
                                            <select name="sms_status" class="form-control input-sm">
                                                <option value="" {{!app('request')->input('sms_status') ? 'selected' : ''}}>Approval Pending</option>
                                                <option value="1" {{app('request')->input('sms_status')=='1' ? 'selected' : ''}}>Approved</option>
                                                <option value="2" {{app('request')->input('sms_status')=='2' ? 'selected' : ''}}>Rejected</option>
                                            </select>
                                        </div>

                                        <div class="col-md-2 text-right text-md-right mt_form pull-right">
                                            <button type="submit" class="btn btn-primary"  aria-controls="dataTable">Search</button>
                                            <a href="{{route('superadmin.due-sms-list')}}" class="btn btn-primary"  aria-controls="dataTable">Reset</a>
                                        </div>
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
                        <div class="pull-right"><h4>Total SMS Notification- {{$records->total()}}</h4></div>
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-hover fixed_headerss">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" class="select_all">
                                        </th>
                                        <th>Person Name</th>
                                        <th>Business Name</th>
                                        <th>Customer Mobile</th>
                                        <th>Member Name</th>
                                        <th>Mobile Number </th>
                                        <th>Customer Type</th>
                                        <th>SMS</th>
                                        <th>Status</th>
                                        <th>Notification Date</th>
                                        <th>Approved Date</th>
                                        <th class="actions">{{ __('voyager::generic.actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($records as $data)
                                    @php
                                        if($data->customer_type=='Individual'){
                                            $customerType='customer';
                                        }else{
                                            $customerType='business';
                                        }
                                    @endphp
                                    <tr id= "tr_{{$data->id}}" data-id="{{$data->id}}">
                                        <td>
                                            <input type="checkbox" name="sms_id" id="checkbox_{{ $data->id }}" value="{{ $data->id }}">
                                        </td>
                                        <td>{{$data->customer_type=='Individual' ? $data->$customerType->person_name : '-'}}</td>
                                        <td>{{$data->customer_type=='Business' ? $data->$customerType->company_name : '-'}}</td>
                                        <td>{{$data->contact_phone}}</td>
                                        <td>{{$data->addedBy->business_name}}</td>
                                        <td>{{$data->addedBy->mobile_number}}</td>
                                        <td>{{$data->customer_type=='Individual' ? 'Individual' : 'Business'}}</td>
                                        <td><div class="wrap-table-text">{{$data->message}}</div></td>
                                        <td class="sms-notification-status">
                                            @if($data->approve_reject_status == 0) 
                                                Pending For Approval
                                            @elseif($data->approve_reject_status==1)
                                                Approved 
                                            @else
                                                Rejected 
                                            @endif
                                        </td>
                                        <td>{{date('F d, Y H:i',strtotime($data->created_at))}}
                                        </td>
                                        <td class="sms-notification-datetime">
                                            {{!empty($data->approve_reject_at) && $data->approve_reject_status ==1 ? date('F d, Y H:i',strtotime($data->approve_reject_at)) : 'N-A'}}
                                            
                                        </td>
                                        
                                        <td class="no-sort no-click bread-actions action-td">
                                            @if($data->approve_reject_status==0)
                                                <button type="button" class="btn btn-sm btn-primary approveOrReject" title="Accept" data-id="{{$data->id}}" data-action="APPROVE">
                                                   Approve
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger approveOrReject" title="Reject" data-id="{{$data->id}}" data-action="REJECT">
                                                   Reject
                                                </button>
                                            @endif
                                        </td>
                                        
                                    </tr>
                                    @empty
                                        <tr><td colspan="10" align="center">No Record Found</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        @if($records->count())
                        <div class="row">
                            <form action="{{route('superadmin.due-sms-approve-reject-bulk')}}" method="POST" id="due-sms-approve-reject-bulk">
                                @csrf
                                <input type="hidden" name="ids" value="" required>  
                                <div style="display: flex;align-items: flex-end;">
                                    <div class="col-md-2">
                                        <label>Select Action</label>    
                                        <select name="action" class="form-control input-sm" required>
                                            <option value="">Select</option>
                                            <option value="APPROVE">Approve</option>
                                            <option value="REJECT">Reject</option>
                                        </select>
                                    </div>
                                    <div class=" col-md-2 text-left text-md-left mt_form">
                                        <button type="submit" class="btn btn-primary btn-blue" style="margin:0;">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>    
                        @endif
                        <div class="pull-right">
                            {{$records->links()}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script>
    $(document).ready(function(){

        $("input.select_all").on('change',function(){
            if($(this).prop('checked')){
                $("input[name=sms_id]").prop('checked',true);
            }else{
                $("input[name=sms_id]").prop('checked',false);
            }
        });

        $(".approveOrReject").on('click',function(){
            var thisButton = $(this);
            var action = thisButton.data('action');
            var smsId = thisButton.data('id');

            if(!action || !smsId){
                alert('Somethig went wrong');
                return false;
            }
           
            $(".table #tr_"+smsId).find('.approveOrReject').attr('disabled', 'disabled');
            $.ajax({
               method: 'post',
               url: "{{route('superadmin.due-sms-approve-reject')}}",
               headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               },
               data: {
                    sms_id: smsId,
                    action: action,
                   _token: $('meta[name="csrf-token"]').attr('content')
               }
            }).then(function (response) {
                var alerter = toastr['success'];
                alerter(response.message);
                $(".table #tr_"+smsId).find('.approveOrReject').removeAttr('disabled');
                
                $(".table #tr_"+smsId).fadeOut(1000,function(){
                   $(this).find('.action-td').html('');
                   $(this).find('.sms-notification-status').text(response.newStatus);
                   $(this).find('.sms-notification-datetime').text(response.dateTime);
                   $(this).fadeIn(500);
                });
            }).fail(function (data) {
                $(".table #tr_"+smsId).find('.approveOrReject').removeAttr('disabled');
                var alerter = toastr['error'];
                alerter(data.responseJSON.message);
            });

        });


        $("form#due-sms-approve-reject-bulk").on('submit',function(){
            var arrValue= $('input[name=sms_id]:checked').map(function(){
                return this.value;
            }).get(); 
            
            if(!arrValue.length){
                alert('Please select atleast one sms notification');
                return false;
            }
            $(this).find('input[name="ids"]').val(arrValue);           
            
        });

    });
</script>    
@endsection
