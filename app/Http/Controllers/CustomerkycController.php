<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\StudentsImport;
use App\Exports\StudentsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use App\Http\Controllers\Controller;
use App\Students;
use App\Customer;
use App\CustomerKyc;
use App\StudentDueFees;
use App\StudentPaidFees;
use App\AddressProofType;
use App\VehicleType;
use App\IdProofType;
use Validator;
use Response;
use Carbon\Carbon;
use DB;
use Auth;


class CustomerkycController extends Controller
{
    
    

	public function storeRating(Request $request) 
    {  
    	
    	$this->validate($request,[ 
            'id' => 'required',
             'rating'=>'required',
        ]);
       

        $customerkyc = new CustomerKyc;
        if(Auth::user()->role_id==1){
            $customerkyc = $customerkyc->where('id',$request->input('id'))->first();
        }else{
            $customerkyc = $customerkyc->where('id',$request->input('id'))->where('added_by',Auth::id())->first();
        }
        if(empty($customerkyc)){
            return redirect()->back()->withErrors(['can not find record']);
        }

        $customerkyc->rating = $request->input('rating');
        $customerkyc->save();
        return redirect()->back()->with([
                'message'    => 'successfully rated',
                'alert-type' => 'success',
            ]);

	}


		
}
