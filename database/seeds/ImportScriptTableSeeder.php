<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;


class ImportScriptTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('import_scripts')->insert([
                'script_path' => 'App\Jobs\ImportAdultSocialCareServices',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );

        DB::table('import_scripts')->insert([
                'script_path' => 'App\Jobs\ImportTempAccom',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );

        DB::table('import_scripts')->insert([
                'script_path' => 'App\Jobs\ImportHousingBenefitSwitch',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );

        DB::table('import_scripts')->insert([
                'script_path' => 'App\Jobs\ImportTroubledFamilies',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );
    }
}
