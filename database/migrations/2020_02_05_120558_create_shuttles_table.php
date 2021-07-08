<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShuttlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shuttles', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('Companies');

            $table->unsignedBigInteger('shift_id')->nullable();
            $table->foreign('shift_id')->references('id')->on('shifts');

            $table->unsignedTinyInteger('airport_id');
            $table->foreign('airport_id')->references('id')->on('airports');

            $table->unsignedTinyInteger('car_model_id')->nullable();
            $table->foreign('car_model_id')->references('id')->on('car_models');

            $table->unsignedTinyInteger('station_id');
            $table->foreign('station_id')->references('id')->on('stations');

            $table->unsignedTinyInteger('payment_type_id')->nullable();
            $table->foreign('payment_type_id')->references('id')->on('payment_types');

            $table->timestamp('shuttle_start_time')->nullable();
            $table->timestamp('shuttle_end_time')->nullable();
            $table->mediumInteger('number_seats');
            $table->mediumInteger('number_of_booking')->nullable();
            $table->text('address_start_point')->nullable();
            $table->text('address_destination')->nullable();
            $table->mediumText('GPS_starting_point')->nullable();
            $table->mediumText('GPS_destination')->nullable();
            $table->enum('type',['departure', 'arrival']);
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
        Schema::dropIfExists('shuttles');
    }
}
