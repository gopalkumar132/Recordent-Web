<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\WithHeadings;

use DB;
use Auth;
use Carbon\carbon;
use Log;
use App\Businesses;
use App\Students;
use App\CustomerCreditReportAnalysis;
use Maatwebsite\Excel\Concerns\FromArray;
class CreditreportExport implements FromArray, WithHeadings
{  
	protected $from_date;
  protected $to_date;
      public function __construct($from_date, $to_date) {
       $this->from_date = $from_date;
       $this->to_date = $to_date;
 }

 
 public function headings():array
 {
     return [
           'Sr.no',
           'Type of Customer',
           'Date',
           'Number of Views',
           'Total Number of Customers viewed'
           ];
 }
 public function array(): array
 {
  $authId = Auth::id();
  $final_array=array();
  if($this->from_date != 0  && $this->to_date != 0 ){

    $data = CustomerCreditReportAnalysis::where('created_at', '>=', $this->from_date)
                                        ->where('created_at', '<', $this->to_date)
                                        ->selectRaw('type,date(created_at) as viewedDate, sum(customer_viewed) as count , count(*) as total_customers_count')
                                        ->groupBy(['type','viewedDate'])
                                        ->get();

  }else{
    $data = CustomerCreditReportAnalysis::selectRaw('type,date(created_at) as viewedDate, sum(customer_viewed) as count ,count(*) as total_customers_count')
                                        ->groupBy(['type','viewedDate'])
                                        ->get();
  }    
        $i=1;
        foreach($data as $rec)
        {
            $prepara_array=array();
            $prepara_array['srno']=$i++;
            $prepara_array['type']=$rec['type'];
            $prepara_array['viewedDate']=$rec['viewedDate'];
            $prepara_array['count']=$rec['count'];
            $prepara_array['total_customers_count']=$rec['total_customers_count'];
            $final_array[]=$prepara_array;
        }

        usort($final_array,self::sortByDate('viewedDate'));
        return $final_array;
 }



 function sortByDate($key)
 {
     return function ($first, $second) use ($key) {
         $firstval = strtotime($first[$key]);
         $secondval = strtotime($second[$key]);
         return $firstval-$secondval;
     };
 }
   

 
}