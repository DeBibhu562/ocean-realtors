<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign(['property_id']);
        });

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE leads MODIFY property_id BIGINT UNSIGNED NULL');
            DB::statement('ALTER TABLE leads ADD CONSTRAINT leads_property_id_foreign FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE SET NULL');
        } else {
            Schema::table('leads', function (Blueprint $table) {
                $table->unsignedBigInteger('property_id')->nullable()->change();
                $table->foreign('property_id')->references('id')->on('properties')->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropForeign(['property_id']);
        });

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE leads MODIFY property_id BIGINT UNSIGNED NOT NULL');
            DB::statement('ALTER TABLE leads ADD CONSTRAINT leads_property_id_foreign FOREIGN KEY (property_id) REFERENCES properties(id) ON DELETE CASCADE');
        }
    }
};
