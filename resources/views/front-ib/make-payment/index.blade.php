@extends('layouts_front_ib.master')
@section('content') 

<!-- BEGIN CONTENT -->
<br>
<div class="container-fluid">
    <div class="side-body padding-top">
        @if(isset($payment_request_details) && !empty($payment_request_details))
            <div class="container-fluid padding-20">
                <div class="h-100 d-flex justify-content-center">
                    <div class="col-md-7 col-sm-12 col-xs-12" style="background: white;">
                        <div class="row">
                            <h3 class="text-center" style="color: black;font-weight:normal;">{{Ucfirst($payment_request_details->payment_type)}} Payment</h3>
                        </div>
                        <hr>
                        <div class="row">
                            <div class="col-md-12 d-flex justify-content-center" style="margin-bottom: 0px;">
                                <table class="col-md-10 col-sm-10 col-xs-12 checkout_table" style="margin-bottom: 0px;">
                                    <tbody>
                                        <tr style="font-size: 18px">
                                            <th style="color: black;font-weight: bold;">Details</th>
                                            <th class="amount" style="color: black;font-weight: bold;">Amount</th>
                                        </tr>
                                        <tr>
                                            <td style="color: black;font-weight:normal;">
                                                <span class="plan_name">{{Ucfirst($payment_request_details->customer_type)}}</span> Membership Plan
                                            </td>
                                            <td style="color: black;font-weight:normal;">
                                                <span class="plan_price">{{$payment_request_details->payment_value}}</span>
                                            </td>
                                        </tr>
                                        @if($user_state == 'Telangana')
                                            <tr class="state_gst">
                                                <td style="color: black;font-weight:normal;">CGST</td>
                                                <td style="color: black;font-weight:normal;">
                                                    <span class="plan_price_cgst">{{$payment_request_details->gst_value/2}}</span>
                                                </td>
                                            </tr>
                                            <tr class="state_gst">
                                                <td style="color: black;font-weight:normal;">SGST</td>

                                                <td style="color: black;font-weight:normal;"> 

                                                    <span class="plan_price_sgst">{{$payment_request_details->gst_value/2}}</span>
                                                </td>
                                            </tr>
                                        @else
                                            <tr class="central_gst">
                                                <td style="color: black;font-weight:normal;">IGST</td>
                                                <td style="color: black;font-weight:normal;">
                                                    <span class="plan_price_igst">{{$payment_request_details->gst_value}}</span>
                                                </td>
                                            </tr>
                                        @endif
                                        <tr style="font-size: 16px;">


                                            <td style="color: black;font-weight: bold;">Total</td>
                                            <td style="color: black;font-weight: bold;"><span class="total_price">{{$payment_request_details->total_collection_value}}</span></td>

                                          

                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <hr>
                        <div class="col-md-12 d-flex justify-content-center">
                            <button type="button" name="checkout" id="checkout_button" class="btn-checkout btn btn-info" style="margin-top: 12px;">Pay Now</button>
                        </div>
                    </div>
                </div>
            </div>
        @else
            @if(isset($view_type) && $view_type == 'payment')
                <h3 class="text-center">Invalid payment link</h3>
            @else
                <div class="row align-items-center">
                    @if(isset($alertType) && $alertType == "success")
                    
                        <div class="text-center">
                            <h3>
                                <span class="glyphicon glyphicon-ok"></span>&nbsp;<strong>Success!</strong>
                            </h3>
                            <h4>{{Ucfirst($message)}}</h4>
                        </div>
                    @elseif(isset($alertType) && $alertType == "error")
                        <div class="text-center">
                            <h3 style="color: red;">
                                {{Ucfirst($message)}}
                            </h3>
                            <!-- <h4>{{Ucfirst($message)}}</h4> -->
                        </div>
                    @elseif(isset($alertType) && $alertType == "info")
                        <div class="text-center">
                            <h3>{{Ucfirst($message)}}</h3>
                        </div>
                    @else
                        <div class="text-center">
                            <h3>No payment details found.</h3>
                        </div>
                    @endif
                </div>
            @endif
        @endif
    </div>
</div>
<!-- END CONTAINER --> 
<script type="text/javascript">
    $(document).ready(function(){
        @if(isset($payment_request_details) && !empty($payment_request_details))
            $('#checkout_button').on('click',function(){
                window.location.href="{{route('customer.make-payment', [$payment_request_details->unique_url_code])}}";
            });
        @endif
    });    
</script>
@endsection