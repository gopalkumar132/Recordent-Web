<div class="modal-body">
    <p>
        You have exceeded your allowance of {{$user_plan_data['free_customer_limit']}} customer as per your {{$user_plan_data['plan_name']}} plan.
        Below are the details of charges applicable.
    </p>
    <div class="table-responsive">
        <table id="dataTable" class="table table-hover fixed_headerss">
            <thead>
                <tr>
                    <th>Details</th>
                    <th>No. of customers</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>₹ {{$user_plan_data['additional_customer_price']}} per additional customer</td>
                    <td>{{$totalSkippedRecordCount}}</td>
                    <td>₹ {{$user_plan_data['additional_customer_price']*$totalSkippedRecordCount}}</td>
                </tr>
                @if($user->state_id != 36)
                    <tr>
                        <td>IGST</td>
                        <td></td>
                        <td>₹ {{number_format(($user_plan_data['additional_customer_price']*$totalSkippedRecordCount*18)/100, 2)}}</td>
                    </tr>
                @else
                    <tr>
                        <td>CGST</td>
                        <td></td>
                        <td>₹ {{number_format(($user_plan_data['additional_customer_price']*$totalSkippedRecordCount*9)/100, 2)}}</td>
                    </tr>
                    <tr>
                        <td>SGST</td>
                        <td></td>
                        <td>₹ {{number_format(($user_plan_data['additional_customer_price']*$totalSkippedRecordCount*9)/100 ,2)}}</td>
                    </tr>
                @endif
                <tr>
                    <td>TOTAL</td>
                    <td></td>
                    <td>₹ {{$user_plan_data['additional_customer_price']*$totalSkippedRecordCount + ($user_plan_data['additional_customer_price'] * $totalSkippedRecordCount*18)/100}}</td>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td>
                        <h6>*convenience fee applicable</h6>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    @if(HomeHelper::showOrHidePlanUpgradeButton())
        @if(Auth::user()->role->name != 'admin' && Auth::user()->role->name != 'Sub Admin')
            <p>Upgrade your membership plans provides additional benefits. <a href="{{route('upgrade-plan')}}">Click here</a> to view details.</p>
        @else
            <p>Upgrade your membership plans provides additional benefits. <a href="javascript:void(0);" style="cursor: not-allowed;">Click here</a> to view details.</p>
        @endif
    @endif
</div>