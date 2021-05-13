<?php

use Illuminate\Database\Seeder;
use App\InvoiceType;

class InvoiceTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $items = [            
            ['id' => 1, 'title' => 'Membership'],
            ['id' => 2, 'title' => 'Individual Recordent Report'],
            ['id' => 3, 'title' => 'Individual Comprehensive Report'],
            ['id' => 4, 'title' => 'Business Recordent Report'],
            ['id' => 5, 'title' => 'Business Comprehensive Report'],
            ['id' => 6, 'title' => 'Collection Fee'],
            ['id' => 7, 'title' => 'Membership Upgrade'],
            ['id' => 8, 'title' => 'Additional Customer Dues'],
            ['id' => 9, 'title' => 'US Business Credit Report']
        ];

        foreach ($items as $item) {
            InvoiceType::updateOrCreate(['id' => $item['id']], $item);
        }
    }
}
