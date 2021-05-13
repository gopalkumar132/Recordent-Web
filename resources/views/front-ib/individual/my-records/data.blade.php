@extends('layouts_front_ib.master')
@section('content')
    <div class="container-fluid" data-select2-id="13">
      <div class="side-body padding-top" data-select2-id="12">
        <div class="container-fluid padding-20">
			<h1 class="page-title">
				<i class="voyager-list"></i> {{-- $records->display_name_plural --}}My Reocords
			</h1>
			<ul class="name_title">
				<li>	
					<a href="" class="btn btn-primary paymentHistoryButton" data-toggle="modal" data-target="#paymentHistory" data-due-id="1" title="Payment History">
						<i class="fa fa-history" aria-hidden="true"></i> = Payment History
					</a>
				</li>
			</ul>
	  	</div>
		@if (count($errors) > 0)
		<div class="alert alert-danger">
			<ul>
			 @foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			 @endforeach
			</ul>
		</div>
		@endif
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
										@foreach($studentDueData as $data)
										<tr>
											<td>{{date('d/m/Y', strtotime($data->ReportedAt))}}</td>
											<td>{{date('d/m/Y', strtotime($data->due_date))}}</td>
											<td>{{General::diffInDays($data->due_date)}}</td>
											<td>{{General::ind_money_format($data->due_amount)}}</td>

											<td class="balance">{{General::ind_money_format($data->due_amount - General::getPaidForDue($data->dueId))}}</td>
											<td><div class="wrap-table-text">{{$data->due_note}}</div></td>
											<td align="center">
												@if(!empty($data->proof_of_due))
														{{--<img class=" img-responsive" src="{{config('app.url').Storage::url($data->proof_of_due)}}" height="100" width="100"/>--}}
														<a target="_blank" href="{{config('app.url').Storage::url($data->proof_of_due)}}">View/Download</a>

												@endif
											</td>
											<td>
												<a href="" class="btn btn-primary paymentHistoryButton" data-toggle="modal" data-target="#paymentHistory" data-due-id="{{$data->dueId}}" title="Payment History">
													<i class="fa fa-history" aria-hidden="true"></i>
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


		
		$('.paymentHistoryButton').on('click', function () { 
			$("#paymentHistory").find(".modal-body").css('display','none');
		    var element = $(this);
		    var dueId = $(this).data('due-id');
					$.ajax({
					   method: 'post',
					   url: "{{route('front-individual.my-payment-history')}}",
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
			/*$.ajax({
			   method: 'post',
			   url: "{{route('student-payment-history-delete')}}",
			   headers: {
				   'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			   },
			   data: {
				   payment_id: paymentId,
				   _token: $('meta[name="csrf-token"]').attr('content')
			   }
		   	}).then(function (response) {
		   		element.attr('disabled',true);
		   		alert('Deleted successfully')
		   	}).fail(function (data) {
		   		alert(data.responseJSON.message);
		  	});*/
    	});
				   	
    	
    	
	</script>
    <!-- End Model Pay Outstanding Amount -->
	
@endsection