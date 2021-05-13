<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
// use App\Mail\SendMail;
use App\User;
use App\Students;
use App\Businesses;
use Illuminate\Support\Facades\Notification;
use App\Notifications\HelpSupport;
use App\Notifications\Campaigns;
use Auth;
use App\UserType;
use Carbon\Carbon;
use Log;
use App\Campaign;
use General;
use Illuminate\Support\Facades\Mail as SendMail;
use App\TempCampaignEmails;
use App\TempCampaignEmailContent;
use Validator;

class SendEmailController extends Controller
{  

    function sendsupportmsg(Request $request)
    {   

        $authId = Auth::id();
        $auth = User::with(['city','state'])->where('id',$authId)->first();
        $user = User::findOrFail(Auth::user()->id);
        $usertype = UserType::find($user->user_type);
        if($auth->business_short=='')   
           {
        $data = array(

            'membername' => $user->business_name,
            'business_type' =>$usertype->name,
            'gstin_udise' => $user->gstin_udise,
            'name' => $user->name,
            'email' => $user->email,
            'mobile_number' => $user->mobile_number,
            'describe_query' =>  strtoupper($request->request->get('describe_query')),
            'query'   =>  $request->request->get('query')
        );
         
      }
      else
      {
        $data = array(

            'membername' => $user->business_short,
            'business_type' =>$usertype->name,
            'gstin_udise' => $user->gstin_udise,
            'name' => $user->name,
            'email' => $user->email,
            'mobile_number' => $user->mobile_number,
            'describe_query' =>  strtoupper($request->request->get('describe_query')),
            'query'   =>  $request->request->get('query')
        );
          
      }
    $email_for_support = config('custom_configs.email_for_support');
    
    Notification::route('mail',$email_for_support)->notify(new HelpSupport($data));

     return back()->with('success', 'Your query is successfullly submitted. We will get back to you soon.');   
    }


    function showCampaignEmailForm(Request $request)
    {
        $is_campaign_emails_exists = TempCampaignEmails::count();
        $show_send_button = true;

        if($is_campaign_emails_exists > 1){
            $show_send_button = false;
        }

        return view('front/home/campaigns', ['show_send_button' => $show_send_button]);
    }


