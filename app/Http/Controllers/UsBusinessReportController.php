<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;
use App\Imports\StudentsImport;
use App\Exports\StudentsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Students;
use App\User;
use App\StudentDueFees;
use App\StudentPaidFees;
use App\MembershipPayment;
use App\DuesSmsLog;
use App\Sector;
use App\Country;
use App\State;
use App\City;
use App\ConsentRequest;
use Validator;
use Response;
use Carbon\Carbon;
use DB;
use Auth;
use General;
use Illuminate\Support\Collection;
use App\Services\SmsService;
use PaytmWallet;
use App\ConsentPayment;
use Str;

class UsBusinessReportController extends Controller
{    
    public function usCreditReportNoHitResponse($business_name, $consent_payment_value){

    	$business_name = isset($business_name)? base64_decode($business_name): '';
    	$consent_payment_value = isset($consent_payment_value)? $consent_payment_value: '';

    	return view('admin.us-creditreportnohitresponse.index', [
					'business_name' => $business_name,
					'consent_payment_value' => $consent_payment_value
				]);
    }


    public function usCreditReportSucessResponse($business_name, $consent_payment_value){

    	$business_name = isset($business_name)? base64_decode($business_name): '';
    	$consent_payment_value = isset($consent_payment_value)? $consent_payment_value: '';

    	return view('admin.us-creditreportresponse.index', [
					'business_name' => $business_name,
					'consent_payment_value' => $consent_payment_value
				]);
    }


    public function getUsB2BReportRefundStatus(Request $request){

    	Log::debug('consent_payment_id = '.$request->consent_payment_id);
    	$get_consent_payment_data = ConsentPayment::where('id', trim($request->consent_payment_id))->first();

    	$output['refund_data']['error'] = false;
    	if (!empty($get_consent_payment_data)) {
    		
    		if ($get_consent_payment_data->refund_status == 4) {
    			$output['refund_data']['refund_status'] = "success";
    		} else {
    			$raw_response = json_decode($get_consent_payment_data->raw_refund_response);

    			if (!empty($raw_response)) {

                    if ($get_consent_payment_data->refund_status == 2) {
                        $refund_request_params = json_decode($get_consent_payment_data->raw_refund_request);
                        $request_id = $refund_request_params->var1;
                    } else {
                        $request_id = $raw_response->request_id;
                    }
	                
	                $raw_output = General::check_payu_refund_status($request_id);

	                $response = json_decode($raw_output);

	                if (!empty($response) && $response->status == 1 && isset($response->transaction_details->$request_id->$request_id->status)) {

                        $status = $response->transaction_details->$request_id->$request_id->status;

                        if ($status == "success" || $status == "failure" || $status == "queued") {

                            if ($status == "success") {
                                $refund_status = 4;
                                $output['refund_data']['refund_status'] = "success";
                            } else if ($status == "failure") {
                                $refund_status = 5;
                                $output['refund_data']['refund_status'] = "failure";
                            } else {
                                $refund_status = 2;
                                $output['refund_data']['refund_status'] = "Pending";
                            }

                            $refund_status_api_request_params = General::getUsB2bReportRefundApiRequestParams($request_id);

                            $get_consent_payment_data->raw_refund_response = $raw_output;
                            $get_consent_payment_data->refund_status = $refund_status;
                            $get_consent_payment_data->raw_refund_request = json_encode($refund_status_api_request_params);

                            $get_consent_payment_data->save();
                        } else {
                        	$output['refund_data']['refund_status'] = "Pending";
                        } 
	                }
	            } else {
	            	$output['refund_data']['error'] = true;
	            }
    		}

    		$output['refund_data']['refund_payment_value'] = $get_consent_payment_data->payment_value;
    	}

    	return $output;
    }
}
