<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session as Session;
use App\State;
use App\City;
use Auth;

class CreditReportController extends Controller
{
    public function index()
    {
		$states = State::where('country_id',231)->get();
		$stateIds = [];
		foreach ($states as $state){
			$stateIds[] =$state->id;
		}

		$usa_b2b_credit_report_price = 6000;
		if (!empty(Auth::user()->user_pricing_plan)) {
			$usa_b2b_credit_report_price = Auth::user()->user_pricing_plan->usa_b2b_credit_report;
		}

		$gst_price = $usa_b2b_credit_report_price * 18/100;
		$total_us_b2b_credit_report_price = $usa_b2b_credit_report_price + $gst_price;

		$cities = City::whereIn('state_id',$stateIds)->orderBy('name','ASC')->get();

		return view('admin.creditreports.index',compact('states','cities', 'total_us_b2b_credit_report_price'));
	}

  public function IndividualCustomCreditReport() {
    return view('admin.creditreports.b2c-custom-credit-report');
  }
}
