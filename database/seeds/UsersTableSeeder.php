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
                'name' => 'Jack Segal',
                'email' => 'jackbsegal@gmail.com',
                'remember_token' => str_random(10),
                'password' => bcrypt('HelloWorld'),
                'permissions' => '["uploadFile","reports","clientLookup","settings"]',
                'active' => '1',
                'provider_code' => 'hounslow-madm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );

        DB::table('users')->insert([
                'name' => 'Owen Kennedy',
                'email' => 'owen.kennedy@hounslow.gov.uk',
                'remember_token' => str_random(10),
                'password' => bcrypt('HelloWorld'),
                'permissions' => '["uploadFile","reports","clientLookup","settings"]',
                'active' => '1',
                'provider_code' => 'hounslow-madm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );

        DB::table('users')->insert([
                'name' => 'Andy Strange',
                'email' => 'andy.strange@hounslow.gov.uk',
                'remember_token' => str_random(10),
                'password' => bcrypt('HelloWorld'),
                'permissions' => '["uploadFile","reports","clientLookup","settings"]',
                'active' => '1',
                'provider_code' => 'hounslow-madm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );

        DB::table('users')->insert([
                'name' => 'Ingrid Karikari',
                'email' => 'ingridkarikari@gmail.com',
                'remember_token' => str_random(10),
                'password' => bcrypt('HelloWorld'),
                'permissions' => '["uploadFile","reports","clientLookup","settings"]',
                'active' => '1',
                'provider_code' => 'hounslow-madm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );

        DB::table('users')->insert([
                'name' => 'Lucy Watt',
                'email' => 'lucy.e.watt@gmail.com',
                'remember_token' => str_random(10),
                'password' => bcrypt('HelloWorld'),
                'permissions' => '["uploadFile","reports","clientLookup","settings"]',
                'active' => '1',
                'provider_code' => 'hounslow-madm',
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]
        );
    }
}
