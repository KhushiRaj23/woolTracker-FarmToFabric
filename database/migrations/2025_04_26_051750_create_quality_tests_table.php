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
        Schema::create('quality_tests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('wool_batch_id')->constrained()->onDelete('cascade');
            $table->dateTime('test_date');
            $table->decimal('cleanliness_score', 5, 2)->comment('Score from 1-10');
            $table->decimal('strength_score', 5, 2)->comment('Score from 1-10');
            $table->decimal('uniformity_score', 5, 2)->comment('Score from 1-10');
            $table->decimal('color_score', 5, 2)->comment('Score from 1-10');
            $table->decimal('overall_score', 5, 2)->comment('Average of all scores');
            $table->text('notes')->nullable();
            $table->foreignId('tested_by')->constrained('users')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('quality_tests');
    }
};
