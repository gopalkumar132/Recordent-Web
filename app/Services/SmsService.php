<?php

namespace App\Services;

class SmsService
{

    public $username;
    public $password;
    public $source;

    public function __construct()
    {
        $this->username = config('services.sms_gateway_provider.username');
        $this->password = config('services.sms_gateway_provider.password');
        $this->source = config('services.sms_gateway_provider.source');
    }

    public function sendSms($to,$text, $tag = '')
    {
        $tag = str_replace('templates-', '', $tag);
        $response = $this->tempSendSms($to,$text, $tag);
       // dd($response);
        return $response;
        $to = "+91".$to;
        // Configure HTTP basic authorization: basicAuth
        $config = \Karix\Configuration::getDefaultConfiguration()
            ->setUsername($this->username)
            ->setPassword($this->password);

        $apiInstance = new \Karix\Api\MessageApi(
            // If you want use custom http client, pass your client which implements `GuzzleHttp\ClientInterface`.
            // This is optional, `GuzzleHttp\Client` will be used as default.
            NULL,
            $config
        );
        date_default_timezone_set('UTC');
        // Create Message object
        $message = (new \Karix\Model\CreateMessage())
            ->setChannel("sms") //Or use "whatsapp"
            ->setSource($this->source)
            ->setDestination([$to])
            ->setContent(["text" =>$text]);
        $response = [];
        $response['fail_to_send'] = false;
        try {
            $result = $apiInstance->sendMessage($message);
            $response['actual_status'] = $result->getObjects()[0]->getStatus();
            //failed // undelivered //rejected
            if(in_array($response['actual_status'],['failed','undelivered','rejected'])){
                $response['sent'] = 0;
                $response['message'] = 'failed to send sms.';
            }else{
                $response['sent'] = 1;
            }
        } catch (\Exception $e) {
            $response['fail_to_send'] = true;
            $response['sent'] = 0;
            $response['message'] = 'can not send sms right now, please try again.';
        }

        return $response;
    }

     public function tempSendSms($to,$text){

        $key = "sFA2OTHGLovBJ6eAzIQf6Q=="; //"krTo3zc7NQSX8zeH18xk9w==";
       // $key = "krTo3zc7NQSX8zeH18xk9w==";
        $sender = "RECDNT";

        $text = urlencode($text);

        $type="UC";

        $countryCode = "91";

        $dlt_entity_id = "1001517760000018200";

        //$url = "https://japi.instaalerts.zone/httpapi/QueryStringReceiver?ver=1.0&send=".$sender."&dest=".$to."&text=".$text."&country_cd=".$countryCode."&key=".$key."&type=".$type."&dlt_entity_id=".$dlt_entity_id;
        $url = "https://japi.instaalerts.zone/httpapi/QueryStringReceiver?ver=1.0&send=".$sender."&dest=".$to."&text=".$text."&country_cd=".$countryCode."&key=".$key."&dlt_entity_id=".$dlt_entity_id;

        if(!empty($tag) && $tag != ''){
            $url = $url . "&tag=".$tag;
        }

        $url = str_replace(' ', '%20', $url);
        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => $url,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "GET",
        ));

     //$otpResponse = curl_exec($curl);
     //curl_close($curl);
     //dd($otpResponse);




        $response = [];

        $response['fail_to_send'] = false;

        try{

            //$otpResponse = file_get_contents($url);
            $otpResponse = curl_exec($curl);
            curl_close($curl);

            $otpResponse = strtolower($otpResponse);

            if(strpos($otpResponse,"statuscode=200")!==false){

                    $response['sent'] = 1;

                    $response['actual_status'] = "queue";

            }else{

                $response['sent'] = 0;

                $response['actual_status'] = "failed";

                $response['message'] = 'failed to send sms.';

            }



        }catch(\Exception $e){

           // dd($e);

            $response['actual_status'] = "failed";

            $response['fail_to_send'] = true;

            $response['sent'] = 0;

            $response['message'] = 'can not send sms right now, please try again.';

        }

       return $response;

    }



}
