<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('wool_batches', function (Blueprint $table) {
            $table->id();
            $table->string('batch_number')->unique();
            $table->decimal('weight', 10, 2);
            $table->decimal('quality', 5, 2);
            $table->decimal('price_per_kg', 10, 2);
            $table->string('status')->default('pending');
            $table->foreignId('farmer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('collection_center_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('processing_center_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('distributor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wool_batches');
    }
}; 