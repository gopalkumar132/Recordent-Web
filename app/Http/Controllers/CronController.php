<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\StudentsImport;
use App\Exports\StudentsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Students;
use App\StudentDueFees;
use App\StudentPaidFees;
use App\Businesses;
use App\BusinessDueFees;
use App\BusinessPaidFees;
use App\AdminNotification;
use App\AdminNotificationSeen;
use App\UserPricingPlan;
use Validator;
use Response;
use Carbon\Carbon;
use DB;
use Auth;
use Storage;
use App\Dispute;
use App\Services\SmsService;
use App\Notifications\DisputeCount;
use App\Notifications\RenewMembership;
use Illuminate\Support\Facades\Notification;
use App\DuePayment;
use Config;
use General;
use App\TempDuePayment;
use App\ConsentPayment;
use PaytmWallet;
use App\MembershipPayment;
use App\TempMembershipPayment;
use App\User;
use App\PricingPlan;

use App\TempCampaignEmails;
use App\Campaign;
use Log;
use Illuminate\Support\Facades\Mail as SendMail;
use PDF;

class CronController extends Controller{
	
	public function storeNotification(Request $request) {

		$currentDateTime = Carbon::now();
		$beforeDateTime = Carbon::now()->subHours(1);

		$studentDues = StudentDueFees::select('id','added_by','student_id','created_at',DB::raw("max('created_at')"))->with('addedBy')
                        ->where('created_at','<',$currentDateTime)
                        ->whereNull('deleted_at')
                        ->where('created_at','>=',$beforeDateTime)
                        ->orderBy('created_at','desc')
                        ->groupBy('added_by')
                        ->get();

        $businessDues = BusinessDueFees::select('id','added_by','business_id','created_at',DB::raw("max('created_at')"))->with('addedBy')
                        ->where('created_at','<',$currentDateTime)
                        ->where('created_at','>=',$beforeDateTime)
                        ->whereNull('deleted_at')
                        ->groupBy('added_by')
                        ->orderBy('created_at','desc')
                        ->get();

    	//dd($studentDues);
    	if($studentDues->count()){

    		foreach($studentDues as $studentDue){

    			
                $location = '';
    			if(!empty($studentDue->addedBy->address)){
    				$location.= $studentDue->addedBy->address.', ';
    			}

    		    if(isset($studentDue->addedBy->city->name)){
                    $location.=$studentDue->addedBy->city->name.', ';
                }

                if(isset($studentDue->addedBy->state->name)){
                    $location.=$studentDue->addedBy->state->name;
                }
                
                if($studentDue->addedBy->business_short==''){
        			AdminNotification::create([
                        'title'=> $studentDue->addedBy->business_name.'('.$studentDue->addedBy->role->name.') - submitted some dues',
                        'reported_org_id'=>$studentDue->added_by,
                        'customer_type'=>'Individual',
                        'action'=>'Due Reported',
                        'reported_at'=>$studentDue->created_at,
                        'created_at'=>Carbon::now(),
                        'status'=>0,
                        'redirect_url'=>'admin/users-records?userId='.$studentDue->added_by
                    ]);

                } else {
                    AdminNotification::create([
                        'title'=> $studentDue->addedBy->business_short.'('.$studentDue->addedBy->role->name.') - submitted some dues',
                        'reported_org_id'=>$studentDue->added_by,
                        'customer_type'=>'Individual',
                        'action'=>'Due Reported',
                        'reported_at'=>$studentDue->created_at,
                        'created_at'=>Carbon::now(),
                        'status'=>0,
                        'redirect_url'=>'admin/users-records?userId='.$studentDue->added_by
                    ]);
                }
            }
        }

    	if($businessDues->count()){

    		foreach($businessDues as $businessDue){
                
                $location = '';

    			if(!empty($businessDue->addedBy->address)){
    				$location.= $businessDue->addedBy->address.', ';
    			}

    		    if(isset($businessDue->addedBy->city->name)){
                    $location.=$businessDue->addedBy->city->name.', ';
                }

                if(isset($businessDue->addedBy->state->name)){
                    $location.=$businessDue->addedBy->state->name;
                }
                if($businessDue->addedBy->business_short==''){
            		AdminNotification::create([
                            'title'=> $businessDue->addedBy->business_name.'('.$businessDue->addedBy->role->name.') - submitted some dues',
                            'reported_org_id'=>$businessDue->added_by,
                            'customer_type'=>'Business',
                            'action'=>'Due Reported',
                            'reported_at'=>$businessDue->created_at,
                            'created_at'=>Carbon::now(),
                            'status'=>0,
                            'redirect_url'=>'admin/users-business-records?userId='.$businessDue->added_by
                        ]);
                    
                } else {
                    AdminNotification::create([
                        'title'=> $businessDue->addedBy->business_short.'('.$businessDue->addedBy->role->name.') - submitted some dues',
                        'reported_org_id'=>$businessDue->added_by,
                        'customer_type'=>'Business',
                        'action'=>'Due Reported',
                        'reported_at'=>$businessDue->created_at,
                        'created_at'=>Carbon::now(),
                        'status'=>0,
                        'redirect_url'=>'admin/users-business-records?userId='.$businessDue->added_by
                    ]);
                } 
            }
        }
    	
    }

