<?php

use Illuminate\Database\Seeder;

class CompanyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('Companies')->insert([
            'name' => 'kaptan',
            'slug' => 'kaptan',
            'type' => 'commercial',
            'status' => 1,
            'receive_request_from_drivers' => 1,
        ]);
    }
}
