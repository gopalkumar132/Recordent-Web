<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\StudentsImport;
use App\Exports\StudentsExport;
use App\Http\Controllers\Controller;
use App\Students;
use App\StudentDueFees;
use App\StudentPaidFees;
use Illuminate\Support\Str;
use Validator;
use Response;
use Carbon\Carbon;
use DB;
use Auth;
use Storage;
use General;
use Session;
use Illuminate\Support\Collection;
use App\DuePayment;
use App\MembershipPayment;
use App\ConsentPayment;

class PaymentController extends Controller
{
    public function index(Request $request){
    	$User = Auth::user();
		$authId = Auth::id();
		$records = new DuePayment;
		$fromDate=$request->from_date;
		$toDate=$request->to_date;
		$paymentType=$request->payment_type;

		if(!empty($fromDate) && !empty($toDate) ){
			$fromDate.=' 00:00:00';
			$toDate.=' 23:59:59';
			$records = $records->where('created_at','>=',$fromDate)->where('created_at','<=',$toDate);
		}elseif(!empty($fromDate)){
			$fromDate.=' 00:00:00';
			$records = $records->where('created_at','>=',$fromDate);
		}elseif(!empty($toDate)){
			$toDate.=' 23:59:59';
			$records = $records->where('created_at','<=',$toDate);
		}

		if($paymentType=='CUSTOMER_DUE_INDIVIDUAL'){
			$records = $records->where('payment_done_by','CUSTOMER')->where('customer_type','INDIVIDUAL');
		}elseif($paymentType=='CUSTOMER_DUE_BUSINESS'){
			$records = $records->where('payment_done_by','CUSTOMER')->where('customer_type','BUSINESS');
		}elseif($paymentType=='COLLECTION_FEE_INDIVIDUAL'){
			$records = $records->where('payment_done_by','ADMIN_MEMBER')->where('customer_type','INDIVIDUAL');
		}elseif($paymentType=='COLLECTION_FEE_BUSINESS'){
			$records = $records->where('payment_done_by','ADMIN_MEMBER')->where('customer_type','BUSINESS');
		}

		$records = $records->orderBy('id','DESC')->get();
		$records = $records->filter(function($key,$value){

			if($key->customer_type=='INDIVIDUAL'){
                $dueType='individualDue';
                $paidType = 'individualPaid';
            }else{
                $dueType='businessDue';
                $paidType = 'businessPaid';
            }
            if(!$key->$dueType){
            	return false;
            }
            return true;
		});
		$records = $records->customPaginate(25);
		$records = $records->appends(request()->query());
		return view('admin.payments.index',compact('records'));
	}


      public function membershipPaymentsListing(Request $request){
        $User = Auth::user();
        $authId = Auth::id();
        $records = new MembershipPayment;
        $fromDate=$request->from_date;
        $toDate=$request->to_date;
        $paymentType=$request->payment_type;

        if(!empty($fromDate) && !empty($toDate) ){
          $fromDate.=' 00:00:00';
          $toDate.=' 23:59:59';
          $records = $records->where('created_at','>=',$fromDate)->where('created_at','<=',$toDate);
        }elseif(!empty($fromDate)){
          $fromDate.=' 00:00:00';
          $records = $records->where('created_at','>=',$fromDate);
        }elseif(!empty($toDate)){
          $toDate.=' 23:59:59';
          $records = $records->where('created_at','<=',$toDate);
        }

        if($paymentType!="") {
            $records = $records->where('pricing_plan_id',$paymentType);
        }
        $records = $records->orderBy('id','DESC')->get();
         $records = $records->whereIn('invoice_type_id',[1,7]);
        $records = $records->customPaginate(25);
        $records = $records->appends(request()->query());
        return view('admin.payments.membershippayments',compact('records'));
      }

      public function consentPaymentsListing(Request $request){
        $User = Auth::user();
        $authId = Auth::id();
        $records = new ConsentPayment;
        $fromDate=$request->from_date;
        $toDate=$request->to_date;
        $paymentType=$request->payment_type;

        if(!empty($fromDate) && !empty($toDate) ){
          $fromDate.=' 00:00:00';
          $toDate.=' 23:59:59';
          $records = $records->where('created_at','>=',$fromDate)->where('created_at','<=',$toDate);
        }elseif(!empty($fromDate)){
          $fromDate.=' 00:00:00';
          $records = $records->where('created_at','>=',$fromDate);
        }elseif(!empty($toDate)){
          $toDate.=' 23:59:59';
          $records = $records->where('created_at','<=',$toDate);
        }
        if($paymentType!="") {
            $records = $records->where('customer_type',$paymentType);
        }
        $records = $records->orderBy('id','DESC')->get();
        $records = $records->customPaginate(25);
        $records = $records->appends(request()->query());
        return view('admin.payments.consentpayments',compact('records'));
      }


}