    /*
    * send dispute nofification count to admin/school on their email and mobile
    * yesterday records will be added.
    * run this cron on 00:01 AM   
    */
    public function sendDisputeNotificationCount(){
        $yesterday = Carbon::yesterday(); 
        $today = Carbon::today();
        $records = Dispute::select('id','due_added_by','created_at',DB::raw("count(*) as totalCount"))
                    ->with('dueAddedBy')
                    ->whereHas('dueAddedBy',function($q){
                        $q->where('status',1);
                    })
                    ->whereDate('created_at','>=',$yesterday)
                    ->where('created_at','<',$today)
                    ->groupBy('due_added_by')
                    ->get();
                   
        if($records->count()){
            $smsService = new SmsService();
            foreach($records as $data){

                $message = "You have received ".$data->totalCount.' dispute request yesterday ('.$yesterday->format('d/m/Y').')';
                if(!empty($data->dueAddedBy->mobile_verified_at)){
                    $smsResponse = $smsService->sendSms($data->dueAddedBy->mobile_number,$message);
                }
                if(!empty($data->dueAddedBy->email_verified_at)){
                   try{
                        Notification::route('mail', $data->dueAddedBy->email)->notify(new DisputeCount($message));
                   }catch(\Exception $e){
                   }                
                    
                }

                
            }
        } 
    }
    public function auto_renew_membership_plans(){
        // $today = Carbon::today();
        $from=date('Y-m-d 00:00:00');
        $to=date('Y-m-d 23:59:59');
        
        $user_pricing_plans_count=UserPricingPlan::whereBetween('end_date', [$from, $to])->count();
        $user_pricing_plans=UserPricingPlan::whereBetween('end_date', [$from, $to])->update(array(
            'pricing_plan_id' => 1,
            'paid_status'=>1,
            'start_date'=>date('Y-m-d H:i:s'),
            'end_date'=>date('Y-m-d H:i:s',strtotime('+1 year')),
            'membership_payment_id'=>0,
            'invoice_id'=>''
        ));
        // return json_encode($user_pricing_plans);
        $response['status']='success';
        if(($user_pricing_plans_count-$user_pricing_plans)!=0)
        $response['message']=$user_pricing_plans.' records updated successfully and '.($user_pricing_plans_count-$user_pricing_plans)." records are failed to update";
        else
            $response['message']=$user_pricing_plans.' records updated successfully';
        return json_encode($response);
    }
    public function auto_notifications_membership_plans($days){
        $from=date('Y-m-d 00:00:00',strtotime('+'.$days.' days'));
        $to=date('Y-m-d 23:59:59',strtotime('+'.$days.' days'));        
        $user_pricing_plans=UserPricingPlan::where('pricing_plan_id','!=',1)->whereBetween('end_date', [$from, $to])->get();    
            if($user_pricing_plans->count()){
                $smsService = new SmsService();
                foreach($user_pricing_plans as $data){

                    $mail_message = setting('admin.renewal_mail_message');
                    $sms_message=setting('admin.renewal_sms_message');

                    $mail_message= str_replace('<DD/MM/YYYY>', date('d/m/y',strtotime( $data->end_date)), $mail_message);
                    $mail_message= str_replace('<NAME>', $data->user->name, $mail_message);
                    $mail_message= str_replace('<BUSINESSNAME>', $data->user->business_name, $mail_message);
                    $mail_message= str_replace('<CLICKHERE>',"<a href='".route('renew-plan')."/?plan_id=".$data->pricing_plan_id."' target='_blank'>Click here</a>", $mail_message);

                    $sms_message= str_replace('<DD/MM/YYYY>', date('d/m/y',strtotime( $data->end_date)), $sms_message);
                    $sms_message= str_replace('<NAME>', $data->user->name, $sms_message);
                    $sms_message= str_replace('<BUSINESSNAME>', $data->user->business_name, $sms_message);
                    $sms_message= str_replace('<CLICKHERE>','Click here '.route('renew-plan').'/?plan_id= '.$data->pricing_plan_id, $sms_message);
                    $sms_message=str_replace('<DD/MM/YYYY>', date('d/m/y',strtotime( $data->end_date)), $sms_message);

                    if(!empty($data->user->mobile_verified_at)){
                        $smsResponse = $smsService->sendSms($data->user->mobile_number,$sms_message);
                    }
                    if(!empty($data->user->email_verified_at)){
                       try{
                            Notification::route('mail', $data->user->email)->notify(new RenewMembership($mail_message,$data));
                       }catch(\Exception $e){
                        return json_encode($e);
                       }                
                        
                    }

                    
                }
                return json_encode($user_pricing_plans);
        }
        return json_encode($user_pricing_plans);
    }
	
