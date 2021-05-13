<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\StudentsImport;
use App\Exports\StudentsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Students;
use App\User;
use App\StudentDueFees;
use App\StudentPaidFees;
use Validator;
use Response;
use Carbon\Carbon;
use App\Services\SmsService;
use DB;
use Auth;
use Illuminate\Support\Collection;

class OrganizationController extends Controller
{

	public function index(Request $request){
		
		
		$authRole = Auth::user()->role_id;

		$records = User::select('users.name','business_name','address','states.name as stateName','cities.name as cityName')
					->leftJoin('states','states.id','=','users.state_id')
					->leftJoin('cities','cities.id','=','users.city_id')
					->where('status',1)
					->where('role_id','=',$authRole);
		if(!empty($request->input('business_name'))){
			$records = $records->where('business_name','=',$request->input('business_name'));
		}

		$records = $records->paginate(25); 
		return view('admin.organizations.index',compact('records'));
	}
		
}
