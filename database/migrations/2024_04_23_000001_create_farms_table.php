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
        Schema::create('farms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('farmer_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('location');
            $table->decimal('size', 10, 2)->comment('Farm size in acres');
            $table->string('contact_person');
            $table->string('contact_number', 20);
            $table->string('email');
            $table->text('description')->nullable();
            $table->string('certification_status')->default('pending');
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('farms');
    }
}; 