	public function verifyIndiaPayment(Request $request){
        /*Config(["database.default"=>'mysql-global']); 
        DB::purge();
        */
        //dd(setting('admin.payment_gateway_type'));
        
        $dateTimeBeforeMinute = Carbon::now()->subMinutes(20);
        $duePayments = DuePayment::whereIn('status',[1,2])->where('created_at','<=',$dateTimeBeforeMinute)->whereNotNull('pg_type')->orderBy('id','DESC')->get();
        $duePayments = $duePayments->filter(function($key,$value){
            
            if($key->customer_type=='INDIVIDUAL'){
                $dueType='individualDue';
                $paidType = 'individualPaid';
            }else{
                $dueType='businessDue';
                $paidType = 'businessPaid';
            }
            if(!$key->$dueType){
                return false;
            }
            return true;
        });
        
        if($duePayments->count()){
            foreach ($duePayments as $duePayment) {
                if($duePayment->pg_type=='payu'){
                    $response = General::reVerifyPayuPayment($duePayment->order_id);
                    if(!$response) continue;
                   
                }elseif($duePayment->pg_type=='paytm'){
                    try{
                        $transaction = PaytmWallet::with('status');
                        $transaction->prepare(['order' => $duePayment->order_id]);
                        $transaction->check();
                        $response = $transaction->response();
                        $response['paymentStatus'] = $transaction->isSuccessful() ? 'success' : ($transaction->isFailed() ? 'failure' : 'open');
                    }catch(\Exception $e){ continue;}    
                }else{
                    //for any other payment gateway. (For now we are skiping whole record)
                    continue;
                }
                $paymentStatus = $response['paymentStatus']=='success' ? 'success': ($response['paymentStatus']=='failure' ? 'failed' : 'open');
               
                $duePayment->raw_response = json_encode($response);
                $duePayment->updated_at = Carbon::now();
                

                if($paymentStatus=='success'){
                    $duePayment->status =4;
                    $duePayment->transaction_id = $response['TXNID'] ?? $response['mihpayid'];               
                    
                }elseif($paymentStatus=='failed'){
                    $duePayment->status =5;
                }else{
                    $duePayment->status =2;
                }
                if($duePayment->payment_done_by=='ADMIN_MEMBER' && ($paymentStatus=='success' || $paymentStatus=='failed')){
                    $tempDuePayment = TempDuePayment::where('order_id','=',$duePayment->order_id)->first();
                    if(empty($tempDuePayment)){
                        continue;
                    }
                }

                if($paymentStatus=='success'){
                    $duePaid = [
                        'due_id'=>$duePayment->due_id,
                        'paid_date'=>Carbon::now(),
                        'paid_amount'=>$duePayment->payment_value,
                        'created_at'=>Carbon::now(),
                        'added_by'=>$duePayment->added_by,
                    ];
                    //add below fields only if payment done by customer .
                    if($duePayment->payment_done_by=='CUSTOMER'){
                         $duePaid['payment_done_by_id']=$duePayment->added_by;
                         $duePaid['payment_done_by']='CUSTOMER';
                    }
                    if($duePayment->payment_done_by=='ADMIN_MEMBER'){
                        $duePaid['paid_note']=$tempDuePayment->payment_note;
                        $duePaid['paid_date']=$tempDuePayment->payment_date;
                        $duePaid['paid_amount']=$tempDuePayment->payment_value;
                    }
                    
                    if($duePayment->customer_type=='INDIVIDUAL'){
                        $duePaid['student_id']=$duePayment->customer_id;
                        $duePaid = StudentDueFees::create($duePaid);
                        
                    }elseif($duePayment->customer_type=='BUSINESS'){
                        $duePaid['business_id']=$duePayment->customer_id;
                        $duePaid = BusinessPaidFees::create($duePaid);
                    }
                    $duePayment->paid_id = $duePaid->id;
                }

                if($duePayment->payment_done_by=='ADMIN_MEMBER' && ($paymentStatus=='success' || $paymentStatus=='failed')){
                    $tempDuePayment->delete();
                }    
                $duePayment->update();

            }
        }

        //consent payment
        $dateTimeBeforeMinute = Carbon::now()->subMinutes(20);
        $consentPayment = ConsentPayment::whereIn('status',[1,2])->where('created_at','<=',$dateTimeBeforeMinute)->whereNotNull('pg_type')->orderBy('id','DESC')->get();
        if($consentPayment->count()){
            foreach ($consentPayment as $consentPay) {
                if($consentPay->pg_type=='payu'){
                    $response = General::reVerifyPayuPayment($consentPay->order_id);
                    if(!$response) continue;
                   
                }elseif($consentPay->pg_type=='paytm'){
                    try{
                        $transaction = PaytmWallet::with('status');
                        $transaction->prepare(['order' => $consentPay->order_id]);
                        $transaction->check();
                        $response = $transaction->response();
                        $response['paymentStatus'] = $transaction->isSuccessful() ? 'success' : ($transaction->isFailed() ? 'failure' : 'open');
                    }catch(\Exception $e){ continue;} 
                }else{
                    //for any other payment gateway. (For now we are skiping whole record)
                    continue;
                }
                $paymentStatus = $response['paymentStatus']=='success' ? 'success': ($response['paymentStatus']=='failure' ? 'failed' : 'open');
               
                $consentPay->raw_response = json_encode($response);
                $consentPay->updated_at = Carbon::now();
                if($paymentStatus=='success'){
                    $consentPay->status =4;
                    $consentPay->transaction_id = $response['TXNID'] ?? $response['mihpayid'];               
                    
                }elseif($paymentStatus=='failed'){
                    $consentPay->status =5;
                }else{
                    $consentPay->status =2;
                }
                $consentPay->update();

            }
        }  
        
        $dateTimeBeforeMinute = Carbon::now()->subMinutes(20);
        $membershipPayments = MembershipPayment::whereIn('status',[1,2])->where('created_at','<=',$dateTimeBeforeMinute)->whereNotNull('pg_type')->orderBy('id','DESC')->get(); 
        if($membershipPayments->count()){
            foreach ($membershipPayments as $membershipPayment) {
                if($membershipPayment->pg_type=='payu'){
                    $response = General::reVerifyPayuPayment($membershipPayment->order_id);
                    if(!$response) continue;
                   
                }elseif($membershipPayment->pg_type=='paytm'){
                    try{
                        $transaction = PaytmWallet::with('status');
                        $transaction->prepare(['order' => $membershipPayment->order_id]);
                        $transaction->check();
                        $response = $transaction->response();
                        $response['paymentStatus'] = $transaction->isSuccessful() ? 'success' : ($transaction->isFailed() ? 'failure' : 'open');
                    }catch(\Exception $e){ continue;} 
                }else{
                    //for any other payment gateway. (For now we are skiping whole record)
                    continue;
                }

                $tempMembershipPayment = TempMembershipPayment::where('order_id','=',$membershipPayment->order_id)->first();
                if(empty($tempMembershipPayment)){
                    continue;
                }
                $paymentStatus = $response['paymentStatus']=='success' ? 'success': ($response['paymentStatus']=='failure' ? 'failed' : 'open');
               
                $membershipPayment->raw_response = json_encode($response);
                $membershipPayment->updated_at = Carbon::now();
                if($paymentStatus=='success'){
                    $membershipPayment->status =4;
                    $membershipPayment->transaction_id = $response['TXNID'] ?? $response['mihpayid'];
                    $message='Your subscription is successful';               
                    
                }elseif($paymentStatus=='failed'){
                    $membershipPayment->status =5;
                    $message='Payment failed.'; 
                }else{
                    $membershipPayment->status =2;
                    $message='Payment is in progress.';
                }
                
                DB::beginTransaction();
                try{
                    $membershipPayment->update();
                    if($membershipPayment->status==4){// successful payment
                        $user=User::findOrFail($membershipPayment->customer_id);
                        $user_pricing_plan=$user->user_pricing_plan;
                        if(empty($user_pricing_plan)){
                            $user_pricing_plan=new UserPricingPlan();
                        }
                        $invoice_no=MembershipPayment::where('created_at','>=',date('Y-m-d 00:00:00'))->where('status',4)->count();
                        $invoice_no=$invoice_no==1?$invoice_no:$invoice_no+1;
                        $pricing_plan=PricingPlan::findOrFail($membershipPayment->pricing_plan_id);
                        $user_pricing_plan->pricing_plan_id=$membershipPayment->pricing_plan_id;
                        $user_pricing_plan->user_id=$membershipPayment->customer_id;
                        $user_pricing_plan->paid_status=1;
                        $user_pricing_plan->start_date=date('Y-m-d H:i:s');
                        $user_pricing_plan->end_date=date('Y-m-d H:i:s',strtotime('+1 year'));                
                        $user_pricing_plan->save();
                        $user_pricing_plan->invoice_id=date('dmY').sprintf('%07d',$invoice_no);
                        $user_pricing_plan->membership_payment_id=$membershipPayment->id;
                        $membershipPayment->invoice_id=$user_pricing_plan->invoice_id;
                        $membershipPayment->user_pricing_plan_id=$user_pricing_plan->id;
                        $user_pricing_plan->save();
                        $membershipPayment->update();
                        $message=$message;
                        $response=$this->sendMembershipMail($membershipPayment);
                    }

                      
                    if($membershipPayment->status==4 || $membershipPayment->status==5){
                        $tempMembershipPayment->delete();
                    }
                    
                    DB::commit();
                }catch(\Exception $e){
                    DB::rollback();
                }

            }

        }
    }


