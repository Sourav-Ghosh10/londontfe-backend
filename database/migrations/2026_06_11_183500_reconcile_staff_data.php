<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $roleToType = [
            'Marketing' => 5,
            'Sales' => 6,
            'Course Editor' => 7,
            'Operation' => 9,
            'superadmin' => 11,
        ];

        foreach ($roleToType as $role => $type) {
            DB::table('user')
                ->join('user_details', 'user.id', '=', 'user_details.user_id')
                ->where('user.user_type', 3)
                ->where('user_details.role', $role)
                ->update(['user.user_type' => $type]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $roleToType = [
            'Marketing' => 5,
            'Sales' => 6,
            'Course Editor' => 7,
            'Operation' => 9,
            'superadmin' => 11,
        ];

        foreach ($roleToType as $role => $type) {
            DB::table('user')
                ->join('user_details', 'user.id', '=', 'user_details.user_id')
                ->where('user.user_type', $type)
                ->where('user_details.role', $role)
                ->update(['user.user_type' => 3]);
        }
    }
};
