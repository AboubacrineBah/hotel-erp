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
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->string('invoice_number')->unique(); // ex: FAC-2026-0891
            $table->foreignId('booking_id')->constrained()->onDelete('cascade');
            $table->decimal('amount_ht', 12, 2);
            $table->decimal('tva_rate', 5, 2)->default(18.00);
            $table->decimal('tva_amount', 12, 2);
            $table->decimal('amount_ttc', 12, 2);
            $table->enum('payment_status', ['Payée', 'En attente', 'Annulée'])->default('Payée');
            $table->timestamp('issued_at')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
