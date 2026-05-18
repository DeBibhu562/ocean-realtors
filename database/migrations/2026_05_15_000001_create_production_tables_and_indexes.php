<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Newsletter Table
        Schema::create('newsletter_subscribers', function (Blueprint $col) {
            $col->id();
            $col->string('email')->unique();
            $col->timestamps();
        });

        // 2. Add Performance Indexes to Properties Table
        try {
            Schema::table('properties', function (Blueprint $table) {
                $table->index(['city', 'type', 'status']);
                $table->index('price');
                $table->index('bedrooms');
                $table->index('is_featured');
            });
        } catch (\Exception $e) {
            // Ignore if indexes already exist
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('newsletter_subscribers');
        
        Schema::table('properties', function (Blueprint $table) {
            $table->dropIndex(['city', 'type', 'status']);
            $table->dropIndex(['price']);
            $table->dropIndex(['bedrooms']);
            $table->dropIndex(['is_featured']);
        });
    }
};
