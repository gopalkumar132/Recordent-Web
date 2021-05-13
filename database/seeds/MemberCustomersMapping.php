<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;
use App\Students;
use App\StudentDueFees;
use App\Businesses;
use App\BusinessDueFees;

class MemberCustomersMapping extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $students = StudentDueFees::groupBy('student_id')->get();

        foreach ($students as $key => $student) {
        	CustomerHelper::insertIntoMemberCustomerIdMappingTable($student->added_by, $student->student_id, 1);
        }


        $businesses = BusinessDueFees::groupBy('business_id')->get();

        foreach ($businesses as $key => $business) {
        	CustomerHelper::insertIntoMemberCustomerIdMappingTable($business->added_by, $business->business_id, 2);
        }
    }
}
