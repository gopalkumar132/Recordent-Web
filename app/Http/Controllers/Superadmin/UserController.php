<?php

namespace App\Http\Controllers\Superadmin;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Exception;
use Response;
use Carbon\Carbon;
use DB;
use Auth;
use Storage;
use Session;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UsersExport;
use Log;
use App\PricingPlan;
use App\UserPricingPlan;
use paytm\paytmchecksum\PaytmChecksum;
use Mail;
use App\Services\SmsService;
use General;
use App\DuesSmsLog;
use App\User;
use App\ConsentRequest;
use App\MakePaymentRequest;
use Illuminate\Support\Str;
use Validator;
use HomeHelper;
use PDF;
use App\InvoiceType;
use App\MembershipPayment;
use App\TempMembershipPayment;
use App\Students;
use App\Businesses;
use App\Exports\CreditreportExport;
use App\Exports\ConsentLogExport;
class UserController extends Controller
{
	public function export(Request $request) 
    {
        $date = $request->input('date')??'0';
        
       return Excel::download(new UsersExport($date), 'Members.xlsx');
    }

    /**
    * membershipIndex method consist of the 
    * @param $customer_id - Integer
    * is used to get customer existing plan details from UserPricingPlan table
    * @param return View - read 
    * returns requested user membership plan details if found else get it from default pricing plan table and parse
    */
    public function membershipIndex(Request $request, $customer_id)
    {
    	$selected_plan_id = 1;
    	$user_plan_details = array();
    	$default_pricing_plan = PricingPlan::find(1);
    	$user_pricing_plan = UserPricingPlan::where('user_id', $customer_id)->first();

    	if (isset($user_pricing_plan) && !empty($user_pricing_plan)) {
    		$selected_plan_id = $user_pricing_plan->pricing_plan_id;
    		$user_plan_details = $this->formatUserPricingPlanDetails($user_pricing_plan);
    	} else {
    		$user_plan_details = $this->formatUserPricingPlanDetails($default_pricing_plan, true);
    	}

    	return view('superadmin.membership.read', [
    		'user_plan_details' => $user_plan_details,
    		'customer_id' => $customer_id,
    		'selected_plan_id' => $selected_plan_id
    	]);
    }

    /**
    * getUserMembershipDetails method consist of the 
    * @param $request - Request Object
    * To differentiate ajax request (change in plan) and page refresh to fetch Membership plan details
    * @param $customer_id - Integer
    * is used to get customer existing plan details from UserPricingPlan table
    * @param return View - edit
    * returns requested user membership plan details if found else get it from default pricing plan table and parse
    */
    public function getUserMembershipDetails(Request $request, $customer_id)
    {
    	$user_pricing_plan = array();
    	$is_ajax = false;
    	$selected_plan_id = 1;

    	$pricing_plans = PricingPlan::all();
        $user_details = User::find($customer_id);

        $prev_pricing_plan_id = $user_details->get_member_previous_plans[0]->pricing_plan_id ?? null;

    	if ($request->has(['is_ajax', 'plan_id'])) {
    		$is_ajax = true;
    		$selected_plan_id = $request->input('plan_id');

            if (isset($user_details->user_pricing_plan) && $user_details->user_pricing_plan->pricing_plan_id == 0 && $selected_plan_id == $prev_pricing_plan_id) {
                $user_pricing_plan = $user_details->user_pricing_plan;
            } else {
                $user_pricing_plan = UserPricingPlan::where('user_id', $customer_id)
		    					->where('pricing_plan_id', $selected_plan_id)->first();
            }

		} else {
			$user_pricing_plan = $user_details->user_pricing_plan;

			if (isset($user_pricing_plan) && !empty($user_pricing_plan)) {

                $selected_plan_id = $user_pricing_plan->pricing_plan_id;
                if ($user_pricing_plan->pricing_plan_id == 0) {
                    $selected_plan_id = $prev_pricing_plan_id;
                }
				
			}
		}

		$selected_plan_details = PricingPlan::find($selected_plan_id);
    	
    	return view('superadmin.membership.edit', [
    		"customer_id" => $customer_id, 
    		'pricing_plans' => $pricing_plans,
    		"user_plan_info" => $user_pricing_plan,
    		"is_ajax" => $is_ajax,
    		"selected_plan_id" => $selected_plan_id,
    		"selected_plan_details" => $selected_plan_details,
            "user_state" => $user_details->state->name?? ''
    	]);
    }

