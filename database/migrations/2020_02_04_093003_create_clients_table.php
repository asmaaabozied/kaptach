<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();

            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies');

            $table->unsignedTinyInteger('station_id')->nullable();
            $table->foreign('station_id')->references('id')->on('stations');

            $table->string('name', 100);
            $table->string('slug', 100)->nullable();
            $table->string('code', 10)->nullable();
            $table->string('website', 150)->nullable();
            $table->bigInteger('contact_phone')->nullable();
            $table->string('contact_email', 50)->nullable();
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lang', 11, 8)->nullable();
            $table->text('address')->nullable();
            $table->string('logo', 255)->nullable();
            $table->boolean('status')->default(1);
            $table->enum('type', ['hotel', 'tourism_company']);
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
        Schema::dropIfExists('clients');
    }
}
