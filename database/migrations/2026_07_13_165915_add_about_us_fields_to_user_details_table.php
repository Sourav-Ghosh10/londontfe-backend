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
        Schema::table('user_details', function (Blueprint $table) {
            $table->longText('about_us_text')->nullable();
            $table->string('about_us_image')->nullable();
            $table->string('company_logo')->nullable();
            $table->string('about_us_user_type')->nullable();
            $table->boolean('is_on_about_us')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_details', function (Blueprint $table) {
            $table->dropColumn([
                'about_us_text',
                'about_us_image',
                'company_logo',
                'about_us_user_type',
                'is_on_about_us'
            ]);
        });
    }
};

