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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('price', 15, 2);
            $table->string('status'); // For Rent / For Sale
            $table->string('type'); // Apartment, House, Villa, etc.
            $table->string('address');
            $table->string('city');
            $table->string('image')->nullable();
            $table->integer('bedrooms')->default(0);
            $table->integer('bathrooms')->default(0);
            $table->integer('garage')->default(0);
            $table->integer('size')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');
    }
};
