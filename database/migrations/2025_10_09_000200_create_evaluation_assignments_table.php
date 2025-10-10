<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluation_assignments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('evaluation_period_id');
            $table->unsignedBigInteger('staff_id'); // PYD
            $table->unsignedBigInteger('ppp_id')->nullable(); // Penilai Pertama
            $table->unsignedBigInteger('ppk_id')->nullable(); // Penilai Kedua
            $table->date('effective_from')->nullable();
            $table->date('effective_to')->nullable();
            $table->timestamps();

            $table->foreign('evaluation_period_id')->references('id')->on('evaluation_periods')->cascadeOnDelete();
            $table->foreign('staff_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('ppp_id')->references('id')->on('users')->nullOnDelete();
            $table->foreign('ppk_id')->references('id')->on('users')->nullOnDelete();

            $table->index(['evaluation_period_id', 'staff_id']);
            $table->unique(['evaluation_period_id','staff_id','effective_from'], 'uniq_period_staff_from');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluation_assignments');
    }
};
