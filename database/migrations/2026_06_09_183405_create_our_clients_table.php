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
        if (!Schema::hasTable('our_clients')) {
            Schema::create('our_clients', function (Blueprint $table) {
                $table->id();
                $table->string('logo')->nullable();
                $table->string('alt_text');
                $table->integer('order')->default(0);
                $table->tinyInteger('status')->default(1);
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Don't drop existing table unless needed, or drop it if it was created.
        Schema::dropIfExists('our_clients');
    }
};
