<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use App\UserType;
use App\UsersOfferCodes;
use App\Country;
use App\State;
use App\City;
use TCG\Voyager\Models\Role;
use Illuminate\Support\Facades\Session;
use App\Traits\MustSendEmail;
use App\Traits\MustVerifyEmail;
use General;
use App\Http\Controllers\Auth\VerificationController;
use Mail;
use App\PricingPlan;
use App\UserPricingPlan;
use App\Services\SmsService;
use App\UserEmailMobileOtp;
use App\RecordentExcludeKeywords;
use Response;
use Carbon\Carbon;
use Log;
use Illuminate\Support\Facades\DB;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;//,MustSendEmail,MustVerifyEmail;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo =  'admin/login';


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm(Request $request)
    {

       $countriePhonecodes = Country::select('phonecode','name')->where('phonecode','!=','0')->groupBy('phonecode')->orderBy('phonecode')->get();
       $countries = Country::where('name','LIKE','india')->orderBy('name')->get();
       $states = State::where('country_id',101)->get();
       $stateIds = [];
       foreach ($states as $state){
           $stateIds[] =$state->id;
       }
       $cities = City::whereIn('state_id',$stateIds)->get();
       // $userTypes = UserType::with('role')->whereHas('role')->where('status',1)->orderBy('name','ASC')->get();
       $userTypes = UserType::where('status',1)->orderBy('name','ASC')->get();

       $campaignsId = $request->query();
       $campaignsIdValue = array_key_exists('campaignid',$campaignsId) ? $campaignsId['campaignid'] : NULL;
       return view('auth.register',compact('userTypes','countriePhonecodes','countries','states','cities','campaignsIdValue'));
    }


    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
       //dd($data);
        // $userTypes = UserType::with('role')->whereHas('role')->pluck('id')->toArray();
        $userTypes = UserType::pluck('id')->toArray();
        $userTypes = implode(',',$userTypes);
        $rules = [
            'name' => ['required', 'string', 'max:28'],
            'email' => [ 'nullable','email', 'max:191'],
            'country_code'=>['required'],
            'mobile_number'=>['required','numeric','digits:10', 'regex:/^[6-9]\d{9}$/u'],
            'business_name'=>['required','max:50'],
            'user_type'=>['required','in:'.$userTypes],
            'pricing_plan_id'=>['nullable','integer']
        ];

        $ruleMessage = [
            'mobile_number.regex' => 'Invalid Mobile Number.',
        ];
	    try{
            if($data['user_type'] == "10" || $data['user_type']=="11"){
                $rules['type_of_business'] = ['required'];
            }
    	} catch(\Exception $e){
            return redirect()->back()->with(['message' => "Something Went Wrong", 'alert-type' => 'error']);
        }

        return Validator::make($data, $rules, $ruleMessage);
    }

	public function checkMobile(Request $request) {

        $alreadyExists = User::where('mobile_number','LIKE',General::encrypt($request->mobile_number))->first();
        $alreadyEmailExists = User::Where('email','LIKE',General::encrypt($request->email))->first();
        Log::debug("Member Signup check mobile number $request->mobile_number");
        $res=General::Email_Validation_api_call($request->email);
        $Response=json_decode($res)->result;

       if($Response == "undeliverable")
        {
		return response()->json(['error'=>true,'message'=>'Your email/Domain is invalid. Enter a valid email.'], 401);
   
        }

        if(!empty($alreadyExists) || !empty($alreadyEmailExists)){

            if (isset($alreadyExists['mobile_number']) && $request->mobile_number == $alreadyExists['mobile_number'] && isset($alreadyEmailExists['email']) && $request->email == $alreadyEmailExists['email']) {

                return response()->json(['error'=>true,'message'=>'The mobile number  and email has already been taken.'], 401);
            } else if(isset($alreadyExists['mobile_number']) && $request->mobile_number == $alreadyExists['mobile_number']){

                return response()->json(['error'=>true,'message'=>'The mobile number has already been taken.'], 401);
            }

            return response()->json(['error'=>true,'message'=>'The email id has already been taken.'], 401);
        } else {

            return Response::json(['success' => true,'message'=>''], 200);
        }
	}


    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $checkOtp = UserEmailMobileOtp::where('mobile_number', General::encrypt($request->mobile_number))
                    ->where('type',3)
                    ->orderBy('id', 'DESC')
                    ->first();

        if(empty($checkOtp) || $checkOtp->otp != '' ){
            return redirect()->back()->withInput()->withErrors(['Invalid Request.']);
        }

        if (!empty($checkOtp)) {
            $checkOtp->delete();
        }

        $alreadyExists = User::where('mobile_number','LIKE',General::encrypt($request->mobile_number))->first();

        if(!empty($alreadyExists)){
            return redirect()->back()->withInput()->withErrors(['The mobile number has already been taken.']);
        }

        if(!empty($request->email)){
            $alreadyExistsEmail = User::where('email','LIKE',General::encrypt($request->email))->first();
            if(!empty($alreadyExistsEmail)){
                return redirect()->back()->withInput()->withErrors(['The email has already been taken.']);
            }
        }
        $res=General::Email_Validation_api_call($request->email);
        $Response=json_decode($res)->result;

       if($Response == "undeliverable")
        {
            return redirect()->back()->withInput()->withErrors(['Your email/Domain is invalid. Enter a valid email.']);
        }

        $user= $this->create($request->all());

        if($user){
            if($request->pricing_plan_id){
                UserPricingPlan::create([
                    'user_id'=>$user->id,
                    'pricing_plan_id'=>$request->pricing_plan_id,
                ]);
            }
            Log::debug("Member Signed Up mobile number is $request->mobile_number");
            Log::debug("Member Id is $user->id");
			if(stripos($request->offer_code, 'one@') === 0) {
				UsersOfferCodes::create([
                    'user_id'=>$user->id,
					'offer_code'=>$request->offer_code,
                    'offer_code_status'=>$request->offer_code_status,
                ]);
			}

            event(new Registered($user));
			$user->sendEmailVerificationNotification();
			$user->markEmailAsSent();
            //$this->guard()->login($user);
        } else {
            return redirect('register')->withErrors('Can not register right now. please try again');
        }

        $adminData = User::where('role_id',1)->whereNotNull('email')->first();
        // $envData = env('APP_ENV');
        $envData = Config('app.env');
        // Log::debug("envData = ".$envData);

        if(!empty($adminData) && $envData == 'production'){

            $mailData = ['name'=>$adminData->name,
                         'email'=> 'signups@recordent.com',
                         'subject'=> 'New Member Registered on Recordent',
                         'business_name'=>$user->business_name,
                         'user'=>$user,
                         ];

            Mail::send('front.emails.after-registration', $mailData, function ($message) use ($mailData){
                $message->from(\Config::get('mail.from.address'), \Config::get('mail.from.name'));
                $message->to($mailData['email']);
                $message->subject($mailData['subject']);
            });
            Log::debug("Member Signup email is triggered");

        }
        if($request->input('campaign_id')!="") {
          DB::table('utm_containers_campaigns')
              ->where('id', $request->input('campaign_id'))
              ->update(array('lead_data' => $request->mobile_number, 'lead_type'=>2,'updated_at'=>Date('Y-m-d H:i:s')));
        }
        Auth::login($user);

        if (Auth::check()) {

            $credit_report_type_query_param = '';
            if(isset($request->credit_report_type) && !empty($request->credit_report_type)){
                $credit_report_type_query_param = '?credit_report_type='.$request->credit_report_type;
                return redirect(route('update-profile', $request->pricing_plan_id).$credit_report_type_query_param);
            }
        }

		return redirect('admin');
    }



    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
		//dd($data);
        // $userTypes = UserType::with('role')->whereHas('role')->where('id',$data['user_type'])->first();
        $userTypes = UserType::where('id',$data['user_type'])->first();
        // if($userTypes && $userTypes->role->id){
        //    $role_id =  $userTypes->role->id;
        // }else{
        //    return null;
        // }
		$type_of_business = array_key_exists('type_of_business', $data) ? $data['type_of_business'] : NULL;
        $customData = [
            'name' => $data['name'],
            //'pincode'=>$data['pincode'],
            'country_id'=>$data['country'],
            'country_code'=>$data['country_code'],
            'mobile_number'=>$data['mobile_number'],
            'business_name'=>$data['business_name'],
            'settings'=>["locale"=>"en"],
            'user_type'=>$data['user_type'],
            'type_of_business'=>$type_of_business,
            'status'=>1,
			'mobile_verified_at'=>Carbon::now()
        ];
        Log::debug("Member Signup mobile number is ".$data['mobile_number']);
        //if(!isset($data['skip_optional_fields'])){
            $customData['email'] = $data['email'];
            $customData['password'] = $data['password'] ? Hash::make($data['password']) : NULL;
            $customData['address']=$data['address'];
            $customData['city_id']=$data['city'];
            $customData['state_id']=$data['state'];
            $customData['branch_name']=$data['branch_name'];
            $customData['gstin_udise']=$data['gstin_udise'];
        //}

        if($data['user_type']==1 || $data['user_type']==2){
            $customData['role_id'] = 3;
        }else{
            // $customData['role_id'] = $role_id;
            $customData['role_id'] = 2;

        }
		//$user = User::where('mobile_number',General::encrypt($data['mobile_number']))->where('status',1)->first();
        return User::create($customData);
		//return redirect('admin');
    }


    public function businessname_validate(Request $request){
            $status= General::businessNameCheck($request->business_name);
            if($status)
            {
                 echo "true";
            }else
            {
                 echo "false";
            }
	}


	public function verifyRegister(Request $request){

        $ruleMessage = [
            'mobile_number.regex' => 'Invalid Mobile Number.',
        ];

		$validator = Validator::make($request->all(), [
            'mobile_number' => 'required|numeric|digits:10|regex:/^[6-9]\d{9}$/u',
            'otp'=> 'required|numeric|digits:6',
        ], $ruleMessage);

        if ($validator->fails()) {
            $errorHTML = '';
             foreach($validator->messages()->all() as $error){
                 $errorHTML.= "<p>$error</p>";
             }
             return response()->json(['error'=>true,'message'=>$errorHTML], 401);
        }

        $mobile_number = $request->input('mobile_number');
        $otp = $request->input('otp');
        $user= User::where('mobile_number',General::encrypt($mobile_number))->first();
        if(!empty($user)){
            return response()->json(['error'=>true,'message'=>'Mobile number already Registerd'], 401);
        }

        $checkOtp = UserEmailMobileOtp::where('mobile_number',General::encrypt($mobile_number))
        			->where('otp','LIKE',General::encrypt($otp))
        			->where('type',3)
        			->first();
        if(empty($checkOtp)){
        	return response()->json(['error'=>true,'message'=>'Invalid otp'], 401);
        }

        $checkOtp->otp = '';
        $checkOtp->save();
        // $checkOtp->delete();

        return Response::json(['success' => true,'message'=>'Successfully Registered.'], 200);
	}



	public function getRegisterOTP(Request $request){

        $ruleMessage = [
            'mobile_number.regex' => 'Invalid Mobile Number.',
        ];

		$validator = Validator::make($request->all(), [
            'mobile_number' => 'required|numeric|digits:10|regex:/^[6-9]\d{9}$/u',
        ], $ruleMessage);

        if ($validator->fails()) {
            $errorHTML = '';
             foreach($validator->messages()->all() as $error){
                 $errorHTML.= "<p>$error</p>";
             }
             return response()->json(['error'=>true,'message'=>$errorHTML], 401);
        }

        $mobile_number = $request->input('mobile_number');
        $user= User::where('mobile_number',General::encrypt($mobile_number))->first();
        if(!empty($user)){
            return response()->json(['error'=>true,'message'=>'Mobile number already Registered'], 401);
        }
        $otp = sprintf("%06d", mt_rand(1, 999999));
        //$otpMessage = 'Your Recordent OTP is '.$otp;
        $otpMessage =   $otp.' is your OTP for signing up on Recordent. Please do not share this with anyone.';
        $smsService = new SmsService();
        $smsResponse = $smsService->sendSms($mobile_number,$otpMessage);
        if($smsResponse['fail_to_send']){
            return response()->json(['error'=>true,'message'=>'server not responding'], 500);
        }
		//print_r($smsResponse); die;
        Log::debug("Member Signup Requested OTP from mobile number is ".$mobile_number);
        Log::debug("Member Signup Requested OTP from mobile number SMS Status is ".$smsResponse['sent']);
        Log::debug("Member Signup OTP is ".$otp);
       if($smsResponse['sent']==1){
        	$checkOtp = UserEmailMobileOtp::where('mobile_number', General::encrypt($mobile_number))
                    ->where('type',3)
                    ->orderBy('id', 'DESC')
                    ->delete();

            UserEmailMobileOtp::create([
             	'mobile_number'=>$mobile_number,
             	'type'=>3,
             	'otp'=>$otp,
             	'added_by'=>'',
             	'created_at'=>Carbon::now()
            ]);
            
            return Response::json(['success' => true,'mobile_number'=>$mobile_number,'message'=>'OTP sent to your mobile number'], 200);
        }
        return Response::json(['error' => true,'message'=>'can not send OTP right now. Try again'], 401);
	}

    function EmailVerification(Request $request)
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
