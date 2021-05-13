@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' My Records')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-list"></i> {{-- $records->display_name_plural --}}My Records
    </h1>

    

    
@stop
@section('content')

<style type="text/css">
    .form-group{
        position: relative;
        margin-bottom: 30px;
    }
    input[type=radio]{
        transform:scale(1.5);
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
    
   .skip_payment_invoices{
        display: none;
    }
    .invoices_screen{
        display: none;
    }
    
    input[type=date]{
        text-transform: uppercase;
    }
  
    @media screen and (min-width: 1200px) and (max-width: 1400px) {
    #dueBtn{padding-top: 0px !important;}
    #paymentBtn{padding-top: 0px !important;}
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
    .downloadBtn {
            float:right;
        padding-inline: 5px;
        }

        .myrecordCls {
        padding-top: 15px;}

    @media only screen and (max-width: 600px) {
        .myrecordCls {
            margin-top: -96px;
            padding-top: 0px;
        }
        .downloadBtn {
            float:none;
            padding-inline: 0px;text-align: center;
        }
    }
</style>
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
    .color input{ background-color:#f1f1f1;}
    .filesImg:before {
        position: absolute;
        bottom:  31px;
       left: -60px;
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

    input[type=file] {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    width: 534px;
}

    .voyager input[type=file] {
        padding-left: 114px !important;
        height: 135px !important;
        padding-top: 99px !important;
    }
    @media screen and (min-width: 1200px) and (max-width: 1400px) {
        .voyager input[type=file] {
            padding-left: 112px !important;

    }
    }
    @media only screen and (max-width: 600px) {
        .voyager input[type=file] {
            padding-left: 3px !important;;
        height: 130px !important;
        padding-top: 89px !important;
        width: 281px;
    }
    .filesImg:before {
        position: absolute;
        bottom: bottom: 8px;
        top: 94px;
        left: 0px;
        pointer-events: none;
        width: 100%;
        right: 0;
        height: 80px;
        content: "Click or drag and drop here. ";
        display: block;
        margin: 0 auto;
        font-size: 14px;
        color: #2ea591;
        font-weight: 600;
        text-transform: lowercase;
        text-align: center;}
    }

    @media only screen and (max-width: 600px) {
        .uploadDownBtns{
            float:none !important;
            margin-top: 46px !important;
            margin-right: 0px !important;
        }
    }
    }

    .download_img {  pointer-events: none;
    position: absolute;
    top: 38px;
    left: 0;
    width: 50px;
    right: 0;
    height: 56px;
    content: none;
    display: block;
    margin: 0 auto;
    background-size: 100%;
    background-repeat: no-repeat;
}
</style>
</section>

<style>
    #slideshow { 
        margin: 0 auto; 
        position: relative; 
        width: 100%;
        padding: 1% 1% 56.25% 1%; /*56.25 is for 16x9 resolution*/
            border-radius:20px;
            background: rgba(0,0,0,0.2);
        box-shadow: 0 0 20px rgba(0,0,0,0.6);
      box-sizing:border-box;
    }

    #slideshow > div { 
        position: absolute; 
        top: 10px; 
        left: 10px; 
        right: 10px; 
        bottom: 10px; 
    }

    #slideshow > div > img {
        width:100%;
        height:100%;
        border-radius:20px;
    }

    #slideshow:hover i, #slideshow:hover .slider-dots{
        opacity: 1;
        }

    .slidebtn {
        z-index:99;
        background:transparent;
        outline:none;
        border:none;
        -webkit-transition: all 0.3s;
        -moz-transition: all 0.3s;
        transition: all 0.3s;
        padding:0 10px 0 10px;
        }

    .slidebtn:active,
    .slidedtn:focus {
        outline:none;}
        
    .slidebtn i {
        color:#FFF;
        font-size:72px;
        opacity: 0.2;
        -webkit-transition: all 0.3s;
        -moz-transition: all 0.3s;
        transition: all 0.3s;

        }

    .prev {
        position: absolute; 
        top: 10px; 
        left: 10px; 
        bottom: 10px;
    }

    .next {
        position: absolute; 
        top: 10px; 
        right: 10px; 
        bottom: 10px;
    }


    .slider-dots {
        opacity: 0.2;
      list-style: none;
      display: inline-block;
      padding-left: 0;
      margin-bottom: 0;
      position:absolute;
      left:50%;
      bottom:3%;
      transform: translate(-50%, 0);
      z-index:99;
      -webkit-transition: all 0.3s;
        -moz-transition: all 0.3s;
        transition: all 0.3s;

    }

    .slider-dots li {
      color: #000;
      display: inline;
      font-size: 48px;
      margin-right: 5px;
      cursor:pointer;
    }

    .slider-dots li.active-dot {
      color: #fff;
    }

    #voyager-loader-popup img {
        width: 100px;
        height: 100px;
        position: absolute;
        top: 50%;
        left: 50%;
        margin-left: -50px;
        margin-right: -50px;
        -webkit-animation: spin 1s linear infinite;
        animation: spin 1s linear infinite;
    }
     #voyager-loader-popup1 img {
        width: 100px;
        height: 100px;
        position: absolute;
        top: 50%;
        left: 50%;
        margin-left: -50px;
        margin-right: -50px;
        -webkit-animation: spin 1s linear infinite;
        animation: spin 1s linear infinite;
    }

    img {
        vertical-align: middle;
    }
    #voyager-loader-popup {
        background: #80498203;;
        position: fixed;
        width: 100%;
        height: 100%;
        left: 0;
        top: 0;
        z-index: 99;
    }
    #voyager-loader-popup1 {
        background: #80498203;;
        position: fixed;
        width: 100%;
        height: 60%;
        left: 0;
        top: 0;
        z-index: 99;
    }
