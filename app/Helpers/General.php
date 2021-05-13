<?php

use Illuminate\Support\Facades\Session as Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Auth;
use App\Students;
use App\Services\SmsService;
use App\StudentDueFees;
use App\StudentPaidFees;
use App\Businesses;
use App\BusinessDueFees;
use App\BusinessPaidFees;
use App\AdminNotification;
use App\AdminNotificationSeen;
use App\CustomerKyc;
use App\UserType;
use App\Sector;
use App\Country;
use App\State;
use App\City;
use App\User;
use App\Role;
use Illuminate\Support\Facades\DB;
use App\Individuals;
use App\DuesSmsLog;
use Carbon\Carbon;
use App\ConsentRequest;
use App\ConsentPayment;
use App\PricingPlan;
use App\ApiTokens;
use App\Dispute;
use Illuminate\Support\Facades\Log;
use App\RecordentExcludeKeywords;
use Symfony\Component\Debug\Exception\FlattenException;
use Illuminate\Support\Facades\Mail as SendMail;

use \Validator as Validator;
use App\BusinessBulkUploadIssues;
use App\IndividualBulkUploadIssues;
use App\MembershipPayment;
use App\TempMembershipPayment;
use App\CustomerCreditReportAnalysis;

// use App\UserPricingPlan;
// use Auth;

/**
 * General class for common functions
 * Class General
 */
class General
{
    public static function getFormatedDate($date){
        $date = date_create($date);
        return date_format($date,"d/m/Y");
    }

    public static function getAccountStatus($value){
        if($value == 'Yes'){
            return 'Active';
        }
        if($value == 'No'){
            return 'Inactive';
        }
        return '---';
    }

    public static function getAccountStatus1($value){
        if($value == 'Yes'){
            return 'Open';
        }
        if($value == 'No'){
            return 'Closed';
        }
        return '---';
    }




    /**
     * Get Total Dues For Student
     *
     * @return string
     */
    public static function getNumberOfDues($studentID,$added_by=null)
    {
        $studentDue = StudentDueFees::where('student_id','=',$studentID);
        if($added_by){
             $studentDue =  $studentDue->where('added_by',$added_by);
        }
       $studentDue =  $studentDue->whereNull('deleted_at');
        $studentDue = $studentDue->count();
        return $studentDue;
    }

