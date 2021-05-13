<?php

namespace App\Http\Controllers;

use Anand\LaravelPaytmWallet\Facades\PaytmWallet;
use App\BusinessDueFees;
use App\Businesses;
use App\City;
use App\CustomerType;
use App\Templates;
use App\Notifications;
use App\NotificationType;
use App\NotificationCustomerExclusion;
use App\Repeats;
use App\Sector;
use App\StudentDueFees;
use App\Students;
use App\User;
use App\Settings;
use Illuminate\Http\Request;
use Validator;
use Response;
use Carbon\Carbon;
use DB;
use Auth;
use Cake\Core\Configure;
use Cake\Core\Exception\Exception;
use Cake\Http\Client;
use Illuminate\Support\Facades\Log;
use Swagger\Client\Api\MessageApi;
use Swagger\Client\Configuration;
use Swagger\Client\Model\CreateMessage;
use App\Services\SmsService;

class NotificationSchedulerController extends Controller
{
    public $username;
    public $password;
    public $source;

    public function __construct()
    {
        $this->username = config('services.sms_gateway_provider.username');
        $this->password = config('services.sms_gateway_provider.password');
        $this->source = config('services.sms_gateway_provider.source');
    }

    public function index(Request $request)
    {
        // $templates = Templates::get();
        $customer_types = CustomerType::where('status', 1)->get();
        $notification_types = NotificationType::where('status', 1)->get();
        $notifications = Notifications::with(['customer', 'notification'])->orderBy('id', 'DESC')->get();
        // dd($notifications->toArray());

        $users = User::get()->toArray();
        uasort($users, function($a, $b){
            return strcmp($a['name'], $b['name']);
        });
        // dd($users);
        $sectors = Sector::get()->toArray();
        uasort($sectors, function($a, $b){
            return strcmp($a['name'], $b['name']);
        });
        return view('admin.notificationScheduler', compact(/*'templates', */'notifications', 'customer_types', 'notification_types', 'users', 'sectors'));
    }

    public function getTemplateByType() {
        $data = array('templates'=> array());
        ob_start();
        $type = /*1;//*/ request('template_type');
        if($type) {
            try {
                $notification_types = NotificationType::where('id', $type)->get()->toArray();
                if( is_array($notification_types) && array_key_exists(0, $notification_types) && array_key_exists('name', $notification_types[0]) ) {
                    $data['type'] = $typeName = $notification_types[0]['name'];
                    $data['templates'] = Templates::where('type', $typeName)->get()->toArray();
                    $data['success'] = 1;
                }
            } catch (Exception $e) {
                // echo $e->getMessage();
            } finally {
                ob_end_clean();
                echo json_encode($data);
                exit;
            }
        }
        ob_end_clean();
        echo json_encode($data);
        exit;
    }