</style>
 
    <div class="page-content container-fluid">
        @include('voyager::alerts')
        
        <div class="row">

            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">

                        <div id="dataTable_filter" class="dataTables_filter">
                            <form action="{{route('individual-records-for-member')}}" method="get">
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

            <div class="col-md-12 container-fluid">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="responsive">
                        <div class="row">    
                        <div class="col-md-2">
                            <div class="form-group"> 
                            <label style="font-family: var(--font-rubik);font-weight: 400;">Choose by date</label> 
                                <select class="form-control"  name="date_type" required id="slectDropDwonType">
                                    <option value="">Select</option>
                                    <option value="Invoice" >Invoice date</option>
                                    <option value="Due" >Due date</option>
                                    <option value="Reported" >Reported date</option>
                                    <option value="Collections" >Collections start date</option>
                                    <option value="Payment" >Payment date</option>    
                                </select>
                                </div>
                            </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label style="font-family: var(--font-rubik);font-weight: 400;"> From Date:</label>
                                        <input type="date" name="from_date" class="form-control input-sm" id="from_date" aria-controls="dataTable">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                         <label style="font-family: var(--font-rubik);font-weight: 400;">To Date:</label>
                                        <input type="date" class="form-control input-sm" name="to_date" id="to_date">
                                    </div>
                                </div>
                                @if(Auth::user()->role_id != setting('admin.hide_export_download'))
                                <div class="col-md-2" >
                                <div class="form-group"> 
                                        <form action="{{route('export')}}" id="dueBtn">
                                            <input type="hidden" id="fromdate" name="fromdate" value="">
                                            <input type="hidden" id="todate" name="todate" value="">
                                            <input type="hidden" id="dropDownType" name="dropDownType" value="">
                                            <label style="font-family: var(--font-rubik);font-weight: 400;width:160px !important"></label>
                                            <button class=" form-control btn btn-info  btn-blue" style="width:170px !important">Due Records <i class="voyager-download"></i>
                                            </button>
                                        </form> 
                                    </div>
                                </div>
                                <div class="col-md-2" >
                                        <div class="form-group">
                                            <form action="{{route('export.payment')}}" id="paymentBtn">
                                            <input type="hidden" id="Paymenfromdate" name="paymentfromdate" value="">
                                            <input type="hidden" id="Paymentodate" name="paymenttodate" value="">
                                            <input type="hidden" id="PaymendropDownType" name="paymentdropDownType" value="">
                                            <label style="font-family: var(--font-rubik);font-weight: 400;width:160px !important"></label>
                                            <button  class=" form-control btn btn-info  btn-blue" style="width:180px !important">Invoice Payments<i class="voyager-download"></i>
                                            </button>
                                            </form>
                                        </div>
                                    </div>
                                <div class="col-md-2" >
                                        <div class="form-group">
                                            <form action="{{route('export.payment')}}" id="paymentBtn">
                                            <input type="hidden" id="Paymenfromdate" name="paymentfromdate" value="">
                                            <input type="hidden" id="Paymentodate" name="paymenttodate" value="">
                                            <input type="hidden" name="customerpayments" value="2">
                                            <input type="hidden" id="PaymendropDownType" name="paymentdropDownType" value="">
                                            <label style="font-family: var(--font-rubik);font-weight: 400;width:160px !important"></label>
                                            <button  class=" form-control btn btn-info  btn-blue" style=" width:180px !important">Customer Payments<i class="voyager-download"></i>
                                            </button>
                                            </form>
                                        </div>
                                    </div>
                                    @endif
                             </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <div class=" myrecordCls col-md-12">
            <div class="col-md-12 md-footer">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-hover fixed_headerss">
                            <thead>
                            <tr>
                                <th>Person's Name</th>
                                <th>Contact Phone</th>
                                <th>Opening Balance</th>
                                <th>Closing Balance</th>
                                <th>Custom Id</th>
                                
                                <th class="actions">{{ __('voyager::generic.actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                                @forelse($records as $data)
                                <tr>
                                    <td>{{$data->person_name}}</td>
                                    <td>{{$data->contact_phone}}</td>
                                    <td>{{General::ind_money_format(General::getTotalDueForStudentByCustomId($data->id,$userId,$data->dueid,$data->external_student_id))}}</td>
                                    <td>{{General::ind_money_format(General::getTotalDueForStudentByCustomId($data->id,$userId,$data->dueid,$data->external_student_id) - General::getTotalPaidForStudentByCustomId($data->id,$userId,$data->dueid,$data->external_student_id)) }}</td>
                                    <td>{{$customId=$data->external_student_id}}</td>
                                    
                                    
                                    
                                    
                                   {{-- <td>{{General::ind_money_format(General::getNumberOfDues($data->id,$userId))}}</td>--}}
                                    <td class="no-sort no-click bread-actions">
                                       {{-- @can('delete', $data)
                                            <div class="btn btn-sm btn-danger pull-right delete" data-id="{{ $data->{$data->getKeyName()} }}">
                                                <i class="voyager-trash"></i> 
                                            </div>
                                        @endcan --}}
                                        
                                            <a href="{{ route('my-individual-records', [$data->{$data->getKeyName()},$data->dueid]) }}" class="btn btn-sm btn-warning view">
                                                <i class="voyager-eye"></i> 
                                            </a>
                                            <a href="" class="btn btn-success addPayButton" data-toggle="modal" data-target="#updatemodal" data-due-id="{{ $data->{$data->getKeyName()} }}" data-due-profileid ="{{$data->dueid}}" data-due-customId ="{{$data->external_student_id}}" data-backdrop="static"> <i class="fa fa-money" aria-hidden="true"></i>
                                            </a>
                                             <a href="" class="btn btn-primary paymentHistoryButton" data-toggle="modal" data-target="#paymentHistory" data-due-id="{{ $data->{$data->getKeyName()} }}" data-due-profileid ="{{$data->dueid}}" data-due-customId ="{{$data->external_student_id}}" title="Payment History">
                                                <i class="fa fa-history" aria-hidden="true"></i>
                                            </a>

                                           
                                            <a href="" class="btn btn-success prrofOfDue" data-toggle="modal" data-target="#pay" data-studentid="{{$data->id}}" data-id="{{$data->dueid}}" 
                                            data-profilename="{{$data->person_name}}"  data-custid="{{$data->external_student_id}}" title="Upload Proof of due" >
                                                <i class="fa fa-upload" aria-hidden="true"></i>
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
                    {{$records->links()}}
                </div>
            </div>
        </div>



        <div class="col-md-12">
            <div class="col-md-12 md-footer">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-hover fixed_headerss">
                            <thead>
                            <tr>
                                <th>Admin Reports</th>
                                <th>Generated Date</th>
                            </tr>
                            </thead>
                            <tbody>
                                @forelse($Report_records as $data)
                                <tr>
                                    <td><?php echo '<a href='.$data["file_path"]." download> Download</a>" ; ?></td>
                                    <td>{{date('d/m/Y', strtotime($data->created_at))}}</td>
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
</div>

    <div class="modal modal-danger fade" tabindex="-1" id="delete_modal" role="dialog" >
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

        </div>
      </div>
    </div>
     <div class="modal commap-team-popup fade" tabindex="-1" id="updatemodal" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" onclick="closefunction()">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    <h4 class="modal-title">
                        Update Payments
                    </h4>
                </div>
                <div class="modal-body">
                 <form id="updatepaymentform" method="POST" action="{{route('student-store-pay-customer-level')}}">
                     @csrf
                     <div id="voyager-loader-popup1"  style="display:none">
                <img src="{{ voyager_asset('images/logo-icon.png') }}" alt="Voyager Loader"> 
                </div>
                    <input type="hidden" name="student_id" value="" id="student_id">
                    <input type="hidden" name="student_due_id" value="" id="student_due_id">
                    <input type="hidden" name="custom_id"  id="custom_id">
                      <div class="form-group payment_screen" id="payment_screen">
                        <div class="form-group">
                          <label for="due_amount">*Amount Due</label>
                           <input type="text" class="form-control" name="due_amount" id="due_amount" value="" readonly >
                        </div>

                           <p  style="font-size: 17px;color: red;">Do you want to update part payment and waive off the balance due ?</p>
                           <div style="text-align: left;">
                            <label> <input type="radio" name="colorRadio" id="colorRadio" value="0" checked onclick="payment_options_disable();">&nbsp;&nbsp;No</label>&nbsp;&nbsp;&nbsp;&nbsp;
                            <label><input type="radio" name="colorRadio" id="colorRadio" value="1" onclick="payment_options_enable();">&nbsp;&nbsp;Yes</label>
                        </div>
                        <div style="display: none;" id="payment_dropdown">
                          <div class="form-group" >
                            <label>Reason for waive off</label>
                             <select name="payment_options"class="form-control input-sm" id="payment_options" placeholder="" aria-controls="dataTable">
                                                   <option value="">Select</option>
                                                   <option value="Settlement reached">Settlement reached</option>
                                                   <option value="Balance written off">Balance written off</option>
                                                   <option value="Interest waived">Interest waived</option>
                                                   <option value="Goods returned">Goods returned</option>
                                                   <option value="Interest written off">Interest written off</option>
                                                   <option value="Wrong invoicing">Wrong invoicing</option>
                                                   <option value="Other">Other</option>
                                                </select>
                        </div>
                        <div class="form-group" id="type_of_payment_div" style="display:none">
                                <div class="form-group">
                             <label>*Please specify reason</label>
                                 <input type="text" name="type_of_payment" id="type_of_payment" value="" placeholder="Please specify type of payment" class="form-control">
                             </div>
                         </div>
                        </div>
                       
                        <div class="form-group">
                            <label for="due_amount">*Payment Amount (Should be above ₹1)</label>
                            <input type="text" class="form-control" id="payment_amount" name="payment_amount" min="2" value="" required onblur="trimIt(this);" onkeypress="return numbersonly(this,event)" oninput="chargesApplicable(this)">
                            <span id="dueAmountExceedError" style="color:red;"></span>
                        </div>
                         <div class="form-group">
                            <label for="pay_date">*Payment Date</label>
                            <input type="text" class="form-control datepicker" id="payment_date_datetimepicker" name="payment_date" required data-date-format="DD/MM/YYYY" autocomplete="off">
                        </div>
                        <div class="form-group">
                            <label for="paid_note">Note</label>
                            <textarea class="form-control" name="payment_note" id="payment_note" maxlength="300"></textarea>
                        </div>
                        <div class="form-check">
                         <input type="checkbox" class="form-check-input" name="send_updatepayment_sms">
                         <label class="form-check-label" for="agree_terms">Send update payment SMS</label>
                       </div>
                        <div class="form-check with-collection-fee-terms">
                            <label class="form-check-label" for="agree_terms">I understand that by clicking on the "Submit" button, I agree to Recordent's <a target="_blank" href="{{route('end-user-license-agreement')}}" target="_blank">End User License Agreement</a> and I agree to pay {{HomeHelper::getMyRecordsCollectionFeePercent()}}% of payment amount (Excluded tax) for using Recordent’s services. </label>
                        </div>
                     </div>  
                      <div class="form-action text-center submit_payments" id="submit_payments">
                    <button type="button" class="btn btn-primary btn-blue" onclick="submitPayments()">SUBMIT</button>
                </div>
               
               
                <div class="form-group invoices_screen" id="invoices_screen">
                    <div class="form-check with-collection-fee-terms">
                        <label class="form-check-label" for="agree_terms" style="font-size: 20px;">Do you want to adjust payment against any of the invoices?</label>
                    </div>
                    <div class="form-group">
                      
                    <table class="table table-striped table1" id="table1">
                          <tr>
                            <th class="th1">Select</th>
                            <th class="th1">Invoice Number</th>
                            <th class="th1">Due Date</th> 
                            <th class="th1">Due Amount</th>
                            <th class="th1">Balance Due</th>
                          </tr>
                        </table>
                         <p id="alert_text_green" style="display:none;color: green;"></p>
                         <p id="alert_text_red" style="display:none;color: red;"></p>
                 
                    </div>
                   
                </div>
                <input type="hidden" name=latest_amount id="latest_amount">
                <input type="hidden" id="orderArr" value="" name="orderArr">

                
                <input type="hidden" name="skipandupdatepayment" id="skipandupdatepayment" value="0">

                  <div class="form-action text-left skip_payment_invoices"  id="skip_payment_invoices">
                    <button type="submit" class="btn btn-primary btn-blue" onclick="skipandupdate()">Skip and Update Payment</button>
                    <button type="button" onclick="submitpaymentsinvoices()" class="btn btn-primary btn-blue">Submit Payments for the invoices</button>
                </div>
            </form>
                
             </div>
                
            </div>
        </div>
    </div>



        <!-- Start Model Proof of due -->
        <div class="modal commap-team-popup slider_Popup" id="pay" tabindex="-1" role="dialog" >
      <div class="modal-dialog" role="document">
        <div class="modal-content" style="height:500px;">
          <div class="modal-header">
            <h3 class="modal-title">Upload Proof of Due</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <form action="{{route('upload-proof-due-customlevel')}}" method="POST" enctype="multipart/form-data">
                @csrf
                 <div class="form-group noProof_ofDue" >
                    <label for="due_amount">Customer Name</label>
                    <input type="text" class="form-control" name="customer_name" id="customer_name" value="" readonly >
                </div><br>

                <div id="slideshow">
                </div>
                <div id="voyager-loader-popup"  style="display:none">
                <img src="{{ voyager_asset('images/logo-icon.png') }}" alt="Voyager Loader"> 
                </div>
                <input type="hidden"  name="customer_id" id="customer_id" value="">
                <input type="hidden"  name="due_id" id="due_id" value="">
                
                <div class="form-group proofofdue_check_errclass files color noProof_ofDue">
                    <label>Proof Of Due </label>
                    <image class="download_img" src="https://image.flaticon.com/icons/png/128/109/109612.png" style="pointer-events: none;
                    position: absolute;top: 38px;left: 0;width: 50px;right: 0;height: 56px;content: none;display: block;margin: 0 auto;background-size: 100%;background-repeat: no-repeat;">
                    <input type="file" id="proof_of_due" class="form-control mydrop filesImg responsive" name="proof_of_due[]" accept='.jpg,.png,.jpeg,.pdf' style="text-align:center !important;border-color: #ecf7fc;background-color: #ecf7fc;border:dashed;">
                    <p for="contact_phone">Note: Only pdf,jpeg,png files are allowed <span id="imgError" style="color:red;"></span></p>
                                
                </div>
                <div class="form-action text-center noProof_ofDue">
                    <button type="submit" class="btn btn-primary btn-blue" id="">UPLOAD</button>
                </div>
                </div>
            </form>
          </div>
          <div class="modal-footer">
          <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button> -->
          </div>
        </div>
      </div>
    </div>
<!-- End Model Proof of due -->


  <!-- Start Model upload Proof of due -->
  <div class="modal commap-team-popup " id="upload_file" tabindex="-1" role="dialog" >
      <div class="modal-dialog" role="document">
        <div class="modal-content" style="height:500px;">
          <div class="modal-header">
            <h3 class="modal-title">Upload Proof of Due</h3>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
          </div>
          <div class="modal-body">
            <form action="{{route('upload-proof-due-customlevel')}}" method="POST" enctype="multipart/form-data">
                @csrf
                 <div class="form-group" >
                    <label for="due_amount">Customer Name</label>
                    <input type="text" class="form-control" name="busin_customer_name" id="busin_customer_name" value="" readonly >
                </div><br>
                
                <input type="hidden"  name="customer_ids" id="customer_ids" value="">
                <input type="hidden"  name="due_id" id="due_id" value="">
                
                <div class="form-group proofofdue_check_errclass files color ">
                    <label>Proof Of Due </label>
                    <image class="download_img" src="https://image.flaticon.com/icons/png/128/109/109612.png" style="pointer-events: none;
                    position: absolute;top: 38px;left: 0;width: 50px;right: 0;height: 56px;content: none;display: block;margin: 0 auto;background-size: 100%;background-repeat: no-repeat;">
                    <input type="file" id="proof_of_due" class="form-control mydrop filesImg responsive" name="proof_of_due[]" accept='.jpg,.png,.jpeg,.pdf' style="text-align:center !important;border-color: #ecf7fc;background-color: #ecf7fc;border:dashed;">
                    <p for="contact_phone">Note: Only pdf,jpeg,png files are allowed <span id="imgError" style="color:red;"></span></p>
                                
                </div>
                <div class="form-action text-center">
                    <button type="submit" class="btn btn-primary btn-blue" id="">UPLOAD</button>
                </div>
                </div>
            </form>
          </div>
          <div class="modal-footer">
          <!-- <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button> -->
          </div>
        </div>
      </div>
    </div>
<!-- Start Model  upload Proof of due -->

<!-- Modal  invoice list-->
<div class="modal commap-team-popup" id="myModal2" data-backdrop="static">
    <div class="modal-dialog">
      <div class="modal-content" >
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        </div>
        <div class="modal-header"><h4 class="modal-title">Which invoice do you want to assign this proof of due against ?</h4></div>
        <div class="container"></div>
        <div class="modal-body" style="height:400px;">
                 <div class="table-responsive">

                    <table class="table table-striped">
                        <thead>
                        <tr>
                            <th></th>
                            <th>Invoice Number</th>
                            <th>Due Date</th>
                            <th>Due Amount</th>
                            <th>Balance Due</th>
                        </tr>
                        </thead>
                        <tbody id="listof_dues">
                       
                        </tbody>
                    </table>
                </div> 
        </div>
            <form action="{{route('assign-proof-duelevel')}}" method="POST" enctype="multipart/form-data">
            @csrf
                <input type="hidden" name="file_name" id="slectedProof" value="">
                <input type="hidden" value="" id="hdnSelected" name="hdnSelected">
                <div class="modal-footer">
                <a href="#" data-dismiss="modal" class="btn btn-primary btn-blue">Cancel</a>
                <button type="submit" class="btn btn-primary btn-blue" id="Assignbtn">Assign</button>
                </div>
            </form>
      </div>
    </div>
</div>
<!-- Modal invoice list-->

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
    <!-- <script src="{{asset('new/home/js/owl-carousel.min.js')}}"></script> -->
<script>
   


$(".prrofOfDue").on("click",function(){
    var dataId = $(this).attr("data-id");
    var profilename = $(this).attr("data-profilename");
    var studentid = $(this).attr("data-studentid");
    var custm_id=$(this).attr("data-custid");
    if(custm_id)
    {
        cust_id=custm_id;   
    }else{
        cust_id=null;
    }
    $("#voyager-loader-popup").css("display",'');
    $(".noProof_ofDue").css("display","none");
    $("#slideshow").css("display","none");

        $("#due_id").val(dataId);
        $("#customer_name").val(profilename);
        $("#customer_id").val(studentid);
        $("#dataTablepopup").html("");
        $.ajax({
                       method: 'post',
                       url: "{{route('student-listof-dues')}}",
                       headers: {
                           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                       },
                       data: {
                        studentid: studentid,
                        cust_id:cust_id,
                        due_id :dataId ,
                           _token: $('meta[name="csrf-token"]').attr('content')
                       }
                    }).then(function (response) {
                        
                console.log(response);
                $("#voyager-loader-popup").css("display",'none');
                var data=response.data; 
                var hHtml="";
                var invNumber="";
                var is_proofdue="No";
                var imgHtml="";
                var cls="";
                var proofofdue = "{{config('app.url')}}storage//proof_of_due/";
                var flag=1;
                var  $dataHtml="";
                var  imgHtmldata="";
                var is_flag="";
                var style='disabled';
                var business_nsme="";

                    for(var i=0; i<data.length;i++)
                    { 

                        invNumber=data[i].invoice_no;
                        if(invNumber == null)
                        {
                            invNumber="-"; 
                        }
                        var invoice_number=data[i].invoice_no;
                       is_flag= data[i].flag;
                       if(is_flag == "undefined"){
                          is_flag =0;
                       }
                       var business_id=data[i].student_id;
                       business_nsme=data[0].person_name;
                       $("#busin_customer_name").val(business_nsme);
                        if(is_flag ==1)
                        {
                            business_id=data[i].id;
                            style='enabled';   
                            invoice_number='Assign to invoice'; 
                        }else{
                            style='disabled';
                            invoice_number=data[i].invoice_no;
                    
                            if(invoice_number == null)
                            {
                                invoice_number="Invoice Number - ";   
                            }else{
                                invoice_number="Invoice Number - "+invoice_number;
                            }
                           
                                style='disabled';
                                invoice_number=data[i].id;
                                if(is_flag !=2){
                                imgHtmldata+='<tr id="checkbox_'+data[i].id+'" ><td><input id="checkboxtd_'+data[i].id+'" class="checkboxCls" type="checkbox" value="'+data[i].id+'"></td>'
                                +'<td>'+invNumber+'</td><td>'+moment(data[i].due_date).format('DD-MM-YYYY')+'</td>'
                                +'<td>'+data[i].due_amount+'</td><td>'+data[i].remaing_balance+'</td>'
                                +'</tr>';
                                }
                            }
                            var str_val =data[i].proof_of_due
                            if(str_val){
                                   var res = str_val.replace("proof_of_due/", "");
                                    var imglist = res.split(",");
                                    var imglink="";

                                    for(var j=0;j<imglist.length;j++)
                                    {
                                        var fileName=imglist[j];
                                        if( fileName != ''){
                                        var ext = fileName.split('.')[1];
                                        var cssStyle=' style=""';
                                        var tag='img';
                                        if(ext == "pdf")
                                        {
                                            cssStyle=' style="width:450px;height:272px;padding-left: 45px;"';
                                             tag='embed';
                                        }
                                        cls='';
                                        if(flag == 1)
                                        {
                                            cls="current";
                                            flag++;
                                        }
                                        
                                            imglink+= "<div class='slideitem  "+cls+"'><"+tag+" src="+proofofdue+imglist[j]+" "+cssStyle+"'><br><div class='col-md-6 uploadDownBtns' style='float:right ;margin-top: 46px;margin-right: -88px;'>"+
                                        "<a  class='btn btn-primary' href='"+proofofdue+imglist[j]+"' style='' download><i class='fa fa-download' aria-hidden='true'></i></a>  <a  class='btn btn-success uplodBtns' style=''><i class='fa fa-upload' aria-hidden='true'></i></a> <input type='hidden' id='hiddenCustId' value='"+business_id+"'><a  data-toggle='modal' data-target='#upload_file' class='btn btn-success uploadCls' style='display:none'>Upload</a></div>"+
                                        "<div class='col-md-6' style='float:left;margin-top: 46px;'><a  data-value='"+imglist[j]+"' class='asign_prrof_due btn btn-primary' "+style+">"+invoice_number+"</a><a data-toggle='modal' href='#myModal2' class='btn btn-primary assign_proofof_due' style='display:none;' >"+invoice_number+"</a><input type='hidden'id='proof_val' value='"+imglist[j]+"'></div></div>";
                                    }
                                       
                                    }
        
    
                                if(data[i].proof_of_due != null && (data[i].proof_of_due != ""))
                                {
                                    is_proofdue="Yes";
                                }
                                imgHtml+=imglink;
                        }
                        
                    }
                    $("#slideshow").html(imgHtml);
                    $("#listof_dues").html(imgHtmldata);
                    prepre_slide_button();
                    $(".asign_prrof_due").on("click",function(){
                        $("#Assignbtn").css("display","none");
                        // var proofof_due_file=$("#is_proof_val").val();

                        var proofof_due_file=$(this).data("value");

                        var studen_id=$("#hiddenCustId").val();
                        $("#slectedProof").val('proof_of_due/'+proofof_due_file);
                        $(".assign_proofof_due")[0].click();
                        $(".slider_Popup").removeClass("shown");
                        $(".slider_Popup").css("display","none");

                        $.ajax({
                       method: 'post',
                       url: "{{route('assigned-file-check-isexist')}}",
                       headers: {
                           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                       },
                       data: {
                        proofof_due_file: proofof_due_file,
                        studen_id:studen_id,

                           _token: $('meta[name="csrf-token"]').attr('content')
                       }
                    }).then(function (response) {
                        console.log(response);
                        var data=response.data;
                        if(data.length == 0)
                        {
                            $("#checkboxtd_"+data[i]).prop('checked', false);
                             $("#checkboxtd_"+data[i]).prop('disabled', false);
                             $("#Assignbtn").css("display","");
                        }else{
                            for(var i=0;i<data.length;i++)
                        {
                            $("#checkboxtd_"+data[i]).prop('checked', true);
                             $("#checkboxtd_"+data[i]).prop('disabled', true);
                        }
                        $("#Assignbtn").css("display","");
                        }
                           
                        
        
                        

                            alert(data.responseJSON.message);
                        });


                            });
                            $(".uplodBtns").on("click",function(){
                                var cutmId=$("#hiddenCustId").val();
                               // var Custname=$("#hiddenCustname").val();
                                $("#customer_ids").val(cutmId);
                                // $("#busin_customer_name").val(Custname);
                                $(".uploadCls")[0].click();
                                
                            })
                        var slectedck=[];
                        function removeNumber(arr, num){
                            return arr.filter(el => {return el !== num});
                        }


                        $(".checkboxCls").on("click",function(){
                            var id=$(this).val();
                            if ($(this).prop('checked')==true)
                            {
                                slectedck.push(id);
                            }
                            else{
                                slectedck = removeNumber(slectedck, id);
                            
                            }
                            $('#hdnSelected').val(slectedck.join(","));
                        })
                        if(is_proofdue == "No")
                        {
                            $("#slideshow").css("display","none");
                            $(".noProof_ofDue").css("display","");
                            $(".proofDuebtn").css("display","none");
                        }else{
                            $("#slideshow").css("display","");
                            $(".noProof_ofDue").css("display","none");
                            $(".proofDuebtn").css("display","");
                        }
                    $("#dataTablepopup").html(hHtml);
                        }).fail(function (data) {
                            alert(data.responseJSON.message);
                        });

    });
    $("#proof_of_due").on("change",function(){
                   var $fileUpload = $("input[type='file']");
                   var error="";
                   if (parseInt($fileUpload.get(0).files.length) >=6){
                      
                    error="<lable class='error'> | only allowed to upload a maximum of 5 files</label>";
                      $("#imgError").html(error);
                      $("#proof_of_due").val("");
                   }else{
                    
                    $("#imgError").html(error);
                   }
            });

    $("#slectDropDwonType").on("change",function(){
       var dropDwonVal =$("#slectDropDwonType").val(); 
       $("#dropDownType").val(dropDwonVal);
       $("#PaymendropDownType").val(dropDwonVal);
      
       if(dropDwonVal == "Payment"){
        $("#dueBtn").css('display','none');
        $("#paymentBtn").css('display','');
        
       }else if(dropDwonVal == ""){
        $("#paymentBtn").css('display','');
        $("#dueBtn").css('display','');
        $("#from_date").val("");
        $("#to_date").val("");
        $("#Paymenfromdate").val("");
        $("#Paymentodate").val("");
        $("#fromdate").val("");
        $("#todate").val("");
       }else{
        $("#paymentBtn").css('display','none');
        $("#dueBtn").css('display','');
       }
    })

    $("#from_date").on("change",function(){
       var from_date =$("#from_date").val();
       $("#fromdate").val(from_date);
       $("#Paymenfromdate").val(from_date);
    })
    $("#to_date").on("change",function(){
       var to_date =$("#to_date").val();
       $("#todate").val(to_date);
       $("#Paymentodate").val(to_date);
       
    })
</script>

<script>
    function prepre_slide_button()
    {
        $("#slideshow > div:gt(0)").hide();

    var buttons = "<button class=\"slidebtn prev\"><i class=\"fa fa-chevron-circle-left\" style=\"color:blue;\"></i></button><button class=\"slidebtn next\"><i class=\"fa fa-chevron-circle-right\" style=\"color:blue;\"></i></button\>";

    var slidesl = $('.slideitem').length
    //var slidesl = 6;
    var d = "<li class=\"dot active-dot\">&bull;</li>";
    for (var i = 1; i < slidesl; i++) {
      d = d+"<li class=\"dot\">&bull;</li>";
    }   
    var dots = "<ul class=\"slider-dots\">" + d + "</ul\>";

    $("#slideshow").append(dots).append(buttons);
    var interval =0;

    function intslide(func) {
        // if (func == 'start') { 
        // interval = setInterval(slide, 3000);
        // } else {
        //  clearInterval(interval);        
        //  }
    }

    function slide() {
            sact('next', 0, 1200);
    }
        
    function sact(a, ix, it) {
            var currentSlide = $('.current');
            var nextSlide = currentSlide.next('.slideitem');
            var prevSlide = currentSlide.prev('.slideitem');
                var reqSlide = $('.slideitem').eq(ix);

                var currentDot = $('.active-dot');
              var nextDot = currentDot.next();
              var prevDot = currentDot.prev();
                var reqDot = $('.dot').eq(ix);
            
            if (nextSlide.length == 0) {
                nextDot = $('.dot').first();
                nextSlide = $('.slideitem').first();
                }

            if (prevSlide.length == 0) {
                prevDot = $('.dot').last();
                prevSlide = $('.slideitem').last();
                }
                
            if (a == 'next') {
                var Slide = nextSlide;
                var Dot = nextDot;
                }
                else if (a == 'prev') {
                    var Slide = prevSlide;
                    var Dot = prevDot;
                    }
                    else {
                        var Slide = reqSlide;
                        var Dot = reqDot;
                        }

            currentSlide.fadeOut(it).removeClass('current');
            Slide.fadeIn(it).addClass('current');
            
            currentDot.removeClass('active-dot');
            Dot.addClass('active-dot');
    }   

    $('.next').on('click', function(e){
            intslide('stop');                       
            sact('next', 0, 400);
            intslide('start');
            return false;                       
        });//next

    $('.prev').on('click', function(){
            intslide('stop');                       
            sact('prev', 0, 400);
            intslide('start');
            return false;                       
        });//prev

    $('.dot').on('click', function(){
            intslide('stop');
            var index  = $(this).index();
            sact('dot', index, 400);
            intslide('start');                      
        });

    }

    $(document).ready(function(){


        //prev
    //slideshow
    });


</script>
<script type="text/javascript">
  function trimIt(currentElement){
      $(currentElement).val(currentElement.value.trim());
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
        if ((key==null) || (key==0) || (key==8) || (key==9) || (key==13) || (key==27) )
            return true;
        // numbers
        else if ((("0123456789").indexOf(keychar) > -1)){
          return true;
        }
        else{
          return false;
        }
  }
  function chargesApplicable(myfield){
    if($(myfield).val()>0){
         if ($('input[name=colorRadio]:checked').val() == "1") {
            if(parseInt($(myfield).val()) >= parseInt($('#due_amount').val())) {
                $(myfield).val('');
                $('#dueAmountExceedError').html('Payment amount should be less than Due amount');
            } else {
                $('#dueAmountExceedError').html('');
            }
       } else { 
            if(parseInt($(myfield).val()) > parseInt($('#due_amount').val())) {
                $(myfield).val('');
                $('#dueAmountExceedError').html('Payment amount should not greater than Due amount');
            } else {
                $('#dueAmountExceedError').html('');
            }
          }  
    
            
  }
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
</script>
<script type="text/javascript">
    $(document).ready(function() {
        $('body').on('focus','.datepicker',function(){
        $(this).datetimepicker();
      });
    })      
</script>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
 <script src="{{asset('js/jquery.validate.min.js')}}"></script>
<script type="text/javascript">

var updatepaymentform = $('#updatepaymentform');
   updatepaymentform.validate({
      ignore: '',

        rules: {

            payment_note: {
                // required:true
                // maxlength: 5
            },
            payment_amount: {
                required:true,
                min: 1
            }
           

        }
    });
</script>
<script type="text/javascript">
    $("#payment_options").on('change',function(){
        $("#type_of_payment_div").find('input').val('');
        // if($(this).val()==10 || $(this).val()==11){
        if($('#payment_options :selected').text()== "Other"){

            $("#type_of_payment_div").show(1);
            $("#type_of_payment_div").find('input').attr('required','required');

        }else{
            $("#type_of_payment_div").hide(1);
            $("#type_of_payment_div").find('input').removeAttr('required');
        }
    });
</script>
<script type="text/javascript">
    
 $('.addPayButton').on('click', function () {
      var profileId = $(this).data('due-id');
      var  DueId= $(this).data('due-profileid');
      var userId = {{$userId}};
      var customId = $(this).data('due-customid');
      
         
         $.ajax({
                    method: 'GET',
                    url: "{{route('get-total-Due-For-Student-By-CustomId')}}",
                    data: {
                        studentID: profileId,
                        dueId:DueId,
                        added_by:userId,
                        custom_id: customId
                    },
                    success:function(res){
                        var data = res.data;
                         $("#updatemodal").find(".modal-body").find('input[name=due_amount]').val(res);

                    },
                    error:function(error){
                        // alert(JSON.stringify(error));
                        // console.log(res);

                    }
                });
                
                    
             
      
    $("#updatemodal").find(".modal-body").find('input[name=student_id]').val(profileId);
    $("#updatemodal").find(".modal-body").find('input[name=student_due_id]').val(DueId);
     $("#updatemodal").find(".modal-body").find('input[name=custom_id]').val(customId);
});

</script>
<script>
function submitPayments() {
    var payment_amount =$('#payment_amount').val();
      var due_amount =$('#due_amount').val();
      var payment_options = $('#payment_options').val();
      var profileId = $('#student_id').val();
      var DueId = $('#business_due_id').val();
      var customId = $('#custom_id').val();
      
      if(updatepaymentform.valid()==true){
          if(Number(payment_amount) < Number(due_amount) && payment_options==''){
            $("#voyager-loader-popup1").css("display",'');

          // $('#skip_payment_invoices').show();
            $('#invoices_screen').show();
            $('#submit_payments').hide();
            $('#payment_screen').hide();
            $.ajax({
                    method: 'GET',
                    url: "{{route('get-student-dues-customer-level')}}",
                    data: {
                        studentID: profileId,
                        dueId: DueId,
                        custom_id: customId
                        
                       
                    },
                    success:function(res){
                          $("#voyager-loader-popup1").css("display",'none');
                          $('#skip_payment_invoices').show();
                       var data = res.data;
                        // console.log(res);
                            
                             var count = 1;
                             var count_id = 1;
                             var unpaid = 1;
                             var unpaid_id = 1;

                         for(i=0; i<res.length; i++){
                            if(res[i].invoice_no == null)   {
                                res[i].invoice_no = '-';
                            }
                               if((res[i].due_amount-res[i].totalPaid)!=0){
                            $("#table1").append("<tr class='row-select'> <td> <input class='invoice_class' type='checkbox' onchange=addChecboxtoArray(this) value="+res[i].id+" name=checkbox[] id='checkbox"+(count++)+"' onclick=checked_function("+res[i].id+",'checkbox"+(count_id++)+"',"+(res[i].due_amount-res[i].totalPaid)+")></td><td class=res_id> "+res[i].invoice_no+" </td> <td class=res_due_date> "+moment(res[i].due_date).format('DD-MM-YYYY')+" </td> <td class=res_due_amount> "+res[i].due_amount+" </td> <td class=res_unpaid_amount value="+(res[i].due_amount-res[i].totalPaid)+" id='unpaid"+(unpaid++)+"' > "+(res[i].due_amount-res[i].totalPaid));
                            var unpaid1 = 'unpaid'+(unpaid_id++);
                           
                          }
                      }
                    },
                    error:function(error){
                    }
                });
      } else {
          document.getElementById("updatepaymentform").submit();
         }
       }   
}
</script>
<script type="text/javascript">
    function skipandupdate(){
       $('#skipandupdatepayment').val(1);
    }
</script>
<script type="text/javascript">
    function submitpaymentsinvoices(){
        var latest_amount= document.getElementById("latest_amount").value;
        var checked_values = document.getElementById("orderArr");
        if (typeof checked_values !== "undefined" && checked_values.value == '') {
             document.getElementById("alert_text_red").style.display = "block";
              document.getElementById("alert_text_red").innerHTML = "Please select the invoices";
        } else if(parseInt(latest_amount)>0) {
          document.getElementById("alert_text_red").style.display = "block";
              document.getElementById("alert_text_red").innerHTML = "Please select the invoices to adjust the amount of Rs "+parseInt(latest_amount);
        } 
        else {
           document.getElementById("updatepaymentform").submit(); 
        }
}
</script>
<script type="text/javascript">
    function payment_options_enable(){
      document.getElementById('payment_dropdown').style.display ='block';
      document.getElementById("payment_amount").value = '';
      document.getElementById("payment_options").value = '';
    }
    function payment_options_disable(){
      document.getElementById('payment_dropdown').style.display = 'none';
      document.getElementById("payment_amount").value = '';
      document.getElementById("payment_options").value = '';
    }
</script>
<script type="text/javascript">
  function closefunction(){
    window.location.reload();
  }
  
function checked_function(clicked_id,checkbox_id,checked_amount) {
   
    var payment_amount =$('#payment_amount').val();
    var latest_amount = $('#latest_amount').val();
    var checkBox = document.getElementById(checkbox_id);
    document.getElementById("alert_text_green").style.display = "none";
    document.getElementById("alert_text_red").style.display = "none";
    
      if (checkBox.checked == true){
         if(latest_amount != ''){
        var payment_amount = latest_amount;
      }

                     if(checked_amount<=payment_amount){
                        document.getElementById(checkbox_id).checked = true;
                        document.getElementById("alert_text_green").style.display = "block";
                        document.getElementById("alert_text_red").style.display = "none";
                        document.getElementById("alert_text_green").innerHTML  ="Amount of Rs " + checked_amount + " will be adjusted from Rs " +payment_amount + " Pending Amount is " +(payment_amount-checked_amount) ;
                        var latest_amount = payment_amount-checked_amount;
                         $("#updatemodal").find(".modal-body").find('input[name=latest_amount]').val(latest_amount);
                         if(latest_amount == 0){
                                    $('input.invoice_class:not(:checked)').attr('disabled', 'disabled');

                         }else {
                                     $('input.invoice_class').removeAttr('disabled');
                         }
                     }  
                     else  {
                        var latest_amount = payment_amount-checked_amount;
                        $("#updatemodal").find(".modal-body").find('input[name=latest_amount]').val(latest_amount);
                        document.getElementById("alert_text_green").style.display = "none";
                        document.getElementById("alert_text_red").style.display = "block";
                        document.getElementById("alert_text_red").innerHTML  ="Amount of Rs "+ payment_amount + " will be adjusted from the amount of Rs" + checked_amount ;
                        if(latest_amount <= 0){
                                    $('input.invoice_class:not(:checked)').attr('disabled', 'disabled');

                         }else {
                                     $('input.invoice_class').removeAttr('disabled');
                         }
                     }
    


    } else {
        $('input.invoice_class').removeAttr('disabled');
        var latest_amount = parseInt(latest_amount)+parseInt(checked_amount);
        $("#updatemodal").find(".modal-body").find('input[name=latest_amount]').val(latest_amount);
    }
}

 
</script>
<script type="text/javascript">
          function removeItem(array, item){
             for(var i in array){
                    if(array[i]==item){
                        array.splice(i,1);
                        break;
                    }
                }
            }     
            function addChecboxtoArray(el){
                var orderArr = $('#orderArr').val();
                if(orderArr != ''){
                    orderArr = JSON.parse($('#orderArr').val());
                }else{
                    orderArr = [];
                }
                if($(el).is(':checked')){
                    orderArr.push($(el).val());
                }else{
                    removeItem(orderArr,$(el).val());
                }
                var array = JSON.parse("[" + orderArr + "]");
                $("#orderArr").val(JSON.stringify(array));
            }
</script>

<script type="text/javascript">
    $('.paymentHistoryButton').on('click', function () {
            $("#paymentHistory").find(".modal-body").css('display','none');
            var element = $(this);
            var profileId = $(this).data('due-id');
             var customId = $(this).data('due-customid');
                    $.ajax({
                       method: 'post',
                       url: "{{route('student-payment-history')}}",
                       headers: {
                           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                       },
                       data: {
                           profileId: profileId,
                           custom_id:customId,
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
</script>
@endsection