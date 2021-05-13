@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' E-Arbitration')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-list"></i> Initiate E-Arbitration
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
                            <form action="{{route('e-arbitration')}}" method="get">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="col-md-2">
                                            <label> Customer Name</label>
                                            <input type="text" name="concerned_person_name" class="form-control input-sm" value="{{!empty(app('request')->input('concerned_person_name')) ? app('request')->input('concerned_person_name') : '' }}" >
                                        </div>
                                        <div class="col-md-2">
                                            <label> GSTIN/Business PAN </label>
                                            <?php
                                            $Redaonly="readonly";
                                            if($customer_type == "Business")
                                            {
                                                $Redaonly="";
                                            }
                                            ?>
                                            <input type="text" name="unique_identification_number" class="form-control input-sm" value="{{!empty(app('request')->input('unique_identification_number')) ? app('request')->input('unique_identification_number') : '' }}" <?php echo $Redaonly; ?>>
                                        </div>
                                        <div class="col-md-2">
                                            <label> Contact Number</label>
                                            <input type="text" name="concerned_person_phone" class="form-control input-sm" value="{{!empty(app('request')->input('concerned_person_phone')) ? app('request')->input('concerned_person_phone') : '' }}">
                                        </div>
                                        <input type="hidden" value="<?php echo $customer_type;?>" name="customers_type">
                                        <div class="col-md-2 text-right text-md-right mt_form pull-right">
                                            <button type="submit" class="btn btn-primary"  aria-controls="dataTable">Search</button>
                                            <a href="{{route('e-arbitration')}}" class="btn btn-primary"  aria-controls="dataTable">Reset</a>
                                        </div>
                                    </div>
                                </div>
                           </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
            <div class="row">
                <div class="col-md-2">
                <span style="font-size:18px;color: black;">Select Customer Type</span>
                </div>
                <div class="col-md-2" style="margin-left: -20px;">
                <div class="form-group">
                <form class="submit-dropdown-form" method="get" action="{{route('e-arbitration')}}">
                <select class="form-control"  name="customers_type" required id="customers_type">
                    <option value="Business" <?php echo  $customer_type == "Business" ? "selected":'' ?>>Business</option>
                    <option value="Individual" <?php echo  $customer_type == "Individual" ? "selected":'' ?>>Individual</option>
                </select>
                </form>
                </div>
                </div>
            </div>
            </div>
            <div class="col-md-12">
            <div class="row">
            <div class="col-md-6">
            <button  class=" form-control btn btn-info  btn-blue ClickEarbitration" data-toggle="modal" data-target="#Earbitration_id" style=" width:190px !important">Send E-Arbirtation<i class="icon voyager-paper-plane"></i>
            </button>
            <div class="" style="color:red;">
            <span class="errorMsg"></span>
            </div>
            </div>
            </div>
            </div>
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="pull-right"><h4></h4></div>
                        <div class="table-responsive">
                            <table id="dataTable" class="table table-hover fixed_headerss">
                                <thead>
                                    <tr>
                                        <th>
                                            <input type="checkbox" class="select_all" style="display:none;">
                                        </th>
                                        <th>Customer Name</th>
                                        <th>Contact Phone</th>
                                        <th style="text-align:center;">GSTIN / Business PAN</th>
                                        <th>Closing Balance</th>
                                        <th>Custom Id </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($records as $data)
                                    <tr id= "tr_{{$data->id}}" data-id="{{$data->id}}">
                                        <td>
                                        <?php
                                        $checkBox="";
                                        if(isset($data->unique_identification_number)){
                                                $closing_Balnace=  General::ind_money_format(General::getTotalDueForBusinessByCustomId($data->id,$userId,$data->dueid,$data->external_business_id) - General::getTotalPaidForBusinessByCustomId($data->id,$userId,$data->dueid,$data->external_business_id));
                                                if($closing_Balnace == 0)
                                                {
                                                    $checkBox="disabled";
                                                }
                                            }else{
                                               $closing_Balnace=  General::ind_money_format(General::getTotalDueForStudentByCustomId($data->id,$userId,$data->dueid,$data->external_student_id) - General::getTotalPaidForStudentByCustomId($data->id,$userId,$data->dueid,$data->external_student_id));
                                               if($closing_Balnace == 0)
                                               {
                                                $checkBox="disabled";
                                               }
                                            }
                                        ?>
                                            <input type="checkbox" name="customer_id" class="checkboxCls" id="checkbox_{{ $data->id }}" value="{{ $data->id }}"  <?php echo $checkBox;?>>

                                            </td>
                                        <td>
                                        <?php
                                     if(isset($data->company_name))
                                     {
                                        $concerned_person_name= "";
                                        if(isset($data->concerned_person_name))
                                        {
                                           $concerned_person_name="<br> Concerned Person Name : ". $data->concerned_person_name;
                                        }
                                        echo "<span class='fa fa-bank'></span>"."<span style='padding-left:10px;'>".$data->company_name. $concerned_person_name."</span>";
                                     }else{
                                        echo "<span class='icon voyager-person'></span>"."<span style='padding-left:10px;'>".$data->person_name."</span>";
                                     }
                                        ?>
                                        </td>
                                        <td>
                                        <?php if(isset($data->concerned_person_phone))
                                                {
                                                    echo $data->concerned_person_phone;
                                                }else{
                                                    echo $data->contact_phone;
                                                }
                                        ?>
                                       </td>
                                        <td><?php if(isset($data->unique_identification_number))
                                        {
                                                $customer_type="Business";
                                                echo "<center>".$data->unique_identification_number."</center>";
                                        } else{
                                                echo "<center>NA</center>";
                                                $customer_type="Individual";
                                        }
                                        ?></td>
                                        <td><?php  echo $closing_Balnace ;?></td>
                                        <td>
                                        <?php if(isset($data->unique_identification_number))
                                        {
                                                $customId =$data->external_business_id;
                                                if($customId)
                                                {
                                                $customId=$customId;
                                                }else{
                                                $customId="-";
                                                }
                                                echo $customId;
                                        }else{
                                                $customId=$data->external_student_id;
                                                if($customId)
                                                {
                                                    $customId=$customId;
                                                }else{
                                                    $customId="-";
                                                }
                                                echo $customId;
                                        }
                                        ?></td>
                                        <?php
                                        if(isset($data->unique_identification_number))
                                        {
                                                $concerned_person_name= "";
                                                if(isset($data->concerned_person_name))
                                                {
                                                $concerned_person_name= $data->concerned_person_name;
                                                }
                                        ?>
                                        <input type="hidden" class="person_{{ $data->id }}" name="person_name" value="<?php echo $data->company_name?>">
                                        <input type="hidden" class="mobile_{{ $data->id }}" name="contact_phone" value="<?php echo $data->concerned_person_phone?>">
                                        <input type="hidden" class="concerned_person_name_{{ $data->id }}" name="concerned_person_name" value="<?php echo $concerned_person_name?>">
                                        <input type="hidden" class="totaldue_{{ $data->id }}" name="contact_phone" value="{{General::ind_money_format(General::getTotalDueForBusinessByCustomId($data->id,$userId,$data->dueid,$data->external_business_id)) }}">
                                        <input type="hidden" class="email_{{ $data->id }}" name="email" value="<?php echo $data->email?>">
                                        <input type="hidden" class="unique_identification_number_{{ $data->id }}" name="unique_identification_number" value="<?php echo $data->unique_identification_number?>">
                                        <input type="hidden" class="customid_{{ $data->id }}" name="custom_id" value="<?php echo $customId?>">
                                       <?php  }else{?>
                                        <input type="hidden" class="person_{{ $data->id }}" name="person_name" value="<?php echo $data->person_name?>">
                                        <input type="hidden" class="concerned_person_name_{{ $data->id }}" name="concerned_person_name" value="<?php $data->person_name?>">
                                        <input type="hidden" class="mobile_{{ $data->id }}" name="contact_phone" value="<?php echo $data->contact_phone?>">
                                        <input type="hidden" class="email_{{ $data->id }}" name="email" value="<?php echo $data->email?>">
                                        <input type="hidden" class="totaldue_{{ $data->id }}" name="contact_phone" value="{{General::ind_money_format(General::getTotalDueForStudentByCustomId($data->id,$userId,$data->dueid,$data->external_student_id))}}">
                                        <input type="hidden" class="unique_identification_number_{{ $data->id }}" name="unique_identification_number" value="">
                                        <input type="hidden" class="customid_{{ $data->id }}" name="custom_id" value="<?php echo $customId?>">
                                       <?php }
                                        ?>
                                       <input type="hidden" class="customer_type_{{ $data->id }}" name="customer_type" value="<?php echo $customer_type?>">
                                    </tr>
                                    @empty
                                        <tr><td colspan="10" align="center">No Record Found</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <div class="pull-right">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Start Model E-arbritaion -->
    <div class="modal commap-team-popup slider_Popup firstpopup" id="Earbitration_id"  tabindex="-1" role="dialog" >
      <div class="modal-dialog" role="document">
        <div class="modal-content Confirmpopup" >
          <div class="modal-header">
          <h3 class="modal-title message_head_text">Confirmation</h3>
            <button type="button" class="close" data-dismiss="modal" id="close_popup" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
          <div id="error_message" style="color:red;">
          </div>
          <div class="table-responsive informationTable">
                <table class="table table-striped">
                    <thead>
                    <tr>
                        <th><span><center>Customer Name</center></span></th>
                        <th><span><center>Mobile Number</span></center></th>
                        <th><center><span>Customer Email</span></center></th>
                        <th class="email_text_add"><center><span class="email_text">Customer Email</span></center></th>
                        <th><center><span class="due_text">Total Due</span></center></th>
                    </tr>
                    </thead>
                    <tbody id="listof_dues">
                    <tr>
                        <th><center><span id="customer_name"></center></span>
                        <input type="hidden" class="form-control name" name="customer_name" value=""  >
                        </th>
                        <th><center><span id="mobile"></center></span>
                        <input type="hidden" class="form-control number" name="mobile"  value=""  >
                        </th>
                        <th class="email_text_add"><center><span id="email"  class="custm_email"></center></span>
                        <th><center><span id="email"  class="emailid"></center></span>
                        <input type="hidden" class="form-control emailid" name="customer_email"  value=""  >
                        </th>
                        <th><center><span id="totaldue" class="total_due"></center></span>
                        </th>
                        <input type="hidden" id="concerned_person_name_id" name="concerned_person_name" value="">
                        <input type="hidden" id="gstn_id" name="gstn" value="">
                        <input type="hidden" id="custm_id_id" name="custm_id" value="">
                        <input type="hidden" id="custm_email" name="custm_email" value="">
                    </tr>
                    </tbody>
                </table>
                </div>
                <div class="message_text_proof"></div>
                <div class="message_text"></div>
                <div class="no_mail_class" style="margin-top: 7px;">
                    <center><button type="submit" id="update_email" class="btn btn-primary" style="width: 114px;margin-top:16px;">Update Email</button> </center>
                </div>
                <div class="form-check t_c " style="padding-top: 13px;">
                    <label class="form-check-label checkbox-inline" for="agree_terms">
                    <input type="checkbox" class="form-check-input earbitration_tc" name="is_check" id="checkboxclick" >
                    I agree that I have an arbitration clause with the customer and I hereby agree to submit all the necessary information and supporting documents.</label>
                </div>
                <button type="button" class="btn btn-danger finalBtn" data-dismiss="modal">Cancel and Go Back</button>
                <button type="submit" class="btn btn-primary agree_submitBtn finalBtn" id="agree_submit">Agree and Submit</button>
                </div>
                <!--  -->
          </div>
          <div class="modal-footer">

          </div>
        </div>
      </div>
    </div>
