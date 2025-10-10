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
        Schema::table('staff_leaves', function (Blueprint $table) {
            $table->float('mc_total')->default(0)->after('leave_balance');
            $table->float('mc_taken')->default(0)->after('mc_total');
            $table->float('mc_balance')->default(0)->after('leave_balance');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
