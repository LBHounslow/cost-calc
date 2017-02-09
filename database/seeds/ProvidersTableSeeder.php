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
                'code' => 'hounslow-madm',
                'display_name' => 'Hounslow MADM',
                'allowed_file_types' => '["h01","asc01"]',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );

        DB::table('providers')->insert([
                'code' => 'hounslow-housing',
                'display_name' => 'Hounslow Housing',
                'allowed_file_types' => '["h01"]',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );

        DB::table('providers')->insert([
                'code' => 'hounslow-adult-social-care',
                'display_name' => 'Hounslow Adult Social Care',
                'allowed_file_types' => '["asc01"]',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );

        DB::table('providers')->insert([
                'code' => 'west-london-mental-health',
                'display_name' => 'West London Mental Health',
                'allowed_file_types' => '[]',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );
    }
}
