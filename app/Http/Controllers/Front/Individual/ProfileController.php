<?php

namespace App\Http\Controllers\Front\Individual;

use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use App\StudentPaidFees;
use App\StudentDueFees;
use App\Individuals;
use App\Students;
use Carbon\Carbon;
use General;
use Validator;
use Response;
use Auth;
use DB;

class ProfileController extends Controller
{

	public function index(Request $request){
		$conactPhone = Session::get('individual_client_mobile_number');
		$profiles = Students::where('contact_phone','=',General::encrypt($conactPhone))->whereNull('deleted_at')->orderBy('id','DESC')->get();
		return view('front-ib/individual/profile/index',compact('profiles'));
	}

	public function edit($id){
		$conactPhone = Session::get('individual_client_mobile_number');
		$data = Students::where('id',$id)->where('contact_phone','=',General::encrypt($conactPhone))->whereNull('deleted_at')->first();
		if(empty($data)){
			return redirect()->back()->withErrors(['No record found']);
		}
		return view('front-ib/individual/profile/edit',compact('data'));
	}

	public function update(Request $request){

		$id = $request->input('id');
		$conactPhone = Session::get('individual_client_mobile_number');
		$data = Students::where('id',$id)->where('contact_phone','=',General::encrypt($conactPhone))->whereNull('deleted_at')->first();
		if(empty($data)){
			return redirect()->back()->withErrors(['No record found']);
		}

		$request->merge(['aadhar_number' => str_replace('-','',$request->aadhar_number)]);
		$request->merge(['aadhar_number' => str_replace('_','',$request->aadhar_number)]);

		
		$rule=[
		   'id'=>'required',
           'aadhar_number' => 'required_without:contact_phone,person_name',
		   'dob' => 'nullable|date|before_or_equal:today',
		   'father_name'=>'nullable|max:191|regex:/^[\pL\s]+$/u',
		   'mother_name'=>'nullable|max:191|regex:/^[\pL\s]+$/u',
       ];
       if(!empty($request->aadhar_number)){
       	  $rule['contact_phone'] ='nullable|regex:/^([0-9\+\(\)]*)$/|min:10|max:13';
       	  $rule['person_name'] = 'nullable|max:191|regex:/^[\pL\s]+$/u';
       }else{
       	  $rule['contact_phone'] ='required_without:aadhar_number|regex:/^([0-9\+\(\)]*)$/|min:10|max:13';
       	  $rule['person_name'] = 'required_without:aadhar_number|max:191|regex:/^[\pL\s]+$/u';
       }

		$validator = Validator::make($request->all(), $rule,
			['person_name.regex'=>'The :attribute may only contain letters and space.',
			 'father_name.regex'=>'The :attribute may only contain letters and space.',
			 'mother_name.regex'=>'The :attribute may only contain letters and space.',
			]
		);
	   
        if($validator->fails()) {
           return redirect()->back()->withErrors($validator)->withInput();
        }
        
        if(empty($data->aadhar_number)){
			$data->aadhar_number  = $request->input('aadhar_number');
		}	
		if(empty($data->contact_phone)){
			$data->contact_phone  = $request->input('contact_phone');
		}
		if(empty($data->person_name)){	
			$data->person_name  = $request->input('person_name');
		}
		if(empty($data->dob)){		
			$data->dob  = $request->input('dob');
		}
		if(empty($data->father_name)){		
			$data->father_name  = $request->input('father_name');
		}
		if(empty($data->mother_name)){		
			$data->mother_name  = $request->input('mother_name');
		}

		
        $data->update();	
        return redirect()->route('front-individual.profile')->with('message','Profile updated');

	}

		
}
