@extends('layouts_front_ib.master')
@section('content')
<!-- BEGIN CONTENT -->
<link rel="stylesheet" type="text/css" href="{{asset('front-ib/css/report.css')}}">
<div class="container-fluid" data-select2-id="13">
  
      <div class="side-body padding-top" data-select2-id="12">
        <div class="container-fluid padding-20">
          <h1 class="page-title"> <i class="voyager-person"></i> Raise dispute </h1>
        </div>
        <div id="voyager-notifications"></div>
<script src="{{asset('front-ib/js/jquery/jquery.min.js')}}"></script>
<div class="page-content container-fluid">
     @include('layouts_front_ib.error')
     @if (\Session::get('message'))
       <div class="alert alert-success">
            <span class="font-weight-semibold">{{ \Session::get('message') }}</span> 
       </div>
     @endif 
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-bordered">
                <div class="panel-body">
                    <div class="report-gen">
                        <div class="table-responsive">
                            <table class="table full-boder">
                                <tr>
                                    <td class="w-100 boder-t-none logo-left"><img src="{{asset('storage/'.setting('admin.icon_image'))}}" alt="" width="180"></td>
                                </tr>
                                <tr>
                                    <td align="center" class="main-title w-100 center-align">Raise a dispute</td>
                                </tr>
                                <tr class="bg-color">
                                    <td class="w-100 sub-title bg-color center-align" align="center">Account Details</td>
                                </tr>
                                @if($duesRecord)
                                    @php
                                        $disputeStatus = 'No';
                                        $disputeComment = 'N/A';

                                        if($lastDispute){
                                            $disputeComment = $lastDispute->comment ? $lastDispute->comment : 'N/A';
                                            $disputeStatus = $lastDispute->is_open == 1 ? 'Open' : 'Closed';
                                        }
                                    @endphp                                            
                                    <tr class="account-de d-flex">
                                        <th>Member Name</th>
                                        <th>Amount Due</th>
                                        <th>Due Date</th>
                                        <th>Date Submitted</th>
                                        <th>Proof Submitted</th>
                                        <th>Status</th>
                                        <th>Dispute status</th>
                                    </tr>
                                    <tr class="account-de d-flex">
                                        @php
                                            $amountDue = $duesRecord->due_amount - General::getPaidForDue($duesRecord->id);
                                        @endphp
                                        <td>{{$duesRecord->addedBy->business_name}}</td>

                                        <td>Rs {{General::ind_money_format($amountDue)}}</td>
                                        <td>{{date('d-m-Y',strtotime($duesRecord->due_date))}}</td>
                                        <td>{{date('d-m-Y',strtotime($duesRecord->created_at))}}</td>
                                        <td>{{$duesRecord->proof_of_due ? 'Yes' : 'No'}}</td>
                                        @php
                                            $now = \Carbon\Carbon::now();
                                            $diffDays = General::diffInDays($duesRecord->due_date);
                                        @endphp
                                        <td> 
                                            @if($diffDays>=180)
                                                180+ days overdue              
                                            @else
                                                {{$diffDays}} days overdue
                                            @endif   
                                         </td>
                                        <td>{{$disputeStatus}}</td>
                                    </tr>
                                    <tr class="d-flex">
                                        <td class="w-tw-c d-flex dis-commen">
                                            <p class="f-w-6">Dispute Comments</p>
                                            
                                        </td>
                                        <td class="w-fi-c bor-left dis-commen d-flex">
                                            <p class="f-w-6" style="word-break: break-all;">{{$disputeComment}}</p>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="w-100 b-td"></td>
                                    </tr>
                                @else
                                <tr>
                                    <td align="center" class="main-title w-100 center-align">No record found</td>
                                </tr>
                                @endif
                            </table>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-bordered">
                <div class="panel-body">
                    <form action="{{route('front-individual.store-raise-dispute',$duesRecord->id)}}" name="add_store_record" id="add_store_record" method="POST" enctype="multipart/form-data">
                        @csrf   
                        <input type="hidden" name="due_id" value="{{$duesRecord->id}}">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="contact_phone">Dispute Reason*</label>
                                <select name="dispute_reason" id="dispute_reason"  placeholder="Select reason" class="form-control" required >
                                    <option value="">Select</option>
                                    @if($disputeReasons->count())
                                        @foreach($disputeReasons as $disputeReason)
                                            <option value="{{$disputeReason->id}}" {{old('dispute_reason')==$disputeReason->id ? 'selected' : '' }} >{{$disputeReason->reason}}</option>
                                        @endforeach
                                    @endif                                       
                                </select>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="contact_phone">Dispute comment</label>
                                <textarea class="form-control" name="dispute_comment" rows="2">{{old('paid_note')}}</textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                                <div class="form-group">
                                    <label for="contact_phone">Proof Of Payment</label>
                                    <input type="file" class="form-control" name="proof_of_payment">
                                    <label for="contact_phone">Note: Only jpeg,bmp,png,gif,svg,pdf files are allowed</label>
                                </div>  
                            </div>
                           
                        <div class="col-md-12">                         
                            <div class="form-action ">
                                <button type="submit" class="btn btn-primary">SUBMIT</button>
                            </div>  
                        </div>      
                    </form>
                </div>
                
            </div>
        </div>
    </div>
</div>

</div>
</div>

<script src="{{asset('js/jquery.validate.min.js')}}"></script>
<script src="{{asset('js/jquery.additional-methods.min.js')}}"></script>
<script type="text/javascript">
    var disputeCommentMaxLength = "{{setting('admin.customer_dispute_comment_max_length') ? (int)setting('admin.customer_dispute_comment_max_length') : 100 }}";
    $('#add_store_record').validate({
        rules: {
            dispute_comment:{
                maxlength:disputeCommentMaxLength,
            },
            proof_of_payment:{
                extension: "jpeg|jpg|bmp|png|gif|svg|pdf",
            }
        },
        messages:{
            proof_of_payment:{
                extension:"please upload valid file.",
            }    
        }
    });
</script>
@endsection
