<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('currency', function (Blueprint $table) {
            $table->id();
            $table->string('currency_code', 10)->unique();
            $table->decimal('exchange_rate', 10, 6)->default(1.0);
            $table->boolean('is_base')->default(false);
            $table->timestamps();
        });

        // Insert default data
        DB::table('currency')->insert([
            ['currency_code' => 'GBP', 'exchange_rate' => 1.000000, 'is_base' => true, 'created_at' => now(), 'updated_at' => now()],
            ['currency_code' => 'USD', 'exchange_rate' => 1.240000, 'is_base' => false, 'created_at' => now(), 'updated_at' => now()]
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('currency');
    }
};
