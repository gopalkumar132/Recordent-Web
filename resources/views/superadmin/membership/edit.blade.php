@extends('voyager::master')

@section('page_title', __('voyager::generic.create').' Membership')

@section('page_header')
    <div class="text-center">
        <h1 class="page-title">
            Membership
        </h1>
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
@stop
@section('content')
<style>
    .errors
    {
        text-align: left;
        position: relative;
        margin-left: -30%;
    }
    label.error {
        position: relative;
    }
</style>
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script> 
<style type="text/css">input,textarea{text-transform: uppercase};</style>
    <div class="page-content container-fluid">
        @include('voyager::alerts')
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
						<form action="{{ route('superadmin.user.update_membership', $customer_id) }}" name="membership_info_form" id="membership_info_form" method="POST" enctype="multipart/form-data">
							@csrf	

                            <div class="container" id="ajax_content">
                                <div class="form-group row">
                                    <label for="pricing_plan_id" class="col-sm-4 col-form-label">Plan</label>
                                    <div class="col-sm-5">
                                        <select class="form-control" name="pricing_plan_id" id="pricing_plan_id">
                                            @foreach($pricing_plans as $key => $plan)
                                                
                                                @if($plan->id == $selected_plan_id)
                                                    <option selected="selected" value="{{$plan->id}}">{{$plan->name}}</option>
                                                @else
                                                    <option value="{{$plan->id}}">{{$plan->name}}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
    							<div class="form-group row">
                                    <label for="free_customer_limit" class="col-sm-4 col-form-label">Customers</label>
                                    <div class="col-sm-5 free_customer_limit_check_errclass">
                                        <input type="text" class="form-control" name="free_customer_limit" id="free_customer_limit" value="{{ $user_plan_info->free_customer_limit ?? $selected_plan_details->free_customer_limit }}" maxlength="{{config('membership_config.customers_input_field_max_limit')}}" required>
                                        <label for="free_customer_limit" generated="true" class="error"></label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="additional_customer_price" class="col-sm-4 col-form-label">Additional Customer Price</label>
                                    <div class="col-sm-5">
                                        <div class='input-group additional_customer_price_check_errclass'>
                                            <span class = "input-group-addon">₹</span>
                                            <input type="text" class="form-control" name="additional_customer_price" id="additional_customer_price" value="{{ $user_plan_info->additional_customer_price ?? $selected_plan_details->additional_customer_price }}" maxlength="{{config('membership_config.price_input_field_max_limit')}}" required>
                                        </div>
                                        <label for="additional_customer_price" generated="true" class="error"></label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="consent_recordent_report_price" class="col-sm-4 col-form-label">Recordent report - Individual Price</label>
                                    <div class="col-sm-5">
                                        <div class='input-group consent_recordent_report_price_check_errclass'>
                                            <span class = "input-group-addon">₹</span>
                                            <input type="text" class="form-control" id="consent_recordent_report_price" name="consent_recordent_report_price" value="{{ $user_plan_info->consent_recordent_report_price ?? $selected_plan_details->consent_recordent_report_price }}" maxlength="{{config('membership_config.price_input_field_max_limit')}}" required>
                                        </div>
                                        <label for="consent_recordent_report_price" generated="true" class="error"></label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="consent_comprehensive_report_price" class="col-sm-4 col-form-label">Recordent comprehensive report - Individual</label>
                                    <div class="col-sm-5">
                                        <div class='input-group consent_comprehensive_report_price_check_errclass'>
                                            <span class = "input-group-addon">₹</span>
                                            <input type="text" class="form-control" name="consent_comprehensive_report_price" id="consent_comprehensive_report_price" value="{{ $user_plan_info->consent_comprehensive_report_price ?? $selected_plan_details->consent_comprehensive_report_price }}" maxlength="{{config('membership_config.price_input_field_max_limit')}}" required>
                                        </div>
                                        <label for="consent_comprehensive_report_price" generated="true" class="error"></label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="recordent_report_business_price" class="col-sm-4 col-form-label">Recordent report - Business</label>
                                    <div class="col-sm-5">
                                        <div class='input-group recordent_report_business_price_check_errclass'>
                                            <span class = "input-group-addon">₹</span>
                                            <input type="text" class="form-control" name="recordent_report_business_price" id="recordent_report_business_price" value="{{ $user_plan_info->recordent_report_business_price ?? $selected_plan_details->recordent_report_business_price }}" maxlength="{{config('membership_config.price_input_field_max_limit')}}" required>
                                        </div>
                                        <label for="recordent_report_business_price" generated="true" class="error"></label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="recordent_cmph_report_bussiness_price" class="col-sm-4 col-form-label">Recordent comprehensive report - Business</label>
                                    <div class="col-sm-5">
                                        <div class='input-group recordent_cmph_report_bussiness_price_check_errclass'>
                                            <span class = "input-group-addon">₹</span>
                                            <input type="text" class="form-control" name="recordent_cmph_report_bussiness_price" id="recordent_cmph_report_bussiness_price" value="{{ $user_plan_info->recordent_cmph_report_bussiness_price ?? $selected_plan_details->recordent_cmph_report_bussiness_price }}" maxlength="{{config('membership_config.price_input_field_max_limit')}}" required>
                                        </div>
                                        <label for="recordent_cmph_report_bussiness_price" generated="true" class="error"></label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="collection_fee_tier_1" class="col-sm-4 col-form-label">Collection Fee % (Tier 1)</label>
                                    <div class="col-sm-5 collection_fee_tier_1_check_errclass">
                                        <input type="text" class="form-control" name="collection_fee_tier_1" id="collection_fee_tier_1" value="{{ $user_plan_info->collection_fee_tier_1 ?? $selected_plan_details->collection_fee_tier_1 }}" maxlength="{{config('membership_config.percentage_input_field_max_limit')}}" required>
                                        <label for="collection_fee_tier_1" generated="true" class="error"></label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="collection_fee_tier_2" class="col-sm-4 col-form-label">Collection Fee % (Tier 2)</label>
                                    <div class="col-sm-5 collection_fee_tier_2_check_errclass">
                                        <input type="text" class="form-control" name="collection_fee_tier_2" id="collection_fee_tier_2" value="{{ $user_plan_info->collection_fee_tier_2 ?? $selected_plan_details->collection_fee_tier_2 }}" maxlength="{{config('membership_config.percentage_input_field_max_limit')}}" required>
                                        <label for="collection_fee_tier_2" generated="true" class="error"></label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="collection_fee" class="col-sm-4 col-form-label">Collection Fee % (Tier 1 - Tier 2 transfer)</label>
                                    <div class="col-sm-5 collection_fee_check_errclass">
                                        <input type="text" class="form-control" name="collection_fee" id="collection_fee" value="{{ $user_plan_info->collection_fee ?? $selected_plan_details->collection_fee }}" maxlength="{{config('membership_config.percentage_input_field_max_limit')}}" required>
                                        <label for="collection_fee" generated="true" class="error"></label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="membership_plan_price" class="col-sm-4 col-form-label">Membership Price (excluding GST)</label>
                                    <div class="col-sm-5">
                                        <div class='input-group membership_plan_price_check_errclass'>
                                            <span class = "input-group-addon">₹</span>
                                            <input type="text" class="form-control" name="membership_plan_price" id="membership_plan_price" value="{{ $user_plan_info->membership_plan_price ?? $selected_plan_details->membership_plan_price }}" maxlength="{{config('membership_config.price_input_field_max_limit')}}" required>
                                        </div>
                                        <label for="membership_plan_price" generated="true" class="error"></label>
                                    </div>
                                </div>

                                @if($user_state == "Telangana")
                                    <div class="form-group row">
                                        <label for="gst_price" class="col-sm-4 col-form-label">CGST+SGST</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" name="gst_price" id="gst_price" readonly>
                                        </div>
                                    </div>
                                @else
                                    <div class="form-group row">
                                        <label for="gst_price" class="col-sm-4 col-form-label">IGST</label>
                                        <div class="col-sm-5">
                                            <input type="text" class="form-control" name="gst_price" id="gst_price" readonly>
                                        </div>
                                    </div>
                                @endif()
                                <div class="form-group row">
                                    <label for="total_price" class="col-sm-4 col-form-label">Total Price</label>
                                    <div class="col-sm-5">
                                        <!-- <div class='input-group'>
                                            <span class = "input-group-addon">₹</span> -->
                                            <input type="text" class="form-control" name="total_price" id="total_price" readonly>
                                        <!-- </div> -->
                                    </div>
                                </div>

                                @if(isset($user_plan_info) && !empty($user_plan_info))
                                    <?php $start_date = date("d/m/Y", strtotime($user_plan_info->start_date)); ?>
                                    <?php $end_date = date("d/m/Y", strtotime($user_plan_info->end_date)); ?>
                                @endif

                                <div class="form-group row">
                                    <label for="start_date" class="col-sm-4 col-form-label">Start date</label>
                                    <div class="col-sm-5 start_date_check_errclass">
                                        <div class='input-group date'>
                                            <input type='text' name="start_date" id="start_date" class="form-control datepicker startdateevent" data-date-format="DD/MM/YYYY" aria-controls="dataTable" value="{{ $start_date ?? '' }}" required/>
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                        <label for="start_date" generated="true" class="error"></label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="end_date" class="col-sm-4 col-form-label">End date</label>
                                    <div class="col-sm-5">
                                        <div class='input-group date'>
                                            <input type='text' name="end_date"id="end_date" class="form-control datepicker" data-date-format="DD/MM/YYYY" aria-controls="dataTable" value="{{$end_date ?? '' }}" readonly />
                                            <span class="input-group-addon">
                                                <span class="glyphicon glyphicon-calendar"></span>
                                            </span>
                                        </div>
                                    </div>
                                    <label for="end_date" generated="true" class="error"></label>
                                </div>
                                <div class="form-group row">
                                    <label for="transaction_id" class="col-sm-4 col-form-label">Transaction ID</label>
                                    <div class="col-sm-5">
                                        <input type="text" class="form-control" name="transaction_id" id="transaction_id" value="{{ $user_plan_info->transaction_id ?? '' }}">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="plan_status" class="col-sm-4 col-form-label">Plan Status</label>
                                    <div class="col-sm-5">
                                        <select class="form-control" name="plan_status" id="plan_status" onchange="DisableButtonsByPlanStatus();">
                                            <?php $selected = ""; ?>
                                            @if(isset($user_plan_info) && !empty($user_plan_info))
                                                @if($user_plan_info->plan_status)
                                                    <?php $selected = "selected" ?>
                                                @endif
                                            @endif
                                            <option value="0">Inactive</option>
                                            <option {{$selected}} value="1">Active</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row align-items-center">
    								<div class="col">
        								<div class="form-action text-center">
        									<button type="submit" id="save_button" class="btn btn-primary btn-blue">Save</button>
                                            @if($selected_plan_id != 1)
                                                <button type="button" id="save_send_payment_link" class="btn btn-primary btn-blue"
                                                    onclick="sendPaymentLink();">Save & Send Payment Link&nbsp;<i class="voyager-paper-plane"></i>
                                                </button>
                                            @endif
											
        								</div>
    								</div>
    							</div>
                            </div>		
						</form>
					</div>
				</div>
			</div>
		</div>
    </div>
	
