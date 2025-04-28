<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProcessorsTable extends Migration
{
    public function up(): void
    {
        Schema::create('processors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('company_name');
            $table->text('address');
            $table->string('phone');
            $table->integer('capacity');
            $table->string('specialization');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('processors');
    }
} 