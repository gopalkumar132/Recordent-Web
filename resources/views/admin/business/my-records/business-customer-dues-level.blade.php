@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' My Business Records')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-list"></i> {{-- $records->display_name_plural --}}My Business Records
    </h1>
    <ul class="name_title">
            {{--<li>
                <a href="#" class="btn btn-sm btn-primary view"><i class="voyager-eye"></i> = View</a>
            </li>--}}
            <li>
                <a href="#" class="btn btn-sm btn-primary view"><i class="voyager-plus"></i> = Add Record</a>
            </li>

            <li>
                <a href="#" class="btn btn-sm btn-primary view"><i class="voyager-eye"></i> = Detail View</a>
            </li>
            <li>
                <a href="" class="btn btn-success addPayButton" data-toggle="modal" data-target="#pay" data-due-id="1" title="Pay" data-customer-no="44" data-invoice-no="121">
                    <i class="fa fa-money btn-success" aria-hidden="true"></i> = Update Payments
                </a>
                <a class="btn btn-warning editDueButton" title="Edit">
                    <i class="voyager-edit"></i> = Edit Record
                </a>
                <a href="" class="btn btn-primary paymentHistoryButton" data-toggle="modal" data-target="#paymentHistory" data-due-id="1" title="Payment History">
                    <i class="fa fa-history" aria-hidden="true"></i> = Payment History
                </a>
                <?php /*<a href="" class="btn btn-danger dueDeleteButton" data-toggle="modal" data-target="#dueDelete" data-due-id="1" title="Delete Record">
                    <i class="voyager-trash"></i> = Delete Due Record
                </a>
                */
                ?>
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
<style type="text/css">
    .form-group{
        position: relative;
        margin-bottom: 30px;
    }
    form label.error{bottom: -25px;top: auto;}
    .md-header{
        margin-bottom: 1px !important;
    }
    .md-footer{
        margin-bottom: 1px !important;
    }
  .pull-right{
        padding-top: 10px;
        padding:  0px 11px 13px;
        font-weight: bold;
        font-family: var(--font-rubik);
    }

input[type=date]{
    text-transform: uppercase;
}
@media only screen and (min-width:320px) and (max-width:767px){
     .md-header{
        /*display: block;*/
        margin-top: 5px !important;
        margin-bottom: 0px;
    }
    .md-footer{
        /*display: block;*/
        margin-top: 122px !important;
        margin-bottom: 0px;
    }
  .pull-right{
       padding:  30px 15px 13px;
      font-weight: bold;
      font-family: var(--font-rubik);
    }

}
</style>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

    <div class="page-content container-fluid">
        @include('voyager::alerts')

        <div class="row">

            <div class="col-md-12 md-header">
                <div class="panel panel-bordered">
                    <div class="panel-body">

                        <div id="dataTable_filter" class="dataTables_filter">
                            <!--<form action="{{route('business.my-records')}}" method="get">-->
                            <form action="" method="get">
                            <div class="row">
                                <div class="col-md-12">
                                 <div class="row new_width">
                                    <div class="col-md-2">
                                        <label>{{General::getLabelName('unique_identification_number')}}: </label>
                                        <input type="text" name="unique_identification_number" class="form-control " aria-controls="dataTable" value="{{!empty(app('request')->input('unique_identification_number')) ? app('request')->input('unique_identification_number') :'' }}">
                                   </div>
                                    <div class="col-md-2">
                                        <label>  Person Name:</label>
                                        <input type="text" name="concerned_person_name" class="form-control " placeholder="" aria-controls="dataTable" value="{{!empty(app('request')->input('concerned_person_name')) ? app('request')->input('concerned_person_name') : '' }}">
                                    </div>
                                     <div class="col-md-2">
                                        <label> Person Phone:</label>
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
                                        <a href="{{route('business.my-records')}}" class="btn btn-primary"  aria-controls="dataTable">Reset</a>
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


 <!--<div class="pull-right">
  <form action="{{route('export-business')}}">
     Paid Date:
    &nbsp
     <input type="date" name="date" id="date">
    <button class="btn btn-info download-mem-data btn-blue">Download Business Customers <i class="voyager-download"></i></button>
 </form>
