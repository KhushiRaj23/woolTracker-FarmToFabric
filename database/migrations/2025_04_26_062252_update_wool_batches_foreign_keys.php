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
        Schema::table('wool_batches', function (Blueprint $table) {
            // Drop existing foreign keys
            $table->dropForeign(['farmer_id']);
            $table->dropForeign(['distributor_id']);
            
            // Add new foreign keys pointing to the correct tables
            $table->foreign('farmer_id')
                ->references('id')
                ->on('farmers')
                ->onDelete('set null');
                
            $table->foreign('distributor_id')
                ->references('id')
                ->on('distributors')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wool_batches', function (Blueprint $table) {
            // Drop the new foreign keys
            $table->dropForeign(['farmer_id']);
            $table->dropForeign(['distributor_id']);
            
            // Restore original foreign keys
            $table->foreign('farmer_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
                
            $table->foreign('distributor_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }
};
