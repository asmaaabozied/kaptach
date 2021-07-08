<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAdminsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admins', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->unsignedBigInteger('role_id');
            $table->foreign('role_id')->references('id')->on('roles');

            $table->bigInteger('adminable_id')->unsigned();
            $table->string('adminable_type', 100);

            $table->string('username', 50)->unique();
            $table->string('email', 50)->unique();
            $table->string('password', 255);
            $table->bigInteger('phone')->nullable();
            $table->enum('gender', ['male', 'female'])->default('male');
            $table->string('image', 255)->nullable();
            $table->enum('type', ['transfer_company', 'hotel', 'tourism_company']);
            $table->boolean('status')->default(1);
            $table->string('api_token',80)->nullable();
            $table->enum('locale',['en','tr','ar'])->default('en');
            $table->string('remember_token', 255)->nullable();
            $table->dateTime('last_login_at')->nullable();
            $table->string('last_login_ip',255)->nullable();
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
        Schema::dropIfExists('admins');
    }
}
