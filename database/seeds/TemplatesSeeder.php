<?php

use Illuminate\Database\Seeder;

class TemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('templates')->insert([
            [
                'name' => 'templates-1_1-30',
                'content' => 'Your payment to <<member_name_15_char>>,<<city_name_10_char>> shows as overdue on Recordent. You may want to clear the overdue payment.To know more, click here <<report_link>>',
                'type' => 'SMS',
                'variables' => 'member_name_15_char,city_name_10_char,report_link',
            ],
            [
                'name' => 'templates-1_31-60',
                'content' => 'Your payment to <<member_name_15_char>>,<<city_name_10_char>> is overdue by <<overdue_bucket>>+ days and still shows as unpaid on Recordent. To know more, click here <<report_link>>',
                'type' => 'SMS',
                'variables' => 'member_name_15_char,city_name_10_char,overdue_bucket,report_link',
            ],
            [
                'name' => 'templates-1_61-90',
                'content' => 'Your payment to <<member_name_15_char>>,<<city_name_10_char>> that was reported on Recordent is overdue by <<overdue_bucket>>+Days.You are advised to pay. To know more, click here <<report_link>>',
                'type' => 'SMS',
                'variables' => 'member_name_15_char,city_name_10_char,overdue_bucket,report_link',
            ],
            [
                'name' => 'templates-1_91-180',
                'content' => 'Your payment to <<member_name_15_char>>,<<city_name_10_char>> is overdue by <<overdue_bucket>>+days and still shows as unpaid on Recordent,pay immediately. To know more, click here <<report_link>',
                'type' => 'SMS',
                'variables' => 'member_name_15_char,city_name_10_char,overdue_bucket,report_link',
            ],
            [
                'name' => 'templates-1_180-210',
                'content' => 'Your payment to <<member_name_15_char>>,<<city_name_10_char>> is overdue by <<overdue_bucket>>+days and still shows as unpaid on Recordent. To know more, click here <<report_link>>',
                'type' => 'SMS',
                'variables' => 'member_name_15_char,city_name_10_char,overdue_bucket,report_link',
            ],
            [
                'name' => 'templates-2_1-30',
                'content' => 'Your overdue payment to <<member_name_15_char>>,<<city_name_10_char>> on Recordent can be seen by other businesses with your consent. To know more click here <<report_link>>',
                'type' => 'SMS',
                'variables' => 'member_name_15_char,city_name_10_char,report_link',
            ],
            [
                'name' => 'templates-2_31-60',
                'content' => 'Your overdue payment to <<member_name_15_char>>,<<city_name_10_char>> on Recordent can be seen & treated as negative by other businesses. To know more click here <<report_link>>',
                'type' => 'SMS',
                'variables' => 'member_name_15_char,city_name_10_char,report_link',
            ],
            [
                'name' => 'templates-2_61-90',
                'content' => 'Your overdue payment to <<member_name_15_char>>,<<city_name_10_char>> on Recordent can be seen & will be treated as negative by other businesses. Click for details <<report_link>>',
                'type' => 'SMS',
                'variables' => 'member_name_15_char,city_name_10_char,report_link',
            ],
            [
                'name' => 'templates-2_91-180',
                'content' => 'Your overdue payment to <<member_name_15_char>>,<<city_name_10_char>> on Recordent can be seen by other businesses & may not offer you credit/loan. Click for details <<report_link>>',
                'type' => 'SMS',
                'variables' => 'member_name_15_char,city_name_10_char,report_link',
            ],
            [
                'name' => 'templates-2_180-210',
                'content' => 'Your overdue payment to <<member_name_15_char>>,<<city_name_10_char>> on Recordent can be seen by other businesses & will not offer you credit/loan. Click for details <<report_link>>',
                'type' => 'SMS',
                'variables' => 'member_name_15_char,city_name_10_char,report_link',
            ],
            [
                'name' => 'templates-3_1-30',
                'content' => 'Your payment to <<member_name_15_char>>,<<city_name_10_char>> still shows as overdue on Recordent. You may want to clear the overdue payment. To know more click here <<report_link>>',
                'type' => 'SMS',
                'variables' => 'member_name_15_char,city_name_10_char,report_link',
            ],
            [
                'name' => 'templates-3_31-60',
                'content' => 'Your payment to <<member_name_15_char>>,<<city_name_10_char>> still shows as unpaid on Recordent & is overdue by <<overdue_bucket>>+days. Pay at the earliest.Click for details <<report_link>>',
                'type' => 'SMS',
                'variables' => 'member_name_15_char,city_name_10_char,overdue_bucket,report_link',
            ],
            [
                'name' => 'templates-3_61-90',
                'content' => 'Your payment to <<member_name_15_char>>,<<city_name_10_char>> that was reported on Recordent is now overdue by 60+days.Pay at the earliest. To know more click here <<report_link>>',
                'type' => 'SMS',
                'variables' => 'member_name_15_char,city_name_10_char,report_link',
            ],
            [
                'name' => 'templates-3_91-180',
                'content' => 'Your payment to <<member_name_15_char>>,<<city_name_10_char>> still shows as unpaid on Recordent despite reminders & is overdue by <<overdue_bucket>>+days. Pay now. Click for details <<report_link>>',
                'type' => 'SMS',
                'variables' => 'member_name_15_char,city_name_10_char,overdue_bucket,report_link',
            ],
            [
                'name' => 'templates-3_180-210',
                'content' => 'Your payment to <<member_name_15_char>>,<<city_name_10_char>> still shows as unpaid on Recordent & further action may be taken on <<overdue_bucket>>+days overdue. Click for details <<report_link>>',
                'type' => 'SMS',
                'variables' => 'member_name_15_char,city_name_10_char,overdue_bucket,report_link',
            ],
            [
                'name' => 'templates-4_1-30',
                'content' => 'Your payment to <<member_name_15_char>>,<<city_name_10_char>> still shows as overdue on Recordent. This is another reminder to make the payment. Click for details <<report_link>>',
                'type' => 'SMS',
                'variables' => 'member_name_15_char,city_name_10_char,report_link',
            ],
            [
                'name' => 'templates-4_31-60',
                'content' => 'Your overdue payment to <<member_name_15_char>>,<<city_name_10_char>> still shows as unpaid on Recordent.Please pay to ensure clean payment record. Click for details <<report_link>>',
                'type' => 'SMS',
                'variables' => 'member_name_15_char,city_name_10_char,report_link',
            ],
            [
                'name' => 'templates-4_61-90',
                'content' => 'Your overdue payment to <<member_name_15_char>>,<<city_name_10_char>> still shows as unpaid on Recordent.Pay now to avoid negative payment record. Click for details <<report_link>>',
                'type' => 'SMS',
                'variables' => 'member_name_15_char,city_name_10_char,report_link',
            ],
            [
                'name' => 'templates-4_91-180',
                'content' => 'Your overdue payment to <<member_name_15_char>>,<<city_name_10_char>> still shows as unpaid on Recordent.Pay today to improve your payment record. Click for details <<report_link>>',
                'type' => 'SMS',
                'variables' => 'member_name_15_char,city_name_10_char,report_link',
            ],
            [
                'name' => 'templates-4_180-210',
                'content' => 'Your overdue payment to <<member_name_15_char>>,<<city_name_10_char>> still shows as unpaid on Recordent.Pay & get your negative record corrected. Click for details <<report_link>>',
                'type' => 'SMS',
                'variables' => 'member_name_15_char,city_name_10_char,report_link',
            ],
            [
                'name' => 'templates-5_1-30',
                'content' => 'Your payment to <<member_name_15_char>>,<<city_name_10_char>> still shows as overdue on Recordent, many payment reminders were sent earlier,you are advised to pay. For report click here <<report_link>>',
                'type' => 'SMS',
                'variables' => 'member_name_15_char,city_name_10_char,report_link',
            ],
            [
                'name' => 'templates-5_31-60',
                'content' => 'Your overdue payment to <<member_name_15_char>>,<<city_name_10_char>> is still unpaid on Recordent,reminders were sent before,make payment at the earliest. For report click here <<report_link>>',
                'type' => 'SMS',
                'variables' => 'member_name_15_char,city_name_10_char,report_link',
            ],
            [
                'name' => 'templates-5_61-90',
                'content' => 'Your overdue payment to <<member_name_15_char>>,<<city_name_10_char>> is still unpaid on Recordent despite many reminders,please pay now.Click for details <<report_link>>',
                'type' => 'SMS',
                'variables' => 'member_name_15_char,city_name_10_char,report_link',
            ],
            [
                'name' => 'templates-5_91-180',
                'content' => 'Your payment to <<member_name_15_char>>,<<city_name_10_char>> is overdue by <<overdue_bucket>>+days and is still unpaid on Recordent despite reminders,pay now.Click for details <<report_link>>',
                'type' => 'SMS',
                'variables' => 'member_name_15_char,city_name_10_char,overdue_bucket,report_link',
            ],
            [
                'name' => 'templates-5_180-210',
                'content' => 'Your payment to <<member_name_15_char>>,<<city_name_10_char>> that is overdue by <<overdue_bucket>>+ days is still unpaid on Recordent,action may be taken if not paid.Click for details <<report_link>>',
                'type' => 'SMS',
                'variables' => 'member_name_15_char,city_name_10_char,overdue_bucket,report_link',
            ],
            [
                'name' => 'introductory-overdue_1-30',
                'content' => '<<member_name_15_char>>,<<city_name_10_char>> has recorded your overdue payment on Recordent and is overdue by <<overdue_bucket>> days. To know more, click here <<report_link>>',
                'type' => 'SMS',
                'variables' => 'member_name_15_char,city_name_10_char,overdue_bucket,report_link',
            ],
            [
                'name' => 'introductory_not_overdue',
                'content' => '<<member_name_15_char>>,<<city_name_10_char>> has recorded your payment details on Recordent and is payable by <<latest_due_date>>. To know more, click here <<report_link>>',
                'type' => 'SMS',
                'variables' => 'member_name_15_char,city_name_10_char,latest_due_date,report_link',
            ],
            [
                'name' => 'ongoing-message_90',
                'content' => 'Your payment to <<member_name_15_char>>,<<city_name_10_char>> is overdue by <<overdue_bucket>>+ days and still shows as unpaid on Recordent pay immediately. To know more, click here <<report_link>>',
                'type' => 'SMS',
                'variables' => 'member_name_15_char,city_name_10_char,overdue_bucket,report_link',
            ],
            [
                'name' => 'introductory_overdue',
                'content' => '<<member_name_15_char>>,<<city_name_10_char>> has reported your overdue payment on Recordent and is overdue by <<overdue_bucket>> days.To know more, click here <<report_link>>',
                'type' => 'SMS',
                'variables' => 'member_name_15_char,city_name_10_char,overdue_bucket,report_link',
            ],

        ]);

    }
}
