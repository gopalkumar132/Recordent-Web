<?php

namespace App\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Validator;
use Response;
use Carbon\Carbon;
use DB;
use Auth;
use Illuminate\Support\Collection;
use General;
use Illuminate\Support\Str;
use App\Services\SmsService;
use Log;
use App\PricingPlan;
use App\UserPricingPlan;
use App\MakePaymentRequest;
use App\MakePayment;
use PaytmWallet;
use App\MembershipPayment;
use Mail;
use PDF;
use HomeHelper;

class MakePaymentController extends Controller
{
	/**
    * index method is to show customer payment details.
    * @param $uniqueUrlCode - String
    * is to Retrieve Customer payment details from MakePaymentRequest table.
    * @param return View - index
    * returns make payment view with customer payment details.
    */
	public function index($uniqueUrlCode)
	{
		$payment_request_details = array();
		$user_state = '';
		$payment_request_details = MakePaymentRequest::where('unique_url_code', $uniqueUrlCode)->first();

		if (isset($payment_request_details)) {
			$user_details = User::find($payment_request_details->customer_id);

			$user_state = $user_details->state->name?? '';
		}
		
		return view('front-ib/make-payment/index', [
			'payment_request_details' => $payment_request_details,
			'view_type' => 'payment',
			'user_state' => $user_state
		]);
	}

	/**
    * makePayment method is to Initiate Payment Process and redirects to Paytm Payment gateway with callback url.
    * @param $uniqueUrlCode - String
    * is to Retrieve Customer payment details from MakePaymentRequest table.
    * @param return View - index
    * Redirects from make payment page to Paytm payment gateway with callback url.
    */
	public function makePayment(Request $request, $uniqueUrlCode)
	{

		$payment_request_details = MakePaymentRequest::where('unique_url_code', $uniqueUrlCode)->first();
		
		if ($payment_request_details) {
			
			$order_id = $payment_request_details->order_id;
			$payment_value = $payment_request_details->payment_value;
			$total_collection_value = $payment_request_details->total_collection_value;
			$gst_value = $total_collection_value - $payment_value;	
		} else {
			return redirect()->back()->with(['message' => "Invalid Payment link. Please try again.", 'alert-type' => 'error']);
		}

		try {
			General::add_to_debug_log($payment_request_details->customer_id, "Inserting Payment info into MakePayment table.");
			$insert = [
				'added_by'=>$payment_request_details->added_by,
				'customer_id'=>$payment_request_details->customer_id,
				'order_id' => $order_id,
				'customer_type'=> $payment_request_details->customer_type,
				'payment_type' => $payment_request_details->payment_type,
				'unique_url_code'=>$uniqueUrlCode,
				'status'=> 1,
	            'customer_mobile_no'=> $payment_request_details->customer_mobile_no,
	            'payment_value'=> $payment_value,
	            'total_collection_value' => $total_collection_value,
	            'gst_value' => $gst_value
			];

			$insert = MakePayment::create($insert);	

			General::add_to_debug_log($payment_request_details->customer_id, "Inserted Payment info into MakePayment table.");
		} catch (Exception $e) {
			General::add_to_debug_log($payment_request_details->customer_id, "can not create payment process");
			return redirect()->back()->with(['message' => "can not create payment process. Please try again.", 'alert-type' => 'error']);
		}

		$customer_details = User::find($payment_request_details->customer_id);
		$customer_name = preg_replace('/\s+/', '_', $customer_details->name);
		
		$insert->pg_type = setting('admin.payment_gateway_type');
	    $insert->update();

		if(setting('admin.payment_gateway_type')=='paytm'){
			$payment = PaytmWallet::with('receive');
	        $payment->prepare([
	            'order'=> $order_id,
	            'user'=> $customer_name,
				'mobile_number'=> $customer_details->mobile_number,
				'email'=> $customer_details->email,
	            'amount'=> $total_collection_value,
	            'callback_url'=> route('customer.payment-callback')
	        ]);

        General::add_to_payment_debug_log($payment_request_details->customer_id, 1);
        
        return $payment->view('admin.payment-submit')->receive();
		} else {
			$postData = [
						'amount'=>$total_collection_value,
						'txnid'=>$order_id,
						'firstname' => preg_replace('/\s+/', '', $customer_details->name),
						'email' => $customer_details->email,
						'phone' => $customer_details->mobile_number,
						'surl'=>route('customer.payment-callback'),
					];
					//dd($postData);
					$payuForm = General::generatePayuForm($postData);
					return view('admin.payment-submit',compact('payuForm'));
		}
	}