    public function sendMembershipMail($membership_payment){
        $id = $membership_payment->id;
        $user=User::findOrFail($membership_payment->customer_id);

        $data["email"]=empty($user->email) ? 'contactus@recordent.com':$user->email;
        $data["client_name"]=$user->name;
        $data["subject"]='Recordent invoice for '.$membership_payment->pricing_plan->name.' plan.';
        // return $membership_payment;
        $records=array( );
        // $reportNumber='hjhjhjh';
        $dateTime = date('d-m-Y H:i',strtotime($membership_payment->updated_at));
        // return view('admin.membership_invoice.report.table',compact('membership_payment','dateTime'));
        $pdf = PDF::loadView('admin.membership_invoice.report.table', ['membership_payment'=>$membership_payment,'dateTime'=>$dateTime])->setPaper('a4','portrait');

        try{
            SendMail::send('admin.membership_invoice.membership_payment_invoice', ['membership_payment'=>$membership_payment,'dateTime'=>$dateTime], function($message)use($data,$pdf) {
            $message->to($data["email"], $data["client_name"])
            ->subject($data["subject"])
            ->attachData($pdf->output(), "invoice.pdf");
            });
        }catch(JWTException $exception){
            $this->serverstatuscode = "0";
            $this->serverstatusdes = $exception->getMessage();
        }
        if (SendMail::failures()) {
             $this->statusdesc  =   "Error sending mail";
             $this->statuscode  =   "0";

        }else{

           $this->statusdesc  =   "Message sent Succesfully";
           $this->statuscode  =   "1";
        }
        return response()->json(compact('this'));
    }


