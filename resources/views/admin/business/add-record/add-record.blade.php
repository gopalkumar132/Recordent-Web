@extends('voyager::master')

@section('page_title', 'Recordent - Business Submit Dues')

@section('page_header')
    <h1 class="page-title">
        
        <i class="voyager-plus"></i>Submit Business Customer Dues
        
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

<style type="text/css">input,textarea{text-transform: uppercase};</style>
    <div class="page-content container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                    	<form action="{{route('business.add-record-store')}}" name="add_store_record" id="add_store_record" method="POST" enctype="multipart/form-data">
							@csrf
							<div class="submitdues-mainbody">
                            <div class="col-md-6">
								<div class="form-group">
									<label for="contact_phone">{{General::getLabelName('unique_identification_number')}} (Unique Identification Number)*</label>
									<input type="text" id="GstPan_fieldId" class="form-control" name="unique_identification_number" style="text-transform:uppercase" value="{{old('unique_identification_number')}}" placeholder="{{General::getLabelName('unique_identification_number')}}*" maxlength="15" required onblur="trimIt(this);">
								</div>
							</div>
                            <div class="clearfix"></div>
							<div class="col-md-6">
								<div class="form-group">
									<label for="contact_phone">Business Name*</label>
									<input type="text" class="form-control company_name" id="company_name" name="company_name" value="{{old('company_name')}}" placeholder="Company Name" maxlength="{{General::maxlength('name')}}" required onblur="trimIt(this);">
                                </div>
							</div>
							
							<div class="col-md-6">
	                        	<div class="form-group">
	                        		<label for="contact_phone">Business Type*</label>
									<select name="user_type" id="user_type"  placeholder="Select Business Type" class="form-control" required>
							            <option value="">Select</option>
							            @if($userTypes->count())  
								            @foreach($userTypes as $userType)
								            	<option value="{{$userType->id}}" {{old('user_type')==$userType->id ? 'selected' : '' }}>{{$userType->name}}</option>
								            @endforeach  
							            @endif   
							        </select>
					        	</div>
					        </div>

					        <input type="hidden" id="business_type_id_hidden" value="{{Auth::user()->user_type}}">
							 
							 <div class="col-md-6" id="type_of_business_div" style="display:none">
	                        	<div class="form-group">
					         <label>Type of Business</label>
					             <input type="text" name="type_of_business" id="type_of_business" value="{{ old('type_of_business',Auth::user()->type_of_business) }}" placeholder="Please specify type of business" class="form-control">
					         </div>
					     </div>

							<div class="col-md-6">
	                        	<div class="form-group">
	                        		<label for="contact_phone">Business Sector</label>
									<select name="sector_id" id="sector_id"  placeholder="Select Sector" class="form-control">
							            <option value="">Select</option>
							            @if($sectors->count())  
								            @foreach($sectors as $sector)
								            	<option value="{{$sector->id}}" {{old('sector_id')==$sector->id ? 'selected' : '' }}>{{$sector->name}}</option>
								            @endforeach  
							            @endif   
							        </select>
					        	</div>
					        </div>

					        <input type="hidden" id="sector_type_id_hidden" value="{{Auth::user()->sector_id}}">

					        
					        <div class="col-md-6" id="type_of_sector_div" style="display:none">
	                         <div class="form-group">	
					          <label>Type of Sector</label>
					             <input type="text" name="type_of_sector" id="type_of_sector" value="{{ old('type_of_sector',Auth::user()->type_of_sector) }}" placeholder="Please specify type of sector" class="form-control">
					         </div>
					        </div>


							<div class="col-md-6">
								<div class="form-group">
									<label for="contact_phone">Concerned Person Name*</label>
									<input type="text" class="form-control concerned_person_name" id="concerned_person_name" minlength="3" name="concerned_person_name" value="{{old('concerned_person_name')}}" placeholder="Concerned Person Name*" maxlength="{{General::maxlength('name')}}" required onblur="trimIt(this);">
                                </div>
							</div>
                           <div class="col-md-6">
								<div class="form-group">
									<label for="contact_phone">Concerned Person Designation*</label>
									<input type="text" class="form-control concerned_person_designation" name="concerned_person_designation" value="{{old('concerned_person_designation')}}" placeholder="Concerned Person Designation*"  maxlength="50" required onblur="trimIt(this);" maxlength="50">
								</div>
							</div>
							<div class="col-md-6">
	                            <div class="form-group">
									<label for="contact_phone">Concerned Person Phone*</label>
									<input type="tel" class="form-control number concerned_person_phone" name="concerned_person_phone" value="{{old('concerned_person_phone')}}" placeholder="Concerned Person Phone" required  maxlength="10" onkeypress="return numbersonly(this,event)">
								</div>
							</div>
							<div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Concerned Person Email Address*</label>
                                    <input type="email" name="email" id="email" class="form-control email Email_Validation" placeholder="Email" maxlength="{{General::maxlength('email')}}" aria-controls="dataTable" value="{{old('email')}}">
                                    <label id="error_msg" style="color:red !important;"></label>
                                </div>
                                
                            </div> 
                           <div class="col-md-6">
	                            <div class="form-group ">
									<label for="contact_phone">Concerned Person Alternate Phone</label>
									<input type="tel" class="form-control number concerned_person_alternate_phone" name="concerned_person_alternate_phone" value="{{old('concerned_person_alternate_phone')}}" placeholder="Concerned Person Alternate Phone"  maxlength="10" onkeypress="return numbersonly(this,event)">
									
								</div>
							</div>
							<div class="clearfix"></div>
							 <div class="col-md-6">
								<div class="form-group invoiceno_check_errclass">
									<label for="contact_phone">Invoice No</label>
									<input type="text" maxlength="20" class="form-control invoice_number" id="invoice_no_0" name="invoice_no[]" value="{{old('invoice_no.0')}}" placeholder="Invoice No" onblur="trimIt(this);">
								</div>
                            </div>
                            <div class="col-md-6">
								<div class="form-group dueamount_check_errclass">
									<label for="contact_phone">Invoice/Due Amount*</label>
									<input type="text" class="form-control invoice_due_amount" id="due_amount_0" name="due_amount[]" value="{{old('due_amount.0')}}"splaceholder="Due Amount*" onblur="trimIt(this);" onkeypress="return numbersonly(this,event)">
									<label class="dueAmountInWord_0" style="display: none"></label>
									<br>

								</div>
                            </div>

							<div class="col-md-6">
                                <div class="form-group">
                                    <label for="contact_phone">Invoice Date (DD/MM/YYYY)<span class="mark" style="color:black;background-color:white;">*</span></label>
                                    <input type="text" name="invoice_date[]" id="inv_date_0" class="form-control datepicker  inv_date" data-date-format="DD/MM/YYYY"   aria-controls="dataTable" value="">
                                    <span class="invoiceDate"></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                            <div class="form-group">
                                    <label for="name">Due Date Option*</label>
                                    <select class="form-control duecredit_cls" id="selectdropdown_0" name="credit_duedate[]" required>
                                         <option value="">Select</option>
                                        <option value="Due Date" >Due Date</option>
                                        <option value="Credit Period" >Credit Period</option>
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="grace_period">Grace period *  <span class="grace_period_info"  data-toggle="tooltip" data-placement="top" title="Grace Period is a set length of time after the due date during which the payment can be made. This may differ depending on your sector of business and your terms with the Customer"><i class="fa fa-info-circle"></i></span></label>
                                    <select class="form-control grace_period" id="grace_period_0" name="grace_period[]" disabled="">
                                       
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
								<input type="hidden" name="grace_period_hidden[]" id="grace_period_hidden_0" value=""/>
                            </div>
							<div class="col-md-6 credit_div_0" >
                                <div class="form-group ">
                                    <label for="credit_period">Credit Period*</label>
                                    <input type="tel" class="form-control number" name="credit_period[]" id="credit_period" value="" placeholder="Credit Period"  onblur="trimIt(this);" maxlength="4" onkeypress="return numbersonly(this,event)">
                                    <label class="due_date_display" id="due_date_display_0" style="display: none">Due Date:<b class="duedate" id="duedate_0"></b></label> 
                               <input type="hidden" name="duedate_on_creditperiod"  id="duedate_on_creditperiod_0" value="">
                                </div>
                            </div>
							<div class="col-md-6 due_div_0" >
								<div class="form-group duedate_check_errclass ">
									<label for="contact_phone">Due Date (DD/MM/YYYY)*</label>
                                    <input type="text" name="due_date[]" id="due_date_0" class="form-control datepicker collectionsetevent auto_poplate_date" data-date-format="DD/MM/YYYY" required aria-controls="dataTable" value="{{old('due_date.0')}}">
								</div>
							</div>
                            <div class="col-md-6">
                                <div class="form-group collection_date_block collection_date_block_0">
                                    <label for="collection_date">Collection Start Date (DD/MM/YYYY)*  <span class="collection_date_info" data-toggle="tooltip" data-placement="top" title="Collection Start Date is the date on which Recordent will start contacting the Customer to recover the dues"><i class="fa fa-info-circle"></i></span></label>
                                    <input type="text" id="collection_date_0" name="collection_date[]" class="form-control datepicker collection_date" placeholder="" data-date-format="DD/MM/YYYY" required aria-controls="dataTable" readonly value="{{old('collection_date.0')}}">
                                </div>
                            </div>
                            <div class="clearfix"></div>

                            <!--<div class="col-md-6">
                                <div class="form-group">
                                    <label for="external_business_id">Custom ID </i></label>
                                    <input type="text" name="external_business_id[]" id="external_business_id" class="form-control " placeholder="Custom ID" maxlength="50" aria-controls="dataTable" value="{{old('external_business_id.0')}}" onkeypress="return blockSpecialChar(this,event)">
                                </div>
                            </div>-->

                            <div class="col-md-12">
                                <div class="form-group">
									<label for="contact_phone">Due Note</label>
									<textarea class="form-control" id= "due_note_0" name="due_note[]" rows="5" maxlength="300" placeholder="Due Note" onblur="trimIt(this);" onkeypress="return blockSpecialChar(this,event)">{{old('due_note.0')}}</textarea>
								</div>
                            </div>
                            {{--<div class="col-md-6">	
	                            <div class="form-group">
									<label for="contact_phone">Paid Date (MM/DD/YYYY)</label>
									<input type="date" name="paid_date" class="form-control" placeholder="" aria-controls="dataTable" value="{{old('paid_date.0')}}">
								</div>
                            </div>
                            <div class="col-md-6">
	                            <div class="form-group">
									<label for="contact_phone">Paid Amount</label>
									<input type="text" class="form-control" name="paid_amount" value="{{old('paid_amount.0')}}" placeholder="Paid Amount">
								</div>
                            </div>
                            
                            <div class="col-md-12">
	                        	<div class="form-group">
									<label for="contact_phone">Paid Note</label>
									<textarea class="form-control" maxlength="300" name="paid_note" rows="5" placeholder="Paid Note" onkeypress="return blockSpecialChar(this,event)">{{old('paid_note.0')}}</textarea>
								</div>
							</div>--}}
							<div class="col-md-6">
	                        	<div class="form-group">
	                        		<label for="contact_phone">State*</label>
									<select name="state" id="state"  placeholder="Select State" class="form-control" required>
							            <option value="">Select</option>
							            @if($states->count())  
								            @foreach($states as $state)
								            	<option value="{{$state->id}}" {{old('state')==$state->id ? 'selected' : '' }}>{{$state->name}}</option>
								            @endforeach  
							            @endif
							        </select>
					        	</div>
					        </div>	

					        <div class="col-md-6">
	                        	<div class="form-group">
	                        		<label for="contact_phone">City*</label>
									<select name="city" id="city"  placeholder="Select city" class="form-control select2" required >
							            <option value="">Select</option>
							        </select>
					        	</div>
					        </div>

					        <div class="col-md-6">
	                            <div class="form-group">
									<label for="contact_phone">Pin Code</label>
									<input type="text" class="form-control pincode" name="pin_code" value="{{old('pin_code')}}" placeholder="Pin Code" maxlength="6" onblur="trimIt(this);" onkeypress="return numbersonly(this,event)">
								</div>
                            </div>

                            <div class="col-md-6">
	                            <div class="form-group">
									<label for="contact_phone">Address</label>
									<input type="text" class="form-control address" name="address" value="{{old('address')}}" placeholder="Address"  maxlength="100" onkeypress="return blockSpecialChar(this,event)" onblur="trimIt(this);">
								</div>
                            </div>
                            <div class="col-md-6">
                            <image class="download_img" src="https://image.flaticon.com/icons/png/128/109/109612.png">
                            <div class="form-group proofofdue_check_errclass files color">
                                <label>Proof Of Due </label>
                                <input type="file" id="proof_of_due_0" class="form-control mydrop filesImg responsive" name="proof_of_due_0[]" multiple accept='.jpg,.png,.jpeg,.pdf,.docx,.xls,.xlsx,.bmp,.csv' style="text-align:center !important;border-color: #ecf7fc;background-color: #ecf7fc;border:dashed;">
                                <p for="contact_phone">Note: Only pdf,docx,jpeg,png,bmp,xls,xlsx,csv files are allowed <span id="imgError" style="color:red;"></span></p>
                                
                            </div>
                        </div>
							
                            <!-- <div class="col-md-6">
								<div class="form-group proofofdue_check_errclass">
									<label for="contact_phone">Proof Of Due</label>
									<input type="file" class="form-control fl-upload-height" id="proof_of_due_0" name="proof_of_due[]" accept='.jpg,.png,.jpeg,.pdf,.doc,.docx,.xls,.xlsx,.bmp,.csv'>
									<p for="contact_phone">Note: Only pdf,doc,docx,jpeg,png,bmp,xls,xlsx,csv files are allowed <span id="imgError" style="color:red;"></p>
								</div>	
                            </div> -->
                           </div>
						   <div class="col-md-12">
								<div class="col-md-6">							
									<div class="form-action ">
										<button type="submit" class="btn btn-primary btn-blue">SUBMIT</button>
									</div>	
								</div>
								<div class="col-md-6">		
									<div class="form-action ">
									<i class="voyager-plus"></i>
									<button type="button" class="btn btn-primary btn-blue add-record-submitdues" id="add-record">Add Record</button>
									</div>	
								</div>
							</div>

						</form>

					</div>
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
 <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.2/moment.min.js"></script>
