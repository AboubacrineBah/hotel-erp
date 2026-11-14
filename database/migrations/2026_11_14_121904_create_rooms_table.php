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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('size')->nullable(); // ex: 32 m²
            $table->unsignedTinyInteger('capacity')->default(2);
            $table->decimal('price_per_night', 12, 2);
            $table->unsignedInteger('available_count')->default(1);
            $table->json('perks')->nullable(); // Avantages (Wifi, Petit-déj, etc.)
            $table->string('image_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