	/**
    * customerPaymentCallback method will be called after Paytm transaction has been done
    *  & Redirects user to make-payment status page.
    * @param $request - Request Object
    * @param return View - index
    * Redirects user to make-payment status page with transaction status success or failure.
    */
	public function customerPaymentCallback(Request $request){
		
		$response = array();
		if(setting('admin.payment_gateway_type')=='paytm'){
			$transaction = PaytmWallet::with('receive');
			Log::debug('Paytm');
			try{
				$response = $transaction->response();
				Log::debug('Paytm'.print_r($response
					, true));
			}catch(\Exception $e){
				Log::debug("Something went wrong. No payment info found.");
				return redirect()->back()->with(['message' => "Something went wrong", 'alert-type' => 'error']);
			}
		} else {
			try{
				$response = General::verifyPayuPayment($request->all());
				if(!$response){
					Log::debug("Something went wrong. No payment info found.");
					return redirect()->back()->with(['message' => "Something went wrong", 'alert-type' => 'error']);
				}
			}catch(\Exception $e){
				Log::debug("Something went wrong. No payment info found.");
				return redirect()->back()->with(['message' => "Something went wrong", 'alert-type' => 'error']);
			}
		}
		$order_id = $response['ORDERID'];
		$payment_details = MakePayment::where('order_id', $order_id)->first();
		
		
		if(setting('admin.payment_gateway_type')=='paytm'){
      		if($transaction->isSuccessful()){
      			$paymentStatus = 'success';
      		} else if ($transaction->isFailed()) {
      			$paymentStatus = 'failed';
      		}else{
      			$paymentStatus = 'open';
      		}
      	} else {
      		$paymentStatus = $response['paymentStatus']=='success' ? 'success': ($response['paymentStatus']=='failure' ? 'failed' : 'open');
		}
		
		$transaction_id = $response['TXNID'] ?? $response['mihpayid'] ?? '';
		$payment_mode = $response['PAYMENTMODE'] ?? $response['mode'] ?? '';
		
		$payment_details->transaction_id = $transaction_id;
		$payment_details->payment_mode = $payment_mode;

		if($paymentStatus=='success'){
            $payment_details->status = 4;
            
        } else if($paymentStatus=='failed'){
            $payment_details->status = 5;

        } else if($paymentStatus == 'open'){
            $payment_details->status = 2;
        } else {
        	$payment_details->status = 3;
        }

        $payment_details->save();

        General::add_to_payment_debug_log($payment_details->customer_id, $payment_details->status);

        if ($payment_details->payment_type == "membership" || $payment_details->payment_type == "membership_upgrade") {
        	
        	General::add_to_debug_log($payment_details->customer_id, "Updating MembershipPayment table");
        	$this->updateMembershipPayments($payment_details->customer_id, $order_id, $response);
        	General::add_to_debug_log($payment_details->customer_id, "Updating MembershipPayment table success.");


        	$is_membership_upgrade = false;
        	if ($payment_details->payment_type == "membership_upgrade") {
        		$is_membership_upgrade = true;
        	}

        	$response = $this->sendmail($payment_details->id, $is_membership_upgrade);

        }

        $payment_request_details = MakePaymentRequest::where('order_id', $order_id)->first();

        if($payment_details->status == 4 || $payment_details->status == 2){
            $payment_request_details->delete();
        } else {
        	$payment_request_details->status = $payment_details->status;
        	$payment_request_details->save();

        	return redirect()->route('customer.payment-page', [$payment_request_details->unique_url_code]);
        }

		return redirect()->route('customer.payment-status', [$order_id]);
	}

	/**
    * paymentStatus - Ret
    * @param $order_id - String
    * is to retrieve payment transaction status success or failure
    * @param return View - index
    * Show customer payment transaction status by order_id.
    */
	public function paymentStatus($order_id)
	{
		$payment_details = array();

		$alertType = "error";
		$message = "Something went wrong.";

		$payment_details = MakePayment::where('order_id', $order_id)->first();

		if (isset($payment_details) && !empty($payment_details)) {
			if ($payment_details->status == 4) {
				$alertType = 'success';
	            $message='your payment has been successfully completed.';
			}

			if ($payment_details->status == 5) {
				$alertType = 'error';
	            $message='Payment failed.';
			}

			if ($payment_details->status == 2) {
				$alertType = 'info';
	            $message='Payment is In Progress.';
			}
		}
		
		return view('front-ib/make-payment/index', [
			'view_type' => 'status',
			'alertType' => $alertType,
			'message' => $message
		]);
	}


