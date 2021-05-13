<?php

namespace App\Widgets;
use Arrilot\Widgets\AbstractWidget;
use Illuminate\Support\Str;
use TCG\Voyager\Facades\Voyager;
use App\Businesses;
use App\BusinessDueFees;
use App\BusinessPaidFees;
use DB;
use Auth;

class DueDimmerBusiness extends AbstractWidget
{
    /**
     * The configuration array.
     *
     * @var array
     */
    protected $config = [];

    /**
     * Treat this method as a controller action.
     * Return view() or other content to display.
     */
    public function run()
    {

		$totalDueFee = \App\BusinessDueFees::select(DB::raw('sum(due_amount) as Due'));
		$totalPaidFee = \App\BusinessPaidFees::select(DB::raw('sum(paid_amount) as Paid'));

        if(!Auth::user()->hasRole('admin')){
            $totalDueFee = $totalDueFee->where('added_by',Auth::id());
            $totalPaidFee = $totalPaidFee->where('added_by',Auth::id());
        }
        $totalDueFee = $totalDueFee->whereNull('deleted_at')->first();
        $totalPaidFee = $totalPaidFee->whereNull('deleted_at')->first();

		/*$records = \App\Students::select()leftJoin(DB::raw('(SELECT sum(student_due_fees.due_amount) AS da,due_date,added_by,deleted_at,student_id from student_due_fees GROUP BY student_due_fees.student_id) due'),function($q){
										$q->on('students.id','=','due.student_id');
										$q->where('due.deleted_at','=',null);
										//$q->where('due.added_by',Auth::id());
									})
								->leftJoin(DB::raw('(SELECT sum(student_paid_fees.paid_amount) AS pa,added_by,deleted_at,student_id from student_paid_fees GROUP BY student_paid_fees.student_id) paid'),function($q) {
									$q->on('students.id','=','paid.student_id');
									$q->where('paid.deleted_at','=',null);
									//$q->where('paid.added_by',Auth::id());
								});*/
		
        // $TotalDue = $totalDueFee->Due - $totalPaidFee->Paid;
        $TotalDue = $totalDueFee->Due;

        $count = number_format($TotalDue);//$records->count();
        $string = "<div class='total-amount-due'><span class='someeeee'>Total Value of Business Customer Dues</span></div>"; //trans_choice('voyager::dimmer.page', $count);

        return view('voyager::dimmer', array_merge($this->config, [
            'icon'   => 'voyager-dollar',
            'title'  => " {$string} <div class='total-amount-due'><span class='someeeee'>INR.{$count} </span></div>",
            'text'   => "",//__('voyager::dimmer.page_text', ['count' => $count, 'string' => Str::lower($string)]),
            'button' => [
                'text' => "",//__('voyager::dimmer.page_link_text'),
                'link' => "",//route('voyager.pages.index'),
            ],
            'image' => 'payment_business.jpg',
            'helper' => '',
        ]));
    }

    /**
     * Determine if the widget should be displayed.
     *
     * @return bool
     */
    public function shouldBeDisplayed()
    {
       return true;
    }
}
