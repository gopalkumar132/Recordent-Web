<?php

namespace App\Http\Controllers;

use App\BusinessBulkUploadIssues;
use App\BusinessDueFees;
use App\Businesses;
use App\BusinessPaidFees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Hash;
use Auth;
use General;
use App\PricingPlan;
use App\UserPricingPlan;
use DB;
use App\TempMembershipPayment;
use App\MembershipPayment;
use PaytmWallet;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\User;
use App\UsersOfferCodes;
use PDF;
use App\UserType;
use App\Country;
use App\State;
use App\City;
use App\Sector;
use Validator;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UpdateProfileEmail;
use App\Http\Controllers\Auth\VerificationController;
use App\IndividualBulkUploadIssues;
use Log;
use File;
use ZipArchive;
use Mail;
use App\InvoiceType;
use App\SkippedDuesRecord;
use App\StudentDueFees;
use App\StudentPaidFees;
use Exception;
use Session;
use App\Students;
use Storage;
use Illuminate\Support\Facades\Mail as SendMail;
use HomeHelper;
use CustomerHelper;
use Response;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();

        // echo General::test();


        return view('home');
    }

    public function logout()
    {
        Auth::logout();
        return redirect(url('/'));
    }

    public function pricing_plan(Request $request, $planId = "")
    {
        if(!empty(General::user_pricing_plan()) && Auth::user()->user_pricing_plan->paid_status && Auth::user()->user_pricing_plan->plan_status){
            return redirect(url('membership'));
        }

        $user = Auth::user();
        $offDataArray = UsersOfferCodes::where('user_id', Auth::id())
            ->where('offer_code_status', 1)->where('offer_code_used', 0)
            ->first();
        $checkOffer = empty($offDataArray) ? 0 : 1;

        $credit_report_type = null;
        if (isset($request->credit_report_type) && !empty($request->credit_report_type)) {
            $credit_report_type = $request->credit_report_type;
        }

        return view('admin.pricing-plan', compact('planId', 'checkOffer', 'credit_report_type'));
    }

    public function register_pricing_plan(Request $request)
    {
        $requestParams = $request->all();
        // dd($requestParams);
        if ($request->pricing_plan_id == 1) {

            if (Auth::user()->profile_verified_at != "") {

                $user = User::findOrFail(Auth::user()->id);
                $user_pricing_plan = $user->user_pricing_plan;
                $pricing_plan = PricingPlan::find($request->pricing_plan_id);

                General::add_to_debug_log($user->id, "Initiated updating UserPricingPlan table data with plan_id =" . $pricing_plan->id);

                if (empty($user_pricing_plan)) {
                    $user_pricing_plan = new UserPricingPlan();
                }

                HomeHelper::InsertIntoUserMembershipHistory($pricing_plan, Auth::user()->id);

                $user_pricing_plan = $this->updateUserPricingPlanDetails($user_pricing_plan, $pricing_plan, $user);
                $user_pricing_plan->plan_status = 1;
                $user_pricing_plan->save();

                $alertType = 'success';
                $message = 'You are now subscribed to Free Trail plan';

                General::add_to_debug_log($user->id, "Updated UserPricingPlan table data with plan_id =" . $pricing_plan->id);
                General::add_to_subscription_debug_log($user->id, $pricing_plan->id);

                $admin_redirect_url = 'admin';
                if ($request->has('credit_report_type') && !empty($request->credit_report_type)) {

                    if($request->credit_report_type == 2){
                        // india b2b credit report url here
                        $admin_redirect_url = route('admin.credit-report');
                    } else if($request->credit_report_type == 3){
                        // us b2b credit report url here
                        $admin_redirect_url = route('us-creditreport');
                    } else {
                        // individual credit report url here
                        $admin_redirect_url = route('admin.credit-report');
                    }
                }

                return redirect(url($admin_redirect_url))->with(['message' => $message, 'alert-type' => $alertType]);
            } else {

                return redirect(url('update-profile/1/'.$request->refferral_status));
            }
        }

        $payment_note = $request->has('upgrade') && $request->upgrade == 1 ? 'UPGRADE' : ($request->has('renew') && $request->renew == 1 ? 'RENEW' : 'For Membership');
        DB::beginTransaction();

        try {
            $payment_date = date('Y-m-d');
            $pricing_plan = PricingPlan::findOrFail($request->pricing_plan_id);
            $payment_amount = $pricing_plan->membership_plan_price;

            if ($request->is_discount == 1) {
                $discountPercent = 100 - setting('admin.one_code_discount');
                $payment_amount = round($payment_amount * $discountPercent / 100);
            }

            Log::debug("payment_note = ".$payment_note);
            $payment_amount = HomeHelper::getMembershipUpgradePlanPrice(Auth::id(), $payment_amount);

            General::add_to_debug_log(Auth::id(), "Initiated updating TempMembershipPayment table data with plan_id =" . $pricing_plan->id);

            $tempDuePayment = TempMembershipPayment::create([
                'order_id' => Str::random(40),
                'customer_type' => 'INDIVIDUAL',
                'customer_id' => Auth::id(),
                'pricing_plan_id' => $request->pricing_plan_id,
                'payment_value' => $payment_amount,
                'created_at' => Carbon::now(),
                'added_by' => Auth::id(),
                'payment_note' => $payment_note,
                'payment_date' => $payment_date
            ]);

            General::add_to_debug_log(Auth::id(), "Updated TempMembershipPayment table data with plan_id =" . $pricing_plan->id);

            $consent_payment_value_gst_in_perc = setting('admin.consent_payment_value_gst_in_perc') ? (int)setting('admin.consent_payment_value_gst_in_perc') : 0;
            $collectionFee = 0;
            $totalGSTValue = 0;
            $totalCollectionValue = 0;

            //1% collection fee
            $temp = ($tempDuePayment->payment_value * 1) / 100;
            $collectionFee = $collectionFee + $temp;
            $collectionFee = bcdiv($collectionFee, 1, 2);

            //GST
            if ($consent_payment_value_gst_in_perc > 0) {
                $temp = ($collectionFee * $consent_payment_value_gst_in_perc) / 100;
                $totalGSTValue = $totalGSTValue + $temp;
                $totalGSTValue = bcdiv($totalGSTValue, 1, 2);
            }

            $totalGSTValue = $payment_amount * $pricing_plan->consent_recordent_report_gst / 100;
            $collectionFee = 0;
            $totalCollectionValue = $tempDuePayment->payment_value + $collectionFee + $totalGSTValue;

            if ($totalCollectionValue < 1) {
                $totalCollectionValue = 1;
            }

            General::add_to_debug_log($tempDuePayment->customer_id, "Initiated updating MembershipPayment table data with plan_id =" . $pricing_plan->id);

            $invoice_type_id = $request->has('upgrade') && $request->upgrade == 1 ? 7 : 1;
            $particular = $pricing_plan->name . " plan with 1 year validity";
            $duePayment = MembershipPayment::create([
                'order_id' => $tempDuePayment->order_id,
                'customer_type' => $tempDuePayment->customer_type,
                'customer_id' => $tempDuePayment->customer_id,
                'payment_value' => $tempDuePayment->payment_value,
                'pricing_plan_id' => $request->pricing_plan_id,
                'status' => 1, //initiated
                'created_at' => Carbon::now(),
                'added_by' => Auth::id(),
                'collection_fee_perc' => 0,
                'gst_perc' => $consent_payment_value_gst_in_perc,
                'gst_value' => $totalGSTValue,
                'collection_fee' => $collectionFee,
                'total_collection_value' => $totalCollectionValue,
                'invoice_type_id' => $invoice_type_id,
                'particular' => $particular
            ]);

            DB::commit();
            General::add_to_debug_log($duePayment->customer_id, "Updated MembershipPayment table data with plan_id =" . $pricing_plan->id);
        } catch (\Exception $e) {
            //echo $e->getMessage(); die;
            Log::debug('error message = '.$e->getMessage());
            // DB::rollback();
            return redirect()->back()->with(['message' => "can not create payment process. Please try again.", 'alert-type' => 'error']);
        }

        $duePayment->pg_type = setting('admin.payment_gateway_type');
        $duePayment->update();

        $userDataToPaytm = User::findOrFail(Auth::user()->id);
        $userDataToPaytm_name = preg_replace('/\s+/', '_', $userDataToPaytm->name);

        $url = route('membership-payment-callback');
        $temp = array();

        $credit_report_type_query_param = '';
        if ($request->has('credit_report_type') && !empty($request->credit_report_type)) {
            $credit_report_type_query_param = '?credit_report_type='.$request->credit_report_type;
        }

        if (setting('admin.payment_gateway_type') == 'paytm') {

            if (isset($requestParams['id'])) {
                $temp['id'] = $requestParams['id'];
            }
            if (isset($requestParams['type'])) {
                $temp['type'] = $requestParams['type'];
            }
            if (isset($requestParams['due_type'])) {
                $temp['due_type'] = $requestParams['due_type'];
            }
            if (!empty($temp)) {
                $url = route('membership-payment-callback', $temp);
            }

            $url = $url.$credit_report_type_query_param;

            $payment = PaytmWallet::with('receive');
            $payment->prepare([
                'order' => $duePayment->order_id,
                'user' => $userDataToPaytm_name,
                'mobile_number' => $userDataToPaytm->mobile_number,
                'email' => $userDataToPaytm->email,
                'amount' => $totalCollectionValue,
                'callback_url' => $url
            ]);

            General::add_to_payment_debug_log($duePayment->customer_id, 1);

            return $payment->view('admin.payment-submit')->receive();
        } else {
            if (isset($requestParams['id'])) {
                $temp['id'] = $requestParams['id'];
            }
            if (isset($requestParams['type'])) {
                $temp['type'] = $requestParams['type'];
            }
            if (isset($requestParams['due_type'])) {
                $temp['due_type'] = $requestParams['due_type'];
            }
            if (!empty($temp)) {
                $url = route('membership-payment-callback', $temp);
            }

            $url = $url.$credit_report_type_query_param;

            $postData = [
                'amount' => $totalCollectionValue,
                'txnid' => $duePayment->order_id,
                'firstname' => preg_replace('/\s+/', '', $userDataToPaytm->name),
                'email' => $userDataToPaytm->email,
                'phone' => $userDataToPaytm->mobile_number,
                'surl' => $url,
            ];

            $payuForm = General::generatePayuForm($postData);

            return view('admin.payment-submit', compact('payuForm'));
        }
        // return redirect(url('admin'));
    }
    function membershipPaymentCallback($id = 0, $type = '', $due_type = '', Request $request)
    {
        // $temp = $this->makePaymentForDuesCallbackImport($id);
        // dd($id, $type, $due_type, $request->all());
        if (setting('admin.payment_gateway_type') == 'paytm') {
            $transaction = PaytmWallet::with('receive');
            try {
                $response = $transaction->response();
                //dd($response);
            } catch (\Exception $e) {
                //add to db log
                return redirect(url('admin'))->with(['message' => "Something went wrong", 'alert-type' => 'error']);
            }
        } else {
            try {
                $response = General::verifyPayuPayment($request->all());
                if (!$response) {
                    return redirect(url('admin'))->with(['message' => "Something went wrong", 'alert-type' => 'error']);
                }
            } catch (\Exception $e) {
                return redirect(url('admin'))->with(['message' => "Something went wrong", 'alert-type' => 'error']);
            }
        }
        //dd($response);
        $duePayment = MembershipPayment::where('order_id', '=', $response['ORDERID'])
            ->where('added_by', Auth::id())
            ->first();

        if (empty($duePayment)) {

            General::add_to_debug_log(Auth::id(), "Invalid due payment");
            return redirect(url('admin'))->with(['message' => "Invalid due payment", 'alert-type' => 'error']);
        }

        $tempDuePayment = TempMembershipPayment::where('order_id', '=', $response['ORDERID'])
            ->where('added_by', Auth::id())
            ->first();

        if (empty($tempDuePayment)) {
            General::add_to_debug_log(Auth::id(), "Invalid due payment");
            return redirect(url('admin'))->with(['message' => "Invalid due payment", 'alert-type' => 'error']);
        }

        $error_url = $tempDuePayment->payment_note == 'UPGRADE' ? route('upgrade-plan') : ($tempDuePayment->payment_note == 'RENEW' ? route('renew-plan') : route('get-pricing-plan'));

        $admin_url = url('admin');

        if ($request->has('credit_report_type') && !empty($request->credit_report_type)) {

            if($request->credit_report_type == 2){
                // india b2b credit report url here
                $admin_url = route('admin.credit-report');
            } else if($request->credit_report_type == 3){
                // us b2b credit report url here
                $admin_url = route('us-creditreport');
            } else {
                // individual credit report url here
                $admin_url = route('admin.credit-report');
            }
        }

        $redirectQueryString = $tempDuePayment->redirect_query_string;

        $message = '';
        $alertType = 'info';

        if (setting('admin.payment_gateway_type') == 'paytm') {
            if ($transaction->isSuccessful()) {
                $paymentStatus = 'success';
            } else if ($transaction->isFailed()) {
                $paymentStatus = 'failed';
            } else {
                $paymentStatus = 'open';
            }
        } else {
            $paymentStatus = $response['paymentStatus'] == 'success' ? 'success' : ($response['paymentStatus'] == 'failure' ? 'failed' : 'open');
        }


        $duePayment->transaction_id = $response['TXNID'] ?? $response['mihpayid'] ?? '';
        $duePayment->payment_mode = $response['PAYMENTMODE'] ?? $response['mode'] ?? '';

        if($paymentStatus=='success'){

            $duePayment->status = 4;
            $alertType = 'success';
            $message = 'Your subscription is successful';

            General::add_to_payment_debug_log(Auth::id(), 4);

            if ($duePayment->pricing_plan_id == 2 || $duePayment->pricing_plan_id == 3) {
                $offerDataCheck = UsersOfferCodes::where('user_id', Auth::id())
                    ->where('offer_code_status', 1)->where('offer_code_used', 0)
                    ->first();
                if (!empty($offerDataCheck)) {
                    $trAmount = $trPlanType = "";
                    if ($duePayment->pricing_plan_id == 2) {
                        $trAmount = 599;
                        $trPlanType = "BASIC";
                    } else if ($duePayment->pricing_plan_id == 3) {
                        $trAmount = 2499;
                        $trPlanType = "EXECUTIVE";
                    } else if ($duePayment->pricing_plan_id == 5) {
                        $trAmount = 1499;
                        $trPlanType = "STANDARD";
                    }

                    $transactionPostData = array("code"=>$offerDataCheck->offer_code,"amount"=>$trAmount,"category"=>$trPlanType,"transactionId"=>Auth::id());
                    $response = General::offer_codes_curl($transactionPostData,'transaction');

                    UsersOfferCodes::where('user_id', Auth::id())->update(array('offer_code_used'=>1,"response"=>$response));
                }
            }
        } else if ($paymentStatus == 'failed') {
            $duePayment->status = 5;
            $alertType = 'error';
            $message = 'Payment failed.';

            General::add_to_payment_debug_log(Auth::id(), 5);
        } else {
            $duePayment->status = 2;
            $alertType = 'info';
            $message = 'Payment is in progress.';

            General::add_to_payment_debug_log(Auth::id(), 2);
        }

        $duePayment->raw_response = json_encode($response);
        $duePayment->updated_at = Carbon::now();
        DB::beginTransaction();
        try {

            if ($paymentStatus == 'success') {

                if ($due_type != '' && $due_type == 'upgrade-plan-due') {
                    if($type == 'import'){
                        $this->makePaymentForDuesCallbackImport($id);
                        $admin_url = route('import-excel-view');
                    } else{
                        $this->makePaymentForDuesCallback($id);
                        $admin_url = route('add-record');
                    }
                }

                if ($due_type != '' && $due_type == 'upgrade-plan-business') {

                    if($type == 'import'){
                        $this->makePaymentForBusinessDuesCallbackImport($id);
                        $admin_url = route('import-excel-view-business');
                    } else{
                        $this->makePaymentForBusinessDuesCallback($id);
                        $admin_url = route('business.add-record');
                    }
                }
            }

            $duePayment->update();
            if ($duePayment->status == 4) { // successful payment

                $user = User::findOrFail($duePayment->customer_id);
                $user_pricing_plan = $user->user_pricing_plan;
                $user_plan = $user->user_pricing_plan;

                General::add_to_debug_log($duePayment->customer_id, "Initiated updating UserPricingPlan table data with plan_id=" . $duePayment->pricing_plan_id);

                if (empty($user_pricing_plan)) {
                    $user_pricing_plan = new UserPricingPlan();
                }
                $invoice_no = MembershipPayment::where('created_at', '>=', date('Y-m-d 00:00:00'))->where('status', 4)->count();
                // $invoice_no = $invoice_no==1?$invoice_no:$invoice_no+1;
                // $invoice_no = $invoice_no+1;
                $pricing_plan = PricingPlan::findOrFail($duePayment->pricing_plan_id);

                Log::debug('tempDuePayment->payment_note'.$tempDuePayment->payment_note);

                HomeHelper::InsertIntoUserMembershipHistory($pricing_plan, $duePayment->customer_id, $duePayment->id);

                $user_pricing_plan = $this->updateUserPricingPlanDetails($user_pricing_plan, $pricing_plan, $user);

                $user_pricing_plan->invoice_id = date('dmY') . sprintf('%07d', $invoice_no);
                $user_pricing_plan->membership_payment_id = $duePayment->id;

                $user_pricing_plan->plan_status = 1;
                $user_pricing_plan->transaction_id = $duePayment->transaction_id;

                $duePayment->invoice_id = $user_pricing_plan->invoice_id;
                $duePayment->user_pricing_plan_id = $user_pricing_plan->id;

                $user_pricing_plan->save();
                $duePayment->update();

                General::add_to_debug_log($duePayment->customer_id, "Updated UserPricingPlan table data with plan_id=" . $duePayment->pricing_plan_id);
                General::add_to_subscription_debug_log($user->id, $pricing_plan->id);

                $message = $message;
                $response = $this->sendmail($duePayment->id,$user_plan);
            }

            // $duePayment->update();
            if ($duePayment->status == 4 || $duePayment->status == 5) {
                $tempDuePayment->delete();
            }


            DB::commit();
        } catch (\Exception $e) {
            // DB::rollback();
            return redirect($error_url)->with(['message' => 'can not store due payment.', 'alert-type' => 'error']);
        }
        //if($transaction->isFailed()){
        if ($duePayment->status == 4) {
            if(isset(Auth::user()->business_short)){
                $business_name = Auth::user()->business_short;
            } else {
                $business_name = Auth::user()->business_name;
            }

            $updateProfileMessageData = array(
                'name' => Auth::user()->name,
                'business_name' => $business_name,
                'pricing_plan' => Auth::user()->user_pricing_plan->pricing_plan->name
                 );
                Notification::route('mail', Auth::user()->email)->notify(new UpdateProfileEmail($updateProfileMessageData));
            }

        if ($paymentStatus == 'failed') {
            return redirect($error_url)->with(['message' => $message, 'alert-type' => $alertType]);
        }
        return redirect($admin_url)->with(['message' => $message, 'alert-type' => $alertType]);
    }

    public function makePaymentForDuesCallback($id)
    {
        $SkippedDuesRecord = SkippedDuesRecord::where('id', $id)->first();

        $authId = Auth::id();
        $temp = $SkippedDuesRecord->toArray();
        $requestData = json_decode($temp['request_data']);

        $aadhar_number = $requestData->aadhar_number;
        $contact_phone = $requestData->contact_phone;
        $invoice_no = $requestData->invoice_no;
        $person_name = $requestData->person_name;
        $dob = $requestData->dob != '' ? Carbon::createFromFormat('d/m/Y', $requestData->dob)->toDateTimeString() : '';
        $father_name = $requestData->father_name;
        $mother_name = $requestData->mother_name;
        if (!is_array($requestData->due_date)) {
            $due_date = Carbon::createFromFormat('d/m/Y', $requestData->due_date)->toDateTimeString();
        } else {
            $due_date = $requestData->due_date;
        }

        $due_amount = $requestData->due_amount;
        $due_note = $requestData->due_note;
        // $proof_of_due = $requestData->file('proof_of_due');
        $collection_date = $requestData->collection_date;
        $grace_period = $requestData->grace_period_hidden;
        // dd($id, $request->all(), $SkippedDuesRecord->toArray(), json_decode($SkippedDuesRecord->request_data), 'in');

        $proofOfDue = [];
        // $files = $request->file('proof_of_due');
        // if ($request->hasFile('proof_of_due')) {
        //  foreach ($files as $key => $file) { //dd(file_get_contents($file->getRealPath()));
        //      $file_get_contents = file_get_contents($file->getRealPath());

        //      $proofOfDue[$key] = Storage::disk('public')->put('proof_of_due', $file);
        //  }
        // }

        $students = Students::where('person_name', 'LIKE', General::encrypt(strtolower($person_name)))
            ->where('contact_phone', '=', General::encrypt($contact_phone))->whereNull('deleted_at')->first();

        if (empty($students)) {
            //dd('456'.$row);
            $students = Students::create([
                'person_name' => $person_name,
                'dob' => $dob,
                'father_name' => $father_name,
                'mother_name' => $mother_name,
                'aadhar_number' => $aadhar_number,
                'contact_phone' => $contact_phone,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
                'added_by' => $authId
            ]);

            $studentId = DB::getPdo()->lastInsertId();
            if ($studentId) {

                if (!is_array($due_amount)) {
                    $studentDue = StudentDueFees::where('student_id', '=', $studentId)->where('due_date', '=', $due_date)->where('added_by', $authId)->whereNull('deleted_at')->first();
                } else {
                    foreach ($due_date as $key => $val) {
                        $thisdue_date = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();
                        $studentDueArr[] = StudentDueFees::where('student_id', '=', $studentId)->where('due_date', '=', $thisdue_date)->where('added_by', $authId)->whereNull('deleted_at')->first();
                    }
                }
                //dd($studentDueArr);
                foreach ($studentDueArr as $key => $arrval) {
                    $proofDueValue = array_key_exists($key, $proofOfDue) ? $proofOfDue[$key] : "";


                    //if(empty($studentDue)){
                    try {
                        if (empty($arrval)) {
                            //foreach($due_date as $key=>$val) {
                            $due_date_formated = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();
                            $collection_date_formated = Carbon::createFromFormat('d/m/Y', $collection_date[$key])->toDateTimeString();
                            //echo "due_date--------->".$due_date."<br/>";
                            $studentDue = StudentDueFees::create([
                                'student_id' => $studentId,
                                'due_date' => $due_date_formated,
                                'due_amount' => str_replace(',', '', $due_amount[$key]),
                                'due_note' => $due_note[$key],
                                'invoice_no' => $invoice_no[$key],
                                'created_at' => Carbon::now(),
                                'added_by' => $authId,
                                'proof_of_due' => $proofDueValue,
                                'collection_date' => $collection_date_formated,
                                'grace_period' => $grace_period[$key]
                            ]);
                            //}

                        } else {
                            //if(!empty($proofOfDue[$key])){
                            if (!empty($proofDueValue)) {
                                $due_date_formated = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();
                                $collection_date_formated = Carbon::createFromFormat('d/m/Y', $collection_date[$key])->toDateTimeString();
                                $studentDue->update([
                                    'student_id' => $studentId,
                                    'due_date' => $due_date_formated,
                                    'due_amount' => str_replace(',', '', $due_amount[$key]),
                                    'due_note' => $due_note[$key],
                                    'invoice_no' => $invoice_no[$key],
                                    'updated_at' => Carbon::now(),
                                    'proof_of_due' => $proofDueValue,
                                    'collection_date' => $collection_date_formated,
                                    'grace_period' => $grace_period[$key]
                                ]);
                            } else {
                                //foreach($due_date as $key=>$val) {
                                $due_date_formated = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();
                                $collection_date_formated = Carbon::createFromFormat('d/m/Y', $collection_date[$key])->toDateTimeString();
                                $studentDue->update([
                                    'student_id' => $studentId,
                                    'due_date' => $due_date_formated,
                                    'due_amount' => str_replace(',', '', $due_amount[$key]),
                                    'due_note' => $due_note[$key],
                                    'invoice_no' => $invoice_no[$key],
                                    'updated_at' => Carbon::now(),
                                    'collection_date' => $collection_date_formated,
                                    'grace_period' => $grace_period[$key]
                                ]);
                                //}
                            }
                        }
                    } catch (Exception $e) {
                        echo 'Message: ' . $e->getMessage();
                    }
                }

                $individual_response = General::generate_magic_url_function($requestData, "individual", $studentId, 'indivSinglerecSkip');
            }
        } else {
            if ($students->id) {
                $studentId = $students->id;
                $valuesForStudent = [
                    'person_name' => $person_name,
                    'dob' => $dob,
                    'father_name' => $father_name,
                    'mother_name' => $mother_name,
                    'aadhar_number' => $aadhar_number,
                    'contact_phone' => $contact_phone,
                    'updated_at' => Carbon::now(),

                ];

                /*if(empty($row['customer_number']) &&  empty($row['customer_number'])){
                    if(empty($students->customer_no) && empty($students->customer_no)){
                        $valuesForStudent['customer_no'] = $row['customer_number'];
                        $valuesForStudent['invoice_no'] = $row['invoice_number'];
                    }
                }else{
                    $valuesForStudent['customer_no'] = $row['customer_number'];
                    $valuesForStudent['invoice_no'] = $row['invoice_number'];
                }*/
                $students->update($valuesForStudent);

                if (!is_array($due_amount)) {
                    $studentDue = StudentDueFees::where('student_id', '=', $studentId)->where('due_date', '=', $due_date)->where('added_by', $authId)->whereNull('deleted_at')->first();
                } else {
                    foreach ($due_date as $key => $val) {
                        $thisdue_date = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();
                        $studentDueArr[] = StudentDueFees::where('student_id', '=', $studentId)->where('due_date', '=', $thisdue_date)->where('added_by', $authId)->whereNull('deleted_at')->first();
                    }
                }
                //dd($studentDueArr);
                //$studentDue = StudentDueFees::where('student_id','=',$studentId)->where('due_date','=',$due_date)->where('added_by',$authId)->whereNull('deleted_at')->first();
                foreach ($studentDueArr as $key => $arrval) {
                    $proofDueValue = array_key_exists($key, $proofOfDue) ? $proofOfDue[$key] : "";
                    try {
                        if (empty($arrval)) {
                            //if(empty($studentDue)){
                            //foreach($due_date as $key=>$val) {
                            $due_date_formated = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();
                            $collection_date_formated = Carbon::createFromFormat('d/m/Y', $collection_date[$key])->toDateTimeString();
                            $studentDue = StudentDueFees::create([
                                'student_id' => $studentId,
                                'due_date' => $due_date_formated,
                                'due_amount' => str_replace(',', '', $due_amount[$key]),
                                'due_note' => $due_note[$key],
                                'invoice_no' => $invoice_no[$key],
                                'created_at' => Carbon::now(),
                                'proof_of_due' => $proofDueValue,
                                'added_by' => $authId,
                                'collection_date' => $collection_date_formated,
                                'grace_period' => $grace_period[$key]
                            ]);
                            //}
                        } else {
                            //if(!empty($proofOfDue[$key])){
                            if (!empty($proofDueValue)) {
                                $due_date_formated = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();
                                $collection_date_formated = Carbon::createFromFormat('d/m/Y', $collection_date[$key])->toDateTimeString();
                                $studentDue->update([
                                    'student_id' => $studentId,
                                    'due_date' => $due_date_formated,
                                    'due_amount' => str_replace(',', '', $due_amount[$key]),
                                    'due_note' => $due_note[$key],
                                    'invoice_no' => $invoice_no[$key],
                                    'updated_at' => Carbon::now(),
                                    'proof_of_due' => $proofDueValue,
                                    'collection_date' => $collection_date_formated,
                                    'grace_period' => $grace_period[$key]
                                ]);
                            } else {
                                foreach ($due_date as $key => $val) {
                                    $due_date_formated = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();
                                    $collection_date_formated = Carbon::createFromFormat('d/m/Y', $collection_date[$key])->toDateTimeString();
                                    $studentDue->update([
                                        'student_id' => $studentId,
                                        'due_date' => $due_date_formated,
                                        'due_amount' => str_replace(',', '', $due_amount[$key]),
                                        'due_note' => $due_note[$key],
                                        'invoice_no' => $invoice_no[$key],
                                        'updated_at' => Carbon::now(),
                                        'collection_date' => $collection_date_formated,
                                        'grace_period' => $grace_period[$key]
                                    ]);
                                }
                            }
                        }
                    } catch (Exception $e) {
                        echo 'Message: ' . $e->getMessage();
                    }
                }
            }
        }

        CustomerHelper::insertIntoMemberCustomerIdMappingTable($authId, $studentId, 1);
        $students->email = $requestData->email;
        $students->save();

        /*One code hit transaction Api Call*/

        $duesCheck = StudentDueFees::where('added_by', Auth::id())->where('due_amount', '>=', 500)->get();
        $checkOfferData = [];
        $TotalDueAmt = 0;
        foreach ($duesCheck as $dcKey => $dcVal) {
            $checkOfferData[] = $dcVal;
            $TotalDueAmt += $dcVal->due_amount;
        }
        //echo "total amount---->".$TotalDueAmt;
        //echo "<pre>"; print_r($checkOfferData); die;
        $offerDataCheck = UsersOfferCodes::where('user_id', Auth::id())
            ->where('offer_code_status', 1)->where('offer_code_used', 0)
            ->first();

        if (!empty($offerDataCheck)) {

            if (count($checkOfferData) >= 2 && $TotalDueAmt >= 3000) {

                General::add_to_debug_log(Auth::id(), "Initiated One code transaction Api Call.");
                $transactionPostData = array(
                    "code" => $offerDataCheck->offer_code,
                    "amount" => 0,
                    "category" => "Basic",
                    "transactionId" => Date('YmdHis')
                );
                $response = General::offer_codes_curl($transactionPostData, 'transaction');
                General::add_to_debug_log(Auth::id(), "One code transaction Api Call Success.");

                UsersOfferCodes::where('user_id', Auth::id())->update(array('offer_code_used' => 1, "response" => $response));
            }
        }
        /*One code hit transaction Api Call ends here*/
        $SkippedDuesRecord->delete();

        return 1;
    }

    public function makePaymentForDuesCallbackImport($id)
    {
        $SkippedDuesRecord = SkippedDuesRecord::where('id', $id)->first();

        $authId = Auth::id();

        $temp = $SkippedDuesRecord->toArray();
        $requestData = json_decode($temp['request_data']);

        foreach ($requestData as $key_rd => $val_rd) {
            $row = array();
            $row['person_name'] = trim($val_rd->person_name);
            $row['contact_phone_number'] = trim($val_rd->contact_phone_number);

            $row['aadhar_number'] = str_replace('-', '', $val_rd->aadhar_number);
            $row['aadhar_number'] = str_replace('_', '', $val_rd->aadhar_number);
            $row['aadhar_number'] = trim($val_rd->aadhar_number);

            $row['dob_ddmmyyyy'] = trim($val_rd->dob_ddmmyyyy);
            $row['father_name'] = trim($val_rd->father_name);
            $row['mother_name'] = trim($val_rd->mother_name);
            $row['duedate_ddmmyyyy'] = trim($val_rd->duedate_ddmmyyyy);
            $row['dueamount'] = str_replace(',', '', $val_rd->dueamount);
            $row['dueamount'] = trim($val_rd->dueamount);
            $row['duenote'] = trim($val_rd->duenote);
            $row['email'] = trim($val_rd->email);
            $row['grace_period'] = trim($val_rd->grace_period);
            $row['invoice_no'] = trim($val_rd->invoice_no);

            if (empty($row['person_name']) && empty($row['father_name']) && empty($row['mother_name']) && empty($row['contact_phone_number']) && empty($row['dob_ddmmyyyy']) && empty($row['duedate_ddmmyyyy']) && empty($row['dueamount']) && empty($row['duenote']) && empty($row['email']) && empty($row['grace_period'])) {
                break;
            }

            //configuation
            $dob_valid_from = Carbon::now()->subYears(100)->format('d/m/Y');

            $due_date_old_in_year = setting('admin.due_date_old_in_year');
            $due_date_max_future_in_year = setting('admin.due_date_max_future_in_year');

            $currentDate = Carbon::now();
            if ($due_date_old_in_year) {
                $due_date_old_in_year = $currentDate->subYears($due_date_old_in_year)->format('d/m/Y');
            }

            $currentDate = Carbon::now();
            if ($due_date_max_future_in_year) {
                $due_date_max_future_in_year = $currentDate->addYears($due_date_max_future_in_year)->format('d/m/Y');
            }

            $row['dueamount'] = str_replace(',', '', $row['dueamount']);

            $authId = Session::get('member_id');
            if (!isset($authId)) {
                $authId = Auth::id();
            }

            $row['duedate_ddmmyyyy'] = str_replace('-', '/', $row['duedate_ddmmyyyy']);
            $row['dob_ddmmyyyy'] = str_replace('-', '/', $row['dob_ddmmyyyy']);

            $dob = '';
            if (!empty($row['dob_ddmmyyyy'])) {
                $newDob = Carbon::createFromFormat('d/m/Y', trim($row['dob_ddmmyyyy']));
                $dob = $newDob->format('Y-m-d');
            }

            $students = Students::where('person_name', 'LIKE', General::encrypt(strtolower($row['person_name'])))
                ->where('contact_phone', '=', General::encrypt($row['contact_phone_number']))->whereNull('deleted_at')->first();

            if (empty($students)) {
                $students = Students::create([
                    'person_name' => $row['person_name'],
                    'dob' => $dob,
                    'father_name' => $row['father_name'],
                    'mother_name' => $row['mother_name'],
                    'aadhar_number' => $row['aadhar_number'],
                    'contact_phone' => $row['contact_phone_number'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'added_by' => $authId
                ]);

                $studentId = DB::getPdo()->lastInsertId();
                if ($studentId) {

                    $dueDate = Carbon::createFromFormat('d/m/Y', trim($row['duedate_ddmmyyyy']));
                    $dueDate  = $dueDate->format('Y-m-d');

					if ($row['grace_period'] == 0 || $row['grace_period'] == "" || $row['grace_period'] <= 1){
						$gracePeriod = 1;
						$collectionDate = date('Y-m-d', strtotime(date('Y-m-d') . ' +1 days'));
					} else {
						$gracePeriod = $row['grace_period'];
						$collectionDate = date('Y-m-d', strtotime($dueDate . '+ ' . $row['grace_period'] . ' days'));
					}

                    $studentDue = StudentDueFees::create([
                        'student_id' => $studentId,
                        'due_date' => $dueDate,
                        'due_amount' => $row['dueamount'],
                        'due_note' => $row['duenote'],
                        'created_at' => Carbon::now(),
                        'added_by' => $authId,
                        'invoice_no' => $row['invoice_no'],
						'grace_period' => $gracePeriod,
						'collection_date' => $collectionDate
                    ]);

                    $individual_response = General::generate_magic_url_function($row, "individual", $studentId, 'indivExcelBulk');
                }
            } else {

                $studentId = $students->id;
                $valuesForStudent = [
                    'person_name' => $row['person_name'],
                    'dob' => $dob,
                    'father_name' => $row['father_name'],
                    'mother_name' => $row['mother_name'],
                    'aadhar_number' => $row['aadhar_number'],
                    'contact_phone' => $row['contact_phone_number'],
                    'updated_at' => Carbon::now(),
                    //'added_by' => $authId,
                ];

                $students->update($valuesForStudent);

                $dueDate = Carbon::createFromFormat('d/m/Y', trim($row['duedate_ddmmyyyy']));
                $dueDate  = $dueDate->format('Y-m-d');

                if ($row['grace_period'] == 0 || $row['grace_period'] == "" || $row['grace_period'] <= 1){
					$gracePeriod = 1;
					$collectionDate = date('Y-m-d', strtotime(date('Y-m-d') . ' +1 days'));
				} else {
					$gracePeriod = $row['grace_period'];
					$collectionDate = date('Y-m-d', strtotime($dueDate . '+ ' . $row['grace_period'] . ' days'));
				}

                $studentDue = StudentDueFees::create([
                    'student_id' => $studentId,
                    'due_date' => $dueDate,
                    'due_amount' => $row['dueamount'],
                    'due_note' => $row['duenote'],
                    'created_at' => Carbon::now(),
                    'added_by' => $authId,
                    'invoice_no' => $row['invoice_no'],
					'grace_period' => $gracePeriod,
					'collection_date' => $collectionDate
                ]);
            }

            CustomerHelper::insertIntoMemberCustomerIdMappingTable($authId, $studentId, 1);
            $students->email = $row['email'];
            $students->save();
        }

        $SkippedDuesRecord->delete();

        return 1;
    }

    public function makePaymentForBusinessDuesCallback($id)
    {
        $SkippedDuesRecord = SkippedDuesRecord::where('id', $id)->first();

        $authId = Auth::id();
        $temp = $SkippedDuesRecord->toArray();
        $requestData = json_decode($temp['request_data']);

        $company_name = $requestData->company_name;

        $sector_id = $requestData->sector_id;
        $unique_identification_number = $requestData->unique_identification_number;
        $concerned_person_name = $requestData->concerned_person_name;
        $concerned_person_designation = $requestData->concerned_person_designation;
        $concerned_person_phone = $requestData->concerned_person_phone;
        $concerned_person_alternate_phone = $requestData->concerned_person_alternate_phone;

        $state_id = $requestData->state;
        $city_id = $requestData->city;
        $pincode = $requestData->pin_code;
        $address = $requestData->address;

        if (!is_array($requestData->due_date)) {
            $due_date = Carbon::createFromFormat('d/m/Y', $requestData->due_date)->toDateTimeString();
        } else {
            $due_date = $requestData->due_date;
        }
        // $paid_date = $requestData->paid_date;
        // $paid_amount = $requestData->paid_amount;
        $due_amount = $requestData->due_amount;
        $due_note = $requestData->due_note;
        // $paid_note = $requestData->paid_note;
        $invoice_no = $requestData->invoice_no;
        // $proof_of_due = $requestData->file('proof_of_due');
        $collection_date = $requestData->collection_date;
        $grace_period = $requestData->grace_period_hidden;
        $proofOfDue = [];

        $business = Businesses::where('unique_identification_number', '=', General::encrypt(strtoupper($unique_identification_number)))->whereNull('deleted_at')->first();

        if (empty($business)) {

            $business = Businesses::create([
                'company_name' => $company_name,
                'sector_id' => $sector_id,
                'unique_identification_number' => $unique_identification_number,
                'concerned_person_name' => $concerned_person_name,
                'concerned_person_designation' => $concerned_person_designation,
                'concerned_person_phone' => $concerned_person_phone,
                'concerned_person_alternate_phone' => $concerned_person_alternate_phone,
                'state_id' => $state_id,
                'city_id' => $city_id,
                'pincode' => $pincode,
                'address' => $address,
                'created_at' => Carbon::now(),
                'added_by' => $authId
            ]);

            $businessId = DB::getPdo()->lastInsertId();
            if ($businessId) {

                foreach ($due_amount as $key => $val) {
                    $proofDueValue = array_key_exists($key, $proofOfDue) ? $proofOfDue[$key] : "";
                    $due_date_formated = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();
                    $collection_date_formated = Carbon::createFromFormat('d/m/Y', $collection_date[$key])->toDateTimeString();

                    $businessDue = BusinessDueFees::create([
                        'business_id' => $businessId,
                        'due_date' => $due_date_formated,
                        'due_amount' => str_replace(',', '', $due_amount[$key]),
                        'due_note' => $due_note[$key],
                        'invoice_no' => $invoice_no[$key],
                        'created_at' => Carbon::now(),
                        'added_by' => $authId,
                        'proof_of_due' => $proofDueValue,
                        'collection_date' => $collection_date_formated,
                        'grace_period' => $grace_period[$key]
                    ]);
                }

                $individual_response = General::generate_magic_url_function($requestData,"business",$businessId ,'BusinessRecSkip');
            }
        } else {

            if ($business->id) {
                $businessId = $business->id;
                $valuesForStudent = [
                    'company_name' => $company_name,
                    'sector_id' => $sector_id,
                    'concerned_person_name' => $concerned_person_name,
                    'concerned_person_designation' => $concerned_person_designation,
                    'concerned_person_phone' => $concerned_person_phone,
                    'concerned_person_alternate_phone' => $concerned_person_alternate_phone,
                    'state_id' => $state_id,
                    'city_id' => $city_id,
                    'pincode' => $pincode,
                    'address' => $address,
                    'updated_at' => Carbon::now(),
                ];

                $business->update($valuesForStudent);

                if (!is_array($due_amount)) {
                    $businessDue = BusinessDueFees::where('business_id', '=', $businessId)->where('due_date', '=', $due_date)->where('added_by', $authId)->whereNull('deleted_at')->first();
                } else {
                    foreach ($due_date as $key => $val) {
                        $thisdue_date = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();
                        $businessDueArr[] = BusinessDueFees::where('business_id', '=', $businessId)->where('due_date', '=', $thisdue_date)->where('added_by', $authId)->whereNull('deleted_at')->first();
                    }
                }

                foreach ($businessDueArr as $key => $arrval) {
                    $proofDueValue = array_key_exists($key, $proofOfDue) ? $proofOfDue[$key] : "";
                    $due_date_formated = Carbon::createFromFormat('d/m/Y', $due_date[$key])->toDateTimeString();
                    $collection_date_formated = Carbon::createFromFormat('d/m/Y', $collection_date[$key])->toDateTimeString();
                    if (empty($arrval)) {
                        $businessDue = BusinessDueFees::create([
                            'business_id' => $businessId,
                            'due_date' => $due_date_formated,
                            'due_amount' => str_replace(',', '', $due_amount[$key]),
                            'due_note' => $due_note[$key],
                            'invoice_no' => $invoice_no[$key],
                            'created_at' => Carbon::now(),
                            'added_by' => $authId,
                            'proof_of_due' => $proofDueValue,
                            'collection_date' => $collection_date_formated,
                            'grace_period' => $grace_period[$key]

                        ]);
                    } else {
                        if (!empty($proofOfDue)) {
                            $businessDue->update([
                                'business_id' => $businessId,
                                'due_date' => $due_date_formated,
                                'due_amount' => str_replace(',', '', $due_amount[$key]),
                                'due_note' => $due_note[$key],
                                'updated_at' => Carbon::now(),
                                'proof_of_due' => $proofDueValue,
                                'invoice_no' => $invoice_no[$key],
                                'collection_date' => $collection_date_formated,
                                'grace_period' => $grace_period[$key]
                            ]);
                        } else {
                            $businessDue->update([
                                'business_id' => $businessId,
                                'due_date' => $due_date_formated,
                                'due_amount' => str_replace(',', '', $due_amount[$key]),
                                'due_note' => $due_note[$key],
                                'invoice_no' => $invoice_no[$key],
                                'updated_at' => Carbon::now(),
                                'collection_date' => $collection_date_formated,
                                'grace_period' => $grace_period[$key]
                            ]);
                        }
                    }
                }
            }
        }

        CustomerHelper::insertIntoMemberCustomerIdMappingTable($authId, $businessId, 2);
        // $business->email = $request->email;
        $business->save();

        /*One code hit transaction Api Call*/

        $duesCheck = BusinessDueFees::where('added_by', Auth::id())->where('due_amount', '>=', 500)->get();
        $checkOfferData = [];

        $TotalDueAmt = 0;
        foreach ($duesCheck as $dcKey => $dcVal) {
            $checkOfferData[] = $dcVal;
            $TotalDueAmt += $dcVal->due_amount;
        }

        $offerDataCheck = UsersOfferCodes::where('user_id', Auth::id())
            ->where('offer_code_status', 1)->where('offer_code_used', 0)
            ->first();

        if (!empty($offerDataCheck)) {

            if (count($checkOfferData) >= 2 && $TotalDueAmt >= 3000) {
                $transactionPostData = array(
                    "code" => $offerDataCheck->offer_code,
                    "amount" => 0,
                    "category" => "Basic",
                    "transactionId" => Date('YmdHis')
                );

                General::add_to_debug_log(Auth::id(), "Business - Initiated One code transaction Api Call.");

                $response = General::offer_codes_curl($transactionPostData, 'transaction');
                General::add_to_debug_log(Auth::id(), "Business - One code transaction Api Call Success.");

                UsersOfferCodes::where('user_id', Auth::id())->update(array('offer_code_used' => 1, "response" => $response));
            }
        }
        /*One code hit transaction Api Call ends here*/

        $SkippedDuesRecord->delete();
        return 1;
    }

    public function makePaymentForBusinessDuesCallbackImport($id)
    {
        $SkippedDuesRecord = SkippedDuesRecord::where('id', $id)->first();

        $authId = Auth::id();
        $temp = $SkippedDuesRecord->toArray();
        $requestData = json_decode($temp['request_data']);
        foreach ($requestData as $key_rd => $val_rd) {
            $row = array();
            // $row['business_name'] = trim($val_rd->business_name);
            // $row['sector_name'] = trim($val_rd->sector_name);
            // $row['unique_identification_number_gstin_business_pan'] = trim($val_rd->unique_identification_number_gstin_business_pan);

            $row = (array)$val_rd;

            foreach ($row as $key => &$value) {
                if (!empty($key)) {
                    $value = trim($value);
                }
            }

            if (!preg_match("/^(((0[1-9]|[12]\d|3[01])\/(0[13578]|1[02])\/((19|[2-9]\d)\d{2}))|((0[1-9]|[12]\d|30)\/(0[13456789]|1[012])\/((19|[2-9]\d)\d{2}))|((0[1-9]|1\d|2[0-8])\/02\/((19|[2-9]\d)\d{2}))|(29\/02\/((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))))$/", $row['duedate_ddmmyyyy'])) {
                if ($row['duedate_ddmmyyyy'] != "") {
                    $row['duedate_ddmmyyyy'] = Carbon::instance(\PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row['duedate_ddmmyyyy']))->format('d/m/Y');
                }
            }

            if (
                empty($row['business_name']) &&
                empty($row['sector_name']) &&
                empty($row['unique_identification_number_gstin_business_pan']) &&
                empty($row['concerned_person_name']) &&
                empty($row['concerned_person_designation']) &&
                empty($row['concerned_person_phone']) &&
                empty($row['state']) &&
                empty($row['city']) &&
                empty($row['duedate_ddmmyyyy']) &&
                empty($row['dueamount']) &&
                empty($row['email']) &&
                empty($row['grace_period'])
            ) {
                break;
            }
            //configuration
            $company_name_min_character = setting('admin.company_name_min_character');
            $company_name_min_character = $company_name_min_character ? $company_name_min_character : 1;
            $due_date_old_in_year = setting('admin.due_date_old_in_year');
            $due_date_max_future_in_year = setting('admin.due_date_max_future_in_year');

            $currentDate = Carbon::now();
            if ($due_date_old_in_year) {
                $due_date_old_in_year = $currentDate->subYears($due_date_old_in_year)->format('d/m/Y');
            }

            $currentDate = Carbon::now();
            if ($due_date_max_future_in_year) {
                $due_date_max_future_in_year = $currentDate->addYears($due_date_max_future_in_year)->format('d/m/Y');
            }

            $row['dueamount'] = str_replace(',', '', $row['dueamount']);

            $authId = Session::get('member_id');
            if (!isset($authId)) {
                $authId = Auth::id();
            }

            if (!empty($row['sector_name'])) {
                $sector = Sector::where('name', '=', $row['sector_name'])->first();
                if ($sector) {
                    // $sectorId = $sector->id;
                }
            }

            $stateId = '';
            if (!empty($row['state'])) {
                $state = State::where('name', '=', $row['state'])->first();
                if ($state) {
                    $stateId = $state->id;
                }
            }

            $cityId = '';
            if (!empty($row['city'])) {
                if (!empty($stateId)) {
                    $city = City::where('name', '=', $row['city'])->where('state_id', $stateId)->first();
                    if ($city) {
                        $cityId = $city->id;
                    }
                }
            }

            $row['duedate_ddmmyyyy'] = str_replace('-', '/', $row['duedate_ddmmyyyy']);
            $businesses = Businesses::where('unique_identification_number', '=', General::encrypt(strtoupper($row['unique_identification_number_gstin_business_pan'])))->whereNull('deleted_at')->first();

            if (empty($businesses)) {
                $businesses = Businesses::create([
                    'company_name' => $row['business_name'],
                    // 'sector_id' => $sectorId,
                    'unique_identification_number' => $row['unique_identification_number_gstin_business_pan'],
                    'concerned_person_name' => $row['concerned_person_name'],
                    'concerned_person_designation' => $row['concerned_person_designation'],
                    'concerned_person_phone' => $row['concerned_person_phone'],
                    'concerned_person_alternate_phone' => $row['concerned_person_alternate_phone'],
                    'state_id' => $stateId,
                    'city_id' => $cityId,
                    'pincode' => $row['pin_code'],
                    'address' => $row['address'],
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'added_by' => $authId
                ]);

                $businessId = DB::getPdo()->lastInsertId();
                if ($businessId) {

                    $dueDate = Carbon::createFromFormat('d/m/Y', $row['duedate_ddmmyyyy']);
                    $dueDate  = $dueDate->format('Y-m-d');

					if ($row['grace_period'] == 0 || $row['grace_period'] == "" || $row['grace_period']<=1) {
						$gracePeriod = 1;
						$collectionDate = date('Y-m-d', strtotime(date('Y-m-d') . ' +1 days'));
					} else {
						$gracePeriod = $row['grace_period'];
						$collectionDate = date('Y-m-d', strtotime($dueDate . '+ ' . $row['grace_period'] . ' days'));
					}


                    $businessDue = BusinessDueFees::create([
                        'business_id' => $businessId,
                        'due_date' => $dueDate,
                        'due_amount' => $row['dueamount'],
                        //'due_note'=> $row['duenote'],
                        'created_at' => Carbon::now(),
                        'added_by' => $authId,
                        'invoice_no' => $row['invoice_no'],
						'grace_period' => $gracePeriod,
						'collection_date' => $collectionDate
                    ]);

                    $individual_response = General::generate_magic_url_function($row, "business", $businessId, 'BusinessExcelBulk');
                }
            } else {
                $businessId = $businesses->id;
                $valuesForBusiness = [
                    'company_name' => $row['business_name'],
                    // 'sector_id' => $sectorId,
                    'unique_identification_number' => $row['unique_identification_number_gstin_business_pan'],
                    'concerned_person_name' => $row['concerned_person_name'],
                    'concerned_person_designation' => $row['concerned_person_designation'],
                    'concerned_person_phone' => $row['concerned_person_phone'],
                    'concerned_person_alternate_phone' => $row['concerned_person_alternate_phone'],
                    'state_id' => $stateId,
                    'city_id' => $cityId,
                    'pincode' => $row['pin_code'],
                    'address' => $row['address'],
                    'updated_at' => Carbon::now(),
                ];

                $businesses->update($valuesForBusiness);

                $dueDate = Carbon::createFromFormat('d/m/Y', $row['duedate_ddmmyyyy']);
                $dueDate  = $dueDate->format('Y-m-d');
				if ($row['grace_period'] == 0 || $row['grace_period'] == "" || $row['grace_period']<=1) {
						$gracePeriod = 1;
						$collectionDate = date('Y-m-d', strtotime(date('Y-m-d') . ' +1 days'));
					} else {
						$gracePeriod = $row['grace_period'];
						$collectionDate = date('Y-m-d', strtotime($dueDate . '+ ' . $row['grace_period'] . ' days'));
					}

                $businessDue = BusinessDueFees::create([
                    'business_id' => $businessId,
                    'due_date' => $dueDate,
                    'due_amount' => $row['dueamount'],
                    //'due_note'=> $row['duenote'],
                    'created_at' => Carbon::now(),
                    'added_by' => $authId,
                    'invoice_no' => $row['invoice_no'],
					'grace_period' => $gracePeriod,
					'collection_date' => $collectionDate
                ]);
            }

            CustomerHelper::insertIntoMemberCustomerIdMappingTable($authId, $businessId, 2);

            $businesses->email = $row['email'];
            $businesses->save();
        }

        $SkippedDuesRecord->delete();
        return 1;
    }

    /**
     * updateUserPricingPlanDetails consist of the
     * @param $user_pricing_plan_obj - UserPricingPlan Model Object
     * is to update subscribed pricing plan details in UserPricingPlan table
     * @param $pricing_plan_obj - PricingPlan Model Object
     * has Subscribed Pricing Plan details
     * @param $user_obj - User Model Object
     * has User details
     * @param return $user_pricing_plan_obj | UserPricingPlan Object
     * returns User Pricing Plan object after updating details.
     */
    public function updateUserPricingPlanDetails($user_pricing_plan_obj, $pricing_plan_obj, $user_obj)
    {
        $user_pricing_plan_obj->pricing_plan_id = $pricing_plan_obj->id;
        $user_pricing_plan_obj->user_id = $user_obj->id;
        $user_pricing_plan_obj->paid_status = 1;
        $user_pricing_plan_obj->start_date = date('Y-m-d H:i:s');
        $user_pricing_plan_obj->end_date = date('Y-m-d H:i:s', strtotime('+364 day'));
        $user_pricing_plan_obj->membership_payment_id = 0;
        $user_pricing_plan_obj->free_customer_limit = $pricing_plan_obj->free_customer_limit ?? 0;
        $user_pricing_plan_obj->membership_plan_price = $pricing_plan_obj->membership_plan_price ?? 0;
        $user_pricing_plan_obj->additional_customer_price = $pricing_plan_obj->additional_customer_price ?? 0;
        $user_pricing_plan_obj->consent_recordent_report_price = $pricing_plan_obj->consent_recordent_report_price ?? 0;
        $user_pricing_plan_obj->consent_comprehensive_report_price = $pricing_plan_obj->consent_comprehensive_report_price ?? 0;
        $user_pricing_plan_obj->recordent_report_business_price = $pricing_plan_obj->recordent_report_business_price ?? 0;
        $user_pricing_plan_obj->recordent_cmph_report_bussiness_price = $pricing_plan_obj->recordent_cmph_report_bussiness_price ?? 0;
        $user_pricing_plan_obj->collection_fee = $pricing_plan_obj->collection_fee ?? 0;
        $user_pricing_plan_obj->collection_fee_tier_1 = $pricing_plan_obj->collection_fee_tier_1 ?? 0;
        $user_pricing_plan_obj->collection_fee_tier_2 = $pricing_plan_obj->collection_fee_tier_2 ?? 0;

        $user_pricing_plan_obj->usa_b2b_credit_report = $pricing_plan_obj->usa_b2b_credit_report?? 6000;

        $user_pricing_plan_obj->transaction_id = null;

        $user_pricing_plan_obj->save();

        return $user_pricing_plan_obj;
    }

    public function invoice()
    {
        if (Auth::guest()) {
            return redirect(url('admin'))->with(['message' => 'Invoice Not Found', 'alert-type' => 'error']);
        }

        $user_pricing_plan = Auth::user()->user_pricing_plan;
        if (empty($user_pricing_plan)) {
            return redirect(url('admin'))->with(['message' => 'Invoice Not Found', 'alert-type' => 'error']);
        };

        $membership_payment = MembershipPayment::where('id', $user_pricing_plan->membership_payment_id)->first();
        if (empty($membership_payment) || is_null($membership_payment)) {
            return redirect(url('admin'))->with(['message' => 'Invoice Not Found', 'alert-type' => 'error']);
        }

        if ($membership_payment->user->user_pricing_plan->pricing_plan_id == 0) {
            $membership_plan_name = $membership_payment->user->get_member_previous_plans[0]->pricing_plan->name ?? '';
            $membership_plan_gst_percentage = $membership_payment->user->get_member_previous_plans[0]->pricing_plan->consent_recordent_report_gst ?? '';
        } else {
            $membership_plan_name = $membership_payment->user->user_pricing_plan->pricing_plan->name;
            $membership_plan_gst_percentage = $membership_payment->user->user_pricing_plan->pricing_plan->consent_recordent_report_gst;
        }

        Log::debug('membership_plan_name = '.$membership_plan_name);

        $records = array();
        $dateTime = date('d-m-Y H:i', strtotime($membership_payment->updated_at));

        $pdf = PDF::loadView('admin.membership_invoice.report.table', [
                'membership_payment' => $membership_payment,
                'dateTime' => $dateTime,
                'membership_plan_name' => $membership_plan_name,
                'membership_plan_gst_percentage' => $membership_plan_gst_percentage])->setPaper('a4', 'portrait');

        $fileName = $membership_payment->invoice_id . '.pdf';
        return $pdf->download('Recordent-' . $fileName);
        // return view('admin.membership_invoice.report.table',compact('membership_payment','dateTime'));
    }

    public function sendmail($id = 41, $user_plan)
    {
        //return 1;
        $data["email"] = (is_null(Auth::user()->email) || Auth::user()->email == '') ? 'contactus@recordent.com' : Auth::user()->email;
        $data["client_name"] = Auth::user()->name;
        $membership_payment = MembershipPayment::findOrFail($id);
        // $data["subject"] = 'Recordent invoice for ' . $membership_payment->pricing_plan->name . ' plan.';
        if($user_plan == null){
             $data["subject"] = 'Your Recordent membership plan invoice ';
             $mail_template = 'admin.membership_invoice.membership_payment_invoice';
        } else {
            $data["subject"] = 'Your Recordent membership plan upgrade invoice ';
            $mail_template = 'admin.membership_invoice.membership_payment_upgrade_invoice';
        }
        // return $membership_payment;
        $records = array();
        // $reportNumber='hjhjhjh';
        $dateTime = date('d-m-Y H:i', strtotime($membership_payment->updated_at));
        // return view('admin.membership_invoice.report.table',compact('membership_payment','dateTime'));
        $pdf = PDF::loadView('admin.membership_invoice.report.table', [
                'membership_payment' => $membership_payment,
                'dateTime' => $dateTime,
                'membership_plan_name' => $membership_payment->user->user_pricing_plan->pricing_plan->name,
                'membership_plan_gst_percentage' => $membership_payment->user->user_pricing_plan->pricing_plan->consent_recordent_report_gst
            ])->setPaper('a4', 'portrait');


        try{
            Mail::send($mail_template, ['membership_payment'=>$membership_payment,'dateTime'=>$dateTime], function($message)use($data,$pdf) {
            $message->to($data["email"], $data["client_name"])
            ->subject($data["subject"])
           ->cc([config('custom_configs.cc_emails.support_mail1'),config('custom_configs.cc_emails.support_mail2')])
            ->attachData($pdf->output(), "invoice.pdf");

            });

            if (Auth::user()->mobile_number) {
                $send_sms_status = HomeHelper::sendPlanUpgradeInvoiceSmsByMobileNo(Auth::user()->mobile_number);
            }


        } catch (JWTException $exception) {
            $this->serverstatuscode = "0";
            $this->serverstatusdes = $exception->getMessage();
        }
        if (Mail::failures()) {
            $this->statusdesc  =   "Error sending mail";
            $this->statuscode  =   "0";
        } else {

            $this->statusdesc  =   "Message sent Succesfully";
            $this->statuscode  =   "1";
        }
        return response()->json(compact('this'));
    }

    public function corporate_plan(Request $request)
    {
        $data["email"] = config('custom_configs.cc_emails.support_mail1');
        $data["client_name"] = Auth::user()->name;
        $data["subject"] = 'Corporate membership enquiry';

        try {
            Mail::send('admin.membership_invoice.corporate_plan', ['user' => Auth::user()], function ($message) use ($data) {
                $message->to($data["email"])
                    ->subject($data["subject"]);
            });

            $user = User::findOrFail(Auth::user()->id);
            $user_pricing_plan = $user->user_pricing_plan;

            $pricing_plan = PricingPlan::findOrFail(1);

            if (empty($user_pricing_plan)) {
                $user_pricing_plan = new UserPricingPlan();
            }

            $user_pricing_plan = $this->updateUserPricingPlanDetails($user_pricing_plan, $pricing_plan, $user);

            $user_pricing_plan->invoice_id = '';
            $user_pricing_plan->plan_status = 1;
            $user_pricing_plan->save();

            HomeHelper::InsertIntoUserMembershipHistory($pricing_plan, Auth::user()->id);

        } catch (JWTException $exception) {
            $this->serverstatuscode = "0";
            $this->serverstatusdes = $exception->getMessage();
        }

        if (Mail::failures()) {
            $this->statusdesc  =   "Error sending mail";
            $this->statuscode  =   "0";
        } else {
            $this->statusdesc  =   "Message sent Succesfully";
            $this->statuscode  =   "1";
        }

        if(isset(Auth::user()->business_short)){
            $business_name = Auth::user()->business_short;
        } else {
            $business_name = Auth::user()->business_name;
        }

        $updateProfileMessageData = array(
            'name' => Auth::user()->name,
            'business_name' => $business_name,
            'pricing_plan' => $pricing_plan->name,
        );

        Notification::route('mail', Auth::user()->email)->notify(new UpdateProfileEmail($updateProfileMessageData));

            $email= Auth::user()->email;
            $name = Auth::user()->name;
            //$message= 'Your Corporate Plan Request Successfully Received';

         try{
                SendMail::send('front.emails.corporate-plan-acknowledge', [
                    'name' => $name
                ], function($message) use ($email) {
                    $message->to($email)
                    ->subject("Corporate Membership Enquiry");
                });

            }catch(JWTException $exception){
                $this->serverstatuscode = "0";
                $this->serverstatusdes = $exception->getMessage();
            }

        if ($this->statuscode == 1) {

            $admin_redirect_url = 'admin';
            if (isset($request->credit_report_type) && !empty($request->credit_report_type)) {

                    if($request->credit_report_type == 2){
                        // india b2b credit report url here
                        $admin_redirect_url = route('admin.credit-report');
                    } else if($request->credit_report_type == 3){
                        // us b2b credit report url here
                        $admin_redirect_url = route('us-creditreport');
                    } else {
                        // individual credit report url here
                        $admin_redirect_url = route('admin.credit-report');
                    }
                }

            return redirect(url($admin_redirect_url))->with(['message' => 'Corporate Plan Membership Request made successful', 'alert-type' => 'success']);
        } else {

            return redirect(url('get-pricing-plan/'))->with(['message' => 'Something went wrong', 'alert-type' => 'error']);
        }
    }

    public function upgrade_corporate_plan(Request $request)
    {
        $data["email"] = config('custom_configs.cc_emails.support_mail1');
        $data["client_name"] = Auth::user()->name;
        $data["subject"] = 'Corporate membership enquiry';
        $comments = $request->comments;

        try {
            Mail::send('admin.membership_invoice.upgrade_corporate_plan', ['user' => Auth::user(), 'comments' => $comments], function ($message) use ($data) {
                $message->to($data["email"])
                    ->subject($data["subject"]);
            });

            if ($request->has('upgrade') && $request->upgrade == 1) {

                Log::debug('upgrade_plan request');
                $user = User::findOrFail(Auth::user()->id);
                $user_pricing_plan = $user->user_pricing_plan;

                // $pricing_plan_id = 1;
                // $pricing_plan = PricingPlan::findOrFail($pricing_plan_id);

                if (empty($user_pricing_plan)) {
                    $user_pricing_plan = new UserPricingPlan();
                }

                $user_pricing_plan->pricing_plan_id = 0;

                // $user_pricing_plan = $this->updateUserPricingPlanDetails($user_pricing_plan, $pricing_plan, $user);
                $user_pricing_plan->membership_payment_id = 0;
                $user_pricing_plan->invoice_id = '';
                $user_pricing_plan->save();
            }

        } catch (JWTException $exception) {
            $this->serverstatuscode = "0";
            $this->serverstatusdes = $exception->getMessage();
        }

        if (Mail::failures()) {
            $this->statusdesc  =   "Error sending mail";
            $this->statuscode  =   "0";
        } else {
            $this->statusdesc  =   "Message sent Succesfully";
            $this->statuscode  =   "1";
        }

        $this->statuscode = 1;
        if ($this->statuscode == 1) {
            return redirect(url('membership'))->with(['message' => 'Thank you for opting for an upgrade. Our executive will get in touch with you shortly.', 'alert-type' => 'success']);
        } else {
            return redirect(url('upgrade-plan/'))->with(['message' => 'Something went wrong', 'alert-type' => 'error']);
        }
    }
     public function contact_email(Request $request)
    {

            $email = $request->email;
            $name = $request->name;
            //$message= 'Your Corporate Plan Request Successfully Received';

         try{
                SendMail::send('front.emails.contactform-acknowledge', [
                    'name' => $name
                ], function($message) use ($email) {
                    $message->to($email)
                    ->subject("Contact us Enquiry");
                });

            }catch(JWTException $exception){
                $this->serverstatuscode = "0";
                $this->serverstatusdes = $exception->getMessage();
            }
        }

    function user_update(Request $request)
    {
        if (!$request->has('user_id')) {
            return 'fail';
        }

        $user = User::findOrFail($request->user_id);
        $user_email = User::where('email', 'like', General::encrypt($request->email))->first();

        General::add_to_debug_log($user->id, "Initiated User update.");

        if (!empty($user_email) && $user_email->id != $user->id) {
            $result['status'] = 'fail';
            $result['message'] = 'Email user already exists, Please change different email';
            General::add_to_debug_log($user->id, $result['message']);
            return $result;
        }

        if (empty($user)) {
            $result['status'] = 'fail';
            $result['message'] = 'Something Went wrong';
            General::add_to_debug_log($user->id, $result['message']);
        } else {
            $user->state_id = $request->state_id;
            $user->gstin_udise = $request->gstin_udise;
            $user->email = $request->email;
            $user->save();
            $result['status'] = 'success';
            $result['message'] = 'Succesfully Updated';

            General::add_to_debug_log($user->id, $result['message']);
        }
        return $result;
    }


    public function update_profile(Request $request, $planId="", $refferralCode="") {

       $countriePhonecodes = Country::select('phonecode','name')
                            ->where('phonecode','!=','0')
                            ->groupBy('phonecode')
                            ->orderBy('phonecode')
                            ->get();

       $countries = Country::where('name','LIKE','india')->orderBy('name')->get();
       $states = State::where('country_id',101)->get();

       $stateIds = $stateIdNames = $allStates = [];
       foreach ($states as $state){
           $stateIds[] =$state->id;
           $stateIdNames[$state->id] = $state->name;
           $allStates[$state->id] = $state->name;
       }

       $stateIdNames = array_flip($stateIdNames);
       $cities = City::whereIn('state_id',$stateIds)->get();
       $userTypes = UserType::where('status',1)->orderBy('name','ASC')->get();

        if(Auth::user()->role_id == 1) {
            $company_types = array("gstin"=>"GSTIN",
                              "cpan"=>"Business PAN",
                              "cin"=>"Company Identification Number",
                              "tin"=>"Tax Identification Number",
                              "udise"=>"UDISE",
                              "seln"=>"Shop and Establishment License Number");
        } else {
           $company_types = array("gstin"=>"GSTIN","cpan"=>"Business PAN");
        }

        $sectors = Sector::where('status',1)->whereNull('deleted_at')->orderBy('id','ASC')->get();

        $credit_report_type = null;
        if (isset($request->credit_report_type) && !empty($request->credit_report_type)) {
            $credit_report_type = $request->credit_report_type;
        }

       return view('admin.profile.update-profile',compact('countries','states','cities','sectors','company_types','planId','stateIdNames','allStates','userTypes','refferralCode', 'credit_report_type'));
    }

    public function update_profile_store(Request $request)
    {
        $user = new User();
        $requestAll = $request->all();
        $company_types = array("gstin", "cpan", "cin", "tin", "udise", "seln");
        $get_dynamic_company_type = "";
        $plan_id = $request->input('plan_id');

        foreach ($company_types as $key => $val) {
            if (in_array($val, $requestAll)) {
                $get_dynamic_company_type = $requestAll[$val];
            }
        }

        $type_of_business = NULL;
        if ($request->has('type_of_business')) {
            $type_of_business = General::encrypt($request->input('type_of_business'));
        }

        $type_of_sector = NULL;
        if ($request->has('type_of_sector')) {
            $type_of_sector = General::encrypt($request->input('type_of_sector'));
        }

        $authId = Auth::id();
        $email_max_character= General::maxlength('email');
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:'.$email_max_character,
            'password' => 'required|confirmed|min:5',
            'business_name' => 'required',
            'sector_id' => 'required',
            'user_type' => 'required',
            'state' => 'required',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }


        $password = $request->input('password') ? Hash::make($request->input('password')) : NULL;
        $address = $request->input('address') ? General::encrypt($request->input('address')) : NULL;
        $business_short = $request->input('business_short') ? General::encrypt($request->input('business_short')) : NULL;
        $msme=$request->input('msme') ? General::encrypt($request->input('msme')) : NULL;
        $compnay_turnover =$request->input('company_turnover') ? General::encrypt($request->input('company_turnover')) : NULL;
        $company_engaged =$request->input('company_engaged') ? General::encrypt($request->input('company_engaged')) : NULL;
        $updateProfile = [
            'email' => General::encrypt(strtolower($request->input('email'))),
            'business_name' => General::encrypt(strtoupper($request->input('business_name'))),
            'state_id' => $request->input('state'),
            'city_id' => $request->input('city'),
            'pincode' => $request->input('pincode'),
            'user_type' => $request->input('user_type'),
            'sector_id' => $request->input('sector_id'),
            'type_of_business' => $type_of_business,
            'type_of_sector' => $type_of_sector,
            'address' => $address,
            'gstin_udise' => General::encrypt(strtoupper($get_dynamic_company_type)),
            'company_type' => $request->input('company_id'),
            'business_short' => $business_short,
            'profile_verified_at' => Carbon::now(),
            'is_company_msme'=>$msme,
            'company_turnover'=>$compnay_turnover,
            'company_engaged'=>$company_engaged,
            'legal_business_name' => General::encrypt(strtoupper($request->input('legal_business_name'))),

        ];

        General::add_to_debug_log(Auth::user()->id, "Initiated update user profile.");
        User::where('id', Auth::user()->id)->update($updateProfile);

        if (Auth::user()->password == "") {
            User::where('id', Auth::user()->id)->update(['password' => $password]);
        }

        if(isset($business_short)){
            $business_name = $business_short;
        } else {
            $business_name = $request->input('business_name');
        }

        $updateProfileMessageData = array(
            'name' => Auth::user()->name,
            'business_name' => $business_name,
            'pricing_plan' => 'BASIC'
        );

        $userpricing_plan = UserPricingPlan::where('user_id', Auth::user()->id)->first();

        if (empty($userpricing_plan) && Auth::user()->profile_verified_at == "" && $plan_id == 1) {
            Notification::route('mail', $request->input('email'))->notify(new UpdateProfileEmail($updateProfileMessageData));
        }

        General::add_to_debug_log(Auth::user()->id, "Profile Updated Succesfully.");

        if ($plan_id == 1) {
            return redirect(url('register-pricing-plan?pricing_plan_id=1'))->with(['message' => 'Profile Updated Succesfully', 'alert-type' => 'success']);
        } else if ($plan_id == 2 || $plan_id == 3 || $plan_id == 5) {
            return redirect(url("get-pricing-plan/$plan_id"))->with(['message' => 'Profile Updated Succesfully', 'alert-type' => 'success']);
        } else {

            $redirect_admin_url = 'admin';

            if ($request->has('credit_report_type') && !empty($request->input('credit_report_type'))) {
                $redirect_admin_url = 'admin?credit_report_type='.$request->input('credit_report_type');
            }

            return redirect(url($redirect_admin_url))->with(['message' => 'Profile Updated Succesfully', 'alert-type' => 'success']);
        }
    }
    public function checkEmailExists(Request $request)
    {
        //echo Auth::user()->email."-----".Auth::user()->profile_verified_at; die;
        //if($request->planid!="" && $request->emailverified=="" && $request->sentverifymail=="") {
        if ($request->planid != "" && $request->emailverified == "" && Auth::user()->email_sent_at == "") {
            $user = User::where('email', General::encrypt(strtolower($request->email)))->first();
            if (!empty($user)) {
                echo 'false';
            } else {
                echo 'true';
            }
        } else {
            echo 'true';
        }
    }

    public function emailVerifyMailOld(Request $request)
    {
        $verificationController = new VerificationController();
        if (Auth::user()->email_sent_at == "" && Auth::user()->profile_verified_at == "") {
            $userCheck = User::where('email', General::encrypt($request->email))->whereNull('email_sent_at')->first();
            if (empty($userCheck)) {
                $user = Auth::user();
                $user->email = $request->email;
                $user->update();
                Auth::user()->sendEmailVerificationNotification();
                Auth::user()->markEmailAsSent();
                echo 'true';
            } else if ($request->sentverifymail == "1") {
                echo 'true';
            } else {
                echo 'false';
            }
        } else {
            echo 'false';
        }
    }


    public function emailVerifyMail(Request $request)
    {
        $verificationController = new VerificationController();
        if (Auth::user()->email_sent_at == "") {
            $user = Auth::user();
            $user->email = $request->email;
            $user->update();
            Auth::user()->sendEmailVerificationNotification();
            Auth::user()->markEmailAsSent();
            echo 'true';
        }
    }

    public function membership()
    {
        $user_plan_details = HomeHelper::getFormattedUserPricingPlanDetails();
        $remaining_free_customer_limit = General::getFreeCustomersDuesLimit(Auth::user()->id);

        if ($remaining_free_customer_limit <= 0) {
            $remaining_free_customer_limit = 0;
        }

        $user_plan_details['remaining_free_customer_limit'] = $remaining_free_customer_limit;

        return view('admin.membership_invoice.membership.membership', ['user_plan_details' => $user_plan_details]);
    }

    public function downloadInvoiceByMembershipPaymentId($membership_payment_id){

        $membership_payment = MembershipPayment::where('id', $membership_payment_id)->first();

        if (empty($membership_payment) || is_null($membership_payment) || $membership_payment->status !=4) {
            return redirect(url('admin'))->with(['message' => 'Invoice Not Found', 'alert-type' => 'error']);
        }

        $dateTime = date('d-m-Y H:i', strtotime($membership_payment->updated_at));

        $pricing_plan_data = PricingPlan::find($membership_payment->pricing_plan_id);

        $membership_plan_name = $pricing_plan_data->name;
        $membership_plan_gst_percentage = $pricing_plan_data->consent_recordent_report_gst;

        $pdf = PDF::loadView('admin.membership_invoice.report.table', [
                'membership_payment' => $membership_payment,
                'dateTime' => $dateTime,
                'membership_plan_name' => $membership_plan_name,
                'membership_plan_gst_percentage' => $membership_plan_gst_percentage,
            ])->setPaper('a4', 'portrait');

        $fileName = $membership_payment->invoice_id . '.pdf';

        return $pdf->download('Recordent-' . $fileName);
    }

    /*
    * Show available pricing plans to upgrade
    */
    public function upgrade_plan()
    {
        return view('admin.membership_invoice.membership.upgrade_plan');
    }

    public function renew_plan()
    {
        return view('admin.membership_invoice.membership.upgrade_plan');
    }

    public function userPrepaidPostpaid(Request $request)
    {
        $user = User::findOrFail($request->id);
        $field = $request->field;
        if (!empty($user)) {
            $user->$field = $request->value;
            $user->save();
            return "success";
        } else {
            return "error";
        }
    }


    public function postpaid_invoice($id)
    {
        $membership_payment = MembershipPayment::where('id', $id)->first();
        // return json_encode($membership_payment);
        if (empty($membership_payment) || is_null($membership_payment)) {
            return redirect(url('admin'))->with(['message' => 'Invoice Not Found', 'alert-type' => 'error']);
        }
        $dateTime = date('d-m-Y H:i', strtotime($membership_payment->updated_at));
        return view('admin.membership_invoice.postpaid_invoice', compact('membership_payment', 'dateTime'));
    }

    public function postpaid_invoice_sendmail($id)
    {
        $membership_payment = MembershipPayment::findOrFail($id);
        $data["email"] = (is_null($membership_payment->user->email) || $membership_payment->user->email == '') ? 'contactus@recordent.com' : $membership_payment->user->email;
        $data["client_name"] = $membership_payment->user->name;
        $data["subject"] = 'Invoice for ' . $membership_payment->particular;

        $dateTime = date('d-m-Y H:i', strtotime($membership_payment->updated_at));


        $pdf = PDF::loadView('admin.membership_invoice.postpaid_invoice', ['membership_payment'=>$membership_payment,'dateTime'=>$dateTime])->setPaper('a4','portrait');


        try{
            Mail::send('admin.membership_invoice.postpaid_invoice_mail', ['membership_payment'=>$membership_payment,'dateTime'=>$dateTime], function($message)use($data,$pdf) {
            $message->to($data["email"], $data["client_name"])
            ->subject($data["subject"])
            ->cc([config('custom_configs.cc_emails.support_mail1'),config('custom_configs.cc_emails.support_mail2')])
            ->attachData($pdf->output(), "invoice.pdf");

            });
        } catch (JWTException $exception) {
            $this->serverstatuscode = "0";
            $this->serverstatusdes = $exception->getMessage();
        }
        if (Mail::failures()) {
            $this->statusdesc  =   "Error sending mail";
            $this->statuscode  =   "0";
        } else {

            $this->statusdesc  =   "Message sent Succesfully";
            $this->statuscode  =   "1";
        }
        return response()->json(compact('this'));
    }

    public function getAllRecords()
    {

      /*$undeliveremails = array("987760941@gmail.com",
"accounts.photoexpress@photoexp",
"acnts@shaadikibiryani.com",
"admin@liveintech.in",
"amfouzial77@gmail.com",
"amit.goyal5197@gmail.com",
"anilsharma001375@gmail.com",
"artiexportsjpr@gmail.com",
"asanthoshkumar1611970@gmail.co",
"asdfg@gmail.com",
"averxdurgs@gmail.com",
"azeemhypermarket@gmail.com",
"b@gmail.com",
"bandisanjay@gmail.com",
"bhagwaniam.ckm9@gmail.com",
"bharatbushan.more@jssl.in",
"bigbazarsuraj@gmail.com",
"chattarpurfarms@17india.com",
"contact@sazerac.in",
"crm@shubh-nivesh.com",
"dharajchoudhary12@gmail.com",
"dilip@orofit.com",
"dinesh_sharma@supreme.co.in",
"dipakp332@gmail.com.mail",
"director@mskpsolar.com",
"divine_engg@sify.com",
"edificinfra15@gmail.com",
"ekkagencies@gmail.com",
"emkesupermarket@gmail.com",
"gandharvm@samarlifestyle.in",
"gauravbahirvani@rsseven.com",
"himanshu@newageapparels.com",
"hrmkt@nestorpharmaceuticals.co",
"igc@gmail.com",
"info@agsepl.com",
"info@hmsinfr.com",
"info@karmaclothing.in",
"info@kigtranding.in",
"info@vinaypolyfilms.com",
"jagdambaelectrics01@gmail.com",
"jainambayelectrical@gmail.com",
"jamjoomnlbt@gmail.com",
"janthan.bvrm@gmail.com",
"jijeesgjh@gmail.com",
"johirn194@gmail.com",
"kamalprjapath82@gmail.com",
"karneesingh01@gmail.com",
"ketanshah@infinityinterior.co",
"kpgouda25@gmail.com",
"lakshmi.pml@johnsonliftsltd.co",
"lidukcs@gmail.com",
"lucasakarui@gmail.com",
"mahendrakumar@gmail.com",
"manjeshbelli@live.cok",
"manoharsinghrathod170@gmail.com",
"massmarketingsby@gmail.com",
"matajielectricaln.s@gmail.com",
"mehdihussain19152@gmail.com",
"mh.tagir12@gmail.com",
"moyeed@matrixfire.in",
"nandienterprises4311562@gmail.com",
"narayanachinthala@gmail.com",
"navjyotdhillon428@gmail.com",
"nb602@gates.com",
"nishikant.chaturvedi@sumeruver",
"nkpjeranat@gmail.com",
"noushadactive@gmail.com",
"npc.agartala@redffmail.com",
"paiprashanthp@gmail.com",
"pgnataraj.1964@gmail.com",
"phanisharma@profusegroup.com",
"pklmayuram@gmail.com",
"prabhugm@kgiclothing.in",
"prakeshtextiles@gmail.com",
"prasenjit@tngcl.com",
"praveen@vijayautoproducts.com",
"praveenkandolkar55@gmail.com",
"praveensinghprav.607@gmail.com",
"purchase@caparo.com",
"purchase@croissancebiomed.com",
"purchasing@muvifoods.com",
"rahul.v@ivymobility.com",
"rajagencies_gkp@hotmail.com",
"rajaramvjaele@gmail.com",
"rakeshpradeshi1@gmail.com",
"ramgopal1@gmail.com",
"rasheedakkotakath@gmail.com",
"raviagarwal291@gmail.com",
"renjithnr70@gmail.com",
"rrenterprisesmandi@gmail.com",
"rsenterprisesemangalore@gmail.com",
"sagram.dule@gmail.com",
"sales@ebponline.in",
"sanjayelet17@gmail.com",
"santosh@samrudhienterprises",
"savauryrestaurant@gmail.com",
"sepanctps1@tnebnet.org",
"sethijatiu1993@gmail.com",
"shajimoideenpdm@gmail.com",
"sharp@md2.vsnl.net.in",
"shekharkhare_kraheja@gmail.com",
"shinewellmarketing@gmail.com",
"shreeganeshhardware001@gmail.com",
"shushilapharma@gmail.com",
"sidendeerkumar@gmail.com",
"skenterprises@gmail.com",
"smartbagencies@gmail.com",
"spbbpuri@gmail.com",
"sreechand@intecc.com",
"sumit@merabaazaar.com",
"sumitdadani@gmail.com",
"suneelmarwani01@gmail.com",
"surajprakash6969@gmail.com",
"swastikal418@gmail.com",
"tig_infra@gmail.com",
"tsroutlook@gmail.com",
"tushar@deltaelectrical.in",
"umeshelectricals141@gmail.com",
"unita.bansal@truebrowns",
"valsrijhmmarketing@gmail.com",
"veerajan@chalimedafeeds.com",
"vijay.s@brownhousebaking.com",
"vinaysinglal1987@gmail.com",
"vivou926@gmail.com",
"vr.ckeaning.work@gmail.com",
"vrindavanmarketing17@gmail.com",
"watwanihitesh72@gmail.com"

);*/

/*$undeliveremails = array("56ruru@gmail.com",
"admin@gstup.com",
"aejaz.shaikh1478@gmail.com",
"ajijurkid@gmail.com",
"akalyani@yukthitech",
"amanchoudhary3000@gmail.com",
"ameerbasha4050@gmail.com",
"ameerlllki@gmail.com",
"arunkumarihs@gmail.com",
"ask879106@gmail.com",
"bajwamamdeep231@gmail.com",
"be6272bs@gmail.com",
"bgfdsbg@gmail.com",
"bhimavarams0@gmail.com",
"bhoomibhalla147@gmail.com",
"bogamahkila@gmail.com",
"cali2828@gmail.com",
"ceo@fastskills.in",
"chadu277772@gmail.com",
"chsrinivas.074@gmail.com",
"chu628@gmail.co",
"dipali_patil@gmail.com",
"enoch.paul7@gmail.com",
"eureka.vijaya@gmail.com",
"faizanurrub76@gmail.com",
"farad5653@gmail.com",
"ffgg@gmail.com",
"ffmenspark@gmai.com",
"fsu79@gmail.com",
"gggg@gggg.com",
"hdfnhg@gmail.com",
"he.samglobalsolutions@gmail.com",
"hello@pureinfresh.com",
"hfrhde@gmail.com",
"hmjsgsfshs@gmail.com",
"iffuirfan830@gmail.com",
"imra627291@gmail.com",
"info@maarstechnologies",
"innocentboy100@gmail.com",
"jali7282shd@gmail.com",
"janimiya.mohammed@hyd.actcorp.in",
"jdhfsaj@gmail.com",
"jeetendershahuikey@emeil.com",
"jeiffnance@gmail.com",
"jini343add@gmail.com",
"jiv310879@gmail.com",
"jiyubhai25@gmail.com",
"joru7272@gmail.com",
"jsusyssh@gmail.com",
"kalida58@gmail.com",
"karmani@gmail.com",
"kenswa@mail.com",
"kesavareddy110968@gmail.com",
"kole61a@gmail.com",
"kssutar124@gmail.com",
"lakvarasnli@gmail.com",
"lavinikakab@gmail.com",
"lepakshisarees@gmail.com",
"loneaijaz19657@gmail.com",
"loyolakolluru@gmail.com",
"madrisanjeeva@gmail.com",
"majull68@gmail.com",
"manishvirt9136@gmail.com",
"maruthipabr123@gmail.com",
"mavillaganesh311@gmail.com",
"mdsakimmddakim42570@gmail.com",
"meripesandeeep6@gmail.com",
"mukeshambabi2380@gmail.com",
"mukkerakishorewgl@gmail.comcom",
"naganagalakshmi967@gmail.com",
"niharithc@gmail.com",
"nileshshriasta123@gmail.com",
"nirajkuma33700@gmail.com",
"nurjahar72@gmail.com",
"padmavadapalli9492431418@gmail.com",
"padnashreeskool@gmail.com",
"pchprasadpasupureddi311@gmail.com",
"penchel233@gmail.com",
"praptipatil3016@icloud.com",
"praveendtmg693@gmail.com",
"principal.ndmt@seedschools.in",
"printmail9@gmail.com",
"purchase@abgensets.co.in",
"pythannarayana@gmail.com",
"raja7272@gmail.com",
"rajebhh@gmail.com",
"rajusha28829282@gmail.com",
"rajutkaradinath34@gmail.com",
"rangabababu0991@gmail.com",
"raosrini811@email.com",
"ravikumaralamuri1@gmail.com",
"ravikumarbaghel@868.email.com",
"recordant@test.com",
"robi727@gmail.com",
"rouful1288@gmail.com",
"rouful2828@gmail.com",
"rubi6n2827@gmail.com",
"s728226@gmail.com",
"sabil2882@gmail.com",
"sabila6291@gmail.com",
"sadat57@gmail.com",
"sahiq2827@gmail.com",
"sajila728@gmail.com",
"sale28911@gmail.com",
"salil629@gmail.com",
"salm1818@gmail.com",
"shabari@gmail.com",
"shaikshoai765@gmail.com",
"shaiksrinubha@gmail.com",
"shjsa@gmail.com",
"showkatchohshowkat45@gmail.com",
"siridigitalmedi1@gmail.com",
"sivaprasad5paa@gmail.com",
"siyal72@gmail.com",
"siyam@gmail.com",
"skmahabunnisa624@gmail.com",
"springdaleschool.222@gmail.com",
"sudharrrao2000@gmail.com",
"sumon788@gmail.com",
"surendarefrigerationequipment@gmail.com",
"surya78k6@gmail.com",
"talima7282@gmail.com",
"tani182@gmail.com",
"tanvi27227@gmail.com",
"testuser@testuser.com",
"tuhituyf@gmail.com",
"tuhna@gmail.com",
"tuimira68@gmail.com",
"unhdhd2376@gmail.com",
"vaddnvthippeswamy92@gmail.com",
"vamsikriintegrity11@gmail.com",
"vasmedias@gmail.com",
"veeramanikantathondapu6028@gmail.com",
"venkateshkudurumala@gmail.com",
"venkateshrongala78@gmail.com",
"vigraj1992@gmai.com",
"vija9831447011@gmail.com",
"vijendralodha.vs.@gmail.com",
"vinay23022001@ail.com",
"vinodkumar@68182.com",
"vivou926@gmail.com",
"vklogistic.shipping@hotmail.com",
"www.ajthorasi@gmail.co",
"www.mahe116@gmail.com",
"xpose23a@gmail.com",
"xyz@gmail.com");*/

/*$undeliveremails = array("9480623491@gmail.com",
"aakashaggarwal15sept@gmail.com",
"amitabgsamal07@gmail.com",
"bharathkinght69@gmail.com",
"bps896647@gmail.com",
"bushan0068@gmail.com",
"catherine.isaac@gmail.com",
"charliechacko143@gmail.com",
"chethan1@gmail.com",
"depankarpatro482@gmail.com",
"dnelectricstores@gmail.com",
"iammeowkhan0207@icloud.com",
"jatinkalra@servicewonder.co.in",
"jeevikaentrprises.delhi@gmail.com",
"kanandozi@gmail.com",
"kilo@gmail.com",
"lokeshprajapati43@gmail.com",
"m12bharmal@gmail.com",
"mahalaxmi.distributor.99991@gmail.com",
"mhmw@vsnl.com",
"mishra31k6@gmail.com",
"munilokesh912@icloud.com",
"onkitbansal293@gmail.com",
"palashji141@gmail.com",
"plhkarad@rediffmail.com",
"pm4002510@gmail.com",
"pradeeppv581@gmail.com",
"pradishetty09@gmail.com",
"prakashenterpriseskp@gmail.com",
"praveenvhosagowdru@gmail.com",
"ragenciestrivandrum@gmail.com",
"rinanded@gmail.com",
"rishabhjain4317@gmail.com",
"s.guha@2502gmail.com",
"sahnelectricals13@gmail.com",
"sahu_purnachandra@gmail.com",
"satendeasingh.ss13@gmail.com",
"sgitmondal@rediffmail.com",
"shiva.shoam2008@gmail.com",
"singhharchan146@gmail.com",
"sreejee609@gmail.com",
"srrimangalaent@gmail.com",
"surajtripathi87@mail.com",
"swati.sahoo@byjus.com",
"vivou925@gmail.com",
"yalmanchilisyam.chanti@gmail.com",
);*/

//print_r($undeliveremailss); die;
$emails = [];
        //$getAllRecords = Businesses::orderBy('id', 'DESC')->get();
        $getAllRecords = Students::orderBy('id', 'DESC')->get();
        //$getAllRecords = User::orderBy('id', 'DESC')->get();
        $eeemails = $noemail = $eemil = [];
        /*foreach ($getAllRecords as $val) {
          $eeemails[] = strtolower($val->email);
        }*/
        foreach ($getAllRecords as $val) {
            //echo $val->business_name. "--------$val->id-----$val->email<br>";
            $business_name_upper = strtoupper($val->business_name);
            $name_upper = strtoupper($val->name);
            if(in_array(strtolower($val->email),$undeliveremails)) {
              //echo $val->id."<br/>";
              echo $val->email."<br>";
              $emails[] = $val->email;
            }
            //'business_name'=>General::encrypt($business_name_upper),
            //User::where('id',$val->id)->update(array('name'=>General::encrypt($name_upper)));
        }

        //print_r(array_diff($undeliveremails,$emails));
        /*foreach($undeliveremails as $val) { //echo $val;
          if(in_array(strtolower($val),$eeemails)) { //echo "test";
          $eemil[] = $val;
        } else {
          $noemail[] = $val;
        }
      }*/
        //dd($emails);
    }

