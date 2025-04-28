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
        Schema::create('batches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farm_id')->constrained()->onDelete('cascade');
            $table->foreignId('processor_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('distributor_id')->nullable()->constrained()->onDelete('set null');
            $table->string('batch_number')->unique();
            $table->date('shearing_date');
            $table->decimal('wool_weight', 10, 2);
            $table->string('wool_type');
            $table->string('status')->default('pending');
            $table->string('quality_grade')->nullable();
            $table->timestamp('processing_date')->nullable();
            $table->timestamp('distribution_date')->nullable();
            $table->timestamp('completed_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batches');
    }
}; 