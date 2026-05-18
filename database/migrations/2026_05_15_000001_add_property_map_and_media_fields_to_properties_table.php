<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->decimal('latitude', 10, 7)->nullable()->after('city');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            $table->string('virtual_tour_url')->nullable()->after('photos');
            $table->string('floor_plan_image')->nullable()->after('virtual_tour_url');
            $table->unsignedBigInteger('views_count')->default(0)->after('floor_plan_image');
        });
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn([
                'latitude',
                'longitude',
                'virtual_tour_url',
                'floor_plan_image',
                'views_count',
            ]);
        });
    }
};
