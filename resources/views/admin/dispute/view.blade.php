@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' Dispute')

@section('page_header')
<h1 class="page-title">
    <i class="voyager-list"></i> View Dispute
</h1>
@stop
@section('content')
<link rel="stylesheet" type="text/css" href="{{asset('css/report.css')}}">
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

<div class="page-content container-fluid">
    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <ul>
         @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
         @endforeach
        </ul>
    </div>
@endif
    @include('voyager::alerts')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-bordered">
                <div class="panel-body">

                    <div class="report-gen">
                        <div class="table-responsive" id="my_table_{{$data->id}}">
                            <table class="table full-boder">
                                <tr>
                                    <td class="w-100 boder-t-none logo-left"><img src="{{asset('storage/'.setting('admin.icon_image'))}}" alt="" width="180"></td>
                                </tr>
                                <tr>
                                    <td align="center" class="main-title w-100 center-align">Dispute Record</td>
                                </tr>
                                <tr class="bg-color">
                                    <td class="w-100 sub-title bg-color center-align" align="center">Account Details</td>
                                </tr>

                                    <tr class="account-de d-flex">
                                        <th>Member Name</th>
                                        <th>Amount Due</th>
                                        <th>Due Date</th>
                                        <th>Date Submitted</th>
                                        <th>Proof Submitted</th>
                                        <th>Status</th>
                                        <th>Dispute Reason</th>
                                    </tr>
                                    @php
                                        if(!empty($dueRecord)){
                                            if($data->customer_type=="INDIVIDUAL"){
                                                $amountDue = $dueRecord->due_amount - General::getPaidForDue($dueRecord->id);
                                            }
                                            else{
                                                $amountDue = $dueRecord->due_amount - General::getPaidForDueOfBusiness($dueRecord->id);
                                            }
                                            $now = \Carbon\Carbon::now();
                                            $diffDays = General::diffInDays($dueRecord->due_date);
                                        }
                                    @endphp
                                    <tr class="account-de d-flex">
                                        <td>
                                            @if(!empty($dueRecord))
                                                @if(Auth::id() == $dueRecord->added_by or Auth::user()->role_id == 1 or Auth::user()->role_id == 14)
                                                    {{$dueRecord->addedBy->business_name}}
                                                @else
                                                XXXXX
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                        <td>@if(!empty($dueRecord)) Rs {{General::ind_money_format($amountDue)}} @else - @endif</td>
                                        <td>@if(!empty($dueRecord)) {{date('d-m-Y',strtotime($dueRecord->due_date))}} @else - @endif</td>
                                        <td>@if(!empty($dueRecord)) {{date('d-m-Y',strtotime($dueRecord->created_at))}} @else - @endif</td>
                                        <td>
                                        <?php
                                        if(!empty($dueRecord))
                                        {
                                            if($dueRecord->proof_of_due){
                                                echo '<a href="'.asset("storage").'/'.$dueRecord->proof_of_due.'" download  >Download</a>';
                                            }else{
                                               echo  "No";
                                            }
                                        }

                                    ?>
                                        </td>
                                        <td>
                                            @if(!empty($dueRecord))
                                                @if($diffDays>=180)
                                                    180+ days overdue
                                                @else
                                                    {{$diffDays}} days overdue
                                                @endif
                                            @else
                                                -
                                            @endif
                                         </td>
                                        <td>{{$data->reason->reason}}</td>
                                    </tr>
                                    <tr class="d-flex">
                                        <td class="w-tw-c d-flex dis-commen">
                                            <p class="f-w-6">Dispute Comments</p>
                                        </td>
                                        <td class="w-fi-c bor-left dis-commen d-flex">
                                            <p class="f-w-6" style="word-break: break-all;">{{ !empty($data->comment) ? $data->comment : 'N/A'}}</p>
                                        </td>
                                    </tr>
                                    <tr class="d-flex">
                                        <td class="w-tw-c d-flex dis-commen">
                                            <p class="f-w-6">Proof of payment</p>
                                        </td>
                                        <td class="w-fi-c bor-left dis-commen d-flex">
                                            <p class="f-w-6">
                                                @if(!empty($data->proof_of_payment))
                                                    <a target="_blank" href="{{config('app.url').Storage::url($data->proof_of_payment)}}">View/Download</a>
                                                @else
                                                    N/A
                                                @endif
                                            </p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="w-100 b-td"></td>
                                    </tr>


                            </table>
                        </div>
                        @if($data->is_open==1)
                            <div>
                                @if($data->dispute_reason_id==2)

                                    <a class="btn btn-sm btn-warning editDueButton" data-due-id="{{$data->due_id}}" data-dispute-id="{{$data->id}}" title="Update Due Record">
                                        Update Record
                                    </a>

                                @else

                                    <a href="" class="btn btn-sm btn-danger dueDeleteButton" data-toggle="modal" data-target="#dueDelete" data-due-id="{{$data->due_id}}"  data-dispute-id="{{$data->id}}" title="Delete Record">
                                       Delete Record
                                    </a>
                                @endif

                                <a href="{{route('admin.dispute-reject',$data->id)}}{{(Request::getQueryString() ? ('?' . Request::getQueryString()) : '')}}" class="btn btn-sm btn-warning view" title="Reject Dispute">
                                    Reject Dispute
                                </a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@if($data->is_open==1)
