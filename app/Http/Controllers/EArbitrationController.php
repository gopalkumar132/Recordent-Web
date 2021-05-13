<?php 
namespace App\Http\Controllers;
use Illuminate\Http\Request;

use Illuminate\Support\Str;
use Validator;
use Response;
use Carbon\Carbon;
use DB;
use Auth;
use App\User;
use Storage;
use General;
use Session;
use App\Services\SmsService;
use Illuminate\Support\Collection;
use App\Businesses;
use App\BusinessDueFees;
use App\BusinessPaidFees;
use App\StudentDueFees;
use App\DuesSmsLog;
use App\StudentPaidFees;
use App\Students;
use App\Sector;
use App\State;
use App\City;
use App\EarbitrationCustomers;
use Illuminate\Support\Facades\Mail as SendMail;
class EArbitrationController extends Controller
{

    public function EArbitrationList(Request $request){

        $userId = Auth::id();
		$AuthUser = Auth::user();
    	$authId = Auth::id();
    	$currentDate =Carbon::now();

        if($request->customers_type == "Individual")
        {
                    $records = Students::select('students.id','students.person_name','students.email','dob','father_name',
                                                'mother_name','aadhar_number','contact_phone','custom_student_id',
                                                'sdf.external_student_id','sdf.id as dueid', DB::raw('da - IF(pa,pa,0) as total'))
                                                    ->join('student_due_fees as sdf',function($q){
                                                        $q->on('students.id','=','sdf.student_id');
                                                    }); 
                
                $records=$records->join(DB::raw('(SELECT sum(student_due_fees.due_amount) AS da,due_date,
                                                    added_by,deleted_at,student_id,external_student_id from student_due_fees 
                                                    WHERE added_by ='.$userId .' AND deleted_at is null 
                                                    GROUP BY student_due_fees.student_id,student_due_fees.added_by,student_due_fees.external_student_id) due'),function($q){
                                                    $q->on('students.id','=','due.student_id');
                                                    $q->where('due.deleted_at','=',null);

                });


                    $records=$records->leftJoin(DB::raw('(SELECT sum(student_paid_fees.paid_amount) AS pa,
                                                        added_by,deleted_at,student_id,external_student_id from student_paid_fees 
                                                        WHERE added_by='.$userId .' AND deleted_at is NULL 
                                                        GROUP BY student_paid_fees.student_id,student_paid_fees.added_by,student_paid_fees.external_student_id) paid'),function($q) {
                                                                            $q->on('students.id','=','paid.student_id');
                                                                            $q->where('paid.deleted_at','=',null);
                                                                        });
                    
                    if(!empty($request->input('concerned_person_name'))){
                        $records = $records->where('person_name' , 'LIKE' , General::encrypt($request->input('concerned_person_name')));
                    }		

                    if(!empty($request->input('concerned_person_phone'))){
                        $records = $records->where('contact_phone','LIKE',General::encrypt($request->input('concerned_person_phone')));
                    }
                    if(!empty($request->input('due_date_period'))){
                        $records = $records->whereNotNull('due2.due_date');
                        $records = $records->groupBy('due2.external_student_id');
                    }else{
                        $records = $records->where('sdf.added_by','=',$userId);
                        $records = $records->where('sdf.deleted_at','=',NULL);
                        $records = $records->groupBy('sdf.student_id');
                        $records = $records->groupBy('sdf.added_by');
                        $records = $records->groupBy('sdf.external_student_id');
                    }
            
                    //$records = $records->orderBy('students.id','DESC')->paginate(25);
                    // $records = $records->get();
                   // $records_individual=$records;
                    $records_individual = $records->get();
                    $default_records = $records_individual;
                    if($records->count()){
                        foreach ($records as &$record) {
                            $record->delayNumber = StudentDueFees::where('student_id',$record->id)->count();
                        }
                    }
                