    public function addNotification(Request $request)
    {
        // dd($request->all());
        try {
            DB::beginTransaction();
            $notification = new Notifications;
            $notification->user_id = Auth::user()->id;
            $notification->customer_type = $request['customer_type'];
            $notification->notification_date = $request['notification_date'];
            $notification->notification_start_time = $request['start_time'];
            $notification->notification_type = $request['notification_type'];
            if (isset($request['member_level'])) {
                $notification->exclusion_member_level = implode(",", $request['member_level']);
            }
            if (isset($request['bussiness_type'])) {
                $notification->exclusion_business_level = implode(",", $request['bussiness_type']);
            }

            $notification->save();

            if (isset($request['member'])) {
                foreach ($request['member'] as $key => $value) {
                    if ($value > 0) {
                        $notification_customer_exclusion = new NotificationCustomerExclusion;
                        $notification_customer_exclusion->notification_id = $notification->id;
                        $notification_customer_exclusion->member_id = $value;

                        if (isset($request['customer'])) {
                            $notification_customer_exclusion->customer_id = implode(",", $request['customer'][$key]);
                        }
                        $notification_customer_exclusion->save();
                    }
                }
            }

            // $repeats = new Repeats;
            // $repeats->notification_id = $notification->id;
            // $repeats->repeats = $request['repeats'];
            // $repeats->every_days = $request['every_days'];
            // $repeats->monthly_date = $request['monthly_date'];
            // if ($request['weekly_notification_days'] == 'every_weekday') {
            //     $repeats->weekly_notification_days = 'monday,tuesday,wednesday,thursday,friday';
            // } elseif (($request['weekly_notification_days'] == 'every_weekend')) {
            //     $repeats->weekly_notification_days = 'sunday,saturday';
            // } elseif (($request['weekly_notification_days'] == 'custom')) {
            //     $repeats->weekly_notification_days = implode(",", $request['custom_days']);
            // }
            // if ($request['ends'] == 'never') {
            //     $repeats->ends_never = '1';
            // }

            // $repeats->ends_on = $request['ends_on'];
            // $repeats->ends_after_occurrence = $request['ends_after'];
            // $repeats->save();

            DB::commit();
            return redirect('admin/notificationScheduler');
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function searchNotification(Request $request)
    {

        $notifications = Notifications::get();

        if ($request['date_from'] || $request['date_to']) {
            if ($request['date_from'] && $request['date_to'])
                $notifications = $notifications->whereBetween('notification_date', [$request['date_from'], $request['date_to']]);
            elseif ($request['date_from'])
                $notifications = $notifications->where('notification_date', '>=', $request['date_from']);
            elseif ($request['date_to'])
                $notifications = $notifications->where('notification_date', '<=', $request['date_to']);
        }
        if ($request['search_customer_type'] != '0' && $request['search_customer_type'] != 'All') {
            // dd($request['search_customer_type']);
            $notifications = $notifications->where('customer_type', $request['search_customer_type']);
        }
        if ($request['search_notification_type'] != '0' && $request['search_notification_type'] != 'All') {
            $notifications = $notifications->where('notification_type', $request['search_notification_type']);
        }

        return view('admin.notificationSchedulerTable')->with('notifications', $notifications);
    }

    public function stopNotification($id,$value)
    {
        // dd($value);
        $notifications = Notifications::where('id', $id)->first();
        // dd($notifications);
        $notifications['is_pause'] = $value;
        // if ($notifications['is_pause'] == 1) {
        //     $notifications['is_pause'] = '0';
        // } else {
        //     $notifications['is_pause'] = '1';
        // }
        $notifications->save();
        if($value == 2){
            return response()->json(['message'=>'Notification is Stop Successfully']);
        }elseif($value == 0){
            return response()->json(['message'=>'Notification is play Successfully']);
        }else{
            return response()->json(['message'=>'Notification is pause Successfully']);
        }

    }
    public function deleteNotification($id)
    {
        $notifications = Notifications::where('id', $id)->first();
        // dd($notifications);
        $notifications->delete();
        return response()->json(true);
    }

    public function customerNotification($customer_type_id = null, $member = null)
    {

        if ($member == null || $member == 0) {
            return response()->json("Data not found");
        }

        if ($customer_type_id != null) {
            $customer_type = CustomerType::where('id', $customer_type_id)->first();
        }

        if ($customer_type['name'] == 'Individual Customer') {
            if ($member != null && $member != 'Select User') {
                $student = Students::where('added_by', $member)->get()->toArray();
            } else {
                $student = Students::get()->toArray();
            }
            uasort($student, function($a, $b){
                return strcmp($a['person_name'], $b['person_name']);
            });
            return response()->json(['student' => $student]);
        } else if ($customer_type['name'] == 'Business Customer') {
            if ($member != null && $member != 'Select User') {
                $business_customer = Businesses::where('added_by', $member)->get()->toArray();
            } else {
                $business_customer = Businesses::get()->toArray();
            }
            uasort($business_customer, function($a, $b){
                return strcmp($a['company_name'], $b['company_name']);
            });
            return response()->json(['business_customer' => $business_customer]);
        } else {
            return response()->json("Data not found");
        }
    }

    public function viewExclusionsMembers($id)
    {
        $notifications = Notifications::with(['customer','customer_exclusion', 'customer_exclusion.member'])->where('id', $id)->first()->toArray();

        if ($notifications['customer_type'] == '1') {
            foreach ($notifications['customer_exclusion'] as $key => $value) {
                $notifications['customer_exclusion'][$key]['users'] = Students::whereIn('id', explode(',', $value['customer_id']))->get()->toArray();
            }
        } elseif ($notifications['customer_type'] == '2') {
            foreach ($notifications['customer_exclusion'] as $key => $value) {
                $notifications['customer_exclusion'][$key]['users'] = Businesses::whereIn('id', explode(',', $value['customer_id']))->get()->toArray();
            }
        }

        $notifications['members'] = User::whereIn('id', explode(',', $notifications['exclusion_member_level']))->get()->toArray();
        $notifications['sectors'] = Sector::whereIn('id', explode(',', $notifications['exclusion_business_level']))->get()->toArray();
        // dd($notifications);
        return view('admin.notificationViewExclusionsMembers')->with('notifications', $notifications);
    }

    public function sendsms(Request $request)
    {
        // dd("test");
        $to = "9052438412";
        $text = "test";
        $response = $this->tempSendSms($to, $text);
        //    dd($response);
        // return $response;
        $to = "+91" . $to;
        // Configure HTTP basic authorization: basicAuth
        $config = \Karix\Configuration::getDefaultConfiguration()
            ->setUsername($this->username)
            ->setPassword($this->password);

        $apiInstance = new \Karix\Api\MessageApi(
            // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
            // This is optional, `GuzzleHttp\Client` will be used as default.
            NULL,
            $config
        );

        date_default_timezone_set('UTC');
        // Create Message object
        $message = (new \Karix\Model\CreateMessage())
            ->setChannel("sms") //Or use "whatsapp"
            ->setSource($this->source)
            ->setDestination([$to])
            ->setContent(["text" => $text]);
        $response = [];
        $response['fail_to_send'] = false;
        try {

            $result = $apiInstance->sendMessage($message);
            $response['actual_status'] = $result->getObjects()[0]->getStatus();
            //failed // undelivered //rejected
            if (in_array($response['actual_status'], ['failed', 'undelivered', 'rejected'])) {
                $response['sent'] = 0;
                $response['message'] = 'failed to send sms.';
            } else {
                $response['sent'] = 1;
            }
        } catch (\Exception $e) {
            $response['fail_to_send'] = true;
            $response['sent'] = 0;
            $response['message'] = 'can not send sms right now, please try again.';
        }

        return $response;
    }

    public function notificationCron()
    {
        Log::info('-----------------------');
        Log::info('notificationCron run at: '. date('Y-m-d H:i:s'));
        Log::info('-----------------------');

        // Log::channel('cron')->info('-----------------------');
        // Log::channel('cron')->info('notificationCron run at: '. date('Y-m-d H:i:s'));
        // Log::channel('cron')->info('-----------------------');

        ini_set('max_execution_time', 0);

        $gapDays = 6;
        $settings = Settings::where('key', 'admin_notification_gapdays')->first();
        if (!empty($settings)) {
            $gapDays = $settings->value;
        }

        $notifications = Notifications::with(['customer', 'notification', 'customer_exclusion'])
            ->where('is_pause', 0)
            ->whereDate('notification_date', '>=', date('Y-m-d'))
            ->get()
            ->toArray();

        foreach ($notifications as $key => $value) {
            $customer_id = [];

            foreach ($value['customer_exclusion'] as $k => $v) {
                $customer_id = array_merge($customer_id, explode(',', $v['customer_id']));
            }

            if ($value['customer_type'] == 1) {
                $students = Students::whereIn('added_by', explode(',', $value['exclusion_member_level']))->pluck('id')->toArray();
                $customer_id = array_merge($customer_id, $students);

                $dues = StudentDueFees::with(['profile', 'paid'])
                    ->whereDate('due_date', '<', date('Y-m-d'))
                    ->whereDate('due_date', '!=', '0000-00-00 00:00:00')
                    ->whereNotIn('student_id', $customer_id)
                    ->orderBy('due_date', 'asc')
                    ->get()
                    ->unique('student_id')
                    ->toArray();
            } else if ($value['customer_type'] == 2) {
                $students = Businesses::whereIn('added_by', explode(',', $value['exclusion_member_level']))->pluck('id')->toArray();
                $customer_id = array_merge($customer_id, $students);

                $dues = BusinessDueFees::with(['profile', 'paid'])
                    ->whereDate('due_date', '<', date('Y-m-d'))
                    ->whereDate('due_date', '!=', '0000-00-00 00:00:00')
                    ->whereNotIn('business_id', $customer_id)
                    ->orderBy('due_date', 'asc')
                    ->get()
                    ->unique('business_id')
                    ->toArray();
            }

            // dd($dues);

            foreach ($dues as $due_key => $due_value) {
                $now = time();
                $gap = 0;

                if (!empty($due_value['profile']['last_notification_date'])) {
                    $last_notification_date = strtotime($due_value['profile']['last_notification_date']);
                    $datediffTemp = $now - $last_notification_date;
                    $gap = round($datediffTemp / (60 * 60 * 24));
                }

                if ($gap >= $gapDays || empty($due_value['profile']['last_notification_date']) || $due_value['profile']['last_notification_date'] == NULL) {
                    // dd($due_value, $gap);
                    $your_date = strtotime($due_value['due_date']);
                    $datediff = $now - $your_date;

                    $bucket = round($datediff / (60 * 60 * 24));
                    $templateName = $this->getNotificationTemplate($bucket, $due_value);
                    $template = Templates::where('name', $templateName)->first();

                    $user = User::where('id', $due_value['profile']['added_by'])->first();
                    if (!empty($user)) {
                        $user = $user->toArray();
                        if (!empty($user['city_id'])) {
                            $city = City::where('id', $user['city_id'])->first();
                            $cityName = !empty($city) ? $city->name : '';
                        } else {
                            $cityName = '';
                        }
                    } else {
                        $cityName = '';
                    }

                    $content = $this->prepareContent($template->content, $bucket, $cityName, $user);

                    // send sms start
                    if (isset($due_value['profile']['contact_phone'])) {
                        $smsService = new SmsService();
                        $config_mobile_number=config('custom_configs.B2B_SMS_Number');
                        $env_type = Config('app.env');
                        if ($env_type == "production") {
                          $smsResponse = $smsService->sendSms($due_value['profile']['contact_phone'], $content, 'SMSNOTI-'.date('d-M-Y').'-'.$templateName);
                        } else {
                          $smsResponse = $smsService->sendSms($config_mobile_number, $content, 'SMSNOTI-'.date('d-M-Y').'-'.$templateName);
                        }
                        // $smsResponse = $this->sendDueSms($due_value['profile']['contact_phone'], $content);
                    }
                    if (isset($due_value['profile']['concerned_person_phone'])) {
                        $smsService = new SmsService();
                        $config_mobile_number=config('custom_configs.B2B_SMS_Number');
                        $env_type = Config('app.env');
                        if ($env_type == "production") {
                          $smsResponse = $smsService->sendSms($due_value['profile']['contact_phone'], $content, 'SMSNOTI-'.date('d-M-Y').'-'.$templateName);
                        } else {
                          $smsResponse = $smsService->sendSms($config_mobile_number, $content, 'SMSNOTI-'.date('d-M-Y').'-'.$templateName);
                        }
                        // $smsResponse = $this->sendDueSms($due_value['profile']['concerned_person_phone'], $content);
                    }
                    // send sms end

                    if (isset($smsResponse) && isset($smsResponse['sent']) && $smsResponse['sent'] == 1) {
                        if ($value['customer_type'] == 1) {
                            $student = Students::where('id', $due_value['profile']['id'])->first();
                            $student->last_sent_notification_template = $templateName;
                            $student->last_notification_date = date('Y-m-d');
                            $student->last_notification_due_id = $due_value['id'];
                            $student->save();
                        } else if ($value['customer_type'] == 2) {
                            $business = Businesses::where('id', $due_value['profile']['id'])->first();
                            $business->last_sent_notification_template = $templateName;
                            $business->last_notification_date = date('Y-m-d');
                            $business->last_notification_due_id = $due_value['id'];
                            $business->save();
                        }
                    }

                    // dd($bucket, $due_value, $template, $content, $user);
                }
            }
            // dd($dues, $customer_id);
            $noti = Notifications::where('id', $value['id'])->first();
            $noti->is_pause = 3;
            $noti->save();
        }
        return 1;
    }

    private function getNotificationTemplate($bucket, $due)
    {
        $template = 'templates-';
        $temp = $due['profile']['last_sent_notification_template'];
        if ($temp != 'introductory_overdue') {
            $temp = str_replace('templates-', '', $temp);
            $temp = explode('_', $temp);
            $temp = $temp[0];

            if ($temp == NULL || $temp == 5 || $temp > 5) {
                $temp = 1;
            } else {
                $temp++;
            }

            $template .= $temp . '_';
        } else {
            $template = 'templates-1_';
        }

        if ($bucket < 31) {
            $template .= '1-30';
        } else if ($bucket >= 31 && $bucket < 61) {
            $template .= '31-60';
        } else if ($bucket >= 61 && $bucket < 91) {
            $template .= '61-90';
        } else if ($bucket >= 91 && $bucket < 181) {
            $template .= '91-180';
        } else {
            $template .= '180-210';
        }

        return $template;
    }

    private function prepareContent($content, $bucketDays, $cityName, $user, $due_date = '')
    {

        if (strpos($content, 'member_name_15_char')) {
            $content = str_replace('member_name_15_char', isset($user['name']) ? substr($user['name'], 0, 15) : '', $content);
        }

        if (strpos($content, 'overdue_bucket')) {
            $content = str_replace('overdue_bucket', $bucketDays, $content);
        }

        if (strpos($content, 'report_link')) {
            $content = str_replace('report_link', url('/'), $content);
        }

        if (strpos($content, 'city_name_10_char')) {
            $content = str_replace('city_name_10_char', substr($cityName, 0, 10), $content);
        }

        if (strpos($content, 'latest_due_date')) {
            $content = str_replace('latest_due_date', $due_date, $content);
        }

        $content = str_replace('<<', '', $content);
        $content = str_replace('>>', '', $content);

        return $content;
    }

    public function sendDueSms($to, $text)
    {
        // $to = '8866222723'; // get sms on testing number
        // $to = '7978041849'; // get sms on testing number

        $key = "sFA2OTHGLovBJ6eAzIQf6Q==";
        // $key = "krTo3zc7NQSX8zeH18xk9w==";

        $sender = "RECDNT";
        $text = urlencode($text);
        $type = "UC";
        $countryCode = "91";
        $dlt_entity_id = "1001517760000018200";

        $url = "https://japi.instaalerts.zone/httpapi/QueryStringReceiver?ver=1.0&send=" . $sender . "&dest=" . $to . "&text=" . $text . "&country_cd=" . $countryCode . "&key=" . $key . "&type=" . $type . "&dlt_entity_id=" . $dlt_entity_id;


        $url = str_replace(' ', '%20', $url);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
        ));

        $response = [];
        $response['fail_to_send'] = false;

        try {

            //$otpResponse = file_get_contents($url);
            $otpResponse = curl_exec($curl);
            curl_close($curl);

            $otpResponse = strtolower($otpResponse);

            if (strpos($otpResponse, "statuscode=200") !== false) {
                $response['sent'] = 1;
                $response['actual_status'] = "queue";
            } else {
                $response['sent'] = 0;
                $response['actual_status'] = "failed";
                $response['message'] = 'failed to send sms.';
            }
        } catch (\Exception $e) {
            $response['actual_status'] = "failed";
            $response['fail_to_send'] = true;
            $response['sent'] = 0;
            $response['message'] = 'can not send sms right now, please try again.';
        }
        return $response;
    }