    /**
    * updateMembershipDetails method consist of the 
    * @param $request - Request Object
    * validate requested plan details on submit & update UserPricingPlan table by customer_id
    * @param $customer_id - Integer
    * is used to find customer existing Membership plan details and create or Update Plan info.
    * @param return View - read 
    * returns requested user membership plan details if found else get it from default pricing plan table and parse
    */
    public function updateMembershipDetails(Request $request, $customer_id, $from="save_button")
    {
    	$logged_in_user = Auth::user();

        if ($from == "save_button") {
            
            $rule = $this->getMembershipFormRules();
            $ruleMessage = $this->getMembershipFormValidationMessages();
            $validator = Validator::make($request->all(), $rule, $ruleMessage);
            
            if($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }
        }

        $member = User::find($customer_id);

        $current_start_date = null;
        $current_end_date = null;
        $member_plan_id = null;
        if (!empty($member) && isset($member->user_pricing_plan)) {
            $current_start_date = $member->user_pricing_plan->start_date;
            $current_end_date = $member->user_pricing_plan->end_date;
            $member_plan_id = $member->user_pricing_plan->pricing_plan_id;
            $member_plan_status = $member->user_pricing_plan->plan_status;
        }

        $plan_data = $request->except(['gst_price','total_price']);
    	$plan_data['start_date'] = Carbon::createFromFormat('d/m/Y', $request->start_date)->format('Y-m-d');
    	$plan_data['end_date'] = Carbon::createFromFormat('d/m/Y', $request->end_date)->format('Y-m-d');

        General::add_to_debug_log($customer_id, "Superadmin - Updating member plan details.");
    	$updated_user_plan = UserPricingPlan::updateOrCreate(['user_id' => $customer_id], $plan_data);
    	$user_pricing_plan = UserPricingPlan::where('user_id', $customer_id)->first();

        General::add_to_debug_log($customer_id, "Superadmin - Updating member plan details success. plan_id=".$user_pricing_plan->pricing_plan_id);

        $user_pricing_plan->paid_status = 1;
    	$user_pricing_plan->updated_by = $logged_in_user->id;
    	$user_pricing_plan->save();

        if ($from != 'save_button') {
            return true;
        }

        $is_plan_changed = false;
        if ($updated_user_plan->wasChanged() && $user_pricing_plan->plan_status) {
            if ($user_pricing_plan->start_date != $current_start_date && $user_pricing_plan->end_date != $current_end_date || $member_plan_id != $user_pricing_plan->pricing_plan_id || $user_pricing_plan->plan_status != $member_plan_status) {
                $is_plan_changed = true;
            }
        }

        if ($user_pricing_plan->plan_status && $updated_user_plan->wasRecentlyCreated || $is_plan_changed) {

            HomeHelper::InsertIntoUserMembershipHistory(PricingPlan::find($user_pricing_plan->pricing_plan_id), $customer_id);
        }
        
        $download_invoice = true;
        if ($user_pricing_plan->pricing_plan_id == 1) {
            $download_invoice = false;
        }

  		return redirect()->route('superadmin.user.membership', ["customer_id" => $customer_id])->with(['message' => "Membership plan details updated successfully.", 'alert-type' => "success", 'download_invoice' => $download_invoice, 'download_member_id' => $customer_id]);
    }

