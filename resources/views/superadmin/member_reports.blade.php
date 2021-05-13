@extends('voyager::master')

@section('page_title', __('voyager::generic.create').' Member Reports')

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
        <i class="icon voyager-file-text"></i>Member Reports
           </h1>
                
                <div class="pull" style="margin-left: 35px;">
                     
                 </div>
                 <div class="pull-left" style="    margin-left: 520px; margin-top: -45px;">
                 
                </div>
                <div class="pull-right" style="margin-top: -44px;">
            <form action="{{route('superadmin.download-members-reports')}}">
            {{ csrf_field() }}
                <button class="btn btn-info download-mem-data btn-blue" id="downloadbtn">Download Member Reports <i class="voyager-download"></i></button>
                 <input type="hidden" id="from_date" name="from_date" value="" >
                 <input type="hidden" id="to_date" name="to_date" value="" >
                </form> 
    </div>

         </div>
    </div>

         <!-- <br><br> -->

    <div class="col-md-12" style="padding-top:50px;">
            <div class="col-md-12 ">
                <div class="panel panel-bordered">
                    <div class="panel-body">
                        <div class="table-responsive">
                        <div class="hack1">
    <div class="hack2">
                            <table id="dataTable" class="table table-hover fixed_headerss">
                            <thead>
                            <tr>
                                <th>Member Name</th>
                                <th>Type of Report</th>
                                <th>Phone</th>
                                <th>GSTIN</th>
                                <th>Person/Business Name</th>
                                <th>Consent Raised Date</th>
                                <th>Consent Status</th>
                                <th>Payment Status</th>
                                <th>Hit Status</th>
                                <th>Payment Amount</th>
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
                                        $report_type="B2C"; 
                                        $contact_phone=$data->contact_phone;
                                        $person_name=$data->person_name; 
                                    }else if($data->customer_type == "BUSINESS"){
                                        $report_type="B2B"; 
                                        $person_name= $data->business_name;
                                        $contact_phone=$data->concerned_person_phone;
                                    }else{
                                        $report_type="USB2B"; 
                                        $person_name= $data->person_name;
                                        $contact_phone=$data->contact_phone;
                                    }?>      
                                    <td><?php  echo $data->memberName ?></td>
                                    <td> <?php echo $report_type?></td>
                                    <td><?php echo $contact_phone?></td>
                                    <td>
                                    <?php 
                                            $unique_identification_number='-';
                                     if($data->unique_identification_number)
                                            {
                                            $unique_identification_number=$data->unique_identification_number;
                                            } 
                                            echo $unique_identification_number;
                                    ?>
                                    </td>
                                    <td><?php echo $person_name;?></td>
                                   
                                    <td><?php 
                                            $created_at="-";
                                            if($data->created_at !=null){
                                            $created_at= date("d/m/Y H:i:s", strtotime($data->created_at));
                                            }
                                            echo $created_at; ?>
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
                                     <td><?php 
                                     if($data->paymentStatus == 1){
                                            echo "Initiated";
                                     }else if($data->paymentStatus == 2){
                                             echo "Pending";
                                     }else if($data->paymentStatus == 3){
                                            echo "Aborted";
                                     }else if($data->paymentStatus == 4){
                                             echo "Success";
                                     }else if($data->paymentStatus == 5){
                                             echo "Failed";
                                     }else if($data->paymentStatus == 0){
                                             echo "-";
                                     }else{
                                        echo "-";
                                     }
                                     
                                     ?></td>
                                     <td><?php 
                                            $Hit_Response= "-";

                                            if (!empty($data->response))
                                            {

                                                  $response_api=json_decode(General::decrypt($data->response));

                                                if (!empty($response_api))
                                                {
                                                if (property_exists($response_api,'CCRResponse'))
                                                                            {
                                                        if (property_exists($response_api->CCRResponse,'CIRReportDataLst'))
                                                            {   
                                                                 
                                                                if (property_exists($response_api->CCRResponse->CIRReportDataLst[0],'Error'))
                                                                {   

                                                                    if( $response_api->CCRResponse->CIRReportDataLst[0]->Error->ErrorCode == '00')
                                                                    {
                                                                        $Hit_Response= "No Hit";
                                                                    }else
                                                                    {
                                                                        $Hit_Response= "Hit";
                                                                    }										
                                                                                        
                                                            }
			
                                                          }

                                                    if (property_exists($response_api->CCRResponse,'CommercialBureauResponse'))
                                                    {
                                                        if( $response_api->CCRResponse->CommercialBureauResponse->hit_as_borrower == '00')
                                                            {
                                                            $Hit_Response= "No Hit";
                                                            }
                                                            else
                                                            {
                                                            $Hit_Response= "Hit";
                                                            }																
                                                        }                                                        																	
                                                }                                                        
                                                    if (property_exists($response_api,'EfxTransmit'))
                                                    {
                                                        if( empty($response_api) || (!empty($response_api->EfxTransmit->ProductCode[0]->value) && $response_api->EfxTransmit->ProductCode[0]->value=="Commercial - NoHit"))
                                                        {
                                                               $Hit_Response= "No Hit";
                                                        }else{
                                                            $Hit_Response=  "Hit";
                                                        }
                                                    }
                                                }  
                                            }
                                            echo $Hit_Response;
                                            ?></td>
                                     <td><?php echo $data->payment_value ;?></td>
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