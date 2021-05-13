<div class="table-responsive">
    <table class='table table-hover'>
    @foreach($paymentHistory as $history)
    <tr>
        @if(isset($history->payment_options_drop_down))
        <td><b style="color: red;">{{$history->payment_options_drop_down}}</b><br>
            <b>Rs.{{General::ind_money_format($history->paid_amount)}}  - {{$history->paid_note}}</b><br>{{date('F d, Y',strtotime($history->paid_date))}}</td>
        @else
        <td><b>Rs.{{General::ind_money_format($history->paid_amount)}}  - {{$history->paid_note}}</b><br>{{date('F d, Y',strtotime($history->paid_date))}}</td>
        @endif
        {{--<td>
        	@if(!empty($history->deleted_at)) 
        			<button data-id="{{$history->id}}" class="removePaymentHistory" style="opacity:0.6" disabled> 
        		@else 
        			<button data-id="{{$history->id}}" class="removePaymentHistory" data-toggle="modal" data-target="#paymentHistoryDelete"> 
        		@endif
    			
        		<i class="voyager-trash"></i>
        	 </button>
    	</td>
        --}}
    </tr>
    @endforeach                                
    </table>
</div>