    /**
     * Get Total Dues For business
     *
     * @return string
     */
    public static function getNumberOfDuesOfBusiness($businessID,$added_by=null)
    {
        $studentDue = BusinessDueFees::where('business_id','=',$businessID);
        if($added_by){
             $studentDue =  $studentDue->where('added_by',$added_by);
        }
       $studentDue =  $studentDue->whereNull('deleted_at');
        $studentDue = $studentDue->count();
        return $studentDue;
    }
    public static function user_pricing_plan(){
        $user_pricing_plan=Auth::guest()?array():Auth::user()->user_pricing_plan;
        return $user_pricing_plan;
    }
    public static function AmountInWords(float $amount)
    {
       $amount_after_decimal = round($amount - ($num = floor($amount)), 2) * 100;
       // Check if there is any number after decimal
       $amt_hundred = null;
       $count_length = strlen($num);
       $x = 0;
       $string = array();
       $change_words = array(0 => '', 1 => 'One', 2 => 'Two',
         3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
         7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
         10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
         13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
         16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
         19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
         40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
         70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
        $here_digits = array('', 'Hundred','Thousand','Lakh', 'Crore');
        while( $x < $count_length ) {
          $get_divider = ($x == 2) ? 10 : 100;
          $amount = floor($num % $get_divider);
          $num = floor($num / $get_divider);
          $x += $get_divider == 10 ? 1 : 2;
          if ($amount) {
           $add_plural = (($counter = count($string)) && $amount > 9) ? 's' : null;
           $amt_hundred = ($counter == 1 && $string[0]) ? ' and ' : null;
           $string [] = ($amount < 21) ? $change_words[$amount].' '. $here_digits[$counter]. $add_plural.'
           '.$amt_hundred:$change_words[floor($amount / 10) * 10].' '.$change_words[$amount % 10]. '
           '.$here_digits[$counter].$add_plural.' '.$amt_hundred;
            }
       else $string[] = null;
       }
       $implode_to_Rupees = implode('', array_reverse($string));
       $get_paise = ($amount_after_decimal > 0) ? "and " . ($change_words[$amount_after_decimal / 10] . "
       " . $change_words[$amount_after_decimal % 10]) . ' Paise' : '';
       return ($implode_to_Rupees ? $implode_to_Rupees . 'Rupees ' : '') . $get_paise;
    }

    public static function user_pricing_plan_status(){
        $user_pricing_plan=Auth::guest()?array():Auth::user()->user_pricing_plan;
        // return $user_pricing_plan;

            if(empty($user_pricing_plan)){
                return 'fail';
            }
            else{
                // if(($user_pricing_plan->paid_status==1&&strtotime($user_pricing_plan->end_date)>strtotime(date('Y-m-d H:i:s')))||$user_pricing_plan->pricing_plan_id==1)
                if(($user_pricing_plan->paid_status==1)||$user_pricing_plan->pricing_plan_id==1)
                {
                    return 'success';
                }else{
                    return 'fail';
                }
            }

    }


	/**
    * Get Total due Amount For Student By Custom ID
    *
    * @return string
    */
   public static function getTotalDueForStudentByCustomId($studentID=null,$added_by=null,$dueId=null,$customId=null)
   {

	 //   $getCustomId = StudentDueFees::select('external_student_id')->where('student_id','=',$studentID)->where('id','=',$dueId);
	 //   $getCustomId = $getCustomId->first();
		// $checkCustomId = isset($getCustomId->external_student_id) ? $getCustomId->external_student_id : NULL;
        $dueAmount = StudentDueFees::select(DB::raw('sum(due_amount) As DueAmount'))->where('student_id','=',$studentID);
		if($added_by){
            $dueAmount = $dueAmount->where('added_by',$added_by);
        }
		// if(!empty($checkCustomId)) {
			$dueAmount =  $dueAmount->where('external_student_id','=',$customId);
		// }


         $dueAmount =  $dueAmount->whereNull('deleted_at');
        $dueAmount = $dueAmount->groupBy('external_student_id')->first();
        if(empty($dueAmount)){
            return 0;
        }
       return $dueAmount->DueAmount;
   }



    /**
    * Get Total due Amount For Student By Student ID
    *
    * @return string
    */
   public static function getTotalDueForStudent($studentID,$added_by=null)
   {
        $dueAmount = StudentDueFees::select(DB::raw('sum(due_amount) As DueAmount'))->where('student_id','=',$studentID);
        if($added_by){
            $dueAmount = $dueAmount->where('added_by',$added_by);
        }
         $dueAmount =  $dueAmount->whereNull('deleted_at');
        $dueAmount = $dueAmount->groupBy('student_id')->first();
        if(empty($dueAmount)){
            return 0;
        }
       return $dueAmount->DueAmount;
   }

   /**
    * Get Total due Amount For Student By Student ID
    *
    * @return string
    */
   public static function getTotalDueForBusiness($businessId,$added_by=null)
   {
        $dueAmount = BusinessDueFees::select(DB::raw('sum(due_amount) As DueAmount'))->where('business_id','=',$businessId);
        if($added_by){
            $dueAmount = $dueAmount->where('added_by',$added_by);
        }
         $dueAmount =  $dueAmount->whereNull('deleted_at');
        $dueAmount = $dueAmount->groupBy('business_id')->first();
        if(empty($dueAmount)){
            return 0;
        }
       return $dueAmount->DueAmount;
   }


   /**
    * Get Total due Amount For Student By Student ID
    *
    * @return string
    */
 public static function getTotalDueForBusinessByCustomId($businessId=null,$added_by=null,$dueId=null,$customId=null)
   {
     //    $getCustomId = BusinessDueFees::select('external_business_id')->where('business_id','=',$businessId)->where('id','=',$dueId);
     //    $getCustomId = $getCustomId->first();
        // $checkCustomId = isset($getCustomId->external_business_id) ? $getCustomId->external_business_id : NULL;

        $dueAmount = BusinessDueFees::select(DB::raw('sum(due_amount) As DueAmount'))->where('business_id','=',$businessId);
        if($added_by){
            $dueAmount = $dueAmount->where('added_by',$added_by);
        }
        // if(!empty($checkCustomId)) {
            $dueAmount =  $dueAmount->where('external_business_id','=',$customId);
        // }
         $dueAmount =  $dueAmount->whereNull('deleted_at');
        $dueAmount = $dueAmount->groupBy('external_business_id')->first();
        if(empty($dueAmount)){
            return 0;
        }
       return $dueAmount->DueAmount;
   }



	/**
    * Get Total Paid Amount For Student By Custom ID
    *
    * @return string
    */
   public static function getTotalPaidForStudentByCustomId($studentID=null,$added_by=null,$dueId=null,$customId=null)
   {
  //       $getCustomId = StudentPaidFees::select('external_student_id')->where('student_id','=',$studentID)->where('due_id','=',$dueId);
		// $getCustomId = $getCustomId->first();
		// $checkCustomId = isset($getCustomId->external_student_id) ? $getCustomId->external_student_id : NULL;

		$paidAmount = StudentPaidFees::select(DB::raw('sum(paid_amount) As PaidAmount'))->where('student_id','=',$studentID);
        if($added_by){
            $paidAmount = $paidAmount->where('added_by',$added_by);
        }
		$paidAmount =  $paidAmount->where('external_student_id','=',$customId);
        $paidAmount =  $paidAmount->whereNull('deleted_at');
        $paidAmount = $paidAmount->groupBy('external_student_id')->first();
        if(empty($paidAmount)){
            return 0;
        }
       return $paidAmount->PaidAmount;

   }


	/**
    * Get Total Paid Amount For Student By Student ID
    *
    * @return string
    */
   public static function getTotalPaidForStudent($studentID,$added_by=null)
   {
        $paidAmount = StudentPaidFees::select(DB::raw('sum(paid_amount) As PaidAmount'))->where('student_id','=',$studentID);
        if($added_by){
            $paidAmount = $paidAmount->where('added_by',$added_by);
        }
        $paidAmount =  $paidAmount->whereNull('deleted_at');
        $paidAmount = $paidAmount->groupBy('student_id')->first();
        if(empty($paidAmount)){
            return 0;
        }
       return $paidAmount->PaidAmount;
   }



	/**
    * Get Total Paid Amount For Student By Student ID
    *
    * @return string
    */
    public static function getTotalPaidForBusinessByCustomId($businessID=null,$added_by=null,$dueId=null,$customId=null)
   {
        // $getCustomId = BusinessPaidFees::select('external_business_id')->where('business_id','=',$businessID)->where('due_id','=',$dueId);
        // $getCustomId = $getCustomId->first();
        // $checkCustomId = isset($getCustomId->external_business_id) ? $getCustomId->external_business_id : NULL;

        $paidAmount = BusinessPaidFees::select(DB::raw('sum(paid_amount) As PaidAmount'))->where('business_id','=',$businessID);
        if($added_by){
            $paidAmount = $paidAmount->where('added_by',$added_by);
        }

        $paidAmount =  $paidAmount->where('external_business_id','=',$customId);
        $paidAmount =  $paidAmount->whereNull('deleted_at');
        $paidAmount = $paidAmount->groupBy('external_business_id')->first();
        if(empty($paidAmount)){
            return 0;
        }
       return $paidAmount->PaidAmount;
   }


	/**
    * Get Total Paid Amount For Student By Student ID
    *
    * @return string
    */
   public static function getTotalPaidForBusiness($businessID,$added_by=null)
   {
        $paidAmount = BusinessPaidFees::select(DB::raw('sum(paid_amount) As PaidAmount'))->where('business_id','=',$businessID);
        if($added_by){
            $paidAmount = $paidAmount->where('added_by',$added_by);
        }
        $paidAmount =  $paidAmount->whereNull('deleted_at');
        $paidAmount = $paidAmount->groupBy('business_id')->first();
        if(empty($paidAmount)){
            return 0;
        }
       return $paidAmount->PaidAmount;
   }




    /**
     * Get Total Dues For Student
     *
     * @return string
     */
    public static function getPaidForDue($dueId,$added_by=null)
    {
        /*$paidAmount = StudentPaidFees::where('due_id','=',$dueId)->get();

        if($paidAmount->count()){
            return 0;
        }

        return $paidAmount->paid_amount;
        */

        $paidAmount = StudentPaidFees::select(DB::raw('sum(paid_amount) As PaidAmount'))->where('due_id','=',$dueId);
        if($added_by){
            $paidAmount = $paidAmount->where('added_by',$added_by);
        }
        $paidAmount =  $paidAmount->whereNull('deleted_at');
        $paidAmount = $paidAmount->groupBy('due_id')->first();

        if(empty($paidAmount)){
            return 0;
        }
       return $paidAmount->PaidAmount;
    }


    /**
     * Get Total Dues For Student
     *
     * @return string
     */
    public static function getPaidForDueOfBusiness($dueId,$added_by=null)
    {
        $paidAmount = BusinessPaidFees::select(DB::raw('sum(paid_amount) As PaidAmount'))->where('due_id','=',$dueId);
        if($added_by){
            $paidAmount = $paidAmount->where('added_by',$added_by);
        }
        $paidAmount =  $paidAmount->whereNull('deleted_at');
        $paidAmount = $paidAmount->groupBy('due_id')->first();

        if(empty($paidAmount)){
            return 0;
        }
       return $paidAmount->PaidAmount;
    }


    /**
     * Get average rating For customer kyc
     *
     * @return string
     */
    public static function getAvgRatingOfCustomerkyc($customerkyc_id)
    {

        $customerkyc = CustomerKyc::where('id',$customerkyc_id)->first();

        if(empty($customerkyc)){
            return '';
        }

        if(!empty($customerkyc->aadhar_number)){
            $averageRating = CustomerKyc::where('aadhar_number','=',Self::encrypt($customerkyc->aadhar_number))->groupBy('aadhar_number')->avg('rating');
        }else{
            $averageRating = CustomerKyc::where('id_proof_type',$customerkyc->id_proof_type)
                            ->where('id_proof_number','=',Self::encrypt($customerkyc->id_proof_number))
                            ->whereNull('aadhar_number')
                            ->groupBy('id_proof_type')
                            ->groupBy('id_proof_number')
                            ->avg('rating');
        }

        if(!empty($averageRating)){
           $averageRating = round($averageRating,2);
        }

        return $averageRating;


    }


    /**
     * Get user types
     *
     * @return object
     */
    public static function getUserTypes()
    {


        $userTypes = UserType::where('status',1)->get();
        return $userTypes;


    }

    /**
    * Get roles
    *
    * @return object
    */
   public static function getRoles()
   {
       $roles = Role::where('display_name','!=','')->get();
       return $roles;


   }


    /**
     * Get records count for user
     *
     * @return object
     */
    public static function getUserRecordsCount($userId)
    {
        $records = Students::select('students.id','students.person_name','dob','father_name','mother_name','aadhar_number','contact_phone', DB::raw('da - IF(pa,pa,0) as total'));

        $records=$records->join(DB::raw('(SELECT sum(student_due_fees.due_amount) AS da,due_date,added_by,deleted_at,student_id from student_due_fees WHERE added_by ='.$userId .' AND deleted_at is null GROUP BY student_due_fees.student_id) due'),function($q){
            $q->on('students.id','=','due.student_id');
            $q->where('due.deleted_at','=',null);
                            });

        $records=$records->leftJoin(DB::raw('(SELECT sum(student_paid_fees.paid_amount) AS pa,added_by,deleted_at,student_id from student_paid_fees WHERE added_by='.$userId .' AND deleted_at is NULL GROUP BY student_paid_fees.student_id) paid'),function($q) {
                                $q->on('students.id','=','paid.student_id');
                                $q->where('paid.deleted_at','=',null);
                                //$q->where('paid.added_by',$userId);
                            });

        $records = $records->get();
        return $records->count();
    }

    /**
     * Get records count for user
     *
     * @return object
     */
    public static function getUserBusinessRecordsCount($userId)
    {
        $records = Businesses::select('businesses.id', DB::raw('da - IF(pa,pa,0) as total'));

        $records=$records->join(DB::raw('(SELECT sum(business_due_fees.due_amount) AS da,due_date,added_by,deleted_at,business_id from business_due_fees WHERE added_by ='.$userId .' AND deleted_at is null GROUP BY business_due_fees.business_id) due'),function($q){
            $q->on('businesses.id','=','due.business_id');
            $q->where('due.deleted_at','=',null);
                            });

        $records=$records->leftJoin(DB::raw('(SELECT sum(business_paid_fees.paid_amount) AS pa,added_by,deleted_at,business_id from business_paid_fees WHERE added_by='.$userId .' AND deleted_at is NULL GROUP BY business_paid_fees.business_id) paid'),function($q) {
                                $q->on('businesses.id','=','paid.business_id');
                                $q->where('paid.deleted_at','=',null);
                                //$q->where('paid.added_by',$userId);
                            });

        $records = $records->get();
        return $records->count();
    }



    /**
     * Get sector list
     *
     * @return object
     */
    public static function getSectorList()
    {
        $data = Sector::whereNull('deleted_at')->where('status',1)->get();
        return $data;
    }

    /**
     * Get sector
     *
     * @return object
     */
    public static function getSector($id=null)
    {
        if(empty($id)){
            return '';
        }

        $data = Sector::whereNull('deleted_at')->where('id',$id)->where('status',1)->first();
        if(empty($data)){
            return '';
        }
        return $data;
    }

    /**
     * Get sector Name
     *
     * @return string
     */
    public static function getSectorNameById($id=null)
    {
        if(empty($id)){
            return '';
        }

        $data = Sector::whereNull('deleted_at')->where('id',$id)->where('status',1)->get();
        if(empty($data)){
            return '';
        }
        return $data->name;
    }


     /**
     * Get List / name  of Unique Identification Type of Sector
     *
     * @return Array or name
     */

    public static function getUniqueIdentificationTypeofSector($key=null)
    {
        $array = [
            1=>'GSTIN',
            2=>'UDISE'
        ];

        if(empty($key)){
            return $array;
        }

        return $array[$key] ?? '';
    }



     /**
     * Get label name of Unique Identification Number
     *
     * @return label name
     */

    public static function getLabelName($key=null)
    {
        $array = [
            'unique_identification_number'=>'GSTIN / Business PAN',
        ];

        if(empty($key)){
            return '';
        }

        return $array[$key] ?? '';
    }


    /**
     * Get State Name
     *
     * @return string
     */
    public static function getStateNameById($id=null)
    {
        if(empty($id)){
            return '';
        }

        $data = State::where('id',$id)->first();
        if(empty($data)){
            return '';
        }
        return $data->name;
    }


    /**
     * Get City Name
     *
     * @return string
     */
    public static function getCityNameById($id=null)
    {
        if(empty($id)){
            return '';
        }

        $data = City::where('id',$id)->first();
        if(empty($data)){
            return '';
        }
        return $data->name;
    }

     /**
     * Get state list
     *
     * @return object
     */
    public static function getStateList()
    {
        $data = State::where('country_id',101)->get();
        return $data;
    }

     /**
     * Get city list
     *
     * @return object
     */
    public static function getCityList()
    {

        $states = State::where('country_id',101)->get();
        $stateIds = [];
        foreach ($states as $state){
           $stateIds[] =$state->id;
        }
        $cities = City::whereIn('state_id',$stateIds)->get();
        return $cities;
    }

     /**
     * Get User Data
     *
     * @return object
     */
    public static function getUserBusinessName($id=null)
    {
        if(empty($id)){
            return '';
        }

        $data = User::where('id',$id)->first();
        if(empty($data)){
            return '';
        }
        return $data->business_name;
    }

    /**
     * Get Business basic profile Data
     * @param null
     * @return object
     */
    public static function getBusinessBasicProfileInArray()
    {

        $data = Businesses::where('unique_identification_number','=',Self::encrypt(Session::get('individual_client_udise_gstn')))->whereNull('deleted_at')->first();
        if(empty($data)){
            $data['sector_unique_identification_type'] ='';
            $data['sector_unique_identification_type_text']='';
            return $data;
        }
        $data = $data->toArray();
        $data['sector_unique_identification_type'] ='';
        $data['sector_unique_identification_type_text']='';


        $sector = General::getSector($data['sector_id']);
        if(!empty($sector)){

            $data['sector_unique_identification_type'] = $sector->unique_identification_type;
            $data['sector_unique_identification_type_text'] = General::getUniqueIdentificationTypeofSector($sector->unique_identification_type);
        }
        return $data;
    }

     /**
     * Store admin notification
     * @param
     * @return bool
     */
    public static function storeAdminNotificationForPayment($customer_type,$paid_id)
    {
        $authId = Auth::id();
        $location = '';
        $auth = User::with(['city','state'])->where('id',$authId)->first();
        if(!empty($auth->address))
        {
            $location.= $auth->address.', ';
        }

        if(isset($auth->city->name)){
            $location.=$auth->city->name.', ';
        }

        if(isset($auth->state->name)){
            $location.=$auth->state->name;
        }

        if($customer_type=='Individual'){
           $paid =  StudentPaidFees::where('id',$paid_id)->first();
           if(empty($paid)){
                return false;
           }
           $profileDetail = Students::where('id',$paid->student_id)->first();
           $profileName ='';
           if(!empty($profileDetail)){

                if(!empty($profileDetail->person_name)){
                    $profileName = $profileDetail->person_name;
                }else{
                    $profileName = $profileDetail->aadhar_number;
                }

           }
             if($auth->business_short=='')
           {
           AdminNotification::create([
                    'title'=> $auth->business_name.'('.$auth->role->name.') - has paid due of '.$profileName,
                    'reported_org_id'=>$authId,
                    'customer_type'=>'Individual',
                    'action'=>'Due Paid',
                    'customer_id'=>$paid->student_id,
                    'reported_at'=>$paid->created_at,
                    'created_at'=>Carbon::now(),
                    'status'=>0,
                    'redirect_url'=>'admin/user/records/'.$paid->student_id.'/'.$authId.'/view?notification=1'
                ]);
           }
            else
            {
                 AdminNotification::create([
                    'title'=> $auth->business_short.'('.$auth->role->name.') - has paid due of '.$profileName,
                    'reported_org_id'=>$authId,
                    'customer_type'=>'Individual',
                    'action'=>'Due Paid',
                    'customer_id'=>$paid->student_id,
                    'reported_at'=>$paid->created_at,
                    'created_at'=>Carbon::now(),
                    'status'=>0,
                    'redirect_url'=>'admin/user/records/'.$paid->student_id.'/'.$authId.'/view?notification=1'
                ]);
            }

           return true;
        }if($customer_type=='Business'){
            $paid =  BusinessPaidFees::where('id',$paid_id)->first();
            if(empty($paid)){
                return false;
           }
           $profileDetail = Businesses::where('id',$paid->business_id)->first();
           $profileName ='';
           if(!empty($profileDetail)){
                $profileName = $profileDetail->company_name;
           }
            if($auth->business_short=='')
           {
           AdminNotification::create([
                    'title'=> $auth->business_name.'('.$auth->role->name.') - has paid due of '.$profileName,
                    'reported_org_id'=>$authId,
                    'customer_type'=>'Business',
                    'action'=>'Due Paid',
                    'customer_id'=>$paid->business_id,
                    'reported_at'=>$paid->created_at,
                    'created_at'=>Carbon::now(),
                    'status'=>0,
                    'redirect_url'=>'admin/user/business/'.$paid->business_id.'/'.$authId.'/view?notification=1'
                ]);
       }
       else
       {
          AdminNotification::create([
                    'title'=> $auth->business_short.'('.$auth->role->name.') - has paid due of '.$profileName,
                    'reported_org_id'=>$authId,
                    'customer_type'=>'Business',
                    'action'=>'Due Paid',
                    'customer_id'=>$paid->business_id,
                    'reported_at'=>$paid->created_at,
                    'created_at'=>Carbon::now(),
                    'status'=>0,
                    'redirect_url'=>'admin/user/business/'.$paid->business_id.'/'.$authId.'/view?notification=1'
                ]);

       }
        return true;
        }
        return false;
    }


     /**
     * get admin notification count
     * @param
     * @return bool
     */
    public static function getAdminNotificationCount()
    {
         $alreadyseenIds = AdminNotificationSeen::select('admin_notification_id')->where('user_id',Auth::id())->get()->toArray();
         $count = AdminNotification::whereNotIn('id',$alreadyseenIds)->count();
         return $count;
    }


    /**
     * make all notification seen
     * @param null
     * @return
     */
    public static function makeAdminNotificationSeen()
    {
          $authId = Auth::id();
          $alreadyseenIds = AdminNotificationSeen::select('admin_notification_id')->where('user_id',Auth::id())->get()->toArray();
           $otherList = AdminNotification::select('id')->whereNotIn('id',$alreadyseenIds)->get();
           if($otherList->count()){
               foreach($otherList as $list){
                   AdminNotificationSeen::updateOrCreate(['user_id'=>$authId,'admin_notification_id'=>$list->id],['admin_notification_id'=>$list->id,'user_id'=>$authId]);
                }
           }
    }



    /**
     * check sms limit by day, week, month
     * @param null
     * @return array
     */
    public static function checkSmsDailyLimit($added_by=null){

        $array = [
            'daily_available'=>true,
            'weekly_available'=>true,
            'monthly_available'=>true,
            'daily_limit'=>setting('admin.daily_sms_limit') ? (int)setting('admin.daily_sms_limit') : 0,
            'weekly_limit'=>setting('admin.weekly_sms_limit') ? (int)setting('admin.weekly_sms_limit') : 0,
            'monthly_limit'=>setting('admin.monthly_sms_limit') ? (int)setting('admin.monthly_sms_limit') : 0,
            'daily_available_limit'=>0,
            'weekly_available_limit'=>0,
            'monthly_available_limit'=>0,
        ];
        $today = Carbon::now()->format('Y-m-d');
        $startOfWeek = Carbon::now()->startOfWeek()->format('Y-m-d');
        $endOfWeek = Carbon::now()->endOfWeek()->format('Y-m-d');

        $startOfMonth = Carbon::now()->startOfMonth()->format('Y-m-d');
        $endOfMonth = Carbon::now()->endOfMonth()->format('Y-m-d');


        if(setting('admin.daily_sms_limit')!==null){

            $data = DuesSmsLog::select('id')->whereDate('created_at',$today)->where('status',1);
            if(!empty($added_by)){
                $data = $data->where('added_by',$added_by);
            }
            $data = $data->get();
            if(setting('admin.daily_sms_limit')!=0 && setting('admin.daily_sms_limit')<=$data->count()){
                $array['daily_available']=false;
            }
        }

        if(setting('admin.weekly_sms_limit')!==null){
            $data = DuesSmsLog::select('id')->whereBetween('created_at',[$startOfWeek,$endOfWeek])->where('status',1);
            if(!empty($added_by)){
                $data = $data->where('added_by',$added_by);
            }
            $data = $data->get();
            if(setting('admin.weekly_sms_limit')!=0 && setting('admin.weekly_sms_limit')<=$data->count()){
                $array['weekly_available']=false;
            }
        }

        if(setting('admin.monthly_sms_limit')!==null){
            $data = DuesSmsLog::select('id')->whereBetween('created_at',[$startOfMonth,$endOfMonth])->where('status',1);
            if(!empty($added_by)){
                $data = $data->where('added_by',$added_by);
            }
            $data = $data->get();
            if(setting('admin.monthly_sms_limit')!=0 && setting('admin.monthly_sms_limit')<=$data->count()){
                $array['monthly_available']=false;
            }
        }

        return $array;
    }



    public static function replaceTextInSmsTemplate($templateId,$smsFor,$authUser=null,$withingDate='',$withingDays='',$dueData=null){
            $authId = Auth::id();
            $authUser = User::with(['city','state'])->where('id',$authId)->first();
            $smsTemplate = \Config::get('sms_templates.'.$templateId);
            $template_text = $smsTemplate['text'];
             if($authUser->business_short=='')
           {
            if(!empty($authUser)){
                $cityName = General::getCityNameById($authUser->city_id);
                if($smsFor=='BUSINESS'){
                    $MemberNameCity = mb_strimwidth($authUser->business_name, 0, 15, "..");
                }elseif($smsFor=='INDIVIDUAL'){
                    $MemberNameCity = mb_strimwidth($authUser->business_name, 0, 15, "..");
                }else{
                    $MemberNameCity = '';
                }
                if(!empty($cityName)){
                    $cityName = mb_strimwidth($cityName, 0, 10, "..");
                    $MemberNameCity.=', '.$cityName;
                }

                $MemberNameCity = trim($MemberNameCity);
                $template_text = str_replace("<Member name, city>",$MemberNameCity,$template_text);
            }
              }
        else
        {

               if(!empty($authUser)){
                $cityName = General::getCityNameById($authUser->city_id);
                if($smsFor=='BUSINESS'){
                    $MemberNameCity = mb_strimwidth($authUser->business_short, 0, 15, "..");
                }elseif($smsFor=='INDIVIDUAL'){
                    $MemberNameCity = mb_strimwidth($authUser->business_short, 0, 15, "..");
                }else{
                    $MemberNameCity = '';
                }
                if(!empty($cityName)){
                    $cityName = mb_strimwidth($cityName, 0, 10, "..");
                    $MemberNameCity.=', '.$cityName;
                }

                $MemberNameCity = trim($MemberNameCity);
                $template_text = str_replace("<Member name, city>",$MemberNameCity,$template_text);
            }
        }

            if(!empty($dueData)){
                $diffDays = Self::diffInDays($dueData->due_date);
                $template_text = str_replace("< days>",$diffDays,$template_text); // exact overdue days
                // overdue days bucket
                if($diffDays>=1 && $diffDays<=30){
                    $diffDays = 1;
                }elseif($diffDays>=31 && $diffDays<=60){
                    $diffDays = 30;
                }elseif($diffDays>=61 && $diffDays<=90){
                    $diffDays = 60;
                }elseif($diffDays>=91 && $diffDays<=180){
                    $diffDays = 90;
                }elseif($diffDays>=181){
                    $diffDays = 180;
                }else{
                    $diffDays='';
                }
                if($diffDays){
                    $template_text = str_replace("< >",$diffDays,$template_text); //slab days
                }else{
                    $template_text = str_replace("< >",0,$template_text); //slab days
                    $template_text = str_replace("+days",' days',$template_text);
                }
            }
            if(!empty($withingDate)){
                $withingDate = Carbon::createFromFormat('Y-m-d', $withingDate);
                $template_text = str_replace("<Date>",$withingDate->format('d/m/Y'),$template_text);
            }
            $checkMyReportUrlSortUrl = \Config::get('app.check_my_report_sort_url');
            $template_text.= ' '.$checkMyReportUrlSortUrl;

            return $template_text;

    }

    public static function enc_config(){
        $config = \Config::get('encryption');
        return $config;
    }
    public static function encrypt($text){

        // $plaintext = strtolower($text);
           $plaintext = $text;

        /*if(empty($plaintext)){
            return NULL;
        }*/
        $config = Self::enc_config();
        $password = $config['key'];
        $method = $config['method'];

        $key = substr(hash('sha256', $password, true), 0, 32);

        // IV must be exact 16 chars (128 bit)
        $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

        $encrypted = base64_encode(openssl_encrypt($plaintext, $method, $key, OPENSSL_RAW_DATA, $iv));
        return $encrypted;
    }
    public static function decrypt($text){
        $plaintext = $text;
        /*if(empty($plaintext)){
            return null;
        }*/
        $config = Self::enc_config();
        $password = $config['key'];
        $method = $config['method'];
        $key = substr(hash('sha256', $password, true), 0, 32);

        // IV must be exact 16 chars (128 bit)
        $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

        $decrypted = openssl_decrypt(base64_decode($plaintext), $method, $key, OPENSSL_RAW_DATA, $iv);
        return $decrypted;
    }
    /**
       * consent response time is valid for 10 minutus only.
       * checking response validity.
       * so admin may have 10 minutes only to view data after response is generated.
       * response_valid_at is datetime including 10 minutes.
    */
    public static function checkConsentResponseTimeValidation($addedBy,$contactPhone,$personNameOrUniqueIdentificationNumber,$customerType){
        $currentTime = Carbon::now();
        if(empty($personNameOrUniqueIdentificationNumber)){
          $personNameOrUniqueIdentificationNumber = NULL;
        }
        if($customerType=='INDIVIDUAL'){
            $check = ConsentRequest::where('customer_type','INDIVIDUAL')
                ->where('contact_phone',Self::encrypt($contactPhone));
                if($personNameOrUniqueIdentificationNumber){
                    $check = $check->where('person_name',Self::encrypt($personNameOrUniqueIdentificationNumber));
                }else{
                    $check = $check->whereNull('person_name');
                }

        }else{
            $check = ConsentRequest::where('customer_type','BUSINESS')
                ->where('concerned_person_phone',Self::encrypt($contactPhone));
                if($personNameOrUniqueIdentificationNumber){
                    $check = $check->where('unique_identification_number',Self::encrypt($personNameOrUniqueIdentificationNumber));
                }else{
                    $check = $check->whereNull('unique_identification_number');
                }

        }

        $check = $check->where('added_by',$addedBy)
            ->whereIn('status',[1,3,4])
            //->where('status',3) // consent accepted
            //->where('response_valid_at','>=',$currentTime)
            ->orderBy('id','DESC')
            ->first();

        if($check){
            if($check->status==3 && $check->response_valid_at >= $currentTime){
                return true;
            }else{
                return false;
            }
        }
        return false;
    }

    /**
       * Admin have only 2 chance to raise consent(only considering opt delivered status = 1 ) for same mobile number in last spefified hours...
       * individual and bsiness will be considered as different consent.
    */
    public static function requestConsentEligible($addedBy,$contactPhone,$customerType,$status=null,$report){

        $request_consent_block_for_hour = setting('admin.request_consent_block_for_hour') ? (int)setting('admin.request_consent_block_for_hour') : 0 ;

        $currentDatetime = Carbon::now();
        $beforeDateTime = Carbon::now()->subHours($request_consent_block_for_hour);
       // dd($beforeDateTime);
        if($customerType=='INDIVIDUAL'){
            $consentRequest = ConsentRequest::where('contact_phone',General::encrypt($contactPhone))
                ->where('customer_type','INDIVIDUAL');
        }else{
            $consentRequest = ConsentRequest::where('concerned_person_phone',General::encrypt($contactPhone))
                ->where('customer_type','BUSINESS');
        }
        $consentRequest = $consentRequest->where('report',$report)->where('created_at','<=',$currentDatetime)->where('created_at','>=',$beforeDateTime);
        if(empty($status)){
            $consentRequest = $consentRequest->where('status',1);
        }else{
            $consentRequest = $consentRequest->where('status',3);
        }
        $consentRequest = $consentRequest->where('added_by',$addedBy)
        ->orderBy('id','DESC')
        ->get();

        return $consentRequest;
    }

    /**
    *
    *
    */
    public static function requestConsentCheckStatus($addedBy,$contactPhone,$personNameOrUniqueIdentificationNumber=null,$customerType,$ignoreRequestConsentBlockForHour=NULL){

        $request_consent_block_for_hour = setting('admin.request_consent_block_for_hour') ? (int)setting('admin.request_consent_block_for_hour') : 0 ;
        $currentDatetime = Carbon::now();
        $beforeDateTime = Carbon::now()->subHours($request_consent_block_for_hour);
        if(empty($personNameOrUniqueIdentificationNumber)){
          $personNameOrUniqueIdentificationNumber = NULL;
        }

       if($customerType=='INDIVIDUAL'){
            $consentRequest = ConsentRequest::where('customer_type','INDIVIDUAL')
                ->where('contact_phone',Self::encrypt($contactPhone));
                if($personNameOrUniqueIdentificationNumber){
                    $consentRequest = $consentRequest->where('person_name',Self::encrypt($personNameOrUniqueIdentificationNumber));
                }else{
                    $consentRequest = $consentRequest->whereNull('person_name');
                }

        }else{
            $consentRequest = ConsentRequest::where('customer_type','BUSINESS')
                ->where('concerned_person_phone',Self::encrypt($contactPhone));
                if($personNameOrUniqueIdentificationNumber){
                    $consentRequest = $consentRequest->where('unique_identification_number',Self::encrypt($personNameOrUniqueIdentificationNumber));
                }else{
                    $consentRequest = $consentRequest->whereNull('unique_identification_number');
                }

        }

        $consentRequest = $consentRequest->where('created_at','<=',$currentDatetime);
        if(empty($ignoreRequestConsentBlockForHour)){
            $consentRequest = $consentRequest->where('created_at','>=',$beforeDateTime);
            $consentRequest = $consentRequest->whereIn('status',[1,3,4]); // otp delivered, consent approved, consent deny
        }else{
            $consentRequest = $consentRequest->where('status',3);
        }

        $consentRequest = $consentRequest->where('added_by',$addedBy)
        ->orderBy('id','DESC')
        ->first();
        return $consentRequest;

    }

    public static function consentPayment($addedBy,$contactPhone,$personNameOrUniqueIdentificationNumber,$customerType){
        $consent_payment_successful_valid_for_in_year = setting('admin.consent_payment_successful_valid_for_in_year') ? (int)setting('admin.consent_payment_successful_valid_for_in_year') : 7 ;

        $currentTime = Carbon::now();
        $beforeDateTime = Carbon::now()->subYear($consent_payment_successful_valid_for_in_year);

        if(empty($personNameOrUniqueIdentificationNumber)){
          $personNameOrUniqueIdentificationNumber = NULL;
        }
        if($customerType=='INDIVIDUAL'){
            $check = ConsentPayment::where('customer_type','INDIVIDUAL')
                ->where('contact_phone',Self::encrypt($contactPhone));
                if($personNameOrUniqueIdentificationNumber){
                    $check = $check->where('person_name',Self::encrypt($personNameOrUniqueIdentificationNumber));
                }else{
                    $check = $check->whereNull('person_name');
                }

        }else{
            $check = ConsentPayment::where('customer_type','BUSINESS')
                ->where('concerned_person_phone',Self::encrypt($contactPhone));
                if($personNameOrUniqueIdentificationNumber){
                    $check = $check->where('unique_identification_number',Self::encrypt($personNameOrUniqueIdentificationNumber));
                }else{
                    $check = $check->whereNull('unique_identification_number');
                }

        }

        $check = $check->where('added_by',$addedBy)
            ->where('updated_at','>=',$consent_payment_successful_valid_for_in_year)
            ->where('status',4)
            ->orderBy('id','DESC')
            ->first();

        return $check;

  }

  public static function updateConsentRequestSearchedAtToLatest($addedBy,$contactPhone,$personNameOrUniqueIdentificationNumber=null,$customerType){

        if(empty($personNameOrUniqueIdentificationNumber)){
          $personNameOrUniqueIdentificationNumber = NULL;
        }

        if($customerType=='INDIVIDUAL'){
            $consentRequest = ConsentRequest::where('customer_type','INDIVIDUAL')
                ->where('contact_phone',Self::encrypt($contactPhone));
                if($personNameOrUniqueIdentificationNumber){
                    $consentRequest = $consentRequest->where('person_name',Self::encrypt($personNameOrUniqueIdentificationNumber));
                }else{
                    $consentRequest = $consentRequest->whereNull('person_name');
                }

        }else{
            $consentRequest = ConsentRequest::where('customer_type','BUSINESS')
                ->where('concerned_person_phone',Self::encrypt($contactPhone));
                if($personNameOrUniqueIdentificationNumber){
                    $consentRequest = $consentRequest->where('unique_identification_number',Self::encrypt($personNameOrUniqueIdentificationNumber));
                }else{
                    $consentRequest = $consentRequest->whereNull('unique_identification_number');
                }

        }

        $consentRequest = $consentRequest->where('added_by',$addedBy)->orderBy('id','DESC')->first();
        if($consentRequest){
            $consentRequest->searched_at = Carbon::now();
            $consentRequest->update();
        }
  }


  public static function makeConsentPaymentFailForcefully($consentPaymentId){
    ConsentPayment::where('id',$consentPaymentId)->update([
        'status'=>5,
        'updated_at'=>Carbon::now(),
        'raw_response'=>json_encode([
            'FAILED_FORCEFULLY'=>true
        ])
    ]);

  }

  public static function diffInDays($date){

    $diffInDays =  Carbon::parse($date)->diffInDays(null,false);
    if($diffInDays>=0){
        return $diffInDays;
    }
    return 0;
  }

  public static function getMemberIdArrayToSkipCollectionPayment(){
    $string = setting('admin.memberid_eligible_to_skip_collection_payment') ? setting('admin.memberid_eligible_to_skip_collection_payment') : '';
    if(empty($string)){
        return [];
    }
    return  explode(',',$string);
  }

  public static function checkMemberEligibleToSkipCollectionPayment(){
    $array = Self::getMemberIdArrayToSkipCollectionPayment();
    return in_array(Auth::id(),$array);
  }

  public static function getMemberIdArrayToIndividualCustomCreditReport(){
    $string = setting('admin.memberid_eligible_to_individual_custom_credit_report') ? setting('admin.memberid_eligible_to_individual_custom_credit_report') : '';
    if(empty($string)){
        return [];
    }
    return  explode(',',$string);
  }

  public static function checkMemberEligibleToIndividualCustomCreditReport(){
    $array = Self::getMemberIdArrayToIndividualCustomCreditReport();
    return in_array(Auth::id(),$array);
  }

  /*
  public static function memberid_skip_email_notifications_for_dues(){
    $string = setting('admin.memberid_skip_email_notifications_for_dues') ? setting('admin.memberid_skip_email_notifications_for_dues') : '';
    if(empty($string)){
        return [];
    }
    return  explode(',',$string);
  }

  public static function Checkmemberid_skip_email_notifications_for_dues(){
    $array = Self::memberid_skip_email_notifications_for_dues();
    return in_array(Auth::id(),$array);
  }*/



  public static function getMemberIdArrayToEditDueAmount(){
    $string = setting('admin.member_id_eligible_to_edit_due_amount') ? setting('admin.member_id_eligible_to_edit_due_amount') : '';
    if(empty($string)){
        return [];
    }
    return  explode(',',$string);
  }

  public static function checkMemberEligibleToEditDueAmount(){
    $array = Self::getMemberIdArrayToEditDueAmount();
    return in_array(Auth::id(),$array);
  }


  public static function getMemberIdArrayToUploadPaymentMasterFile(){
    $string = setting('admin.memberid_eligible_to_upload_payment_master_file') ? setting('admin.memberid_eligible_to_upload_payment_master_file') : '';
    if(empty($string)){
        return [];
    }
    return  explode(',',$string);
  }

  public static function checkMemberEligibleToUploadPaymentMasterFile(){
    $array = Self::getMemberIdArrayToUploadPaymentMasterFile();
    return in_array(Auth::id(),$array);
  }

  public static function ind_money_format($number){
        $decimal = (string)($number - floor($number));
        $money = floor($number);
        $length = strlen($money);
        $delimiter = '';
        $money = strrev($money);

        for($i=0;$i<$length;$i++){
            if(( $i==3 || ($i>3 && ($i-1)%2==0) )&& $i!=$length){
                $delimiter .=',';
            }
            $delimiter .=$money[$i];
        }

        $result = strrev($delimiter);
        $decimal = preg_replace("/0\./i", ".", $decimal);
        $decimal = substr($decimal, 0, 3);

        if( $decimal != '0'){
            $result = $result.$decimal;
        }
        return $result;
    }

    public static function totalNumberDueReportedForIndividualBusiness($userId){
        $individual = Students::where('added_by',$userId)->whereNull('deleted_at')->groupBy('id')->get()->count();
        $business = Businesses::where('added_by',$userId)->whereNull('deleted_at')->groupBy('id')->get()->count();

        return $individual + $business ;
    }

    public static function totalNumberofIndividualBusiness($userId){
        $individual = Students::where('added_by',$userId)->whereNull('deleted_at')->get()->count();
        $business = Businesses::where('added_by',$userId)->whereNull('deleted_at')->get()->count();

        return $individual + $business ;
    }

    public static function getRecordsLevelBusinessDuesCount($userId)
    {
        $business = BusinessDueFees::where('added_by',$userId)->whereNull('deleted_at')->get()->count();
        return $business;
    }

    public static function getRecordsLevelIndividualDuesCount($userId)
    {
        $individual = StudentDueFees::where('added_by',$userId)->whereNull('deleted_at')->get()->count();
        return $individual;
    }

    public static function usersTotalDueReportedFilter($search_filter,$search_value){
        $users = User::select('id')->get();
        $ids = [];
        if($users->count()){
            foreach ($users as $user) {
                $individual = Students::select('id')->where('added_by',$user->id)->whereNull('deleted_at')->get()->count();
                $business = Businesses::select('id')->where('added_by',$user->id)->whereNull('deleted_at')->get()->count();
                $total = $individual + $business;
                if($search_filter=='=' && $total==$search_value){

                    array_push($ids,$user->id);
                }elseif($search_filter=='<' && $total<$search_value){
                    array_push($ids,$user->id);
                }elseif($search_filter=='>' && $total>$search_value){
                    array_push($ids,$user->id);
                }
            }
        }
        return $ids;

    }
    public static function upgrade_check(){
        if(Auth::user()->user_pricing_plan->pricing_plan_id==4)
            return false;

        if(strtotime(Auth::user()->user_pricing_plan->end_date)<strtotime(date('Y-m-d H:i:s')))
            return true;
        $count=General::totalNumberofIndividualBusiness(Auth::user()->id);

        if($count >= Auth::user()->user_pricing_plan->pricing_plan->free_customer_limit)
        {
            return true;
        }else{
            return false;
        }

    }

    public static function getInquiryPurpose($value){
        $arr = [
            '00' => 'Other',
            '01' => 'Auto Loan',
            '02' => 'Housing Loan',
            '03' => 'Property Loan',
            '04' => 'Loan against Shares/Securities',
            '05' => 'Personal Loan',
            '06' => 'Consumer Loan',
            '07' => 'Gold Loan',
            '08' => 'Education Loan',
            '09' => 'Loan to Professional',
            '10' => 'Credit Card',
            '11' => 'Lease',
            '12' => 'Overdraft',
            '13' => 'Two-wheeler Loan',
            '14' => 'Non-Funded Credit Facility',
            '15' => 'Loan Against Bank Deposits',
            '16' => 'Fleet Card',
            '17' => 'Commercial Vehicle Loan',
            '18' => 'Telco - Wireless',
            '19' => 'Telco - Broadband',
            '20' => 'Telco - Landline',
            '31' => 'Secured Credit Card',
            '32' => 'Used Car Loan',
            '33' => 'Construction Equipment Loan',
            '34' => 'Tractor Loan',
            '35' => 'Corporate Credit Card',
            '3A' => 'Auto Lease',
            '51' => 'Business Loan',
            '52' => 'Business Loan-Priority Sector-Small Business',
            '53' => 'Business Loan - Priority Sector- Agriculture',
            '54' => 'Business Loan - Priority Sector- Others',
            '55' => 'Business Non-Funded Credit Facility',
            '56' => 'Business Non-Funded Credit Facility - Priority Sector - Small Business',
            '57' => 'Business Non-Funded Credit Facility - Priority Sector - Agriculture',
            '58' => 'Business Non-Funded Credit Facility - Priority Sector - Other',
            '59' => 'Business Loan Against Bank Deposits',
            '60' => 'Staff Loan',
            '8A' => 'Disclosure',
            '0E' => 'MicroFinance Business Loan',
            '1E' => 'MicroFinance Personal Loan',
            '2E' => 'MicroFinance Housing Loan',
            '3E' => 'MicroFinance Others',

        ];
        return isset($arr[$value]) ? $arr[$value] : $value;
    }

    public static function getMaskedDOB($value){
        $value = preg_replace('/[0-9]/', '*', $value);
        return $value;
    }

    public static function getMaskedAddress($value){
        $value = preg_replace('/[a-zA-Z0-9]/', '*', $value);
        return $value;
    }

    public static function getMaskedCharacterAndNumber($value){
        $value = preg_replace('/[a-zA-Z0-9]/', '*', $value);
        return $value;
    }

    public static function getMaskedCharacters($value){
        $value = preg_replace('/[a-zA-Z]/', '*', $value);
        return $value;
    }

    public static function getMaskedPhone($value){
        $value = str_repeat('*', strlen($value) - 2) . substr($value, -2);
        return $value;
    }

    public static function getMaskedPAN($str){
        $len = strlen($str);
        if($len >= 2){
            return substr($str, 0, 1).str_repeat('*', $len - 2).substr($str, $len - 1, 1);
        }
        return substr($str, 0, 1).str_repeat('*', 0).substr($str, $len - 1, 1);
    }

    public static function pricing_plan_data($plan_id){
        $pricing_plan= PricingPlan::where('id',$plan_id)->first();
        if(empty($pricing_plan)){
            $pricing_plan=array('membership_plan_price'=>0,'free_customer_limit'=>0,'collection_fee'=>0,'consent_recordent_report_gst'=>0,'name'=>'');
            $pricing_plan=(object) $pricing_plan;

        }
        return $pricing_plan;
    }

	public static function offer_codes_curl($postData,$endPoint) {
		$OnecodeToken = ApiTokens::where('api_token_name','onecode')->first();
		$verifyCodeHeaders = array(
									"access_token:$OnecodeToken->access_token",
									"cache-control: no-cache",
									"content-type: application/json"
									);

		$curl_verify = curl_init();

		curl_setopt_array($curl_verify, array(
		CURLOPT_URL => "http://partner.staging.onecode.in/$endPoint",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => json_encode($postData),
		CURLOPT_HTTPHEADER => $verifyCodeHeaders,
		));

		$response = curl_exec($curl_verify);
		$err = curl_error($curl_verify);

		curl_close($curl_verify);

		if ($err) {
            Log::debug("Error in onecode curl api.");
		    return  0;
		} else {

			$checkInvalidToken = json_decode($response);
			if($checkInvalidToken!=""){
    			if($checkInvalidToken->httpCode == 401) {
    				$returnResonse = self::offer_codes_curl_expires($postData,$endPoint);
    				return $returnResonse;
    			} else {
    				return $response;
    			}
    		} else {
                Log::debug("onecode curl api, checkInvalidToken is empty.");
    			return $response;
    		}

		}
	}

	public static function offer_codes_curl_expires($postData,$endPoint) {


		$curl = curl_init();
		$configDetails = \Config::get('custom_configs.oneCodeConfig');
		/*$postData = array("clientId"=>"Recordent","clientSecret"=>"3B30B956C3583D367C920EDBE1B3EE55");*/
		$tokenHeaders = array(
		"cache-control: no-cache",
		"content-type: application/json"
		);
		curl_setopt_array($curl, array(
		CURLOPT_URL => "http://partner.staging.onecode.in/generate-token",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => json_encode($configDetails),
		CURLOPT_HTTPHEADER => $tokenHeaders,
		));

		$response_token = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
            Log::debug("onecode curl api - Error in offer_codes_curl_expires".print_r($err, true));
		    echo "cURL Error #:" . $err;
		}
		$codeTokenResonse = json_decode($response_token);

		$access_token = $codeTokenResonse->data->access_token;


		ApiTokens::where('api_token_name', 'onecode')->update(array('access_token'=>$access_token));

		$OnecodeToken = ApiTokens::where('api_token_name','onecode')->first();


		$verifyCodeHeaders = array(
									"access_token:$access_token",
									"cache-control: no-cache",
									"content-type: application/json"
									);

		$curl_verify = curl_init();

		curl_setopt_array($curl_verify, array(
		CURLOPT_URL => "http://partner.staging.onecode.in/$endPoint",

		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => json_encode($postData),
		CURLOPT_HTTPHEADER => $verifyCodeHeaders,
		));

		$response = curl_exec($curl_verify);
		$err = curl_error($curl_verify);

		curl_close($curl_verify);

		if ($err) {
            Log::debug("onecode curl api - Error");
		    return  0;
		} else {
		    return $response;
		}
	}

    /**
    * add_to_debug_log - To Log any debug message with member id.
    * @param $member_id - Integer - Member or customer ID
    * @param $message - String
    */
    public static function add_to_debug_log($member_id=null, $message=null)
    {

        $message = "Member ID = ".$member_id.", ".$message;

        Log::debug($message);
    }

    /**
    * add_to_subscription_debug_log - Log subscription related module flow.
    * @param $member_id - Integer - Member or customer ID
    * @param $plan_id - Integer - 1-Basic|2-Premium|3-Executuive|4-Corporate
    */
    public static function add_to_subscription_debug_log($member_id, $plan_id)
    {

        $message = "Member ID = ".$member_id.", Subscription is successful. plan_id = ".$plan_id;

        Log::debug("[Subscription_Log] - ".$message);
    }

    /**
    * add_to_payment_debug_log - add debug logs in laravel log file.
    * @param $member_id - Integer - Member or customer ID
    * @param $payment_step - Integer
    * 1= Initiated, 2=pending, 3=aborted, 4=success, 5= failed
    */
    public static function add_to_payment_debug_log($member_id, $payment_step=null)
    {
        $message='';
        if (isset($member_id)) {

            switch ($payment_step) {
                case '1':
                    $message = "Customer/Member ID = ".$member_id.", Payment has been initiated.";
                    break;

                case '2':
                    $message = "Customer/Member ID = ".$member_id.", Payment is in progress.";
                    break;

                case '3':
                    $message = "Customer/Member ID = ".$member_id.". Payment Aborted.";
                    break;

                case '4':
                    $message = "Customer/Member ID = ".$member_id.", Payment is successful.";
                    break;

                case '5':

                    $message = "Customer/Member ID = ".$member_id.", Payment failed.";
                    break;

                default:
                    $message = "Customer/Member ID = ".$member_id. " Something went wrong.";
                    break;
            }
        }

        Log::debug("[Payment_Log] - ".$message);
    }

    /**
    * triggerExceptionMail - HttpExceptions - 404,500 errors
    * @param $exception - Object of Class Exception
    */
    public static function triggerExceptionMail($exception)
    {
        $fe = FlattenException::create($exception);
        Log::debug("error url =".Request::fullUrl());

        $env_type = Config('app.env');

        if ($env_type == "local") {
            $env_type = "Test";
        }

        $subject = ucfirst($env_type)." Server - ".$fe->getStatusCode();

        try{
            SendMail::send('errors.email_alert', [
                'getStatusCode' => $fe->getStatusCode(),
                'getMessage' => $fe->getMessage(),
                'getFile' => $fe->getFile(),
                'getLine' => $fe->getLine(),
                'error_url' => Request::fullUrl()
            ], function($message) use ($subject) {
                $message->to("dev@recordent.com", "Recordent Development Team")
                ->subject($subject);
            });
        }catch(JWTException $exception){
            $this->serverstatuscode = "0";
            $this->serverstatusdes = $exception->getMessage();
        }
    }

	public static function generatePayuForm($array){

        $array['productinfo'] = 'payment';
        $array['udf5'] = $array['udf5'] ?? '';
        $array['firstname'] = $array['firstname'] ?? '';
        $array['Lastname'] = $array['Lastname'] ?? '';
        $array['Zipcode'] = $array['Zipcode'] ?? '';
        $array['email'] = $array['email'] ?? '@';
        $array['phone'] = $array['phone'] ?? '';
        $array['address1'] = $array['address1'] ?? '';
        $array['address2'] = $array['address2'] ?? '';
        $array['city'] = $array['city'] ?? '';
        $array['state'] = $array['state'] ?? '';
        $array['country'] = $array['country'] ?? '';
        $array['Pg'] = $array['Pg'] ?? '';
        $array['state'] = $array['state'] ?? '';
      //  dd($array);
        $environment = \Config::get('app.payu_environment');
        $key = \Config::get('app.payu_merchant_key');
        $salt = \Config::get('app.payu_merchant_salt');
        if($environment=='live'){
            $action = 'https://secure.payu.in/_payment';
        }else{
            $action = 'https://test.payu.in/_payment';
        }

       //dd($array,$action,$key,$salt);

        $hash=hash('sha512', $key.'|'.$array['txnid'].'|'.$array['amount'].'|'.$array['productinfo'].'|'.$array['firstname'].'|'.$array['email'].'|||||||||||'.$salt);

        $html = '<form action="'.$action.'" id="payment_form_submit" method="post">
            <input type="hidden" id="udf5" name="udf5" value="'.$array['udf5'].'" />
            <input type="hidden" id="surl" name="surl" value="'.$array['surl'].'" />
            <input type="hidden" id="furl" name="furl" value="'.$array['surl'].'" />
            <input type="hidden" id="curl" name="curl" value="'.$array['surl'].'" />
            <input type="hidden" id="key" name="key" value="'.$key.'" />
            <input type="hidden" id="txnid" name="txnid" value="'.$array['txnid'].'" />
            <input type="hidden" id="amount" name="amount" value="'.$array['amount'].'" />
            <input type="hidden" id="productinfo" name="productinfo" value="'.$array['productinfo'].'" />
            <input type="hidden" id="firstname" name="firstname" value="'.$array['firstname'].'" />
            <input type="hidden" id="Lastname" name="Lastname" value="'.$array['Lastname'].'" />
            <input type="hidden" id="Zipcode" name="Zipcode" value="'.$array['Zipcode'].'" />
            <input type="hidden" id="email" name="email" value="'.$array['email'].'" />
            <input type="hidden" id="phone" name="phone" value="'.$array['phone'].'" />
            <input type="hidden" id="address1" name="address1" value="'.$array['address1'].'" />
            <input type="hidden" id="address2" name="address2" value="'.(isset($array['address2'])? $array['address2'] : '').'" />
            <input type="hidden" id="city" name="city" value="'.$array['city'].'" />
            <input type="hidden" id="state" name="state" value="'.$array['state'].'" />
            <input type="hidden" id="country" name="country" value="'.$array['country'].'" />
            <input type="hidden" id="Pg" name="Pg" value="'.$array['Pg'].'" />
            <input type="hidden" id="hash" name="hash" value="'.$hash.'" />
            </form>
            <script type="text/javascript">
                document.getElementById("payment_form_submit").submit();
            </script>';
        return $html;

    }


    public static function verifyPayuPayment($array){
        $key = $array['key']?? '';
        if(empty($key)) return false;
        if($key != \Config::get('app.payu_merchant_key')) return false;

        $salt = \Config::get('app.payu_merchant_salt');
        $txnid              =   $array['txnid']??'';
        $amount             =   $array['amount']??'';
        $productInfo        =   $array['productinfo']??'';
        $firstname          =   $array['firstname']??'';
        $email              =   $array['email']??'';
        $udf5               =   $array['udf5']??'';
        $status             =   $array['status']??'';
        $resphash           =   $array['hash']??'';
        //Calculate response hash to verify
        $keyString          =   $key.'|'.$txnid.'|'.$amount.'|'.$productInfo.'|'.$firstname.'|'.$email.'|||||'.$udf5.'|||||';
        $keyArray           =   explode("|",$keyString);
        $reverseKeyArray    =   array_reverse($keyArray);
        $reverseKeyString   =   implode("|",$reverseKeyArray);
        $CalcHashString     =   strtolower(hash('sha512', $salt.'|'.$status.'|'.$reverseKeyString)); //hash without additionalcharges

        //check for presence of additionalcharges parameter in response.
        $additionalCharges  =   "";
        If (isset($array["additionalCharges"])) {
           $additionalCharges=$array["additionalCharges"];
           //hash with additionalcharges
           $CalcHashString  =   strtolower(hash('sha512', $additionalCharges.'|'.$salt.'|'.$status.'|'.$reverseKeyString));
        }
        if($resphash != $CalcHashString){
            return false;
        }

        //Comapre status and hash. Hash verification is mandatory.
        $response = $array;
        $response['paymentStatus'] = $status;
        $response['ORDERID'] = $txnid;
        return $response;


    }


	public static function reVerifyPayuPayment($txnid){
        $key = \Config::get('app.payu_merchant_key');
        $salt = \Config::get('app.payu_merchant_salt');

        $environment = \Config::get('app.payu_environment');
        $key = \Config::get('app.payu_merchant_key');
        $salt = \Config::get('app.payu_merchant_salt');
        if($environment=='live'){
            $action = 'https://info.payu.in/merchant/postservice.php?form=2';
        }else{
            $action = 'https://test.payu.in/merchant/postservice.php?form=2';
        }
        $command = "verify_payment"; //mandatory parameter
        $hash_str = $key  . '|' . $command . '|' . $txnid . '|' . $salt ;
        $hash = strtolower(hash('sha512', $hash_str)); //generate hash for verify payment request

        $r = array('key' => $key , 'hash' =>$hash , 'var1' => $txnid, 'command' => $command);

        $qs= http_build_query($r);

        try{
            $c = curl_init();
            curl_setopt($c, CURLOPT_URL, $action);
            curl_setopt($c, CURLOPT_POST, 1);
            curl_setopt($c, CURLOPT_POSTFIELDS, $qs);
            curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($c, CURLOPT_SSLVERSION, 6); //TLS 1.2 mandatory
            curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);
            $o = curl_exec($c);
            if (curl_errno($c)) {
                $sad = curl_error($c);
                throw new \Exception($sad);
            }
            curl_close($c);

            $response = json_decode($o,true);

            if(isset($response['status']))
            {
                // response is in Json format. Use the transaction_detailspart for status
                $response = $response['transaction_details'];
                $response = $response[$txnid];
                $response['paymentStatus'] = $response['status'];
                $response['ORDERID'] = $txnid;
                return $response;

            }
            else {
                return false;
            }
        }catch (\Exception $e){
            return false;
        }
    }

