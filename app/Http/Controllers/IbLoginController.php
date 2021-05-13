<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use TCG\Voyager\Facades\Voyager;
use Auth;


class IbLoginController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
      $utmsource = "";
        if( $request->has('utmsource') ) {
            $utmsource = $request->query('utmsource');
        }
        return Voyager::view('voyager::login-ib',compact('utmsource'));
    }

     public function bussines()
    {
        return Voyager::view('voyager::business-login');
    }
}