<script src="{{asset('js/jquery.validate.min.js')}}"></script>
<script src="{{asset('js/number-to-word.js')}}"></script>
<script language="javascript" type="application/javascript">
$(document).ready(function() {
	$('.credit_div_0').css('display','none');
        $('.due_div_0').css('display','none');
        $('.mark').css('display','none');

        $("#proof_of_due_0").on("change",function(){
               var $fileUpload = $("input[type='file']");
               var error="";
               if (parseInt($fileUpload.get(0).files.length) >=6){
                  
                   error="<lable class='error'> | only allowed to upload a maximum of 5 files</label>";
                  $("#imgError").html(error);
                  $("#proof_of_due_0").val("");
               }else{
                
                $("#imgError").html(error);
               }
        });

    $(".duecredit_cls").on('change',function(){

        var inputId = $(this).attr("id");
        var sletc_val=$("#selectdropdown_0").val();

        $('.invoiceDate').empty();
        $('.mark').css('display','none');
        $('#inv_date_0').prop('required',false);
        if(sletc_val == "Due Date")
        {
            $('#inv_date_0').prop('required',false);
            $('.credit_div_0').css('display','none');
            $('.due_div_0').css('display','');
        }
        else if(sletc_val == "Credit Period"){
            $('#inv_date_0').prop('required',true);
            $('.mark').css('display','');
            var invDate=$('#inv_date_0').val();
            $("#credit_period").attr("required", "true");
                if(invDate == '')
                {
                    $('.mark').css('display','');
                    $('#inv_date_0').prop('required',true);
                    $('.invoiceDate').append('<label class="error">This field is required.</label>');
                    $('.credit_div_0').css('display','none');
                    $('.due_div_0').css('display','none');
                    return false;
                }
                else{
                    $('#inv_date_0').prop('required',false);
                    $('.invoiceDate').empty();
                    $('.due_div_0').css('display','none');
                    $('.credit_div_0').css('display','');
                }
        }else{
            $('.credit_div_0').css('display','none');
            $('.due_div_0').css('display','none');
        }

    })
    
    $('#inv_date_0').on("dp.change",function(){
    $('.invoiceDate').empty();
   var invoice_date=$("#inv_date_0").val();
   var days=$("#credit_period").val();
   var sletc_val=$("#selectdropdown_0").val();
         if(invoice_date);
           {
            if(sletc_val == 'Credit Period')
               {
                $('.credit_div_0').css('display','');
               }
               else{
                $('.credit_div_0').css('display','none'); 
               }
           } 
           if(days)
           {
               date_days_invoices(invoice_date,days,'')
           }
       });

   function date_days_invoices(invoice_date,days,id){
var collect_due_var;
var due_date_display;
var append_duedate;
var due_date_place;

       
        if(id)
        {
            $('#duedate_'+id+'').empty();
            collect_due_var="due_date_"+id+"";
            due_date_display="#due_date_display_"+id+"";
            append_duedate="#duedate_"+id+"";
            due_date_place="#duedate_"+id+"";
        }
        else{
            $('#duedate_0').empty();
            collect_due_var='due_date_0';
            due_date_place="#due_date_0";
            due_date_display='#due_date_display_0';
            duedate_on_creditperiod=".duedate_on_creditperiod_0"
            append_duedate='#duedate_0';
        }

        

       var new_date = moment(invoice_date, "DD/MM/YYYY").add('days', days);
           var day = new_date.format('DD');
           var month = new_date.format('MM');
           var year = new_date.format('YYYY');
           var newdate=day + '/' + month + '/' + year;
          
           if(days)
           {
            $(due_date_display).css('display','');
            $(append_duedate).append(newdate); 
           }
           else{
            $(due_date_display).css('display','none'); 
           }

           $('#duedate_on_creditperiod_0').val(newdate);
           $(due_date_place).val(newdate);
           set_collection_date(collect_due_var);
   }

   $("#credit_period").on("change",function(){

       var invoice_date=$("#inv_date_0").val();
       var days=$("#credit_period").val();
       if(invoice_date)
       {
           date_days_invoices(invoice_date,days,'')
       } 
   });

	$('body').on('focus','.datepicker',function(){
    $(this).datetimepicker();
});
	
	var rowNum = 1;
    var ProofIdCount=0;
      //$(".add-record-submitdues").click(function(){
		  $("body").on("click", ".add-record-submitdues", function(){
          //var html = $(".submitdues-copy").html();
		  //$(".submitdues-mainbody").append(html);
		  var options_dynamic = "";
		  var allowedgraceperiod = [7, 15, 21, 30, 45, 75, 90, 120, 150, 180];
		  for(var i=0; i<allowedgraceperiod.length; i++) {
			  var days = allowedgraceperiod[i]; 
			  options_dynamic +='<option value='+days+'>'+days+' days</option>';
			  }
			  rowNum++;
              ProofIdCount++;
			  var numAddRows = $('.copy-multiple-submitdues-copy').length;
			  if(numAddRows< <?php echo Config::get('constants.add_rows.limit');?>) {
				  var invoiceRowCount = numAddRows+1;

				  $(".submitdues-mainbody").append('<div class="clearfix" id="copy-multiple-submitdues-copy-scroll'+invoiceRowCount+'"></div><div style="background-color:#F7F6F6" class="copy-multiple-submitdues-copy"><div style="border-top:2px dotted black"></div><div style="padding-left:10px; id="add_record_count"><b>Add Record No:</b> <button class="btn btn-info btn-sm"><b>'+invoiceRowCount+'</b></button></div><div class="col-md-6"><div class="form-group invoiceno_check_errclass"><label for="contact_phone">Invoice No</label><input type="text" class="form-control invoice_number" maxlength="20" id="invoice_no_'+rowNum+'" name="invoice_no[]" value="{{old('
                invoice_no')}}" placeholder="Invoice No" onblur="trimIt(this);"></div></div><div class="col-md-6"><div class="form-group dueamount_check_errclass"><label for="contact_phone">Invoice/Due Amount*</label><input type="text" class="form-control invoice_due_amount" id="due_amount_'+rowNum+'" name="due_amount[]" value="{{old('
                due_amount')}}" placeholder="Due Amount*" onblur="trimIt(this);" onkeypress="return numbersonly(this,event)"><label class="dueAmountInWord_'+rowNum+'" style="display: none"></label><br></div></div>'+
                '<div class="col-md-6"><div class="form-group "><label for="contact_phone">Invoice Date (DD/MM/YYYY)<span class="mark_'+rowNum+'" style="color:black;background-color:white;">*</span></label>'+
                '<input type="text" id="invdate_'+rowNum+'" name="invoice_date[]" data-provide="datepicker" class="form-control datepicker collectionsetevent" data-date-format="DD/MM/YYYY"  aria-controls="dataTable" value=""><label  id="errorId_'+rowNum+'" class="error" style="display:none;">This filed is required</label></div></div>'
                + '<div class="col-md-6"><div class="form-group"><label for="grace_period">Grace period *  <span class="grace_period_info"  data-toggle="tooltip" data-placement="top" title="Grace Period is a set length of time after the due date during which the payment can be made. This may differ depending on your sector of business and your terms with the Customer"><i class="fa fa-info-circle"></i></span></label><select class="form-control grace_period" id = "grace_period_'+rowNum+'" name="grace_period[]" disabled=""><option value="1">1 day</option>'+ options_dynamic+'</select></div><input type="hidden" name="grace_period_hidden[]" id="grace_period_hidden_'+rowNum+'" value=""/></div><div class="clearfix"></div><div class="col-md-6"><div class="form-group"><label for="name">Due Date Option*</label>'+
                '<select class="form-control duecredit_cls" id="selectdropdown_'+rowNum+'" name="credit_duedate[]" required >'+
                '<option value="">Select</option><option value="Due Date" >Due Date</option><option value="Credit Period" >Credit Period</option></select></div></div>'
                +'</b></label><input type="hidden" class="" id="hiddendate_'+rowNum+'" value="">'
                
                +'<div class="col-md-6 credit_div_'+rowNum+'" >'
                +'<div class="form-group "><label for="credit_period">Credit Period*</label>'
                +'<input type="tel" class="form-control number credit_period" name="credit_period[]" id="creditperiod_'+rowNum+'" value="" placeholder="Credit Period"  onblur="trimIt(this);" maxlength="4" onkeypress="return numbersonly(this,event)"><label class="due_date_display" id="due_date_display_'+rowNum+'" style="display: none">Due Date:<b class="duedate" id="duedate_'+rowNum+'"></div></div>'
                +'<div class="col-md-6 due_div_'+rowNum+'"><div class="form-group duedate_check_errclass"><label for="contact_phone">Due Date (DD/MM/YYYY)*</label><input type="text" id="due_date_'+rowNum+'" name="due_date[]" data-provide="datepicker" class="form-control datepicker collectionsetevent" data-date-format="DD/MM/YYYY" required aria-controls="dataTable" value="{{old('
                due_date')}}"></div></div><div class="col-md-6"><div class="form-group collectiondateblock collection_date_block_'+rowNum+'"><label for="collection_date">Collection Start Date (DD/MM/YYYY)*  <span class="collection_date_info" data-toggle="tooltip" data-placement="top" title="Collection Start Date is the date on which Recordent will start contacting the Customer to recover the dues"><i class="fa fa-info-circle"></i></span></label><input id="collection_date_'+rowNum+'" type="text" name="collection_date[]" class="form-control datepicker collection_date" placeholder="" data-date-format="DD/MM/YYYY" required aria-controls="dataTable" readonly value="{{old('
                collection_date')}}"></div></div><div class="clearfix"></div><div class="col-md-12"><div class="form-group"><label for="contact_phone">Due Note</label><textarea class="form-control" name="due_note[]" rows="5" maxlength="300" placeholder="Due Note" onblur="trimIt(this);" onkeypress="return blockSpecialChar(this,event)">{{old('
                due_note')}}</textarea></div></div>'
                +'<div class="col-md-6"><image class="download_img_addrec" src="https://image.flaticon.com/icons/png/128/109/109612.png"><div class="form-group proofofdue_check_errclass files color"><label>Proof Of Due </label>'
                +'<input type="file" id="proof_of_due_'+rowNum+'" class="form-control mydrop filesImg" name="proof_of_due_'+ProofIdCount+'[]" multiple accept=".jpg,.png,.jpeg,.pdf,.docx,.xls,.xlsx,.bmp,.csv" style="text-align:center !important;border-color: #ecf7fc;background-color: #ecf7fc;border:dashed;"><p for="contact_phone">Note: Only pdf,docx,jpeg,png,bmp,xls,xlsx,csv files are allowed <span id="imgError_'+rowNum+'" style="color:red;"></span></p></div></div>'
                +'<div class="input-group-btn"> <button class="btn btn-danger remove-submitdues" style="margin-top: 140px;" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button></div></div>');
                   
                    $('.due_div_'+rowNum+'').css('display','none');
                    $('.credit_div_'+rowNum+'').css('display','none');
                    $('.mark_'+rowNum+'').css('display','none');
                   
                    $("#proof_of_due_"+rowNum+"").on("change",function(){
                        var error="";
                        var filecount=$("#proof_of_due_"+rowNum+"").get(0).files.length;
                        
                        if (parseInt(filecount) >6){
                            
                            error="<lable class='error'> | only allowed to upload a maximum of 5 files</label>";
                            $("#imgError_"+rowNum+"").html(error);
                            $("#proof_of_due_"+rowNum+"").val("");
                        }else{
                            
                            $("#imgError_"+rowNum+"").html(error);
                        }
                    });

                    $('#invdate_'+rowNum+'').on("dp.change",function(){
                            $("#errorId_"+rowNum+"").css('display','none');
                        var invoice_date=$("#invdate_"+rowNum+"").val();
                        var days=$("#creditperiod_"+rowNum+"").val();
                        var sletc_valTxt=$("#selectdropdown_"+rowNum+"").val();
                                if(invoice_date);
                                {
                                    if(sletc_valTxt == 'Credit Period')
                                    {
                                        
                                        $('.credit_div_'+rowNum+'').css('display','');
                                    }
                                    else{
                                      
                                        $('.credit_div_'+rowNum+'').css('display','none'); 
                                    }
                                } 
                                if(days)
                                {
                                    date_days_invoices_1(invoice_date,days,'');
                                }
                    });
		  
		  $(window).scrollTop($('#copy-multiple-submitdues-copy-scroll'+invoiceRowCount).offset().top);
			  }
		  //$(this).attr('id','testidddd');
          function date_days_invoices_1(invoice_date,days,id){
            var collect_due_var;
            var due_date_display;
            var append_duedate;
            var due_date_place;

       
        if(id)
        {
            $('#duedate_'+id+'').empty();
            collect_due_var="due_date_"+id+"";
            due_date_display="#due_date_display_"+id+"";
            append_duedate="#duedate_"+id+"";
            due_date_place="#duedate_"+id+"";
        }
        else{
            $('#duedate_0').empty();
            collect_due_var='due_date_0';
            due_date_place="#due_date_0";
            due_date_display='#due_date_display_0';
            append_duedate='#duedate_0';
        }

        

       var new_date = moment(invoice_date, "DD/MM/YYYY").add('days', days);
           var day = new_date.format('DD');
           var month = new_date.format('MM');
           var year = new_date.format('YYYY');
           var newdate=day + '/' + month + '/' + year;
          
           if(days)
           {
            $(due_date_display).css('display','');
            $(append_duedate).append(newdate); 
           }
           else{
            $(due_date_display).css('display','none'); 
           }
           $('#hiddendate_'+id+'').val(newdate);
           $("#due_date_"+id+"").val(newdate);
           set_collection_date(collect_due_var);
   }

   $(".credit_period").on("change",function(){

var inputId = $(this).attr("id");
var res= inputId.split("_");
var invoice_date=$("#invdate_"+res[1]+"").val();
var days=$("#creditperiod_"+res[1]+"").val();

if(invoice_date)
{
    date_days_invoices_1(invoice_date,days,res[1])
} 
    });

    $(".duecredit_cls").on('change',function(){
                var inputId = $(this).attr("id");
                var res= inputId.split("_");
                
                var select_val=$("#selectdropdown_"+res[1]+"").val();
             
                    $('.invoiceDate_'+res[1]+'').empty();
                    $('#invdate_'+res[1]+'').prop('required',false);
                    $("#creditperiod_"+res[1]+"").prop("required", false);
                  

                if(  select_val == "Credit Period")
                {
                    $('.mark_'+rowNum+'').css('display','');
                    $("#creditperiod_"+res[1]+"").attr("required", "true");
                    var invDate=$('#invdate_'+res[1]+'').val();
                    $('#invdate_'+res[1]+'').prop('required',true);

                if(invDate == '')
                {
                   
                    $("#errorId_"+res[1]+"").css('display','');
                    $('#invdate_'+res[1]+'').attr("required", "true");
                    $('.credit_div_'+res[1]+'').css('display','none');
                    $('.due_div_'+res[1]+'').css('display','none');
                    return false;
                }
                else{
                    $('#invdate_'+res[1]+'').attr("required", "none");
                    $("#errorId_"+res[1]+"").css('display','none');
                    $('.due_div_'+res[1]+'').css('display','none');
                    $('.credit_div_'+res[1]+'').css('display','');
                }
                    
                }
                else if( select_val == "Due Date" ){
                    $("#errorId_"+res[1]+"").css('display','none');
                    $('.mark_'+rowNum+'').css('display','none');
                    $('.credit_div_'+res[1]+'').css('display','none');
                    $('.due_div_'+res[1]+'').css('display','');
                }
                else{

                    $('.due_div_'+res[1]+'').css('display','none');
                    $('.credit_div_'+res[1]+'').css('display','none');
                }
               

            });

      });
	  
      $("body").on("click",".remove-submitdues",function(){ 
          $(this).parents(".copy-multiple-submitdues-copy").remove();
      });	  	  	  	  	

    });

    $("#user_type").on('change',function(){
        $("#type_of_business_div").find('input').val('');
        // if($(this).val()==10 || $(this).val()==11){
        if($('#user_type :selected').text()== "Others"){

            $("#type_of_business_div").show(1);
            $("#type_of_business_div").find('input').attr('required','required');

        }else{
            $("#type_of_business_div").hide(1);
            //$("#type_of_business_div").find('input').removeAttr('required');
        }
    });

    $("#sector_id").on('change',function(){
        $("#type_of_sector_div").find('input').val('');
        // if($(this).val()==10 || $(this).val()==11){
        	if($('#sector_id :selected').text()== "Others"){

            $("#type_of_sector_div").show(1);
            $("#type_of_sector_div").find('input').attr('required','required');
        }else{
            $("#type_of_sector_div").hide(1);
        }
    });


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
            else if ( (key==192) || (key==49) || (key==50) || (key==51) || (key==52) || (key==54) || (key==55) || (key==56) || (key==189) || (key==187) || (key==220) || (key==191) || (key==219) || key==221){
                //return false;
            }else if ((("~!@#$^&*_+|\/<>{}[]").indexOf(keychar) > -1)){
                return false;
            }else{
                return true;
            }
        }

	convertToINRFormat = function(value, inputField) {
		var number = Number(value.replace(/,/g, ""));
		withComma = number.toLocaleString('en-IN');
		if(withComma!=0 && withComma!='NaN'){
			$(inputField).val(withComma);
		}else{
			$(inputField).val('');
		}
	};
	
	
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
	$.validator.addMethod("alphaspace", function(value, element) {
        return this.optional(element) || /^[a-z ]+$/i.test(value);
    }, "Only alphabet and space allowed.");

    $.validator.addMethod("alphaspacename", function(value, element) {
        return this.optional(element) || /^[a-z0-9&. -]+$/i.test(value);
    }, "Please enter valid business name.");
	
	$.validator.addMethod("alphanum", function(value, element) {
        return this.optional(element) || /^[a-z0-9]+$/i.test(value);
    }, "Only alphabet and numbers allowed.");
    
    $.validator.addMethod("onlynumber", function(value, element) {
        return this.optional(element) || /^[0-9]+$/i.test(value);
    }, "Please enter a valid number");
    
    $.validator.addMethod("numberNotStartWithZero", function(value, element) { 
    	alert(value.match("^0")); 
	    return this.optional(element) || value.match("^0"); 
	}, "Please enter a value greater than or equal to 1");
    
    $.validator.addMethod("mobile_number_india", function(value, element) {
        return this.optional(element) || /^[6789]\d{9}$/i.test(value);
    }, "Please enter a valid number.");


    
	$.validator.addMethod("maxlengthto_100cr", function (value, element) {
    var flag = true;
	var error_count = 0;
	var errcountArr = [];
	$("[name^=due_amount]").each(function (i, j) {
		$(this).parent('.dueamount_check_errclass').find('label.error').remove();
		var thisValue = $(this).val();
		var number = Number(thisValue.replace(/,/g, ""));
		if ($.trim($(this).val()) == '') {
			$(this).parent('.dueamount_check_errclass').append('<label  id="duedate_check'+i+'-error" class="error">This field is required.</label>');
			flag =  false;
			errcountArr.push(flag);
		} else if(number > 0) {
			if(number <500) {
				$(this).parent('.dueamount_check_errclass').append('<label  id="duedate_check'+i+'-error" class="error">Due amount cannot be less than 500.</label>');
				flag = false;
				errcountArr.push(flag);
			} else if(number >= 1000000000) {
				$(this).parent('.dueamount_check_errclass').append('<label  id="duedate_check'+i+'-error" class="error">Due amount can not be greater than 1,00,00,00,000.</label>');
				flag = false;
				errcountArr.push(flag);
			} else {
				flag = true;
				errcountArr.push(flag);
			}
		}
	});
		var returnValue = errcountArr.includes(false);
		if(returnValue) { return false; } else { return true; }
	}, "");
	
	
	$.validator.addMethod("invoice_validate", function(value, element) {
        var flag = true;
		var error_count = 0;
		$("[name^=invoice_no]").each(function (i, j) {
		$(this).parent('.invoiceno_check_errclass').find('label.error').remove();
		var thisValue = $(this).val();
		var pattern = /^[A-Za-z0-9/*(),#+-@]+$/i;
		var check_pattern = pattern.test(thisValue);
			if(thisValue!=""){
				if(thisValue.length >= 1) {
				if (!check_pattern) {
					error_count++;
					$(this).parent('.invoiceno_check_errclass').append('<label  id="duedate_check'+i+'-error" class="error">Only alphanumeric characters allowed.</label>');
					}
				} else {
					$(this).parent('.invoiceno_check_errclass').append('<label  id="duedate_check'+i+'-error" class="error">Invoice Number length should be minimum 3 characters.</label>');
				}
		}
	});
		var error_count_flag = error_count > 0 ? false:true;
		return error_count_flag;
    }, "");
	
	
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
	
	
	$.validator.addMethod("check_gstin", function(value, element) {
        if(value.toString().length == 10) {
			var valueToString = value.toString().toUpperCase();
			// var fourthChar = valueToString.charAt(3);
			// var allowedCharsAtFourthPosition = ["C","H","A","B","G","J","L","F","T"];
			if(valueToString) {
				return this.optional(element) || /^[A-Z]{3}[A|B|C|F|G|H|L|J|P|T]{1}[A-Z]{1}[0-9]{4}[A-Z]{1}$/i.test(value);
			} else {
				return false;
			}

		} else {
          return this.optional(element) || /^[0-3|9]{1}[0-9]{1}[A-Z]{3}[A|B|C|F|G|H|L|J|P|T]{1}[A-Z]{1}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/i.test(value);
      }
    }, "Please enter a valid GSTIN/Business PAN.");


   $.validator.addMethod("notEqual", function(value, element, param) {
       return this.optional(element) || value != {{Auth::user()->mobile_number}};
      }, "Mobile number same as your registered mobile number");


     $.validator.addMethod("file_upload", function(value, element) {
        var flag = true;
        var error_count = 0;
        $("[name^=proof_of_due]").each(function (i, j) {
        $(this).parent('.proofofdue_check_errclass').find('label.error').remove();
        var thisValue = $(this).val();
        var pattern = /(.*png$)|(.*jpg$)|(.*docx$)|(.*xlsx$)|(.*pdf$)|(.*bmp$)|(.*csv$)|(.*xls$)|(.*doc$)|(.*jpeg$)$/i;
        var check_pattern = pattern.test(thisValue);
            if(thisValue!=""){
                if (!check_pattern) { 
                    error_count++;
                    $(this).parent('.proofofdue_check_errclass').append('<label  id="duedate_check'+i+'-error" class="error">Invalid File Format.</label>');
                }
                }
        
    });
        var error_count_flag = error_count > 0 ? false:true;
        return error_count_flag;
    }, "");

	
	/*$.validator.addMethod("maxlengthto_100cr", function(value, element) {
    	var number = Number(value.replace(/,/g, ""));
        return this.optional(element) || number<=1000000000;
    }, "Due amount can not be greater than 1,00,00,00,000");*/
    $.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
 });
	$('#add_store_record').validate({
        rules: {
            unique_identification_number: {
              maxlength: 15,
              minlength:2,
              required:true,
			  check_gstin:true
            },
           concerned_person_phone:{
                maxlength:10,
                mobile_number_india:true,
                notEqual:true
            },
            concerned_person_name: {
              alphaspace:true,
              maxlength: {{General::maxlength('name')}},
              remote: {
                          url: "/businessname_validation",
                          type: "post",
                data: { business_name:
                  
                  function () { return $("#concerned_person_name").val();
                              }
                  
                 }
                      }
            },
           /* concerned_person_name:{
                maxlength:{{General::maxlength('name')}},
                alphaspace:true
            },*/
             concerned_person_designation: {
                alphaspace:true,
                maxlength:28
             },
             company_name: {
              required: true,
              alphaspacename:true,
              remote: {
                          url: "/businessname_validation",
                          type: "post",
                data: { business_name:
                  
                  function () { return $("#company_name").val();
                              }
                  
                 }
                      }
            },
            concerned_person_alternate_phone:{
                maxlength:10,
                mobile_number_india:true
            },
            "proof_of_due[]": {
            file_upload:true,
            },
            "due_amount[]":{
            	maxlengthto_100cr:true
            },
			"due_date[]":{
            	duedate_check:true
            },
			"invoice_no[]":{
				invoice_validate:true
			},
            email:{
            	required: true,
      			email: true
            }
           
        },messages: {
            company_name: {
                    remote:"Business name is not valid"
                  },
                  concerned_person_name: {
                    remote:"Person name is not valid"
                  }
                }
    });


	function trimIt(currentElement){
    	$(currentElement).val(currentElement.value.trim());
	}

	function numbersonly(myfield, e)
    {
        var key;
        var keychar;
        if (window.event)
            key = window.event.keyCode;
        else if (e)
            key = e.which;
        else
            return true;

//        alert(1);
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
		 // console.log("idNum = "+idNum[2]);
         if(d<today){
            // console.log('grace_period  applicable');
            //$(".grace_period").prop("disabled", true);
			$("#grace_period_hidden_"+idNum[2]).val(1);
			$("#grace_period_"+idNum[2]).val('1').prop("disabled", true);
			 $("#grace_period_hidden_"+idNum[2]).val(parseInt($("#grace_period_" + idNum[2]).val()));
            d=today;
            d.setDate(today.getDate() + 1);
         }else{
            // console.log('grace_period not applicable');
            //$(".grace_period").prop("disabled", false);
			$("#grace_period_"+idNum[2]).prop("disabled", false);
			$("#grace_period_hidden_"+idNum[2]).val(parseInt($("#grace_period_"+idNum[2]).val()));
			// console.log("grace period value = "+$("#grace_period_"+idNum[2]).val());
            // d.setDate(d.getDate() + parseInt($('.grace_period').val()));
            d.setDate(d.getDate() + parseInt($("#grace_period_"+idNum[2]).val()));
         }
         var  month = '' + (d.getMonth() + 1),day = '' + d.getDate(),year = d.getFullYear();
            if (month.length < 2) 
                month = '0' + month;
            if (day.length < 2) 
                day = '0' + day;
            // console.log([day,month,year ]);
            //$('.collection_date_block').show();
			$('.collection_date_block_'+idNum[2]).show();
         //$('.collection_date').val([day,month,year ].join('/'));
		 $('#collection_date_'+idNum[2]).val([day,month,year ].join('/'));
    }
	$(document).ready(function(){
		
		if($("input[name=due_amount]").val()){
			convertToINRFormat($("input[name=due_amount]").val(),$("input[name=due_amount]"));
		}
		//$("input[name=due_amount]").keyup(function() {
		  $("body").on('keyup','.invoice_due_amount',function() {	
			convertToINRFormat($(this).val(),$(this));
		});

		if($("#state").val()!=''){
			@if(old('city')) 		
		    var oldCity = "{{old('city')}}";	
			var selected = '';
	    	$("#city").find('option').remove();
	    	//$("#city").append('<option value="">Select</option>');
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
	    	$("#city").append('<option value="">Select</option>');

	     if($("#state").val()!=''){
	     	var stateId =  $("#state").val();
	        $("#maincity option").each(function(){
	        	if($(this).data('state-id')==stateId){
	        		$("#city").append('<option value="'+$(this).val()+'">'+$(this).text()+'</option>');	
	        	}
	        });
	      }  
	    });
	    $('.collection_date_info').tooltip('toggle')
        $('.grace_period_info').tooltip('toggle');

        $('.collection_date_info').tooltip('hide')
        $('.grace_period_info').tooltip('hide');
	    $('.collection_date_block').hide();
        $("input[name=due_date]").on('dp.change',function(){
             set_collection_date();
        });
        //$('.grace_period').on('change',function(){
		  $("body").on('dp.change blur',".collectionsetevent",function(){
            set_collection_date(this.id);
        });
  	});
	
	$("body").on('change','.grace_period',function(){
			var currentId = this.id;
			var idNum = currentId.split('_');
			var due_date = "due_date_"+idNum[2];
			//console.log(currentId+"----------------"+due_date);
			 // return false;
            set_collection_date(due_date);
        });

            $('#GstPan_fieldId').on('change',function(){

            var GstPanNumber=$("#GstPan_fieldId").val();

            var inputvalues = GstPanNumber; 

            if(inputvalues.toString().length == 10){

                var gstinformat =  /([A-Z]){5}([0-9]){4}([A-Z]){1}$/;
                if ( !gstinformat.test(inputvalues)) {    
                    return false; 
                } 

            }   else{
                var gstinformat = /^[0-3|9]{1}[0-9]{1}[A-Z]{3}[A|B|C|F|G|H|L|J|P|T]{1}[A-Z]{1}[0-9]{4}[A-Z]{1}[1-9A-Z]{1}Z[0-9A-Z]{1}$/i;
                if ( !gstinformat.test(inputvalues)) {    
                    return false; 
                } 
            }
                 


           // GstPanNumber="FQWPS9021O";
            $.ajax({
                       method: 'post',
                       url: "{{route('get-customer-deatils')}}",
                       headers: {
                           'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                       },
                       data: {
                           GstPanNumber: GstPanNumber,
                           _token: $('meta[name="csrf-token"]').attr('content')
                       }
                    }).then(function (response) {

                        console.log(response);
                        var data=response.data;
                        var StateId=response.StateId;
                        if(StateId){
                                $("#state").val(StateId);
                                $("#city").find('option').remove();
                                $("#city").append('<option value="">Select</option>');
                                    if(StateId != ''){

                                        $("#maincity option").each(function(){
                                            if($(this).data('state-id')==StateId){
                                                $("#city").append('<option value="'+$(this).val()+'">'+$(this).text()+'</option>');	
                                            }
                                    });
                                    }
                            }
                        if(data){
                            var sectot_id= data.sector_id
                            var user_type= data.user_type
                            if(type_of_business)
                            {
                                $("#user_type").val(user_type);
                            }
                            if(sectot_id)
                            {
                                $("#sector_id").val(sectot_id);
                            }
                            $('.address').prop('readonly', false);
                            $('.concerned_person_phone').prop('readonly', false);
                            $('.company_name').prop('readonly', false);
                            $('.concerned_person_alternate_phone').prop('readonly', false);
                            $('.concerned_person_designation').prop('readonly', false);
                            $('.concerned_person_name').prop('readonly', false);
                            $('.email').prop('readonly', false);
                            $('.pincode').prop('readonly', false);

                            if(data.address)
                            {
                                $(".address").val(data.address);
                                $('.address').prop('readonly', true);
                            }
                            if(data.concerned_person_phone)
                            {
                                $(".concerned_person_phone").val(data.concerned_person_phone);
                                $('.concerned_person_phone').prop('readonly', true);
                            }
                            if(data.company_name)
                            {
                                $(".company_name").val(data.company_name);
                                $('.company_name').prop('readonly', true);
                            }
                            if(data.concerned_person_alternate_phone)
                            {
                                $(".concerned_person_alternate_phone").val(data.concerned_person_alternate_phone);
                                $('.concerned_person_alternate_phone').prop('readonly', true);
                            }
                            if(data.concerned_person_designation)
                            {
                                $(".concerned_person_designation").val(data.concerned_person_designation);
                                $('.concerned_person_designation').prop('readonly', true);
                            }
                            if(data.concerned_person_name)
                            {
                                $(".concerned_person_name").val(data.concerned_person_name);
                                $('.concerned_person_name').prop('readonly', true);
                            }
                            if(data.email)
                            {
                                $(".email").val(data.email);
                                $('.email').prop('readonly', true);
                            }
                            if(data.pincode)
                            {
                                $(".pincode").val(data.pincode);
                                $('.pincode').prop('readonly', true);
                            }    
                        }else
                        {
                            $("#user_type").val("");
                            $("#user_type").val("");
                            $('.address').val("");
                            $('.concerned_person_phone').val("");
                            $('.company_name').val("");
                            $('.concerned_person_alternate_phone').val("");
                            $('.concerned_person_designation').val("");
                            $('.concerned_person_name').val("");
                            $('.email').val("");
                            $('.pincode').val("");
                        }
                        
                    }).fail(function (data) {
                        
                        // alert(data.responseJSON.message);
                    });

        })


$(".Email_Validation").on("change",function(){
     
     var emailid=$("#email").val();

     var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;
         if(regex.test(emailid))
         {
       $.ajax({
            method: 'post',
            url: "{{route('verifyemaiid')}}",
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: {
           emailid: emailid,
              _token: $('meta[name="csrf-token"]').attr('content')
            }
       }).then(function (response) {
        //console.log(response);
        
        if(response.status)
        {
        $("#error_msg").html('');
        $("#error_msg").css("display:'none';");
        }else{
         $("#error_msg").css("display:'';");
         $("#error_msg").html(response.message);

        }
       }).fail(function (data) {
        
       });
     }
})
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
    content: none;
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
    width: 838px;
}
.color input{ background-color:#f1f1f1;}
 .filesImg:before {
    position: absolute;
    bottom: 52px;
    left: -35px;
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
    padding-left: 224px;
    height: 135px !important;
    padding-top: 98px !important;
}



@media screen and (min-width: 1850px) and (max-width: 2000px) {
    .voyager input[type=file] {
        padding-left: 283px !important; }

}

@media screen and (min-width: 1200px) and (max-width: 1400px) {
    .voyager input[type=file] {
        padding-left:146px !important;}
        input[type=file] {
            width: 568px;}

}
@media only screen and (max-width: 600px) {
    .voyager input[type=file] {
    padding-left: 3px !important;
    height: 137px !important;
    padding-top: 89px !important;
    width: 234px;
}
.remove-submitdues{
    margin-top: 10px !important;
}
.filesImg:before {
    position: absolute;
    bottom: 52px;
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
.download_img_addrec
{  pointer-events: none;
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

@endsection