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
        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number', 16)->unique();
            $table->string('sender_name', 100);
            $table->string('sender_email', 100);
            $table->text('sender_address');
            $table->string('sender_contact', 20);
            $table->string('recipient_name', 100);
            $table->string('recipient_email', 100);
            $table->text('recipient_address');
            $table->string('recipient_contact', 20);
            $table->string('cargo_name', 100);
            $table->text('cargo_description');
            $table->float('cargo_length')->nullable();
            $table->float('cargo_width')->nullable();
            $table->float('cargo_height')->nullable();
            $table->float('cargo_weight')->nullable();
            $table->string('current_status', 50)->default('Booking Received');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipments');
    }
};