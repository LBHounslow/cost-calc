<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class FileTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('file_types')->insert([
                'code' => 'h01',
                'display_name' => 'Temporary accommodation',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );

        DB::table('file_types')->insert([
                'code' => 'asc01',
                'display_name' => 'Adult social care services provided',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );

        /*DB::table('file_types')->insert([
                'code' => 'rb01',
                'display_name' => 'Housing Benefit Entitlement',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );*/

    }
}
