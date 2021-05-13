<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\WithHeadings;
use General;
use DB;
use Auth;
use Carbon\carbon;
use Log;
use App\ConsentRequest;
use Maatwebsite\Excel\Concerns\FromArray;
class ConsentLogExport implements FromArray, WithHeadings
{  
    protected $downloadType;
      public function __construct($downloadType="") {
        $this->downloadType=$downloadType;
 }

 
 public function headings():array
 {

    if($this->downloadType == "ConsentLogReports"){
        return [
            'Member Name',
            'User id',
            'IP Address',
            'User Created Date',
            'First name',
            'Last name',
            'Mobile number',
            'Report order number',
            'Report Type',
            'Customer Reference Number',
            'OTP code',
            'OTP generation date',
            'OTP generation time',
            'Consent Requested Date',
            'Consent Requested Time',
            'Consent Action date',
            'Consent Action time',
            'API Response at',
            'Consent status'
            ];
    }else{
        return [
            'Member Name',
            'Type of Report',
            'Phone',
            'GSTIN',
            'Person/Business Name',
            'Consent Raised Date',
            'Consent Status',
            'Payment Status',
            'Hit Status',
            'Payment Amount'
            ];

    }
     
 }
 public function array(): array
 {
    $final_array=array();
    if($this->downloadType == "ConsentLogReports"){
  $authId = Auth::id();
  $final_array=array();
//   $consent_payment_dtls = DB::table('consent_request')->get();

$consent_request_dtls = consentRequest::select('consent_request.*','consent_api_response.response','consent_api_response.ip_address as ip_address_response','consent_api_response.created_at as response_date_at','consent_api_response.status as status_response')
                                        ->leftJoin('consent_payment', 'consent_request.id', '=', 'consent_payment.consent_id')
                                        ->leftJoin('consent_api_response', 'consent_request.id', '=', 'consent_api_response.consent_request_id')
                                        ->orderBy('consent_request.created_at','DESC')
                                        ->get();

foreach($consent_request_dtls as $rec)
{
$auth = User::where('id',$rec['added_by'])->first();
$rec->memberName=$auth['name'];
$rec->user_register_date=$auth['created_at'];

}

// foreach($consent_request_dtls as $rec)
// {
//     $final_array[]=(array)$rec;

// }

  $final_prepare_array=array();
  foreach($consent_request_dtls as $rec)
  {
    $main_array=array();
    if($rec['customer_type'] == "INDIVIDUAL")
    {
        $contact_phone=$rec['contact_phone'];
        $report_type="INDIVIDUAL";
    }else if($rec['customer_type'] == "BUSINESS")
    {
        $contact_phone=$rec['concerned_person_phone'];
        $report_type="BUSINESS";
    }else{
        $contact_phone=$rec['contact_phone'];
        $report_type="USB2B";
    }
    if($rec['response_otp_at'] !=null)
        {
            $date_response_otp_at=date("d/m/Y", strtotime($rec['response_otp_at']));
            $time_response_otp_at=date("H:i:s", strtotime($rec['response_otp_at']));
        }else{
            $date_response_otp_at="-";
            $time_response_otp_at="-";
        }

        if($rec['created_at'] !=null)
        {
            $created_at=date("d/m/Y", strtotime($rec['created_at']));
            $created_at_time=date("H:i:s", strtotime($rec['created_at']));
        }else{
            $created_at="-";
            $created_at_time="-";
        }
        if($rec['response_at'] !=null)
        {
            $response_at_date=date("d/m/Y", strtotime($rec['response_at']));
            $response_at_time=date("H:i:s", strtotime($rec['response_at']));
        }else{
            $response_at_date="-";
            $response_at_time="-";
        }

        if($rec['response_date_at'] !=null)
        {
            $response_date_at=date("d/m/Y", strtotime($rec['response_date_at']));
        }else{
            $response_date_at="-";
        }
      $otp="-";
      if($rec['response_otp'] != null)
      {
        $otp=$rec['response_otp'];
      }
      $order_id="-";
      if (!empty($rec->response))
        {
            $response=json_decode($rec->response);
            if (!empty($response))
            {
                if (property_exists($response,"InquiryResponseHeader"))
                {
                    $order_id= $response->InquiryResponseHeader->ReportOrderNO;
                }

            } 
            
        }
        $ip_address=$rec['ip_address'];
        if(isset($rec['ip_address_response']))
        {
            $ip_address=$rec['ip_address_response'];
        }
        
      $main_array['member_name']=$rec['memberName'];
      $main_array['user_id']=$rec['added_by'];
      $main_array['ip_register']=$ip_address;
      $main_array['user_id_ct_date']=date("d/m/Y", strtotime($rec['user_register_date']));
      $main_array['first_name']=$rec['person_name'];
      $main_array['last_name']="-";
      $main_array['mobile_nmeber']=$contact_phone;
      $main_array['report_order_num']=$order_id;
      $main_array['report_type']=$report_type;
      $main_array['cust_ref_number']=$rec['id'];
      $main_array['otp_code']=$otp;
      $main_array['otp_gen_date']=$date_response_otp_at;
      $main_array['otp_gen_time']=$time_response_otp_at;
      $main_array['consent_request_date']=$created_at;
      $main_array['consent_request_time']=$created_at_time;
      $main_array['consent_action_date']=$response_at_date;
      $main_array['consent_action_time']=$response_at_time;
      $main_array['api_response_at']=$response_date_at;
      
      if( $rec['customer_type'] == "" || $rec['customer_type'] == "USBUSINESS")
      {
           $status='-';
          /*if($data->status_response ==3)
          {
              echo $status='Success';

          }else{
              echo $status='Refund in process';
          }*/
      }else{
                if($rec['status']==4){
                    $status='Denied';
                }

                else if($rec['status']==3)
                {
                    $status='Approved';
                }    
                else
                    {
                        $request_consent_block_for_hour = setting('admin.request_consent_block_for_hour') ? (int)setting('admin.request_consent_block_for_hour') : 0 ;
                        $dateTimeForCheckStatus = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $rec['created_at']);
                        $dateTimeForCheckStatus->addHour($request_consent_block_for_hour);
                        if($dateTimeForCheckStatus >= \Carbon\Carbon::now())
                        {
                            $status='Pending';
                            $removeAllRecordListSection = false;  
                        }
                        else
                        {
                            $status='Expired';
                        }
                   } 
      }

       $main_array['consent_status']=$status;
      $final_prepare_array[]=$main_array;

  }

        return $final_prepare_array;
    }
    
