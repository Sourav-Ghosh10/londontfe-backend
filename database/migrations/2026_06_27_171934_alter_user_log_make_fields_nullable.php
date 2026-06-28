<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE user_log 
            MODIFY log_type varchar(20) DEFAULT NULL,
            MODIFY name varchar(50) DEFAULT NULL,
            MODIFY email varchar(50) DEFAULT NULL,
            MODIFY phone_no varchar(15) DEFAULT NULL,
            MODIFY ip varchar(20) DEFAULT NULL,
            MODIFY country varchar(25) DEFAULT NULL,
            MODIFY course_url text DEFAULT NULL,
            MODIFY coursename text DEFAULT NULL,
            MODIFY categoryname text DEFAULT NULL,
            MODIFY coursevenue varchar(20) DEFAULT NULL,
            MODIFY quantity int(11) DEFAULT NULL,
            MODIFY coursestartdt varchar(20) DEFAULT NULL,
            MODIFY currency varchar(11) DEFAULT NULL,
            MODIFY price varchar(11) DEFAULT NULL,
            MODIFY coupon varchar(50) DEFAULT NULL,
            MODIFY status varchar(30) DEFAULT NULL,
            MODIFY created_dt datetime DEFAULT NULL,
            MODIFY dyc_status enum('1','0') DEFAULT NULL,
            MODIFY crm_ids text DEFAULT NULL,
            MODIFY crm_update_dt datetime DEFAULT NULL
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverting this complex alter statement is not typically required
        // unless you want to put all NOT NULL constraints back
    }
};
