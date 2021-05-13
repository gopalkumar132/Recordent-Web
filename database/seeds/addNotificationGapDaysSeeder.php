<?php

use Illuminate\Database\Seeder;

class addNotificationGapDaysSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('settings')->insert([
            [
                'key' => 'admin_notification_gapdays',
                'display_name' => 'gapdays',
                'value' => '6',
                'group' => 'admin',
            ],
        ]);
    }
}
