<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('price_drop_alerts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('property_id')->constrained()->cascadeOnDelete();
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('name')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->unique(['property_id', 'email']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('price_drop_alerts');
    }
};
