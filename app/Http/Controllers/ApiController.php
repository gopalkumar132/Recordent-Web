<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use App\UsersOfferCodes;
use App\Country;
use App\State;
use App\City;
use General;
use Auth;
use Carbon\Carbon;
use App\StoreGstinLookups;
use Config;
use Log;


class ApiController extends Controller
{
    public function getGstinData(Request $request, $report=null) {
		//echo json_encode($request->all());
		//echo $request->gstin;
		$postData = config('custom_configs.gstinLookUpConfig');
		$authHeaders = array(
			"cache-control: no-cache",
			"client_id: config('custom_configs.gstinLookUpConfig.client_id')",
			"content-type: application/json"
		);
		if($report!= null){
		 $gstin=$request->unique_identification_number;
		 $response = StoreGstinLookups::where('gstin_no','LIKE',$gstin)->value('gstin_response_data');
		 	$response = json_decode($response);
		 	$gstinResponse = (array)json_decode($response);
		 	// $gstinResponse = (array)json_decode($gstinResponse);

		 if(!empty($response)){
				$gstinResponseUpdate = (array)$gstinResponse['data']->pradr;
				$address = $gstinResponseUpdate['addr']->bnm." ".$gstinResponseUpdate['addr']->bno." ".$gstinResponseUpdate['addr']->flno." ".$gstinResponseUpdate['addr']->st." ".$gstinResponseUpdate['addr']->loc;
				$state=$gstinResponseUpdate['addr']->stcd;
				$business_name=$gstinResponse['data']->lgnm;
				$pincode=$gstinResponseUpdate['addr']->pncd;
		   return response()->json(['success' => true,'response' => $response, 'address' => $address,'state' => $state, 'business_name' => $business_name,'pincode' => $pincode]);
		 }
	    } else {
		$gstin = strtoupper($request->gstin);
	    }
		/*$postData = array(
			"username" => "koteshwara.rao@recordent.com",
			"password" => "Koteshwara@123",
			"client_id" => "tkxRkkvmyitjmNELbW",
			"client_secret" => "uOLmxtUI6YV6dTGZUeQhcb0x",
			"grant_type" => "password"
		);
		$authHeaders = array(
			"cache-control: no-cache",
			"client_id: tkxRkkvmyitjmNELbW",
			"content-type: application/json"
		);*/

		General::add_to_debug_log(Auth::user()->id, "gstin access_token api call initiated.");

		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL => "https://commonapi.mastersindia.co/oauth/access_token",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_CUSTOMREQUEST => "POST",
		CURLOPT_POSTFIELDS => json_encode($postData),
		CURLOPT_HTTPHEADER => $authHeaders,
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);

		if ($err) {
			General::add_to_debug_log(Auth::user()->id, "Error in gstin access_token api call.");
			echo "cURL Error #:" . $err;
		}
		$authTokenResonse = json_decode($response);
		//echo $authTokenResonse->access_token;
		
		General::add_to_debug_log(Auth::user()->id, "gstin api call initiated.");

		$clientId = $postData['client_id'];
		$getGstinHeaders = array(
		"authorization: Bearer $authTokenResonse->access_token",
		"cache-control: no-cache",
		"client_id: $clientId",
		"content-type: application/json"
		);
		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL => "https://commonapi.mastersindia.co/commonapis/searchgstin?gstin=$gstin",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_ENCODING => "",
		CURLOPT_MAXREDIRS => 10,
		CURLOPT_TIMEOUT => 30,
		CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
		CURLOPT_CUSTOMREQUEST => "GET",
		CURLOPT_HTTPHEADER => $getGstinHeaders,
		));

		$response = curl_exec($curl);
		$err = curl_error($curl);

		curl_close($curl);
         
		if ($err) {
			General::add_to_debug_log(Auth::user()->id, " Error in gstin api call initiated.");
			echo "cURL Error #:" . $err;
		} else {
			
			$states = State::where('country_id',101)->get();
			$stateIdNames = $cityIdNames = []; 
			foreach ($states as $state){
				$stateIdNames[$state->id] = $state->name;
			}
			$stateIdNames = array_flip($stateIdNames);
			$gstinResponse = (array)json_decode($response);
			if(!$gstinResponse['error']) {
				//print_r($gstinResponse);
				General::add_to_debug_log(Auth::user()->id, " gstin api call success.");
				$gstinResponseUpdate = (array)$gstinResponse['data']->pradr;
				$address = $gstinResponseUpdate['addr']->bnm." ".$gstinResponseUpdate['addr']->bno." ".$gstinResponseUpdate['addr']->flno." ".$gstinResponseUpdate['addr']->st." ".$gstinResponseUpdate['addr']->loc;
				$stateId = $stateIdNames[$gstinResponseUpdate['addr']->stcd];
				$cities = City::where('state_id',$stateId)->get();
				foreach ($cities as $city){
						$cityIdNames[$city->id] = $city->name;
					}
				$cityKey = array_search ($gstinResponseUpdate['addr']->city, $cityIdNames);
				$cityKey = $cityKey==""?null:$cityKey;
				$businessNameCheck = stripos($gstin, "p",5);
				if($businessNameCheck === false) {
					$business_nameAssign = General::encrypt($gstinResponse['data']->lgnm);
				} else {
					$business_nameAssign = General::encrypt($gstinResponse['data']->tradeNam);
				}
				$updateProfile = [
					'business_name' => $business_nameAssign,
					'state_id' => $stateId,
					'city_id' => $cityKey,
					'pincode' => $gstinResponseUpdate['addr']->pncd,
					'address'=>General::encrypt($address),
					'company_type'=>'gstin',
					'gstin_udise'=>General::encrypt(strtoupper($gstin)),
					'gstin_verified_at' => Carbon::now()
				];
				$insertgstinlookup = $this->insertgstinlookup($gstin,$response);
				User::where('id', Auth::user()->id)->update($updateProfile);
				if($report != null){
		         	$business_name=$gstinResponse['data']->lgnm;
		         	$state=$gstinResponseUpdate['addr']->stcd;
		         	$pincode=$gstinResponseUpdate['addr']->pncd;
		         	return response()->json(['success' => true,'response' => $response,'address' => $address, 'state' =>$state, 'business_name' => $business_name,'pincode' => $pincode]);
		         }
				 echo $response;
				} else {
					echo $response;
				}
		}
	}

	public function insertgstinlookup($gstin, $response) {
        $gstin_lookup = [
                    'user_id' => Auth::user()->id,
                    'gstin_no' => $gstin,
                    'gstin_response_data' => json_encode($response)
                ];
          StoreGstinLookups::create($gstin_lookup);
          return $response;      
    }
	
	
	public function verifyOfferCode(Request $request) {
		$verifyCodePostData = array("code"=>$request->offercode);
		//General::add_to_debug_log("verifyOfferCode api call initiated.");
		$response = General::offer_codes_curl($verifyCodePostData,'verify-code');
		//General::add_to_debug_log("verifyOfferCode api call completed.");
		echo $response; 
		
	}

	public function getCityData(Request $request) {
		$pincode = $request->pincode;
		$data= file_get_contents('http://postalpincode.in/api/pincode/'.$pincode);
        $data = json_decode($data);
        if(isset($data->PostOffice['0'])){
        	$city = $data->PostOffice['0']->Taluk;
		return response()->json(['success' => true, 'city' => $city]);

        }
	}
}
