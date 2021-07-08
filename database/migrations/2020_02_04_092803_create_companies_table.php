<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->foreign('parent_id')->references('id')->on('companies');
            $table->string('name', 100);
            $table->string('slug', 100)->nullable();
            $table->string('code', 10)->nullable();
            $table->string('website', 150)->nullable();
            $table->bigInteger('contact_phone')->nullable();
            $table->string('contact_email', 50)->nullable();
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lang', 11, 8)->nullable();
            $table->text('address')->nullable();
            $table->boolean('receive_request_from_drivers')->default(0);
            $table->string('logo', 255)->nullable();
            $table->boolean('status')->default(1);
            $table->enum('type', ['personal', 'commercial']);
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
        Schema::dropIfExists('companies');
    }
}