    /**
    * sendMakePaymentLink method is to generate payment link for Membership plan upgrade or renew 
    * & sending generated link to customer mobile number through sms.
    * @param $request - Request Object
    * @param $customer_id - Integer
    * is used to retrieve customer existing Membership plan details.
    * @param return $response - Json Response 
    * returns status is success or error with message.
    */
    public function sendMakePaymentLink(Request $request, $customer_id, $is_membership_upgrade=false)
    {

		$user_pricing_plan = UserPricingPlan::where("user_id", $customer_id)->first();

		$subscription_plan_name = $user_pricing_plan->plan->name;
		$user_details = User::find($customer_id);

		if (isset($user_details->mobile_number) && !empty($user_details->mobile_number)) {

			$membership_price = 0;
	    	$totalGSTValue = 0;
	    	$totalCollectionValue = 0;

	        $membership_price = $user_pricing_plan->membership_plan_price;
	        $pricing_plan=PricingPlan::findOrFail($user_pricing_plan->pricing_plan_id);

	        $totalGSTValue = $membership_price * $pricing_plan->consent_recordent_report_gst/100;
	        $totalCollectionValue = $membership_price + $totalGSTValue;

			$uniqueUrlCode = Str::random(10);
			$make_payment_link_url = route('customer.payment-page',[$uniqueUrlCode]);
			$user_mobile_number = $user_details->mobile_number;

            $payment_type = "membership";
            if ($is_membership_upgrade) {
                $payment_type = "membership_upgrade";
            }

            General::add_to_debug_log($customer_id, "Superadmin - Updating membership payment request details. plan_id=".$pricing_plan->id);
			$insert = [
				'added_by'=>Auth::id(),
				'customer_id'=>$customer_id,
				'order_id' => Str::random(40),
				'customer_type'=> $subscription_plan_name,
				'payment_type' => $payment_type,
				'unique_url_code'=>$uniqueUrlCode,
				'status'=>0,
	            'customer_mobile_no'=>$user_mobile_number,
	            'payment_value'=> $membership_price,
	            'gst_value' => $totalGSTValue,
	            'total_collection_value' => $totalCollectionValue
			];

			$insert = MakePaymentRequest::updateOrCreate([
					'customer_id' => $customer_id,
					'payment_type' => $payment_type
				], $insert);

            General::add_to_debug_log($customer_id, "Superadmin - Updating membership payment request details success. plan_id=".$pricing_plan->id);

			$payment_request_details = MakePaymentRequest::where('customer_id', $customer_id)->where('payment_type', $payment_type)->first();

			if ($payment_request_details) {
				
				$message= "Recordent has requested payment of ".$totalCollectionValue." for ".$subscription_plan_name." plan subscription. Click ".$make_payment_link_url;

				$smsService = new SmsService();
				$smsResponse = $smsService->sendSms($user_mobile_number, $message);

				$alert_message = "Membership plan details saved and payment link sent successfully.";
				$alert_type = "success";

				if($smsResponse['fail_to_send']){
					$payment_request_details->status = 2;
	        		$payment_request_details->update();

		 			$alert_message = "Error sending Payment link to customer!";
		 			$alert_type = "error";
		 		}

		 		if($smsResponse['sent']==1){
	             
		            $payment_request_details->status = 1;
		        	$payment_request_details->update();
	        	}
			} else {
				$alert_message = "Unable to send Payment Link to customer, Error in saving Payment Request details.";
		 		$alert_type = "error";
			}

		} else {
			$alert_message = "Unable to send Payment Link to customer, Customer Mobile Number is Missing. ";
		 	$alert_type = "error";
		}

		$response['alert_type'] = $alert_type;
		$response['alert_message'] = $alert_message;

        General::add_to_debug_log($customer_id, $alert_message);

        return $response;
		// return json_encode($response);
		// return redirect()->route('superadmin.user.membership', ["customer_id" => $customer_id])->with(['message' => $response['alert_message'], 'alert-type' => $response['alert_type']]);
    }