	/**
    * updateUserPricingPlanDetails consist of the 
    * @param $customer_id - Integer
    * is to retrieve customer subscribed pricing plan details from UserPricingPlan table.
    * @param $order_id - String
    * retrieve user payment details from MakePayment table.
    * @param $response - Raw Json response
    * has customer payment transaction raw response.
    * @param return $insert_status - Boolean - true|false
    * returns true or false after .
    */
	public function updateMembershipPayments($customer_id, $order_id, $response)
	{
		
		$user_pricing_plan = UserPricingPlan::where('user_id', $customer_id)->first();
		$pricing_plan = PricingPlan::findOrFail($user_pricing_plan->pricing_plan_id);
		$payment_details = MakePayment::where('order_id', $order_id)->first();

		$invoice_no = MembershipPayment::where('created_at','>=',date('Y-m-d 00:00:00'))
										->where('status',4)
										->count();
        $invoice_no = $invoice_no==1?$invoice_no : $invoice_no + 1;
		$invoice_id = date('dmY').sprintf('%07d', $invoice_no);

		$invoice_type_id = 1;
		if ($payment_details->payment_type == "membership_upgrade") {
			$invoice_type_id = 7;
		}

		$membership_payment = MembershipPayment::create([
                'order_id' => $order_id,
                'customer_type' => $payment_details->customer_type,
                'customer_id' => $customer_id,
                'payment_value' => $payment_details->payment_value,
                'pricing_plan_id' => $user_pricing_plan->pricing_plan_id, 
                'status' => $payment_details->status,
                'added_by' => $customer_id,
                'gst_perc' => $pricing_plan->consent_recordent_report_gst,
                'gst_value' => $payment_details->gst_value,
                'total_collection_value' => $payment_details->total_collection_value,
                'transaction_id' => $payment_details->transaction_id,
                'payment_mode' => $payment_details->payment_mode,
                'raw_response' => json_encode($response),
                'particular' => $payment_details->customer_type . ' plan with 1 year validity',
                'pg_type' => setting('admin.payment_gateway_type'),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'invoice_type_id' => $invoice_type_id,
            ]);

		$insert_status = true;
		if (isset($membership_payment) && !empty($membership_payment)) {
        	if ($payment_details->status == 4) {
			
				$membership_payment->user_pricing_plan_id = $user_pricing_plan->id;
				$membership_payment->invoice_id = $invoice_id; //$invoice_id
				$membership_payment->save();

				$user_pricing_plan->invoice_id = $invoice_id; //$invoice_id
		        $user_pricing_plan->membership_payment_id = $membership_payment->id;
		        $user_pricing_plan->transaction_id = $payment_details->transaction_id;
		        $user_pricing_plan->plan_status = 1;
		        $user_pricing_plan->paid_status = 1;
		        $user_pricing_plan->save();

		        HomeHelper::InsertIntoUserMembershipHistory(PricingPlan::find($user_pricing_plan->pricing_plan_id), $customer_id, $membership_payment->id);
			}	
        } else {
        	$insert_status = false;
        }

		return $insert_status;
	}
	

	public function sendmail($id=41, $is_membership_upgrade=false){

        $membership_payment=MakePayment::findOrFail($id);
        $membership_payment_invoice = MembershipPayment::findOrFail($id);

        $invoice_no = $membership_payment_invoice::where('created_at','>=',date('Y-m-d 00:00:00'))->where('status',4)->count();
        $membership_payment->invoice_id = date('dmY').sprintf('%07d',$invoice_no);

		$data["email"]=$membership_payment->user->email;
        $data["client_name"]=$membership_payment->user->name;
        $data["mobile"]= $membership_payment->customer_mobile_no;
        $data["subject"]='Recordent invoice for '.$membership_payment->customer_type.' plan.';
        $mail_template = 'admin.membership_invoice.membership_payment_invoice';

        if ($is_membership_upgrade) {
        	$data["subject"] = 'Your Recordent membership plan upgrade invoice ';
            $mail_template = 'admin.membership_invoice.membership_payment_upgrade_invoice';
        }

        $records=array( );

        $dateTime = date('d-m-Y H:i',strtotime($membership_payment->updated_at));

        $pdf = PDF::loadView('admin.membership_invoice.report.table', [
		    	'membership_payment' => $membership_payment,
		    	'dateTime' => $dateTime,
		    	'membership_plan_name' => $membership_payment->user->user_pricing_plan->pricing_plan->name,
                'membership_plan_gst_percentage' => $membership_payment->user->user_pricing_plan->pricing_plan->consent_recordent_report_gst
		    ])->setPaper('a4','portrait');

        try{
            Mail::send($mail_template, ['membership_payment' => $membership_payment, 'dateTime' => $dateTime], function($message)use($data,$pdf) {
            $message->to($data["email"], $data["client_name"])
            ->subject($data["subject"])
            ->cc([config('custom_configs.cc_emails.support_mail1'),config('custom_configs.cc_emails.support_mail2')])
            ->attachData($pdf->output(), "invoice.pdf");
            });

            if ($is_membership_upgrade && $membership_payment->user->mobile_number) {
                $send_sms_status = HomeHelper::sendPlanUpgradeInvoiceSmsByMobileNo($membership_payment->user->mobile_number);    
            }
        }catch(JWTException $exception){
            $this->serverstatuscode = "0";
            $this->serverstatusdes = $exception->getMessage();
        }
        if (Mail::failures()) {
             $this->statusdesc  =   "Error sending mail";
             $this->statuscode  =   "0";

        }else{

           $this->statusdesc  =   "Message sent Succesfully";
           $this->statuscode  =   "1";
        }
        return response()->json(compact('this'));

 }

}