<!-- End Model E-arbritaion -->


<input type="hidden" value="" id="customer_type">
<input type="hidden" value="" id="customer_id">
<input type="hidden" value="" id="checked_ids" name="checked_ids">
<script>
  $(document).ready(function(){
        $(".earbitration_tc").attr('disabled',false);
                var slectedck=[];
            function removeNumber(arr, num){
                return arr.filter(el => {return el !== num});
            }

            $(".checkboxCls").on("click",function(){
                slectedck=[];
                var id=$(this).val();
                $(".ClickEarbitration").prop('disabled', true);

                if ($(this).prop('checked')==true)
                {
                    $(".errorMsg").html("");
                    $(".ClickEarbitration").prop('disabled', false);
                    $('input[type=checkbox]').prop('checked', false);
                    $(this).attr('disabled',false);
                    $(this).prop('checked', true);
                    $(".earbitration_tc").attr('disabled',false);
                    slectedck.push(id);
                }
                else{
                    slectedck = removeNumber(slectedck, id);

                }
                $('#checked_ids').val(slectedck.join(","));
            })

        $("input.select_all").on('change',function(){
            var id=$(this).val();
            if($(this).prop('checked')){
                $("input[name=customer_id]").prop('checked',true);
                slectedck.push(id);
            }else{
                $("input[name=customer_id]").prop('checked',false);
                slectedck = removeNumber(slectedck, id);
            }
            $('#checked_ids').val(slectedck.join(","));
        });

        $(".approveOrReject").on('click',function(){
            var thisButton = $(this);
            var action = thisButton.data('action');
            var customer_id = thisButton.data('id');

            if(!action || !customer_id){
                alert('Somethig went wrong');
                return false;
            }

            $(".table #tr_"+customer_id).find('.approveOrReject').attr('disabled', 'disabled');
            $.ajax({
               method: 'post',
               url: "{{route('superadmin.due-sms-approve-reject')}}",
               headers: {
                   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
               },
               data: {
                customer_id: customer_id,
                    action: action,
                   _token: $('meta[name="csrf-token"]').attr('content')
               }
            }).then(function (response) {
                var alerter = toastr['success'];
                alerter(response.message);
                $(".table #tr_"+customer_id).find('.approveOrReject').removeAttr('disabled');

                $(".table #tr_"+customer_id).fadeOut(1000,function(){
                   $(this).find('.action-td').html('');
                   $(this).find('.sms-notification-status').text(response.newStatus);
                   $(this).find('.sms-notification-datetime').text(response.dateTime);
                   $(this).fadeIn(500);
                });
            }).fail(function (data) {
                $(".table #tr_"+customer_id).find('.approveOrReject').removeAttr('disabled');
                var alerter = toastr['error'];
                alerter(data.responseJSON.message);
            });

        });


        $("form#due-sms-approve-reject-bulk").on('submit',function(){
            var arrValue= $('input[name=customer_id]:checked').map(function(){
                return this.value;
            }).get();

            if(!arrValue.length){
                alert('Please select atleast one sms notification');
                return false;
            }
            $(this).find('input[name="ids"]').val(arrValue);

        });

    });



