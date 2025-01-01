<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->unsignedBigInteger('booking_id')->autoIncrement();
            $table->unsignedBigInteger('hotel_id')->comment('Foreign key from hotels');
            $table->string('customer_name')->comment('Customer full name');
            $table->string('customer_contact')->comment('Customer contact information');
            $table->dateTime('checkin_time')->comment('Check-in time');
            $table->dateTime('checkout_time')->comment('Check-out time');
            $table->timestamps();

            $table->foreign('hotel_id')
                ->references('hotel_id')
                ->on('hotels')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
