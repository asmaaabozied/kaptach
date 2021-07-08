<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoresTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stores', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('transfer_id');
            $table->foreign('transfer_id')->references('id')->on('transfers');

            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('Companies');

            $table->unsignedBigInteger('driver_id')->nullable();
            $table->foreign('driver_id')->references('id')->on('drivers');

            $table->bigInteger('seller_id');
            $table->string('seller_type',150);

            $table->bigInteger('buyable_id')->nullable();
            $table->string('buyable_type',150)->nullable();

            $table->char('store_for',1)->comment('1=>drivers,2=>Companies,3=>both');
            $table->enum('type',['sale','exchange']);
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
        Schema::dropIfExists('stores');
    }
}