<script src="{{asset('js/jquery.validate.min.js')}}"></script>
<script src="{{asset('js/number-to-word.js')}}"></script>
<script type="text/javascript">

    $(document).ready(function() {
        
    	$('body').on('focus','.datepicker',function(){
            $(this).datetimepicker();
        });

        $('#start_date').datetimepicker().on('dp.change', function (event) {
            set_plan_end_date();
        });

        $('body').on('change', '#pricing_plan_id', function(){
            update_user_plan_details_form($(this).val());
        });

        update_gst_total_price_fields();
        $('body').on('keyup change', '#membership_plan_price', function(){
            update_gst_total_price_fields();
        });

        var plan_status = $('#plan_status').val();
        DisableButtonsByPlanStatus(plan_status);

        $('body').on('keyup change', '#plan_status', function(){
            DisableButtonsByPlanStatus();
        });

        $('#start_date').on('dp.show', function (){
            var dp = $('#start_date').data('DateTimePicker');

            dp.minDate(new Date());
        });
    });

    function DisableButtonsByPlanStatus(){

        var plan_status = $('#plan_status').val();
        if (plan_status == 0) {
            $('#save_button').attr("disabled", true);
            $('#save_send_payment_link').removeAttr("disabled");
        } else {
            $('#save_send_payment_link').attr("disabled", true);
            $('#save_button').removeAttr("disabled");
        }
    }

    function set_plan_end_date(){

        var start_date=$("#start_date").val().split('/');
        var d = new Date(start_date[1]+'/'+start_date[0]+'/'+start_date[2]);
        var today= new Date("{{ date('Y-m-d 00:00:00') }}");

        if(d<today){
            // d=today;
            // d.setDate(today.getDate() + 364);
            d.setDate(d.getDate() + 364);
        } else {
            d.setDate(d.getDate() + 364);
        }
        
        var  month = '' + (d.getMonth() + 1),day = '' + d.getDate(),year = d.getFullYear();
            if (month.length < 2) 
                month = '0' + month;
            if (day.length < 2) 
                day = '0' + day;

         $('#end_date').val([day,month,year ].join('/'));
    }

    function update_user_plan_details_form(plan_id){

        $.ajax({
            url: "{{route('superadmin.user-edit.membership', $customer_id) }}?is_ajax=true&plan_id="+plan_id,
            method: 'GET',
            success: function(response) {
                var ajax_content =  $($.parseHTML(response)).find("#ajax_content");
                $('#ajax_content').empty();
                $('#ajax_content').append(ajax_content.children());
                $('#start_date').datetimepicker().on('dp.change', function (event) {
                    set_plan_end_date();
                });
                update_gst_total_price_fields();
                DisableButtonsByPlanStatus();
                $('#start_date').on('dp.show', function (){
                    var dp = $('#start_date').data('DateTimePicker');
                    dp.minDate(new Date());
                });
            }
        });
    }

    function update_gst_total_price_fields() {
        var plan_price = parseFloat($('#membership_plan_price').val());
        var gst_percentage = 18;
        console.log(plan_price);
        if (isNaN(plan_price)) {
            plan_price = 0;
        }
        var gst_price = parseFloat((plan_price * gst_percentage)/100);
        var total_price = plan_price + gst_price;

        $('#gst_price').val(gst_price.toFixed(2));
        $('#total_price').val(total_price.toFixed(2));
    }
