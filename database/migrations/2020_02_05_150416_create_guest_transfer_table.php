<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGuestTransferTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('guest_transfer', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('transfer_id');
            $table->foreign('transfer_id')->references('id')->on('transfers');

            $table->BigInteger('host_id')->nullable()->unsigned();
            $table->foreign('host_id')->references('id')->on('hosts');

            $table->unsignedBigInteger('guest_id');
            $table->foreign('guest_id')->references('id')->on('guests');

            $table->string('room_number',11)->nullable();

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
        Schema::dropIfExists('guest_transfer');
    }
}
