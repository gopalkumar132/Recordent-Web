<?php

use Illuminate\Database\Seeder;
use App\Businesses;
use App\Helpers;

class LinkGenerateBusinessTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        
       
		$Businesses = Businesses::where('uniqe_url_business', null)->orWhere('uniqe_url_business', '')->get();
        

		foreach($Businesses as $rec)
		{
                 
					$BusinessesTable=Businesses::where('id',$rec['id'])->first();
					$token=$rec['id'].$rec['added_by']."Bus";
					$uniq_id=General::encrypt($token);
					$encrpt_res=str_replace("/","",$uniq_id);
					$unique_url_business=url('checkmyreport/business/'.$encrpt_res);
					$BusinessesTable->update(['uniqe_url_business'=>$unique_url_business]);

		}
    }
}
