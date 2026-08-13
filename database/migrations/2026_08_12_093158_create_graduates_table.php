<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('graduates', function (Blueprint $table) {
            $table->id();
            $table->string('student_reference')->nullable();
            $table->string('name');
            $table->foreignId('major_id')->constrained('majors');
            $table->foreignId('campus_id')->constrained('campuses');
            $table->foreignId('graduation_id')->constrained('graduations');
            $table->enum('consent_status', ['pending', 'granted', 'declined'])->default('pending');
            $table->enum('publish_status', ['draft', 'reviewed', 'approved', 'published', 'archived'])->default('draft');
            $table->foreignId('portrait_media_id')->nullable()->constrained('media');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('graduates');
    }
};