$(".agree_submitBtn").on("click",function(){

     var customer_type=$("#customer_type").val();
     var customer_id=$("#customer_id").val();
         if(customer_id && customer_type)
         {
       $.ajax({
            method: 'post',
            url: "{{route('e-arbitration-sent-mail')}}",
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
            customer_type: customer_type,
            customer_id: customer_id,
              _token: $('meta[name="csrf-token"]').attr('content')
            }
       }).then(function (response) {
        console.log(response);

        if(response.status)
        {

            var gst_id=$("#gstn_id").val();
            var  custm_id_id =$("#custm_id_id").val();
            var custm_email=$("#custm_email").val();
            if(gst_id)
            {
                gst_id=gst_id;
            }else{
                gst_id="NA";
            }
            if(custm_id_id)
            {
                custm_id_id=custm_id_id;
            }else{
                custm_id_id="-";
            }
            if(custm_email)
            {
                custm_email=custm_email;
            }else{
                custm_email="-";
            }
            $(".no_mail_class").css("display","none");
            $(".email_text_add").css("display","");
            $(".informationTable").css("display","");
            $("#error_message").html("");
            $(".emailid").html(gst_id);
            $(".custm_email").html(custm_email);
            $(".total_due").html(custm_id_id);
            $(".t_c").css("display","none");
            $(".email_text").html("GSTIN/Business PAN");
            $(".due_text").html("Custom ID")
            $(".finalBtn").css("display","none");
            $(".message_text_proof").css("display","");
            $(".message_head_text").html("Your request for E-Arbitration is raised successfully.");
            $(".message_text_proof").html("<span style='font-size: 14px;color:red;'>Please ensure that you have a proof of due against all the above customer</span>");
            $(".message_text").html("<b style='font-size: 17px;'>The E-Arbitration company will get in touch with you shortly.</b>");
        }else if ( response.status == "undeliverable"){
            $(".informationTable").css("display","none");
            $(".t_c").css("display","none");
            $(".message_head_text").html("");
            $(".finalBtn").css("display","none");
            $("#error_message").html("<center><span style='font-size: 24px;color:red;font-family:bold'>"+response.message+"</span><center>");
            $(".no_mail_class").css("display","");
        }else{
            $(".informationTable").css("display","none");
            $(".message_head_text").html("");
            $(".t_c").css("display","none");
            $(".finalBtn").css("display","none");
            $("#error_message").html("<center><span style='font-size: 24px;color:red;font-family:bold'>Email is not available. Please update your mail. </span><center>");
            $(".no_mail_class").css("display","");
        }
       }).fail(function (data) {

       });
     }
});



    $(".ClickEarbitration").on("click",function(){

            $(".no_mail_class").css("display","none");
            $(".informationTable").css("display","");
            $("#error_message").html("");
            $(".t_c").css("display","");
            $(".email_text").html("Customer Email");
            $(".email_text_add").css("display","none");
            $(".due_text").html("Total Due")
            $(".finalBtn").css("display","");
            $("#agree_submit").attr('disabled',true);
            $(".message_head_text").html("Confirmation");
            $(".message_text_proof").css("display","none");
           $(".message_text").html("");
        var Selected_ids=$('#checked_ids').val();
        if(Selected_ids)
        {
            $(".errorMsg").html("");

        }else{
            $(".errorMsg").html("Atleast select one check box !");
        }
        $("#customer_name_id").val("");
        $("#mobile_id").val("");
        $("#totaldue_id").val("");
        $("#email_id").val("");
        $("#error_msg").val("");
        $("#gstn_id").val("");
        $(".emailid").html("");
        $("#concerned_person_name").val("");
        $("#customer_id").val('');
        $("#customer_type").val("");
        if(Selected_ids)
        {
            var person_name=$(".person_"+Selected_ids+"").val();
            var mobile=$(".mobile_"+Selected_ids+"").val();
            var totaldue=$(".totaldue_"+Selected_ids+"").val();
            var email=$(".email_"+Selected_ids+"").val();
            var unique_identification_number=$(".unique_identification_number_"+Selected_ids+"").val();
            var customid=$(".customid_"+Selected_ids+"").val();
           var concerned_person_name=$(".concerned_person_name_"+Selected_ids+"").val();
           var customer_type=$(".customer_type_"+Selected_ids+"").val();
           $("#customer_type").val(customer_type);
           $("#customer_id").val(Selected_ids);

           if(unique_identification_number)
           {
            $("#gstn_id").val(unique_identification_number);
           }
           if(customid)
           {
            $("#custm_id_id").val(customid);
           }
           if(concerned_person_name)
           {
            $("#concerned_person_name_id").val(concerned_person_name);
           }
            if(person_name)
            {
                $("#customer_name_id").val(person_name);
                $("#customer_name").html(person_name);
            }
            if(mobile)
            {
                $("#mobile_id").val(mobile);
                $("#mobile").html(mobile);
            }
            if(totaldue)
            {
                $("#totaldue_id").val(totaldue);
                $("#totaldue").html(totaldue);
            }
            $("#email_id").val("-");
            $(".emailid").html("-");
            $(".custm_email").html("-");
            $("#custm_email").val("");
            if(email)
            {
                $("#email_id").val(email);
                $(".emailid").html(email);
                $(".custm_email").html(email);
                $("#custm_email").val(email);

            }
    }else{
        $(".ClickEarbitration").prop('disabled', true);
    }


    });

    $("#checkboxclick").on("click",function(){
        var id=$(this).val();
            if($(this).prop('checked')){
                $('#agree_submit').attr('disabled',false);
            }else{
                $('#agree_submit').attr('disabled',true);
            }
    });
    $("#close_popup").on("click",function(){
        location.reload();
    })
    $("#update_email").on("click", function(){
        location.href = 'admin/profile/edit/email';
      });

      $("#customers_type").on("change",function(){
        $(".submit-dropdown-form").submit();
      })
</script>

<style>
.row>[class*=col-] {
    margin-bottom: 8px !important;
}
@media only screen and (max-width: 600px) {
    #Earbitration_id{
     padding-top: 16px !important;
    width:100% !important;
}

.Confirmpopup{
    height:100% !important;
    width: 100% !important;
}

}
#Earbitration_id{
    padding-top: 136px ;
    padding-right: 17px !important;
}
.Confirmpopup{
    height:100% !important;
    width: 123% ;
}

</style>
@endsection
