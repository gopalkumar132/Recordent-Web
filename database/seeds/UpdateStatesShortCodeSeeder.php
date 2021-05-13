<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateStatesShortCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       $stateCodes = array("1"=>"AN","2"=>"AP","3"=>"AR","4"=>"AS","5"=>"BR","6"=>"CH","7"=>"CG","8"=>"DH","9"=>"DD","10"=>"DL","11"=>"GA","12"=>"GJ","13"=>"HR","14"=>"HP","15"=>"JK","16"=>"JH","17"=>"KA","19"=>"KL","20"=>"LD","21"=>"MP","22"=>"MH","23"=>"MN","24"=>"ML","25"=>"MZ","26"=>"NL","29"=>"OR","31"=>"PY","32"=>"PB","33"=>"RJ","34"=>"SK","35"=>"TN","36"=>"TS","37"=>"TR","38"=>"UP","39"=>"UK","41"=>"WB","3919"=>"AL","3920"=>"AK","3921"=>"AZ","3922"=>"AR","3924"=>"CA","3926"=>"CO","3927"=>"CT","3928"=>"DE","3930"=>"FL","3931"=>"GA","3932"=>"HI","3933"=>"ID","3934"=>"IL","3935"=>"IN","3936"=>"IA","3937"=>"KS","3938"=>"KY","3939"=>"LA","3941"=>"ME","3942"=>"MD","3943"=>"MA","3945"=>"MI","3946"=>"MN","3947"=>"MS","3948"=>"MO","3949"=>"MT","3950"=>"NE","3951"=>"NV","3952"=>"NH","3953"=>"NJ","3955"=>"NM","3956"=>"NY","3957"=>"NC","3958"=>"ND","3959"=>"OH","3960"=>"OK","3962"=>"OR","3963"=>"PA","3965"=>"RI","3966"=>"SC","3967"=>"SD","3969"=>"TN","3970"=>"TX","3972"=>"UT","3973"=>"VT","3974"=>"VA","3975"=>"WA","3976"=>"WV","3977"=>"WI","3978"=>"WY");
	   $deleteStateIds = array("3923","3925","3940","3944","3961","3929","3954","3964","3968","3971");

		foreach($stateCodes as $key=>$val) {
			DB::table('states')->where('id', $key)->update(['short_code' => $val]);
		}
		DB::table('states')->whereIn('id', $deleteStateIds)->delete();
    }
}