    public static function maxlength($key=null)
    {
        $array = [
            'email'=>60,
            'name' => 80
        ];

        if(empty($key)){
            return '';
        }

        return $array[$key] ?? '';
    }

    public static function storeAdminNotificationForProfile($names,$requestData,$userId)
    {
        if(!isset($userId)){

            $userId = Auth::id();
        }
        if($requestData!='')
        {
            $names = implode(" ,",$names);
        }
           AdminNotification::create([
                    'title'=> Auth::user()->business_name.' - has updated their ' .$names. '',
                    'reported_org_id'=>$userId,
                    'customer_type'=>'ProfileUpdated',
                    'action'=>'Profile Update',
                    'customer_id'=>$userId,
                    'created_at'=>Carbon::now(),
                    'reported_at'=>Carbon::now(),
                    'status'=>0,
                    'redirect_url'=>'edit-profile/'.$userId


                ]);

        return true;


        return false;
}

   public static function storeAdminNotificationForCustomerProfile($names,$requestData)
    {
        $userId = Auth::id();
        $id= $requestData['id'];
        $person_name= $requestData['person_name'];
           AdminNotification::create([
                    'title'=> Auth::user()->business_name.' - has updated the '.implode(" ,",$names).' of the individual customer '.$person_name ,
                    'reported_org_id'=>$userId,
                    'customer_type'=>'IndividualProfileUpdated',
                    'action'=>'Profile Update',
                    'customer_id'=>$userId,
                    'created_at'=>Carbon::now(),
                    'reported_at'=>Carbon::now(),
                    'status'=>0,
                    'redirect_url'=>'admin/user/records/'.$id.'/'.$userId.'/view'


                ]);

        return true;

        return false;
    }

public static function storeAdminNotificationForBusinessProfile($names,$requestData)
    {
        $userId = Auth::id();
        $id = $requestData['id'];
        $company_name= $requestData['company_name'];
           AdminNotification::create([
                    'title'=> Auth::user()->business_name.' - has updated the '.implode(" ,",$names).' of the business customer '.$company_name   ,
                    'reported_org_id'=>$userId,
                    'customer_type'=>'BusinessProfileUpdated',
                    'action'=>'Profile Update',
                    'customer_id'=>$userId,
                    'created_at'=>Carbon::now(),
                    'reported_at'=>Carbon::now(),
                    'status'=>0,
                    'redirect_url'=>'admin/user/business/'.$id.'/'.$userId.'/view'


                ]);

        return true;


        return false;
}


