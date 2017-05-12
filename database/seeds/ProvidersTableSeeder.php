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
                'display_name' => 'Cost Calculator Admin',
                'allowed_file_types' => '["1", "2", "3", "4", "5"]',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );
    }
}
