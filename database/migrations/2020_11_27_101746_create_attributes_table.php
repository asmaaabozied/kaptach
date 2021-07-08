<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('exchange_id');
            $table->foreign('exchange_id')->references('id')->on('exchanges');

            $table->dateTime('from_date')->nullable();
            $table->dateTime('to_date')->nullable();

            $table->unsignedTinyInteger('airport_id')->nullable();
            $table->foreign('airport_id')->references('id')->on('airports');

            $table->string('type')->nullable();

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
        Schema::dropIfExists('attributes');
    }
}
