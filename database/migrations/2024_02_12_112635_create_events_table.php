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
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId('fiscal_year_id')
                ->constrained('fiscal_years')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table
                ->foreignId('company_id')
                ->constrained('companies')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('title');
            $table->string('content');
            $table->string('date');
            $table->string('time')->nullable();
            $table->string('assignTo')->nullable();
            $table->enum('status', ['done', 'notDone', 'postponed', 'canceled'])->default('notDone');


            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};
