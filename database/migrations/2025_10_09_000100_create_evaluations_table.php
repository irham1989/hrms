<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('evaluation_period_id');
            $table->unsignedBigInteger('pyd_id'); // owner = PYD (staf)
            // Minimal fields (boleh tambah ikut keperluan)
            $table->text('kegiatan_sumbangan')->nullable();
            $table->text('latihan_dihadiri')->nullable();
            $table->text('latihan_diperlukan')->nullable();
            $table->date('tempoh_penilaian_ppp_mula')->nullable();
            $table->date('tempoh_penilaian_ppp_tamat')->nullable();
            $table->text('ulasan_keseluruhan_ppp')->nullable();
            $table->string('kemajuan_kerjaya_ppp')->nullable();
            $table->date('tempoh_penilaian_ppk_mula')->nullable();
            $table->date('tempoh_penilaian_ppk_tamat')->nullable();
            $table->text('ulasan_keseluruhan_ppk')->nullable();

            $table->string('status', 50)->default('draf_pyd'); // workflow ringkas
            $table->timestamps();

            $table->foreign('evaluation_period_id')->references('id')->on('evaluation_periods')->cascadeOnDelete();
            $table->foreign('pyd_id')->references('id')->on('users')->cascadeOnDelete();
            $table->index(['evaluation_period_id','pyd_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluations');
    }
};
