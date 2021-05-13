@extends('voyager::master')

@section('page_title', __('voyager::generic.viewing').' All Records')

@section('page_header')
    <h1 class="page-title">
        <i class="voyager-list"></i> {{-- $records->display_name_plural --}}All Reports
    </h1>
    <!-- <ul class="name_title">
        	<li>
        		
        		<a href="#" class="btn btn-sm btn-primary view"><i class="voyager-eye"></i> = View</a>
        		
        	</li> 
        </ul> -->
@stop

@section('content')
<script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    <div class="page-content container-fluid">
        @include('voyager::alerts')
        <div class="row">
            @if(!Auth::user()->hasRole('admin'))

                <div class="col-md-12">
                    <div class="panel panel-bordered">
                        <div class="panel-body">
                            <div class="table-responsive consentPaymentListing">
                                @include('admin.all-students.consent-payment-list.index')
                            </div>    
                        </div>
                    </div>
                </div>
                <h1 class="page-title">
                    <i class="voyager-list"></i>US Reports 
                </h1>

                <div class="col-md-12">
                    <div class="col-md-12 ">
                        <div class="panel panel-bordered">
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table id="dataTable" class="table table-hover fixed_headerss">
                                        <thead>
                                        <tr>
                                            <th>Business Name</th>
                                            <th>Report Type</th>
                                            <th>Payment Date</th>
                                            <th>Payment Status</th>
                                            <th>Action</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($consent_payment_dtls as  $key => $value)

                                                
                                                <?php 
                                                    if($value->payment_status == 4){
                                                        $payment_status =  "Success";
                                                    }else if(($value->payment_status == 0) || ($value->payment_status == 5)){
                                                        $payment_status =  "Failed";
                                                        if($value->consent_api_response_status == 3 &&  $value->payment_status == 5 && $value->refund_status == null ){
                                                            $payment_status = 'Not Invoiced';
                                                        }
                                                        
                                                    }else if($value->payment_status == 3){
                                                        $payment_status =  "Aborted";
                                                    } else {
                                                        $payment_status =  "Pending";
                                                    }

                                                    if($value->consent_api_response_status == 1){
                                                        $view_report_link = route('admin.us.report',['c_id'=>$value->consent_request_id]);
                                                        $reporttext="View Report";
                                                        $consent_payament_id = null;
                                                        $refund_status = null;
                                                    }else{
                                                        $view_report_link="#";
                                                        $reporttext="Refund Status";
                                                        $consent_payament_id = $value->id;
                                                        $refund_status = $value->refund_status;
                                                    }   
                                                ?>
                                                <tr>
                                                    <td><?php echo strtoupper(General::decrypt($value->person_name)) ?></td>
                                                    <td>
                                                    <?php 
                                                        if(($value->customer_type == '')|| ($value->customer_type == 'USBUSINESS')){
                                                            echo "US B2B";
                                                        }else{
                                                            echo "US BUSINESS";
                                                        }
                                                    ?>
                                                    </td>   
                                            
                                                    <td>
                                                    <?php 
                                                        if($value->created_at == null){
                                                            echo date("d/m/Y H:i:s", strtotime($value->payment_date));
                                                        }else{
                                                            echo date("d/m/Y H:i:s", strtotime($value->created_at));
                                                        }
                                                    ?>
                                                    </td>
                                                    <td> {{$payment_status}}</td>
                                                    @if($value->status != 0)
                                                        <td>
                                                            @if($value->consent_api_response_status == 1)
                                                                <a href="{{$view_report_link}}" class="btn btn-primary ">{{$reporttext}}</a>
                                                            @else
                                                                @if($value->consent_api_response_status == 3 &&  $value->payment_status == 5 && $value->refund_status == null )
                                                                    <a href="javascript:void(0); return false;" class="btn btn-primary" disabled>No Report</a>
                                                                @else
                                                                    <a href="#" onclick="check_refund_status('{{$consent_payament_id}}', '{{$refund_status}}'); return false;" class="btn btn-primary ">{{$reporttext}}</a>
                                                                @endif
                                                            @endif
                                                        </td>
                                                    @else
                                                        <td></td>
                                                    @endif
                                                </tr>
                                                
                                            @empty
                                                <tr><td colspan="10" align="center">No Record Found</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
             @else
                <script>$(".all-record-listing-section").removeClass("hide");</script>
            @endif
        </div>
    </div>
        <div class="modal commap-team-popup" id="refund_status_modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-header">
                        <!-- <h3 class="modal-title">Refund Info</h3>
                        <hr> -->
                    </div>
                    <div class="modal-body">
                        <h4>Refund Status : &emsp;<b><span id="rf_status_span"></b></h4>
                        <h4>Refund Payment Value : &emsp;<b><span id="refund_payment_value"></span></b></h4>
                    </div>
                    <div class="modal-footer">
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Ok</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<script>
    

    function check_refund_status(consent_payament_id, refund_status){
        
        if (consent_payament_id != null && refund_status != null) {

            $.ajax({
                url: "{{route('admin.get-us-b2b-report-refund-status')}}?consent_payment_id="+consent_payament_id,
                method: 'GET',
                success: function(response) {
                    console.log(response.refund_data);
                    // Display Modal
                    $('#refund_status_modal').modal('show');
                    $('#rf_status_span').html(response.refund_data.refund_status);
                    $('#refund_payment_value').html(response.refund_data.refund_payment_value);

                }
            });
        }

        return false;
    }
</script>

@endsection