<?php

namespace App\Http\Controllers\Front;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Students;
use App\User;
use App\StudentDueFees;
use App\StudentPaidFees;
use Validator;
use Response;
use Carbon\Carbon;
use DB;
use Auth;
use App\Post;
use Illuminate\Support\Collection;

class PostController extends Controller
{

	public function view($slug,Request $request){
		$post = Post::with('category')->where('slug', '=', $slug)->firstOrFail(); //dd($post);
		return view('front.post.view',compact('post'));
	}
		
}