    public function introductoryOverdueStudentsCron()
    {
        Log::info('-----------------------');
        Log::info('introductoryOverdueStudentsCron run at: '. date('Y-m-d H:i:s'));
        Log::info('-----------------------');

        ini_set('max_execution_time', 0);

        $dues = StudentDueFees::with(['profile', 'paid'])
            ->whereDate('due_date', '<', date('Y-m-d'))
            ->whereDate('due_date', '!=', '0000-00-00 00:00:00')
            ->orderBy('due_date', 'asc')
            ->get()
            ->unique('student_id')
            ->toArray();

        // $smsService = new SmsService();
        // $smsResponse = $smsService->sendSms('8866222723', 'Test sms from recordent');

        // dd($dues);

        foreach ($dues as $due_key => $due_value) {
            $now = time();
            $gap = 0;

            $due_date = strtotime($due_value['due_date']);
            $datediffTemp = $now - $due_date;
            $gap = round($datediffTemp / (60 * 60 * 24));

            if ($gap > 6) {
                if (empty($due_value['profile']['last_notification_date']) || $due_value['profile']['last_notification_date'] == NULL) {
                    // dd($due_value, $gap);
                    $your_date = strtotime($due_value['due_date']);
                    $datediff = $now - $your_date;

                    $bucket = round($datediff / (60 * 60 * 24));
                    $templateName = 'introductory_overdue';
                    $template = Templates::where('name', $templateName)->first();

                    if(isset($due_value['profile']['added_by'])) {
                      $user = User::where('id', $due_value['profile']['added_by'])->first();
                      if (!empty($user)) {
                          $user = $user->toArray();
                          if (!empty($user['city_id'])) {
                              $city = City::where('id', $user['city_id'])->first();
                              $cityName = !empty($city) ? $city->name : '';
                          } else {
                              $cityName = '';
                          }
                      } else {
                          $cityName = '';
                      }
                  }

                    $content = $this->prepareContent($template->content, $bucket, $cityName, $user);

                    // send sms start
                    if (isset($due_value['profile']['contact_phone'])) {
                        $smsService = new SmsService();
                        $config_mobile_number=config('custom_configs.B2B_SMS_Number');
                        $env_type = Config('app.env');
                        if ($env_type == "production") {
                          $smsResponse = $smsService->sendSms($due_value['profile']['contact_phone'], $content, 'SMSNOTI-'.date('d-M-Y').'-'.$templateName);
                        } else {
                          $smsResponse = $smsService->sendSms($config_mobile_number, $content, 'SMSNOTI-'.date('d-M-Y').'-'.$templateName);
                        }
                        // $smsResponse = $this->sendDueSms($due_value['profile']['contact_phone'], $content);

                        if (isset($smsResponse) && isset($smsResponse['sent']) && $smsResponse['sent'] == 1) {
                            $student = Students::where('id', $due_value['profile']['id'])->first();
                            $student->last_sent_notification_template = $templateName;
                            $student->last_notification_date = date('Y-m-d');
                            $student->last_notification_due_id = $due_value['id'];
                            $student->save();
                        }
                    }
                    // send sms end
                    // dd('in', $smsResponse);

                    // dd($bucket, $due_value, $template, $content, $user);
                }
            }
        }

        return 1;
    }