    /**
    * saveAndSendMakePaymentLink - Onclick Save & Send Payment link button 
    * Validate Member plan details if no errors found,
    * Call updateMembershipDetails & sendMakePaymentLink methods
    * @param $request - Request Object
    * has Member plan details. 
    * @param $customer_id - Integer
    * is used to retrieve customer existing Membership plan details.
    * @param return View - read
    * Redirect user to member plan details read page.
    */
    public function saveAndSendMakePaymentLink(Request $request, $customer_id)
    {
        $response = array();
        $response['alert_message'] = "Something went wrong. Please try again.";
        $response['alert-type'] = "error";

        $rule = $this->getMembershipFormRules();
        $ruleMessage = $this->getMembershipFormValidationMessages();
        $validator = Validator::make($request->all(), $rule, $ruleMessage);
        
        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $is_membership_upgrade = false;
        $member = User::find($customer_id);

        if ($member->user_pricing_plan) {
            if ($member->user_pricing_plan->pricing_plan_id == 0) {
                $is_membership_upgrade = true;
            }
        }

        $save_memberplan_info_status = $this->updateMembershipDetails($request, $customer_id, "send_payment_link_button");
        
        if ($save_memberplan_info_status) {
            $response = $this->sendMakePaymentLink($request, $customer_id, $is_membership_upgrade);
        }

        $download_invoice = true;
        if ($member->user_pricing_plan->pricing_plan_id == 1) {
            $download_invoice = false;
        }
        
        return redirect()->route('superadmin.user.membership', ["customer_id" => $customer_id])->with(['message' => $response['alert_message'], 'alert-type' => $response['alert_type'], 'download_invoice' => $download_invoice, 'download_member_id' => $customer_id]);
    }

    /**
    * formatUserPricingPlanDetails - Based on customer plan, Formatting user pricing plan details in Array format
    * @param $user_pricing_plan - UserPricingPlan Obj
    * @param $is_default_plan - boolean - false|true
    * @param return $response - Array
    * returns array of formatted membership plan details.
    */
    public function formatUserPricingPlanDetails($user_pricing_plan, $is_default_plan=false)
    {
    	$user_plan_details = array();
    	$plan_status = "Active";

        if (!$user_pricing_plan->plan_status) {
            $plan_status = "Inactive";
        }
        
        if (!$is_default_plan) {
            
            if ($user_pricing_plan->pricing_plan_id == 0 || $user_pricing_plan->pricing_plan_id !=4 && !$user_pricing_plan->plan_status) {
                $prev_pricing_plan = $user_pricing_plan->user->get_member_previous_plans[0]->pricing_plan;

                $start_date = $user_pricing_plan->user->get_member_previous_plans[0]->start_date;
                $end_date = $user_pricing_plan->user->get_member_previous_plans[0]->end_date;

                return self::formatUserPreviousPricingPlanDetails($prev_pricing_plan, $start_date, $end_date, $user_pricing_plan->transaction_id);
            }   
        }

    	$user_plan_details['plan'] = $is_default_plan ? "-" : $user_pricing_plan->plan->name;
    	$user_plan_details['customers'] = $is_default_plan ? "-" : $user_pricing_plan->free_customer_limit;
    	$user_plan_details['additional_customer_price'] = $is_default_plan ? "-" : $user_pricing_plan->additional_customer_price;

    	$user_plan_details['recordent_report_indv_price'] = $is_default_plan ? "-" : $user_pricing_plan->consent_recordent_report_price;
    	$user_plan_details['recordent_cmph_report_indv_price'] = $is_default_plan ? "-" : $user_pricing_plan->consent_comprehensive_report_price;

    	$user_plan_details['recordent_report_business_price'] = $is_default_plan ? "-" : $user_pricing_plan->recordent_report_business_price;
    	$user_plan_details['recordent_cmph_report_bussiness_price'] = $is_default_plan ? "-" : $user_pricing_plan->recordent_cmph_report_bussiness_price;

        $user_plan_details['collection_fee_tier_1'] = $is_default_plan ? "-" : $user_pricing_plan->collection_fee_tier_1;
        $user_plan_details['collection_fee_tier_2'] = $is_default_plan ? "-" : $user_pricing_plan->collection_fee_tier_2;
    	$user_plan_details['collection_fee'] = $is_default_plan ? "-" : $user_pricing_plan->collection_fee;
        
    	$user_plan_details['membership_price'] = $is_default_plan ? "-" : $user_pricing_plan->membership_plan_price;

    	$user_plan_details['start_date'] = $is_default_plan ? "-" : date("d-m-Y", strtotime($user_pricing_plan->start_date));
    	$user_plan_details['end_date'] = $is_default_plan ? "-" : date("d-m-Y", strtotime($user_pricing_plan->end_date));
    	$user_plan_details['transaction_id'] = $is_default_plan ? "-" : $user_pricing_plan->transaction_id;
    	$user_plan_details['plan_status'] = $plan_status;

    	return $user_plan_details;
    }


