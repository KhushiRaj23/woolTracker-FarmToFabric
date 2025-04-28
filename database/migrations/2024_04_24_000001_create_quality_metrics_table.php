<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quality_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('batch_id')->constrained()->onDelete('cascade');
            $table->foreignId('stage_id')->constrained('processing_stages')->onDelete('cascade');
            $table->decimal('fiber_length', 8, 2)->nullable(); // in mm
            $table->decimal('fiber_strength', 8, 2)->nullable(); // in g/tex
            $table->decimal('moisture_content', 8, 2)->nullable(); // in percentage
            $table->decimal('trash_content', 8, 2)->nullable(); // in percentage
            $table->decimal('micronaire', 8, 2)->nullable(); // fiber fineness
            $table->decimal('uniformity_index', 8, 2)->nullable(); // in percentage
            $table->text('visual_inspection_notes')->nullable();
            $table->string('overall_grade')->nullable(); // A+, A, B+, B, C
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quality_metrics');
    }
}; 