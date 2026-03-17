<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BundleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $bundles = [
            [
                'name' => 'Bundle 1',
                'start_time' => '2024-07-01 08:00:00',
                'duration' => '01:00:00',
                'value' => 100.00,
                'description' => 'Description for Bundle 1',
                'category_id' => 1,
            ],
            [
                'name' => 'Bundle 2',
                'start_time' => '2024-07-02 09:00:00',
                'duration' => '01:30:00',
                'value' => 150.00,
                'description' => 'Description for Bundle 2',
                'category_id' => 2,
            ],
            [
                'name' => 'Bundle 3',
                'start_time' => '2024-07-03 10:00:00',
                'duration' => '02:00:00',
                'value' => 200.00,
                'description' => 'Description for Bundle 3',
                'category_id' => 3,
            ],
            [
                'name' => 'Bundle 4',
                'start_time' => '2024-07-04 11:00:00',
                'duration' => '02:30:00',
                'value' => 250.00,
                'description' => 'Description for Bundle 4',
                'category_id' => 4,
            ],
        ];
        
    }
}