    public static function generate_magic_url_function( $request, $type,$lastIns_id,$bulkupload)
    {
        /* token created */
        if($type == "individual"){
            $exString="Inv";
        }

        if($type == "business"){
            $exString="Bus";
        }

        $login_id = Auth::id();
        $uniq_id = General::encrypt($login_id.$lastIns_id.$exString);
        $encrpt_res = str_replace("/","",$uniq_id);


        if($type == "individual"){


            if($bulkupload == 'indivExcelBulk'){
                $mobile_number = $request['contact_phone_number'];

            } else if($bulkupload == 'indivSinglerecSkip'){
                $mobile_number = $request->contact_phone;

            } else {
                $mobile_number=$request->input('contact_phone');
            }

            $check_customer_due = Students::where("contact_phone",General::encrypt($mobile_number))->first();
            $unique_url_individual = url('checkmyreport/individual/'.$encrpt_res);


    		$individual = Individuals::where('mobile_number', General::encrypt($mobile_number))
                    		->where('status',1)
                    		->first();

    		if(empty($individual)){

        		$individual = Individuals::create([
        		'mobile_number' => $mobile_number,
        		'customer_type' => 'individual',
        		'created_at' => Carbon::now()
        		]);
    		}

    		$individual = Individuals::where('mobile_number', General::encrypt($mobile_number))
    		->where('status', 1)
            ->first();

            if(!$individual){

    			$error_response = array(
                    'error' => true,
                    'message'=>'No records found this number'
    			);
    			return ;
    		}

    		$individual->otp = NULL;
    		$individual->updated_at = Carbon::now();
    		$individual->update();


    		if(empty($check_customer_due->uniqe_url_individual)){
    			//$smsService = new SmsService();
    			//$message = $unique_url_individual;
    			// $smsResponse = $smsService->sendSms("xxxxxxxxx",$message);
                //$smsResponse = $smsService->sendSms($mobile_number,$message);

    			$check_customer_due->uniqe_url_individual=$unique_url_individual;
    			$check_customer_due->update();
    			return $check_customer_due;
    		}

            return $check_customer_due;

        }

        if($type == "business"){

            if($bulkupload == 'BusinessExcelBulk'){
                $mobile_number=$request['concerned_person_phone'];
                $email = $request['email'];

            } else if( $bulkupload == 'BusinessRecSkip'){
                $mobile_number=$request->concerned_person_phone;
                $email = $request->email;

            } else {
                $mobile_number=$request->input('concerned_person_phone');
                $email = $request->input('email');
            }



            $check_customer_due = Businesses::where("concerned_person_phone",General::encrypt($mobile_number))->first();
            $unique_url_individual=url('checkmyreport/business/'.$encrpt_res);


            $check_business_customer_due = array();
            if ($mobile_number) {
                $check_business_customer_due = Businesses::where("concerned_person_phone", General::encrypt($mobile_number))->first();

                $individual_where_column_name = 'mobile_number';
                $individual_where_column_value = $mobile_number;
            } else {
                if ($email) {
                    $check_business_customer_due = Businesses::where("email", General::encrypt($email))->first();
                    $individual_where_column_name = 'email';
                    $individual_where_column_value = $email;

                    Log::debug('email dues = '.$check_business_customer_due);
                }

            }

            $individual = Individuals::where($individual_where_column_name, General::encrypt($individual_where_column_value))
                                    ->where('status', 1)
                                    ->first();

            if(empty($individual)){
                $individual = Individuals::create([
                    'mobile_number' => $mobile_number ? $mobile_number : 0,
                    'email' => $email,
                    'created_at' => Carbon::now(),
                ]);
            }

            $individual->otp = NULL;
            $individual->updated_at = Carbon::now();
            $individual->update();

            if(empty($check_customer_due->uniqe_url_business))
            {
                //$smsService = new SmsService();
                //$message ='Check my report : '. $unique_url_individual;
                // $smsResponse = $smsService->sendSms("xxxxxxxxx",$message);
               // $smsResponse = $smsService->sendSms($mobile_number,$message);
                $check_customer_due->uniqe_url_business=$unique_url_individual;
                $check_customer_due->update();
                return $check_customer_due ;
            }

            return $check_customer_due ;
        }

    }


    public static function sendMail($individual,$type)
	{

        if($type == "Individual")
        {
            $url=$individual->uniqe_url_individual;
            $subjecttxt="Individual";
        }
        else
        {
            $url=$individual->uniqe_url_business;
            $subjecttxt="Business";
        }

		$magic_url=$url;

		$data = array('magic_url'=>$magic_url,
						 'email'=> $individual['email'],
					//test'email'=> "xxxx@gmail.com",
					'subject'=>''.$subjecttxt.'Checkmy Report');


			Mail::send('checkmyreport',$data,function($message) use ($data) {
				$message->to($data['email'])->subject($data['subject']);
				$message->from('no-reply@recordent.com',$data['subject']);
			});

			if( count(Mail::failures()) > 0 ) {
				echo "There was one or more failures. They were: <br />";
				foreach(Mail::failures() as $email_address) {
					echo " - $email_address <br />";
				}

			}

	}


