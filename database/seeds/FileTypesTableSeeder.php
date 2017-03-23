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
                'display_name' => 'Temporary Accommodation',
                'import_script_id' => 1,
                'import_model_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );

        DB::table('file_types')->insert([
                'display_name' => 'Adult Social Care',
                'import_script_id' => 2,
                'import_model_id' => 2,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );

        DB::table('file_types')->insert([
                'display_name' => 'Housing Benefit Switch',
                'import_script_id' => 3,
                'import_model_id' => 3,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );

        DB::table('file_types')->insert([
                'display_name' => 'Troubled Families',
                'import_script_id' => 4,
                'import_model_id' => 4,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );

        DB::table('file_types')->insert([
                'display_name' => 'Housing Benefit Entitle',
                'import_script_id' => 5,
                'import_model_id' => 5,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );


    }
}
