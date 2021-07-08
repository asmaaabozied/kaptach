<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statuses', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();

            $table->bigInteger("actors_id")->unsigned()->nullable();
            $table->string("actors_type",100)->nullable();

            $table->bigInteger('statusable_id')->unsigned()->nullable();
            $table->string('statusable_type', 100)->nullable();

            $table->timestamp('status_time');
            $table->string('status', 50);
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lang', 11, 8)->nullable();
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
        Schema::dropIfExists('statuses');
    }
}