    public function formatUserPreviousPricingPlanDetails($prev_pricing_plan, $start_date, $end_date, $transaction_id){
        
        $user_plan_details = array();

        $user_plan_details['plan'] = $prev_pricing_plan->name;
        $user_plan_details['customers'] = $prev_pricing_plan->free_customer_limit;
        $user_plan_details['additional_customer_price'] = $prev_pricing_plan->additional_customer_price;

        $user_plan_details['recordent_report_indv_price'] = $prev_pricing_plan->consent_recordent_report_price;
        $user_plan_details['recordent_cmph_report_indv_price'] = $prev_pricing_plan->consent_comprehensive_report_price;

        $user_plan_details['recordent_report_business_price'] = $prev_pricing_plan->recordent_report_business_price;
        $user_plan_details['recordent_cmph_report_bussiness_price'] = $prev_pricing_plan->recordent_cmph_report_bussiness_price;

        $user_plan_details['collection_fee_tier_1'] = $prev_pricing_plan->collection_fee_tier_1;
        $user_plan_details['collection_fee_tier_2'] = $prev_pricing_plan->collection_fee_tier_2;
        $user_plan_details['collection_fee'] = $prev_pricing_plan->collection_fee;
        
        $user_plan_details['membership_price'] = $prev_pricing_plan->membership_plan_price;

        $user_plan_details['start_date'] = date("d-m-Y", strtotime($start_date));
        $user_plan_details['end_date'] = date("d-m-Y", strtotime($end_date));
        $user_plan_details['transaction_id'] = $transaction_id;
        $user_plan_details['plan_status'] = 'Active';


        return $user_plan_details;
    }

    /**
    * getMembershipFormRules - Membership form custom validation rules
    * @param return $response - Array
    * returns array of Validation rules.
    */
    public function getMembershipFormRules()
    {
        $rule = [
           'free_customer_limit'=>'required|numeric|gt:0|lte:10000000',
           'additional_customer_price' => 'required|numeric|lte:10000000',
           'consent_recordent_report_price' => 'required|numeric|lte:10000000',
           'consent_comprehensive_report_price' => 'required|numeric|lte:10000000',
           'recordent_report_business_price' => 'required|numeric|lte:10000000',
           'recordent_cmph_report_bussiness_price' => 'required|numeric|lte:10000000',
           'membership_plan_price' => 'required|numeric|lte:10000000',
           'start_date' => 'required|date_format:d/m/Y',
           'end_date' => 'required|date_format:d/m/Y'        
        ]; 

        return $rule;  
    }