    else
    {

        $consent_request_dtls = consentRequest::select('consent_request.*','consent_api_response.response' ,'consent_api_response.status as status_response'
                                                        ,'consent_payment.status as paymentStatus','consent_payment.payment_value')
                                                ->leftJoin('consent_payment', 'consent_request.id', '=', 'consent_payment.consent_id')
                                                ->leftJoin('consent_api_response', 'consent_payment.consent_id', '=', 'consent_api_response.consent_request_id')
                                                ->orderBy('consent_request.created_at','DESC')
                                                ->get();
        foreach($consent_request_dtls as $rec)
        {
            $auth = User::where('id',$rec['added_by'])->first();
            $rec->memberName=$auth['name'];

            $Hit_Response="-"; 
            if (!empty($rec->response) && $rec->response != null){    
                    $response_api=json_decode(General::decrypt($rec->response));
                    try{    
                        if (property_exists($response_api,'CCRResponse')){

                            if (property_exists($response_api->CCRResponse,'CIRReportDataLst')){   
                                                             
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
                             if (property_exists($response_api->CCRResponse,'CommercialBureauResponse')){
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
                    if(property_exists($response_api,'EfxTransmit')){
                         if( empty($response_api) || (!empty($response_api->EfxTransmit->ProductCode[0]->value) && $response_api->EfxTransmit->ProductCode[0]->value=="Commercial - NoHit"))
                            {
                                    $Hit_Response= "No Hit";
                            }else{
                                $Hit_Response=  "Hit";
                            }
                                  
                    }
                    }catch(\Exception $e)
                        {
                            $Hit_Response="No Hit";
                        }
                   }
        $main_array=array();
        if($rec['customer_type'] == "INDIVIDUAL")
        {
            $contact_phone=$rec['contact_phone'];
            $report_type="B2C";
            $person_name=$rec['person_name'];
        }else if($rec['customer_type'] == "BUSINESS")
        {
            $contact_phone=$rec['concerned_person_phone'];
            $report_type="B2B";
            $person_name=$rec['business_name'];
        }else{
            $contact_phone=$rec['contact_phone'];
            $report_type="USB2B";
            $person_name=$rec['person_name'];
        }
        $payment_value="-";
        if($rec['payment_value'] != null)
        {
            $payment_value=$rec['payment_value'];
        }
        if($rec['created_at'] !=null)
        {
            $created_at=date("d/m/Y", strtotime($rec['created_at']));
            $created_at_time=date("H:i:s", strtotime($rec['created_at']));
        }else{
            $created_at="-";
            $created_at_time="-";
        }
        $unique_identification_number='-';
        if($rec['unique_identification_number'] !=null)
        {
            $unique_identification_number=$rec['unique_identification_number'];
        }
        if($rec['paymentStatus'] == 1){
            $paymentStatus= "Initiated";
        }else if($rec['paymentStatus'] == 2){
            $paymentStatus= "Pending";
        }else if($rec['paymentStatus'] == 3){
            $paymentStatus= "Aborted";
        }else if($rec['paymentStatus'] == 4){
            $paymentStatus= "Success";
        }else if($rec['paymentStatus'] == 5){
            $paymentStatus= "Failed";
        }else if($rec['paymentStatus'] == 0){
            $paymentStatus= "-";
        }else{
            $paymentStatus= "-";
        }
        $main_array['member_name']=$rec['memberName'];
        $main_array['report_type']=$report_type;
        $main_array['mobile_nmeber']=$contact_phone;
        $main_array['unique_identification_number']=$unique_identification_number;
        $main_array['person_name']=$person_name;
        
        $main_array['consent_request_date']=$created_at;
            if( $rec['customer_type'] == "" || $rec['customer_type'] == "USBUSINESS")
            {
                 $status='-';
            }else{
                      if($rec['status']==4){
                          $status='Denied';
                      }
      
                      else if($rec['status']==3)
                      {
                          $status='Approved';
                      }    
                      else
                          {
                              $request_consent_block_for_hour = setting('admin.request_consent_block_for_hour') ? (int)setting('admin.request_consent_block_for_hour') : 0 ;
                              $dateTimeForCheckStatus = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $rec['created_at']);
                              $dateTimeForCheckStatus->addHour($request_consent_block_for_hour);
                              if($dateTimeForCheckStatus >= \Carbon\Carbon::now())
                              {
                                  $status='Pending';
                                  $removeAllRecordListSection = false;  
                              }
                              else
                              {
                                  $status='Expired';
                              }
                         } 
            }

            $main_array['consent_status']=$status;
            $main_array['payment_response']=$paymentStatus;
            $main_array['hit_response']="-";
            $main_array['payment_value']=$payment_value;
            $final_array[]=$main_array;
        }
        return $final_array;
    }



 }
   

 
}