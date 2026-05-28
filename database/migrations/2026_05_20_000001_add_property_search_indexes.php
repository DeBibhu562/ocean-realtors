<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $this->normalizeListingTypes();

        if (Schema::hasTable('properties')) {
            $this->addPropertiesIndexes();
        }

        if (Schema::hasTable('reviews')) {
            $this->safeIndex('reviews', ['email', 'created_at'], 'reviews_email_created_at_index');
        }

        if (Schema::hasTable('leads') && Schema::hasColumn('leads', 'status')) {
            $this->safeIndex('leads', 'status', 'leads_status_index');
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('properties')) {
            $this->dropPropertiesIndexes();
        }

        $this->dropIndexIfExists('reviews', 'reviews_email_created_at_index');
        $this->dropIndexIfExists('leads', 'leads_status_index');
    }

    protected function normalizeListingTypes(): void
    {
        if (! Schema::hasTable('properties') || ! Schema::hasColumn('properties', 'listing_type')) {
            return;
        }

        DB::table('properties')
            ->whereRaw('LOWER(listing_type) IN (?, ?)', ['rent', 'for rent'])
            ->update(['listing_type' => 'rent']);

        DB::table('properties')
            ->whereRaw('LOWER(listing_type) IN (?, ?, ?)', ['sell', 'sale', 'for sale'])
            ->update(['listing_type' => 'sale']);

        DB::table('properties')
            ->whereRaw('LOWER(listing_type) LIKE ?', ['%pg%'])
            ->update(['listing_type' => 'pg']);
    }

    protected function addPropertiesIndexes(): void
    {
        $this->safeIndex('properties', 'listing_type', 'properties_listing_type_index');
        $this->safeIndex('properties', 'created_at', 'properties_created_at_index');
        $this->safeIndex('properties', ['is_featured', 'created_at'], 'properties_featured_created_at_index');
        $this->safeIndex('properties', ['city', 'listing_type', 'created_at'], 'properties_city_listing_created_index');
        $this->safeIndex('properties', 'bathrooms', 'properties_bathrooms_index');
        $this->safeIndex('properties', ['agent_id', 'created_at'], 'properties_agent_created_at_index');

        if (Schema::getConnection()->getDriverName() === 'mysql') {
            $this->addFullTextIndex();
        }
    }

    protected function addFullTextIndex(): void
    {
        $indexName = 'properties_search_fulltext';

        if ($this->indexExists('properties', $indexName)) {
            return;
        }

        try {
            DB::statement(
                'ALTER TABLE properties ADD FULLTEXT '.$indexName.' (title, address, society_name)'
            );
        } catch (\Throwable) {
            // Index may already exist under another name or engine limitations
        }
    }

    protected function dropPropertiesIndexes(): void
    {
        if (Schema::getConnection()->getDriverName() === 'mysql') {
            try {
                DB::statement('ALTER TABLE properties DROP INDEX properties_search_fulltext');
            } catch (\Throwable) {
                //
            }
        }

        foreach ([
            'properties_listing_type_index',
            'properties_created_at_index',
            'properties_featured_created_at_index',
            'properties_city_listing_created_index',
            'properties_bathrooms_index',
            'properties_agent_created_at_index',
        ] as $name) {
            $this->dropIndexIfExists('properties', $name);
        }
    }

    /**
     * @param  string|list<string>  $columns
     */
    protected function safeIndex(string $table, string|array $columns, ?string $name = null): void
    {
        if ($name !== null && $this->indexExists($table, $name)) {
            return;
        }

        try {
            Schema::table($table, function (Blueprint $blueprint) use ($columns, $name) {
                if ($name !== null) {
                    $blueprint->index($columns, $name);
                } else {
                    $blueprint->index($columns);
                }
            });
        } catch (\Throwable) {
            //
        }
    }

    protected function dropIndexIfExists(string $table, string $name): void
    {
        if (! $this->indexExists($table, $name)) {
            return;
        }

        try {
            Schema::table($table, function (Blueprint $blueprint) use ($name) {
                $blueprint->dropIndex($name);
            });
        } catch (\Throwable) {
            //
        }
    }

    protected function indexExists(string $table, string $name): bool
    {
        try {
            $indexes = Schema::getIndexes($table);

            foreach ($indexes as $index) {
                if (($index['name'] ?? '') === $name) {
                    return true;
                }
            }
        } catch (\Throwable) {
            //
        }

        return false;
    }
};