    public static function businessBulkUploadRules($isAlreadyExistingCustomerr)
    {

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

        if($isAlreadyExistingCustomerr != "Yes")
        {
                $name_max_character= General::maxlength('name');
                $email_max_character= General::maxlength('email');
        $rule= [
		    	'business_name' => 'required|max:'.$name_max_character.'|min:'.$company_name_min_character,
		    	'sector_name' => 'nullable|regex:/^[a-zA-Z&\/\- ]+$/u|max:50',
		    	'unique_identification_number' => 'alpha_num|max:15',
		    	'concerned_person_name' => 'nullable|regex:/^[a-zA-Z. \/\&]+$/u|max:'.$name_max_character,
		    	'concerned_person_designation' => 'nullable|regex:/^[\pL\s\.-]+$/u|max:50',
		    	'concerned_person_phone' => 'numeric|digits:10,starts_with:6,7,8,9',
		    	'concerned_person_alternate_phone' => 'nullable|numeric|digits:10,starts_with:6,7,8,9',
		    	'state' => 'nullable|regex:/^[\pL\s]+$/u|max:50',
		    	'city' => 'nullable|regex:/^[\pL\s]+$/u|max:50',
		    	'pin_code'=> 'nullable|digits:6',
		    	'address'=> 'nullable|string',
		    	'duedate_ddmmyyyy' => 'required|date_multi_format:"d-m-Y","d/m/Y"',
		    	'dueamount' => 'required|numeric|gt:0|min:1|lte:1000000000',
		    	'email'=> 'nullable|max:'.$email_max_character.'|email',
		    	'grace_period'=> 'nullable|integer',
				'invoice_no' => 'nullable|max:40|regex:/^[a-zA-Z0-9.\/\* (),#+-@]+$/u',
				'custom_id' => 'nullable|max:50|regex:/^[a-zA-Z0-9.\/\* (),:;#+-]+$/u',
        'business_type' => 'nullable|regex:/^[a-zA-Z&\/\- ]+$/u|max:50',
		    ];

    }else
       {

        $rule= [
		    	'duedate_ddmmyyyy' => 'required|date_multi_format:"d-m-Y","d/m/Y"',
		    	'dueamount' => 'required|numeric|gt:0|min:1|lte:1000000000',
		       ];
    }

                if ($due_date_old_in_year) {
                    $rule['duedate_ddmmyyyy'] = $rule['duedate_ddmmyyyy'] . '|custom_date_after_or_equal:' . $due_date_old_in_year;
                }

                if ($due_date_max_future_in_year) {
                    $rule['duedate_ddmmyyyy'] = $rule['duedate_ddmmyyyy'] . '|custom_before_date_or_equal:' . $due_date_max_future_in_year;
                }

        return $rule;
    }

    public static function businessBulkUploadMessages($isAlreadyExistingCustomerr)
    {

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

        if($isAlreadyExistingCustomerr != "Yes")
        {
        $ruleMessage=[
		    		'business_name.required'=> 'The Business name can not be empty.',
		    		// 'business_name.string'=> 'The Business name must be a string.',
		    		'business_name.max'=> 'The Business name may not be greater than :max characters.',

		    		'sector_name.required'=> 'The Sector name can not be empty.',
		    		'sector_name.regex'=> 'The Sector name may only contain letters and space.',
		    		'sector_name.max'=> 'The Sector name may not be greater than :max characters.',

		    		'unique_identification_number.required'=> 'The Unique identification number can not be empty.',
		    		'unique_identification_number.string'=> 'The unique identification number must be a string.',
		    		'unique_identification_number.max'=>'The Unique identification number may not be greater than :max characters.',

		    		// 'concerned_person_name.required'=>'The Concerned person name can not be empty.',
		    		'concerned_person_name.regex'=>'The Concerned person name may only contain letters and space.',
		    		'concerned_person_name.max'=> 'The Concerned person name may not be greater than :max characters.',

		    		// 'concerned_person_designation.required'=>'The Concerned person designation can not be empty.',
		    		'concerned_person_designation.regex'=>'The Concerned person designation may only contain letters, dash and space.',
		    		'concerned_person_designation.max'=> 'The Concerned person designation may not be greater than :max characters.',

		    		'concerned_person_phone.required'=>'The Concerned person phone can not be empty.',
		    		'concerned_person_phone.digits'=>'The Concerned person phone must be :digits digits.',
		    		'concerned_person_phone.numeric'=>'The Concerned person phone must be a number.',

		    		'concerned_person_alternate_phone.digits'=>'The Concerned person alternate phone must be :digits digits.',
		    		'concerned_person_alternate_phone.numeric'=>'The Concerned person alternate phone must be a number.',

		    		'state.required'=> 'The State can not be empty.',
		    		'state.regex'=> 'The State name may only contain letters and space.',
		    		'state.max'=> 'The State name may not be greater than :max characters.',

		    		'city.required'=> 'The City can not be empty.',
		    		'city.regex'=> 'The City name may only contain letters and space.',
		    		'city.max'=> 'The City name may not be greater than :max characters.',

		    		'pin_code.string'=>'The Pincode must be a string.',
		    		'pin_code.alpha_num'=>'The Pincode may only contain letters and numbers.',
		    		'pin_code.max'=> 'The Pincode may not be greater than :max characters.',

		    		'address.string'=>'The Address must be a string.',
		    		'duedate_ddmmyyyy.required'=>'The Due date can not be empty',
		    		'duedate_ddmmyyyy.date_multi_format'=>'The Due date must be a valid date.',

		    		'dueamount.required'=>'The Due amount can not be empty.',
		    		'dueamount.numeric'=>'The Due amount  must be a number.',
		    		'dueamount.gt'=>'The Due amount must be greater than :value.',
		    		'dueamount.lte'=>'The Due amount must be less than or equal 1,00,00,00,000',
		    		'dueamount.min' => 'Due amount can not be less than 1.',
		    		'email.email'=>'The Email must be a valid email.',
		    		'grace_period.integer'=>'The Grace period must be a number.',
					'invoice_no.regex'=>'The Invoice contained unallowed characters.',
					'invoice_no.max'=>'The Invoice may not be greater than :max characters.',
					'custom_id.regex'=>'The Custom Id contained unallowed characters.',
					'custom_id.max'=>'The Custom Id may not be greater than :max characters.',
                    'business_type.required'=> 'The Business type can not be empty.',
                    'business_type.regex'=> 'The Business type may only contain letters and space.',
                    'business_type.max'=> 'The Business type may not be greater than :max characters.'

		    	];
    }else{
        $ruleMessage=[
		    		'duedate_ddmmyyyy.required'=>'The Due date can not be empty',
		    		'duedate_ddmmyyyy.date_multi_format'=>'The Due date must be a valid date.',

		    		'dueamount.required'=>'The Due amount can not be empty.',
		    		'dueamount.numeric'=>'The Due amount  must be a number.',
		    		'dueamount.gt'=>'The Due amount must be greater than :value.',
		    		'dueamount.lte'=>'The Due amount must be less than or equal 1,00,00,00,000',
		    		'dueamount.min' => 'Due amount can not be less than 1.',

		    	   ];

    }
    if ($due_date_old_in_year) {
        $ruleMessage['duedate_ddmmyyyy.custom_date_after_or_equal'] = 'The Due date must be a date after or equal to ' . $due_date_old_in_year;
    }

    if ($due_date_max_future_in_year) {
        $ruleMessage['duedate_ddmmyyyy.custom_before_date_or_equal'] = 'The Due date must be a date before or equal to ' . $due_date_max_future_in_year;
    }

        return $ruleMessage;
    }


    public static function validateBusinessBulkUploadData($row, $uniqueUrlCode, $member_id=null,$isAlreadyExistingCustomerr="No"){
        $rows_count = count(array_filter($row));
        if($rows_count > 0){

        $reasons = '';

        if (isset($member_id)) {
            $authId = $member_id;
        } else {
            $authId = Auth::id();
        }
        /*$isAlreadyExistingCustomerr="Yes" Skip basic validations */

        $rule = General::businessBulkUploadRules($isAlreadyExistingCustomerr);
        $ruleMessage = General::businessBulkUploadMessages($isAlreadyExistingCustomerr);
        $validator = Validator::make($row, $rule, $ruleMessage);
        if ($validator->fails()) {
            foreach ($validator->messages()->all() as $error) {
                $reasons .= $error . '<br>';
            }
        }

        if (!empty($row['sector_name'])) {
            $sector = Sector::where('name', '=', $row['sector_name'])->first();
            if ($sector) {
                 $sectorId = $sector->id;
            } else {
                $reasons .= 'The Sector name can not be matched with our database.<br>';
            }
        }

        if (!empty($row['business_type'])) {
            $user_type = UserType::where('name', '=', $row['business_type'])->where('status','1')->first();
            if ($user_type) {
                $userType = $user_type->id;
            } else {
                $reasons .= 'The Business type can not be matched with our database.<br>';
            }
        }

        $stateId = '';
        if (!empty($row['state'])) {
            $state = State::where('name', '=', $row['state'])->first();
            if ($state) {
                $stateId = $state->id;
            } else {
                $reasons .= 'The State can not be matched with our database.<br>';
            }
        }

        $cityId = '';
        if (!empty($row['city'])) {
            if (!empty($stateId)) {
                $city = City::where('name', '=', $row['city'])->where('state_id', $stateId)->first();
                if ($city) {
                    $cityId = $city->id;
                } else {
                    $reasons .= 'The City can not be matched with state with our database.<br>';
                }
            } else {
                $reasons .= 'Due to state, The City can not be matched with our database.<br>';
            }
        }

        if ($reasons != '' && !empty($reasons)) {
            $reasons = trim($reasons, '<br>');
            BusinessBulkUploadIssues::create([
                'unique_url_code' => $uniqueUrlCode,
                'added_by' => $authId,
                'issue' => $reasons,
                'company_name' => $row['business_name'],
                'sector_name' => $row['sector_name'],
                'unique_identification_number' => $row['unique_identification_number_gstin_business_pan'],
                'concerned_person_name' => $row['concerned_person_name'],
                'concerned_person_designation' => $row['concerned_person_designation'],
                'concerned_person_phone' => $row['concerned_person_phone'],
                'concerned_person_alternate_phone' => $row['concerned_person_alternate_phone'],
                'state' => $row['state'],
                'city' => $row['city'],
                'pincode' => $row['pin_code'],
                'address' => $row['address'],
                'due_date' => $row['duedate_ddmmyyyy'],
                'due_amount' => $row['dueamount'],
                'email' => $row['email'],
                'grace_period' => $row['grace_period'],
                'business_type' => $row['business_type'],
                'created_at' => Carbon::now(),
                'invoice_no' => $row['invoice_no']
            ]);

            return true;
        }

        return false;
    }
}

    public static function individualBulkUploadRules(){

        $dob_valid_from = Carbon::now()->subYears(100)->format('d/m/Y');

        $due_date_old_in_year = setting('admin.due_date_old_in_year');
        $due_date_max_future_in_year = setting('admin.due_date_max_future_in_year');

        if ($due_date_old_in_year) {
            $due_date_old_in_year = Carbon::now()->subYears($due_date_old_in_year)->format('d/m/Y');
        }

        if ($due_date_max_future_in_year) {
            $due_date_max_future_in_year = Carbon::now()->addYears($due_date_max_future_in_year)->format('d/m/Y');
        }
		$name_max_character= General::maxlength('name');
		$email_max_character= General::maxlength('email');
        $rule = [
			   'person_name'=>'required|max:'.$name_max_character.'|regex:/^[a-zA-Z. \/\&]+$/u',
			   'contact_phone_number'=>'required|numeric|digits:10|starts_with:6,7,8,9',
			   'aadhar_number'=>'nullable|numeric|digits:6',
			   'dob_ddmmyyyy' => 'nullable|date_multi_format:"d-m-Y","d/m/Y"|custom_before_date_or_equal:'.Carbon::now()->format('d/m/Y').'|custom_date_after_or_equal:'.$dob_valid_from,
			   'father_name'=>'nullable|max:'.$name_max_character.'|regex:/^[a-zA-Z. \/\&]+$/u',
			   'mother_name'=>'nullable|max:'.$name_max_character.'|regex:/^[a-zA-Z. \/\&]+$/u',
			   'duedate_ddmmyyyy' => 'required|date_multi_format:"d-m-Y","d/m/Y"',
			   'dueamount' => 'required|numeric|gt:0|min:1|lte:100000000',
			   'due_note'=>'nullable|string|max:300',
			   'email'=> 'nullable|max:'.$email_max_character.'|email',
			   'grace_period'=> 'nullable|integer',
			   'invoice_no' => 'nullable|max:40|regex:/^[a-zA-Z0-9.\/\* (),#+-@]+$/u',
			   'custom_id' => 'nullable|max:50|regex:/^[a-zA-Z0-9.\/\* (),:;#+-]+$/u'
	];

        if ($due_date_old_in_year) {
            $rule['duedate_ddmmyyyy'] = $rule['duedate_ddmmyyyy'] . '|custom_date_after_or_equal:' . $due_date_old_in_year;
        }

        if ($due_date_max_future_in_year) {
            $rule['duedate_ddmmyyyy'] = $rule['duedate_ddmmyyyy'] . '|custom_before_date_or_equal:' . $due_date_max_future_in_year;
        }
        return $rule;
    }

    public static function individualBulkUploadRuleMessages(){

        $dob_valid_from = Carbon::now()->subYears(100)->format('d/m/Y');

        $due_date_old_in_year = setting('admin.due_date_old_in_year');
        $due_date_max_future_in_year = setting('admin.due_date_max_future_in_year');

        if ($due_date_old_in_year) {
            $due_date_old_in_year = Carbon::now()->subYears($due_date_old_in_year)->format('d/m/Y');
        }

        if ($due_date_max_future_in_year) {
            $due_date_max_future_in_year = Carbon::now()->addYears($due_date_max_future_in_year)->format('d/m/Y');
        }

        $ruleMessage = [
            'person_name.required' => 'The Person name can not be empty.',
            'person_name.max' => 'The Person name may not be greater than :max characters.',
            'person_name.regex' => 'The Person name may only contain letters and space.',

            'contact_phone_number.required' => 'The Contact Phone number can not be empty.',
            'contact_phone_number.digits' => 'The Contact Phone number must be :digits digits.',
            'contact_phone_number.numeric' => 'The Contact Phone number must be a number.',

            'aadhar_number.numeric' => 'The Aadhar number must be a number.',
            'aadhar_number.digits' => 'The Aadhar number must be :digits digits.',

            'dob_ddmmyyyy.date_multi_format' => 'The Dob must be a valid date.',
            'dob_ddmmyyyy.before_or_equal' => 'The Dob must be a date before or equal to :date.',

            'father_name.regex' => 'The Father name may only contain letters and space.',
            'father_name.max' => 'The Father name may not be greater than :max characters.',

            'mother_name.regex' => 'The Mother name may only contain letters and space.',
            'mother_name.max' => 'The Mother name may not be greater than :max characters.',

            'duedate_ddmmyyyy.required' => 'The Due date can not be empty',
            'duedate_ddmmyyyy.date_multi_format' => 'The Due date must be a valid date.',

            'dueamount.required' => 'The Due amount can not be empty.',
            'dueamount.numeric' => 'The Due amount  must be a number.',
            'dueamount.gt' => 'The Due amount must be greater than :value.',
            'dueamount.lte' => 'The Due amount must be less than or equal 1,00,00,000',
            'due_note.max' => 'The Due note may not be greater than :max characters.',
            'email.email' => 'The Email must be a valid email.',
            'grace_period.integer' => 'The Grace period must be a number.',
            'invoice_no.regex' => 'The Invoice contained unallowed characters.',
            'invoice_no.max' => 'The Invoice may not be greater than :max characters.',
            'custom_id.regex'=>'The Custom Id contained unallowed characters.',
            'custom_id.max'=>'The Custom Id may not be greater than :max characters.'

        ];

        $ruleMessage['dob_ddmmyyyy.custom_before_date_or_equal'] = 'The Dob must be a date before or equal to ' . Carbon::now()->format('d/m/Y');
        $ruleMessage['dob_ddmmyyyy.custom_date_after_or_equal'] = 'The Dob must be a date after or equal to ' . $dob_valid_from;

        if ($due_date_old_in_year) {
            $ruleMessage['duedate_ddmmyyyy.custom_date_after_or_equal'] = 'The Due date must be a date after or equal to ' . $due_date_old_in_year;
        }

        if ($due_date_max_future_in_year) {
            $ruleMessage['duedate_ddmmyyyy.custom_before_date_or_equal'] = 'The Due date must be a date before or equal to ' . $due_date_max_future_in_year;
        }

        return $ruleMessage;
    }

    public static function validateIndividualBulkUploadData($row, $uniqueUrlCode, $member_id=null){

        $rows_count = count(array_filter($row));
        if($rows_count > 0){
        $reasons = '';
        if (isset($member_id)) {
            $authId = $member_id;
        } else {
            $authId = Auth::id();
        }

        $rule = General::individualBulkUploadRules();
        $ruleMessage = General::individualBulkUploadRuleMessages();

        $validator = Validator::make($row, $rule, $ruleMessage);
        if ($validator->fails()) {
            foreach ($validator->messages()->all() as $error) {
                $reasons .= $error . '<br>';
            }
        }

        if ($reasons != '' && !empty($reasons)) {

            $reasons = trim($reasons, '<br>');
            IndividualBulkUploadIssues::create([
                'unique_url_code' => $uniqueUrlCode,
                'added_by' => $authId,
                'issue' => $reasons,
                'aadhar_number' => $row['aadhar_number'],
                'contact_phone' => $row['contact_phone_number'],
                'person_name' => $row['person_name'],
                'dob' => $row['dob_ddmmyyyy'],
                'father_name' => $row['father_name'],
                'mother_name' => $row['mother_name'],
                'due_date' => $row['duedate_ddmmyyyy'],
                'due_amount' => $row['dueamount'],
                'due_note' => $row['duenote'],
                'email' => $row['email'],
                'grace_period' => $row['grace_period'],
                'created_at' => Carbon::now(),
                'invoice_no' => $row['invoice_no']
            ]);

            return true;
        }

        return false;
    }
  }

