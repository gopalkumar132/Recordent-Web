<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Exception;
use Illuminate\Support\Facades\Log;

use App\Imports\StudentsImport;
use App\Exports\StudentsExport;
use App\Imports\StudentsDuePaymentImport;
use App\MembershipPayment;
use App\Students;
use App\StudentDueFees;
use App\StudentPaidFees;
use App\IndividualBulkUploadIssues;
use App\DuesSmsLog;
use App\ConsentRequest;
use App\ConsentPayment;
use App\Dispute;
use App\DuePayment;
use App\TempDuePayment;
use App\BulkDuePaymentUploadIssues;
use App\ConsentAPIResponse;
use App\ReportRequestResponseLog;
use App\UsersOfferCodes;
use App\Services\SmsService;
use App\User;
use Carbon\Carbon;
use Validator;
use Response;
use DB;
use Auth;
use Storage;
use General;
use PDF;
use PaytmWallet;
use Session;

class UsBusinessReportDownloadController extends Controller
{
	public function usReportView(Request $request)
	{
		$dataList = Collection::make();
		$currentTime = Carbon::now();
		
		$cp_id = $request->c_id;
		$c_id  = $request->c_id;
		$api_data = DB::table('consent_api_response')->where('consent_request_id', $request->c_id)->get();
		
		foreach($api_data as $key => $value){
			$response = General::decrypt($value->response);
		}
		
		$response = json_decode($response, true);

		$business_name = "";
		if(!empty(Session::get('business_name'))){
			$business_name = Session::get('business_name');
		}

		return view('admin.us-creditreport.report.index', compact('business_name','response','cp_id','c_id'));
	}
 
	/*	
		Name: usBusinessReportDowloadPdf
		Description: Function used to download PDF file, for US Business Report PDF.
	*/
	 /*	
		Name: usBusinessReportDowloadPdf
		Description: Function used to download PDF file, for US Business Report PDF.
	*/
	
	public function usBusinessReportDowloadPdf(Request $request)
	{
		ini_set('max_execution_time', 0);
		
		// dd($request->all());
		$dataList = Collection::make();		
		$dateTime = Carbon::now();		
		$cp_id = $request->cp_id;
		
		$api_data = DB::table('consent_api_response')->where('consent_request_id', $cp_id)->get();		
		
		foreach($api_data as $key => $value){
			$response = General::decrypt($value->response);
		}
		
		$response = json_decode($response, true);
		$business_name = "";
		if(!empty(Session::get('business_name'))){
			$business_name = Session::get('business_name');
		}
		
		//user name.
		$user = Auth::user()->name;
		
		$pdf = PDF::loadView('us_report_pdf', compact('dateTime', 'cp_id', 'response', 'user','business_name'));
		$fileName = $request->r_n . '.pdf';
		return $pdf->download('Recordent US Business Report' . $fileName);
	}
	

}
