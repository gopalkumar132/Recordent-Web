<?php

use Illuminate\Database\Seeder;

class NotificationTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('notification_type')->insert([
            [
                'name' => 'SMS',
                'status' => '1',
            ],
            [
                'name' => 'IVR',
                'status' => '0',
            ],
            [
                'name' => 'Email',
                'status' => '0',
            ]

        ]);

    }
}
