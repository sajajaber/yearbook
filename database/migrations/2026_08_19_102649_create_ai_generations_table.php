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

            // What kind of content this is: 'event_summary' | 'graduate_biography'
            $table->string('content_type');

            $table->unsignedBigInteger('source_record_id');
            $table->string('source_record_type'); // 'event' | 'graduate'

            $table->string('prompt_version')->default('v1');

            $table->longText('generated_text');
            $table->longText('reviewed_text')->nullable();

            $table->foreignId('reviewer_id')->nullable()->constrained('users')->nullOnDelete();

            // pending_review | approved | edited | rejected
            $table->string('status')->default('pending_review');

            $table->timestamps();

            $table->index(['source_record_type', 'source_record_id']);
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ai_generations');
    }
};