    function sendCampaignMails(Request $request)
    {   
        $email_to = $request->get('email_to');
        $email_subject   =  trim($request->get('subject'));
        $email_content= trim($request->get('content'));

        $rule = [
            'subject' => 'required',
            'content' => 'required'
        ];

        $ruleMessage = [
            'subject.required' => 'Email Subject is required.',
            'content.required' => 'Email Content is required.',
        ];

        $validator = Validator::make($request->all(), $rule, $ruleMessage);

        if($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $temp_campaign_email_content = TempCampaignEmailContent::create([
            'email_subject' => $email_subject,
            'email_content' => $email_content
        ]);

        $temp_campaign_email_content_id = $temp_campaign_email_content->id;

        switch ($email_to) {
            case '0':
                // Only Members
                $this->insertMembersEmailCampaignData($request, $temp_campaign_email_content_id);
                break;

            case '1':
                // Individual Customers
                $this->insertIndividualCustomersEmailCampaignData($request, $temp_campaign_email_content_id);
                break;

            case '2':
                // Business Customers
                $this->insertBusinessCustomersEmailCampaignData($request, $temp_campaign_email_content_id);
                break;

            case '3':
                // All Customers (Individual + Business)
                $this->insertIndividualCustomersEmailCampaignData($request, $temp_campaign_email_content_id);
                $this->insertBusinessCustomersEmailCampaignData($request, $temp_campaign_email_content_id);
                break;

            case '4':
                // All (Members + All Customers)
                $this->insertMembersEmailCampaignData($request, $temp_campaign_email_content_id);
                $this->insertIndividualCustomersEmailCampaignData($request, $temp_campaign_email_content_id);
                $this->insertBusinessCustomersEmailCampaignData($request, $temp_campaign_email_content_id);
                break;

            case '5':
                // Custom Email ids only (comma seprated email ids from request)
                $custom_email_ids = trim($request->get('custom_email_ids'));

                $validator = Validator::make($request->all(), [
                    'custom_email_ids' => 'required'], [
                    'custom_email_ids.required' => 'Custom: To Emails - Comma seperated email ids are required.'
                ]);

                if($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }

                $array_email_ids = explode(',', $custom_email_ids);
                $array_email_ids = array_filter(array_map('trim', $array_email_ids));

                $this->insertCustomEmailIdsCampaignData($request, $array_email_ids, $temp_campaign_email_content_id);
                break;
            
            default:
                //
                break;
        }

        try {
            $send_email_to = 'recordentapp@gmail.com';
            $alert_email_subject = "Campaign Email Alert to Tech Team";

            SendMail::send('admin.emails.triggered_campaign_email_details', [
                    'email_subject' =>  $email_subject,
                    'datetime' => date('Y-m-d H:i:s'),
                    'client_ip' => $request->ip(),
                ], function($message) use ($alert_email_subject, $send_email_to) {
                    $message->to($send_email_to)
                    ->subject($alert_email_subject)
                    ->cc(['tech@recordent.com','koteshwara.rao@recordent.com', 'tarun.rumalla@recordent.com']);
            });

            Log::debug('campaign email alert sent to tech team = '.$send_email_to);
        } catch(JWTException $exception){
            $this->serverstatuscode = "0";
            $this->serverstatusdes = $exception->getMessage();
            Log::debug('exception message = '.$exception->getMessage());
        }

        return back()->with('success', 'Campaign is executed successfully.');
    }

    public function insertMembersEmailCampaignData($request, $temp_campaign_email_content_id)
    {
        $user_emails = User::distinct()
                        ->select('*')
                        ->whereNotNull('email')
                        ->where('email', '<>', '')
                        ->groupBy('email')
                        ->get();

        $this->insertEmailCampaignData($request, $user_emails, 0, $temp_campaign_email_content_id);
    }

    public function insertIndividualCustomersEmailCampaignData($request, $temp_campaign_email_content_id)
    {
        $individual_emails = Students::distinct()
                            ->select('*')
                            ->whereNotNull('email')
                            ->where('email','<>', '')
                            ->groupBy('email')
                            ->get();

        $this->insertEmailCampaignData($request, $individual_emails, 1, $temp_campaign_email_content_id);
    }
    
    public function insertBusinessCustomersEmailCampaignData($request, $temp_campaign_email_content_id)
    {
        $business_emails = Businesses::distinct()
                            ->select('*')
                            ->whereNotNull('email')
                            ->where('email', '<>', '')
                            ->groupBy('email')
                            ->get();

        $this->insertEmailCampaignData($request, $business_emails, 2, $temp_campaign_email_content_id);
    }

    public function insertEmailCampaignData($request, $user_data, $user_type=0, $temp_campaign_email_content_id)
    {
        $insert_user_emails = array();
        $email_campaign_type =  trim($request->get('promotion_type'));

        foreach ($user_data as $key => $user) {

            if ($user_type == 2) {
                $user_name = $user->company_name;
            } else if($user_type == 1) {
                $user_name = $user->person_name;
            } else {
                $user_name = $user->business_name;
            }

            $insert_user_emails[] = [
                'email' => $user->email,
                'name' => $user_name,
                'temp_campaign_email_content_id' => $temp_campaign_email_content_id,
                'user_type' => $user_type,
                'campaign_type' => $email_campaign_type
            ];
        }

        if (!empty($insert_user_emails)) {
            TempCampaignEmails::insert($insert_user_emails);
        }

        return true;
    }

    public function insertCustomEmailIdsCampaignData($request, $array_email_ids, $temp_campaign_email_content_id){

        if (!empty($array_email_ids)) {
            
            foreach ($array_email_ids as $key => $email) {
                
                $insert_user_email = array();
                
                $user = User::select('*')
                        ->where('email', '=', General::encrypt($email))
                        ->first();
                
                if ($user) {
                    $insert_user_email = [
                        'email' => $email,
                        'name' =>  $user->business_name,
                        'temp_campaign_email_content_id' => $temp_campaign_email_content_id,
                        'user_type' => 5,
                        'campaign_type' => trim($request->get('promotion_type'))
                    ];

                    TempCampaignEmails::insert($insert_user_email);
                }
            }
        }

        return true;
    }
}