<?php

use Illuminate\Database\Seeder;
use App\Students;
use App\Helpers;

class LinkGenerateStudentTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        
       
		$individual = Students::where('uniqe_url_individual', null)->orWhere('uniqe_url_individual', '')->get();


		foreach($individual as $rec)
		{
            
                $studentTable=Students::where('id',$rec['id'])->first();
                $token=$rec['id'].$rec['added_by']."Inv";
                $uniq_id=General::encrypt($token);
                $encrpt_res=str_replace("/","",$uniq_id);
                $unique_url_individual=url('checkmyreport/individual/'.$encrpt_res);
                $studentTable->update(['uniqe_url_individual'=>$unique_url_individual]);			

		}
    }
}
