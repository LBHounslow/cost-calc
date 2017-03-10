<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
                'name' => 'Jane Doe',
                'email' => 'jane.doe@test.gov.uk',
                'remember_token' => str_random(10),
                'password' => bcrypt('12345678'),
                'permissions' => '["uploadFile","reports","clientLookup","settings"]',
                'active' => '1',
                'provider_id' => 1,
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );

    }
}
