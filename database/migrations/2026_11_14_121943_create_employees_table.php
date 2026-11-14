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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            $table->string('matricule')->unique(); // ex: EMP-01
            $table->foreignId('hotel_id')->nullable()->constrained()->nullOnDelete();
            $table->string('full_name');
            $table->string('job_title');
            $table->string('department');
            $table->string('shift_hours')->nullable();
            $table->decimal('monthly_salary', 12, 2);
            $table->enum('presence_status', ['En poste', 'En congé', 'Repos'])->default('En poste');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
