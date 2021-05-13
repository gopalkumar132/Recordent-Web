<?php

use Illuminate\Support\Facades\Session as Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\User;
use App\ConsentRequest;
use App\ConsentPayment;
use Illuminate\Support\Facades\Mail as SendMail;
use App\ConsentAPIResponse;
use App\MembershipPayment;


class CreditReportHelper
{

	public static function getEquifaxPostData(){
		$postData = '{
			"EfxCommercialRequest": {
				"serviceCode": "SB1",
				"version": "5.0",
				"tranID": "XSOF",
				"customerNumber":"'.config("app.us_customer_id").'",
				"securityCode": "'.config("app.us_security_code").'",
				"CustomerSecurityInfo": {
					"ProductCode": {
						"name": "'.config("app.us_score_name").'",
						"code": "'.config("app.us_score_code").'",
						"value": "RPT'.config("app.us_score_code").'"
					},
					"ProductCode": {
						"name": "'.config("app.us_product_name").'",
						"code": "'.config("app.us_product_code").'",
						"value": "RPT'.config("app.us_product_code").'"
					},
					"Channel": {
						"Name": "ISTS",
						"IdNumber": "3"
					}
				},
				"StandardRequest": {
					"Folder": {
						"IdTrait": {
							"CompanyNameTrait": {
								"BusinessName": "'.Session::get('business_name').'"
							},
							"AddressTrait": {
								"PostalCode": "'.Session::get('zip_us').'",
								"City": "'.General::get_city_name(Session::get('city_us')).'",
								"State": "'.General::get_state_code(Session::get('state_us')).'",
								"AddressLine1": "'.Session::get('address_line1').'"
							}
						}
					}
				}
			}
		}';

