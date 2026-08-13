<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('academic_year_id')->constrained('academic_years');
            $table->foreignId('category_id')->constrained('event_categories');
            $table->string('title');
            $table->date('event_date');
            $table->text('description')->nullable();
            $table->string('location')->nullable();
            $table->enum('status', ['draft', 'reviewed', 'approved', 'published', 'archived'])->default('draft');
            $table->boolean('featured')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('events');
    }
};