<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->string('username', 50)->unique();
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('email', 50)->unique();
            $table->string('password', 255);
            $table->integer('phone')->nullable();
            $table->enum('gender', ['male', 'female'])->default('male');
            $table->string('email_verification_code','6');
            $table->boolean('email_verified')->default(0);
            $table->string('api_token',80);
            $table->string('image', 255)->nullable();
            $table->boolean('status');
            $table->string('remember_token',255)->nullable();
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
        Schema::dropIfExists('users');
    }
}