    /*
    * send campaign emails to Members, Individual and Business customers
    * send emails per hour based on custom configuration limit
    * user_type - 0-User, 1-Individual, 2-Business    
    * run this cron every One Hour
    */
    public function cronSendCampaignEmails(){
        
        Log::Debug('cron execution started successfully.');

        $env_type = config('app.env');
        // Log::debug("env_type = ".$env_type);

        $email_limit_per_hour = config('custom_configs.email_campaign_limit_per_hour');
        // Log::debug("email_campaign_limit_per_hour = ".$email_limit_per_hour);

        $campaign_emails = TempCampaignEmails::limit($email_limit_per_hour)->get();

        $campaign_emails_count = count($campaign_emails);

        foreach ($campaign_emails as $key => $campaign_email) {
            
            $name = $campaign_email->name;
            $user_type = $campaign_email->user_type;
            $promotion_type = $campaign_email->campaign_type;

            $email_subject = $campaign_email->campaign->email_subject;
            $content = $campaign_email->campaign->email_content;

            $app_url = config('app.url');

            if ($user_type == 1) {
                $check_my_report_url = $app_url.'check-my-report';
            } else {
                $check_my_report_url = $app_url.'check-my-business-report';
            }

            if ($env_type == "production") {
                $email = $campaign_email->email;
            } else {

                if ($user_type == 0) {
                    $email = "sandeepdeveloper2021@gmail.com";
                } else if($user_type == 1){
                    $email = "koteshwara.rao@recordent.com";
                } else {
                    $email = "sandeepdeveloper2021@gmail.com";
                }
            }
        
            try{
                SendMail::send('admin.emails.campaignstemplate', [
                      'content' =>  $content,
                      'name' => $name,
                      'check_my_report_url' => $check_my_report_url
                ], function($message) use ($email_subject, $email) {
                    $message->to($email)
                    ->subject($email_subject);
                });

                $campaign_data = [
                   'email' => General::encrypt($campaign_email->email),
                   'email_sent_at'=> Carbon::now(),
                   'promotion_type' => $promotion_type,
                   'user_type'=> $user_type
                ];

                Campaign::updateOrCreate(['email' => General::encrypt($campaign_email->email), 'user_type' => $user_type], $campaign_data);

                // Log::debug('user_type = '.$user_type.', email = '.print_r($campaign_email->email, true));
                
                $campaign_email->delete();

            } catch (JWTException $exception){

                // Log::debug('cron send mail execption case... email = '.$email);
                Log::debug("exception = ".print_r($exception->getMessage(), true));
            }
        }

        if ($campaign_emails_count > 0) {
            Log::debug('Campaign emails triggered successfully.');    
        } else {
            Log::debug('No Campaign emails found.');
        }

        Log::debug('cron executed successfully.');
    }

