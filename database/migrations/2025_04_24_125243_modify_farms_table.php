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
        Schema::table('farms', function (Blueprint $table) {
            // First, drop the existing size column if it exists
            if (Schema::hasColumn('farms', 'size')) {
                $table->dropColumn('size');
            }
            
            // Add the size column with proper configuration
            $table->decimal('size', 10, 2)->default(0)->comment('Farm size in acres');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('farms', function (Blueprint $table) {
            $table->dropColumn('size');
        });
    }
};