<div class="modal" id="edit" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Edit Outstanding Amount</h3>
      </div>
      <div class="modal-body">
        <form action="{{route('admin.dispute-edit-due',$data->id)}}{{(Request::getQueryString() ? ('?' . Request::getQueryString()) : '')}}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('put')
            <input type="hidden" name="due_id" value="">
            <input type="hidden" name="dispute_id" value="">
            <div class="form-group">
                <label for="due_date">*Due Date</label>
                <input type="date" class="form-control" name="due_date" value="{{date('m-d-Y', strtotime(Carbon\Carbon::now()))}}">
            </div>
            <div class="form-group">
                <label for="due_amount">*Amount Due</label>
                <input type="number" class="form-control" name="due_amount" value="" onkeypress="return numbersonly(this,event)">
            </div>
            <div class="form-group">
                <label for="due_note">Note</label>
                <textarea class="form-control" name="due_note" maxlength="300" onkeypress="return blockSpecialChar(this,event)" ></textarea>
            </div>

            <div class="form-group proof_of_due_main_div">
                <div class="proof_of_due_div">
                    <a id="view_proof_of_due" target="blank" href="">View</a> |
                    <a id="delete_proof_of_due" href="javascript:void" data-due-id="">Delete Proof Of Due</a>
                </div>
                <label for="due_note">Proof of Due</label>
                <input type="file" class="form-control" name="proof_of_due">
                <label for="contact_phone">Note: Only jpeg,bmp,png,gif,svg,pdf files are allowed</label>
            </div>
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="agree_terms">
                <label class="form-check-label" for="agree_terms">Check here to indicate that you have read and agree to the terms of the <a target="_blank" href="{{route('end-user-license-agreement')}}" target="_blank">Recordent End User License Agreement</a></label>
            </div>
            <div class="form-action text-center">
                <button type="submit" disabled class="btn btn-primary btn-blue">SUBMIT</button>

            </div>
        </form>
      </div>
      <div class="modal-footer">
      </div>
    </div>
  </div>
</div>

<div class="modal" id="dueDelete" tabindex="-1" role="dialog">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h3 class="modal-title">Delete Record</h3>
          </div>
          <div class="modal-body">
            <form action="{{ route('admin.dispute-due-delete',$data->id)}}{{(Request::getQueryString() ? ('?' . Request::getQueryString()) : '')}}" method="POST">
                @csrf
                <input type="hidden" name="due_id" value="">
                <input type="hidden" name="dispute_id" value="">

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

<script type="text/javascript">
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
            }*/else if ((("~!@#$^&*_+|\/<>{}[]").indexOf(keychar) > -1)){
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

    var editDueUrl='';
    var deleteProofOfDueUrl = '';
    @if($data->customer_type=='INDIVIDUAL')
        editDueUrl = "{{route('edit-due-data')}}";
        deleteProofOfDueUrl = "{{route('student-proof-of-due-delete')}}";
    @else
        editDueUrl = "{{route('business.edit-due-data')}}";
        deleteProofOfDueUrl = "{{route('business.business-proof-of-due-delete')}}";
    @endif
    $("input[name=agree_terms]").on('change',function(){
        if($(this).is(':checked')){
            $(this).parents().parents().parents().parents().find("button[type=submit]").attr('disabled',false);
        }else{
            $(this).parents().parents().parents().parents().find("button[type=submit]").attr('disabled',true);
        }
    });
    $('.dueDeleteButton').on('click', function () {
        var element = $(this);
        var dueId = $(this).data('due-id');
        var disputeId = $(this).data('dispute-id');
        $("#dueDelete").find(".modal-body").find('input[name=due_id]').val(dueId);
        $("#dueDelete").find(".modal-body").find('input[name=dispute_id]').val(disputeId);
    });

    $('.editDueButton').on('click', function () {
        var dueId = $(this).data('due-id');
        var disputeId = $(this).data('dispute-id');
        $.ajax({
            method: 'GET',
            url: editDueUrl,
            data: {
                due_id: dueId
            },
            success:function(res){
                var data = res.data;console.log(data);
                var dueDate = res.due_date;
                $("#edit").find(".modal-body").find('input[name=due_date]').removeAttr('value');
                $("#edit").find(".modal-body").find('input[name=due_date]').attr('value',dueDate);
                //$("#edit").find(".modal-body").find('input[name=due_date]').val(dueDate.getDate);

                $("#edit").find(".modal-body").find('input[name=due_amount]').val(data.due_amount);
                $("#edit").find(".modal-body").find('textarea[name=due_note]').val(data.due_note);
                $("#edit").find(".modal-body").find('input[name=due_id]').val(data.id);
                $("#edit").find(".modal-body").find('input[name=dispute_id]').val(disputeId);

                if($.trim(data.proof_of_due).length>0){

                    var proofofdue = "{{config('app.url')}}storage/"+data.proof_of_due;
                    $("#edit").find(".modal-body").find('.proof_of_due_main_div').find('#view_proof_of_due').attr('href',proofofdue);
                    $("#edit").find(".modal-body").find('.proof_of_due_main_div').find('#delete_proof_of_due').data('due-id',dueId);
                    $("#edit").find(".modal-body").find('.proof_of_due_div').show(1);
                }else{

                    $("#edit").find(".modal-body").find('.proof_of_due_main_div').find('#view_proof_of_due').attr('href','#');
                    $("#edit").find(".modal-body").find('.proof_of_due_main_div').find('#delete_proof_of_due').data('due-id','');
                    $("#edit").find(".modal-body").find('.proof_of_due_div').hide(1);
                }

                $('#edit').modal('toggle');
            }
        });

    });
    $("#edit").find(".modal-body").find('.proof_of_due_main_div #delete_proof_of_due').on('click',function(){
            var dueId = $(this).data('due-id');
                    $.ajax({
                       method: 'post',
                       url: deleteProofOfDueUrl,
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

        });
</script>
@endif
@endsection
