<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\StudentsImport;
use App\Exports\StudentsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Students;
use App\Businesses;
use App\AdminNotification;
use App\User;
use Validator;
use Response;
use Carbon\Carbon;
use DB;
use Auth;
use Illuminate\Support\Collection;
use App\CustomerKyc;
use App\Individuals;
use App\DuesSmsLog;


class EncryptController extends Controller
{
	public function loginBehalfMe($id){
		$user = User::where('id',$id)->first();
		if($user){
			Auth::login($user);
			return redirect(url('/admin'));	
		}
	}
	public function students(Request $request){

		/*Students::create([
			'contact_phone'=>'7834512678',
			'added_by'=>1,
			'person_name'=>'NVU',
			'created_at'=>Carbon::now(),
		]);*/
		/*$v= new Students;
		$v->contact_phone = '6767676776';
		$v->added_by = 1;
		$v->person_name = 'NNANANA';
		$v->created_at = Carbon::now();
		$v->save();*/


		Students::chunk(100, function($students) {
		    foreach ($students as $student) {
		    	$student->father_name = $student->father_name;
		    	$student->mother_name = $student->mother_name;
		    	$student->aadhar_number = $student->aadhar_number;
		    	$student->contact_phone = $student->contact_phone;
		    	$student->person_name = $student->person_name;
		    	$student->dob = $student->dob;
		        $student->update();
		    }
		});
	}

	public function users(Request $request){
		User::chunk(100, function($list) {
		    foreach ($list as $data) {
		    	$data->name = $data->name;
		    	$data->email = $data->email;
		    	$data->mobile_number = $data->mobile_number;
		    	$data->business_name = $data->business_name;
		    	$data->branch_name = $data->branch_name;
		    	$data->address = $data->address;
		    	$data->type_of_business = $data->type_of_business;
		    	$data->gstin_udise = $data->gstin_udise; 
		    	$data->update();
		    }
		});
	}

	public function business(Request $request){
		Businesses::chunk(100, function($list) {
		    foreach ($list as $data) {
		    	$data->company_name = $data->company_name;
		    	$data->unique_identification_number = $data->unique_identification_number;
		    	$data->concerned_person_name = $data->concerned_person_name;
		    	$data->concerned_person_phone = $data->concerned_person_phone;
		    	$data->concerned_person_alternate_phone = $data->concerned_person_alternate_phone;
		    	$data->address = $data->address;
		    	$data->update();
		    }
		});
	}	

	public function adminNotifications(Request $request){
		AdminNotification::chunk(100, function($list) {
		    foreach ($list as $data) {
		    	$data->title = $data->title;
		    	$data->update();
		    }
		});
	}

	public function customerKyc(Request $request){
		CustomerKyc::chunk(100, function($list) {
		    foreach ($list as $data) {
		    	$data->firstname = $data->firstname;
		    	$data->middlename = $data->middlename;
		    	$data->lastname = $data->lastname;
		    	$data->father_name = $data->father_name;
		    	$data->mother_name = $data->mother_name;
		    	$data->dob = $data->dob;
		    	$data->aadhar_number = $data->aadhar_number;
		    	$data->mobile_number = $data->mobile_number;
		    	$data->permenent_address = $data->permenent_address;
		    	$data->id_proof_number = $data->id_proof_number;
		    	$data->address_proof_number = $data->address_proof_number;
		    	$data->vehicle_name = $data->vehicle_name;
		    	$data->vehicle_number = $data->vehicle_number;
		    	$data->update();
		    }
		});
	}
	public function individualBusinessFront(Request $request){
		Individuals::chunk(100, function($list) {
		    foreach ($list as $data) {
		    	$data->mobile_number = $data->mobile_number;
		    	$data->udise_gstn = $data->udise_gstn;
		    	$data->otp = $data->otp;
		    	$data->update();
		    }
		});
	}
	public function duesSmsLog(Request $request){
		DuesSmsLog::chunk(100, function($list) {
		    foreach ($list as $data) {
		    	$data->message = $data->message;
		    	$data->contact_phone = $data->contact_phone;
		    	$data->update();
		    }
		});
	}

}
