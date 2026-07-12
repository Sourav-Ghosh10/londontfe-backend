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
        Schema::create('schedule_data', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->string('type')->nullable();
            $table->string('downtype')->nullable();
            $table->text('querytxt')->nullable();
            $table->string('unqid')->unique();
            $table->integer('first_no')->nullable();
            $table->integer('second_no')->nullable();
            $table->string('operator')->nullable();
            $table->tinyInteger('status')->default(0);
            $table->timestamp('expire_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule_data');
    }
};
