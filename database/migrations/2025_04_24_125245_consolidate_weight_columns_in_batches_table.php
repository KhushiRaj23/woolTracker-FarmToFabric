<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // First, check if both columns exist
        if (Schema::hasColumn('batches', 'weight') && Schema::hasColumn('batches', 'wool_weight')) {
            // If both exist, copy data from weight to wool_weight where wool_weight is null
            DB::table('batches')
                ->whereNull('wool_weight')
                ->update(['wool_weight' => DB::raw('weight')]);

            // Then drop the weight column
            Schema::table('batches', function (Blueprint $table) {
                $table->dropColumn('weight');
            });
        }
        // If only weight exists, rename it to wool_weight
        else if (Schema::hasColumn('batches', 'weight') && !Schema::hasColumn('batches', 'wool_weight')) {
            Schema::table('batches', function (Blueprint $table) {
                $table->renameColumn('weight', 'wool_weight');
            });
        }
        // If neither exists, create wool_weight
        else if (!Schema::hasColumn('batches', 'weight') && !Schema::hasColumn('batches', 'wool_weight')) {
            Schema::table('batches', function (Blueprint $table) {
                $table->decimal('wool_weight', 10, 2)->nullable();
            });
        }
    }

    public function down(): void
    {
        // We'll keep wool_weight as the standard column name
        // No need to revert as this is a cleanup migration
    }
}; 