    public function introductoryOverdueBusinessCron()
    {
        Log::info('-----------------------');
        Log::info('introductoryOverdueBusinessCron run at: '. date('Y-m-d H:i:s'));
        Log::info('-----------------------');

        ini_set('max_execution_time', 0);

        $dues = BusinessDueFees::with(['profile', 'paid'])
            ->whereDate('due_date', '<', date('Y-m-d'))
            ->whereDate('due_date', '!=', '0000-00-00 00:00:00')
            ->orderBy('due_date', 'asc')
            ->get()
            ->unique('business_id')
            ->toArray();

        // dd($dues);

        foreach ($dues as $due_key => $due_value) {
            $now = time();
            $gap = 0;

            $due_date = strtotime($due_value['due_date']);
            $datediffTemp = $now - $due_date;
            $gap = round($datediffTemp / (60 * 60 * 24));

            if ($gap < 6) {
                if (empty($due_value['profile']['last_notification_date']) || $due_value['profile']['last_notification_date'] == NULL) {
                    // dd($due_value, $gap);
                    $your_date = strtotime($due_value['due_date']);
                    $datediff = $now - $your_date;

                    $bucket = round($datediff / (60 * 60 * 24));
                    $templateName = 'introductory_overdue';
                    $template = Templates::where('name', $templateName)->first();

                    $user = User::where('id', $due_value['profile']['added_by'])->first();
                    if (!empty($user)) {
                        $user = $user->toArray();
                        if (!empty($user['city_id'])) {
                            $city = City::where('id', $user['city_id'])->first();
                            $cityName = !empty($city) ? $city->name : '';
                        } else {
                            $cityName = '';
                        }
                    } else {
                        $cityName = '';
                    }

                    $content = $this->prepareContent($template->content, $bucket, $cityName, $user);

                    // send sms start
                    if (isset($due_value['profile']['concerned_person_phone'])) {
                        $smsService = new SmsService();
                        $config_mobile_number=config('custom_configs.B2B_SMS_Number');
                        $env_type = Config('app.env');
                        if ($env_type == "production") {
                          $smsResponse = $smsService->sendSms($due_value['profile']['concerned_person_phone'], $content, 'SMSNOTI-'.date('d-M-Y').'-'.$templateName);
                        } else {
                          $smsResponse = $smsService->sendSms($config_mobile_number, $content, 'SMSNOTI-'.date('d-M-Y').'-'.$templateName);
                        }
                        // $smsResponse = $this->sendDueSms($due_value['profile']['concerned_person_phone'], $content);
                    }
                    // send sms end

                    if (isset($smsResponse) && isset($smsResponse['sent']) && $smsResponse['sent'] == 1) {
                        $business = Businesses::where('id', $due_value['profile']['id'])->first();
                        $business->last_sent_notification_template = $templateName;
                        $business->last_notification_date = date('Y-m-d');
                        $business->last_notification_due_id = $due_value['id'];
                        $business->save();
                    }

                    // dd($bucket, $due_value, $template, $content, $user);
                }
            }
        }
        // dd($dues, $customer_id);
        return 1;
    }

