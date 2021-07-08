<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfers', function (Blueprint $table) {
            $table->bigIncrements('id')->unsigned();
            $table->unsignedBigInteger('requested_by_admin')->nullable();
            $table->foreign('requested_by_admin')->references('id')->on('admins');

            $table->unsignedBigInteger('took_action_by_admin')->nullable();
            $table->foreign('took_action_by_admin')->references('id')->on('admins');

            $table->unsignedBigInteger('company_id');
            $table->foreign('company_id')->references('id')->on('companies');

            $table->unsignedTinyInteger('car_model_id');
            $table->foreign('car_model_id')->references('id')->on('car_models');

            $table->unsignedBigInteger('car_id')->nullable();
            $table->foreign('car_id')->references('id')->on('cars');

            $table->unsignedBigInteger('shift_id')->nullable();
            $table->foreign('shift_id')->references('id')->on('shifts');

            $table->unsignedBigInteger('host_id')->nullable();
            $table->foreign('host_id')->references('id')->on('hosts');

            $table->unsignedBigInteger('driver_id')->nullable();
            $table->foreign('driver_id')->references('id')->on('drivers');

            $table->unsignedTinyInteger('airport_id');
            $table->foreign('airport_id')->references('id')->on('airports');

            $table->unsignedTinyInteger('payment_type_id')->nullable();
            $table->foreign('payment_type_id')->references('id')->on('payment_types');

            $table->bigInteger('transferable_id');
            $table->string('transferable_type', 100);

            $table->string('flight_number', 50)->nullable();
            $table->timestamp('flight_departure_time')->nullable();
            $table->timestamp('flight_arrival_time')->nullable();
            $table->timestamp('transfer_start_time')->nullable();;
            $table->timestamp('transfer_end_time')->nullable();;
            $table->text('address_starting_point')->nullable();
            $table->text('address_destination')->nullable();
            $table->mediumText('GPS_starting_point')->nullable();
            $table->mediumText('GPS_destination')->nullable();
            $table->mediumInteger('number_seats');
            $table->mediumInteger('number_of_booking')->nullable();
            $table->decimal('price', 10, 2);

            $table->bigInteger('cancellable_id')->nullable();
            $table->string('cancellable_type', 100)->nullable();
            $table->boolean('cancelled')->default(0);
            $table->text('cancel_reason')->nullable();
            $table->dateTime('cancellation_date')->nullable();

            $table->enum('status', ['Pending', 'Start', 'Guest Received', 'Call Driver', 'Driver Replied', 'Guest Delivered', 'End', 'Stop'])->default('pending');
            $table->boolean('sold')->default(0);
            $table->enum('type', ['departure', 'arrival']);
            $table->boolean('request_status')->default(0);
            $table->enum('driver_acceptance', ['0', '1', '2'])->default(0);
            $table->enum('host_status', ['Pending', 'Accept', 'Guest Delivered'])->default('Pending');
            $table->text('notes')->nullable();
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
        Schema::dropIfExists('transfers');
    }
}