    public static function getFreeCustomersDuesLimit($member_id){

        $records = \App\StudentDueFees::whereNull('deleted_at')
                    ->where('added_by', $member_id)
                    ->groupBy('student_id')
                    ->get();

        $count = $records->count();
        Log::debug('countstudents = '.$count);

        $records = \App\BusinessDueFees::whereNull('deleted_at')
                    ->where('added_by', $member_id)
                    ->groupBy('business_id')->get();

        $countBusiness = $records->count();
        Log::debug('countBusiness = '.$countBusiness);

        $member = User::find($member_id);

       $free_customer_limit = $member->user_pricing_plan->free_customer_limit;

        if (!$member->user_pricing_plan->plan_status || $member->user_pricing_plan->pricing_plan_id == 0) {
            $free_customer_limit = HomeHelper::getMemberPreviousPlanFreeCustomerLimit();
        }

        $remainingCustomer = $free_customer_limit - ($count + $countBusiness);

        if ($remainingCustomer <= 0) {
            $remainingCustomer = 0;
        }

        return $remainingCustomer;
    }

    public static function validateBusinessBulkExcelImportColumns($columnNames, $unique_identification_number){

        if (
            !in_array('business_name', $columnNames) ||
            !in_array('sector_name', $columnNames) ||
            !in_array($unique_identification_number, $columnNames) ||
            !in_array('concerned_person_name', $columnNames) ||
            !in_array('concerned_person_designation', $columnNames) ||
            !in_array('concerned_person_phone', $columnNames) ||
            !in_array('concerned_person_alternate_phone', $columnNames) ||
            !in_array('state', $columnNames) ||
            !in_array('city', $columnNames) ||
            !in_array('pin_code', $columnNames) ||
            !in_array('address', $columnNames) ||
            !in_array('duedate_ddmmyyyy', $columnNames) ||
            !in_array('dueamount', $columnNames) ||
            !in_array('email', $columnNames) ||
            !in_array('grace_period', $columnNames) ||
            !in_array('invoice_no', $columnNames) ||
            !in_array('custom_id', $columnNames) ||
			!in_array('business_type', $columnNames)
        ) {
            return true;
        }

        return false;
    }

    public static function validateBusinessBulkExcelImportColumnsFormat($columnNames, $unique_identification_number){

        if (
            $columnNames[0] != 'business_name' ||
            $columnNames[1] != 'sector_name' ||
            $columnNames[2] != $unique_identification_number ||
            $columnNames[3] != 'concerned_person_name' ||
            $columnNames[4] != 'concerned_person_designation' ||
            $columnNames[5] != 'concerned_person_phone' ||
            $columnNames[6] != 'concerned_person_alternate_phone' ||
            $columnNames[7] != 'state' ||
            $columnNames[8] != 'city' ||
            $columnNames[9] != 'pin_code' ||
            $columnNames[10] != 'address' ||
            $columnNames[11] != 'duedate_ddmmyyyy' ||
            $columnNames[12] != 'dueamount' ||
            $columnNames[13] != 'email' ||
            $columnNames[14] != 'grace_period' ||
            $columnNames[15] != 'invoice_no' ||
            $columnNames[16] != 'business_type' ||
			$columnNames[17] != 'custom_id'
        ) {
            return true;
        }

        return false;
    }

    public static function validateIndividualBulkExcelImportColumns($columnNames)
    {

        if (
            !in_array('aadhar_number', $columnNames) ||
            !in_array('contact_phone_number', $columnNames) ||
            !in_array('person_name', $columnNames) ||
            !in_array('dob_ddmmyyyy', $columnNames) ||
            !in_array('father_name', $columnNames) ||
            !in_array('mother_name', $columnNames) ||
            !in_array('duedate_ddmmyyyy', $columnNames) ||
            !in_array('dueamount', $columnNames) ||
            !in_array('duenote', $columnNames) ||
            !in_array('email', $columnNames) ||
            !in_array('grace_period', $columnNames) ||
            !in_array('invoice_no', $columnNames) ||
            !in_array('custom_id', $columnNames)

        ){
            return true;
        }

        return false;
    }

    public static function validateIndividualBulkExcelImportColumnsFormat($columnNames)
    {
        if (
            $columnNames[0] != 'aadhar_number' ||
            $columnNames[1] != 'contact_phone_number' ||
            $columnNames[2] != 'person_name' ||
            $columnNames[3] != 'dob_ddmmyyyy' ||
            $columnNames[4] != 'father_name' ||
            $columnNames[5] != 'mother_name' ||
            $columnNames[6] != 'duedate_ddmmyyyy' ||
            $columnNames[7] != 'dueamount' ||
            $columnNames[8] != 'duenote' ||
            $columnNames[9] != 'email' ||
            $columnNames[10] != 'grace_period' ||
            $columnNames[11] != 'invoice_no' ||
            $columnNames[12] != 'custom_id'

        ) {
            return true;
        }

        return false;
    }

    /**
    * validate payment status from paytm or payu payment gateway response
    */
    public static function getPaymentStatus($response, $transaction=null)
    {
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

        return $paymentStatus;
    }

    public static function updateAdditionalCustomersLimitPaymentDetails($membership_payment_obj, $payment_response, $paymentStatus) {

        if($authId = session::get('member_id')){
        } else {
            $authId = Auth::user()->id;
        }

        $membership_payment_obj->transaction_id = $payment_response['TXNID'] ?? $payment_response['mihpayid'] ?? '';
        $membership_payment_obj->payment_mode = $payment_response['PAYMENTMODE'] ?? $payment_response['mode'] ?? '';
        $membership_payment_obj->raw_response = json_encode($payment_response);
        $membership_payment_obj->updated_at = Carbon::now();

        if($paymentStatus == 'success'){
            $membership_payment_obj->status = 4;
            $invoice_no = MembershipPayment::where('created_at', '>=', date('Y-m-d 00:00:00'))->where('status', 4)->count();
            $invoice_no = $invoice_no + 1;
            $membership_payment_obj->invoice_id = date('dmY') . sprintf('%07d', $invoice_no);
            General::add_to_payment_debug_log($authId, 4);

        } else if($paymentStatus == 'failed') {
            $membership_payment_obj->status = 5;
            General::add_to_payment_debug_log($authId, 5);
        } else {
            $membership_payment_obj->status = 2;
            General::add_to_payment_debug_log($authId, 2);
        }

        $membership_payment_obj->update();
    }

    public static function insertIntoTempMembershipPayments($order_id, $customer_type, $payment_amount, $pricing_plan_id, $payment_note=null){

        $customer_type = strtoupper($customer_type);

        if(session::get('member_id')){
            $authId = session::get('member_id');
        } else {
            $authId = Auth::id();
        }

        $tempDuePayment = TempMembershipPayment::create([
            'order_id' => $order_id,
            'customer_type' => $customer_type,
            'customer_id' => $authId,
            'pricing_plan_id' => $pricing_plan_id,
            'payment_value' => $payment_amount,
            'created_at' => Carbon::now(),
            'added_by' => $authId,
            'payment_note' => 'Additional Customer Dues',
            'payment_date' => date('Y-m-d'),
        ]);

        return $tempDuePayment;
    }

    public static function insertIntoMembershipPayments($tempDuePayment, $gst_price, $total_collection_value){

        if(session::get('member_id')){
            $authId = session::get('member_id');
        } else {
            $authId = Auth::id();
        }

        $duePayment = MembershipPayment::create([
            'order_id' => $tempDuePayment->order_id,
            'customer_type' => $tempDuePayment->customer_type,
            'customer_id' => $tempDuePayment->customer_id,
            'payment_value' => $tempDuePayment->payment_value,
            'pricing_plan_id' => $tempDuePayment->pricing_plan_id,
            'status' => 1, //initiated
            'created_at' => Carbon::now(),
            'added_by' => $authId,
            'gst_perc' => 18,
            'gst_value' => $gst_price,
            'total_collection_value' => $total_collection_value,
            'invoice_type_id' => 8,
            'particular' => $tempDuePayment->payment_note,
        ]);

        $duePayment->pg_type = setting('admin.payment_gateway_type');
        $duePayment->update();

        return $duePayment;

    }

     public static function sendprepaidinvoices($id){

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
        return redirect()->back()->with(['alert-type' => 'success']);
     }




    public static function storeAdminNotificationForDispute($type,$record_addedby,$student_id,$personName,$Dispute_id)
    {

        $User_Rec=User::where('id',$record_addedby)->first();
        $Dispute=Dispute::where('id',$Dispute_id)->first();

        if($type== 'Individual')
        {
            $custType='Individual';
        }
        else
        {
            $custType='Business';

        }

        if($User_Rec->business_short)
        {
            $member_Name=$User_Rec->business_short;
        }
        else
        {
            $member_Name=$User_Rec->business_name;
        }

        if(empty($personName))
        {
            $personName='';
        }
        if(empty($member_Name))
        {

            $member_Name='';
        }

            AdminNotification::create([
                'title'=>$personName.'('.$custType.') - reported a dispute against  '.$member_Name,
                'reported_org_id'=>'',
                'customer_type'=>$custType,
                'action'=>'Dispute Raised',
                'customer_id'=>$student_id,
                'reported_at'=>$Dispute->created_at,
                'created_at'=>Carbon::now(),
                'status'=>0,
                'redirect_url'=>'admin/dispute/'.$Dispute_id
            ]);



    }

     public static function storeAdminNotificationForPaymentFromCustomer($customer_type,$paid_id)
    {

        if($customer_type=='Individual'){
           $paid =  StudentPaidFees::where('id',$paid_id)->first();
           if(empty($paid)){
                return false;
           }
           $auth = User::with(['city','state'])->where('id',$paid->added_by)->first();
           $profileDetail = Students::where('id',$paid->student_id)->first();
           $profileName ='';
           if(!empty($profileDetail)){

                if(!empty($profileDetail->person_name)){
                    $profileName = $profileDetail->person_name;
                }else{
                    $profileName = $profileDetail->aadhar_number;
                }

           }
             if($auth->business_short=='')
           {
           AdminNotification::create([
                    'title'=>$profileName.'- has paid due of  '.$auth->business_name.'('.$auth->role->name.')',
                    'reported_org_id'=>$auth->id,
                    'customer_type'=>'Individual',
                    'action'=>'Due Paid',
                    'customer_id'=>$paid->student_id,
                    'reported_at'=>$paid->created_at,
                    'created_at'=>Carbon::now(),
                    'status'=>0,
                    'redirect_url'=>'admin/user/records/'.$paid->student_id.'/'.$auth->id.'/view?notification=1'
                ]);
           }
            else
            {
                 AdminNotification::create([
                   'title'=>$profileName.'- has paid due of  '.$auth->business_name.'('.$auth->role->name.')',
                    'reported_org_id'=>$auth->id,
                    'customer_type'=>'Individual',
                    'action'=>'Due Paid',
                    'customer_id'=>$paid->student_id,
                    'reported_at'=>$paid->created_at,
                    'created_at'=>Carbon::now(),
                    'status'=>0,
                    'redirect_url'=>'admin/user/records/'.$paid->student_id.'/'.$auth->id.'/view?notification=1'
                ]);
            }

           return true;
        }if($customer_type=='Business'){
            $paid =  BusinessPaidFees::where('id',$paid_id)->first();
            if(empty($paid)){
                return false;
           }
           $auth = User::with(['city','state'])->where('id',$paid->added_by)->first();

           $profileDetail = Businesses::where('id',$paid->business_id)->first();
           $profileName ='';
           if(!empty($profileDetail)){
                $profileName = $profileDetail->company_name;
           }
            if($auth->business_short=='')
           {
           AdminNotification::create([
                 'title'=>$profileName.'- has paid due of  '.$auth->business_name.'('.$auth->role->name.')',
                    'reported_org_id'=>$auth->id,
                    'customer_type'=>'Business',
                    'action'=>'Due Paid',
                    'customer_id'=>$paid->business_id,
                    'reported_at'=>$paid->created_at,
                    'created_at'=>Carbon::now(),
                    'status'=>0,
                    'redirect_url'=>'admin/user/business/'.$paid->business_id.'/'.$auth->id.'/view?notification=1'
                ]);
       }
       else
       {
          AdminNotification::create([
                   'title'=>$profileName.'- has paid due of  '.$auth->business_name.'('.$auth->role->name.')',
                    'reported_org_id'=>$auth->id,
                    'customer_type'=>'Business',
                    'action'=>'Due Paid',
                    'customer_id'=>$paid->business_id,
                    'reported_at'=>$paid->created_at,
                    'created_at'=>Carbon::now(),
                    'status'=>0,
                    'redirect_url'=>'admin/user/business/'.$paid->business_id.'/'.$auth->id.'/view?notification=1'
                ]);

       }
        return true;
        }
        return false;
    }


    public static function storeAdminNotificationForDisputeFromMemberSide($type,$addedBy,$disputeId,$type_ofAction){


        $User_Rec = User::where('id',$addedBy)->first();
        $Dispute = Dispute::where('id',$disputeId)->first();

        if($type == 'Individual' || $type == 'INDIVIDUAL'){
            $custType='Individual';

            $Rec=students::where('id',$Dispute->customer_id)->first();
            $personName=$Rec->person_name;
        } else {
            $custType='Business';
            $Rec=Businesses::where('id',$Dispute->customer_id)->first();
            $personName=$Rec->concerned_person_name;
        }


        if($User_Rec->business_short){
            $member_Name=$User_Rec->business_short;
        } else {
            $member_Name=$User_Rec->business_name;
        }

        if(empty($personName)){
            $personName='';
        }

        if(empty($member_Name)) {
            $member_Name='';
        }

        if($type_ofAction == 'RECORD_DELETED'){
            $discript = ' - has deleted dispute of ';
            $actionType = 'Delete Dispute';
            $url = '';

        } else if($type_ofAction == 'DISPUTE_REJECTED' ){
            $discript =' - has rejected dispute of ';
            $actionType = 'Rejected Dispute';
            $url ='';
        } else {
            $discript=' - has updated dispute of ';
            $actionType='Update Dispute';
            // $url='admin/dispute/'.$disputeId.'/due-edit';
            $url = '';
        }

        AdminNotification::create([
            'title'=>$member_Name.'  '.$discript.$personName,
            'reported_org_id'=>'',
            'customer_type'=>$custType,
            'action'=>$actionType,
            'customer_id'=>'',
            'reported_at'=>$Dispute->created_at,
            'created_at'=>Carbon::now(),
            'status'=>0,
            'redirect_url'=>$url
        ]);
    }


    public static function getPaymentGatewayFormattedResponseMessage($payment_status_code, $success_message)
    {

        $output = array();

        if ($payment_status_code == 4) {
            $output['alert-type'] = 'success';
            $output['message'] = $success_message;

        } else if ($payment_status_code == 5) {
            $output['alert-type'] = 'error';
            $output['message'] = 'Payment failed.';

        } else if($payment_status_code == 2) {
            $output['alert-type'] = 'info';
            $output['message'] = 'Payment is in progress.';

        } else {
            $output['alert-type'] = 'error';
            $output['message'] = 'Something went wrong';
        }

        return $output;
    }

	public static function process_equifax_request($postData,$endPoint) {

        $tokenHeaders = array(
                            "cache-control: no-cache",
                            "content-type: application/json"
                        );

		$curl = curl_init();
		curl_setopt_array($curl, array(
    		CURLOPT_URL => $endPoint,
    		CURLOPT_RETURNTRANSFER => true,
    		CURLOPT_ENCODING => "",
    		CURLOPT_MAXREDIRS => 10,
    		CURLOPT_TIMEOUT => 30,
    		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    		CURLOPT_CUSTOMREQUEST => "POST",
    		CURLOPT_POSTFIELDS => $postData,
    		CURLOPT_HTTPHEADER => $tokenHeaders,
		));

		$response = curl_exec($curl);
        // Log::debug("raw response = ".print_r($response, true));

		$err = curl_error($curl);
		curl_close($curl);

		if ($err) {
            Log::debug("Error in process_equifax_request curl api = ".print_r($err, true));
		    return  $response;
		} else {

			$equifax_response = json_decode($response);
			return $equifax_response;
		}
	}


	public static function  get_state_code($state_id){

		if(!empty($state_id)){
			 $get_state  = State::where('id',$state_id)->get();
			 $stateName  = [];

			foreach ($get_state as $state){
				$stateName[] =$state->short_code;
			}
			return $stateName[0];
		}else{
			return 0;
		}
	}

	public static function  get_city_name($city_id){

		if(!empty($city_id)){
			$get_city    = City::where('id', $city_id)->get();
			$cityName   = [];
				foreach ($get_city as $city){
					$cityName[] =$city->name;
				}
				return  $cityName[0];
		}else{
			return 0;
		}
	}

