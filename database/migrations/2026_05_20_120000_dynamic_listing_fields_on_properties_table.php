<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->string('user_role', 32)->default('Broker')->after('user_id');
            $table->string('listing_status', 32)->default('Active')->after('status');
            $table->unsignedTinyInteger('completion_score')->default(0)->after('listing_status');

            $table->string('locality')->nullable()->after('city');
            $table->string('landmark')->nullable()->after('address');

            $table->string('commercial_washrooms', 32)->nullable()->after('furnish_type');
            $table->unsignedSmallInteger('workstations')->nullable()->after('commercial_washrooms');
            $table->unsignedSmallInteger('cabins')->nullable()->after('workstations');
            $table->string('commercial_pantry', 32)->nullable()->after('cabins');
            $table->string('zoning_type', 64)->nullable()->after('commercial_pantry');

            $table->string('pg_sharing_type', 32)->nullable()->after('zoning_type');
            $table->string('pg_tenant_gender', 32)->nullable()->after('pg_sharing_type');
            $table->boolean('pg_food_included')->default(false)->after('pg_tenant_gender');
            $table->string('curfew_time', 64)->nullable()->after('pg_food_included');

            $table->decimal('booking_amount', 15, 2)->nullable()->after('price');
            $table->boolean('price_negotiable')->default(false)->after('booking_amount');
            $table->string('ownership_type', 64)->nullable()->after('price_negotiable');
            $table->decimal('food_charges', 12, 2)->nullable()->after('maintenance_charges');

            $table->json('furnishing_items')->nullable()->after('amenities');
        });

        if (Schema::hasColumn('properties', 'listing_status')) {
            DB::table('properties')->whereNull('listing_status')->update(['listing_status' => 'Active']);
        }
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn([
                'user_role',
                'listing_status',
                'completion_score',
                'locality',
                'landmark',
                'commercial_washrooms',
                'workstations',
                'cabins',
                'commercial_pantry',
                'zoning_type',
                'pg_sharing_type',
                'pg_tenant_gender',
                'pg_food_included',
                'curfew_time',
                'booking_amount',
                'price_negotiable',
                'ownership_type',
                'food_charges',
                'furnishing_items',
            ]);
        });
    }
};
