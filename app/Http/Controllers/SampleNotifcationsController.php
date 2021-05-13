<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;

class SampleNotifcationsController extends Controller
{
    public function index() {
		return view('admin.sample-notifications');
	}
}
