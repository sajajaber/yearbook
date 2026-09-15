<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('review_feedback', function (Blueprint $table) {
            $table->id();
            $table->string('reviewable_type');
            $table->unsignedBigInteger('reviewable_id');
            $table->foreignId('reviewer_id')->constrained('users')->cascadeOnDelete();
            $table->text('message');
            $table->enum('status', ['open', 'resolved'])->default('open');
            $table->timestamps();

            $table->index(['reviewable_type', 'reviewable_id']);
            $table->index(['status', 'reviewer_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('review_feedback');
    }
};
