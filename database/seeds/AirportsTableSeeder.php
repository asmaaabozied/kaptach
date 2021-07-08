<?php

use App\Airport;
use Illuminate\Database\Seeder;

class AirportsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Airport::create(
            [
                'station_id' => 1,
                'name' => 'ISTANBUL',
                'lat' => '41.25925379',
                'lang' => '28.73037378',
                'address'=>'Tayakadın, İstanbul Havalimanı, 34277 Arnavutköy/İstanbul, Turkey',
                'arrival_image'=>'img-1601907916.jpeg',
                'departure_image'=>'img-1601907916.jpeg'
            ],
            [
                'station_id' => 2,
                'name' => 'SABIHA',
                'lat' => '41.25925379',
                'lang' => '28.73037378',
                'address'=>'Tayakadın, Istanbul Airport (IST), Terminal Caddesi No:1, 34283 Arnavutköy/İstanbul, Turkey',
                'arrival_image'=>'img-1601907916.jpeg',
                'departure_image'=>'img-1601907916.jpeg'
            ]
        );
    }
}