    public function sendDisputeReminder(){
        $disputes = Dispute::select('*')
                    ->with('dueAddedBy')
                    ->whereHas('dueAddedBy',function($q){
                        $q->where('status',1);
                    })
                    ->where('is_open','1')
                    ->get();
        
        if($disputes->count()){
            $smsService = new SmsService();
            foreach($disputes as $data){
               $business_name = $data->dueAddedBy->business_name; 
                if(isset($data->dueAddedBy->business_short)){
                $business_name = $data->dueAddedBy->business_short;    
           } 
               $created_at = Carbon::parse($data->created_at);
               $current_date = Carbon::now();
               $diffInDays = $created_at->diffInDays($current_date);
               if($diffInDays<=30){
                $sms_message= "Dear ". $business_name .", You have received a dispute request and it is open since 30 days. Login to ". route('home') ." resolve the dispute.";
               }
               else if($diffInDays>30){
                $sms_message =  "Dear " . $business_name. ", You have received a dispute request on " . $data->created_at->format('d/m/Y'). " Login to ". route('home') ." resolve the dispute.";
               }
               if($diffInDays <=15) {
                 $mail_message= "Dear ". $business_name .", You have received a dispute request and it is open since 15 days. Login to ". route('home') ." resolve the dispute.";
               }
               if($diffInDays >15) {
                 $mail_message =  "Dear " . $business_name. ", You have received a dispute request on " . $data->created_at->format('d/m/Y'). " Login to ". route('home') ." resolve the dispute.";
               }
               if($diffInDays%30 == 0){

                if(!empty($data->dueAddedBy->mobile_verified_at)){
                    $smsResponse = $smsService->sendSms($data->dueAddedBy->mobile_number,$sms_message);
                }
            }
            if($diffInDays%15 == 0){
            $email = $data->dueAddedBy->email;
            try{
                SendMail::send('front.emails.send-otp-to-email', [
                    'otpMessage' => $mail_message
                ], function($message) use ($email) {
                    $message->to($email)
                    ->subject("Dispute Reminder Recordent");
                });

            }catch(JWTException $exception){
                $this->serverstatuscode = "0";
                $this->serverstatusdes = $exception->getMessage();

            }      
          }
        } 
      } 
    }	

