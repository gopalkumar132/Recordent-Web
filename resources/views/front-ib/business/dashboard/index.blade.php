@extends('layouts_front_ib.master')
@section('content')
@php
if(session()->get('individual_client_udise_gstn')){
$url = config('app.url').'business/';
}else{
$url = config('app.url').'individual/';
}
@endphp
  <!-- BEGIN CONTENT -->
<div class="container-fluid" data-select2-id="13">
    <div class="side-body padding-top" data-select2-id="12">

        <div id="voyager-notifications"></div>
        <div class="page-content browse container-fluid" data-select2-id="11">
            <!--<div class="alerts"> </div>-->
            <div class="col-xs-12 col-sm-12 col-md-12">
                <div class="" style="text-align:center">
                    <h2><span style=" style="font-weight:bold;padding:6px;padding-left:10px;padding-right:10px;color:#5f94c4; font-family:var(--font-rubik);"><strong>Welcome </strong>
                         @if($individual[0]['company_name']!='')
                        {{$individual[0]['company_name']}}
                        @else
                        {{Session::get('individual_client_email')}}
                        @endif
                        @if(!empty(Session::get('individual_client_udise_gstn_sector_type_text')))
                        ({{Session::get('individual_client_udise_gstn_sector_type_text')}})
                        @endif
                    </span></h2>
                </div>
            </div>
        </div>
        <div class="container-fluid custom-dimmers d-flex flex-wrap">
            <div class="dimmers-boxes ">
                <div class="panel widget center bgimage" style="margin-bottom:0;overflow:hidden;background-image:url('{{config('app.url')}}payment_business.jpg');">
                    <div class="dimmer"></div>
                    <div class="panel-content">
                        <i class="voyager-dollar"></i>
                        <h4 style="font-size:17px;"> 
                            <div class="total-amount-due">
                                <span class="someeeee">Total Dues Submitted</span>
                            </div>  
                            <div class="total-amount-due">
                                <span class="someeeee">INR.{{$TotalDue}} </span>
                            </div>
                        </h4>
                        <p></p>
                    </div>
                </div>
                <div>
                    <p> &nbsp;</p>
                </div>
            </div>

            <div class="dimmers-boxes ">
                <div class="panel widget center bgimage" style="margin-bottom:0;overflow:hidden;background-image:url('{{config('app.url')}}ladpers_business.jpg');">
                    <div class="dimmer"></div>
                    <div class="panel-content">
                        <i class="voyager-file-text"></i>
                        <h4 style="font-size:17px;">
                            <div class="total-amount-due">
                                <span class="someeeee">Total Dues Submitted</span>
                            </div>
                            <div class="total-amount-due">
                                <span class="someeeee">{{$numberOfBusinessReported}}</span>
                            </div>
                        </h4>
                        <p></p>
                    </div>
                </div>

                <div>
                    <p> &nbsp;</p>
                </div>
            </div>
        </div>

        @if($message != "No Records")
          {!! $htmlReport !!}
        @endif 
    </div>
</div>
<!-- END CONTAINER --> 
@endsection

