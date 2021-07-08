<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTourPriceListsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tour_price_lists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies');
            $table->unsignedTinyInteger('car_model_id');
            $table->foreign('car_model_id')->references('id')->on('car_models');
            $table->string('tourism_place',100);
            $table->boolean('with_food');
            $table->string('time_spend',30);
            $table->decimal('price',10,2);
            $table->timestamp('tours_start_time')->nullable();
            $table->timestamp('tours_end_time')->nullable();
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
        Schema::dropIfExists('tour_price_lists');
    }
}