    public function sendRemindertoUsers(){

        $days = array("2", "5", "9", "15", "21", "28", "31", "45", "60", "75", "90", "120"); 
        
        foreach ($days as  $value) {
    
            $users = DB::table('users')
                        ->select('*')
                        ->whereNotExists( function ($query) {
                            $query->select(DB::raw(1))
                            ->from('students')
                            ->whereRaw('users.id = students.added_by');
                        })
                        ->whereNotExists( function ($query) {
                            $query->select(DB::raw(1))
                            ->from('businesses')
                            ->whereRaw('users.id = businesses.added_by');
                        })
                        ->whereDate('users.created_at', '=', Carbon::today()->subDays($value))
                        ->where('users.status','1')
                        ->where('users.email','!=',null)
                        ->get();
    
            foreach ($users as $data) {
                $email =General::decrypt($data->email);
                if(isset($data->business_short)){
                    $name = General::decrypt($data->business_short);
                }  else {
                    $name = General::decrypt($data->business_name);
                }
              
                $created_at = $data->created_at;
                // dd($created_at);
                if($created_at < Carbon::today()->subDays('28')){
                    $mail =  "support@recordent.com";
                    $contact = "888 6634 105";
                } else {
                   $mail =  "contact@recordent.com";
                   $contact = "888 6634 100";
                }

                try{
                    SendMail::send('admin.emails.send-reminder-to-users', [
                        'support_mail' => $mail,
                        'contact'    => $contact,
                        'name'       => $name
                    ], function($message) use ($email) {
                        $message->to($email)
                        ->subject("One step closer to start collecting payments faster");
                    });

                }catch(JWTException $exception){
                    $this->serverstatuscode = "0";
                    $this->serverstatusdes = $exception->getMessage();
                }
            }
        }
    }

