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
        Schema::table('venue', function (Blueprint $table) {
            $table->string('venue_image_second', 255)->nullable()->after('venue_image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('venue', function (Blueprint $table) {
            $table->dropColumn('venue_image_second');
        });
    }
};
