<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\StudentsImport;
use App\Exports\StudentsExport;
use Maatwebsite\Excel\Facades\Excel;
use App\Http\Controllers\Controller;
use App\Students;
use App\StudentDueFees;
use App\StudentPaidFees;
use App\Businesses;
use App\BusinessDueFees;
use App\BusinessPaidFees;
use App\AdminNotification;
use App\AdminNotificationSeen;
use Validator;
use Response;
use Carbon\Carbon;
use DB;
use Auth;
use Storage;
use General;

class NotificationController extends Controller{
	
	public function index(Request $request) {

        //dd(General::getAdminNotificationCount());
        
       $authId = Auth::id();
       $records= AdminNotification::with(['seen'=>function($q) use($authId){
           $q->where('user_id',$authId);
       }])->orderBy('reported_at','desc')->get();

       //make all notification seen
       General::makeAdminNotificationSeen();
       return view('admin/notifications/index',compact('records'));
    }
	
}
