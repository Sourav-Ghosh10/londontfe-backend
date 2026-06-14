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
        if (!Schema::hasTable('user')) {
            Schema::create('user', function (Blueprint $table) {
                $table->integer('id', true);
                $table->string('username', 100);
                $table->string('password', 100);
                $table->tinyInteger('is_admin_eligible')->default(0);
                $table->string('fname', 255);
                $table->string('lname', 255)->nullable();
                $table->text('address')->nullable();
                $table->string('email', 100);
                $table->string('whats', 255)->nullable();
                $table->string('calender_link', 255);
                $table->integer('user_type');
                $table->enum('status', ['0', '1'])->default('1');
                $table->dateTime('create_date');
                $table->dateTime('last_update')->nullable();
                $table->string('title', 255)->nullable();
                $table->enum('changed_status', ['0', '1'])->default('0')->comment('0 => Status unchanged, 1 => Status changed');
                $table->string('conversation_message', 255)->nullable();
                $table->date('conversation_date')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user');
    }
};
