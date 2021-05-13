@extends('voyager::master')

@section('page_title', __('voyager::generic.create').' Credit Report Consent Log')

@section('page_header')

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
        <div class="" style="margin-top: -28px;">
           
              <h1 class="page-title">
        <i class="voyager-list"></i> Consent Log
           </h1>
                
                <div class="pull" style="margin-left: 35px;">
                     
                 </div>
                 <div class="pull-left" style="    margin-left: 520px; margin-top: -45px;">
                 
                </div>
                <div class="pull-right" style="margin-top: 0px;">
            <form action="{{route('superadmin.download-consentlog-records')}}">
            {{ csrf_field() }}
                <button class="btn btn-info download-mem-data btn-blue" id="downloadbtn">Download Credit Report Consent log <i class="voyager-download"></i></button>
                 <input type="hidden" id="from_date" name="from_date" value="" >
                 <input type="hidden" id="to_date" name="to_date" value="" >
                </form> 
    </div>
         </div>
    </div>

         <!-- <br><br> -->

    <div class="col-md-12" style="padding-top:45px;">
            <div class="col-md-12">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="table-responsive">
                        <div class="hack1">
    <div class="hack2">
                            <table id="dataTable" class="table table-hover fixed_headerss">
                            <thead>
                            <tr>
                                <th>Member Name</th>
                                <th>User Id</th>
                                <th>IP Address</th>
                                <th>User Created Date</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Mobile Number</th>
                                <th>Report Order Number</th>
                                <th>Report Type</th>
                                <th>Customer Reference Number</th>
                                <th>OTP code</th>
                                <th>OTP Generation date </th>
                                <th>OTP Generation Time</th>
                                <th>Consent Requested Date </th>
                                <th>Consent Requested Time</th>
                                <th>Consent Action Date</th>
                                <th>Consent Action time</th>
                                <th>API Response at</th>
                                <th>Consent status</th>
                            </tr>
                            </thead>
                            <tbody>

                            @forelse($consent_request_dtls as $data)
                                <tr>
                                <?php 
                                    $report_type="";
                                    $contact_phone="";
                                    $person_name="";
                                    if($data->customer_type == "INDIVIDUAL")
                                    {
                                        $report_type="INDIVIDUAL"; 
                                        $contact_phone=$data->contact_phone;
                                        $person_name=$data->person_name; 
                                    }else if($data->customer_type == "BUSINESS"){
                                        $report_type="BUSINESS"; 
                                        $person_name= $data->business_name;
                                        $contact_phone=$data->concerned_person_phone;
                                    }else{
                                        $report_type="US B2B"; 
                                        $person_name= $data->person_name;
                                        $contact_phone=$data->contact_phone;
                                    }?>      
                                    <td><?php  echo $data->memberName ?></td>
                                    <td><?php echo $data->added_by; ?></td>
                                    <td><?php if(isset($data->ip_address_response)){echo $data->ip_address_response;}else{echo $data->ip_address;} ?></td>
                                    <td><?php echo date("d/m/Y", strtotime($data->user_register_date)); ?></td>
                                    <td><?php echo $person_name;?></td>
                                    <td>-</td>
                                    <td><?php echo $contact_phone?></td>
                                    <td><?php 
                                            if (!empty($data->response))
                                            {
                                                $response=json_decode($data->response);
                                                if (!empty($response))
                                                {
                                                    if (property_exists($response,"InquiryResponseHeader"))
                                                    {
                                                        echo $response->InquiryResponseHeader->ReportOrderNO;
                                                    }
                                                }  
                                            }?>
                                    </td>
                                     <td> <?php echo $report_type?></td>
                                    <td><?php echo $data->id; ?></td>
                                    <td><?php echo $data->response_otp; ?></td>
                                    <td><?php
                                            $response_otp_at="-";
                                            $response_otp_time="-";
                                            if($data->response_otp_at !=null){
                                            $response_otp_at= date("d/m/Y", strtotime($data->response_otp_at));
                                            $response_otp_time= date("H:i:s", strtotime($data->response_otp_at));
                                            }
                                            echo $response_otp_at; ?>
                                    </td>
                                    <td><?php echo $response_otp_time ?></td>
                                    <td><?php 
                                            $created_at="-";
                                            $created_at_time="-";
                                            if($data->created_at !=null){
                                            $created_at= date("d/m/Y", strtotime($data->created_at));
                                            $created_at_time= date("H:i:s", strtotime($data->created_at));
                                            }
                                            echo $created_at; ?>
                                    </td>
                                    <td><?php echo $created_at_time; ?></td>
                                    <td><?php 
                                            $response_at="-";
                                            $response_at_time="-";
                                            if($data->response_at !=null){
                                            $response_at= date("d/m/Y", strtotime($data->response_at));
                                            $response_at_time= date("H:i:s", strtotime($data->response_at));
                                            }
                                            echo $response_at; ?>
                                    </td> 
                                    <td><?php echo $response_at_time; ?></td>
                                    <td><?php 
                                            $response_date_at="-";
                                            if($data->response_date_at !=null){
                                            $response_date_at= date("d/m/Y", strtotime($data->response_date_at));
                                            }
                                            echo $response_date_at;
                                            ?>
                                    </td>
                                    <td><?php 
                                            if($data->customer_type == "" || $data->customer_type == "USBUSINESS")
                                            {
                                                echo $status='-';
                                                /*if($data->status_response ==3)
                                                {
                                                    echo $status='Success';

                                                }else{
                                                    echo $status='Refund in process';
                                                }*/
                                            }else{
                                                if($data->status==4){
                                                    echo $status='Denied';
                                                }
                                    
                                            else if($data->status==3)
                                            {
                                            echo  $status='Approved';
                                            }    
                                            else
                                                {
                                                    $request_consent_block_for_hour = setting('admin.request_consent_block_for_hour') ? (int)setting('admin.request_consent_block_for_hour') : 0 ;
                                                    $dateTimeForCheckStatus = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $data->created_at);
                                                    $dateTimeForCheckStatus->addHour($request_consent_block_for_hour);
                                                    if($dateTimeForCheckStatus >= \Carbon\Carbon::now())
                                                    {
                                                    echo  $status='Pending';
                                                    }
                                                    else
                                                    {
                                                    echo  $status='Expired';
                                                    }
                                                }     
                                            }?>
                                     </td>
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
            </div>

            {{$consent_request_dtls->links()}}
        </div>
    </div>
</div>
<style>
.hack1 {
  display: table;
  table-layout: fixed;
  width: 100%;
}

.hack2 {
  display: table-cell;
  overflow-x: auto;
  width: 100%;
}
.container {
  width: 100%;
  background-color: white;
}

table {
  width: 100%;
  border-collapse: collapse;
}

td {
  border: 1px solid black;
}
</style>
@endsection