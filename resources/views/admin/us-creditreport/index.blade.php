@extends('voyager::master')

@section('page_title', __('voyager::generic.create').' Submit Dues')

@section('page_header')

<style>
body, html {
           margin: 0px auto;
           padding: 30px 0px;
           background-color: #f9f9f9 !important;
           font-family: 'Open Sans', sans-serif;
    }
	.app-container.expanded .content-container .side-menu {
    width: 250px;
    top: 0px !important;
}


.app-container .content-container .side-menu {
    overflow-y: auto;
    z-index: 9999;
    position: fixed;
    width: 60px;
    top: 0px;
    height: 100%;
    transition: width .25s;
}

.panel-txt{
		margin: 0px auto;
		text-align: left;
		padding: 10px 0px;
		border-radius: 6px;
	}
	.panel-txt h1{
		display: inline-block;
		height: auto;
		font-size: 25px;
		margin: 0px;
		margin-top: 0px;
		color: #555;
		font-weight: 800;
		line-height: 29px;
	}
	.panel-txt h1 > img {
		padding: 0px 15px 0px 0px;
		width: 70px;
		height: 55px;
	}
	.page-title3{
		font-size: 23px;
		margin-top: -15px;
		margin-bottom: 20px;
		color: #5f94c4;
		font-weight: 600;
		line-height: 0px;
		text-align: left;
		/* padding-left: 20px;
		padding-bottom:15px; */
	}
	.errors
    {
        text-align: left;
        position: relative;
        margin-left: -30%;
    }
	.requestbtn{
		background-color: #273581;
		border: 1px solid #273581;
		border-radius: 8px;
		color: #fff;
		padding: 0px;
		font-weight: 700;
		line-height: 40px;
		width: 400px;
		height: 50px;
		margin-top: 32px;
	}
	.requestbtn:hover{
		background-color: #fff;
		border: 1px solid #273581;
		border-radius: 8px;
		color: #273581;
		padding: 0px;
		font-weight: 700;
		line-height: 40px;
		width: 400px;
		height: 50px;
		margin-top: 32px;
	}
	.label_style{
		color:#000 !important;
	}
	.usreportbtn {width:400px;}

	.Ustext{
		text-align:center;
	}
	@media only screen and (max-width: 600px) {
		.Ustext{
		text-align:none;
	}

		.page-title3{
		    font-size: 18px;
    margin-top: -61px;
    margin-bottom: 20px;
    color: #5f94c4;
    font-weight: 600;
    line-height: 22px;
    text-align: left;
    padding-left: 20px;
    padding-bottom: 16px;
	}

	.usreportbtn {width:200px;}
	}
	.app-container .content-container .side-body.padding-top {
    padding-top: 12px !important;
}
</style>

    <h1 class="">
        
			<div class="col-md-12">
				<div class="" style="text-align:center ;font-weight:600;">
					<div class="" ><img class="card-img-top" src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAADAAAAAwCAYAAABXAvmHAAADj0lEQVRoge2V72tTVxzGv3S9FWNC5w8oNmkxaTUIuUJpp1C5WjVbuuKgWN/U2GHfTC2GNZd4q1JaqVs1yujIoBsbaxfpmHvhRl/4hyiUDtZqbmmtWqWJP5IXJjy+6O09tzHVBs4lBPLAB85zzjc35wM3hKiUUkoppdD5hIgsRGQrMiza3Wnr8eM3YDZPv+riDhFtJSLa1dZ2HeHwJAAgHJ7U4dnj4RGuLLadBBHtIiJyt7YOY3j4XyjKBBRlAhcvMhRlAkof2+/TZj6E/jlDfzV+5+OM/bWhmdd/TyI+eBNE5CYicvt832Fo6C5k+TZk+TYAIBiMQpajel89k+XoSg9GIQejCK527czYg1pf3eMdXeBz7zX0999BIDCOQGAsi/F11sae63PGmXFzBY4cGYKiTAAAzp79VYdnf/3nXW68+eceEiO/MIGWlqvo7f0D3d2jpvGs+xtuPO8N4XHLF0xAkgZw/vxv8Psj8PsjAKCv9X4q8v75qQ/MG7rfH8HS6R5uvOjpw+LhdibQ3NyPM2dG0dHxg2mkny7xY+kF0gtPmMCBA1fQ2fkjAKz58+HZzYgu0Nh4Ce3tt+D1XjONt/8/4sesirf/zTCBhgYFPt/3kKQBSNIAAOhrHl2SBvBY+pIbi74TWGg4xAREMQRJGkRT02XTmG86yI0F6Rjm933GBPbuldHUeBkA4BFDK3hCAADRE4IohiBq3SOudI9o6NoMAHhyzXtCePnTGD9+juJl5Hcm4Hb3Ys+eb03F1B9xXV0AtbU9OgC49tU90wSczguwWr82lSdHO7jy7EQ3E6ip6YHF0gUAsFi6dHj2R5ZtXJmr2c0EqqvPYfPm06Yy727mysL+ViaQyWRMeUfNTCaTYQLpdLrQ98k7awQebndjzuaCuqmmKJizufBwu5sJzFbWQ7W5EBMciFVoCAYqHGvPcpE9n+s81zp7L/s7s58pOKBucWL203omMGNzQbU6ERPsiJVnIRgot+eeycV6c9nPEdaZFdZ/lmp1YsbmYgKpVKrQr3TeSaVSTCCZTBb6PnknmUwygekKB1SrC7GynUWBanVhusLBBKYEO1RbXcEvtmEBWx2mBLtBoLy6+ATKq5lAIpEo9CuddxKJBBOIx+OFvk/eWV5eZgL3y6owV7m7qHhQVqUL1N8vq0IxQkT1RERVROQlos4iw6vdnTYR0Q4ishcZO7S7l1JKKaUUMO8AngFZRbbvS9EAAAAASUVORK5CYII=" width="45" height="50">US Business Credit Report
				</div>
				</div>
			</div>
        
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
<style type="text/css">input,textarea{};</style>
    <div class="page-content container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12" style="padding-top:20px;">				
                <div class="panel panel-bordered">				
                    <div class="panel-body">
					<div class="panel-txt">
					    <h1></h1>		
						<div class="page-title3 col-md-6  col-lg-12 Ustext">Reduce risk by checking US business's credit report for Rs. 6000** only.
						</div>											
						<!-- <p class="page-title3 col-md-4">Reduce risk by checking US business's credit report for Rs. 6000 only.</p> -->
                    	<p style="color:red;font-weight:bold; margin: 15px;">Fields with * are Mandatory</p>
						
						
						<form action="{{route('add-record-storereference')}}" name="add_store_record" id="add_store_record" method="POST" enctype="multipart/form-data">
							@csrf	
							
                            <div class="submitdues-mainbody">
                            <div class="col-md-12">
								<div class="form-group">
									<label for="contact_phone">Business Name*</label>
									<input type="text" class="form-control" name="business_name" value="{{old('person_name')}}" placeholder="Business Name" required onblur="trimIt(this);">
								</div>
                            </div>
							<div class="clearfix"></div>
                            <div class="col-md-12">
                                <div class="form-group">
									<label for="contact_phone">Address*</label>
									<input type="text" class="form-control" name="address" value="{{old('father_name')}}" placeholder="address details" required onblur="trimIt(this);">
								</div>
                            </div>                            
                            <div class="clearfix"></div>
                            
							<!--<div class="col-md-12">
								<div class="form-group">
									<label for="contact_phone">State*</label>
									<input type="text" class="form-control" name="state_name" value="{{old('state_name')}}" placeholder="State" required onblur="trimIt(this);">
								</div>
								</div>
								<div class="clearfix"></div>
								<div class="col-md-12">
									<div class="form-group">
										<label for="contact_phone">City*</label>
										<input type="text" class="form-control" name="mother_name" value="{{old('mother_name')}}" placeholder="City" required onblur="trimIt(this);">
									</div>
								</div>
							-->
							
							<div class="col-md-12">
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

					        <div class="col-md-12">
	                        	<div class="form-group">
	                        		<label for="contact_phone">City*</label>
									<select name="city" id="city"  placeholder="Select city" class="form-control" required >
							            <option value="">Select</option>
							        </select>
					        	</div>
					        </div>
							
                            <div class="clearfix"></div>                            
							 <div class="col-md-12">
	                            <div class="form-group">
									<label for="contact_phone">Zip Code*</label>
									<input type="tel" class="form-control number" name="zip_code" value="{{old('contact_phone')}}" placeholder="Zip code" required onblur="trimIt(this);" maxlength="12" onkeypress="return numbersonly(this,event)">
								</div>
							</div>
							<div class="clearfix"></div> 
							</div>
                            <div class="col-md-12">
								<div class="col-md-12">
								<div class="form-action ">
									<label for="contact_phone">By Clicking on 'Continue' I Agree Recordent`s <a target="_blank" href="https://www.recordent.com/terms-and-conditions">Terms & Conditions</a> & <a target="_blank" href="https://www.recordent.com/end-user-license-agreement">End User License Agreement</a></label>
								</div>
								</div>
							</div>
							
                             <div class="col-md-12">
								<div class="col-md-12">
								<div class="form-action ">
									<!-- <button type="submit" class="requestbtn">Continue</button> -->
									<button type="submit" class="usreportbtn btn btn-info  btn-blue">Continue</button>
								</div>
								<span class="pull-right" style="color:red;">**Taxes excluded</span>
								</div>
								<!--
									<div class="col-md-6">
										<div class="form-action ">
										<i class="voyager-plus"></i>
										<button type="button" class="  btn btn-primary btn-blue add-record-submitdues" id="add-record">Continue</button>
										</div>	
									</div>
								-->
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
	
<script src="{{asset('js/jquery.validate.min.js')}}"></script>
<script src="{{asset('js/number-to-word.js')}}"></script>

<script type="text/javascript">

$(document).ready(function() {
	
	//Add daily status validations
    /*
	$("#add_store_record").validate({
        debug: false,
        errorClass: "error",
        errorElement: "span",
        
        errorPlacement: function (error, element) {
            error.appendTo(element.parent()).css('color', 'red');
        },
        rules: {
            business_name: {
                required: true,
                maxlength: 20,
            },
            address: {                
                required: true,
                maxlength: 20
            },
            username: {
                username: true,
                required: true,
                maxlength: 20,
                Checkusername:true,
            },
            contact: {
                required: true,
                email: true,
                CheckEmailExist: true,
            },
            active: {
                required: true,
            }
        },
        messages: {
            business_name: {
                required: "Please enter Your business name.",
            },
            address: {               
                required: "Please enter your address.",
            },
            username: {
                required: "Please enter Username.",
                username: "Only alphabets, space, dot(.) are allowed.",
                Checkusername:"Username already exists.",
            },
            contact: {
                required: "Please enter Email Id.",
                email:"Please enter valid email address.",
                CheckEmailExist: "Email Id already exists.",

            },
            active: {
                required: "Please Select Member Role."
            },
        },
        highlight: function (element) {
            $(element).removeClass("error");
        }
    }); */
	/* ends of messages for add business report */
	
	
	$('body').on('focus','.datepicker',function(){
    $(this).datetimepicker();
});
	
	var rowNum = 1;
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
			  var numAddRows = $('.copy-multiple-submitdues-copy').length;
			  if(numAddRows< <?php echo Config::get('constants.add_rows.limit');?>) {
				  
				  var invoiceRowCount = numAddRows+1;
		  $(".submitdues-mainbody").append('<div class="clearfix" id="copy-multiple-submitdues-copy-scroll'+invoiceRowCount+'"></div><div style="background-color:#F7F6F6" class="copy-multiple-submitdues-copy"><div style="border-top:2px dotted black"></div><div style="padding-left:10px; id="add_record_count"><b>Add Record No:</b> <button class="btn btn-info btn-sm"><b>'+invoiceRowCount+'</b></button></div><div class="col-md-6"><div class="form-group invoiceno_check_errclass"><label for="contact_phone">Invoice No</label><input type="text" class="form-control invoice_number" maxlength="20" id="invoice_no_'+rowNum+'" name="invoice_no[]" value="{{old('invoice_no')}}" placeholder="Invoice No" onblur="trimIt(this);"></div></div><div class="col-md-6"><div class="form-group dueamount_check_errclass"><label for="contact_phone">Invoice/Due Amount*</label><input type="text" class="form-control invoice_due_amount" id="due_amount_'+rowNum+'" name="due_amount[]" value="{{old('due_amount')}}" required placeholder="Due Amount*" onblur="trimIt(this);" onkeypress="return numbersonly(this,event)"><label class="dueAmountInWord_'+rowNum+'" style="display: none"></label></div></div><div class="col-md-6"><div class="form-group duedate_check_errclass"><label for="contact_phone">Due Date (DD/MM/YYYY)*</label><input type="text" id="due_date_'+rowNum+'" name="due_date[]" data-provide="datepicker" class="form-control datepicker collectionsetevent" data-date-format="DD/MM/YYYY" required aria-controls="dataTable" value="{{old('due_date')}}"></div></div><div class="col-md-6"><div class="form-group"><label for="grace_period">Grace period *  <span class="grace_period_info"  data-toggle="tooltip" data-placement="top" title="Grace Period is a set length of time after the due date during which the payment can be made. This may differ depending on your sector of business and your terms with the Customer"><i class="fa fa-info-circle"></i></span></label><select class="form-control grace_period" id = "grace_period_'+rowNum+'" name="grace_period[]" disabled=""><option value="1">1 day</option>'+ options_dynamic+'</select></div><input type="hidden" name="grace_period_hidden[]" id="grace_period_hidden_'+rowNum+'" value=""/></div><div class="col-md-6"><div class="form-group collectiondateblock collection_date_block_'+rowNum+'"><label for="collection_date">Collection Start Date (DD/MM/YYYY)*  <span class="collection_date_info" data-toggle="tooltip" data-placement="top" title="Collection Start Date is the date on which Recordent will start contacting the Customer to recover the dues"><i class="fa fa-info-circle"></i></span></label><input id="collection_date_'+rowNum+'" type="text" name="collection_date[]" class="form-control datepicker collection_date" placeholder="" data-date-format="DD/MM/YYYY" required aria-controls="dataTable" readonly value="{{old('collection_date')}}"></div></div><div class="clearfix"></div><div class="col-md-12"><div class="form-group"><label for="contact_phone">Due Note</label><textarea class="form-control" name="due_note[]" rows="5" maxlength="300" placeholder="Due Note" onblur="trimIt(this);" onkeypress="return blockSpecialChar(this,event)">{{old('due_note')}}</textarea></div></div><div class="col-md-6"><div class="form-group proofofdue_check_errclass"><label for="contact_phone">Proof Of Due</label><input type="file" accept=".jpg,.png,.jpeg,.pdf,.doc,.docx,.xls,.xlsx,.bmp,.csv" class="form-control fl-upload-height"  id="proof_of_due_'+rowNum+'" name="proof_of_due[]"><p for="contact_phone">Note: Only pdf,doc,docx,jpeg,png,bmp,xls,xlsx,csv files are allowed</p></div></div><div class="input-group-btn"> <button class="btn btn-danger remove-submitdues" style="margin-top:30px;" type="button"><i class="glyphicon glyphicon-remove"></i> Remove</button></div></div>');
		  
		  
		  $(window).scrollTop($('#copy-multiple-submitdues-copy-scroll'+invoiceRowCount).offset().top-20);
			  }
		  //$(this).attr('id','testidddd');
		  
      });
	  
      $("body").on("click",".remove-submitdues",function(){
          $(this).parents(".copy-multiple-submitdues-copy").remove();
      });	  	  	  	  	

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
    </script>
<script language="javascript" type="application/javascript">
	convertToINRFormat = function(value, inputField) {
		var number = Number(value.replace(/,/g, ""));
		withComma = number.toLocaleString('en-IN');
		if(withComma!=0 && withComma!='NaN'){
			$(inputField).val(withComma);
		}else{
			$(inputField).val('');
		}
	};
	$.validator.addMethod("alphaspace", function(value, element) {
        return this.optional(element) || /^[a-z ]+$/i.test(value);
    }, "Only alphabet and space allowed.");

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
    $.validator.addMethod("file_upload", function(value, element) {
        return this.optional(element) || /(\.jpg|\.jpeg|\.png|\.gif|\.bmp|\.svg|\.pdf|\.doc|\.doc|\.docx)$/i.test(value);
    }, "Please select a valid file.");

	

    /*$.validator.addMethod("maxlengthto_1cr", function(value, element) {
    	var number = Number(value.replace(/,/g, ""));
        return this.optional(element) || number<=10000000;
    }, "Due amount can not be greater than 1,00,00,000");*/
	
	$.validator.addMethod("dob_check", function(value, element) {
			var returnFlag = true;
			var currentDate = new Date();
			var dateString = value;
			var dateParts = dateString.split("/");
			var dateObject = new Date(+dateParts[2], dateParts[1] - 1, +dateParts[0]);
			if(dateObject.getTime() > currentDate.getTime()) {
				returnFlag = false;
			}
        return returnFlag;
    }, "DOB should not greater than current date");
	
	
	$.validator.addMethod("maxlengthto_1cr", function (value, element) {
    var flag = true;
	var error_count = 0;
	$("[name^=due_amount]").each(function (i, j) {
		$(this).parent('.dueamount_check_errclass').find('label.error').remove();
		var thisValue = $(this).val();
		var number = Number(thisValue.replace(/,/g, ""));
		if ($.trim($(this).val()) == '') {
			//flag = false;
			error_count++;
			$(this).parent('.dueamount_check_errclass').append('<label  id="duedate_check'+i+'-error" class="error">This field is required.</label>');
		} else if(number >= 10000000) {
			$(this).parent('.dueamount_check_errclass').append('<label  id="duedate_check'+i+'-error" class="error">Due amount can not be greater than 1,00,00,000.</label>');
		}
	});
		var error_count_flag = error_count > 0 ? false:true;
		return error_count_flag;
	}, "");
	
	$.validator.addMethod("invoice_validate", function(value, element) {
        var flag = true;
		var error_count = 0;
		$("[name^=invoice_no]").each(function (i, j) {
		$(this).parent('.invoiceno_check_errclass').find('label.error').remove();
		var thisValue = $(this).val();
		var pattern = /^[A-Za-z0-9/*(),#+-]+$/i;
		var check_pattern = pattern.test(thisValue);
			if(thisValue!=""){
				if(thisValue.length > 3) {
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
	



	
	/*$.validator.addMethod("invoice_duplicate_check", function (value, element) {
    var flag = true;
		var ar = $("[name^=invoice_no]"]).map(function() {
			if ($(this).val() != '') return $(this).val()
		}).get();

		//Create array of duplicates if there are any
		var unique = ar.filter(function(item, pos) {
			return ar.indexOf(item) != pos;
		});
		if(unique.length != 0)) {
		$(this).parent('.invoiceno_check_errclass').append('<label  id="duedate_check'+i+'-error" class="error">Only alphanumeric characters allowed.</label>');
		flag = false;
		} else {
			$(this).parent('.invoiceno_check_errclass').append('');
		}
		return flag;
		
	}, "");*/
	
	
	

	$('#add_store_record').validate({
		ignore: '',
        rules: {
			
            business_name: {
              required: true,
              maxlength:200
            },
			address: {
              required: true,
              maxlength:250
            },

			dob: {
			  dob_check:true	
			},
            zip_code:{
				required: true,
                maxlength:12,
                /*mobile_number_india:true*/
            },
            father_name: {
              alphaspace:true,
              maxlength:28
            },
			state_name: {
              alphaspace:true,
              maxlength:28
            },			
            mother_name: {
              alphaspace:true,
              maxlength:28
            },
            "proof_of_due[]": {
            file_upload:true,
            },
            "due_amount[]":{
            	maxlengthto_1cr:true
            },
			"due_date[]":{
            	duedate_check:true
            },
			"invoice_no[]":{
				invoice_validate:true
			},
            email:{
                email: true
            }
            
        }
    });

    

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
         if(d<today){
            // console.log('grace_period  applicable');
			$("#grace_period_"+idNum[2]).val('1').prop("disabled", true);
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
		}
		$("input[name=due_amount]").keyup(function() {
			convertToINRFormat($(this).val(),$(this));
		});*/
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
@endsection