	public static function getConsentPaymentByUser($userId){

		//$users = ConsentPayment::table('users')->get();
		/*ConsentPayment::where('customer_type','INDIVIDUAL')
                ->where('contact_phone',Self::encrypt($contactPhone));*/
		$consent_payment = ConsentPayment::where('added_by',$userId)->get();
		return  $consent_payment;
	}

	public static function getNumberOfYearsFromDate($beginDate, $endDate){

		//$date1 = "2007-03-24"; format Dates.
		//$date2 = "2009-06-26";
		$diff = abs(strtotime($endDate) - strtotime($beginDate));
		$years = floor($diff / (365*60*60*24));
		return  $years;
	}

	public static function getPayHisClass($payCode){
		$class_color = "";
		if($payCode!=""){

			switch ($payCode) {
				case "B":
					$class_color = "green";
					break;
				case 0:
					$class_color = "green";
					break;
				case "S":
					$class_color = "red";
					break;
				case 1:
					$class_color = "red";
					break;
				case 2:
					$class_color = "red";
					break;
				case 3:
					$class_color = "red";
					break;
				case 4:
					$class_color = "red";
					break;
				case 5:
					$class_color = "red";
					break;
				case 6:
					$class_color = "pur";
					break;
				case 7:
					$class_color = "orange";
					break;
				case 8:
					$class_color = "bri";
					break;
				case 9:
					$class_color = "black";
					break;
			}
		}
		return $class_color;
	}

	public static function getPayHisText($payCode){

		$text_val = "";
		if($payCode!=""){

			switch ($payCode) {
				case "B":
					$text_val = "";
					break;
				case 0:
					$text_val = "";
					break;
				case "S":
					$text_val = "";
					break;
				case 1:
					$text_val = "";
					break;
				case 2:
					$text_val = "";
					break;
				case 3:
					$text_val = "";
					break;
				case 4:
					$text_val = "";
					break;
				case 5:
					$text_val = "";
					break;
				case 6:
					$text_val = "";
					break;
				case 7:
					$text_val = "";
					break;
				case 8:
					$text_val = "";
					break;
				case 9:
					$text_val = "";
					break;
			}
		}
		return $text_val;
	}

	public static function getPercentSlowValue($percentCode){

		$percent_val = "";
		if($percentCode!=""){

			switch ($percentCode) {
				case "Current":
					$percent_val = "";
					break;
				case "Slow 30":
					$percent_val = 'PercentSlow30';
					break;
				case "Slow up to 30":
					$percent_val = 'PercentSlow30';
					break;
				case "Slow 60":
					$percent_val = 'PercentSlow60';
					break;
				case "Slow up to 60":
					$percent_val = 'PercentSlow60';
					break;
				case "Slow 90":
					$percent_val = 'PercentSlow90';
					break;
				case "Slow up to 90":
					$percent_val = 'PercentSlow90';
					break;
				case "Slow 120":
					$percent_val = 'PercentSlow120';
					break;
				case "Slow up to 120":
					$percent_val = 'PercentSlow120';
					break;
				case "Slow 121+":
					$percent_val = 'PercentSlow120Plus';
					break;
			}
		}
		return $percent_val;
	}


		/* Grouping Of array elements on category basis */

		public static function getGroupedArray($array, $keyFieldsToGroup) {
			$newArray = array();

			foreach ($array as $record)
				$newArray = General::getRecursiveArray($record, $keyFieldsToGroup, $newArray);

				return $newArray;
		}

		public static function getRecursiveArray($itemArray, $keys, $newArray) {
			if (count($keys) > 1)
				$newArray[$itemArray[$keys[0]]] = General::getRecursiveArray($itemArray,    array_splice($keys, 1), $newArray[$itemArray[$keys[0]]]);
			else
				$newArray[$itemArray[$keys[0]]][] = $itemArray;

			return $newArray;
		}


		public static function getElemenstGreaterthanMonthsCount($month_counter, $dateArray){

				$greaterThan12Month = "";

				/*function date_sort($a, $b) {
						return strtotime($a) - strtotime($b);
				}*/

			//$arrSorted = usort($dateArray, General::compareDates($date1, $date2));

			//get count of elements in array in last 12 momths(365) are.
			$count12Months = General::getCountOfValueInDateRange($month_counter, $dateArray);
			$greaterThan12Month = count($dateArray) - count($count12Months);

			//$cnt = array_slice($dateArray, $count12Months);
			//echo "Cnt-Remaining Val=" .count($cnt);
			return $greaterThan12Month;
		}

		public static function compareDates($date1, $date2){
		  return strtotime($date1) - strtotime($date2);
	    }

		public static function getCountOfValueInDateRange($days, $arrDate){

			$date2 = date('m/d/Y');
			$dates = array();

			foreach($arrDate as $date)
			{
				$date1 = $date;
				$array_date = new DateTime($date);
				$now = new DateTime();

				if($array_date < $now) {

					//$dateDiff = dateDiff($date1, $date2);
					  $date1_ts = strtotime($date1);
					  $date2_ts = strtotime($date2);
					  $diff = $date2_ts - $date1_ts;
					  $dateDiff  = round($diff / 86400);

					if($dateDiff <= $days)
					{
						$dates[] = $date;
					}
				}
			}
			if(!empty($dates)){
				// return count($dates);
                return $dates;
			}else{
				return $dates;
			}
	}

	public static  function businessNameCheck( $busniess_name){

        if (!preg_match('/(\w)\1{2,}/', $busniess_name)) {

            if(isset($busniess_name)){

                $State= State::where('name', $busniess_name)->first();
                $City= City::where('name', $busniess_name)->first();
                $Country= Country::where('name', $busniess_name)->first();
                $profanityOrSingle = RecordentExcludeKeywords::where('exclude_profanity',$busniess_name)->orWhere('exclude_single_words',$busniess_name)->first();

                if($State || $City || $Country || $profanityOrSingle ){
                    return false;
                }else{
                    return true;
                }
            }else{
                return false;
            }
        }else
        {
            return false;
        }
    }

    public static function utmContainerDetect() {
      $campaignUrl = Request::fullUrl();
      $pageUrl = Request::url();
      $campaignsData = Request::query();
      $campaignsData['utm_campaign_url'] = $campaignUrl;
      if(array_key_exists('utm_campaign_url',$campaignsData) && array_key_exists('utm_medium',$campaignsData) && array_key_exists('utm_campaign',$campaignsData))
      {
          $campaignsData['utm_campaign_url'] = Request::fullUrl();
          $campaignsData['utm_medium'] = $campaignsData['utm_medium'];
          $campaignsData['utm_source'] = $campaignsData['utm_source'] ? $campaignsData['utm_source'] : NULL;
          $campaignsData['utm_id'] = $campaignsData['utm_id'] ? $campaignsData['utm_id'] : NULL;
          $campaignsData['utm_campaign'] = $campaignsData['utm_campaign'];
          $campaignsData['lead_type'] = 1;
          $campaignsData['created_at'] = Date('Y-m-d H:i:s');
          $campaignsData['updated_at'] = Date('Y-m-d H:i:s');
          $id = DB::table('utm_containers_campaigns')->insertGetId($campaignsData);
          header("Location:".$pageUrl."?campaignid=$id"); exit;
          //return redirect()->to($pageUrl."?campaignid=$id"); exit;
        }
    }


    public static function sortByDate($key)
    {
        return function ($first, $second) use ($key) {
            $firstval = strtotime($first[$key]);
            $secondval = strtotime($second[$key]);
            return $firstval-$secondval;
        };
    }


    public static  function exist_rec($src_data,$key_name,$col_value)
    {
          foreach($src_data as $rec)
          {
            if ($rec->{$key_name}==$col_value)
            {
              return 1;
            }
          }
          return 0;
    }

    public static function CreditReport_Listing($from_date,$to_date){

        $Date1 = date("d-m-Y", strtotime($from_date));
        $Date2 = date("d-m-Y", strtotime($to_date));
        $array = array();
        $Variable1 = strtotime($Date1);
        $Variable2 = strtotime($Date2);

        for ($currentDate = $Variable1; $currentDate <= $Variable2; $currentDate += (86400)) {
            $Store = date('Y-m-d', $currentDate);
            $array[] = $Store;
        }

        $totalDates=$array;
        $final_array=array();


        $dataStudent = Students::where('last_viewed', '>=', $from_date)
                      ->where('last_viewed', '<', $to_date)
                      ->selectRaw('count(*) as viewed_Count,SUM(no_of_view_count) as no_of_view_count,DATE_FORMAT(students.last_viewed, "%d-%m-%Y") As last_viewed_date_student')
                      ->groupBy('last_viewed_date_student')
                      ->get();

        $data_business = Businesses::where('last_viewed', '>=', $from_date)
                                ->where('last_viewed', '<', $to_date)
                                ->selectRaw('count(*) as viewed_Count,SUM(no_of_view_count) as no_of_view_count,DATE_FORMAT(businesses.last_viewed, "%d-%m-%Y") As last_viewed_date_business')
                                ->groupBy('last_viewed_date_business')
                                ->get();

        foreach($totalDates as $date){
            $orgDate = $date;
            $newDate = date("d-m-Y", strtotime($orgDate));
            $student_exist = self::exist_rec($dataStudent,'last_viewed_date_student',$newDate);
            $business_exist = self::exist_rec($data_business,'last_viewed_date_business',$newDate);

            if (($student_exist == 1) && ($business_exist == 0)){
                $final_array[] = array(
                                'srno'=>0,
                                'type'=>'Business',
                                'date'=>$newDate,
                                'count'=>0,
                                'no_of_view_count'=>0
                            );
            }

            if (($student_exist==0)&&($business_exist==1)){
                $final_array[] = array(
                                'srno'=>0,
                                'type'=>'Individual',
                                'date'=>$newDate,
                                'count'=>0,
                                'no_of_view_count'=>0
                            );
            }
        }

        foreach($dataStudent as $rec){
            $studentData_new = array();

            $studentData_new['srno'] = 0;
            $studentData_new['type'] = 'Individual';
            $studentData_new['date'] = $rec['last_viewed_date_student'];
            $studentData_new['count'] = $rec['viewed_Count'];
            $studentData_new['no_of_view_count'] = $rec['no_of_view_count'];
            $final_array[] = $studentData_new;
        }

        foreach($data_business as $rec){
            $businessData=array();
            $businessData['srno']=0;
            $businessData['type']='Business';
            $businessData['date']=$rec['last_viewed_date_business'];
            $businessData['count']=$rec['viewed_Count'];
            $businessData['no_of_view_count']=$rec['no_of_view_count'];
            $final_array[]=$businessData;
        }

        usort($final_array,self::sortByDate('date'));

        $i = 1;
        foreach($final_array as &$rec){
            $rec['srno']=$i++;
        }

        return $final_array;
    }


    public static function getSequence($num){
		return sprintf("%'.05d\n", $num);
	}

	/*
		Name: refund_api_paubiz_usb2b()
		Desc: calling API for refunding amount from PayuBiz
	*/
	public static function payu_refund_api($amount, $mihpayid, $bank_ref_num){

		$key = config('app.payu_merchant_key');
		$salt = config('app.payu_merchant_salt');
		$command = "cancel_refund_transaction";
		$var1    = $mihpayid; // Payu ID (mihpayid) of transaction
		$var2    = rand();   // Token ID to be used in case of refund Can be a random number
		$var3    = $amount;   //  Amount to be used in case of refund
		$var4    = $bank_ref_num ; //bank_ref_num used for unique identity

		$hash_str = $key  . '|' . $command . '|' . $var1 . '|' . $salt ;
		$hash = strtolower(hash('sha512', $hash_str));
		$post_request_params = array('key' => $key , 'hash' => $hash , 'var1' => $var1, 'var2' => $var2,'var3' => $var3, 'var4' => $var4, 'command' => $command);

		$query_string = http_build_query($post_request_params);
        $refund_api_url = config('app.payu_refund_url');

		$output = self::curl_request_api_call($refund_api_url, $query_string);

		return $output;
	}

	public static function update_refund_amount_status($order_id, $refund_request, $refund_response, $refund_status){
		ConsentPayment::where('order_id', $order_id)->update([
			'raw_refund_request'  => $refund_request,
			'raw_refund_response' => $refund_response,
			'refund_status'       => $refund_status,
			]);
	}

    public static function check_payu_refund_status($request_id){
        $key = config('app.payu_merchant_key');
        $salt = config('app.payu_merchant_salt');
        $refund_api_url = config('app.payu_refund_url');

        $command = "check_action_status";
        $var1 = $request_id; // cancel_refund_transaction->request_id

        $hash_str = $key  . '|' . $command . '|' . $var1 . '|' . $salt ;
        $hash = strtolower(hash('sha512', $hash_str));
        $post_request_params = array('key' => $key , 'hash' => $hash , 'var1' => $var1, 'command' => $command);

        $query_string = http_build_query($post_request_params);
        $output = self::curl_request_api_call($refund_api_url, $query_string);

        return $output;
    }


