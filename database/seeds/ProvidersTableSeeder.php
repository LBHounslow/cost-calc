<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class ProvidersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('providers')->insert([
                'display_name' => 'Hounslow MADM',
                'allowed_file_types' => '["1","2", "3", "4"]',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );

        DB::table('providers')->insert([
                'display_name' => 'Hounslow Housing',
                'allowed_file_types' => '["1", "3", "4"]',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );

        DB::table('providers')->insert([
                'display_name' => 'Hounslow Adult Social Care',
                'allowed_file_types' => '["2"]',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );

        DB::table('providers')->insert([
                'display_name' => 'West London Mental Health',
                'allowed_file_types' => '[]',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );
    }
}