</script>
<script type="text/javascript">

    $.validator.addMethod("check_collection_fee", function (value, element) {
        var flag = true;
        if (value == '' || value < 0) {            
            flag = false;
        }
        return flag;
    }, "Collection fee is required.");

    $.validator.addMethod("customers_count", function(value, element) {
            var returnFlag = true;
            if (value == '' || value <= 0 || value > 10000000) {
                returnFlag = false;
            }
        return returnFlag;
    }, "Customers limit should be greater than 0 and less than 10000000");

    $.validator.addMethod("check_price", function(value, element) {
            var returnFlag = true;
            if (value == '' || value < 0 || value > 10000000) {
                returnFlag = false;
            }
        return returnFlag;
    }, "Price value cannot be less than 0 & greater than 10000000.");

    $.validator.addMethod("check_start_date", function (value, element) {
        var flag = true;
        
        if (value == '') {
            flag = false;
        }
        return flag;
    }, "This field is required.");

    $.validator.addMethod("check_price_input_field", function (value, element) {
        return this.optional( element ) || /^(?:-?\d+|-?\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/.test( value );
    }, "Please enter valid Price. Only integers and decimals are allowed.");

    $.validator.addMethod("check_collection_fee_percentage_field", function (value, element) {
        return this.optional( element ) || /^(?:-?\d+|-?\d{1,3}(?:,\d{3})+)?(?:\.\d+)?$/.test( value );
    }, "Please enter valid collection fee percentage. Only integers and decimals are allowed.");


    $('#membership_info_form').validate({
        ignore: '',
        rules: {
            free_customer_limit: {
                customers_count : true,
                digits : true,
            },
            additional_customer_price : {
                check_price : true,
                check_price_input_field: true,
            },
            consent_recordent_report_price : {
                check_price : true,
                check_price_input_field: true,
            },
            consent_comprehensive_report_price : {
                check_price : true,
                check_price_input_field: true,
            },
            recordent_report_business_price:{
                check_price : true,
                check_price_input_field: true,
            },
            recordent_cmph_report_bussiness_price : {
                check_price : true,
                check_price_input_field: true,
            },
            collection_fee_tier_1 : {
                check_collection_fee : true,
                check_collection_fee_percentage_field : true,
            },
            collection_fee_tier_2 : {
                check_collection_fee : true,
                check_collection_fee_percentage_field : true,
            },
            collection_fee : {
                check_collection_fee : true,
                check_collection_fee_percentage_field : true,
            },
            membership_plan_price : {
                check_price : true,
                check_price_input_field: true,
            },
            start_date : {
                check_start_date : true,
            },
        }
    });
</script>
<script type="text/javascript">
    var membership_info_form = $( "#membership_info_form" );
    membership_info_form.validate();
    
    function sendPaymentLink(){

        if(window.membership_info_form.valid()) {
            $('#membership_info_form').attr('action', "{{route('superadmin.user.save.send-payment-link', $customer_id)}}");
            $("#membership_info_form").submit();
        }
    }
</script>
@endsection