<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAirportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('airports', function (Blueprint $table) {
            $table->tinyIncrements('id')->unsigned();
            $table->unsignedTinyInteger('station_id');
            $table->foreign('station_id')->references('id')->on('stations');
            $table->string('name', 100);
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lang', 11, 8)->nullable();
            $table->text('address')->nullable();
            $table->string('arrival_image',255);
            $table->string('departure_image',255);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('airports');
    }
}