public function getAllCustomers() {
  $getAllRecordsStudents = StudentDueFees::orderBy('id', 'DESC')->get();
  $getAllRecordsBusiness = BusinessDueFees::orderBy('id', 'DESC')->get();
  /*$getAllRecords = DB::table('student_due_fees')
       ->join('student_paid_fees', 'student_due_fees.id', '=', 'student_paid_fees.due_id')
       ->sum('student_paid_fees.paid_amount')->get();
       dd($getAllRecords);*/
  /*foreach ($getAllRecordsStudents as $val) {
    $paidfees = StudentPaidFees::where('due_id',$val->id)->sum('paid_amount');
    $balanceDue = $val->due_amount - $paidfees;
    StudentDueFees::where('id',$val->id)->update(array('balance_due'=>$balanceDue));

  }*/

  foreach ($getAllRecordsBusiness as $val) {
    $paidfees = BusinessPaidFees::where('due_id',$val->id)->sum('paid_amount');
    $balanceDue = $val->due_amount - $paidfees;
    BusinessDueFees::where('id',$val->id)->update(array('balance_due'=>$balanceDue));

  }
}
    public function invoices(Request $request)
    {
        $user = Auth::user();
        $invoice_types = InvoiceType::all();
        $limit = $request->has('limit') ? $request->limit : 10;
        if ($request->has('status')) {
            $invoices =  $user->invoices()->where(
                function ($query) use ($request) {

                    if ($request->status != 2) {
                        $query->where('postpaid', $request->status);
                    }
                    if ($request->invoice_type != 0) {
                        $query->where('invoice_type_id', $request->invoice_type);
                    }
                }
            )->latest('updated_at')->paginate($limit)->setPath('');

            $pagination = $invoices->appends(array(
                'status' => $request->input('status'),
                'invoice_type' => $request->input('invoice_type'),
                'limit' => $limit
            ));
        } else {

            $invoices =  $user->invoices()->where('postpaid', 1)->latest()->paginate($limit);
        }
        return view('admin.membership_invoice.membership.invoices')->with('invoices', $invoices)->with('invoice_types', $invoice_types);
    }
    public function multiple_invoice_download(Request $request)
    {

        // return json_encode($request->all());

        $zip = new ZipArchive;
        $path = public_path('pdf');



        if (!File::isDirectory($path)) {

            File::makeDirectory($path, 0777, true, true);
        }
        $zipfileName = 'pdf/invoices_' . strtotime(date('Y-m-d H:i:s')) . '.zip';

        if ($zip->open(public_path($zipfileName), ZipArchive::CREATE) === TRUE) {

            $invoices = $request->invoice_id;
            foreach ($invoices as $invoice) {
                $membership_payment = MembershipPayment::findOrFail($invoice);
                $dateTime = date('d-m-Y H:i', strtotime($membership_payment->updated_at));
                $pdf = PDF::loadView('admin.membership_invoice.postpaid_invoice', ['membership_payment' => $membership_payment, 'dateTime' => $dateTime])->setPaper('a4', 'portrait');

                $fileName = 'pdf/' . $membership_payment->invoice_id . ".pdf";
                $output = $pdf->output();
                file_put_contents($fileName, $output);
                try {
                    $zip->addFile($fileName, $membership_payment->invoice_id . ".pdf");
                } catch (JWTException $exception) {
                    return redirect(url('invoices'))->with(['message' => "Something went wrong", 'alert-type' => 'error']);
                }
            }
            // $membership_payment=MembershipPayment::findOrFail(68);
            // $records=array( );
            $zip->close();
        }

        return response()->download(public_path($zipfileName));
    }
    public function multiple_invoice_payment(Request $request)
    {
        $input = $request->all();
        $total = 0;
        foreach ($input['invoice_id'] as $invoice_id) {
            $membership_payment = MembershipPayment::findOrFail($invoice_id);
            $total += $membership_payment->total_collection_value;
        }
        $payment_date = date('Y-m-d');
        $tempDuePayment = TempMembershipPayment::create([
            'order_id' => Str::random(40),
            'customer_type' => 'INDIVIDUAL',
            'customer_id' => Auth::id(),
            'pricing_plan_id' => '',
            'payment_value' => $total,
            'created_at' => Carbon::now(),
            'added_by' => Auth::id(),
            'payment_note' => json_encode($input['invoice_id']),
            'payment_date' => $payment_date
        ]);
        // return json_encode($request->all());
        $userDataToPaytm = User::findOrFail(Auth::user()->id);
        $userDataToPaytm_name = preg_replace('/\s+/', '_', $userDataToPaytm->name);
        $tempDuePayment->pg_type = setting('admin.payment_gateway_type');
        $tempDuePayment->update();
        if (setting('admin.payment_gateway_type') == 'paytm') {
            $payment = PaytmWallet::with('receive');
            $payment->prepare([
                'order' => $tempDuePayment->order_id,
                'user' => $userDataToPaytm_name,
                'mobile_number' => $userDataToPaytm->mobile_number,
                'email' => $userDataToPaytm->email,
                'amount' => $total,
                'callback_url' => route('multiple-invoice-payment-callback')
            ]);


            return $payment->view('admin.payment-submit')->receive();
        } else {
            $postData = [
                'amount' => $total,
                'txnid' => $tempDuePayment->order_id,
                'phone' => $userDataToPaytm->mobile_number,
                'email' => $userDataToPaytm->email,
                'firstname' => preg_replace('/\s+/', '', $userDataToPaytm->name),
                'surl' => route('multiple-invoice-payment-callback'),
            ];
            //dd($postData);
            $payuForm = General::generatePayuForm($postData);
            return view('admin.payment-submit', compact('payuForm'));
        }
    }
    public function  multiplePaymentCallback(Request $request)
    {
        if (setting('admin.payment_gateway_type') == 'paytm') {
            $transaction = PaytmWallet::with('receive');
            try {
                $response = $transaction->response();
                //dd($response);
            } catch (\Exception $e) {
                //add to db log
                General::add_to_payment_debug_log(Auth::id());
                return redirect(url('invoices'))->with(['message' => "Something went wrong", 'alert-type' => 'error']);
            }
        } else {
            try {
                $response = General::verifyPayuPayment($request->all());
                if (!$response) {
                    return redirect(url('invoices'))->with(['message' => "Something went wrong", 'alert-type' => 'error']);
                }
            } catch (\Exception $e) {
                return redirect(url('invoices'))->with(['message' => "Something went wrong", 'alert-type' => 'error']);
            }
        }

        //dd($response);
        // return json_encode($response);
        $tempDuePayment = TempMembershipPayment::where('order_id', '=', $response['ORDERID'])
            ->first();

        if (empty($tempDuePayment)) {
            General::add_to_debug_log(Auth::id(), "Invalid due payment");
            return redirect(url('invoices'))->with(['message' => "Invalid due payment", 'alert-type' => 'error']);
        }

        if (setting('admin.payment_gateway_type') == 'paytm') {
            if ($transaction->isSuccessful()) {
                $paymentStatus = 'success';
            } else if ($transaction->isFailed()) {
                $paymentStatus = 'failed';
            } else {
                $paymentStatus = 'open';
            }
        } else {
            $paymentStatus = $response['paymentStatus'] == 'success' ? 'success' : ($response['paymentStatus'] == 'failure' ? 'failed' : 'open');
        }


        if ($paymentStatus == 'success') {
            $invoices = json_decode($tempDuePayment->payment_note);
            foreach ($invoices as $invoice) {
                $duePayment=MembershipPayment::findOrFail($invoice);
                $duePayment->transaction_id = $response['TXNID']?? $response['mihpayid'] ?? '';
                $duePayment->payment_mode = $response['PAYMENTMODE'] ?? $response['mode'] ?? '';
                $duePayment->postpaid = $response['PAYMENTMODE'] ?? $response['mode'];
                $duePayment->pg_type = setting('admin.payment_gateway_type');
                $duePayment->raw_response = json_encode($response);
                $duePayment->updated_at = Carbon::now();
                $duePayment->save();
            }
            return redirect(url('invoices'))->with(['message' => "Paid Successfully", 'alert-type' => 'success']);
        } else if ($paymentStatus == 'failed') {
            $alertType = 'error';
            $message = 'Payment failed.';
        } else {
            $alertType = 'info';
            $message = 'Payment is in progress.';
        }
        return redirect(url('invoices'))->with(['message' => $message, 'alert-type' => $alertType]);
    }

    public function upgrade_plan_due($id, $type = 'insert')
    {
        return view('admin.membership_invoice.membership.upgrade_plan', compact('id', 'type'));
    }

    public function upgrade_plan_business($id, $type = 'insert')
    {
        return view('admin.membership_invoice.membership.upgrade_plan', compact('id', 'type'));
    }


    public function edit_profile($userId) {
        //dd($userId);
        $user_profile = User::where('id', $userId)->first();
        $countriePhonecodes = Country::select('phonecode','name')->where('phonecode','!=','0')->groupBy('phonecode')->orderBy('phonecode')->get();
       $countries = Country::where('name','LIKE','india')->orderBy('name')->get();
       $states = State::where('country_id',101)->get();
       $stateIds = $stateIdNames = $allStates = [];
       foreach ($states as $state){
           $stateIds[] =$state->id;
           $stateIdNames[$state->id] = $state->name;
           $allStates[$state->id] = $state->name;
       }
       $stateIdNames = array_flip($stateIdNames);
       //echo "<pre>"; print_r($states); die;
       $cities = City::whereIn('state_id',$stateIds)->get();
       // $userTypes = UserType::with('role')->whereHas('role')->where('status',1)->orderBy('name','ASC')->get();
       $userTypes = UserType::where('status',1)->orderBy('name','ASC')->get();
       if(Auth::user()->role_id == 1) {
        $company_types = array("gstin"=>"GSTIN",
                              "cpan"=>"Business PAN",
                              "cin"=>"Company Identification Number",
                              "tin"=>"Tax Identification Number",
                              "udise"=>"UDISE",
                              "seln"=>"Shop and Establishment License Number");
       } else {
           $company_types = array("gstin"=>"GSTIN","cpan"=>"Business PAN");
       }
       $sectors = Sector::where('status',1)->whereNull('deleted_at')->orderBy('id','ASC')->get();
        if(Auth::user()->role_id!=1){
             if($userId != Auth::id()){
             return redirect(url("admin"));
              } else {
              return view('admin.profile.edit-profile',compact('countries','states','cities','sectors','company_types','stateIdNames','allStates','userTypes','userId','user_profile'));
              }
         } else {
             return view('admin.profile.edit-profile',compact('countries','states','cities','sectors','company_types','stateIdNames','allStates','userTypes','userId','user_profile'));
         }

    }

    public function edit_profile_store(Request $request, $userId)
    {
   // dd($userId);
        $user_profile = User::where('id', $userId)->first();
        $requestAll = $request->all();
        $company_types = array("gstin", "cpan", "cin", "tin", "udise", "seln");
        $get_dynamic_company_type = "";
        foreach ($company_types as $key => $val) {
            if (in_array($val, $requestAll)) {
                $get_dynamic_company_type = $requestAll[$val] ? General::encrypt(strtoupper($requestAll[$val])) : NULL;
            }
        }

        $type_of_business = NULL;
        if ($request->has('type_of_business')) {
            $type_of_business = General::encrypt($request->input('type_of_business'));
        }
        $avatar = '';
           if(!empty($request->file('avatar'))){
                $avatar = Storage::disk('public')->put('user_profiles', $request->file('avatar'));
            } else {
                $avatar = $user_profile->avatar;
            }
        //echo $get_dynamic_company_type; die;
        $authId = Auth::id();
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:100',
            'business_name' => 'required',
            'sector_id' => 'required',
            'state' => 'required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $address = $request->input('address') ? General::encrypt($request->input('address')) : NULL;
        $business_short = $request->input('business_short') ? General::encrypt($request->input('business_short')) : NULL;
        //dd($get_dynamic_company_type);
        // $message = '';
        // General::storeAdminNotificationForProfile($request,$userId,$message,$get_dynamic_company_type);

        $updateProfile = [
            'email' => General::encrypt($request->input('email')),
            'business_name' => General::encrypt(strtoupper($request->input('business_name'))),
            'state_id' => $request->input('state'),
            'city_id' => $request->input('city'),
            'pincode' => $request->input('pincode'),
            'user_type' => $request->input('sector_id'),
            'type_of_business' => $type_of_business,
            'address' => $address,
            'gstin_udise' => $get_dynamic_company_type,
            'company_type' => $request->input('company_id'),
            'business_short' => $business_short,
            'avatar' => $avatar,
            'updated_at' => Carbon::now()
        ];

        User::where('id', $userId)->update($updateProfile);
      //  dd($updateProfile);



        $updateProfileMessageData = array(
            'name' => Auth::user()->name,
            'business_name' => $request->input('business_name')
        );


            return redirect(url("admin"))->with(['message' => 'Profile Updated Succesfully', 'alert-type' => 'success']);
    }

    public function business_namevalidation(Request $request)
    {
       $status= General::businessNameCheck($request->business_name);
       if($status)
       {
            echo "true";
       }else
       {
            echo "false";
       }
    }
     public function update_bank_details(Request $request) {
        // $user = User::where('id',Auth::id())->first();
        // $account_number = General::decrypt($user->account_number);
        // $ifsc_code = General::decrypt($user->ifsc_code);
        // $account_holder_name = General::decrypt($user->account_holder_name);
        // dd($user);

       return view('admin.profile.update-bank-details');
    }
    public function update_bank_details_store(Request $request) {
         $account_number = $request->account_number;
         $ifsc_code = $request->ifsc_code;
         $account_holder_name = $request->account_holder_name;


         $path = public_path('bank_check_proof');
         if (!file_exists($path)) {
            $path='bank_check_proof/';
            Storage::disk('public')->makeDirectory($path);
        }

        $Bank_check_proof = '';
        if(!empty($request->file('bank_check_proof'))){
                $Bank_check_proof = Storage::disk('public')->put('bank_check_proof', $request->file('bank_check_proof'));
        } else {
            $Bank_check_proof = $request->old_file;
        }
         $valueforBankDetails = [
           'account_number' => General::encrypt($account_number),
           'ifsc_code' => General::encrypt($ifsc_code),
           'account_holder_name'=> General::encrypt($account_holder_name),
           'bank_check_proof'=>$Bank_check_proof
         ];
          User::where('id', Auth::id())->update($valueforBankDetails);
         // dd($user);
          return redirect(url("admin/update-bank-details"))->with(['message' => 'Bank Details Updated Succesfully', 'alert-type' => 'success']);
    }

    function EmailVerificationApicall(Request $request)
    {
        if($request->emailid)
        {
            $res=General::Email_Validation_api_call($request->emailid);
            $Response=json_decode($res)->result;

            if($Response == "deliverable")
            {
                return Response::json(['success' => true,'message'=>'','status'=>true], 200);
            }
            else if($Response == "undeliverable")
            {

                return Response::json(['error' => true,'message'=>"Your email/Domain is invalid. Enter a valid email.",'status'=>false], 200);
            }
            else
            {
                return Response::json(['error' => true,'message'=>"Please cross check your mail.",'status'=>true], 200);
            }
        }
    }

}
