@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' payments')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-list"></i> Payments
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
                            <form action="{{route('admin.due-payments')}}" method="get">
                                <div class="row">
                                	<div class="col-md-12">
                                        <div class="col-md-2">
                                            <label> Payment Type</label>
                                            <select name="payment_type" class="form-control input-sm">
                                                <option value="">Select</option>
                                                <option value="CUSTOMER_DUE_INDIVIDUAL" {{ app('request')->input('payment_type')=='CUSTOMER_DUE_INDIVIDUAL' ? 'selected' : '' }}>Customer Due - Individual</option>
                                                <option value="CUSTOMER_DUE_BUSINESS" {{ app('request')->input('payment_type')=='CUSTOMER_DUE_BUSINESS' ? 'selected' : '' }}>Customer Due - Business</option>
                                                <option value="COLLECTION_FEE_INDIVIDUAL" {{ app('request')->input('payment_type')=='COLLECTION_FEE_INDIVIDUAL' ? 'selected' : '' }}>Collection Fee - Individual</option>
                                                <option value="COLLECTION_FEE_BUSINESS" {{ app('request')->input('payment_type')=='COLLECTION_FEE_BUSINESS' ? 'selected' : '' }}>Collection Fee - Business</option>
                                            </select>

                                        </div>
                                        <div class="col-md-2">
                                            <label> From Date</label>
                                            <input type="date" name="from_date" class="form-control input-sm" value="{{!empty(app('request')->input('from_date')) ? app('request')->input('from_date') : '' }}">
                                        </div>
                                        <div class="col-md-2">
                                            <label> To Date</label>
                                            <input type="date" name="to_date" class="form-control input-sm" value="{{!empty(app('request')->input('to_date')) ? app('request')->input('to_date') : '' }}">
                                        </div>
                                        <div class="col-md-6 text-right text-md-right mt_form">
                                            <button type="submit" class="btn btn-primary"  aria-controls="dataTable">Search</button>
                                            <a href="{{route('admin.due-payments')}}" class="btn btn-primary"  aria-controls="dataTable">Reset</a>
                                        </div>
                                    </div>
                                </div>
                           </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 float-right float-sm-right">
                <div class="form-group">
                <label style="font-family: var(--font-rubik);font-weight: 400;">Choose Payments Option types</label>
                    <select class="form-control"  name="payment_option_type" required id="slectPaymentOptionType">
                        <option value="">Select</option>
                        <option value="payments" selected>Due Payments</option>
                        <option value="membershippayments" >Membership Payments</option>
                        <option value="consentpayments" >Reports Payments</option>
                    </select>
                    </div>
                </div>
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-hover fixed_headerss">
                                <thead>
                                <tr>
                                    <th>Customer Name</th>
                                    <th>Customer Mobile Number</th>
                                    <th>Customer Email Address</th>
                                    <th>Amount Overdue</th>
                                    <th>Due Date</th>
                                    <th>Payment Type</th>
                                    <th>Payment Amount</th>
                                    <th>Collection Amount @ 1%</th>
                                    <th>GST @ 18%</th>
                                    <th>Collection Date</th>
                                    <th>Payment Date</th>
                                    <th>Payment Status</th>
                                    <th>Payment Days</th>
                                    <th>Member Name</th>
                                    <th>Member Mobile Number</th>
                                    <th>Member Email Address</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @forelse($records as $data)
                                        @php
                                            if($data->customer_type=='INDIVIDUAL'){
                                                $dueType='individualDue';
                                                $paidType = 'individualPaid';
                                            }else{
                                                $dueType='businessDue';
                                                $paidType = 'businessPaid';
                                            }

                                        @endphp
                                    <tr>
                                        @if($data->customer_type=='INDIVIDUAL')
                                            <td>{{$data->individualProfile->person_name}}</td>
                                            <td>{{$data->individualProfile->contact_phone}}</td>
                                        @else
                                            <td>{{$data->businessProfile->company_name}}</td>
                                            <td>{{$data->businessProfile->concerned_person_phone}}</td>
                                        @endif
                                            <td>-</td>
                                            <td>{{General::ind_money_format($data->$dueType->due_amount)}}</td>
                                            <td>{{date('d/m/Y', strtotime($data->$dueType->due_date))}}</td>
                                            <td>
                                                @if($data->payment_done_by =='CUSTOMER' && $data->customer_type=='INDIVIDUAL')
                                                    Customer Due - Individual
                                                @elseif($data->payment_done_by =='CUSTOMER' && $data->customer_type=='BUSINESS')
                                                    Customer Due - Business
                                                @elseif($data->payment_done_by =='ADMIN_MEMBER' && $data->customer_type=='INDIVIDUAL')
                                                    Collection Fee - Individual
                                                @elseif($data->payment_done_by =='ADMIN_MEMBER' && $data->customer_type=='BUSINESS')
                                                    Collection Fee - Business
                                                @endif
                                            </td>
                                            <td>{{General::ind_money_format($data->payment_value)}}</td>
                                            @if($data->payment_done_by =='CUSTOMER')
                                                <td>-</td>
                                                <td>-</td>
                                            @else
                                                <td>{{number_format($data->collection_fee,2)}}</td>
                                                <td>{{number_format($data->gst_value,2)}}</td>
                                            @endif
                                            <td>{{date('d/m/Y H:i', strtotime($data->created_at))}}</td><!-- Collection Date -->
                                            <td>
                                                @if($data->payment_done_by =='ADMIN_MEMBER')

                                                    @php $duePaymentDate = $data->$paidType->paid_date?? ''; @endphp

                                                    @if(!empty($duePaymentDate)) {{date('d/m/Y', strtotime($duePaymentDate))}} @else - @endif
                                                @else
                                                    -
                                                @endif
                                            </td>

                                            <td>
                                                @if($data->status==4)
                                                    @if($data->$dueType->due_amount == $data->payment_value)
                                                        Full
                                                    @else
                                                        Part
                                                    @endif
                                                @elseif($data->status==5)
                                                    Failed
                                                @elseif($data->status==3)
                                                    Aborted
                                                @elseif($data->status==2)
                                                    Open
                                                @elseif($data->status==1)
                                                    In Progress
                                                @else
                                                    -
                                                @endif
                                            </td>
                                            <td>{{Carbon\Carbon::parse($data->created_at)->diffInDays($data->$dueType->due_date)}}</td>
                                            <td>{{$data->$dueType->addedBy->business_name ?? '' }}</td>
                                            <td>{{$data->$dueType->addedBy->mobile_number ?? ''}}</td>
                                            <td>{{$data->$dueType->addedBy->email ?? ''}}</td>

                                    </tr>
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
    <script type="text/javascript">
    $(document).ready(function(){
      $('#slectPaymentOptionType').change(function() {
        //alert($(this).val());
        location.href = '/admin/'+$(this).val();
      });
    });
    </script>
@endsection
