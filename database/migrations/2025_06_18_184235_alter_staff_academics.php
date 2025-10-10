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
        Schema::table('staff_academics', function (Blueprint $table) {
            $table->renameColumn('professional_certification_date', 'professional_certification_date_start')->nullable()->change();
        });

        Schema::table('staff_academics', function (Blueprint $table) {
            $table->date('professional_certification_date_end')->nullable()->after('professional_certification_date_start');
            $table->string('certificate_file')->nullable()->after('professional_certification_date_end');
            $table->string('certification_professional')->nullable()->after('certificate_file');
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
