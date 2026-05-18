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
        Schema::table('properties', function (Blueprint $table) {
            // Category & Basic Type
            $table->string('category')->default('Residential')->after('id'); // Residential / Commercial
            $table->string('listing_type')->default('Rent')->after('category'); // Rent / Sell / PG
            $table->string('bhk')->nullable()->after('type');
            $table->string('society_name')->nullable()->after('bhk');
            
            // Areas
            $table->decimal('built_up_area', 10, 2)->nullable()->after('size');
            $table->decimal('carpet_area', 10, 2)->nullable()->after('built_up_area');
            
            // Details
            $table->integer('age_of_property')->default(0)->after('carpet_area');
            $table->integer('balconies')->default(0)->after('bathrooms');
            $table->string('furnish_type')->default('Unfurnished')->after('balconies');
            $table->json('amenities')->nullable()->after('furnish_type');
            
            // Parking
            $table->string('covered_parking')->default('0')->after('garage');
            $table->string('open_parking')->default('0')->after('covered_parking');
            
            // Tenants & Availability
            $table->string('tenant_type')->nullable()->after('is_featured');
            $table->string('bachelor_preference')->nullable()->after('tenant_type');
            $table->boolean('pet_friendly')->default(false)->after('bachelor_preference');
            $table->date('available_from')->nullable()->after('pet_friendly');
            
            // Financials
            $table->decimal('maintenance_charges', 10, 2)->nullable()->after('price');
            $table->string('maintenance_type')->default('Separate')->after('maintenance_charges');
            $table->string('security_deposit')->nullable()->after('maintenance_type');
            $table->string('lock_in_period')->nullable()->after('security_deposit');
            $table->string('brokerage_charge')->nullable()->after('lock_in_period');
            $table->boolean('brokerage_negotiable')->default(false)->after('brokerage_charge');
            $table->string('parking_charges_type')->default('Separate')->after('brokerage_negotiable');
            $table->string('painting_charges')->nullable()->after('parking_charges_type');
            
            // Floors & Facing
            $table->integer('floor_no')->nullable()->after('painting_charges');
            $table->integer('total_floors')->nullable()->after('floor_no');
            $table->string('facing')->nullable()->after('total_floors');
            
            // Misc
            $table->boolean('servant_room')->default(false)->after('facing');
            $table->string('rera_id')->nullable()->after('servant_room');
            $table->json('highlights')->nullable()->after('rera_id');
            $table->json('photos')->nullable()->after('image');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropColumn([
                'category', 'listing_type', 'bhk', 'society_name', 'built_up_area', 'carpet_area',
                'age_of_property', 'balconies', 'furnish_type', 'amenities', 'covered_parking',
                'open_parking', 'tenant_type', 'bachelor_preference', 'pet_friendly', 'available_from',
                'maintenance_charges', 'maintenance_type', 'security_deposit', 'lock_in_period',
                'brokerage_charge', 'brokerage_negotiable', 'parking_charges_type', 'painting_charges',
                'floor_no', 'total_floors', 'facing', 'servant_room', 'rera_id', 'highlights', 'photos'
            ]);
        });
    }
};
