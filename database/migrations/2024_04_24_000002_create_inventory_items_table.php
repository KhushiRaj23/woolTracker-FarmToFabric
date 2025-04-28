<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventory_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('processor_id')->constrained()->onDelete('cascade');
            $table->foreignId('batch_id')->constrained()->onDelete('cascade');
            $table->string('item_code')->unique();
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('quantity', 10, 2); // in kg
            $table->string('unit')->default('kg');
            $table->decimal('unit_price', 10, 2);
            $table->string('quality_grade'); // A+, A, B+, B, C
            $table->string('status')->default('available'); // available, reserved, sold
            $table->date('production_date');
            $table->date('expiry_date')->nullable();
            $table->text('storage_location')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventory_items');
    }
}; 