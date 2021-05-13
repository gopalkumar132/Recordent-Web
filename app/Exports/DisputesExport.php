<?php

namespace App\Exports;

use App\User;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use DB;
use Auth;
use Carbon\carbon;
use App\UserPricingPlan;
use App\PricingPlan;
use App\Dispute;
use Log;

class DisputesExport implements FromCollection, WithHeadings
{
	protected $date;
      public function __construct($date) {
       $this->date = $date;
 }

    public function headings():array
    {
        return [
    		'Member Id',
			'Customer Id',
			'Customer Type',
			'Due Id',
    		'Comment',
    		'Status',
    		'Dispute Reason',
    		'Dispute Raised At',
    		'Action',
    		'Action Taken At'
		];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
    	$authId = Auth::id();
		$data = Dispute::select(
					'disputes.due_added_by',
					'disputes.customer_id',
					'disputes.customer_type',
					'disputes.due_id',
					'disputes.comment',
					DB::raw("IF(disputes.is_open=1, 'OPEN','CLOSED' ) As is_open"),
					'dispute_reasons.reason',
					DB::raw('DATE_FORMAT(disputes.created_at, "%d/%m/%Y") As dispute_created_at'),
					'disputes.action_taken',
					DB::raw('DATE_FORMAT(disputes.action_taken_at, "%d/%m/%Y") As action_taken_at')
					)
					->leftJoin('dispute_reasons','dispute_reasons.id','=','disputes.dispute_reason_id')
					->where(function($q){
								if(Auth::user()->role_id != 1 && Auth::user()->role_id != 14){
									$q->where('disputes.due_added_by',Auth::user()->id);
								}
							})
					->orderBy('disputes.id')
					->get();
			//dd($data);
				return $data;
    }
}
