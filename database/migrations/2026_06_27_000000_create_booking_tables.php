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
        Schema::create('booking_master', function (Blueprint $table) {
            $table->id();
            $table->string('booking_reference')->nullable()->unique();
            $table->string('payment_type');
            $table->string('payment_currency', 3);
            $table->decimal('payment_amount', 10, 2);
            $table->dateTime('booking_date');
            $table->string('user_email')->nullable();
            $table->string('user_phone')->nullable();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('transaction_id')->nullable();
            $table->string('status')->default('pending'); // pending, completed, failed
            $table->timestamps();
        });

        Schema::create('booking_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('booking_master')->onDelete('cascade');
            $table->unsignedBigInteger('course_id')->nullable();
            $table->unsignedBigInteger('schedule_id')->nullable();
            $table->string('course_name');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2);
            $table->string('venue')->nullable();
            $table->timestamps();
        });

        Schema::create('booking_invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('booking_master')->onDelete('cascade');
            $table->string('company_name');
            $table->string('finance_name');
            $table->string('finance_email');
            $table->string('finance_phone')->nullable();
            $table->string('address');
            $table->string('address2')->nullable();
            $table->string('city');
            $table->string('postcode');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_invoices');
        Schema::dropIfExists('booking_items');
        Schema::dropIfExists('booking_master');
    }
};
