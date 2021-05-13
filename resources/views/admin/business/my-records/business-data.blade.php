@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' My Business Records')

@section('page_header')
    <h1 class="page-title">
        
        <i class="voyager-list"></i> {{-- $records->display_name_plural --}}My Business Records
        <div id="addDue" class="pull-right">
			<a href="" class="btn btn-success" data-toggle="modal" data-target="#outstanding">
				<i class="voyager-plus"></i> Add
			</a>
   		</div>
        <div id="more" class="pull-right" style="display: none; padding-left: 10px">
			<a href="" class="btn btn-danger" data-toggle="modal" data-target="#pay">
				<i class="voyager-check"></i> Pay				
			</a>
   		</div>
    </h1>
     <ul class="name_title">
        <li>	<a href="" class="btn btn-success addPayButton" data-toggle="modal" data-target="#pay" data-due-id="1" title="Pay" data-customer-no="44" data-invoice-no="121">											 
					<i class="fa fa-money btn-success" aria-hidden="true"></i> = Pay
				</a>
            	<a class="btn btn-warning editDueButton" data-due-id="1" title="Edit">
					<i class="voyager-edit"></i> = Edit 
				</a>
				<a href="" class="btn btn-primary paymentHistoryButton" data-toggle="modal" data-target="#paymentHistory" data-due-id="1" title="Payment History">
					<i class="fa fa-history" aria-hidden="true"></i> = Payment History
				</a>
				<a href="" class="btn btn-danger dueDeleteButton" data-toggle="modal" data-target="#dueDelete" data-due-id="1" title="Delete Record">
					<i class="voyager-trash"></i> = Delete
				</a>
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
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script> 
    <div class="page-content container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                    	<div class="table-responsive">
	                        <table id="dataTable" class="table table-hover fixed_headerss">
	                            <thead>
									<tr>
										
										<th>Reported Date</th>
										<th>Due Date</th>
										<th>Outstanding Days</th>
										<th>Reported Due</th>
										<th>Balance Due</th>
										<th>Notes</th>
										<th>Proof of Due</th>
										<th class="actions">{{ __('voyager::generic.actions') }}</th>
									</tr>
	                            </thead>
	                            <tbody>
	                                @foreach($businessDueData as $data)
	                                <tr>
	                                	
	                                    <td>{{date('d/m/Y', strtotime($data->ReportedAt))}}</td>
	                                    <td>{{date('d/m/Y', strtotime($data->due_date))}}</td>
	                                    <td>{{General::diffInDays($data->due_date)}}</td>
	                                    <td>{{General::ind_money_format($data->due_amount)}}</td>
	                                    
	                                    <td class="balance">{{General::ind_money_format($data->due_amount - General::getPaidForDueOfBusiness($data->dueId, Auth::id()))}}</td>
	                                    <td><div class="wrap-table-text">{{$data->due_note}}</div></td>
	                                    <td align="center">
	                                		@if(!empty($data->proof_of_due))
	                                				{{--<img class=" img-responsive" src="{{config('app.url').Storage::url($data->proof_of_due)}}" height="100" width="100"/>--}}
	                                				<a target="_blank" href="{{config('app.url').Storage::url($data->proof_of_due)}}">View/Download</a>
	                                            
	                                		@endif
	                                	</td>
	                                    <td>
	                                    	<a href="" class="btn btn-success addPayButton" data-toggle="modal" data-target="#pay" data-due-id="{{$data->dueId}}" title="Pay">											 
												<i class="fa fa-money" aria-hidden="true"></i>
											</a>
	                                    	<a class="btn btn-warning editDueButton" data-due-id="{{$data->dueId}}" title="Edit">
												<i class="voyager-edit"></i> 
											</a>
											<a href="" class="btn btn-primary paymentHistoryButton" data-toggle="modal" data-target="#paymentHistory" data-due-id="{{$data->dueId}}" title="Payment History">
												<i class="fa fa-history" aria-hidden="true"></i>
											</a>
											<a href="" class="btn btn-danger dueDeleteButton" data-toggle="modal" data-target="#dueDelete" data-due-id="{{$data->dueId}}" title="Delete Record">
												<i class="voyager-trash"></i>
											</a>
											
								   		</td>
	                                </tr>
	                                @endforeach
	                            </tbody>
	                        </table>
                    	</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Start Model Submit Outstanding Record -->  
    <div class="modal" id="outstanding" tabindex="-1" role="dialog">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h3 class="modal-title">Submit Outstanding Record</h3>
		  </div>
		  <div class="modal-body">
			<form action="{{ route('business.store-due', $businessId) }}" method="POST" enctype="multipart/form-data">
				@csrf	
                <div class="form-group">
					<label for="due_date">*Due Date</label>
					<input type="date" class="form-control" name="due_date" value="{{date('Y-m-d', strtotime(Carbon\Carbon::now()))}}">
				</div>							
				<div class="form-group">
					<label for="due_amount">*Amount Due</label>
					<input type="text" class="form-control" name="due_amount" value="">
				</div>							
				<div class="form-group">
					<label for="due_note">Note</label>
					<textarea class="form-control" name="due_note" maxlength="300"></textarea>
				</div>	
				<div class="form-group">
					<label for="due_note">Proof of Due</label>
					<input type="file" class="form-control" name="proof_of_due">
					<label for="contact_phone">Note: Only jpeg,bmp,png,gif,svg,pdf files are allowed</label>
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
    
    <!-- Start Model Pay Outstanding Amount -->  
    <div class="modal" id="pay" tabindex="-1" role="dialog">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h3 class="modal-title">Pay Outstanding Amount</h3>
		  </div>
		  <div class="modal-body">
			<form action="{{ route('business.business-store-pay', $businessId) }}" method="POST">
				@csrf	
                <input type="hidden" name="outstanding" value="">	
									
				<div class="form-group">
					<label for="due_amount">*Amount Due</label>
					<input type="text" class="form-control" name="due_amount" id="due_amount" value="" readonly>
				</div>						
				<div class="form-group">
					<label for="pay_date">*Pay Date</label>
					<input type="date" class="form-control" name="pay_date" value="{{date('Y-m-d', strtotime(Carbon\Carbon::now()))}}">
				</div>								
				<div class="form-group">
					<label for="due_amount">*Paid Amount (Minimum: Rs. 1)</label>
					<input type="text" class="form-control" name="pay_amount" min="1" value="" >
				</div>							
				<div class="form-group">
					<label for="due_note">Note</label>
					<textarea class="form-control" name="due_note"></textarea>
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

	<!-- Due delete Model -->
	<!-- Start Model Edit Outstanding Amount -->  
    <div class="modal" id="edit" tabindex="-1" role="dialog">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h3 class="modal-title">Edit Outstanding Amount</h3>
		  </div>
		  <div class="modal-body">
			<form action="{{ route('business.business-edit-due', $businessId) }}" method="POST" enctype="multipart/form-data">
				@csrf
				@method('put')			
				<input type="hidden" name="outstanding" value="">	
               					
				<div class="form-group">
					<label for="due_date">*Due Date</label>
					<input type="date" class="form-control" name="due_date" value="{{date('m-d-Y', strtotime(Carbon\Carbon::now()))}}">
				</div>							
				<div class="form-group">
					<label for="due_amount">*Amount Due</label>
					<input type="number" class="form-control" name="due_amount" value="" readonly>
				</div>							
				<div class="form-group">
					<label for="due_note">Note</label>
					<textarea class="form-control" name="due_note" maxlength="300"></textarea>
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
				<div class="form-action pull-right">
					<button type="submit" class="btn btn-primary">SUBMIT</button>
					<button type="reset" class="btn btn-secondary" data-dismiss="modal">CANCEL</button>
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
			<h3 class="modal-title">Delete Record</h3>
		  </div>
		  <div class="modal-body">
			<form action="{{ route('business.business-delete-due')}}" method="POST">
				@csrf			
				<input type="hidden" name="due_id" value="">	
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






	<!--- Payment History Model-->
	 <div class="modal" id="paymentHistory" tabindex="-1" role="dialog">
	  <div class="modal-dialog" role="document">
		<div class="modal-content">
		  <div class="modal-header">
			<h3 class="modal-title">Payment History</h3>
		  </div>
		  <div class="modal-body">
			
		  </div>
		  <div class="modal-footer">
		  	<button type="reset" class="btn btn-primary" data-dismiss="modal">close</button>
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

  	
   	<script type="text/javascript">
   		$("input[name=agree_terms]").on('change',function(){
   			if($(this).is(':checked')){
   				$(this).parents().parents().parents().parents().find("button[type=submit]").attr('disabled',false);
   			}else{
   				$(this).parents().parents().parents().parents().find("button[type=submit]").attr('disabled',true);
   			}
   		});
		/*$("#all").change(function(){
			if($(this).is(':checked')){
				$("#addDue").hide();
				$("#more").show();
				$('.student-check').prop("checked", true);
			}else{
				$("#addDue").show();
				$("#more").hide();
				$('.student-check').prop("checked", false);
			}
		});*/
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


		

		$('.addPayButton').on('click', function () { 
			var element = $(this);
		    var dueId = $(this).data('due-id');
			
			var amount = $(this).closest('tr').find('.balance').text();

			amount = amount.replace(/,/g, "");
			$("#due_amount").val(amount);
			$("#pay").find(".modal-body").find('input[name=pay_amount]').attr('max',amount);
			
			@if(Auth::user()->user_type==2)
				var customerNo =  $(this).data('customer-no');
				var invoiceNo =  $(this).data('invoice-no');
				$("#pay").find(".modal-body").find('input[name=customer_no]').val(customerNo);
				$("#pay").find(".modal-body").find('input[name=invoice_no]').val(invoiceNo);
			@endif

			$("#pay").find(".modal-body").find('input[name=outstanding]').val(dueId);		
			
			   
    	});

		$('.dueDeleteButton').on('click', function () { 
			var element = $(this);
		    var dueId = $(this).data('due-id');

			$("#dueDelete").find(".modal-body").find('input[name=due_id]').val(dueId);		
			
			   
    	});
		$('.editDueButton').on('click', function () {
			var dueId = $(this).data('due-id');
			$.ajax({
				method: 'GET',
				url: "{{route('business.edit-due-data')}}",
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
					$("#edit").find(".modal-body").find('input[name=outstanding]').val(data.id);
					
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

		});
		$('.paymentHistoryButton').on('click', function () { 

			$("#paymentHistory").find(".modal-body").css('display','none');
		    var element = $(this);
		    var dueId = $(this).data('due-id');
					$.ajax({
					   method: 'post',
					   url: "{{route('business.business-payment-history1')}}",
					   headers: {
						   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					   },
					   data: {
						   due_id: dueId,
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
    <!-- End Model Pay Outstanding Amount -->
	
@endsection