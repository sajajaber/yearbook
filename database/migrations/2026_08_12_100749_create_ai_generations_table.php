<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ai_generations', function (Blueprint $table) {
            $table->id();
            $table->string('content_type');
            $table->unsignedBigInteger('source_record_id')->nullable();
            $table->string('source_record_type')->nullable();
            $table->string('prompt_version')->default('v1');
            $table->longText('generated_text');
            $table->longText('reviewed_text')->nullable();
            $table->foreignId('reviewer_id')->nullable()->constrained('users');
            $table->enum('status', ['pending_review', 'approved', 'rejected', 'edited'])->default('pending_review');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_generations');
    }
};