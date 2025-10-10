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
        Schema::create('staff_position_histories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('staff_id')->index();
            $table->unsignedBigInteger('branch_position_id')->index();
            $table->unsignedBigInteger('branch_id')->index();
            $table->timestamps();

            $table->foreign('staff_id')->references('id')->on('staffs');
            $table->foreign('branch_position_id')->references('id')->on('branch_positions');
            $table->foreign('branch_id')->references('id')->on('branches');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff_position_histories');
    }
};
