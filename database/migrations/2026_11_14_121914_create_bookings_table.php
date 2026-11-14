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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('reference')->unique(); // ex: AZL-8410
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
            $table->foreignId('room_id')->constrained()->onDelete('cascade');

            // Informations Client CRM (même si réservation visiteur anonyme)
            $table->string('client_name');
            $table->string('client_email');
            $table->string('client_phone');

            // Détails du séjour
            $table->date('check_in');
            $table->date('check_out');
            $table->unsignedInteger('nights');
            $table->string('guests_summary');
            $table->string('extra_option_name')->nullable();
            $table->decimal('extra_option_price', 12, 2)->default(0);
            $table->decimal('total_ttc', 12, 2);

            $table->enum('status', ['Confirmée', 'Check-in Prévu', 'Check-in Effectué', 'Terminée', 'Annulée'])
                ->default('Confirmée');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
