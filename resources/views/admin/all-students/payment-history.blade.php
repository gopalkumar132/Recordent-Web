<div class="table-responsive">
    <table class='table table-hover'>
    @foreach($paymentHistory as $history)
    <tr>
        <td><b>Rs.{{General::ind_money_format($history->paid_amount)}}  - {{$history->paid_note}}</b><br>{{date('F d, Y',strtotime($history->paid_date))}}</td>
        {{--<td>
        	@if(!empty($history->deleted_at)) 
        			<button data-id="{{$history->id}}" class="removePaymentHistory" style="opacity:0.7" disabled> 
        		@else 
        			<button data-id="{{$history->id}}" class="removePaymentHistory"> 
        		@endif
    			
        		<i class="voyager-trash"></i>
        	 </button>
    	</td>--}}
    </tr>
    @endforeach
    </table>
</div>>