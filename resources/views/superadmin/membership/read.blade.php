@extends('voyager::master')

@section('page_title', __('voyager::generic.create').' Membership')

@section('page_header')

	<h1 class="page-title">
        <i class="voyager-person"></i> User Membership Plan &nbsp;
        <a href="{{route('superadmin.user-edit.membership', $customer_id)}}" class="btn btn-info">
            <span class="glyphicon glyphicon-pencil"></span>&nbsp;
            {{ __('voyager::generic.edit') }}
        </a>
    </h1>

@stop

@section('content')
<style>
    .errors
    {
        text-align: left;
        position: relative;
        margin-left: -30%;
    }
    .page-content {
    	margin-top: 15px;
    }
</style>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script> 
<style type="text/css">input,textarea{text-transform: uppercase};</style>
	<div class="page-content read container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered" style="padding-bottom:5px;">
                	@if($user_plan_details)
	                	@include('superadmin.membership.partials.view_readonly_data', ['title' => 'Plan', 'value' => $user_plan_details['plan']])
	                	<hr style="margin:0;">
	                	@include('superadmin.membership.partials.view_readonly_data', ['title' => 'Customers', 'value' => $user_plan_details['customers']])
	                	<hr style="margin:0;">
	                	@include('superadmin.membership.partials.view_readonly_data', ['title' => 'Additional Customer Price (₹)', 'value' => $user_plan_details['additional_customer_price']])
	                	<hr style="margin:0;">
	                	@include('superadmin.membership.partials.view_readonly_data', ['title' => 'Recordent report - Individual Price (₹)', 'value' => $user_plan_details['recordent_report_indv_price']])
	                	<hr style="margin:0;">
	                	@include('superadmin.membership.partials.view_readonly_data', ['title' => 'Recordent comprehensive report - Individual (₹)', 'value' => $user_plan_details['recordent_cmph_report_indv_price']])
	                	<hr style="margin:0;">
	                	@include('superadmin.membership.partials.view_readonly_data', ['title' => 'Recordent report - Business (₹)', 'value' => $user_plan_details['recordent_report_business_price']])
	                	<hr style="margin:0;">
	                	@include('superadmin.membership.partials.view_readonly_data', ['title' => 'Recordent comprehensive report - Business (₹)', 'value' => $user_plan_details['recordent_cmph_report_bussiness_price']])
	                	<hr style="margin:0;">
	                	@include('superadmin.membership.partials.view_readonly_data', ['title' => 'Collection Fee % (Tier 1)', 'value' => $user_plan_details['collection_fee_tier_1']])
	                	<hr style="margin:0;">
	                	@include('superadmin.membership.partials.view_readonly_data', ['title' => 'Collection Fee % (Tier 2)', 'value' => $user_plan_details['collection_fee_tier_2']])
	                	<hr style="margin:0;">
	                	@include('superadmin.membership.partials.view_readonly_data', ['title' => 'Collection Fee % (Tier 1 - Tier 2 transfer)', 'value' => $user_plan_details['collection_fee']])
	                	<hr style="margin:0;">
	                	@include('superadmin.membership.partials.view_readonly_data', ['title' => 'Membership Price (₹)', 'value' => $user_plan_details['membership_price']])
	                	<hr style="margin:0;">
	                	@include('superadmin.membership.partials.view_readonly_data', ['title' => 'Start date', 'value' => $user_plan_details['start_date']])
	                	<hr style="margin:0;">
	                	@include('superadmin.membership.partials.view_readonly_data', ['title' => 'End date', 'value' => $user_plan_details['end_date']])
	                	<hr style="margin:0;">
	                	@include('superadmin.membership.partials.view_readonly_data', ['title' => 'Transaction ID', 'value' => $user_plan_details['transaction_id']])
	                	<hr style="margin:0;">
	                	@include('superadmin.membership.partials.view_readonly_data', ['title' => 'Plan Status', 'value' => $user_plan_details['plan_status']])
	                @endif
                </div>
            </div>
         </div>
    </div>

    @php
    	$customer_id = Session::get('download_member_id') ?? '';
    @endphp
    <form action="{{ route('superadmin.user.membership.invoice', $customer_id) }}" id="download_membership_invoice" target="hidden-form">
     	<input type="hidden" name="member_id" id="member_id" value="{{$customer_id}}">
     	<input type="hidden" name="transaction_id" value="{{$user_plan_details['transaction_id']}}">
 	</form>
	<IFRAME style="display:none" name="hidden-form"></IFRAME>

    <script type="text/javascript">

    	var download_invoice = "{{Session::get('download_invoice')}}";
    	if (download_invoice) {
    		$("#download_membership_invoice").submit();
    	}
    </script>
@endsection