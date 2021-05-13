<!-- part_1 -->
<ul class="{{ $part_1 }}">
    <li>Upto <b>{{ General::pricing_plan_data($plan_id)->free_customer_limit }} Customers</b><p>(₹{{$rupees_per_customer}}/customer)</p></li>
    <li><b>₹{{ General::pricing_plan_data($plan_id)->additional_customer_price }}</b> per additional customer</li>
    <li><b>₹{{ General::pricing_plan_data($plan_id)->consent_comprehensive_report_price }}</b> Individual Credit Report</li>
    <li><b>₹{{ General::pricing_plan_data($plan_id)->recordent_cmph_report_bussiness_price }}</b> B2B Credit Report</li>
    <li><b>₹{{ General::pricing_plan_data($plan_id)->usa_b2b_credit_report }}</b> USA B2B Credit Report</li>
</ul>
<!-- part_2 -->
<div class="{{$part_2}}">
    @if(isset($from_pricing_plan) && $from_pricing_plan == true)
        <p><b>{{ General::pricing_plan_data($plan_id)->collection_fee }}%</b> Collection Fee</p>
    @else
        <p><b>{{ General::pricing_plan_data($plan_id)->collection_fee }}%</b> Collection Fee</p>
    @endif
</div>
<!-- part_3 -->
<ul class="{{$part_3}}">
    <li class="ic-sign ic-green-sign position-relative">Installment &amp; payment options to customers</li>
    <li class="ic-sign ic-green-sign position-relative">{{$report_type}} Standard reports</li>
</ul>
