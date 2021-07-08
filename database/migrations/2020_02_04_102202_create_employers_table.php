<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employers', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();

            $table->string('username', 50)->unique();
            $table->string('first_name', 50);
            $table->string('last_name', 50);
            $table->string('email', 50)->unique();
            $table->string('password', 255);
            $table->string('phone',50)->nullable();
            $table->enum('gender', ['male', 'female'])->default('male');
            $table->date('birth_date')->nullable();

            $table->boolean('working')->default(0);
            $table->string('api_token',80)->nullable();
            $table->string('platform')->nullable();
            $table->string('profile_pic', 255)->nullable();
            $table->enum('locale',['en','ar','tr'])->default('en');
            $table->enum('type', ['driver', 'host']);
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
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
        Schema::dropIfExists('employers');
    }
}
