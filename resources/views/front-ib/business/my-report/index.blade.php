<link rel="stylesheet" type="text/css" href="{{asset('front-ib/css/report.css')}}">
<div class="page-content container-fluid">
     
    <div class="">
        <div class="col-md-12">
            @include('layouts_front_ib.error')
             @if (\Session::get('message'))
               <div class="alert alert-success">
                    <span class="font-weight-semibold">{{ \Session::get('message') }} </span> 
               </div> 
             @endif 
            <div class="panel panel-bordered">
                <div class="panel-body">
                    @forelse($records as $data)
                    @php
                        $reportNumber = '';
                        $reportNumber = \Carbon\Carbon::now()->format('dmY');
                        $reportNumber.='CB';
                        $reportNumber.=rand(1000000,9999999);
                    @endphp
                    <div class="report-gen">
                        <a href="{{route('front-business.report.download',['c_id'=>$data->id,'r_n'=>$reportNumber])}}" class="btn pull-right downloadAsPdf btn-to-action" style="margin-top: 15px;">Download</a>
						
                        <div class="table-responsive">
                            <table class="table full-boder">
                                <tr>
                                    <td class="w-100 boder-t-none logo-left"><img src="{{asset('storage/'.setting('admin.icon_image'))}}" alt="" width="180"></td>
                                </tr>
                                <tr>
                                    <td align="center" class="main-title w-100 center-align">Recordent Business Report</td>
                                </tr>
                                <tr class="d-flex justify-content-between boder-t1">
                                    <td class="w-60 boder-t-none"></td>
                                    <td class="w-40 boder-t-none">
                                        <div class="d-flex report-nu-date margin-h">
                                            <p class="f-w-6">Report Number</p>
                                            
                                            <p>{{$reportNumber}}</p>
                                        </div>
                                        <div class="d-flex report-nu-date margin-h">
                                            <p class="f-w-6">Date &amp; Time Stamp</p>
                                            <p>{{$dateTime}}</p>
                                        </div>
                                    </td>
                                </tr>
                                <tr class="bg-color">
                                    <td class="w-100 sub-title bg-color center-align" align="center">Business Details</td>
                                </tr>
                                <tr class="d-flex justify-content-between">
                                    <td class="w-60  boder-t-none">
                                        <div class="d-flex persona-det margin-h">
                                            <p class="f-w-6">Company Name</p>
                                            <p>{{$data->company_name}}</p>
                                        </div>
                                        
                                    </td>
                                    <td class="w-40 justify-content-between bor-left boder-t-none">
                                        <div class="d-flex report-nu-date margin-h">
                                            <p class="f-w-6">GSTIN / UDISE Number</p>
                                            <p>
                                                @if(!empty($data->unique_identification_number))
                                                    @if(substr($data->unique_identification_number,0,2)!='98')
                                                    {{$data->unique_identification_number}}
                                                    @else
                                                    XXXXXXXXXXXXXXX
                                                    @endif

                                                @else
                                                    Not Reported
                                                @endif
                                            </p>
                                        </div>
                                        
                                    </td>
                                </tr>
                                <tr class="bg-color">
                                    <td class="w-100 sub-title bg-color center-align" align="center">Summary</td>
                                </tr>
                                <tr class="d-flex summary-de">
                                    <td class="f-w-6">Total Members Submitted</td>
                                    <td align="center">{{$data->summary_totalMemberReported}}</td>
                                    <td class="f-w-6">Total Dues Submitted</td>
                                    <td align="center">Rs {{General::ind_money_format($data->summary_totalDueReported)}}</td>
                                    <td class="f-w-6">Total Dispute</td>
                                    <td class="center-align" align="center">{{$data->totalDispute}}</td>
                                </tr>
                                <tr>
                                    <td class="w-100 b-td"></td>
                                </tr>
                                <tr class="d-flex overdue-status">
                                    <td class="f-w-6">Overdue status</td>
                                    <td align="center">0-29 days</td>
                                    <td align="center">30-59 days</td>
                                    <td align="center">60-89 days</td>
                                    <td align="center">90-119 days</td>
                                    <td align="center">120-149 days</td>
                                    <td align="center">150-179 days</td>
                                    <td class="center-align" align="center">180+ days</td>
                                </tr>
                                <tr class="d-flex overdue-status">
                                    <td class="f-w-6">Total Accounts</td>
                                    <td align="center">{{$data->summary_overDueStatus0To29Days}}</td>
                                    <td align="center">{{$data->summary_overDueStatus30To59Days}}</td>
                                    <td align="center">{{$data->summary_overDueStatus60To89Days}}</td>
                                    <td align="center">{{$data->summary_overDueStatus90To119Days}}</td>
                                    <td align="center">{{$data->summary_overDueStatus120To149Days}}</td>
                                    <td align="center">{{$data->summary_overDueStatus150To179Days}}</td>
                                    <td class="center-align" align="center">{{$data->summary_overDueStatus180PlusDays}}</td>
                                </tr>
                                <tr class="bg-color">
                                    <td class="w-100 sub-title bg-color center-align" align="center">Account Details</td>
                                </tr>
                                @if($data->accountDetails->count())
                                <?php $j=0;?>
                                    @foreach($data->accountDetails as $accountDetail)
                                        @php
                                            $disputeDetail = $accountDetail->dispute->last();
                                            $raiseDispute=true;
                                            $disputeStatus = 'No';
                                            $disputeComment = 'N/A';
                                            if($disputeDetail){
                                                $disputeComment = $disputeDetail->comment ? $disputeDetail->comment : 'N/A';
                                                $disputeStatus = $disputeDetail->is_open == 1 ? 'Open' : 'Closed';
                                                if($disputeDetail->is_open == 1){
                                                    $raiseDispute = false;
                                                }
                                            }
                                        @endphp
                                        <tr class="account-de d-flex">
                                            <th>Member Name <span class="collection_date_info" data-toggle="tooltip" data-placement="top" title="A member is any business entity that has signed up with Recordent to use the services."><i class="fa fa-info-circle"></i></span></label></th>
                                            <th>Amount Due</th>
                                            <th>Due Date</th>
                                            <th>Date Submitted</th>
                                            <th>Proof Submitted</th>
                                            <th>Status</th>
                                            <th>Dispute status</th>
                                        </tr>
                                        <tr class="account-de d-flex">
                                            @php
                                                $amountDue = $accountDetail->due_amount - General::getPaidForDueOfBusiness($accountDetail->id);
                                            @endphp
											<input type="hidden" id="amountduevalidate" value="{{$amountDue}}">
                                            <td>{{$accountDetail->addedBy->business_name}}</td>
                                             <?php if($settled_records!=null){
                                             foreach ($settled_records as $key => $value) {
                                             if($value==$data->id){?>
                                             <td>0</td>
                                             <?php } else {?>
                                                 <td>Rs {{General::ind_money_format($amountDue)}}</td>
                                               <?php }}} else {?>
                                                 <td>Rs {{General::ind_money_format($amountDue)}}</td>
                                               <?php }?>
                                            <td>{{date('d-m-Y',strtotime($accountDetail->due_date))}}</td>
                                            <td>{{date('d-m-Y',strtotime($accountDetail->created_at))}}</td>
                                            <td>
                                                <?php
                                                if($accountDetail->proof_of_due){
                                                    echo '<a href="#" onclick="downloadImgs('.$j.')" >Download</a>';
                                                    //$floder_name=explode("/",$accountDetail->proof_of_due); 
                                                    $imgProof=str_replace("business/proof_of_due/","",$accountDetail->proof_of_due);
                                                    $imgList=explode(",",$imgProof);
                                                    $i=0;
                                                    $Idlist='';
                                                    foreach($imgList as $img_name){
                                                        echo '<a style="visibility: hidden"  id="'.$j.'prrofId_'.$i.'" href="'.asset("storage").'/business/proof_of_due/'.$img_name.'" download  >Download</a>'. "  ";
                                                        $Idlist.=$i++.",";
                                                    }
                                                    echo '<input type="hidden" id="downloadvalues_'.$j.'"  value="'.$Idlist.'">';
                                                }else{
                                                   echo  "No";
                                                }
                                                ?>

                                               
                                            </td>
                                            @php
                                                $now = \Carbon\Carbon::now();
                                                $diffDays = General::diffInDays($accountDetail->due_date);
                                            @endphp
                                            <td> 
                                                @if($diffDays>=180 )
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
                                        
                                        <tr class="d-flex justify-content-center boder-t1">
                                            <td class="boder-t-none two-btn-sath">
                                                 <?php if($settled_records!=null){ foreach ($settled_records as $key => $value) {
                                                if($value==$data->id){
                                                     $amountDue=0;

                                                   }} }?>
                                                @if($amountDue>0)
                                                    <a href="javascript:void(0)" data-due-id="{{$accountDetail->id}}" data-due-amount="{{$amountDue}}" class="btn-to-action makePayment" data-toggle="modal" data-target="#pay">Make Payment</a>

                                                    @if($raiseDispute)
                                                    <a href="{{route('front-business.raise-dispute',$accountDetail->id)}}" class="btn-to-action">Raise Dispute</a>
                                                    @endif
                                                    <br><small id="make_payment_help" class="form-text text-muted">(Payment will be credited in the member's bank account within 24 hours)</small>
                                                @endif
                                                
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td class="w-100 b-td"></td>
                                        </tr>
                                        <?php $j++;?>
                                    @endforeach
                                @endif
                                
                                <tr>
                                    <td align="center" class="main-title w-100 center-align">End of Report</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                    @empty
                    <div><center>No report found</center></div>
                    @endforelse

                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal" id="pay" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">make Payment</h3>
      </div>
      <div class="modal-body">
        <form action="{{ route('front-business.my-records-make-payment') }}" method="POST">
            @csrf   
            <input type="hidden" name="due_id" value="">
            <input type="hidden" name="due_amount" id="due_amount" value="">
                                
            <div class="form-group">
                <label for="due_amount">*Amount (Minimum: Rs. 1)</label>
                <input type="text" class="form-control" name="pay_amount" min="1" oninput="chargesApplicable(this)" value="" required onkeypress="return numbersonly(this,event)">
				<span id="dueAmountExceedError" style="color:red;"></span>
            </div>                          
            <div class="form-check">
                <input type="checkbox" class="form-check-input" name="agree_terms">
                <label class="form-check-label" for="agree_terms">Check here to indicate that you have read and agree to the terms of the <a target="_blank" href="{{route('end-user-license-agreement')}}" target="_blank">Recordent End User License Agreement</a></label>
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

@include('partials.hot-jar-tracking')

<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.22/pdfmake.min.js"></script>
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/0.4.1/html2canvas.min.js"></script>

<script type="text/javascript">
function chargesApplicable(myfield){
            $('#dueAmountExceedError').html('');   
            if($(myfield).val()>0){
				if(parseInt($(myfield).val()) > parseInt($('#due_amount').val())) {
					$(myfield).val('');
					$('#dueAmountExceedError').html('Payment amount should not greater than Due amount');
				}
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
    $("input[name=agree_terms]").on('change',function(){
        if($(this).is(':checked')){
            $(this).parents().parents().parents().parents().find("button[type=submit]").attr('disabled',false);
        }else{
            $(this).parents().parents().parents().parents().find("button[type=submit]").attr('disabled',true);
        }
    });
    $('.makePayment').on('click', function () { 
        var element = $(this);
        var dueId = $(this).data('due-id');
        var dueamount = $(this).data('due-amount');
        $("#pay").find(".modal-body").find('input[name=due_id]').val(dueId);     
         $("#pay").find(".modal-body").find('input[name=due_amount]').val(dueamount);     
               
    });
    $('#pay').find("button[type=reset]").on('click',function(){
      $("#pay").find(".modal-body").find('input[name=due_id]').val('');
      $("#pay").find(".modal-body").find('input[name=pay_amount]').val('');
      $("#pay").find(".modal-body").find("input[name=agree_terms]").prop('checked',false);
      $("#pay").find(".modal-body").find("button[type=submit]").attr('disabled',true);
    });

    $("body").on("click", ".downloadAsPdf", function (e) {
        var alertType = "info";
        var alertMessage = "Your download will start soon";
        var alerter = toastr[alertType];
        alerter(alertMessage);
        
    });
    $(document).ready(function(){
    $('.collection_date_info').tooltip('toggle');
    $('.collection_date_info').tooltip('hide');
});

function downloadImgs(id){
    var imgnumberList=$("#downloadvalues_"+id).val();
    var  strlist =imgnumberList;
    var latest = strlist.replace(/,(?=\s*$)/, '');
    var str = latest;
    var res = str.split(",");
    for(var i=0;i<res.length;i++)
    {
        $("#"+id+"prrofId_"+i)[0].click();
    }  
}
</script>
