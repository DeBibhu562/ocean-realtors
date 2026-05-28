<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->foreignId('agent_id')->nullable()->after('user_id')->constrained('agents')->nullOnDelete();
        });

        $defaultAgentId = DB::table('agents')->where('slug', 'ocean-realtors-team')->value('id');

        if (! $defaultAgentId && Schema::hasTable('agents')) {
            $defaultAgentId = DB::table('agents')->insertGetId([
                'name' => 'Ocean Realtors Team',
                'slug' => 'ocean-realtors-team',
                'phone' => '+911124000000',
                'whatsapp_phone' => '+911124000000',
                'designation' => 'Senior Property Consultant',
                'city' => 'Delhi',
                'rating' => 4.8,
                'reviews_count' => 127,
                'experience_years' => 10,
                'languages' => json_encode(['English', 'Hindi']),
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        if ($defaultAgentId) {
            DB::table('properties')->whereNull('agent_id')->update(['agent_id' => $defaultAgentId]);
        }
    }

    public function down(): void
    {
        Schema::table('properties', function (Blueprint $table) {
            $table->dropForeign(['agent_id']);
            $table->dropColumn('agent_id');
        });
    }
};