    /**
    * getMembershipFormValidationMessages - Membership form custom validation error messages
    * @param return $response - Array
    * returns array of Custom Validation messages.
    */
    public function getMembershipFormValidationMessages()
    {
        $ruleMessage = [
            'free_customer_limit.gt' => 'Customers limit must be greater than 0.',
            'free_customer_limit.lte' => 'Customers limit must be less than or equal to 1,00,00,000.',
            'additional_customer_price.lte'=>'The Additional Customer Price must be less than or equal to 1,00,00,000.',
            'consent_recordent_report_price.lte'=>'The Recordent report - Individual Price must be less than or equal to 1,00,00,000.',
            'consent_comprehensive_report_price.lte'=>'The Recordent comprehensive report - Individual Price must be less than or equal to 1,00,00,000.',
            'recordent_report_business_price.lte'=>'The Recordent report - Business Price must be less than or equal to 1,00,00,000.',
            'recordent_cmph_report_bussiness_price.lte'=>'The Recordent comprehensive report - Business Price must be less than or equal to 1,00,00,000.',
            'membership_plan_price.lte'=>'The Membership Price must be less than or equal to 1,00,00,000.',
        ];

        return $ruleMessage;
    }



    public function downloadMembershipInvoice(Request $request, $member_id, $transaction_id=null){

        $invoice_data = array();
        $transaction_id = $request->input('transaction_id');
        $member_id = $request->input('member_id');

        $user = User::find($member_id);
        
        $is_plan_active = true;
        if (isset($user->user_pricing_plan) && !$user->user_pricing_plan->plan_status) {
            $is_plan_active = false;
        }

        if ($transaction_id == '-' || $transaction_id == null || $transaction_id == '' ) {
            $transaction_id = null;
        }

        $invoice_data['transaction_id'] = $transaction_id;
        $invoice_data['is_plan_active'] = $is_plan_active;

        $invoice_data['membership_plan_name'] = $user->user_pricing_plan->pricing_plan->name;
        $invoice_data['membership_plan_price'] = $user->user_pricing_plan->membership_plan_price;
        $invoice_data['membership_plan_gst_percentage'] = $user->user_pricing_plan->pricing_plan->consent_recordent_report_gst;
        $invoice_data['user_business_name'] = $user->business_name;
        $invoice_data['user_state_name'] = $user->state->name;
        $invoice_data['user_gstin_udise'] = $user->gstin_udise;
        $invoice_data['user_state_id'] = $user->state_id;

        $invoice_data['gst_value'] = $invoice_data['membership_plan_price'] * $invoice_data['membership_plan_gst_percentage']/100;
        $invoice_data['convenience_fee'] = 0;

        $invoice_data['total_price'] = $invoice_data['membership_plan_price'] + $invoice_data['gst_value'] + $invoice_data['convenience_fee'];

        $dateTime = date('d-m-Y H:i:s');
        

        $pdf = PDF::loadView('admin.membership_invoice.report.invoice_pdf', [
                'user' => $user,
                'invoice_data' => $invoice_data,
                'dateTime' => $dateTime
            ])->setPaper('a4', 'portrait');

        $fileName = 'Invoice'.$member_id.'_'.$invoice_data['membership_plan_name'].'_'.$dateTime.'.pdf';
        return $pdf->download('Recordent-' . $fileName);
    }

    /**
    * get Invoice list 

    */

    public function invoiceList(Request $request, $customer_id)
    {
        $user=User::where('id',$customer_id)->first();
        $invoice_types = InvoiceType::all();
        $invoices =  $user->invoices()->paginate(25);
    	return view('superadmin.invoice.read', [
    		'invoices' => $invoices,
            'user_name'=>$user->business_name
    	]);
    }


    public function creditReport_export(Request $request) 
    {
        $from_date = $request->input('from_date')??'0';
        $to_date = $request->input('to_date')??'0';
        if(($from_date !=0) && ($to_date == 0))
		{	
				$to_date=Carbon::today()->toDateString();	
                $to_date=date('Y-m-d',strtotime('+1 day', strtotime($to_date)));
		}

       
       return Excel::download(new CreditreportExport($from_date, $to_date), 'Creditreport.xlsx');
    }


