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
        Schema::table('user', function (Blueprint $table) {
            if (!Schema::hasColumn('user', 'is_admin_eligible')) {
                $table->tinyInteger('is_admin_eligible')->default(0)->after('password');
            }
        });

        Schema::table('user_details', function (Blueprint $table) {
            if (!Schema::hasColumn('user_details', 'first_name')) {
                $table->string('first_name')->nullable()->after('user_id');
            }
            if (!Schema::hasColumn('user_details', 'last_name')) {
                $table->string('last_name')->nullable()->after('first_name');
            }
            if (!Schema::hasColumn('user_details', 'whatsapp')) {
                $table->string('whatsapp')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('user_details', 'address')) {
                $table->text('address')->nullable()->after('whatsapp');
            }
            if (!Schema::hasColumn('user_details', 'bio')) {
                $table->text('bio')->nullable()->after('address');
            }
            if (!Schema::hasColumn('user_details', 'status')) {
                $table->string('status')->default('Active')->after('bio');
            }
            if (!Schema::hasColumn('user_details', 'role')) {
                $table->string('role')->nullable()->after('status');
            }
            if (!Schema::hasColumn('user_details', 'calendar_link')) {
                $table->string('calendar_link')->nullable()->after('role');
            }
            if (!Schema::hasColumn('user_details', 'short_order')) {
                $table->integer('short_order')->default(0)->after('calendar_link');
            }
            if (!Schema::hasColumn('user_details', 'show_admin_profile')) {
                $table->tinyInteger('show_admin_profile')->default(0)->after('short_order');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user', function (Blueprint $table) {
            if (Schema::hasColumn('user', 'is_admin_eligible')) {
                $table->dropColumn('is_admin_eligible');
            }
        });

        Schema::table('user_details', function (Blueprint $table) {
            $columns = [
                'first_name',
                'last_name',
                'whatsapp',
                'address',
                'bio',
                'status',
                'role',
                'calendar_link',
                'short_order',
                'show_admin_profile'
            ];
            foreach ($columns as $column) {
                if (Schema::hasColumn('user_details', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