                        $businessUser = User::where('id','=',$userId)->first();
                    $businessName = $businessUser->business_name;

    }else{

                

        $User = Auth::user();
    	$sectors = Sector::whereNull('deleted_at')->where('status',1)->get();
    	$states = State::where('country_id',101)->get(); 
	    $stateIds = []; 
	    foreach ($states as $state){
	       $stateIds[] =$state->id; 
	    } 
	    $cities = City::whereIn('state_id',$stateIds)->get();
		$userId = Auth::id();
    	$currentDate =Carbon::now();
    	if(!empty($request->input('due_date_period'))){
    		$business_records = Businesses::select('businesses.unique_identification_number','businesses.custom_business_id','businesses.concerned_person_name','businesses.concerned_person_phone','businesses.id','businesses.company_name','businesses.sector_id','businesses.state_id','businesses.city_id','businesses.added_by','due2.due_date', DB::raw('da - IF(pa,pa,0) as total'));
    	}else{

			$business_records = Businesses::select('businesses.unique_identification_number',
                                                    'businesses.custom_business_id','businesses.concerned_person_name',
                                                    'businesses.concerned_person_phone','businesses.id','businesses.company_name','businesses.email',
                                                    'businesses.sector_id','businesses.state_id','businesses.city_id',
                                                    'businesses.added_by','bdf.external_business_id','bdf.id as dueid', DB::raw('da - IF(pa,pa,0) as total'))
                                                    ->join('business_due_fees as bdf',function($q){
                                                        $q->on('businesses.id','=','bdf.business_id');
                                                        });				
            }
		   $business_records=$business_records->join(DB::raw('(SELECT sum(business_due_fees.due_amount) AS da,due_date,added_by,
                                                        deleted_at,business_id from business_due_fees WHERE added_by ='.$userId .' 
                                                        AND deleted_at is null GROUP BY business_due_fees.business_id,business_due_fees.added_by,business_due_fees.external_business_id) due'),function($q){
                                                        $q->on('businesses.id','=','due.business_id');
                                                        $q->where('due.deleted_at','=',null);

                                                    });	

        if(!empty($request->input('unique_identification_number'))){
            
            $business_records = $business_records->where('businesses.unique_identification_number' , 'LIKE' , General::encrypt($request->input('unique_identification_number')));
        }
        if(!empty($request->input('concerned_person_name'))){
            
            $business_records = $business_records->where('businesses.concerned_person_name' , 'LIKE' , General::encrypt($request->input('concerned_person_name')));
        }
        if(!empty($request->input('concerned_person_phone'))){
            
            $business_records = $business_records->where('businesses.concerned_person_phone' , 'LIKE' , General::encrypt($request->input('concerned_person_phone')));
        }

		$business_records=$business_records->leftJoin(DB::raw('(SELECT sum(business_paid_fees.paid_amount) AS pa,added_by,deleted_at,business_id from business_paid_fees WHERE added_by='.$userId .' AND deleted_at is NULL GROUP BY business_paid_fees.business_id,business_paid_fees.added_by,business_paid_fees.external_business_id) paid'),function($q) {
                                                        $q->on('businesses.id','=','paid.business_id');
                                                        $q->where('paid.deleted_at','=',null);
                                                        //$q->where('paid.added_by',$userId);
							                        });
		
			
				$business_records = $business_records->where('bdf.added_by','=',$userId);
				$business_records = $business_records->where('bdf.deleted_at','=',NULL);
				$business_records = $business_records->groupBy('bdf.business_id');
				$business_records = $business_records->groupBy('bdf.added_by');
				$business_records = $business_records->groupBy('bdf.external_business_id');

			//$business_records =$business_records->orderBy('businesses.id','DESC')->paginate(25);
			 $business_records = $business_records->get();
             $default_records=$business_records;
			$businessUser = User::where('id','=',$userId)->first();
			$businessName = $businessUser->business_name ?? '';
			
    }

           // $records = $records_individual->merge($business_records);
            $records = $default_records;
            $customer_type="Business";
           if(isset($request->customers_type))
           {
               if($request->customers_type == "Individual")
               {
                $customer_type="Individual";
                $records = $records_individual;
               }else{
                $records = $business_records;
                $customer_type="Business";
               }

           }
             
		return view('earbitration',compact('records','userId','customer_type'));

	}	

public function EArbitrationSendMail(Request $request)
{

    $authId = Auth::id();
    $MemberData=User::where("id",'=',$authId)->first();

    if(isset($MemberData['email']))
    {
        if($MemberData['email_verified_at'] == null)
        {
            $res=General::Email_Validation_api_call($MemberData['email']);
            $Response=json_decode($res)->result;
            if($Response == "undeliverable")
            {
 
                return Response::json(['success' => true,'message'=>'Your email/Domain is invalid. Enter a valid email.','status'=>"undeliverable"], 200);
            }

             $email_verified_at = [
                'email_verified_at'=>Carbon::now()
              ];
              $MemberData->where('id', Auth::id())->update($email_verified_at);
        }
        

        $member_name=$MemberData['business_name'];
        $concerned_person_name=$MemberData['name'];
        $member_mobile_number=$MemberData['mobile_number'];
        $member_email=$MemberData['email'];

        $data = array(   'member_name'=>$member_name,
						 'concerned_person_name'=> $concerned_person_name,
					     'member_mobile_number'=>$member_mobile_number,
                         'member_email'=>$member_email,
					     'subject'=>'E-Arbitration');


try{
            SendMail::send('earbitration_mail_sent', $data,function($message)use($data) {
            $message->to($data['member_email'])
            ->subject($data["subject"])
            ->cc([config('custom_configs.e_arbitration_cc_emails.e_arbitration_support_mail1'),config('custom_configs.e_arbitration_cc_emails.e_arbitration_support_mail2')]);
            });
        } catch (JWTException $exception) {
            $this->serverstatuscode = "0";
            $this->serverstatusdes = $exception->getMessage();
        }

            $Earbitration = EarbitrationCustomers::create([
                'member_id' =>Auth::id(),
                'customer_id'=>$request->customer_id,
                'customer_type' =>$request->customer_type,
                'created_at' => Carbon::now()
                ]);
        
        

        return Response::json(['success' => true,'message'=>'','status'=>true], 200);
 
    }else{
        return Response::json(['success' => true,'message'=>'','status'=>false], 200);  
    }
}


}?>