    private function sendDueSmsOld($to, $text)
    {
        $to = '8866222723';
        $to = "+91" . $to;

        // Configure HTTP basic authorization: basicAuth
        $config = \Karix\Configuration::getDefaultConfiguration()
            ->setUsername($this->username)
            ->setPassword($this->password);

        $apiInstance = new \Karix\Api\MessageApi(
            // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
            // This is optional, `GuzzleHttp\Client` will be used as default.
            NULL,
            $config
        );

        date_default_timezone_set('UTC');
        // Create Message object
        $message = (new \Karix\Model\CreateMessage())
            ->setChannel("sms") //Or use "whatsapp"
            ->setSource($this->source)
            ->setDestination([$to])
            // ->setMessageTag('SMSNOTI-'.date('d-M-Y').'-1-30-O1')
            ->setContent(["text" => $text]);
        $response = [];
        $response['fail_to_send'] = false;

        try {

            $result = $apiInstance->sendMessage($message);
            $response['actual_status'] = $result->getObjects()[0]->getStatus();
            //failed // undelivered //rejected
            if (in_array($response['actual_status'], ['failed', 'undelivered', 'rejected'])) {
                $response['sent'] = 0;
                $response['message'] = 'failed to send sms.';
            } else {
                $response['sent'] = 1;
            }
        } catch (\Exception $e) {
            $response['fail_to_send'] = true;
            $response['sent'] = 0;
            $response['message'] = 'can not send sms right now, please try again.';
        }

        return $response;
    }
}
