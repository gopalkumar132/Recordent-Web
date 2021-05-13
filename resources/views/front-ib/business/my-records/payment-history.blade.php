<div class="table-responsive">
<table class='table table-hover'>
	@foreach($paymentHistory as $history)
	<tr>
		<td><b>Rs.{{General::ind_money_format($history->paid_amount)}}  @if(!empty($history->paid_note)) - {{$history->paid_note}} @endif</b><br>{{date('F d, Y h:i A',strtotime($history->paid_date))}}</td>
		<td>
			{{--@if(!empty($history->deleted_at)) 
					<button data-id="{{$history->id}}" class="removePaymentHistory" style="opacity:0.6" disabled> 
				@else 
					<button data-id="{{$history->id}}" class="removePaymentHistory" data-toggle="modal" data-target="#paymentHistoryDelete"> 
				@endif

				<i class="voyager-trash"></i>
			 </button>--}}
		</td>
	</tr>
	@endforeach                                
</table>
</div>