@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' payments')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-list"></i>Membership Payments
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
                            <form action="{{route('admin.membershippayments-listing')}}" method="get">
                                <div class="row">
                                	<div class="col-md-12">
                                        <div class="col-md-2">
                                            <label> Plan Type</label>
                                            <select name="payment_type" class="form-control input-sm">
                                                <option value="">Select</option>
                                                <option value="2" {{ app('request')->input('payment_type')==2 ? 'selected' : '' }}>Basic</option>
                                                <option value="3" {{ app('request')->input('payment_type')==3 ? 'selected' : '' }}>Executive</option>
                                                <option value="4" {{ app('request')->input('payment_type')==4 ? 'selected' : '' }}>Corporate</option>
                                                <option value="5" {{ app('request')->input('payment_type')==5 ? 'selected' : '' }}>Standard</option>
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
                                            <a href="{{route('admin.membershippayments-listing')}}" class="btn btn-primary"  aria-controls="dataTable">Reset</a>
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
                        <option value="payments" >Due Payments</option>
                        <option value="membershippayments" selected >Membership Payments</option>
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
                                    <tr><th>Member Name</th>
                                    <th>Member Mobile Number</th>
                                    <th>Member Email Address</th>
                                    <th>Plan</th>
                                    <th>Subscription Amount</th>
                                    <th>GST @ 18%</th>
                                    <th>Payment Mode</th>
                                    <th>Payment Date</th>
                                    <th>Payment Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                  @forelse($records as $data)
                                  <tr>
                                  <td>{{$data->user->business_name ?? '' }}</td>
                                  <td>{{$data->user->mobile_number ?? '' }}</td>
                                  <td>{{$data->user->email ?? '' }}</td>
                                  <td>{{$data->pricing_plan->name ?? '' }}</td>
                                  <td>{{$data->payment_value}}</td>
                                  <td>{{$data->gst_value}}</td>
                                  <td>{{$data->payment_mode}}</td>
                                  <td>{{$data->created_at}}</td>
                                  <td>
                                    @if($data->status==4)
                                        Success
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
          location.href = '/admin/'+$(this).val();
        });
      });
    </script>
@endsection
