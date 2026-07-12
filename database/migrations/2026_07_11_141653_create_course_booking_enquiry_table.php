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
        Schema::create('course_booking_enquiry', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->nullable();
            $table->string('phone_code')->nullable();
            $table->string('phone_no')->nullable();
            $table->string('company_name')->nullable();
            $table->string('job_title')->nullable();
            $table->string('more_info')->nullable();
            $table->string('where')->nullable();
            $table->integer('course_id')->nullable();
            $table->integer('user_log_id')->nullable();
            $table->integer('status')->nullable();
            $table->string('course_name_search')->nullable();
            $table->string('course_date')->nullable();
            $table->string('course_month')->nullable();
            $table->string('course_venue')->nullable();
            $table->string('course_category')->nullable();
            $table->string('booking_status')->nullable();
            $table->string('course_type')->nullable();
            $table->timestamp('request_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_booking_enquiry');
    }
};