    public function sendEmailforMemberServices(){

            $student_emails = DB::table('students')
            ->select('students.email','students.person_name','students.created_at')
            ->where('students.email','!=',null);
            
            $business_emails = DB::table('businesses')
            ->select('businesses.email','businesses.company_name','businesses.created_at')
            ->where('businesses.email','!=',null)
            ->union($student_emails)
            ->get();
    
            foreach ($business_emails as $data) {
                $created_at = Carbon::parse($data->created_at);
                $current_date = Carbon::now();
                $diffInDays = $created_at->diffInDays($current_date);
    
                $email =General::decrypt($data->email);
                $name = General::decrypt($data->company_name);
              
                if($diffInDays%30 == 0){
                  try{
                        SendMail::send('admin.emails.send-email-for-member-services', [
                            'name'       => $name
                        ], function($message) use ($email) {
                            $message->to($email)
                            ->subject("Did you check your report on Recordent?");
                        });

                    }catch(JWTException $exception){
                        $this->serverstatuscode = "0";
                        $this->serverstatusdes = $exception->getMessage();
                  }
            }
         }
        }    
    public function sendEmailtoEducateUsers(){

        $days = array("4", "8", "13", "21", "33", "50","70", "91"); 
        foreach ($days as  $value) {
    
            $dues = DB::table('users')
                ->select('*')
                ->whereExists( function ($query) {
                    $query->select(DB::raw(1))
                    ->from('student_due_fees')
                    ->whereRaw('users.id = student_due_fees.added_by')
                    ->havingRaw('COUNT(student_due_fees.added_by)<3');
                })
                ->whereExists( function ($query) {
                    $query->select(DB::raw(1))
                    ->from('business_due_fees')
                    ->whereRaw('users.id = business_due_fees.added_by')
                    ->havingRaw('COUNT(business_due_fees.added_by)<3');
                })
               ->whereExists( function ($query) {
                    $query->select(DB::raw(1))
                    ->from('student_due_fees')
                    ->whereRaw('users.id = student_due_fees.added_by')
                    ->join('business_due_fees','business_due_fees.added_by','=','student_due_fees.added_by')
                    ->havingRaw('COUNT(student_due_fees.added_by)+COUNT(business_due_fees.added_by)<3');
                })  
                ->where('users.status','1')
                ->where('users.email','!=',null)
                ->whereDate('users.created_at', '=', Carbon::today()->subDays($value))
                ->groupBy('users.id')
                ->get();

            foreach ($dues as $data) {
                $email =General::decrypt($data->email);
              
                if(isset($data->business_short)){
                    $name = General::decrypt($data->business_short);
                } else {
                    $name = General::decrypt($data->business_name);
                }

                $created_at = $data->created_at;
                if($created_at < Carbon::today()->subDays('33')){
                    $mail =  "support@recordent.com";
                    $contact = "888 6634 105";
                } else {
                    $mail =  "contact@recordent.com";
                    $contact = "888 6634 100";
                }
                
                try{
                    SendMail::send('admin.emails.send-email-to-educate-users', [
                        'support_mail' => $mail,
                        'contact'    => $contact,
                        'name'       => $name
                    ], function($message) use ($email) {
                        $message->to($email)
                        ->subject("Make the most out of your Recordent membership");
                    });
                }catch(JWTException $exception){
                    $this->serverstatuscode = "0";
                    $this->serverstatusdes = $exception->getMessage();
                }
            }
        }
    }

    public function checkOrUpdatePayuRefundStatus(){

        $get_refunded_data = ConsentPayment::where('status', 4)
                            ->where('refund_status', 1)
                            ->orWhere('refund_status', 2)
                            ->where('pg_type', 'payu')
                            ->get();

        Log::debug("get_refunded_data count = ".count($get_refunded_data));

        foreach ($get_refunded_data as $key => $value) {
            $raw_response = json_decode($value->raw_refund_response);

            if (!empty($raw_response)) {

                if ($get_consent_payment_data->refund_status == 2) {
                    $refund_request_params = json_decode($value->raw_refund_request);
                    $request_id = $refund_request_params->var1;
                } else {
                    $request_id = $raw_response->request_id;
                }

                $raw_output = General::check_payu_refund_status($request_id);

                $response = json_decode($raw_output);

                if (!empty($response) && $response->status == 1) {

                    if (isset($response->transaction_details->$request_id->$request_id->status)) {
                        $status = $response->transaction_details->$request_id->$request_id->status;

                       if ($status == "success" || $status == "failure" || $status == "queued") {
                            
                            if ($status == "success") {
                                $refund_status = 4;
                            } else if ($status == "failure") {
                                $refund_status = 5;
                            } else {
                                $refund_status = 2;
                            }

                            $refund_status_api_request_params = General::getUsB2bReportRefundApiRequestParams($request_id);

                            $value->raw_refund_response = $raw_output;
                            $value->refund_status = $refund_status;
                            $value->raw_refund_request = json_encode($refund_status_api_request_params);

                            $value->save();
                        }
                    }
                }
            }
        }
    }
}
