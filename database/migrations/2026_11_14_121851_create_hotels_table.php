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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique(); // ex: abidjan, bamako, dakar
            $table->string('name');
            $table->string('city');
            $table->string('country');
            $table->string('badge')->nullable();
            $table->string('address');
            $table->text('description')->nullable();
            $table->decimal('min_price', 12, 2)->default(0);
            $table->string('image_url')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