		return $postData;
	}
	public static function makeEquifaxApiCall(){

		$endPoint = config('app.equifax_us_b2b_url');
		$postData = self::getEquifaxPostData();

		$response_api = General::process_equifax_request($postData, $endPoint);
		// Log::debug('equifax api response postpaid = '.json_encode($response_api));
		// Log::debug('equifax api postData postpaid = '.$postData);
		

		return $response_api;
	}


	public static function insertIntoConsentRequestTable(){
		
		$insert = [
			'added_by' => Auth::id(),
			'customer_type' => 'USBUSINESS',
			'created_at' => Carbon::now(),
			'searched_at' => Carbon::now(),
			'unique_url_code' => Str::random(10),
			'status' => 0,
			'person_name' => Auth::user()->name,
			'contact_phone' => Auth::user()->mobile_number
		];

		$consentRequestInsert = ConsentRequest::create($insert);

		return $consentRequestInsert;
	}

	public static function insertIntoConsentPaymentTable($order_id, $consent_request_id, $payment_value, $status){

		$consentPayment = ConsentPayment::create([
					'order_id' => $order_id,
					'customer_type' => "USBUSINESS",
					'person_name' => Auth::user()->name,
					'contact_phone' => Auth::user()->mobile_number,
					'consent_id' => $consent_request_id,
					'payment_value' => $payment_value,
					'status' => $status,
					'created_at' => Carbon::now(),
					'added_by' => Auth::id(),
				]);

		return $consentPayment;
	}

	public static function insertIntoConsentApiResponseTable($consent_request_id, $equifax_api_response, $status){
		
		$consent_api_response = new ConsentAPIResponse();
		
		$consent_api_response->consent_request_id = $consent_request_id;
		$consent_api_response->response = General::encrypt(json_encode($equifax_api_response));
		$consent_api_response->request_data = General::encrypt(json_encode(self::getEquifaxPostData()));
		$consent_api_response->ip_address = request()->ip();
		$consent_api_response->created_at = Carbon::now();
		$consent_api_response->status = $status;
		$consent_api_response->save();

		return $consent_api_response;
	}


	public static function getConsentPaymentInvoiceId(){
		$invoice_no = ConsentPayment::where('created_at', '>=', date('Y-m-d 00:00:00'))
						->where('status', 4)
						->whereNull('refund_status')
						->count();
		$invoice_id = date('dmY') . sprintf('%07d', $invoice_no);

		return $invoice_id;
	}


	public static function usb2b_invoice_sendmail($consent_payment_id){
		
		$consent_payment = Consentpayment::where('id', $consent_payment_id)->first();

		$dateTime = date('d-m-Y H:i', strtotime($consent_payment->created_at));

		$usa_b2b_credit_report_price = Auth::user()->user_pricing_plan->usa_b2b_credit_report;
		$consent_payment_value_gst_in_perc = HomeHelper::getConsentRecordentReportGst();

		$gst_price = 0;
		if ($consent_payment_value_gst_in_perc > 0) {
			$temp = ($usa_b2b_credit_report_price * $consent_payment_value_gst_in_perc) / 100;
			$temp = round($temp);
			$gst_value = (int)$temp;
		}

		$user = Auth::user();
		$data["email"] = $user->email;
		$data["client_name"] = $user->name;
		$data["subject"] = 'Invoice for US Business Credit Report';

		$pdf = PDF::loadView('admin.us_report_invoice.us_pdf_report_invoice', compact('user', 'dateTime','consent_payment', 'gst_value', 'usa_b2b_credit_report_price'))->setPaper('a4','portrait');
		
		try{
			SendMail::send('admin.us_report_invoice.us_report_invoice_mail', compact('user'), function($message)use($data,$pdf) {
				$message->to($data["email"], $data["client_name"])
				->subject($data["subject"])
				->attachData($pdf->output(), "us_report_invoice.pdf");
			});
		} catch (JWTException $exception){
			// $exception->getMessage();
			Log::debug('Exception in sending US Report Invoice mail '.$exception->getMessage().' email = '.$user->email);
		}
		
		if (SendMail::failures()) {
			Log::debug('Error sending Us Report Invoice mail to '.$user->email);

			return false;
		}

		return true;
	}


	public static function getMembershipPaymentsInvoiceId(){
		$invoice_no = MembershipPayment::where('created_at', '>=', date('Y-m-d 00:00:00'))->where('status', 4)->count();
        $invoice_no = $invoice_no + 1;

        $invoice_id = date('dmY').sprintf('%07d', $invoice_no);

        return $invoice_id;
	}

	public static function insertIntoMembershipPaymentsTable($consent_request_id){

		$consent_payment_value_excluding_gst = Auth::user()->user_pricing_plan->usa_b2b_credit_report;
		$consent_payment_value_gst_perc = HomeHelper::getConsentRecordentReportGst();

		$total_collection_value = $consent_payment_value_excluding_gst;
		if ($consent_payment_value_gst_perc > 0) {
			$temp = ($consent_payment_value_excluding_gst * $consent_payment_value_gst_perc) / 100;
			$temp = round($temp);
			$gst_price = (int)$temp;
			$total_collection_value = $consent_payment_value_excluding_gst + $gst_price;
		}

		$valuesForMembershipPayment = [
	        'customer_id' => Auth::user()->id,
	        'invoice_id' => self::getMembershipPaymentsInvoiceId(),
	        'customer_type' => 'USBUSINESS',
	        'payment_value' => $consent_payment_value_excluding_gst,
	        'gst_perc' => $consent_payment_value_gst_perc,
	        'gst_value' => $gst_price,
	        'total_collection_value' => $total_collection_value,
	        'particular' => "US Business Credit Report",
	        'consent_id' => $consent_request_id,
	        'postpaid' => Auth::user()->reports_us_business,
	        'status' => 4,
	        'discount' => 0,
	        'invoice_type_id' => 9,
	        'pricing_plan_id' => Auth::user()->user_pricing_plan->pricing_plan_id,
	    ];

	    $membershipPayment = MembershipPayment::create($valuesForMembershipPayment);


	    return $membershipPayment;
	}
}