    public function customer_credit_report_analysis(Request $request)
    {

        $from_date=$request->fromdate??'0';
        $to_date=$request->todate??'0';
        $fdate="";
        $tdate="";
        if(($from_date !=0) && ($to_date == 0))
		{	
				$to_date=Carbon::today()->toDateString();
                $to_date=date('Y-m-d',strtotime('+1 day', strtotime($to_date)));	
		}
        
        $flag=1;
        if(!empty($from_date) && !empty($to_date))
        {
            $fdate=$from_date;
            $tdate=$to_date;
            $to_date=date('Y-m-d',strtotime('+1 day', strtotime($to_date)));
            $listofData_latest=General::CreditReportAnalysis_GetList($from_date,$to_date);
            $count_array=General::CreditReportAnalysis_totalCount($from_date,$to_date);
            $flag=0;
        }else{
            $date_new=Carbon::today()->toDateString();
            $date_newdate=date('Y-m-d',strtotime('+1 day', strtotime($date_new)));

            $listofData_latest=General::CreditReportAnalysis_GetList($date_new,$date_newdate);
            $count_array=General::CreditReportAnalysis_totalCount($date_new,$date_newdate);

        }
        
            $Individual_count=0;
            $business_count=0;
            foreach($count_array as $rec)
            {
                if($rec['type'] == 'Individual'){
                    $Individual_count=$rec['count'];
                }else{
                    $business_count=$rec['count'];
                }
            }

    	return view('superadmin.credit_report', [
                    'total_viewed_individual' => $Individual_count,
                    'total_viewed_business' => $business_count,
                    'flag'=>$flag,
                    'credit_reportrecords'=>$listofData=array(),
                    'credit_reportrecords_latest'=>$listofData_latest,
                    'display_count'=>$count_array,
                    'fdate'=>$fdate,
                    'tdate'=>$tdate
            
    	]);
    }

    public function ConsentLog_export()
    {
        return Excel::download(new ConsentLogExport($downloadType="ConsentLogReports"), 'Consentlog.xlsx');
    }
    public function ConsentLog(Request $request)
    {
        $consent_request_dtls = consentRequest::select('consent_request.*','consent_api_response.response','consent_api_response.ip_address as ip_address_response','consent_api_response.request_data','consent_api_response.created_at as response_date_at' ,'consent_api_response.status as status_response')
                                                ->leftJoin('consent_payment', 'consent_request.id', '=', 'consent_payment.consent_id')
                                                ->leftJoin('consent_api_response', 'consent_payment.consent_id', '=', 'consent_api_response.consent_request_id')
                                                ->orderBy('consent_request.created_at','DESC')
                                                ->paginate(25);
        foreach($consent_request_dtls as $rec)
        {
            $auth = User::where('id',$rec['added_by'])->first();
            $rec->memberName=$auth['business_name'];
            $rec->user_register_date=$auth['created_at'];
        }

        return view('superadmin.consent_log',compact('consent_request_dtls'));

    } 

    public function MembersReportsExport()
    {
        return Excel::download(new ConsentLogExport($downloadType="MembersReports"), 'Consentlog.xlsx');
    }

    public function Member_Reports(Request $request)
    {
        $consent_request_dtls = consentRequest::select('consent_request.*','consent_api_response.response','consent_api_response.ip_address as ip_address_response','consent_api_response.request_data','consent_api_response.created_at as response_date_at' ,'consent_api_response.status as status_response'
                                                        ,'consent_payment.status as paymentStatus','consent_payment.payment_value')
                                                ->leftJoin('consent_payment', 'consent_request.id', '=', 'consent_payment.consent_id')
                                                ->leftJoin('consent_api_response', 'consent_payment.consent_id', '=', 'consent_api_response.consent_request_id')
                                                ->orderBy('consent_request.created_at','DESC')
                                                ->paginate(25);
        foreach($consent_request_dtls as $rec)
        {
            $auth = User::where('id',$rec['added_by'])->first();
            $rec->memberName=$auth['business_name'];
        }

        return view('superadmin.member_reports',compact('consent_request_dtls'));

    } 

}
