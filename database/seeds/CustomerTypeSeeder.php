<?php

use Illuminate\Database\Seeder;

class CustomerTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('customer_type')->insert([
            [
                'name' => 'Individual Customer',
                'status' => '1',
            ],
            [
                'name' => 'Business Customer',
                'status' => '1',
            ],
            [
                'name' => 'Member',
                'status' => '0',
            ]

        ]);

    }
}
