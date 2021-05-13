<?php

namespace App\Http\Controllers\Front\Business;

use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use App\StudentPaidFees;
use App\StudentDueFees;
use App\Individuals;
use App\Businesses;
use App\State;
use App\City;
use App\Sector;
use Carbon\Carbon;
use General;
use Validator;
use Response;
use Auth;
use DB;

class ProfileController extends Controller
{

	public function index(Request $request){
		
		$mobile_number = Session::get('individual_client_mobile_number');
		$email = Session::get('individual_client_email');

		if ($mobile_number) {
			$data = Businesses::where('concerned_person_phone','=',General::encrypt($mobile_number))->whereNull('deleted_at')->first();
		} else {
			$data = Businesses::where('email','=',General::encrypt($email))->whereNull('deleted_at')->first();
		}

		if(empty($data)){
			return redirect()->route('front-business.dashboard')->withErrors(['No record found']);
		}

		$states = State::where('country_id',101)->get(); 
	     $stateIds = []; 
	     foreach ($states as $state){
	        $stateIds[] =$state->id; 
	     } 
	     $cities = City::whereIn('state_id',$stateIds)->get();
	     $sectors = Sector::where('status',1)->whereNull('deleted_at')->orderBy('id','ASC')->get();
		return view('front-ib/business/profile/edit',compact('data','cities','states','sectors'));
	}

	/*public function edit($id){
		$udise_gstn = Session::get('individual_client_udise_gstn');
		$data = Businesses::where('unique_identification_number','=',$udise_gstn)->whereNull('deleted_at')->first();
		if(empty($data)){
			return redirect()->route('front-business.business')->withErrors(['No profile found']);
		}
		return view('front-ib/business/profile/edit',compact('data'));
	}*/

	public function update(Request $request){
		
		$id = $request->input('id');

		$mobile_number = Session::get('individual_client_mobile_number');
		$email = Session::get('individual_client_email');

		if ($mobile_number) {
			$data = Businesses::where('concerned_person_phone','=',General::encrypt($mobile_number))->whereNull('deleted_at')->first();
		} else {
			$data = Businesses::where('email','=',General::encrypt($email))->whereNull('deleted_at')->first();
		}

		if(empty($data)){
			return redirect()->route('front-business.dashboard')->withErrors(['No record found']);
		}
		
		$rule['id'] = 'required';
		$rule['concerned_person_alternate_phone'] = 'nullable|regex:/^([0-9\+\(\)]*)$/|min:10|max:13';	
		$rule['address'] = 'nullable|max:191';
		$rule['pin_code']  = 'nullable|max:15';
		
		$validator = Validator::make($request->all(), $rule);
	   
        if($validator->fails()) {
           return redirect()->back()->withErrors($validator)->withInput();
        }
        $isUpdate = false;
        if(empty($data->concerned_person_alternate_phone)){
			$data->concerned_person_alternate_phone  = $request->input('concerned_person_alternate_phone');
			$isUpdate = true;
			
		}	
		if(empty($data->address)){
			$data->address  = $request->input('address');
			$isUpdate = true;
			
		}
		if(empty($data->pincode)){	
			$data->pincode  = $request->input('pin_code');
			$isUpdate = true;
			
		}
		if($isUpdate){
			$data->updated_at = Carbon::now();
			$data->update();
		}
        
        return redirect()->back()->with('message','Profile updated');

	}

		
}