    public static function curl_request_api_call($api_url, $post_params){

        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $api_url);
        curl_setopt($c, CURLOPT_POST, 1);
        curl_setopt($c, CURLOPT_POSTFIELDS, $post_params);
        curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);

        $output = curl_exec($c);

        if (curl_errno($c)) {
          $curl_error = curl_error($c);
          throw new Exception($curl_error);
          Log::debug("curl_error = ".print_r($curl_error, true));
        }

        curl_close($c);

        // Log::debug("curl_request_api_call response output = ".print_r($output, true));

        $valueSerialized = @unserialize($output);
        if($output === 'b:0;' || $valueSerialized !== false) {
          Log::debug('valueSerialized = '.print_r($valueSerialized, true));
        }

        return $output;
    }

    public static function getUsB2bReportRefundApiRequestParams($request_id){
        $key = config('app.payu_merchant_key');
        $salt = config('app.payu_merchant_salt');
        $refund_api_url = config('app.payu_refund_url');
        $command = "check_action_status";

        $hash_str = $key  . '|' . $command . '|' . $request_id . '|' . $salt;
        $hash = strtolower(hash('sha512', $hash_str));

        $refund_status_api_request_params = array("key" => $key, 'hash' => $hash, 'var1' => $request_id, "command" => $command, "api_endpoint" => $refund_api_url);

        return $refund_status_api_request_params;
    }


    public static function CreditreportAnalysis_Savedata_function($date,$today_date,$StudentBusiness_id,$customer_type)
    {

        $is_AlreadyViewCheck=CustomerCreditReportAnalysis::where('customer_id', $StudentBusiness_id)
                                                              ->where('created_at', '>=', $date)
                                                              ->where('created_at', '<', $today_date)
                                                              ->get();

        if(count($is_AlreadyViewCheck) == 0)
        {
            DB::table('customer_credit_report_analysis')
              ->insert(['customer_id' => $StudentBusiness_id,
                        'type' => $customer_type,
                        'customer_viewed' => 1,
                        'created_at' => Carbon::now()
                        ]);
        }else{
            CustomerCreditReportAnalysis::where('customer_id', $StudentBusiness_id)
                                          ->where('created_at', '>=', $date)
                                          ->where('created_at', '<', $today_date)
                                          ->update([
                                                'customer_viewed'=> DB::raw('customer_viewed + 1'),
                                            ]);
        }

    }
    public static function CreditReportAnalysis_GetList($from_date,$to_date)
    {
        $final_array=array();

        $data = CustomerCreditReportAnalysis::where('created_at', '>=', $from_date)
                                            ->where('created_at', '<', $to_date)
                                            ->selectRaw('type,date(created_at) as viewedDate, sum(customer_viewed) as count , count(*) as total_customers_count')
                                            ->groupBy(['type','viewedDate'])
                                            ->get();
            foreach($data as $rec)
            {
                $prepara_array=array();
                $prepara_array['type']=$rec['type'];
                $prepara_array['viewedDate']=$rec['viewedDate'];
                $prepara_array['count']=$rec['count'];
                $prepara_array['total_customers_count']=$rec['total_customers_count'];
                $final_array[]=$prepara_array;
            }


            usort($final_array,self::sortByDate('viewedDate'));

            return $final_array;
    }

    public static function CreditReportAnalysis_totalCount($from_date,$to_date)
    {
        $data = CustomerCreditReportAnalysis::where('created_at', '>=', $from_date)
                                            ->where('created_at', '<', $to_date)
                                            ->selectRaw('type,date(created_at) as viewedDate, sum(customer_viewed) as count , count(*) as total_customers_count')
                                            ->groupBy(['type'])
                                            ->get();
        $final_array=array();

        if(count($data) == 0)
        {
                    $types=array("Individual","Business");
                    foreach($types as $rec)
                    {
                        $prepara_array=array();
                        $prepara_array['type']=$rec;
                        $prepara_array['count']=0;
                        $final_array[]=$prepara_array;
                    }


        }else{
                    foreach($data as $rec)
                    {
                        $prepara_array=array();
                        $prepara_array['type']=$rec['type'];
                        $prepara_array['count']=$rec['count'];
                        $final_array[]=$prepara_array;
                    }

                    if(count($final_array)==1)
                    {
                        foreach($final_array as $rec)
                        {
                            if($rec['type'] != 'Individual')
                            {
                                $prepara_array=array();
                                $prepara_array['type']="Individual";
                                $prepara_array['count']=0;
                                $final_array[]=$prepara_array;
                            }
                            if($rec['type'] != 'Business')
                            {
                                $prepara_array=array();
                                $prepara_array['type']="Business";
                                $prepara_array['count']=0;
                                $final_array[]=$prepara_array;
                            }
                        }

                    }

        }

        return $final_array;
    }

    public static function Gst_StateWise_Code($State_GstCode)
    {
        if($State_GstCode != "")
        {
            $Gst_CodeStateWise=array("1"=>"35","2"=>"37","3"=>"12","4"=>"18","5"=>"18","6"=>"10","7"=>"04","8"=>"26","9"=>"26","10"=>"07",
                                    "11"=>"30","12"=>"24","13"=>"06","14"=>"02","15"=>"01","16"=>"20","17"=>"29","18"=>"","19"=>"32","20"=>"31",
                                    "21"=>"23","22"=>"27","23"=>"14","24"=>"17","25"=>"15","26"=>"13","27"=>"","28"=>"","29"=>"21","30"=>"",
                                    "31"=>"34","32"=>"03","33"=>"08","34"=>"11","35"=>"33","36"=>"36","37"=>"16","38"=>"09","39"=>"05","40"=>"",
                                    "41"=>"19");

                foreach($Gst_CodeStateWise as $Key=>$Value)
                {
                    if($Value == $State_GstCode)
                    {
                        return $stateId=$Key;
                    }
                }

        }

        return $stateId="";
    }


    public static function UpdatePaymentsCustomerLevelStudent($customer_id,$due_id,$paid_note,$orderArr,$skipandupdatepayment,$payment_options,$paid_date,$payment_value)
    {
            $getCustomId = StudentDueFees::select('external_student_id')->where('student_id','=',$customer_id)->where('id','=',$due_id);
                  $getCustomId = $getCustomId->first();
                    $checkCustomId = isset($getCustomId->external_student_id) ? $getCustomId->external_student_id : NULL;

                $dues = StudentDueFees::where('added_by',Auth::id())->where('external_student_id', $checkCustomId)->whereNull('deleted_at');
                if($paid_note==0){
                    $paid_note = null;
                }

                $orderArr = array_map('intval', explode(',', $orderArr));
                 $last_orderArr= last($orderArr);
                  $remove= array_pop($orderArr);
                if(isset($last_orderArr) && !empty($last_orderArr) && $skipandupdatepayment==0){
                    $sum=0;
                  foreach ($orderArr as $key => $value) {
                    $dues = $dues->where('id',$value);
                    $dues = $dues->withCount([
                        'paid AS totalPaid' => function ($query)  {
                        $query->select(DB::raw("SUM(paid_amount) as paid"))->whereNull('deleted_at');
                    }
                    ]);
                    $dues = $dues->first();
                      $total_dues_unpaid=$dues->due_amount-$dues->totalPaid;
                        if($total_dues_unpaid!=0){
                      $sum+= $total_dues_unpaid;


                    $valuesForStudentPayFees = [
                        'due_id' => $dues->id,
                        'student_id' => $dues->student_id,
                        'paid_amount' => $total_dues_unpaid,
                        'paid_date'=> $paid_date,
                        'paid_note' =>$paid_note,
                        'added_by' => Auth::id(),
                        'external_student_id' => $dues->external_student_id

                      ];
                     $studentDue= StudentPaidFees::create($valuesForStudentPayFees);
                    }

                  }
                    $dues1 = StudentDueFees::where('id',$last_orderArr)->where('external_student_id', $checkCustomId);
                    $dues1 = $dues1->withCount([
                        'paid AS totalPaid' => function ($query)  {
                        $query->select(DB::raw("SUM(paid_amount) as paid"))->whereNull('deleted_at');
                    }
                    ]);
                    $dues1 = $dues1->first();

                        $valuesForStudentPayFees = [
                                'due_id' => $dues1->id,
                                'student_id' => $dues1->student_id,
                                'paid_amount' => $payment_value-$sum,
                                'paid_date'=> $paid_date,
                                'paid_note' =>$paid_note,
                                'added_by' => Auth::id(),
                                'external_student_id' => $dues1->external_student_id


                        ];
                       $studentDue= StudentPaidFees::create($valuesForStudentPayFees);


                }  else if ($skipandupdatepayment==1) {
                    $dues = $dues->where('student_id',$customer_id);
                    $dues = $dues->withCount([
                    'paid AS totalPaid' => function ($query)  {
                    $query->select(DB::raw("SUM(paid_amount) as paid"))->whereNull('deleted_at');
                    }
                    ]);
                        $dues = $dues->get();
                        $sum= 0;
                        foreach ($dues as $key => $value) {
                          $total_dues_unpaid=$value->due_amount-$value->totalPaid;
                          if($total_dues_unpaid!=0){
                          if($payment_value > $total_dues_unpaid){
                                 $sum= $payment_value-$total_dues_unpaid;
                                 $valuesForStudentPayFees = [
                                    'due_id' => $value->id,
                                    'student_id' => $value->student_id,
                                    'paid_amount' => $total_dues_unpaid,
                                    'paid_date'=> $paid_date,
                                    'paid_note' =>$paid_note,
                                    'added_by' => Auth::id(),
                                    'external_student_id' => $value->external_student_id
                                  ];
                             if($payment_value!=0){
                                $studentDue=StudentPaidFees::create($valuesForStudentPayFees);
                              }
                             $payment_value = $sum;

                          } else {
                            $sum= $total_dues_unpaid-$payment_value;
                            $valuesForStudentPayFees = [
                                'due_id' => $value->id,
                                'student_id' => $value->student_id,
                                'paid_amount' => $payment_value,
                                'paid_date'=> $paid_date,
                                'paid_note' =>$paid_note,
                                'added_by' => Auth::id(),
                                'external_student_id' => $value->external_student_id
                              ];
                              $studentDue=StudentPaidFees::create($valuesForStudentPayFees);
                                 break;
                          }
                        }

                    }
                }


               else if(!empty($payment_options)) {
                $dues = $dues->where('student_id',$customer_id);
                $dues = $dues->withCount([
                'paid AS totalPaid' => function ($query)  {
                $query->select(DB::raw("SUM(paid_amount) as paid"))->whereNull('deleted_at');
                }
                ]);
                    $dues = $dues->get();
                    $sum = 0;
                    // $ids=[];
                foreach ($dues as $key => $value) {
                  $total_dues_unpaid=$value->due_amount-$value->totalPaid;
                  if($total_dues_unpaid!=0){
                     $sum+= $total_dues_unpaid;
                     // $ids[]= $value->id;
                   }
                }
                // $ids = implode(',', $ids);
                $valuesForStudentPaidFees = [
                        'due_id' => '',
                        'student_id' => $value->student_id,
                         'paid_amount' => $payment_value,
                        'paid_date'=> $paid_date,
                        'paid_note' =>$paid_note,
                        'added_by' => Auth::id(),
                        'external_student_id' => $value->external_student_id
                      ];
            $studentDue=StudentPaidFees::create($valuesForStudentPaidFees);
                 $valuesForStudentPayFees = [
                        'due_id' => '',
                        'student_id' => $value->student_id,
                        'paid_amount' => $sum-$payment_value,
                        'paid_date'=> $paid_date,
                        'paid_note' =>$paid_note,
                        'added_by' => Auth::id(),
                        'external_student_id' => $value->external_student_id,
                        'payment_options_drop_down' => $payment_options,

                      ];
                     $studentDue= StudentPaidFees::create($valuesForStudentPayFees);

                } else {

                    $dues = $dues->where('student_id',$customer_id);
                    $dues = $dues->withCount([
                        'paid AS totalPaid' => function ($query)  {
                        $query->select(DB::raw("SUM(paid_amount) as paid"))->whereNull('deleted_at');
                       }
                    ]);
                    $dues = $dues->get();
                    // dd($dues);
                     foreach ($dues as $key => $value) {
                      $total_dues_unpaid=$value->due_amount-$value->totalPaid;

                      if($total_dues_unpaid!=0){
                        // dd($total_dues_unpaid);

                          $valuesForStudentPayFees = [
                            'due_id' => $value->id,
                            'student_id' => $value->student_id,
                            'paid_amount' => $total_dues_unpaid,
                            'paid_date'=> $paid_date,
                            'paid_note' =>$paid_note,
                            'added_by' => Auth::id(),
                            'external_student_id' => $value->external_student_id

                          ];
                          $studentDue=StudentPaidFees::create($valuesForStudentPayFees);
                      }
                    }
                }
                General::storeAdminNotificationForPayment('Individual', $studentDue->id);
                return;
    }

     public static function UpdatePaymentsCustomerLevelBusiness($customer_id,$due_id,$paid_note,$orderArr,$skipandupdatepayment,$payment_options,$paid_date,$payment_value)
    {
         $getCustomId = BusinessDueFees::select('external_business_id')->where('business_id','=',$customer_id)->where('id','=',$due_id);
                  $getCustomId = $getCustomId->first();
                    $checkCustomId = isset($getCustomId->external_business_id) ? $getCustomId->external_business_id : NULL;
                $dues = BusinessDueFees::where('added_by',Auth::id())->where('external_business_id', $checkCustomId)->whereNull('deleted_at');
                if($paid_note==0){
                    $paid_note = null;
                }
                $orderArr = array_map('intval', explode(',', $orderArr));
                 $last_orderArr= last($orderArr);
                  $remove= array_pop($orderArr);
                if(isset($last_orderArr) && !empty($last_orderArr) && $skipandupdatepayment==0){
                    $sum=0;
                  foreach ($orderArr as $key => $value) {
                    $dues = $dues->where('id',$value);
                    $dues = $dues->withCount([
                        'paid AS totalPaid' => function ($query)  {
                        $query->select(DB::raw("SUM(paid_amount) as paid"))->whereNull('deleted_at');
                    }
                    ]);
                    $dues = $dues->first();
                      $total_dues_unpaid=$dues->due_amount-$dues->totalPaid;
                        if($total_dues_unpaid!=0){
                      $sum+= $total_dues_unpaid;
                    $valuesForBusinessPayFees = [
                        'due_id' => $dues->id,
                        'business_id' => $dues->business_id,
                        'paid_amount' => $total_dues_unpaid,
                        'paid_date'=> $paid_date,
                        'paid_note' =>$paid_note,
                        'added_by' => Auth::id(),
                        'external_business_id' => $dues->external_business_id

                      ];
                      $businessDue=BusinessPaidFees::create($valuesForBusinessPayFees);
                    }


                  }
                    $dues1 = BusinessDueFees::where('id',$last_orderArr)->where('external_business_id', $checkCustomId);
                    // dd($dues1);
                    $dues1 = $dues1->withCount([
                        'paid AS totalPaid' => function ($query)  {
                        $query->select(DB::raw("SUM(paid_amount) as paid"))->whereNull('deleted_at');
                    }
                    ]);
                    $dues1 = $dues1->first();

                             $valuesForBusinessPayFees = [
                                'due_id' => $dues1->id,
                                'business_id' => $dues1->business_id,
                                'paid_amount' => $payment_value-$sum,
                                'paid_date'=> $paid_date,
                                'paid_note' =>$paid_note,
                                'added_by' => Auth::id(),
                                'external_business_id' => $dues1->external_business_id


                              ];
                               $businessDue= BusinessPaidFees::create($valuesForBusinessPayFees);


                }  else if ($skipandupdatepayment==1) {
                    $dues = $dues->where('business_id',$customer_id);
                    $dues = $dues->withCount([
                    'paid AS totalPaid' => function ($query)  {
                    $query->select(DB::raw("SUM(paid_amount) as paid"))->whereNull('deleted_at');
                    }
                    ]);
                        $dues = $dues->get();
                        $sum= 0;
                        foreach ($dues as $key => $value) {
                          $total_dues_unpaid=$value->due_amount-$value->totalPaid;
                          if($total_dues_unpaid!=0){
                          if($payment_value > $total_dues_unpaid){
                                 $sum= $payment_value-$total_dues_unpaid;

                                 $valuesForBusinessPayFees = [
                                'due_id' => $value->id,
                                'business_id' => $value->business_id,
                                'paid_amount' => $total_dues_unpaid,
                                'paid_date'=> $paid_date,
                                'paid_note' =>$paid_note,
                                'added_by' => Auth::id(),
                                'external_business_id' => $value->external_business_id


                              ];
                             if($payment_value!=0){
                               $businessDue= BusinessPaidFees::create($valuesForBusinessPayFees);
                              }
                             $payment_value = $sum;

                          } else {
                            $sum= $total_dues_unpaid-$payment_value;

                            $valuesForBusinessPayFees = [
                                'due_id' => $value->id,
                                'business_id' => $value->business_id,
                                'paid_amount' => $payment_value,
                                'paid_date'=> $paid_date,
                                'paid_note' =>$paid_note,
                                'added_by' => Auth::id(),
                                'external_business_id' => $value->external_business_id
                              ];
                                $businessDue= BusinessPaidFees::create($valuesForBusinessPayFees);
                                break;

                          }
                        }

                    }
                }


               else if(!empty($payment_options)) {
                $dues = $dues->where('business_id',$customer_id);
                $dues = $dues->withCount([
                'paid AS totalPaid' => function ($query)  {
                $query->select(DB::raw("SUM(paid_amount) as paid"))->whereNull('deleted_at');
                }
                ]);
                    $dues = $dues->get();
                    $sum = 0;
                   // $ids=[];
                foreach ($dues as $key => $value) {
                  $total_dues_unpaid=$value->due_amount-$value->totalPaid;
                  if($total_dues_unpaid!=0){
                     $sum+= $total_dues_unpaid;
                     // $ids[]= $value->id;
                   }
                }
                // $ids = implode(',', $ids);
                  $valuesForBusinessPaidFees = [
                        'due_id' => '',
                        'business_id' => $value->business_id,
                        'paid_amount' => $payment_value,
                        'paid_date'=> $paid_date,
                        'paid_note' =>$paid_note,
                        'added_by' => Auth::id(),
                        'external_business_id' => $value->external_business_id

                      ];
                   $businessDue= BusinessPaidFees::create($valuesForBusinessPaidFees);
                $valuesForBusinessPayFees = [
                        'due_id' => '',
                        'business_id' => $value->business_id,
                        'paid_amount' => $sum-$payment_value,
                        'paid_date'=> $paid_date,
                        'paid_note' =>$paid_note,
                        'added_by' => Auth::id(),
                        'external_business_id' => $value->external_business_id,
                        'payment_options_drop_down' => $payment_options

                      ];
                     $businessDue= BusinessPaidFees::create($valuesForBusinessPayFees);

                }
                else {

                $dues = $dues->where('business_id',$customer_id);
                $dues = $dues->withCount([
                    'paid AS totalPaid' => function ($query)  {
                    $query->select(DB::raw("SUM(paid_amount) as paid"))->whereNull('deleted_at');
                   }
                ]);
                $dues = $dues->get();
                 foreach ($dues as $key => $value) {
                  $total_dues_unpaid=$value->due_amount-$value->totalPaid;

                  if($total_dues_unpaid!=0){

                      $valuesForBusinessPayFees = [
                        'due_id' => $value->id,
                        'business_id' => $value->business_id,
                        'paid_amount' => $total_dues_unpaid,
                        'paid_date'=> $paid_date,
                        'paid_note' =>$paid_note,
                        'added_by' => Auth::id(),
                        'external_business_id' => $value->external_business_id

                      ];
                     $businessDue= BusinessPaidFees::create($valuesForBusinessPayFees);
                  }
                }
            }
             General::storeAdminNotificationForPayment('Business',$businessDue->id);
            // dd($businessDue);
            return ;
    }

    public static function Email_Validation_api_call($email_id){

        $api_url="https://api.bouncify.io/v1/verify?apikey=qhinzsf2g2uwdes8zpnuglkdhj7zel8h&email=".$email_id."";
        $c = curl_init();
        curl_setopt($c, CURLOPT_URL, $api_url);
        curl_setopt($c, CURLOPT_POST, 0);
        curl_setopt($c, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($c, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($c, CURLOPT_SSL_VERIFYPEER, 0);

        $output = curl_exec($c);

        if (curl_errno($c)) {
          $curl_error = curl_error($c);
          throw new Exception($curl_error);
          Log::debug("curl_error = ".print_r($curl_error, true));
        }

        curl_close($c);

        return $output;
    }


    public static function Update_Balance_Due($balance_due,$payment_amount,$type,$due_id,$Id){
        if($balance_due != 0){
            $balance_due_amount=($balance_due) - ($payment_amount);
            $balance_due = [
                            'balance_due'=>$balance_due_amount];
            if($type=="Student")
            {
                $getCustomId = StudentDueFees::select('external_student_id')->where('student_id','=',$Id)->where('id','=',$due_id);
                $getCustomId = $getCustomId->first();
                $checkCustomId = isset($getCustomId->external_student_id) ? $getCustomId->external_student_id : NULL;
                StudentDueFees::where('id', $due_id)->where('student_id', $Id)->where('external_student_id', $checkCustomId)->where('added_by', Auth::id())->whereNull('deleted_at')->update($balance_due);
            }else if($type=="Business"){

                $getCustomId = BusinessDueFees::select('external_business_id')->where('business_id','=',$Id)->where('id','=',$due_id);
                $getCustomId = $getCustomId->first();
                $checkCustomId = isset($getCustomId->external_business_id) ? $getCustomId->external_business_id : NULL;
                BusinessDueFees::where('id',$due_id)->where('business_id','=',$Id)->where('external_business_id', $checkCustomId)->where('added_by',Auth::id())->whereNull('deleted_at')->update($balance_due);
            }
            
        }
    }

}
