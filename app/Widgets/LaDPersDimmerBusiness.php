<?php

namespace App\Widgets;
use Arrilot\Widgets\AbstractWidget;
use Illuminate\Support\Str;
use TCG\Voyager\Facades\Voyager;
use DB;
use Auth;

class LaDPersDimmerBusiness extends AbstractWidget
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
        /*$select = "SELECT due_date,added_by,deleted_at,student_id from student_due_fees";
		if(!Auth::user()->hasRole('admin')){
			$select.= "WHERE added_by = Auth::id()";
		}
        $select.= "GROUP BY student_due_fees.student_id";*/
		/*$records = \App\Students::join(DB::raw('(SELECT due_date,added_by,deleted_at,student_id from student_due_fees GROUP BY student_due_fees.student_id) due'),function($q){
				$q->on('students.id','=','due.student_id');
				$q->whereNull('due.deleted_at');
        		if(!Auth::user()->hasRole('admin')){
        			$q->where('due.added_by',Auth::id());
        		}
				
			});*/
			
		$records = \App\BusinessDueFees::whereNull('deleted_at');
		
		if(!Auth::user()->hasRole('admin')){
			$records = $records->where('added_by',Auth::id());
		}
		//$records = $records->groupBy('business_id')->get();
		$records = $records->get();
        $count = $records->count();
        $string = "<div class='total-amount-due'><span class='someeeee'>Number of Due Records (Business Customers) </span></div>"; //trans_choice('voyager::dimmer.page', $count);

        return view('voyager::dimmer', array_merge($this->config, [
            'icon'   => 'voyager-file-text',
            'title'  => " {$string} <div class='total-amount-due'><span class='someeeee'>{$count}</span></div>",
            'text'   => "",//__('voyager::dimmer.page_text', ['count' => $count, 'string' => Str::lower($string)]),
            'button' => [
                'text' => "",//__('voyager::dimmer.page_link_text'),
                'link' => "",//route('voyager.pages.index'),
            ],
            'image' => 'ladpers_business.jpg',
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
