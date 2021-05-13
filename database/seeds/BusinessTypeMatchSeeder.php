<?php

use Illuminate\Database\Seeder;

class BusinessTypeMatchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $usertypes= DB::table('user_types')->get();
        
            foreach ($usertypes as $key => $value) {
        	
               if(in_array($value->name,array('Advertising','Agribusiness','Construction & Real Estate','Dry Cleaning & Laundry Business','DTH Service Provider','Entertainment & Film','Food & Beverage','Hospital','Hostel / Paying Guest','Hotel','Internet Service Provider','Logistics & Supply Chain','Mobile/Telecom Service Provider','Other Education Institutions','Paying Guest','Rental Company','School','Software/IT','Travel Tourism & Hospitality'))) {
        	             
        	       $user_update = $usertypes->where('name' ,'Service Provider')->first();
               }
           
               if(in_array($value->name,array('Others','Tele Caller'))){
                        
                   $user_update = $usertypes->where('name' ,'Others')->first();
               }
           
               if($value->name == 'Distributor/Stockist'){
        	
        	       $user_update = $usertypes->where('name' ,'Distributor')->first();
               }
	           
	           if($value->name == 'Manufacturer'){
	              
	               $user_update = $usertypes->where('name' ,'Manufacturer')->first();
	           }
	           
	           if($value->name == 'Wholesale and Retail'){
	        	
	           	   $user_update = $usertypes->where('name' ,'Wholesaler')->first();
	           }

	           if($value->name == 'Retailers/Dealers'){
	        	
	           	   $user_update = $usertypes->where('name' ,'Retailer / Dealer')->first();
	           }
				if(isset($value->id)) {
					DB::table('users')->where('user_type',$value->id)->update(['user_type' => $user_update->id]);
				}
            }
            $business_types = array('Advertising','Agribusiness','Construction & Real Estate','Dry Cleaning & Laundry Business','DTH Service Provider','Entertainment & Film','Food & Beverage','Hospital','Hostel / Paying Guest','Internet Service Provider','Logistics & Supply Chain','Mobile/Telecom Service Provider','Other Education Institutions','Paying Guest','Rental Company','School','Software/IT','Travel Tourism & Hospitality','Tele Caller','Distributor/Stockist','Wholesale and Retail','Retailers/Dealers','Hotel');
            foreach ($business_types as $value) {
               
                   DB::table('user_types')->where('name',$value)->update(['status' => '0']);
            }
     }	
    }
