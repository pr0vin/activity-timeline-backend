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
        Schema::table('fiscal_years', function (Blueprint $table) {
            //
            $table->date('ad_startDate')->nullable();
            $table->date('ad_endDate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fiscal_years', function (Blueprint $table) {
            //
            $table->dropColumn('ad_startDate');
            $table->dropColumn('ad_endDate');
        });
    }
};
