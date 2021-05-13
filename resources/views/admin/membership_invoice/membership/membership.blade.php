@extends('voyager::master')

@section('page_header')
@stop
@section('content')
        <section class="membership-plans plans-price bg-white">
            <div class="container">
                <div class="the-title text-center">
                    <h2 class="text-uppercase">Membership</h2>
                </div>
                <div class="row">
                    <table class="table table-cell membership-table">
                        <tr>
                            <th>Membership Plan</th>
                            <th>Start Date</th>
                            <th>End Date</th>
                            <th>Total No of Customer</th>
                            <th>Total No of Customers Left</th>
                            <th>Payment Amount</th>
                            <th>GST</th>
                            <th>Invoice</th>
                        </tr>
                        @if(Auth::user()->role->name!='admin' && Auth::user()->role->name!='Sub Admin')
                            <tr>
                                <td>{{$user_plan_details['plan_name']}}</td>
                                <td>{{$user_plan_details['start_date']}}</td>
                                <td>{{$user_plan_details['end_date']}}</td>
                                <td>{{$user_plan_details['free_customer_limit']}}</td>
                                <td>{{$user_plan_details['remaining_free_customer_limit']}}</td>
                                <td>{{$user_plan_details['membership_plan_price']}}</td>
                                <td>{{$user_plan_details['gst_price']}}</td>
                                <td>{!! $user_plan_details['invoice']  ? '<a href="'. route('invoice') .'" target="_blank" style="color: #48a3f5;text-decoration: none;"><span class="font-weight-bold">Click here to download Invoice</span></a>' : 'Invoice Not found' !!}
                                </td>
                            </tr>
                        @endif
                    </table>
                </div>
                

                @if( !empty($user_plan_details['membership_history']) && count($user_plan_details['membership_history']) > 1)
                    <div class="row">
                        <div class="col-md-12">
                            <a href="javascript:void(0)" class="float-right font-weight-bold" id="view_history" style="color: #48a3f5;text-decoration: none;"><span>Click here to view/hide membership history</span></a>
                        </div>
                        <div class="col-md-12" id="membership_history_table" style="display: none;">
                            <table class="table table-cell membership-table">
                                <caption class="font-weight-bold h4 text-center">Membership History</caption>
                                <tr>
                                    <th>Membership Plan</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Total No of Customer</th>
                                    <th>Payment Amount</th>
                                    <th>Invoice</th>
                                </tr>

                                @foreach($user_plan_details['membership_history'] as $key => $history)
                                    @if($key == 0)
                                        @continue
                                    @endif
                                    <tr>
                                        <td>{{$history['plan_name']}}</td>
                                        <td>{{$history['start_date']}}</td>
                                        <td>{{$history['end_date']}}</td>
                                        <td>{{$history['free_customer_limit']}}</td>
                                        <td>{{$history['plan_price']}}</td>
                                        <td>{!! $history['invoice']  ? '<a href="'. $history['invoice'] .'" target="_blank" style="color: #48a3f5;text-decoration: none;"><span class="font-weight-bold">Click here to download Invoice</span></a>' : 'Invoice Not found' !!}
                                        </td>
                                    </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                @endif
                
                @if(Auth::user()->role->name!='admin' && Auth::user()->role->name!='Sub Admin')
                    @if(Auth::user()->user_pricing_plan != NULL)
                        <div class="row" style="margin-top: 40px;">
                            <div class="col-md-3">
                                <span class="font-weight-bold h5">Your Current Pricing plan details:</span>
                            </div>
                            <div class="col-md-9 plan-details">
                                <p>RS. {{$user_plan_details['additional_customer_price']}} per additional customer</p>
                                <p style="margin-top: 15px;">RS. {{$user_plan_details['consent_comprehensive_report_price']}} Individual Credit Report</p>
                                <p style="margin-top: 15px;">RS. {{$user_plan_details['recordent_cmph_report_bussiness_price']}} B2B Credit Report</p>
                            </div>
                        </div>
                        @if(Auth::user()->user_pricing_plan->pricing_plan_id != 0 && Auth::user()->user_pricing_plan->pricing_plan_id != 4 && Auth::user()->user_pricing_plan->plan_status || HomeHelper::isPlanRenewable())
                            <div class="row">
                                <div class="col-md-12">
                                    <?php 
                                        if(strtotime(Auth::user()->user_pricing_plan->end_date) < strtotime(date('Y-m-d H:i:s',strtotime('+10 days')))){
                                            $button_name = "Renew";
                                        } else {
                                            $button_name = "Upgrade";
                                        }
                                     ?>
                                    <span class="text-danger font-weight-bold h4">Now you can upgrade any time you want. &nbsp;<a href="{{route('upgrade-plan')}}" class="btn btn-danger" id="upgrade_button">{{$button_name}}</a>
                                    </span>
                                </div>
                            </div>
                        @endif
                    @endif
                @endif
            </div>
        </section> 
@endsection
@section('css')
<link rel="stylesheet" href="{{asset('front_new/css/style.css')}}">  
<style type="text/css">
    .app-footer{
        display: none;
    }

    a[disabled="disabled"] {
        pointer-events: none;
    }

    .plan-details {
        margin-left: -50px;
    }

    @media (max-width: 768px) {
        .plan-details {
            margin-left: 0px;
        }
    }
</style>

@endsection
@section('javascript')
<script type="text/javascript">

    $('#view_history').click(function(){
        $("#membership_history_table").toggle();
    });

</script>
@endsection