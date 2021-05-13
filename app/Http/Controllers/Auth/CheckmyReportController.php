<?php 
namespace App\Http\Controllers\Front\Individual;
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Response;   
use App\User;
use General;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Services\SmsService;
use Illuminate\Contracts\Encryption\DecryptException;
use App\UserToken;
use Session;
use Illuminate\Support\Facades\DB;
use Mail;
use App\Students;
use App\Individuals;
use App\Businesses;
use App\StudentDueFees;
use App\StudentPaidFees;
use App\DuePayment;
use PDF;
use App\DisputeReason;
use App\Dispute;
use Storage;

//use App\Http\Controllers\MagicAthunicate;

class CheckmyReportController extends Controller
{

    public function authenticate_individual_checkmyreport_login(Request $request,  $token)
    {

        $str_url=url('checkmyreport/individual/'.$token);
        $individual = Students::where('uniqe_url_individual', $str_url)->first();
       
        if (empty($individual->id)) {

            $error_response=array(
                'error' => true,
                'message'=>'There is no Due report for this link or Invalid token ' 
            );

            return Response::json($error_response, 401);
           
        }

        $str =url('checkmyreport/individual/').'/';
        $res_ary=explode($str,$individual->uniqe_url_individual);

            if($res_ary[1] != $token)
            {
                $error_response=array(
                    'error' => true,
                    'message'=>'There is no Due report for this link or Invalid token ' 
                );

                return Response::json($error_response, 401);
            }



        $individuals_mobile=$individual->contact_phone;

        $individuals = Individuals::where('mobile_number', General::encrypt($individuals_mobile))
        ->where('status', 1)
        ->first();
        if(empty($individuals)){
			
            $individuals = Individuals::create([
            'mobile_number'=>$individuals_mobile,
            'customer_type' => 'individual',
            'created_at'=>Carbon::now()
            ]);
            }
            $individuals = Individuals::where('mobile_number', General::encrypt($individuals_mobile))
            ->where('status', 1)
            ->first();

        Session::put('individual_client_id', $individuals->id);
        Session::put('individual_client_mobile_number', $individuals->mobile_number);

        $data = General::getBusinessBasicProfileInArray();
        if(count($data)){
        Session::put('individual_client_udise_gstn_sector_id', $data['sector_id'] ?? '');   
        Session::put('individual_client_udise_gstn_sector_type', $data['sector_unique_identification_type']); 
        Session::put('individual_client_udise_gstn_sector_type_text', $data['sector_unique_identification_type_text']);  
        }


        if(!empty(Session::get('individual_client_id'))){


            Students::where('uniqe_url_individual', $str_url)->update([
                'no_of_view_count'=> DB::raw('IFNULL(no_of_view_count,0) + 1'),
                'last_viewed' => Carbon::now()
            ]);

            $date=Carbon::today()->toDateString();
            $today_date=date('Y-m-d',strtotime('+1 day', strtotime($date)));

           General::CreditreportAnalysis_Savedata_function($date,$today_date,$individual->id,"Individual");
           
                return redirect()->route('front-individual.dashboard');
            
            }  
            else{
                $error_response=array(
                    'error' => true,
                    'message'=>'Session is invalid' 
                );

                return Response::json($error_response, 401);
            }      
    }

    public function authenticate_business_checkmyreport_login(Request $request,  $token)
    {

        $str_url=url('checkmyreport/business/'.$token);
        $individual = Businesses::where('uniqe_url_business', $str_url)->first();
       
        if (empty($individual->id)) {

            $error_response=array(
                'error' => true,
                'message'=>'There is no Due report for this link or Invalid token ' 
            );

            return Response::json($error_response, 401);
           
        }

        $str =url('checkmyreport/business/').'/';
        $res_ary=explode($str,$individual->uniqe_url_business);

            if($res_ary[1] != $token)
            {
                $error_response=array(
                    'error' => true,
                    'message'=>'There is no Due report for this link or Invalid token ' 
                );

                return Response::json($error_response, 401);
            }



        $mobile_number=$individual->concerned_person_phone;
        $email=$individual->email;
        if ($mobile_number) {
            $individual_where_column_name = "mobile_number";
            $individual_where_column_value = $mobile_number;
        } else {
            $individual_where_column_name = "email";
            $individual_where_column_value = $email;
        }

        $individual = Individuals::where($individual_where_column_name, General::encrypt($individual_where_column_value))
                                    ->where('status', 1)
                                    ->first();
        
                                    if(empty($individual)){
			
                                        $individual = Individuals::create([
                                        $individual_where_column_name=>$individual_where_column_value,
                                        'customer_type' => 'individual',
                                        'created_at'=>Carbon::now()
                                        ]);
                                        }
         $individual = Individuals::where($individual_where_column_name, General::encrypt($individual_where_column_value))
                                        ->where('status', 1)
                                        ->first();
        Session::put('individual_client_id', $individual->id);
        if ($mobile_number) {
            Session::put('individual_client_mobile_number', $individual->mobile_number);
        } else {
            Session::put('individual_client_email', $individual->email);
        }
    
        Session::put('individual_client_report_type', 'business_login');

        $data = General::getBusinessBasicProfileInArray();
        if(count($data)){
            Session::put('individual_client_udise_gstn_sector_id', $data['sector_id'] ?? '');   
            Session::put('individual_client_udise_gstn_sector_type', $data['sector_unique_identification_type']); 
            Session::put('individual_client_udise_gstn_sector_type_text', $data['sector_unique_identification_type_text']);  
        }       
        



        if(Session::has('individual_client_report_type') && !empty(Session::get('individual_client_report_type'))){

            Businesses::where('uniqe_url_business', $str_url)->update([
                'no_of_view_count'=> DB::raw('IFNULL(no_of_view_count,0) + 1'), 
                'last_viewed' => Carbon::now()
            ]);

            $date=Carbon::today()->toDateString();
            $today_date=date('Y-m-d',strtotime('+1 day', strtotime($date)));

            General::CreditreportAnalysis_Savedata_function($date,$today_date,$individual->id,"Business");

            return redirect()->route('front-business.dashboard');
        } 
            else{
                $error_response=array(
                    'error' => true,
                    'message'=>'Session is invalid' 
                );

                return Response::json($error_response, 401);
            }      
    }

}



?>