</div>-->

            <div class="col-md-12 md-footer">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-hover fixed_headerss">
                                <thead>
                                <tr>
                                    <th>Company</th>
                                    <th>{{General::getLabelName('unique_identification_number')}}</th>
                                    {{--<th>Business Type</th>--}}
                                    {{--<th>Concerned Person</th>--}}
                                     {{--<th>Concerned Person Phone</th>--}}
                                     {{--<th>Location</th>--}}
                                    {{--<th>Reported Date</th>--}}
                                    <th>Due Date</th>
                                    {{--<th>Outstanding Days</th>--}}
                                    <th>Reported Due</th>
                                    <th>Balance Due</th>
                                    <th class="text-center">Invoice Number</th>
                                    <th class="actions">{{ __('voyager::generic.actions') }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @forelse($records as $data)
                                        @php
                                            $sector = General::getSector($data->profile->sector_id);
                                        @endphp
                                    <tr>
                                    <?php {
                                        $ActiveStatus="enabled";
                                        $fdate = date('d/m/Y', strtotime($data->created_at));
                                        $tdate =date('d/m/Y');
                                        $to = \Carbon\Carbon::createFromFormat('d/m/Y', $fdate);
                                        $from = \Carbon\Carbon::createFromFormat('d/m/Y',$tdate);
                                        $diff_in_days = $to->diffInDays($from);
                                        if(setting('admin.number_of_days') < $diff_in_days)
                                        {
                                            $ActiveStatus="disabled";
                                        }
                                        $invoice_no=$data->invoice_no;
                                        if(empty($data->invoice_no)){
                                            $invoice_no="NA";
                                        }

                                    }?>
                                     <td>{{$data->profile->company_name}}</td>
                                        <!-- <td>{{$data->profile->unique_identification_number}}{{ $sector ? ' ('.General::getUniqueIdentificationTypeofSector($sector->unique_identification_type).')' : ''}}</td> -->
                                        <td style="text-transform:uppercase">{{$data->profile->unique_identification_number}}</td>
                                        {{--<td>{{ $sector ? $sector->name : ''}}</td>--}}
                                        {{--<td>{{$data->profile->concerned_person_name}}</td>--}}
                                        {{--{{$data->profile->concerned_person_phone}}</td>--}}
                                        {{--<td>{{General::getCityNameById($data->profile->city_id)}},{{General::getStateNameById($data->profile->state_id)}}</td>--}}
                                        {{--<td>{{date('d/m/Y', strtotime($data->created_at))}}</td>--}}
                                        <td>{{date('d/m/Y', strtotime($data->due_date))}}</td>
                                        {{--<td>{{General::diffInDays($data->due_date)}}</td>--}}
                                        <td>
                                            {{General::ind_money_format($data->due_amount)}}
                                            {{-- General::ind_money_format(General::getTotalDueForBusiness($data->id,Auth::id()) - General::getTotalPaidForBusiness($data->id,Auth::id())) --}}
                                        </td>
                                        {{--<td>{{General::ind_money_format(General::getNumberOfDuesOfBusiness($data->id,Auth::id()))}}</td>--}}
                                      <?php if($settled_records!=''){?>
                                        <td class="balance">0</td>
                                        <?php } else {?>
                                        <td class="balance">
                                         {{General::ind_money_format($data->due_amount - $data->totalPaid)}}
                                        </td>
                                         <?php }?>
                                        <td class="text-center">{{$invoice_no}}</td>
                                        <td class="no-sort no-click bread-actions">
                                               {{-- <a href="{{ route('business.business-data', $data->profile->id) }}" class="btn btn-sm btn-warning view" title="view Record">
                                                    <i class="voyager-eye"></i>
                                                </a>--}}
                                                <!-- @if(Auth::id()==$data->profile->added_by)
                                                <a href="{{ route('business.edit-business', $data->profile->id) }}{{(Request::getQueryString() ? ('?' . Request::getQueryString()) : '')}}" class="btn btn-sm btn-warning view" title="Detail View">
                                                    <i class="voyager-eye"></i>
                                                </a>
                                                @endif -->
                                                <a href="javascript:void" class="btn btn-sm btn-warning view dueDataListing" title="Detail View" data-due-id="{{$data->id}}" data-profile-id="{{$data->profile->id}}" data-toggle="modal" data-target="#dueDataListing">
                                                    <i class="voyager-eye"></i>
                                                </a>
                                                <a href="" class="btn btn-primary addOutstanding" data-toggle="modal" data-target="#outstanding" data-profile-id="{{$data->profile->id}}" data-custom-id="{{$data->external_business_id}}" title="Add Record">
                                                    <i class="voyager-plus" aria-hidden="true"></i>
                                                </a>
                                                @php
                                                  if($settled_records!='')
                                                   {
                                                    $amountDue=0;
                                                  } else {
                                                    $amountDue = $data->due_amount - $data->totalPaid;
                                                  }
                                                @endphp
                                                <a href="" class="btn btn-success addPayButton {{$amountDue<=0 ? 'disabled' : '' }}" data-toggle="modal" data-target="#pay" data-due-id="{{$data->id}}" data-profile-id="{{$data->profile->id}}" title="Update Payments" data-collection-date="{{$data->collection_date}}" data-due-date="{{Carbon\Carbon::parse($data->due_date)->toDateString()}}"
                                                data-due-reported-date="{{Carbon\Carbon::parse($data->created_at)->toDateString()}}">
                                                <i class="fa fa-money" aria-hidden="true"></i>
                                                </a>
                                                <a class="btn btn-warning editDueButton {{$amountDue<=0 ? 'disabled' : '' }}" data-due-id="{{$data->id}}" data-profile-id="{{$data->profile->id}}" title="Edit Record">
                                                    <i class="voyager-edit"></i>
                                                </a>
                                                <a href="" class="btn btn-primary paymentHistoryButton" data-toggle="modal" data-target="#paymentHistory" data-due-id="{{$data->id}}" data-profile-id="{{$data->profile->id}}" data-custom-id="{{$data->external_business_id}}" title="Payment History">
                                                    <i class="fa fa-history" aria-hidden="true"></i>
                                                </a>
                                                <a href="" class="btn btn-danger dueDeleteButton" data-toggle="modal" data-target="#dueDelete" data-due-id="{{$data->id}}" data-profile-id="{{$data->profile->id}}" title="Delete Due Record" <?php echo $ActiveStatus;?>>
                                                    <i class="voyager-trash"></i>
                                                </a>


                                        </td>
                                    </tr>
                                    @empty
                                        <tr><td colspan="10" align="center">No Record Found</td></tr>
                                        @if(Auth::user()->hasRole('admin'))
                                        <tr><td colspan="10" align="center"><a href="{{route('voyager.users.index')}}"><button type="button" class="btn btn-primary" aria-controls="dataTable">Reporting Organization</button></a></td></tr>
                                        @endif
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            {{$records->links()}}
            </div>
        </div>
    </div>
@if($records->count())
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
    <!-- Start Model Submit Outstanding Record -->
    <div class="modal commap-team-popup" id="outstanding" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title">Add Record</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
          </div>
          <div class="modal-body">
            <form action="{{ route('business.store-due', $data->business_id) }}" method="POST" enctype="multipart/form-data" id="store_data">
                @csrf
                <input type="hidden" value="" name="business_id">
                <input type="hidden" name="custom_id" value="" id="custom_id">
                <!-- <div class="form-group">
                    <label for="due_date">*Due Date</label>
                    <input type="date" class="form-control" name="due_date" value="{{date('Y-m-d', strtotime(Carbon\Carbon::now()))}}">
                </div>     -->
                <div class="form-group duedate_check_errclass">
                      <label for="contact_phone">Due Date (DD/MM/YYYY)*</label>
                       <input type="text" name="due_date" id="due_date_0" autocomplete="off" class="form-control datepicker collectionsetevent" data-date-format="DD/MM/YYYY" required aria-controls="dataTable" value="{{old('due_date')}}">
                </div>
                <div class="form-group">
                                     <label for="grace_period">Grace period *  <span class="grace_period_info"  data-toggle="tooltip" data-placement="top" title="Grace Period is a set length of time after the due date during which the payment can be made. This may differ depending on your sector of business and your terms with the Customer"><i class="fa fa-info-circle"></i></span></label>
                                    <select class="form-control grace_period" id="grace_period_0" name="grace_period" disabled="">
                                        <option value="1">1 day</option>
                                        <?php
                                        $allowedgraceperiod = array(7, 15, 21, 30, 45, 75, 90, 120, 150, 180);
                                        for($i=0;$i<count($allowedgraceperiod);$i++){
                                            $days = $allowedgraceperiod[$i];
                                        ?>
                                             <option value="{{$days}}">{{$days}} days</option>
                                        <?php } ?>

                                    </select>
                                </div>
                                <input type="hidden" name="grace_period_hidden" id="grace_period_hidden_0" value="1"/>
                            <!-- </div> -->

                   <!-- <div class="col-md-6"> -->
                                <div class="form-group collectiondateblock collection_date_block_0">
                                    <label for="collection_date">Collection Start Date (DD/MM/YYYY)*  <span class="collection_date_info" data-toggle="tooltip" data-placement="top" title="Collection Start Date is the date on which Recordent will start contacting the Customer to recover the dues"><i class="fa fa-info-circle"></i></span></label>
                                    <input type="text" id="collection_date_0" name="collection_date" class="form-control datepicker collection_date" placeholder="" data-date-format="DD/MM/YYYY" required aria-controls="dataTable" readonly value="{{old('collection_date')}}">
                                </div>
                            <!-- </div> -->

                            <div class="clearfix"></div>
                <div class="form-group">
                    <label for="due_amount">*Amount Due</label>
                    <input type="text" class="form-control" name="due_amount" value="" onkeypress="return numbersonly(this,event)">
                    <label class="dueAmountInWord" style="display: none"></label>
                </div>
                <!--<div class="form-group">
                    <label for="external_business_id">Custom ID</label>
                    <input type="text" class="form-control" id="external_business_id" name="external_business_id" value="" onkeypress="return blockSpecialChar(this,event)" maxlength="50" style="text-transform: uppercase;">
                    <label class="dueAmountInWord" style="display: none"></label>
                </div>-->
                <div class="form-group">
                    <label for="due_note">Note</label>
                    <textarea class="form-control" name="due_note" maxlength="300" onkeypress="return blockSpecialChar(this,event)"></textarea>
                </div>
                <div class="form-group proofofdue_check_errclass files color">
                                <label>Proof Of Due </label>
                                <input type="file" id="editFileupload" class="form-control mydrop filesImg responsive" name="proof_of_due[]" multiple accept='.jpg,.png,.jpeg,.pdf,.docx,.xls,.xlsx,.bmp,.csv' style="text-align:center !important;border-color: #ecf7fc;background-color: #ecf7fc;border:dashed;">
                                <p for="contact_phone">Note: Only pdf,docx,jpeg,png,bmp,xls,xlsx,csv files are allowed <span id="imgError" style="color:red;"></span></p>

                            </div>
                <!-- <div class="form-group">
                    <label for="due_note">Proof of Due</label>
                    <input type="file" class="form-control fl-upload-height" name="proof_of_due" accept='.jpg,.png,.jpeg,.pdf,.doc,.docx,.xls,.xlsx,.bmp,.csv'>
                    <label for="contact_phone">Note: Only pdf,doc,docx,jpeg,png,bmp,xls,xlsx,csv files are allowed</label>
                </div>   -->

                <div class="form-check">
                    <label class="form-check-label" for="agree_terms">By clicking Submit  you indicate that you have read and agree to the terms of the Recordent <a target="_blank" href="{{route('end-user-license-agreement')}}" target="_blank">End User License Agreement</a></label>
                </div>
                <div class="form-action text-center">
                    <button type="submit" class="btn btn-primary btn-blue">SUBMIT</button>
                </div>
            </form>
          </div>
          <div class="modal-footer">
          </div>
        </div>
      </div>
    </div>

    <!-- Start Model Pay Outstanding Amount -->
    <div class="modal commap-team-popup" id="pay" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title">Update Payment</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="{{ route('business.business-store-pay', $data->business_id) }}" method="POST" onSubmit="document.getElementById('update_payment_disabled').disabled=true;">
                @csrf
                <input type="hidden" name="outstanding" value="">
                <input type="hidden" name="skipcollectionpayment" id="skipcollectionpayment" value="1">
                <input type="hidden" value="" name="business_id">
                <input type="hidden" name="redirect_query_string" value="{{Request::getQueryString() ? '?'.Request::getQueryString() : ''}}">

                <div class="form-group">
                    <label for="due_amount">*Amount Due</label>
                    <input type="text" class="form-control" name="due_amount" id="due_amount" value="" readonly>
                    <label class="dueAmountInWord" style="display: none"></label>
                </div>
                <div class="form-group">
                    <label for="pay_date">*Payment Date</label>
                    <input type="text" class="form-control datepicker1" id="payment_date_datetimepicker" name="payment_date" autocomplete="off" required data-date-format="DD/MM/YYYY">
                </div>
                <div class="form-group">
                    <label for="due_amount">*Payment Amount (Should be above ₹1)</label>
                    <input type="text" class="form-control" name="payment_amount" min="2" value="" required onblur="trimIt(this);" onkeypress="return numbersonly(this,event)" oninput="chargesApplicable(this)">

                    <span id="dueAmountExceedError" style="color:red;"></span>
                    <label class="applicableCharges" style="display: none">charges applicable including GST is ₹<font id="applicableCharges" style="font-family: monospace;"></font><br>({{HomeHelper::getMyRecordsCollectionFeePercent()}}% of payment amount + 18% GST or ₹50 + 18% GST which ever is higher)</label>
                </div>
               <div class="form-group">
                    <label for="paid_note">Note</label>
                    <textarea class="form-control" name="payment_note" maxlength="300" onkeypress="return blockSpecialChar(this,event)"></textarea>
                </div>
                @if(General::checkMemberEligibleToSkipCollectionPayment() && Auth::user()->collection_fee_business == 0)
                <!-- <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="skip_payment">
                     <label class="form-check-label" for="agree_terms">Skip payment</label>
                </div> -->
                @endif
                <div class="form-check">
                    <input type="checkbox" class="form-check-input" name="send_updatepayment_sms">
                     <label class="form-check-label" for="agree_terms">Send update payment SMS</label>
                </div>

                <div class="form-check no-collection-fee-terms">
                    <label class="form-check-label" for="agree_terms">I understand that by clicking on the "Submit" button, I agree to Recordent's <a target="_blank" href="{{route('end-user-license-agreement')}}" target="_blank">End User License Agreement</a></label>
                </div>
                <div class="form-check with-collection-fee-terms">
                    <label class="form-check-label" for="agree_terms">I understand that by clicking on the "Submit" button, I agree to Recordent's <a target="_blank" href="{{route('end-user-license-agreement')}}" target="_blank">End User License Agreement</a> and I agree to pay {{HomeHelper::getMyRecordsCollectionFeePercent()}}% of payment amount (Excluded tax) for using Recordent’s services. </label>
                </div>
                <div class="form-action text-center">
                    <button type="submit" class="btn btn-primary btn-blue" id="update_payment_disabled">SUBMIT</button>
                </div>
            </form>
          </div>
          <div class="modal-footer">
          </div>
        </div>
      </div>
    </div>

    <!-- Due delete Model -->
    <!-- Start Model Edit Outstanding Amount -->
    <div class="modal commap-team-popup" id="edit" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title">Edit Record</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
          </div>
          <div class="modal-body">

            <form action="{{ route('business.business-edit-due', $data->business_id) }}" method="POST" enctype="multipart/form-data" id="new_data">
                @csrf
                @method('put')
                <input type="hidden" name="outstanding" value="">
                <input type="hidden" value="" name="business_id">
                <div class="form-group">
                    <label for="contact_phone">Company Name*</label>
                    <input type="text" class="form-control" minlength="3" name="company_name" value="" placeholder="Member Name*" maxlength="{{General::maxlength('name')}}" required onblur="trimIt(this);">
                </div>
                <div class="form-group">
                    <label for="contact_phone">Person Phone*</label>
                    <input type="tel" class="form-control number" name="concerned_person_phone" value="" placeholder="Concerned Person Phone" required  maxlength="10" onkeypress="return numbersonly(this,event)">
                </div>
                <div class="form-group">
                    <label for="email"> Email Address*</label>
                    <input type="email" name="email" class="form-control " placeholder="Email" maxlength="{{General::maxlength('email')}}" aria-controls="dataTable" value="">
                </div>
                <div class="form-group">
                    <label for="due_date">*Due Date</label>
                    <input type="date" class="form-control" name="due_date" value="{{date('m-d-Y', strtotime(Carbon\Carbon::now()))}}">
                    <span id="date_error"></span>
                </div>
                <input type="hidden" name="EdithiddenDuedate" class="EdithiddenDuedate" value="">
                <div class="form-group">
                    <label for="due_amount">*Amount Due</label>
                    <input type="number" id="dueamount_check" class="form-control" name="due_amount" value="" {{$editDueAmount}}  onkeypress="return numbersonly(this,event)">
                    <label class="dueAmountInWord" style="display: none"></label><br>
                    <span id='dueerror'></span>
                    <input type="hidden"  name="originalDueAmount" class="originalDueAmount" value="">
                </div>
                <!-- <div class="form-group">
                    <label for="due_date">*Balance Due</label>
                    <input type="number" class="form-control" name="balance_due" id="updateval" value=""  onkeypress="return numbersonly(this,event)">

                </div>  -->
                <input type="hidden" name="PreviousBalance" value="">
                <!--<div class="form-group">
                <label for="external_business_id">Custom ID </i></label>
                <input type="text" name="external_business_id" id="external_business_id" class="form-control " placeholder="Custom ID" maxlength="50" aria-controls="dataTable" value="" onkeypress="return blockSpecialChar(this,event)">
                </div>-->

                <div class="form-group">
                    <label for="contact_phone">Invoice No</label>
                    <input type="text" maxlength="20" class="form-control invoice_number" id="invoice_no" name="invoice_no" value="" placeholder="Invoice No" onblur="trimIt(this);">
                </div>

                <input type="hidden" class="numberflag" name="numberflag" value="">
                <div class="form-group">
                    <label for="due_note">Note</label>
                    <textarea class="form-control" name="due_note" maxlength="300" onkeypress="return blockSpecialChar(this,event)"></textarea>
                </div>
                <input type="hidden" class="dayslimt" value="{{setting('admin.number_of_days')}}">
                <div class="form-group proof_of_due_main_div">
                <div id="view_proof_of_due_div">

                    </div>
                    <!-- <div class="proof_of_due_div">
                        <a id="view_proof_of_due" target="blank" href="">View</a> |
                        <a id="delete_proof_of_due" href="javascript:void" data-due-id="">Delete Proof Of Due</a>
                    </div> -->

                    <div class="form-group proofofdue_check_errclass files color">
                                <label>Proof Of Due </label>
                                <image class="download_img" src="https://image.flaticon.com/icons/png/128/109/109612.png" style="pointer-events: none;
                    position: absolute;top: 38px;;left: 0;width: 50px;right: 0;height: 56px;content: none;display: block;margin: 0 auto;background-size: 100%;background-repeat: no-repeat;">
                                <input type="file" id="editFileupload_editdue" class="form-control mydrop filesImg responsive" name="proof_of_due[]" multiple accept='.jpg,.png,.jpeg,.pdf,.docx,.xls,.xlsx,.bmp,.csv' style="text-align:center !important;border-color: #ecf7fc;background-color: #ecf7fc;border:dashed;">
                                <p for="contact_phone">Note: Only pdf,docx,jpeg,png,bmp,xls,xlsx,csv files are allowed <span id="imgError_editdue" style="color:red;"></span></p>

                            </div>

                    <!-- <label for="due_note">Proof of Due</label>
                    <input type="file" id="editFileupload" class="form-control fl-upload-height" name="proof_of_due" accept='.jpg,.png,.jpeg,.pdf,.doc,.docx,.xls,.xlsx,.bmp,.csv'>
                    <label for="contact_phone">Note: Only pdf,doc,docx,jpeg,png,bmp,xls,xlsx,csv files are allowed <span id="imgError" style="color:red;"></span></label> -->
                </div>
                <input type="hidden" id="ExsistingImgcount" value="">
                <div class="form-check">
                    <label class="form-check-label" for="agree_terms">By clicking Submit  you indicate that you have read and agree to the terms of the Recordent <a target="_blank" href="{{route('end-user-license-agreement')}}" target="_blank">End User License Agreement</a></label>
                </div>
                <div class="form-action text-center">
                    <button type="submit" class="btn btn-primary btn-blue editsubmit-btn">SUBMIT</button>
                </div>
            </form>
          </div>
          <div class="modal-footer">
          </div>
        </div>
      </div>
    </div>

     <!-- Start Model Pay Due delete Model -->
    <div class="modal" id="dueDelete" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
          </div>
          <div class="modal-body">
          <p style="font-size: 24px;font-family:bold;color:red;"> Are you sure you want to delete this record?</p>
            <form action="{{ route('business.business-delete-due')}}" method="POST">
                @csrf
                <input type="hidden" name="due_id" value="">
                <div class="form-action pull-right">
                    <button type="submit" class="btn btn-success">Yes</button>
                    <button type="reset" class="btn btn-danger" data-dismiss="modal">CANCEL</button>
                </div>
            </form>
          </div>
          <div class="modal-footer">
          </div>
        </div>
      </div>
    </div>






    <!--- Payment History Model-->
     <div class="modal commap-team-popup" id="paymentHistory" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title">Payment History</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
          </div>
          <div class="modal-body">

          </div>
          <div class="modal-footer">
          </div>
        </div>
      </div>
    </div>

     <!-- Due Data Listing Modal -->
    <div class="modal commap-team-popup" tabindex="-1" id="dueDataListing" role="dialog">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">Detail View</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">

                </div>
            </div>
        </div>
    </div>


    <!-- Start Model payment delete Model -->
    <div class="modal" id="paymentHistoryDelete" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title">Delete Payment Record</h3>
          </div>
          <div class="modal-body">
            <form action="{{ route('business.business-payment-history-delete')}}" method="POST">
                @csrf
                <input type="hidden" name="payment_id" value="">
                <div class="form-group">
                    <label for="aadhaar_number">*Note</label>
                    <input type="text" class="form-control" name="delete_note" required>
                </div>
                <div class="form-group">
                    <label for=""></label>
                    <input type="checkbox" class="form-check-input" required="required" name="agree_terms"><label for="">Check here to indicate that you have read and agree to the terms of the</label>
                </div>
                <div class="form-group">
                    <a target="_blank" href="{{route('end-user-license-agreement')}}" target="_blank">Recordent End User License Agreement</a></label>
                </div>

                <div class="form-action pull-right">
                    <button type="submit" disabled class="btn btn-primary">SUBMIT</button>
                    <button type="reset" class="btn btn-secondary" data-dismiss="modal">CANCEL</button>
                </div>
            </form>
          </div>
          <div class="modal-footer">
          </div>
        </div>
      </div>
    </div>

<script src="{{asset('js/jquery.validate.min.js')}}"></script>
    <script src="{{asset('js/number-to-word.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
     <script type="text/javascript">
      $(document).ready(function() {
        $('body').on('focus','.datepicker',function(){
    $(this).datetimepicker();
});
    // $('.datepicker1').datetimepicker({
    //     maxDate: new Date()
    // });
  });
    </script>

    <script language="javascript" type="application/javascript">

    $.validator.addMethod("file_upload", function(value, element) {
        return this.optional(element) || /(.*png$)|(.*jpg$)|(.*docx$)|(.*xlsx$)|(.*pdf$)|(.*bmp$)|(.*csv$)|(.*xls$)|(.*doc$)|(.*jpeg$)$/i.test(value);
    }, "Invalid File Format.");

    $.validator.addMethod("alphanum", function(value, element) {
        return this.optional(element) || /^[a-z0-9]+$/i.test(value);
    }, "Only alphabet and numbers allowed.");


    $('#store_data').validate({

        rules: {

            proof_of_due: {
                file_upload:true,
            },
            due_amount:{
                min:500
            }
        }
    });

     /*$('#new_data').validate({

        rules: {

            proof_of_due: {
            file_upload:true,
            }


        }
    });*/

        function set_collection_date(currentId){
        //var custom_date=$("input[name=due_date]").val().split('/');

        var custom_date=$("#"+currentId).val().split('/');
        // console.log(custom_date[1]+'/'+custom_date[0]+'/'+custom_date[2]);
         var d = new Date(custom_date[1]+'/'+custom_date[0]+'/'+custom_date[2]);
         // console.log(Date.parse($("input[name=due_date]").val()));
         // console.log($("input[name=due_date]").val());
         var today= new Date("{{ date('Y-m-d 00:00:00') }}");
         // console.log(d);
         // console.log(today);
         var idNum = currentId.split('_');
         if(d<today){
            // console.log('grace_period  applicable');
            $("#grace_period_"+idNum[2]).val('1').prop("disabled", true);
            $("#grace_period_hidden_"+idNum[2]).val(parseInt($("#grace_period_" + idNum[2]).val()));
            //$(".grace_period").prop("disabled", true);
            d=today;
            d.setDate(today.getDate() + 1);
         }else{
            // console.log('grace_period not applicable');
            //$(".grace_period").prop("disabled", false);
            $("#grace_period_"+idNum[2]).prop("disabled", false);
            //d.setDate(d.getDate() + parseInt($('.grace_period').val()));
            $("#grace_period_hidden_"+idNum[2]).val(parseInt($("#grace_period_"+idNum[2]).val()));
            d.setDate(d.getDate() + parseInt($("#grace_period_"+idNum[2]).val()));

         }
         var  month = '' + (d.getMonth() + 1),day = '' + d.getDate(),year = d.getFullYear();
            if (month.length < 2)
                month = '0' + month;
            if (day.length < 2)
                day = '0' + day;
            //$('.collection_date_block').show();
            $('.collection_date_block_'+idNum[2]).show();
            // console.log([day,month,year ].join('/'));
         //$('.collection_date').val([day,month,year ].join('/'));
         $('#collection_date_'+idNum[2]).val([day,month,year ].join('/'));
    }

    $(document).ready(function(){


        if($("input[name=due_amount]").val()){
            convertToINRFormat($("input[name=due_amount_0]").val(),$("input[name=due_amount]"));
        }
        //$("input[name=due_amount]").keyup(function() {
        $("body").on('keyup','.invoice_due_amount',function() {
            convertToINRFormat($(this).val(),$(this));

        });

        //$("input[name=due_amount]").on('input',function(){
            $("body").on('input','.invoice_due_amount',function(){
                var currentId = this.id;
                var idNum = currentId.split('_');
            dueAmountInWordlabel = $(this).parent().find('label.dueAmountInWord_'+idNum[2]).eq(0);
            dueAmountInWord = price_in_words_ind($(this).val());
            if(dueAmountInWord){
                dueAmountInWordlabel.text(dueAmountInWord);
                dueAmountInWordlabel.show(1);
            }else{
                dueAmountInWordlabel.hide(1);
                dueAmountInWordlabel.text('');
            }
        });



        /*if($("input[name=due_amount]").val()){
            convertToINRFormat($("input[name=due_amount]").val(),$("input[name=due_amount]"));
        }*/
        $('.collection_date_info').tooltip('toggle')
        $('.grace_period_info').tooltip('toggle');

        $('.collection_date_info').tooltip('hide')
        $('.grace_period_info').tooltip('hide');
        //$('.collection_date_block').hide();
        $('body .collectiondateblock').hide();
        //$("input[name=due_date]").on('dp.change',function(){
            $("body").on('dp.change blur',".collectionsetevent",function(){
             set_collection_date(this.id);
        });
        //$('.grace_period').on('change',function(){
        $("body").on('change','.grace_period',function(){
            var currentId = this.id;
            var idNum = currentId.split('_');
            var due_date = "due_date_"+idNum[2];
            //console.log(currentId+"----------------"+due_date); return false;
            set_collection_date(due_date);
        });
    });

</script>
    <script type="text/javascript">
        var gstPerc ={{setting('admin.consent_payment_value_gst_in_perc') ? (int)setting('admin.consent_payment_value_gst_in_perc') : 0}};
        var collectionFeePerc = {{HomeHelper::getMyRecordsCollectionFeePercent()}};
        var skipCollection = false;
        var currentDate = "{{Carbon\Carbon::now()->toDateString()}}";
        currentDate = new Date(currentDate);
        function toFixedTrunc(x, n) {
          const v = (typeof x === 'string' ? x : x.toString()).split('.');
          if (n <= 0) return v[0];
          let f = v[1] || '';
          if (f.length > n) return `${v[0]}.${f.substr(0,n)}`;
          while (f.length < n) f += '0';
          return `${v[0]}.${f}`
        }
        function chargesApplicable(myfield){
            if($(myfield).val()>0){
if(parseInt($(myfield).val()) > parseInt($('#due_amount').val())) {
    $(myfield).val('');
    $('#dueAmountExceedError').html('Payment amount should not greater than Due amount');
} else {
                    $('#dueAmountExceedError').html('');
                }
                // collectionFee = ($(myfield).val()*collectionFeePerc)/100;
                //collectionFee = Math.ceil(collectionFee);

                collectionFee1 = ($(myfield).val()*collectionFeePerc)/100;
                if(collectionFee1>50){
                    collectionFee = collectionFee1;
                }else{
                    collectionFee = 50;
                }

                gstValue = (collectionFee*gstPerc)/100;
                //gstValue = Math.ceil(gstValue);

                gstCollectionFee = collectionFee + gstValue;
                gstCollectionFee = toFixedTrunc(gstCollectionFee,2);
                if(gstCollectionFee<1){
                    gstCollectionFee = 1;
                }
                $(myfield).parents('div').find("#applicableCharges").text(gstCollectionFee);
                if(skipCollection===false){
                    $(myfield).nextAll(".applicableCharges").eq(0).show(1);
                }else{
                    $(myfield).nextAll(".applicableCharges").eq(0).hide(1);
                }
            }else{
                $(myfield).nextAll(".applicableCharges").eq(0).hide(1);
            }
        }
        function trimIt(currentElement){
            $(currentElement).val(currentElement.value.trim());
        }
        function blockSpecialChar(myfield, e)
        {

            var key;
            var keychar;
            if (window.event)
                key = window.event.keyCode;
            else if (e)
                key = e.which;
            else
                return true;

            keychar = String.fromCharCode(key);
            console.log(key);
            // control keys
            if ((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) ){
                return true;
            }
            // numbers
            /*else if ( (key==192) || (key==49) || (key==50) || (key==51) || (key==52) || (key==54) || (key==55) || (key==56) || (key==189) || (key==187) || (key==220) || (key==191) || (key==219) || key==221){
                //return false;
            }*/else if ((("~!@#$^&*_+?%|\/<>{}[]").indexOf(keychar) > -1)){
                return false;
            }else{
                return true;
            }
        }
        function numbersonly(myfield, e,maxlength=null)
        {
            var key;
            var keychar;
            if (window.event)
                key = window.event.keyCode;
            else if (e)
                key = e.which;
            else
                return true;

            keychar = String.fromCharCode(key);
            // control keys
            if ((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) ){
                return true;
            }
            // numbers
            else if ((("0123456789").indexOf(keychar) > -1)){
                return true;
            }
            else{
                return false;
            }
        }

        $('.dueDataListing').on('click', function () {
            $('#dueDataListing').find('.modal-body').html('');
            var dueId = $(this).data('due-id');
            var profileId = $(this).data('profile-id');
            $.ajax({
                method: 'GET',
                url: "{{route('business.edit-due-data')}}",
                data: {
                    due_id: dueId,
                    with_html:'yes',
                    query_string:"{{Request::getQueryString()}}"
                },
                success:function(res){
                    var data = res.data;
                    $('#dueDataListing').find('.modal-body').html(data);
                }
            });
        });


        $(".student-check").click(function(){
            if($(this).is(':checked')){
                $("#addDue").hide();
                $("#more").show();
                $(".outstanding").val($(this).val());
                var amount = $(this).closest('tr').find('.balance').text();
                $("#due_amount").val(amount);
            }else{
                $("#addDue").show();
                $("#more").hide();
                $(".outstanding").val('');
                $("#due_amount").val('');
            }
        });
        $(document).find("input[name=due_amount]").on('input',function(){
            dueAmountInWordlabel = $(this).parent().find('label.dueAmountInWord').eq(0);
            dueAmountInWord = price_in_words_ind($(this).val());
            if(dueAmountInWord){
                dueAmountInWordlabel.text(dueAmountInWord);
                dueAmountInWordlabel.show(1);
            }else{
                dueAmountInWordlabel.hide(1);
                dueAmountInWordlabel.text('');
            }
        });


        $('.addPayButton').on('click', function () {
            $('#dueAmountExceedError').html('');
            var collectionDate = $(this).data('collection-date');
            var dueDate = $(this).data('due-date');
            var element = $(this);

            var dp_start_date = $(this).data('due-reported-date');
            var due_reported_date = new Date(dp_start_date).getTime();
            var present = new Date().getTime();

             if(due_reported_date > present){
                dp_start_date = new Date();
            }

            $('#payment_date_datetimepicker').datetimepicker({
                maxDate: new Date(),
                minDate: dp_start_date,
                defaultDate: new Date()
            });

            $("#pay").find(".modal-body").find('label.dueAmountInWord').hide(1);
            $("#pay").find(".modal-body").find('input[name=payment_date]').on('dp.change', function(e){
                date =e.date.date();
                date = date.toString();
                if(date.length==1){
                    date = "0"+date;
                }

                month = e.date.month() +1;
                month = month.toString();
                if(month.length==1){
                    month = "0"+month;
                }
                year = e.date.year();
                string = ''+year+'-'+month+'-'+date;
                string = new Date(string); // paymentdate

                if(collectionDate){
                    dueDate = new Date(dueDate);
                    collectionDate = new Date(collectionDate);
                    const diffTime = Math.abs(currentDate - collectionDate);
                    const diffCurrentCollectionDateDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                    if(string<=collectionDate){
                        if(diffCurrentCollectionDateDays < 45 && due_reported_date <=dueDate) {
                        $("#skipcollectionpayment").val(1);
                        skipCollection = true;
                        $("#pay").find(".modal-body").find('.no-collection-fee-terms').show(1);
                        $("#pay").find(".modal-body").find('.with-collection-fee-terms').hide(1);
                        $("#pay").find(".modal-body").find('.applicableCharges').hide(1);
                        } else {
                        skipCollection = false;
                        $("#skipcollectionpayment").val(0);
                        $("#pay").find(".modal-body").find('.no-collection-fee-terms').hide(1);
                        $("#pay").find(".modal-body").find('.with-collection-fee-terms').show(1);
                        if($("#pay").find(".modal-body").find('input[name=payment_amount]').val()>0){
                            $("#pay").find(".modal-body").find('.applicableCharges').show(1);
                        }
                        }
                    }else{
                        skipCollection = false;
                        $("#skipcollectionpayment").val(0);
                        $("#pay").find(".modal-body").find('.no-collection-fee-terms').hide(1);
                        $("#pay").find(".modal-body").find('.with-collection-fee-terms').show(1);
                        if($("#pay").find(".modal-body").find('input[name=payment_amount]').val()>0){
                            $("#pay").find(".modal-body").find('.applicableCharges').show(1);
                        }
                    }
                }else{
                    dueDate = new Date(dueDate);
                    if(string<=dueDate){
                        skipCollection = true;
                        $("#skipcollectionpayment").val(1);
                        $("#pay").find(".modal-body").find('.no-collection-fee-terms').show(1);
                        $("#pay").find(".modal-body").find('.with-collection-fee-terms').hide(1);
                        $("#pay").find(".modal-body").find('.applicableCharges').hide(1);
                    }else{
                        skipCollection = false;
                        $("#pay").find(".modal-body").find('.no-collection-fee-terms').hide(1);
                        $("#pay").find(".modal-body").find('.with-collection-fee-terms').show(1);
                        if($("#pay").find(".modal-body").find('input[name=payment_amount]').val()>0){
                            $("#pay").find(".modal-body").find('.applicableCharges').show(1);
                        }
                    }
                }
            });

            if(collectionDate){
                collectionDate = new Date(collectionDate);
                //var fee_collection_date = collectionDate.setDate(collectionDate.getDate() + 45);
                if(currentDate<=collectionDate){
                    skipCollection = true;
                    $("#skipcollectionpayment").val(1);
                    $("#pay").find(".modal-body").find('.no-collection-fee-terms').show(1);
                    $("#pay").find(".modal-body").find('.with-collection-fee-terms').hide(1);
                }else{
                    skipCollection = false;
                    $("#skipcollectionpayment").val(0);
                    $("#pay").find(".modal-body").find('.no-collection-fee-terms').hide(1);
                    $("#pay").find(".modal-body").find('.with-collection-fee-terms').show(1);
                }
            }else{
                dueDate = new Date(dueDate);
                if(currentDate<=dueDate){
                    skipCollection = true;
                    $("#skipcollectionpayment").val(1);
                    $("#pay").find(".modal-body").find('.no-collection-fee-terms').show(1);
                    $("#pay").find(".modal-body").find('.with-collection-fee-terms').hide(1);
                }else{
                    skipCollection = false;
                    $("#skipcollectionpayment").val(0);
                    $("#pay").find(".modal-body").find('.no-collection-fee-terms').hide(1);
                    $("#pay").find(".modal-body").find('.with-collection-fee-terms').show(1);
                }
            }

            $("#pay").find(".modal-body").find('input[name=payment_amount]').val('');
            $("#pay").find(".modal-body").find('textarea[name=payment_note]').val('');
            $("#pay").find(".modal-body").find('input[name=payment_date]').val('');
            var dueId = $(this).data('due-id');
            var profileId = $(this).data('profile-id');

            var amount = $(this).closest('tr').find('.balance').text();
            amount = amount.trim();
            amount = amount.replace(/,/g, "");
            $("#due_amount").val(amount);
             $("#pay").find(".modal-body").find('input[name=payment_amount]').attr('max',amount);

            @if(Auth::user()->user_type==2)
                var customerNo =  $(this).data('customer-no');
                var invoiceNo =  $(this).data('invoice-no');
                $("#pay").find(".modal-body").find('input[name=customer_no]').val(customerNo);
                $("#pay").find(".modal-body").find('input[name=invoice_no]').val(invoiceNo);
            @endif

            $("#pay").find(".modal-body").find('input[name=outstanding]').val(dueId);
            $("#pay").find(".modal-body").find('input[name=business_id]').val(profileId);

            // Reset previously set datetimepicker values
            $("#pay").on("hidden.bs.modal", function () {

                var dp = $('#payment_date_datetimepicker').data('DateTimePicker');

                if(dp && typeof dp.destroy === 'function'){

                    dp.date(new Date());
                    dp.maxDate(new Date());
                    dp.minDate(new Date());
                    dp.destroy();
                }
            });

        });

        $('.dueDeleteButton').on('click', function () {
            var element = $(this);
            var dueId = $(this).data('due-id');

            $("#dueDelete").find(".modal-body").find('input[name=due_id]').val(dueId);


        });
        $('.addOutstanding').on('click', function () {
            var element = $(this);
            var profileId = $(this).data('profile-id');
            var customId = $(this).data('custom-id');

            $("#outstanding").find(".modal-body").find('input[name=business_id]').val(profileId);
            $("#outstanding").find(".modal-body").find('input[name=due_amount]').val('');
            $("#outstanding").find(".modal-body").find('textarea[name=due_note]').val('');
            $("#outstanding").find(".modal-body").find('input[name=proof_of_due]').val('');
            $("#outstanding").find(".modal-body").find('input[name=custom_id]').val(customId);
            $("#outstanding").find(".modal-body").find('label.dueAmountInWord').hide(1);
        });
        $('.editDueButton').on('click', function () {
            var dueId = $(this).data('due-id');
            var profileId = $(this).data('profile-id');
            $("#edit").find(".modal-body").find('label.dueAmountInWord').hide(1);
            $.ajax({
                method: 'GET',
                url: "{{route('business.edit-due-data')}}",
                data: {
                    due_id: dueId
                },
                success:function(res){

                    var data = res.data;
                    var personal_data = res.personal_data;
                    var dueDate = res.due_date;
                    var paidamout=res.paid_amount;
                    var balanceAmount=data.due_amount;
                    var created_at=data.created_at;
                    if( paidamout !=0 )
                    {
                         balanceAmount= data.due_amount-paidamout;
                    }
                 //console.log(res);

                    var startDate = moment(created_at, "YYYY-MM-DD");
                    var days=$('.dayslimt').val();
                    var todayDate= moment().format("YYYY-MM-DD");
                    var endDate = moment(todayDate, "YYYY-MM-DD");
                    var result = endDate.diff(startDate, 'days');

                     if(days >= result)
                     {

                    $("#edit").find(".modal-body").find('input[name=numberflag]').val('0');
                    $("#edit").find(".modal-body").find('input[name=due_amount]').prop('readonly', false);
                    $("#edit").find(".modal-body").find('input[name=company_name]').prop('readonly', false);
                    $("#edit").find(".modal-body").find('input[name=concerned_person_phone]').prop('readonly', false);
                    $("#edit").find(".modal-body").find('input[name=email]').prop('readonly', false);
                    // $("#edit").find(".modal-body").find('input[name=balance_due]').prop('readonly', true);
                    $("#edit").find(".modal-body").find('input[name=due_date]').prop('readonly', false);
                    //$("#edit").find(".modal-body").find('input[name=external_business_id]').prop('readonly', false);
                    $("#edit").find(".modal-body").find('input[name=invoice_no]').prop('readonly', false);
                }
                     else{
                        $("#edit").find(".modal-body").find('input[name=invoice_no]').prop('readonly', true);
                    $("#edit").find(".modal-body").find('input[name=numberflag]').val('1');
                    $("#edit").find(".modal-body").find('input[name=due_amount]').prop('readonly', false);
                    $("#edit").find(".modal-body").find('input[name=company_name]').prop('readonly', true);
                    $("#edit").find(".modal-body").find('input[name=concerned_person_phone]').prop('readonly', true);
                    $("#edit").find(".modal-body").find('input[name=email]').prop('readonly', true);
                    // $("#edit").find(".modal-body").find('input[name=balance_due]').prop('readonly', true);
                    $("#edit").find(".modal-body").find('input[name=due_date]').prop('readonly', false);
                    //$("#edit").find(".modal-body").find('input[name=external_business_id]').prop('readonly', true);
                     }

                    $("#edit").find(".modal-body").find('input[name=due_date]').removeAttr('value');
                    $("#edit").find(".modal-body").find('input[name=due_date]').attr('value',dueDate);
                    $("#edit").find(".modal-body").find('input[name=originalDueAmount]').val(data.due_amount).trigger("input");
                    // $("#edit").find(".modal-body").find('input[name=due_amount]').prop('readonly', false);
                    // $("#edit").find(".modal-body").find('input[name=balance_amount]').prop('readonly', false);
                    $("#edit").find(".modal-body").find('input[name=company_name]').val(personal_data.company_name);
                    $("#edit").find(".modal-body").find('input[name=concerned_person_phone]').val(personal_data.concerned_person_phone);
                    $("#edit").find(".modal-body").find('input[name=email]').val(personal_data.email);
                    $("#edit").find(".modal-body").find('input[name=due_amount]').val(data.due_amount);
                    // $("#edit").find(".modal-body").find('input[name=balance_due]').val(balanceAmount);
                    $("#edit").find(".modal-body").find('input[name=PreviousBalance]').val(balanceAmount);
                    $("#edit").find(".modal-body").find('textarea[name=due_note]').val(data.due_note);
                    $("#edit").find(".modal-body").find('input[name=invoice_no]').val(data.invoice_no);
                    $("#edit").find(".modal-body").find('input[name=outstanding]').val(data.id);
                    $("#edit").find(".modal-body").find('input[name=business_id]').val(profileId);
                    $("#edit").find(".modal-body").find('input[name=invoice_no]').val(data.invoice_no);
                    $("#edit").find(".modal-body").find('input[name=EdithiddenDuedate]').val(dueDate).trigger("input");
                    //$("#edit").find(".modal-body").find('input[name=external_business_id]').val(data.external_business_id).trigger("input");

                    if($.trim(data.proof_of_due).length>0){

                        var proofofdue = "{{config('app.url')}}storage/business/proof_of_due/";
                        var str =data.proof_of_due;
                        var res = str.replace("business/proof_of_due/", "");
                        var imglist = res.split(",");

                        var Display_Divlink="";
                        $('#ExsistingImgcount').val(imglist.length);
                        if(imglist.length >= 5)
                        {
                            $("#editFileupload").prop('disabled', true);
                            $("#editFileupload_editdue").prop('disabled', true);

                        }else{
                            $("#editFileupload").prop('disabled', false);
                            $("#editFileupload_editdue").prop('disabled', false);
                        }
                        for(var i=0;i<imglist.length;i++)
                        {
                            if( imglist[i] != ''){
                            var file_name_img="'"+imglist[i]+"'";
                            Display_Divlink+= '<span id="prooflink_'+i+'"><a id="view_proof_of_due" target="blank" href="'+proofofdue+''+imglist[i]+'">View</a> | '+
                           '<a id="delete_proof_of_due" href="javascript:void"  onclick="remove_proofof_due_file('+dueId+','+file_name_img+')" data-due-id="'+dueId+'">Delete Proof Of Due</a>'
                           +'</span><br>';
                            }
                        }
                        $("#edit").find(".modal-body").find('.proof_of_due_main_div').find("#view_proof_of_due_div").html(Display_Divlink);

                        /*var proofofdue = "{{config('app.url')}}storage/"+data.proof_of_due;
                        $("#edit").find(".modal-body").find('.proof_of_due_main_div').find('#view_proof_of_due').attr('href',proofofdue);
                        $("#edit").find(".modal-body").find('.proof_of_due_main_div').find('#delete_proof_of_due').data('due-id',dueId);*/
                        $("#edit").find(".modal-body").find('.proof_of_due_div').show(1);
                    }else{

                        $("#edit").find(".modal-body").find('.proof_of_due_main_div').find('#view_proof_of_due').attr('href','#');
                        $("#edit").find(".modal-body").find('.proof_of_due_main_div').find('#delete_proof_of_due').data('due-id','');
                        $("#edit").find(".modal-body").find('.proof_of_due_div').hide(1);
                    }

                    $('#edit').modal('toggle');

                    var balanceVal=$("#edit").find(".modal-body").find('input[name=PreviousBalance]').val();

                    if(parseInt(result) > parseInt(days))
                    {
                        $("input[name=due_amount]").on('change',function() {
                            var balanceVal=$("#edit").find(".modal-body").find('input[name=PreviousBalance]').val();
                            var due_amount=$(this).val();
                         });
                    }
                    else{

                        $("input[name=due_amount]").on('change',function() {
                            var balanceVal=$("#edit").find(".modal-body").find('input[name=PreviousBalance]').val();
                        var due_amount=$(this).val();
                        var originalDueAmount=$("#edit").find(".modal-body").find('input[name=originalDueAmount]').val();
                         });

                    }


                }
            });

        });

        $("#edit").find(".modal-body").find('input[name=due_date]').on("change",function(){
            var dueDate=$("#edit").find(".modal-body").find('input[name=due_date]').val();
            var days=$('.dayslimt').val();
            var orignalduedate=$('.EdithiddenDuedate').val();
            var originalDueAmount=$('.originalDueAmount').val();
                    var dueAmount= $('#dueamount_check').val();
                    $('#date_error').html('');
            if($("#edit").find(".modal-body").find('input[name=numberflag]').val() == 1)
            {
                var orignalduedate = moment(orignalduedate).format('YYYY-MM-DD');
                var selectedDate = moment(dueDate).format('YYYY-MM-DD');
                if(orignalduedate > selectedDate)
                {
                    $('#date_error').html('');
                    $("#edit").find(".modal-body").find('input[name=due_date]').val(dueDate).trigger("input");
                }else{
                    $('#date_error').append('<label class="error">Not allowed to select future date</label>');
                    $("#edit").find(".modal-body").find('input[name=due_date]').val(orignalduedate).trigger("input");
                }
                   $('#dueerror').html('');

                    if(parseFloat(originalDueAmount) <= parseFloat(dueAmount))
                    {
                        $('#dueerror').html('');
                        $('.editsubmit-btn').prop("disabled",false);
                    }else{

                            $('#dueerror').append('<label class="error">Amount due should be graterthan or equal to</label>');
                            $('.editsubmit-btn').prop("disabled",true);
                            return false;
                    }
            }
            else
            {
                $("#edit").find(".modal-body").find('input[name=due_date]').val(dueDate).trigger("input");
            }

        });



        $("#edit").find(".modal-body").find('#dueamount_check').on("change",function(){

                    var orignal_duedate=$('.EdithiddenDuedate').val();
                    var dueDate=$("#edit").find(".modal-body").find('input[name=due_date]').val();
                    var orignalduedate = moment(orignal_duedate).format('YYYY-MM-DD');
                    var selectedDate = moment(dueDate).format('YYYY-MM-DD');
                    var originalDueAmount=$('.originalDueAmount').val();
                    var dueAmount= $('#dueamount_check').val();
                    var balanceVal=$("#edit").find(".modal-body").find('input[name=PreviousBalance]').val();
                if(orignalduedate > selectedDate)
                {
                    if($("#edit").find(".modal-body").find('input[name=numberflag]').val() == 1)
                    {
                    $('#dueerror').html('');
                    if(parseFloat(originalDueAmount) <= parseFloat(dueAmount))
                    {
                        $('#dueerror').html('');
                        $('.editsubmit-btn').prop("disabled",false);

                    }else{
                        $('#dueerror').append('<label class="error">Amount due should be graterthan or equal to</label>');
                        $('.editsubmit-btn').prop("disabled",true);
                        return false;
                    }
                    }

                }else
                {
                    if($("#edit").find(".modal-body").find('input[name=numberflag]').val() == 1)
                    {
                    $('#dueerror').html('')
                    var originalDueAmount=$('.originalDueAmount').val();
                    var dueAmount= $('#dueamount_check').val();
                    if(parseFloat(originalDueAmount) <= parseFloat(dueAmount))
                    {
                        $('#dueerror').html('');
                        $('.editsubmit-btn').prop("disabled",false);

                    }else{
                        $('#dueerror').append('<label class="error">Amount due should be graterthan or equal to</label>');
                        $('.editsubmit-btn').prop("disabled",true);
                        return false;

                    }
                    }

                }

        });


        function remove_proofof_due_file(proof_id,file_name='',divId)
        {
            var dueId = proof_id;
            var file_name=file_name;
            var divId=divId;
                $.ajax({
                       method: 'post',
                       url: "{{route('business.business-proof-of-due-delete')}}",
                       headers: {
                           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                       },
                       data: {
                           due_id: dueId,
                           file_name:file_name,
                           div_id:divId,
                           _token: $('meta[name="csrf-token"]').attr('content')
                       }
                    }).then(function (response) {
                        if($('#ExsistingImgcount').val() >= 5)
                        {
                            $("#editFileupload").prop('disabled', true);
                        }else{
                            $("#editFileupload").prop('disabled', false);
                        }
                        var lessCountOneFile=  $('#ExsistingImgcount').val()-1;
                       $('#ExsistingImgcount').val(lessCountOneFile)
                        if(lessCountOneFile <= 5)
                        {
                            $("#editFileupload").prop('disabled', false);
                        }
                        $("#edit").find(".modal-body").find('.proof_of_due_main_div').find('#view_proof_of_due_div').find('#prooflink_'+response.div_id).attr('href','#');
                        $("#edit").find(".modal-body").find('.proof_of_due_main_div').find('#delete_proof_of_due').find('#prooflink_'+response.div_id).data('due-id','');
                        $("#edit").find(".modal-body").find("#prooflink_"+response.div_id).fadeOut(500);

                        /*$("#edit").find(".modal-body").find('.proof_of_due_main_div').find('#view_proof_of_due').attr('href','#');
                        $("#edit").find(".modal-body").find('.proof_of_due_main_div').find('#delete_proof_of_due').data('due-id','');
                        $("#edit").find(".modal-body").find('.proof_of_due_div').fadeOut(500);*/

                    }).fail(function (data) {
                        alert(data.responseJSON.message);
                    });


        }


       /* $("#edit").find(".modal-body").find('.proof_of_due_main_div #delete_proof_of_due').on('click',function(){
            var dueId = $(this).data('due-id');
                    $.ajax({
                       method: 'post',
                       url: "{{route('business.business-proof-of-due-delete')}}",
                       headers: {
                           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                       },
                       data: {
                           due_id: dueId,
                           _token: $('meta[name="csrf-token"]').attr('content')
                       }
                    }).then(function (response) {

                        $("#edit").find(".modal-body").find('.proof_of_due_main_div').find('#view_proof_of_due').attr('href','#');
                        $("#edit").find(".modal-body").find('.proof_of_due_main_div').find('#delete_proof_of_due').data('due-id','');
                        $("#edit").find(".modal-body").find('.proof_of_due_div').fadeOut(500);

                    }).fail(function (data) {
                        alert(data.responseJSON.message);
                    });

        });*/
        $('.paymentHistoryButton').on('click', function () {

            $("#paymentHistory").find(".modal-body").css('display','none');
            var element = $(this);
            var dueId = $(this).data('due-id');
            <?php if(!empty($settled_records)){?>
            var settled_records = "<?php echo $settled_records;?>";
            <?php } else {?>
             var settled_records = [];
         <?php }?>
             var businessId = $(this).data('profile-id');
             var customId = $(this).data('custom-id');

                    $.ajax({
                       method: 'post',
                       url: "{{route('business.business-payment-history1')}}",
                       headers: {
                           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                       },
                       data: {
                           due_id: dueId,
                           settled_records: settled_records,
                           businessId : businessId,
                           custom_id: customId,
                           _token: $('meta[name="csrf-token"]').attr('content')
                       }
                    }).then(function (response) {
                        if(response.noData==true){
                            $("#paymentHistory").find(".modal-body").html('');
                            $("#paymentHistory").find(".modal-body").append("<center><h4>No payment history</h4></center");
                            $("#paymentHistory").find(".modal-body").css('display','block');
                        }else{

                            $("#paymentHistory").find(".modal-body").html('');
                            $("#paymentHistory").find(".modal-body").append(response.paymentHistoryData);
                            $("#paymentHistory").find(".modal-body").css('display','block');

                        }



                    }).fail(function (data) {

                            $("#paymentHistory").find(".modal-body").html('');
                            $("#paymentHistory").find(".modal-body").html('<center><h4></h4></center>');
                            $("#paymentHistory").find(".modal-body").css('display','block');
                            alert(data.responseJSON.message);


                    });


        });
        // remove payment histopry
        $("#paymentHistory").find(".modal-body").on('click','.removePaymentHistory', function () {
            var element = $(this);

            var paymentId = $(this).data('id');
            $("#paymentHistory").find(".modal-footer").find("button[type=reset]").click();
            $("#paymentHistoryDelete").find(".modal-body").find('input[name=payment_id]').val(paymentId);

        });

        $.validator.addMethod("duedate_check", function (value, element) {
    var flag = true;
    var error_count = 0;
    $("[name^=due_date]").each(function (i, j) {
        $(this).parent('.duedate_check_errclass').find('label.error').remove();
        if ($.trim($(this).val()) == '') {
            //flag = false;
            error_count++;
            $(this).parent('.duedate_check_errclass').append('<label  id="duedate_check'+i+'-error" class="error">This field is required.</label>');
        }
    });
        var error_count_flag = error_count > 0 ? false:true;
        return error_count_flag;
    }, "");



    </script>
    <!-- End Model Pay Outstanding Amount -->
@endif
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
      $("#editFileupload_editdue").on("change",function(){
                        var error="";
                        var filecount=$("#editFileupload_editdue").get(0).files.length;
                        var previousCountImg=$('#ExsistingImgcount').val();
                        if(previousCountImg == ""){
                            previousCountImg=0;
                        }
                        var totalImgCount=parseInt(filecount)+parseInt(previousCountImg)
                        if (parseInt(totalImgCount) >=6){

                            error="<lable class='error'> | only allowed to upload a maximum of 5 files</label>";
                            $("#imgError_editdue").html(error);
                            $("#editFileupload_editdue").val("");
                            return false;
                        }else{

                            $("#editFileupload_editdue").html(error);
                        }
                    });
      $("#editFileupload").on("change",function(){
                        var error="";
                        var filecount=$("#editFileupload").get(0).files.length;
                        var previousCountImg=$('#ExsistingImgcount').val();
                        if(previousCountImg == ""){
                            previousCountImg=0;
                        }
                        var totalImgCount=parseInt(filecount)+parseInt(previousCountImg)
                        if (parseInt(totalImgCount) >=6){

                            error="<lable class='error'> | only allowed to upload a maximum of 5 files</label>";
                            $("#imgError").html(error);
                            $("#editFileupload").val("");
                            return false;
                        }else{

                            $("#imgError").html(error);
                        }
                    });
 </script>
 <style>

.filesImg input {
   outline: 2px dashed #92b0b3;
   outline-offset: -10px;
   -webkit-transition: outline-offset .15s ease-in-out, background-color .15s linear;
   transition: outline-offset .15s ease-in-out, background-color .15s linear;
   padding: 120px 0px 85px 35%;
   text-align: center !important;
   margin: 0;
   width: 100% !important;
}
.filesImg input:focus{     outline: 2px dashed #92b0b3;  outline-offset: -10px;
   -webkit-transition: outline-offset .15s ease-in-out, background-color .15s linear;
   transition: outline-offset .15s ease-in-out, background-color .15s linear; border:1px solid #92b0b3;
}

/* .filesImg:after {  pointer-events: none;
    position: absolute;
    top: 38px;
    left: 0;
    width: 50px;
    right: 0;
    height: 56px;
    content: "";
    background-image: url(https://image.flaticon.com/icons/png/128/109/109612.png);
    display: block;
    margin: 0 auto;
    background-size: 100%;
    background-repeat: no-repeat;
} */
input[type=file] {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    width: 538px !important;
}
.color input{ background-color:#f1f1f1;}
.filesImg:before {
    position: absolute;
    bottom: 31px;
    left: -26px;
    pointer-events: none;
    width: 100%;
    right: 0;
    height: 57px;
    content: "Click or drag and drop here. ";
    display: block;
    margin: 0 auto;
    font-size: 18px;
    color: #2ea591;
    font-weight: 600;
    text-transform: lowercase;
    text-align: center;
}

.voyager input[type=file] {
    padding-left: 136px !important;
    height: 135px !important;
    padding-top: 97px !important;
    width: 538px !important;
}

@media only screen and (max-width: 600px) {
   .voyager input[type=file] {
   padding-left: 3px !important;
   height: 137px !important;
   padding-top: 89px !important;
   width: 282px !important;
}
.filesImg:before {
height: 80px !important;
bottom: 58px !important;
left: -20px !important;}
}




</style>
@endsection
