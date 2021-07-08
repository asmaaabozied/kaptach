<?php

use App\Station;
use Illuminate\Database\Seeder;

class StationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Station::create(['name' => 'SULTAN AHMET'], ['name' => 'SIRKECI']);
    }
}
